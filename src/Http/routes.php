<?php
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'speedy','middleware' => 'Rolice\Speedy\Http\Middleware\Speedy'], function () {

    Route::post('test', ['as' => 'speedy.test', 'uses' => 'Rolice\Speedy\Http\Controllers\SpeedyController@test']);

});