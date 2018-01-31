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

$Conecctions = implode('|',array_keys(config('database.connections')));

Route::pattern('company', "($Conecctions)");

Route::prefix('{company}')->group(function () {
    Route::group(['prefix' => 'wsdl', 'as' => 'wsdl.', 'middleware' => 'auth:api'], function() {
        Route::get('areas', 'Wsdl\AreasController@index');
    });
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});