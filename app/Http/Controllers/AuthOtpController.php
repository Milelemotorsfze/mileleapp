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

                // calculate verification expiry date time
                // get the latest login activeity of user
//                $macAddr = exec('getmac');
//                $userMacAdress = substr($macAddr, 0, 17);

                $userCurrentBrowser = Agent::browser();
                $userLastOtpVerified = VerificationCode::where('user_id', $user->id)
                    ->orderBy('id','DESC')->first();
                info($userLastOtpVerified);
                // check opt table has entry
                if($userLastOtpVerified) {
                    $latestLoginActivity = LogActivity::where('user_id', $user->id)->orderBy('id','DESC')->first();
                    // check the mac address change to check whether the device is changed or not
//                    if($latestLoginActivity->mac_address == $userMacAdress ) {
                        info("mac address same");
                    if($latestLoginActivity) {
//                        if($latestLoginActivity->mac_address == $userMacAdress ) {
                        // check the platform same or not
                        if (Agent::isPhone() == 'phone') {
                            $userDevice = 'phone';
                        } elseif (Agent::isTablet() == 'tablet') {
                            $userDevice = 'tablet';
                        } elseif (Agent::isDesktop() == 'desktop') {
                            $userDevice = 'desktop';
                        }
//                        info("mac address same");
                        if ($latestLoginActivity->device_name == $userDevice) {
                            if ($latestLoginActivity->browser_name == $userCurrentBrowser) {
                                info("browser name same");

                                $userLastOtpVerifiedDate = Carbon::parse($userLastOtpVerified->created_at)->addDays(30);
                                info($userLastOtpVerifiedDate);
                                $currentDate = Carbon::now();
                                if ($currentDate->isBefore($userLastOtpVerifiedDate)) {
                                    info("expiration  date NOT reached");

                                    $request['user_id'] = $user->id;
                                    return (app('App\Http\Controllers\Auth\LoginController')->login($request));
                                }
                            }
                        }
//                    }
                    }
                }

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
                Mail::send(
                        "auth.otpemail",
                        ["data"=>$data] ,
                        function($msg) use ($data,$template,$subject) {
                            $msg->to($data['email'], $data['name'])
                                ->from($template['from'],$template['from_name'])
                                ->subject($subject);
                        }
                    );
                    $user_id = Crypt::encryptString($verificationCode->user_id);
                    $email = Crypt::encryptString($request->email);
                    $password = Crypt::encryptString($request->password);
                return redirect()->route('otp.verification', ['user_id' => $user_id, 'email'=>$email,'password'=>$password])->with('success',  $message);
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
//        return view('auth.login');
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
        else
        {
            Session::flash('error','This email do not match our records.');
            return view('otp.login');
        }
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


}
