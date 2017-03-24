# theomessin/vatauth

A package for Laravel 5.4 to handle authentication via Vatsim SSO.

## Installation

- ### Composer
  Simply run `composer require theomessin/vatauth` to install the latest version of this package

- ### Laravel Setup
  There are several things that need to be done for this package to work:
  #### Registering the Service Provider
  Find your `app.php` configuration file and add the following line in your Third Pary Service providers:
   ```
   Theomessin\Vatauth\VatauthServiceProvider::class,
   ```

  #### Configuration
  Run the following command to publish the default `vatauth.php` configuration file:
  ```
  php artisan vendor:publish --provider="Theomessin\Vatauth\VatauthServiceProvider"
  ```

  #### Migrations
   Firstly, you will have to change your `users` table so that it is compatible with the data that Vatsim provides. 

  Assuming you have the default `create_users_table` migration:
  - Remove line `20` (the password attribute) as this is no longer applicable.
  - Replace line `17` (the incremental ID) with a simple integer `id` attribute and set that to be the primary key of the table:

    ```php
    $table->integer('id');
    $table->primary('id');
    ```
    
  #### Routes
  
  Vatauth will have to register some authentication routes in order to work. To do this, you should call the `Vatauth::routes` method within your `boot` method of your `AuthServiceProvider`:
  
  ```php
  <?php

  namespace App\Providers;

  use Theomessin\Vatauth\Vatauth;
  use Illuminate\Support\Facades\Gate;
  use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

  class AuthServiceProvider extends ServiceProvider
  {
      /**
       * The policy mappings for the application.
       *
       * @var array
       */
      protected $policies = [
          'App\Model' => 'App\Policies\ModelPolicy',
      ];7

      /**
       * Register any authentication / authorization services.
       *
       * @return void
       */
      public function boot()
      {
          $this->registerPolicies();

          Vatauth::routes();
      }
  }
  ```
  
  #### Traits
  
  Finally, you will have to add the `VatsimSynchronisable` trait to your `User.php` model:

  ```php
  use Theomessin\Vatauth\Traits\VatsimSynchronisable;
  
  class User extends Authenticatable
  {
      use Notifiable. VatsimSynchronisable;
  ```
  
  Also, make sure you add the `id` attribute in the `$fillable` array, and remove the password from the `$fillable` and `$hidden` array.

  ### Configuration
  Go over the `vatauth.php` configuration file to see what you should set.
  
  ***NB: you should keep your RSA certificate in a `storage/app/cert.key` file.***
  