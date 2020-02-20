<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware'=>['guest']],function(){
    Route::get('/','Auth\LoginController@showLoginForm');
    Route::post('/login', 'Auth\LoginController@login')->name('login');
});

Route::group(['middleware'=>['auth']],function(){
    
    Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

    Route::get('/main', function () {
        return view('contenido/contenido');
    })->name('main');

    // Pizzas
    Route::get('/pizza', 'PizzaController@index');
    Route::post('/pizza/registrar', 'PizzaController@store');

    // pedidos
    Route::get('/pedido', 'PedidoController@index');
    Route::post('/pedido/registrar', 'PedidoController@store');
    Route::get('/pedido/obtenerCabecera', 'PedidoController@obtenerCabecera');
    Route::get('/pedido/obtenerDetalles', 'PedidoController@obtenerDetalles');
});