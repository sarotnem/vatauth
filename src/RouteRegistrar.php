<?php

namespace Theomessin\Vatauth;

use Illuminate\Contracts\Routing\Registrar as Router;

class RouteRegistrar
{
    /**
     * The router implementation.
     *
     * @var Router
     */
    protected $router;

    /**
     * Create a new route registrar instance.
     *
     * @param  Router  $router
     * @return void
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * Register routes for SSO authentication.
     *
     * @return void
     */
    public function all()
    {
        $this->forAuthentication();
    }

    /**
     * Register the routes needed to develop locally.
     *
     * @return void
     */
    public function forAuthentication()
    {
        $this->router->group(['middleware' => ['web']], function ($router) {
            $router->get('login', 'VatauthController@fire')->name('login');
            $router->get('login/handle', 'VatauthController@handle')->name('handle');
            $router->post('logout', 'VatauthController@logout')->name('logout');
        });
    }

}