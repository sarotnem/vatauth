<?php

namespace Theomessin\Vatauth;

use File;
use Illuminate\Support\ServiceProvider;
use Theomessin\Vatauth\Repository\SSO as Vatauth;

class VatauthServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
	    $this->publishes([
	        __DIR__.'/../config/vatauth.php' => config_path('vatauth.php'),
	    ]);
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->mergeConfigFrom(
			__DIR__.'/../config/vatauth.php', 'vatauth'
		);

		$cert = File::exists(storage_path('app/cert.key')) ? File::get(storage_path('app/cert.key')) : File::get(__DIR__.'/../storage/app/cert.key');

		$this->app->bind(Vatauth::class, function($app){
			return new Vatauth(
				config('vatauth.server'),
				config('vatauth.key'),
				config('vatauth.secret'),
				config('vatauth.method'),
				$cert
			);
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return [Vatauth::class];
	}

}
