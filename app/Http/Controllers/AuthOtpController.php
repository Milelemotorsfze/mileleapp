<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\VerificationCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AuthOtpController extends Controller
{
    // Return View of OTP Login Page
    public function login()
    {
        return view('auth.otp-login');
    }

    // Generate OTP
    public function generate(Request $request)
    {
        # Validate Data
        $request->validate([
            'email' => 'required|exists:users,email'
        ]);

        # Generate An OTP
        $verificationCode = $this->generateOtp($request->email);
        $message = "Your OTP To Login is Send Successfully ";
        // $message = "Your OTP To Login is - ".$verificationCode->otp;
        # Return With OTP

        // $renderedData = view('email')->render();
        // $data['id'] = $user->id;
        $data['email'] = $request->email;
        $data['name'] = 'Hello,';
        $data['otp'] = $verificationCode->otp;
        $template['from'] = 'no-reply@milele.com';
        $template['from_name'] = 'Milele Matrix';
        $subject = 'Milele Matrix Login OTP Code';
        Mail::send(
                "auth.otpemail",
                ["data"=>$data] ,
                function($msg) use ($data,$template,$subject) {
                    $msg->to($data['email'], $data['name'])
                        ->from($template['from'],$template['from_name'])
                        ->subject($subject);
                        // ->attachData($renderedData, 'name_of_attachment');
                }
            );

        return redirect()->route('otp.verification', ['user_id' => $verificationCode->user_id])->with('success',  $message);
    }

    public function generateOtp($email)
    {
        $user = User::where('email', $email)->first();

        # User Does not Have Any Existing OTP
        $verificationCode = VerificationCode::where('user_id', $user->id)->latest()->first();

        $now = Carbon::now();

        if($verificationCode && $now->isBefore($verificationCode->expire_at)){
            return $verificationCode;
        }

        // Create a New OTP
        return VerificationCode::create([
            'user_id' => $user->id,
            'otp' => rand(123456, 999999),
            'expire_at' => Carbon::now()->addMinutes(10)
        ]);
    }

    public function verification($user_id)
    {
        return view('auth.otp-verification')->with([
            'user_id' => $user_id
        ]);
    }

    public function loginWithOtp(Request $request)
    {
        #Validation
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'otp' => 'required'
        ]);

        #Validation Logic
        $verificationCode   = VerificationCode::where('user_id', $request->user_id)->where('otp', $request->otp)->first();
        $now = Carbon::now();
        if (!$verificationCode) {
            return redirect()->back()->with('error', 'Your OTP is not correct');
        }elseif($verificationCode && $now->isAfter($verificationCode->expire_at)){
            return redirect()->route('otp.login')->with('error', 'Your OTP has been expired');
        }
        $user = User::whereId($request->user_id)->first();
        if($user){
            // Expire The OTP
            $verificationCode->update([
                'expire_at' => Carbon::now()
            ]);

            Auth::login($user);

            // return redirect('/home');
            return redirect()->route('home');
        }
        return redirect()->route('otp.login')->with('error', 'Your Otp is not correct');
    }
}
