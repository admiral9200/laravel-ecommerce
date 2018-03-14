<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use AvoRed\Ecommerce\Models\Database\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use AvoRed\Ecommerce\Mail\NewUserMail;

class UserActivationController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('front.guest');
    }

    public function activateAccount($token, $email)
    {

        $user = User::whereEmail($email)->first();

        if($token == $user->activation_token) {

            $user->update(['activation_token' => null]);
            Auth::loginUsingId($user->id);
            return redirect()->route('my-account.home');
        }

        return redirect()->route('login')->withErrors(['email' => 'User Activation token is invalid.']);

    }

    public function resend() {
        return view('auth.user.resend');
    }

    public function resendPost(Request $request) {

        $user = User::whereEmail($request->get('email'))->first();


        Mail::to($user)->send(new NewUserMail($user));


        return redirect()->back()->with('notificationText', 'User Activation Email Sent! Please Check your email');

    }

}
