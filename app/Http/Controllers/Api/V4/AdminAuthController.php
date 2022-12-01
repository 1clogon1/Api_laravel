<?php

namespace App\Http\Controllers\Api\V4;
use App\Http\Controllers\Controller;
use App\Models\V4\Register;
use App\Models\V4\Admin;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AdminAuthController extends Controller
{
    use AuthenticatesUsers;

    protected $guardName = 'admin';
    protected $maxAttempts = 3;
    protected $decayMinutes = 2;

    protected $loginRoute;

    public function __construct()
    {
        $this->middleware('guest:admin')->except('postLogout');
        $this->loginRoute = route('admin.login');
    }
    public function create313(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'email_verified_at' => 'required',
            'password' => 'required',
            'password_confirmation' => 'same:password',
            'timestamps' => 'required|date',
        ]);
        if ($validation->fails()) {
            $result = ['error' => ['code' => 422, 'message' => 'Validation error', 'error' => $validation->errors()]];
            return response($result, 422);
        }
        $admin=Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'email_verified_at' => $request->email_verified_at,
            'timestamps' => $request->timestamps,
            'password' => Hash::make($request->password),
            'rememberToken' => Str::random(80)
        ]);
        return response('Успешная регистрация', 204);

    }
    public function getLogin()
    {
        return view('admin.login');
    }

    public function postLogout()
    {
        Auth::guard($this->guardName)->logout();
        Session::flush();
        return redirect()->guest($this->loginRoute);
    }

    public function postLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:5'
        ]);

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            $this->sendLockoutResponse($request);
        }

        $credential = [
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ];


        if (Auth::guard($this->guardName)->attempt($credential)) {

            $request->session()->regenerate();
            $this->clearLoginAttempts($request);
            return redirect()->intended();

        } else {
            $this->incrementLoginAttempts($request);

            return redirect()->back()
                ->withInput()
                ->withErrors(["Incorrect user login details!"]);
        }
    }

}
