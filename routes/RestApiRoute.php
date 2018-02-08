<?php

$Conecctions = implode('|',array_keys(config('database.connections')));

Route::pattern('company', "($Conecctions)");

Route::prefix('{company}')->group(function () {
    Route::group(['prefix' => 'restapi', 'as' => 'restapi.', 'middleware' => 'auth'], function() {
        Route::resource('pagos', 'RestApi\PagosController');
    });
});