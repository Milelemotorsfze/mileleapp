<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LogActivity;

use Jenssegers\Agent\Facades\Agent;
use Session;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
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
    public function login(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
//            'otp' => 'required'
        ]);
        #Validation Logic
        $user = User::whereId($request->user_id)->first();
        if($request->otp) {
            $verificationCode   = VerificationCode::where('user_id', $request->user_id)->where('otp', $request->otp)->first();
            $now = Carbon::now();
            if(!$verificationCode)
            {
                return redirect()->back()->with('error', 'Your OTP is not correct');
            }elseif($verificationCode && $now->isAfter($verificationCode->expire_at))
            {
                return redirect()->route('login')->with('error', 'Your OTP has been expired');
            }else{
                if($user) {
                    // Expire The OTP
                    $verificationCode->update([
                        'expire_at' => Carbon::now()
                    ]);
                }
            }
        }

        if($user){
                // Expire The OTP
            if(Auth::guard('web')->attempt(['email'=>$request->email,'password'=>$request->password]))
            {
                if(Auth::user()->status == 'active')
                {
                    $macAddr = exec('getmac');
                    $userMacAdress = substr($macAddr, 0, 17);
                    if(Agent::isPhone() == 'phone') {
                        $useDevice = 'phone';
                    }elseif (Agent::isTablet() == 'tablet') {
                        $useDevice = 'tablet';
                    }elseif (Agent::isDesktop() == 'desktop') {
                        $useDevice = 'desktop';
                    }

                    $activity['ip'] = $request->ip();
                    $activity['user_id'] = Auth::id();
                    $activity['status'] = 'success';
                    $activity['mac_address'] = $userMacAdress;
                    $activity['device_name'] = $useDevice ?? '';
                    $activity['browser_name'] = Agent::browser();

                    LogActivity::create($activity);
                    return redirect()->route('home');

                }
                else
                {
                    Session::flash('error','You are not Active by Admin');
                    return view('auth.login');
                }
            }
            else
            {
                Session::flash('error','These credentials do not match our records.');
                return view('auth.login');
            }
        }
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
    public function loginWithOtp(Request $request)
    {

//         #Validation
//         $request->validate([
//             'user_id' => 'required|exists:users,id',
//             'otp' => 'required'
//         ]);

//         #Validation Logic
//         $verificationCode   = VerificationCode::where('user_id', $request->user_id)->where('otp', $request->otp)->first();
//         $now = Carbon::now();
//         if (!$verificationCode) {
//             return redirect()->back()->with('error', 'Your OTP is not correct');
//         }elseif($verificationCode && $now->isAfter($verificationCode->expire_at)){
//             return redirect()->route('otp.login')->with('error', 'Your OTP has been expired');
//         }
//         $user = User::whereId($request->user_id)->first();
//         if($user){
//             // Expire The OTP
//             $verificationCode->update([
//                 'expire_at' => Carbon::now()
//             ]);

//             // Auth::login($user);
// info('here');
//             // return redirect('/home');
//             $this->login($request);
//             // return redirect()->route('login',[$request]);
//         }
//         return redirect()->route('otp.login')->with('error', 'Your Otp is not correct');
    }
}
