<?php

namespace App\Http\Controllers;

use App\Models\LogActivity;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\VerificationCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Session;
use Jenssegers\Agent\Facades\Agent;

class AuthOtpController extends Controller
{
    // Return View of OTP Login Page
    public function login()
    {
        return view('auth.otp-login');
    }

    public function loginOtpGenerate(Request $request)
    {
        $user = User::where('email',$request->email)->first();
        if($user && Hash::check($request->password, $user->password))
        {
            if('active' == $user->status)
            {
                # Validate Data
                $request->validate([
                    'email' => 'required|exists:users,email',
                    'password' => 'required',
                ]);

                $userCurrentBrowser = Agent::browser();
                $userLastOtpVerified = VerificationCode::where('user_id', $user->id)
                    ->orderBy('id','DESC')->first();
                if($userLastOtpVerified) {
                    $latestLoginActivity = LogActivity::where('user_id', $user->id)->orderBy('id','DESC')->first();
                    if($latestLoginActivity) {
                        if (Agent::isPhone() == 'phone') {
                            $userDevice = 'phone';
                        } elseif (Agent::isTablet() == 'tablet') {
                            $userDevice = 'tablet';
                        } elseif (Agent::isDesktop() == 'desktop') {
                            $userDevice = 'desktop';
                        }
                        if ($latestLoginActivity->device_name == $userDevice) {
                            if ($latestLoginActivity->browser_name == $userCurrentBrowser) {

                                $userLastOtpVerifiedDate = Carbon::parse($userLastOtpVerified->created_at)->addDays(30);
                                $currentDate = Carbon::now();
                                if ($currentDate->isBefore($userLastOtpVerifiedDate)) {

                                    $request['user_id'] = $user->id;
                                    return (app('App\Http\Controllers\Auth\LoginController')->login($request));
                                }
                            }
                        }
                    }

                }

                /*
                # Generate An OTP
                $verificationCode = $this->generateOtp($request->email);
                $message = "Your OTP To Login is Send Successfully ";
                # Return With OTP
                $data['email'] = $request->email;
                $data['name'] = 'Hello,';
                $data['otp'] = $verificationCode->otp;
                $template['from'] = 'no-reply@milele.com';
                $template['from_name'] = 'Milele Matrix';
                $subject = 'Milele Matrix Login OTP Code';
                try {
                    Mail::send(
                            "auth.otpemail",
                            ["data"=>$data] ,
                            function($msg) use ($data,$template,$subject) {
                                $msg->to($data['email'], $data['name'])
                                    ->from($template['from'],$template['from_name'])
                                    ->subject($subject);
                            }
                        );
                } catch (\Exception $e) {
                    \Log::error($e);
                }
                $user_id = Crypt::encryptString($verificationCode->user_id);
                $email = Crypt::encryptString($request->email);
                $password = Crypt::encryptString($request->password);
                return redirect()->route('otp.verification', ['user_id' => $user_id, 'email'=>$email,'password'=>$password])->with('success',  $message);
                */
                // Directly log in the user without OTP and mail
                $request['user_id'] = $user->id;
                return (app('App\Http\Controllers\Auth\LoginController')->login($request));
            }
            else
            {
                Session::flash('error','You are not Active by Admin.');
                return view('auth.login');
            }
        }
        else
        {
            Session::flash('error','These credentials do not match our records.');
            return view('auth.login');
        }
    }
    // Generate OTP
    public function generate(Request $request)
    {
        $user = User::where('email',$request->email)->first();
        if($user)
        {
            # Validate Data
            $request->validate([
                'email' => 'required|exists:users,email',
            ]);
            /*
            # Generate An OTP
            $verificationCode = $this->generateOtp($request->email);
            $message = "Your OTP To Login is Send Successfully ";
            # Return With OTP
            $data['email'] = $request->email;
            $data['name'] = 'Hello,';
            $data['otp'] = $verificationCode->otp;
            $template['from'] = 'no-reply@milele.com';
            $template['from_name'] = 'Milele Matrix';
            $subject = 'Milele Matrix Login OTP Code';
            try {
                Mail::send(
                        "auth.otpemail",
                        ["data"=>$data] ,
                        function($msg) use ($data,$template,$subject) {
                            $msg->to($data['email'], $data['name'])
                                ->from($template['from'],$template['from_name'])
                                ->subject($subject);
                        }
                    );
            } catch (\Exception $e) {
                \Log::error($e);
            }
            return redirect()->route('otp.verification', ['user_id' => $verificationCode->user_id])->with('success',  $message);
            */
            // Directly redirect to login page with success (simulate OTP success)
            return redirect()->route('login')->with('success',  'OTP step bypassed, please login.');
        }
        else
        {
            Session::flash('error','This email do not match our records.');
            return view('otp.login');
        }
    }

    /*
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
    */

    // Optionally, you may want to comment out or adjust the verification method if not needed
    /*
    public function verification($user_id, $email, $password)
    {
        $user_id= Crypt::decryptString($user_id);
        $email= Crypt::decryptString($email);
        $password= Crypt::decryptString($password);
        return view('auth.otp-verification')->with([
            'user_id' => $user_id,
            'email' => $email,
            'password' => $password,
        ]);
    }
    */


}
