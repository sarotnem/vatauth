<?php

namespace Theomessin\Vatauth\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Theomessin\Vatauth\Repository\SSO as Vatauth;

trait viaVatsim
{
    /**
     * Prepare an OAuth request to send to the VATSIM server
     *
     * @param \Theomessin\Vatauth\Repository\SSOVatauth $vatauth
     * @return mixed
     */
    public function fire(Vatauth $vatauth)
    {
        $callback = function($key, $secret, $url) {
            session(['vatauth' => compact('key', 'secret')]);
            return redirect($url);
        };

        return $vatauth->login(route('handle'), $callback);
    }

    /**
     * Handle a VATSIM SSO authentication request
     *
     * @param \Theomessin\Vatauth\Repository\SSOVatauth $vatauth
     * @param Request $request
     * @return mixed
     */
    public function handle(Vatauth $vatauth, Request $request)
    {
        $callback = function($e) use ($request) {
            $request->session()->forget('vatauth');
            config('vatauth.users.model')::sync($e);
            Auth::loginUsingId($e->id, true);
            return redirect()->intended($this->redirectTo);
        };
        
        return $vatauth->validate(session('vatauth.key'), session('vatauth.secret'), $request->input('oauth_verifier'), $callback);
    }
}
