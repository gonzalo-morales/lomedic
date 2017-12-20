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

Auth::routes();

$Conecctions = implode('|',array_keys(config('database.connections')));

Route::get('/', 'HomeController@index')->name('home');


Route::get('/phpinfo', function () { phpinfo(); });

Route::pattern('company', "($Conecctions)");

Route::prefix('{company}')->group(function () {
    Route::resource('/', 'HomeController', ['middleware' => ['share','csrf','password_expired']]);
    Route::get('/phpinfo', function () { phpinfo(); });
    Route::get('/password/expired', 'Auth\ExpiredPasswordController@expired')->name('password.expired')->middleware(['share','csrf']);
    Route::post('password/reset', 'Auth\ExpiredPasswordController@reset')->name('password.reset')->middleware(['share','csrf']);
});

Route::group(['prefix' => '{company}/{entity}', 'middleware' => ['auth','share','password_expired']], function($co) {
	Route::resource('api', 'APIController');
});