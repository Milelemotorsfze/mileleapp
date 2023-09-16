<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LogActivity;
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
        
        if(Auth::guard('web')->attempt(['email'=>$data['email'],'password'=>$data['password']])) {
            
            if(Auth::user()->status == 'active') 
            {
                $activity['ip'] = $request->ip();
                $activity['user_id'] = Auth::id();
                $activity['status'] = 'success';
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
