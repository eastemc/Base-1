<?php

namespace Backpack\Base\app\Http\Controllers\Auth;

use Backpack\Base\app\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Auth;

class ResetPasswordController extends Controller
{
    protected $data = []; // the information we send to the view

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
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $guard = config('backpack.base.guard')
            ?: config('auth.defaults.guard');

        $this->middleware("guest:$guard");

        // where to redirect after password was reset
        $this->redirectTo = property_exists($this, 'redirectTo') ? $this->redirectTo : config('backpack.base.route_prefix', 'admin').'/dashboard';
    }

    // -------------------------------------------------------
    // Laravel overwrites for loading backpack views
    // -------------------------------------------------------

    /**
     * Display the password reset view for the given token.
     *
     * If no token is present, display the link request form.
     *
     * @param \Illuminate\Http\Request $request
     * @param string|null              $token
     *
     * @return \Illuminate\Http\Response
     */
    public function showResetForm(Request $request, $token = null)
    {
        $this->data['title'] = trans('backpack::base.reset_password'); // set the page title

        return view('backpack::auth.passwords.reset', $this->data)->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    public function broker()
    {
        $passwords = config('backpack.base.passwords')
            ?: config('auth.defaults.passwords');

        return Password::broker($passwords);
    }

    protected function guard()
    {
        $guard = config('backpack.base.guard')
            ?: config('auth.defaults.guard');

        return Auth::guard($guard);
    }
}
