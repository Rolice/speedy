<?php
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'speedy','middleware' => 'Rolice\Speedy\Http\Middleware\Speedy'], function () {

    Route::post('test', ['as' => 'speedy.test', 'uses' => 'Rolice\Speedy\Http\Controllers\SpeedyController@test']);
    Route::get('session', ['as' => 'speedy.session', 'uses' => 'Rolice\Speedy\Http\Controllers\SpeedyController@session']);

    Route::get('settlements', ['as' => 'speedy.settlements', 'uses' => 'Rolice\Speedy\Http\Controllers\SettlementController@index']);
    Route::get('settlements/autocomplete', [ 'as' => 'speedy.settlements.autocomplete', 'uses' => 'Rolice\Speedy\Http\Controllers\SettlementController@autocomplete']);
});