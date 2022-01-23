<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
    public function __construct(){
        $this->middleware('guest')->except('logout');
    }

    public  function credentials(Request $request){
        $login = $request->input($this->username());
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'cedula';
        return [$field => $login, 'password' => $request->input('password')];
    }

    public  function username(){
        return 'login';
    }

    public function showLoginForm(){
        return view('welcome');
    }

    public function login(Request $request){
        $datos = $request->all();
        $this->validateLogin($request);
        $user = User::where('cedula', $datos['login'])->first();
        if ($user) {
            if ($user['estado'] == 1) {
                $pass = $datos['password'];
                if ($user &&  Hash::check($pass, $user->password)){
                    Auth::login($user, false);
                    return $this->sendLoginResponse($request);
                }else{
                    return redirect()->back()->with('error' , 'Contraseña incorrecta.');
                }
            } else {
                return redirect()->back()->with('error' , 'Usuario bloqueado');
            }
        } else {
            return redirect()->back()->with('error' , 'Usuario ingresado no existe.');
        }
    }
}
