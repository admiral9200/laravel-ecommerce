<?php

namespace Mage2\Auth\Controllers;

use Mage2\Framework\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

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
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/my-account';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('frontguest', ['except' => 'logout']);

        $url = URL::previous();
        $checkoutUrl = route('checkout.index');

        if ($url == $checkoutUrl) {
            $this->redirectTo = $checkoutUrl;
        }

    }

    protected function guard()
    {
        return Auth::guard('web');
    }

}
