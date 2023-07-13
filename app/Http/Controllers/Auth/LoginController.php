<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LogActivity;
use Session;

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
                return redirect()->route('home');
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
}
