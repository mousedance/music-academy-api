<?php

use App\Http\Controllers\UserController;

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

Route::get(
    '/',
    function () {
        return view('welcome');
    }
);

Route::group(
    array('prefix' => 'users'),
    function ($app) {
        $app->get(
            '',
            array('uses' => 'UserController@index')
        );
        $app->post(
            '',
            array('uses' => 'UserController@create')
        );
        $app->get(
            '{id}',
            'UserController@show'
        )->where('id', '[0-9]+');
        $app->put(
            '{id}',
            'UserController@update'
        )->where('id', '[0-9]+');
        $app->delete(
            '{id}',
            'UserController@destroy'
        )->where('id', '[0-9]+');
    }
);
