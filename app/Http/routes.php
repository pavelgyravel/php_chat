<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return redirect('/messages');
});

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::get('auth/blocked', 'Auth\AuthController@blocked');


Route::group(['middleware' => ['web']], function () {

    Route::get('auth/login', 'Auth\AuthController@getLogin');
    Route::post('auth/login', 'Auth\AuthController@postLogin');
    Route::get('auth/logout', 'Auth\AuthController@getLogout');

    Route::get('auth/register', 'Auth\AuthController@getRegister');
    Route::post('auth/register', 'Auth\AuthController@postRegister');

});


Route::group(['prefix' => 'messages', 'middleware' => ['web', 'auth', 'blocked']], function () {
    Route::put('user/{id}/new', ['as' => 'messages.user.new', 'uses' => 'MessagesController@getMessages']);

    Route::get('user/{id}', ['as' => 'messages.user', 'uses' => 'MessagesController@user']);
    //Route::post('user/{id}', ['as' => 'messages.user', 'uses' => 'MessagesController@user']);
    Route::get('/', ['as' => 'messages', 'uses' => 'MessagesController@index']);

    Route::get('newMessages', ['as' => 'messages.newMessages', 'uses' => 'MessagesController@newMessages']);
    //Route::get('create', ['as' => 'messages.create', 'uses' => 'MessagesController@create']);
    //Route::post('/', ['as' => 'messages.store', 'uses' => 'MessagesController@store']);
    //Route::get('{id}', ['as' => 'messages.show', 'uses' => 'MessagesController@show']);

    Route::put('user/{id}', ['as' => 'messages.update', 'uses' => 'MessagesController@update']);

});

Route::group(['prefix' => 'user', 'middleware' => ['web', 'auth']], function () {
    Route::get('/profile', ['as' => 'user.profile', 'uses' => 'UserController@index']);
    Route::post('/update', ['as' => 'user.update', 'uses' => 'UserController@update']);
});

Route::group(['prefix' => 'user', 'middleware' => ['web', 'auth', 'admin']], function () {
    Route::get('/profile/{id}', ['as' => 'admin.user.profile', 'uses' => 'AdminController@index']);
    Route::post('/update/{id}', ['as' => 'admin.user.update', 'uses' => 'AdminController@update']);
    Route::get('/delete/{id}', ['as' => 'admin.user.delete', 'uses' => 'AdminController@delete']);
});
