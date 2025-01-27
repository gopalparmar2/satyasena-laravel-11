<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Models\User;

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

    protected function showResetForm(Request $request, $token = null) {
        try {
            $user = User::where('email', $request->email)->first();

            if ($user && $user->hasRole('super-admin')) {
                return view('admin.auth.passwords.reset')->with(
                    ['token' => $token, 'email' => $request->email]
                );
            }

            return view('front.auth.passwords.reset')->with(
                ['token' => $token, 'email' => $request->email]
            );
        } catch (\Exception $e) {
            return abort(404);
        }
    }

    protected function reset(Request $request) {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required|confirmed|min:8',
                'token' => 'required',
            ]);

            $user = User::where('email', $request->email)->first();

            $response = $this->broker()->reset(
                $this->getResetCredentials($request), function ($user, $password) {
                    $user->password = bcrypt($password);
                    $user->save();
                }
            );

            if ($response == Password::PASSWORD_RESET) {
                if ($user && $user->hasRole('super-admin')) {
                    return redirect()->route('admin.login')->with('status', trans($response));
                }

                return redirect()->route('login')->with('status', trans($response));
            }

            return back()->withErrors(['email' => trans($response)]);
        } catch (\Exception $e) {
            return back()->withErrors(['email' => trans($e->getMessage())]);
        }
    }

    protected function getResetCredentials(Request $request) {
        return $request->only('email', 'password', 'password_confirmation', 'token');
    }

    protected function broker() {
        return Password::broker();
    }

    protected function resetResponse() {
        return 'Your password has been reset!';
    }
}
