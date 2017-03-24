# VatsimSSO

A package for Laravel 5.4 to handle authentication via Vatsim SSO.

## Installation

- ### Composer
  Simply run `composer require theomessin\vatauth` to install the latest version of this package

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
  php artisan vendor:publish --provider="Theomessin\Vatauth\VatauthServiceProvider" --tag="config"
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
  
  You will only need three routes for authentication:
  
  ```php
  Route::get('login', 'Auth\LoginController@login')->name('login');
  Route::get('login/handle', 'Auth\LoginController@handle')->name('handle');
  Route::post('logout', 'Auth\LoginController@logout')->name('logout');
  ```
  
  #### Traits
  
  Finally, you will have to add the following traits:
  
  - `viaVatsim` to your `LoginController.php` controller:
  
    ```php
    use Theomessin\Vatauth\Tratis\viaVatsim;
  
    class LoginController extends Controller
    {
        use AuthenticatesUsers, viaVatsim;
    ```
    
  - `VatsimSynchronisable` to your `User.php` model:
  
    ```php
    use Theomessin\Vatauth\Tratis\VatsimSynchronisable;
    
    class User extends Authenticatable
    {
        use Notifiable. VatsimSynchronisable;
    ```
  
  ### Configuration
  Go over the `vatauth.php` configuration file to see what you should set.
  
  ***NB: you should keep your RSA certificate in a `storage/app/cert.key` file.***
  