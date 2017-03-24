<?php

namespace Theomessin\Vatauth\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Theomessin\Vatauth\Traits\viaVatsim;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class VatauthController extends Controller
{

    use AuthenticatesUsers, viaVatsim;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo;

    /**
     * Where to redirect users after logout.
     *
     * @var string
     */
    protected $redirectAfterLogout;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);

        if (config('vatauth.redirect.afterLogin.type') === 'route') {
            $this->redirectTo = route(config('vatauth.redirect.afterLogin.to'));
        } else if (config('vatauth.redirect.afterLogin.type') === 'url') {
            $this->redirectTo = config('vatauth.redirect.afterLogin.to');
        }

        if (config('vatauth.redirect.afterLogout.type') === 'route') {
            $this->redirectAfterLogout = route(config('vatauth.redirect.afterLogout.to'));
        } else if (config('vatauth.redirect.afterLogout.type') === 'url') {
            $this->redirectAfterLogout = config('vatauth.redirect.afterLogout.to');
        }
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->flush();

        $request->session()->regenerate();

        return redirect($this->redirectAfterLogout);
    }
}
