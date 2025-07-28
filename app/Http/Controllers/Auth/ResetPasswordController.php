<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Exception;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Session;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\VerificationCode;
use Carbon\Carbon;
use Hash;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/';
    public function createPassword($email)
    {
        $email= Crypt::decryptString($email);
        return view('auth.createPassword', compact('email'));
    }
    public function storePassword(Request $request)
    {
        #Validation Logic
        $request->validate([
            'otp' => ['required'],
            'email' => ['required'],
            'password' => ['required'],
        ]);
        $verificationCode = VerificationCode::where('user_id', $request->user_id)->where('otp', $request->otp)->first();
        $now = Carbon::now();
        if(!$verificationCode )
        {
            return redirect()->back()->with('error', 'Your OTP is not correct');
        }elseif($verificationCode && $now->isAfter($verificationCode->expire_at))
        {
            return redirect()->route('password.request')->with('error', 'Your OTP has been expired! Resend OTP Here!');
        }
        else
        {
            $user = User::whereId($request->user_id)->first();
            if($user){
                // Expire The OTP
                $verificationCode->update([
                    'expire_at' => Carbon::now()
                ]);

                $user = User::where('email', $request->email)->first();
                    $user->password = Hash::make($request->password);
                    $user->save();
                return redirect()->route('login')->with('success', 'Password Updated Successfully! Login Here.');
            }
        }
    }
    public function createPasswordOtpGenerate(Request $request)
    {
        # Validate Data
        $request->validate([
            'email' => 'required|exists:users,email',
            'password' => 'required|confirmed|min:6',
            'password_confirmation' =>'required'
        ]);
        if($request->password == $request->password_confirmation)
        {
                # Generate An OTP
                $verificationCode = $this->generateOtp($request->email);
                $message = "Your OTP To Password Reset is Send Successfully ";
                # Return With OTP
                $data['email'] = $request->email;
                $data['name'] = 'Hello,';
                $data['otp'] = $verificationCode->otp;
                $template['from'] = 'no-reply@milele.com';
                $template['from_name'] = 'Milele Matrix';
                $subject = 'Milele Matrix Password Update OTP Code';
            try {
                Mail::send(
                    "auth.otpemail",
                    ["data" => $data],
                    function ($msg) use ($data, $template, $subject) {
                        $msg->to($data['email'], $data['name'])
                            ->from($template['from'], $template['from_name'])
                            ->subject($subject);
                    }
                );
            }catch(Exception $e){
                \Log::error($e);
                return response($e->getMessage(), 422);
            }

                    $user_id = Crypt::encryptString($verificationCode->user_id);
                    $email = Crypt::encryptString($request->email);
                    $password = Crypt::encryptString($request->password);
                    $password_confirmation = Crypt::encryptString($request->password_confirmation);
                return redirect()->route('createPassword.verification', ['user_id' => $user_id, 'email'=> $email,
                    'password'=>$password,'password_confirmation',$password_confirmation])->with('success',  $message);
        }
        else
        {
            Session::flash('error','Password and confirm password are not match');
            return redirect()->back();
        }
        return redirect()->back();
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
    public function verification($user_id,$email, $password, $password_confirmation)
    {
        $user_id= Crypt::decryptString($user_id);
        $email= Crypt::decryptString($email);
        $password= Crypt::decryptString($password);
        return view('auth.create-password-otp-verification')->with([
            'user_id' => $user_id,
            'email' => $email,
            'password' => $password,
        ]);
    }
}
