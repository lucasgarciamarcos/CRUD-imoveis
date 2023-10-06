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

$router->post('/create', 'ImovelController@create');        // O endpoint "/create" deve criar e atualizar informações do imovel
$router->get('/get/{id}', 'ImovelController@get');               // O endpoint "/get" deve fornecer as informações do imovel
$router->get('/list', 'ImovelController@list');             // O endpoint "/list" deve fornecer as informações de todos imoveis
$router->get('/delete/{id}', 'ImovelController@delete');      // O endpoint "/delete" deve remover o imovel caso exista

// Rota da view utilizada
$router->get('/home', function ()  {
    return view('app');
});