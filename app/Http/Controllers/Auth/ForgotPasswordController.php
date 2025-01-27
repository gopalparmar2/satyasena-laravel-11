<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use App\Notifications\CustomResetPasswordNotification;
use App\Models\User;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    protected function showLinkRequestForm() {
        try {
            if (request()->is('admin/*')) {
                return view('admin.auth.passwords.email');
            }

            return view('front.auth.passwords.email');
        } catch (\Exception $e) {
            return abort(404);
        }
    }

    public function sendResetLinkEmail(Request $request) {
        try {
            $request->validate(['email' => 'required|email']);

            $user = User::where('email', $request->email)->first();

            if ($user) {
                $user->notify(new CustomResetPasswordNotification($this->broker()->createToken($user)));
                return back()->with('status', 'We have emailed your password reset link!');
            }

            return back()->withErrors(['email' => 'We could not find a user with that email address.']);
        } catch (\Exception $e) {
            return back()->withErrors(['email' => $e->getMessage()]);
        }
    }

    protected function broker() {
        return Password::broker();
    }
}
