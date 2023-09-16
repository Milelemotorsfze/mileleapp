<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LogActivity;
use Illuminate\Support\Facades\Cookie;
use Jenssegers\Agent\Facades\Agent;
use Session;
use Illuminate\Support\Facades\Mail;
use App\Models\VerificationCode;
use Carbon\Carbon;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */
public function login(Request $request){
    if ($request->isMethod('post')) {
        $data= $request->all();
        $roles=[
            'email' => 'required|email|max:255',
            'password' => 'required',
        ];
        $customessage=[
                'email.required' =>'Email is required',
                'email.email' => 'Email is not vaild',
            'password.required' => 'Password is required',
        ];
        $this->validate($request,$roles,$customessage);


        // calculate verification expiry date time
        // get the latest login activeity of user
        $user = User::where('email', $request->email)->first();
        $macAddr = exec('getmac');
        $userMacAdress = substr($macAddr, 0, 17);
        $userCurrentBrowser = Agent::browser();

        $latestLoginActivity = LogActivity::where('user_id', $user->id)->orderBy('id','DESC')->first();
        // check the mac address change to check whether the device is changed or not
        if($latestLoginActivity->mac_address != $userMacAdress) {
            // redirect to otp verification
        }elseif ($latestLoginActivity->browser != $userCurrentBrowser) {
            // redirect to otp verification
        }else{
            // if device is not changed check the otp expiration date;
            $otpExpirationDate = Carbon::today()->format('d/m/Y');
            $currentDate = Carbon::now()->format('d/m/Y');
            if($currentDate < $otpExpirationDate) {
                // redirect to otp verification
            }
        }
        // update the latest login activity of the user with current date time in otp_verification_expiry.

        // update the existing date of expiry for log activity without otp.

        if(Auth::guard('web')->attempt(['email'=>$data['email'],'password'=>$data['password']])) {

            if(Auth::user()->status == 'active')
            {
                $activity['ip'] = $request->ip();
                $activity['user_id'] = Auth::id();
                $activity['status'] = 'success';
                $activity['mac_address'] = $userMacAdress;
                $activity['browser_name'] = Agent::browser();

                if (Agent::isMobile()) {
                    $activity['device_name'] = 'mobile';
                }else if (Agent::isDesktop()) {
                    $activity['device_name'] = 'desktop';
                }else if (Agent::isTablet()) {
                    $activity['device_name'] = 'tablet';
                }

                LogActivity::create($activity);
                // return redirect()->route('home');
                // otp
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
                // end otp
            }
    else{
        Session::flash('error','You are not Active by Admin');
        return view('auth.login');
    }

        } else {

            Session::flash('error','These credentials do not match our records.');
            return view('auth.login');
        }
    }
    return view('auth.login');
}
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */

    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
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
}
