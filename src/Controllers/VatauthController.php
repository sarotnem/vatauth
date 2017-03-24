<?php

namespace Theomessin\Vatauth\Controllers;

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
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);

        if (config('vatauth.redirect.type') === 'route') {
            $this->redirectTo = route(config('vatauth.redirect.to'));
        } else if (config('vatauth.redirect.type') === 'url') {
            $this->redirectTo = config('vatauth.redirect.to');
        }
    }
}
