<?php

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

$router->group(['prefix' => 'blockchain'], function() use ($router) {
    $router->get('/chain', 'MinerController@chain');
    $router->get('/validation', 'MinerController@chainValidation');
    $router->get('/store', 'MinerController@store');
    $router->get('replace-chain', 'MinerController@replaceChain');
});

$router->group(['prefix' => 'transactions'], function() use ($router) {
    $router->post('add', 'MinerController@addTransaction');
});

$router->group(['prefix' => 'nodes'], function() use ($router) {
    $router->post('connect', 'MinerController@connectNodes');
});

