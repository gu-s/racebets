<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

//$router->get('/user',[Controller::class, 'show'])->name('show');


$router->get('customers', 'CustomerController@list');
$router->get('customers/{id}', 'CustomerController@show');
$router->post('customers', 'CustomerController@create');
$router->put('customers/{id}', 'CustomerController@edit');

$router->post('balances/deposit', 'BalanceController@deposit');
$router->post('balances/withdraw', 'BalanceController@withdraw');
$router->post('balances/report', 'BalanceController@report');

