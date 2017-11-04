<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('auth/register', "Auth\RegisterController@createUser");
Route::post('auth/login', "Auth\LoginController@loginUser");

Route::group(
    ['middleware' => 'jwt.auth'],
    function () {
        Route::get('user', "UserController@readResource");
    }
);

Route::group(
    ['middleware' => ['jwt.auth', 'role:admin']],
    function () {
        Route::post('users', 'AdminUserController@create');
        Route::patch('users/{id}', 'AdminUserController@update');
        Route::get('users', 'AdminUserController@index');
        Route::get('/users/{id}', 'AdminUserController@show');
        Route::delete('users/{id}', 'AdminUserController@destroy');
    }
);
