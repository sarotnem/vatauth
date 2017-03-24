<?php

namespace Theomessin\Vatauth\Traits;

use Auth;
use Illuminate\Http\Request;
use Theomessin\Vatauth\Facades\Vatauth;

trait viaVatsim
{
    /**
     * Prepare an OAuth request to send to the VATSIM server
     *
     * @return mixed
     */
    public function login()
    {
        $callback = function($key, $secret, $url) {
            session(['vatauth' => compact('key', 'secret')]);
            return redirect($url);
        };

        return Vatauth::login(route('handle'), $callback);
    }

    /**
     * Handle a VATSIM SSO authentication request
     *
     * @param Request $request
     * @return mixed
     */
    public function handle(Request $request)
    {
        $callback = function($user) use ($request) {
            $request->session()->forget('vatauth');
            Auth::login(config('vatauth.users.model')::sync($user));
            return redirect()->intended($this->redirectTo);
        };

        return Vatauth::validate(session('vatauth.key'), session('vatauth.secret'), $request->input('oauth_verifier'), $callback);
    }
}