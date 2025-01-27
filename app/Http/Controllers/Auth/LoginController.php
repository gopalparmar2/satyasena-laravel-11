<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
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

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('guest')->except('logout');
    //     $this->middleware('auth')->only('logout');
    // }

    protected function showLoginForm() {
        $user = Auth::user();

        if (!$user) {
            if (request()->is('admin/*')) {
                return view('admin.auth.login');
            }

            return view('front.auth.login');
        }

        return redirect()->back();
    }

    protected function login(Request $request) {
        try {
            if ($request->has('email')) {
                $this->validate($request, [
                    'email' => 'required|email',
                    'password' => 'required',
                ]);

                $credentials = $request->only('email', 'password');

                if (Auth::attempt($credentials)) {
                    return redirect()->route('admin.index');
                }

                return back()->withErrors([
                    'email' => 'These credentials do not match our records.',
                ]);
            } else {
                $this->validate($request, [
                    'mobileNumber' => 'required|regex:/^[0-9]{10}$/',
                ]);

                if (Auth::user()) {
                    $user = Auth::user();
                    $user->mobile_number = $request->mobileNumber;
                    $user->save();
                } else {
                    $user = User::where('mobile_number', $request->mobileNumber)->first();

                    if (!$user) {
                        $user = new User();
                        $user->mobile_number = $request->mobileNumber;
                        $user->save();
                    }
                }

                if (Auth::loginUsingId($user->id)) {
                    $isOtpVerificationOn = \Config::get('app.isOtpVerificationOn');

                    if ($isOtpVerificationOn) {
                        // $user->otp = generateOtp();
                        $user->otp = 123456;
                        $user->otp_expires_at = Carbon::now()->addMinutes(5);
                        $user->save();

                        return redirect()->route('front.show.verify.otp.form');
                    }

                    if (!$user->is_details_filled) {
                        return redirect()->route('front.show.user.details.form');
                    }

                    return redirect()->route('front.index');
                }

                Session::flash('alert-message', 'Something went wrong.');
                Session::flash('alert-class','error');

                return redirect()->back();
            }
        } catch (\Exception $e) {
            Session::flash('alert-message', $e->getMessage());
            Session::flash('alert-class','error');

            return redirect()->back();
        }
    }

    protected function authenticated($user) {
        if (auth()->user()->hasRole('super-admin')) {
            return redirect()->route('admin.index');
        }

        return redirect('/');
    }

    public function logout(Request $request) {
        $isSuperAdmin = Auth::user()->hasRole('super-admin');

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($isSuperAdmin) {
            return redirect()->route('admin.login');
        }

        return redirect()->route('login');
    }
}
