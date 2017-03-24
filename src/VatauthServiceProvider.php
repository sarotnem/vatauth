<?php

namespace Theomessin\Vatauth;

use Storage;
use Theomessin\Vatauth\Repository\SSO;
use Illuminate\Support\ServiceProvider;

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

		$cert = Storage::exists(storage_path('app/cert.key')) ? Storage::get(storage_path('app/cert.key')) : Storage::exists(__DIR__.'/../storage/app/cert.key');

		$this->app->bind('vatauth', function($app){
			return new SSO(
				config('vatauth.base'),
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
		return [SSO::class];
	}

}
