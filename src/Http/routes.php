<?php
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'speedy','middleware' => 'Rolice\Speedy\Http\Middleware\Speedy'], function () {

    Route::post('test', ['as' => 'speedy.test', 'uses' => 'Rolice\Speedy\Http\Controllers\SpeedyController@test']);
    Route::get('session', ['as' => 'speedy.session', 'uses' => 'Rolice\Speedy\Http\Controllers\SpeedyController@session']);

    Route::get('services', ['as' => 'speedy.services', 'uses' => 'Rolice\Speedy\Http\Controllers\ServicesController@index']);
    Route::get('services/autocomplete/{sender}/{receiver}', ['as' => 'speedy.services.autocomplete', 'uses' => 'Rolice\Speedy\Http\Controllers\ServicesController@autocomplete']);

//    Route::get('settlements', ['as' => 'speedy.settlements', 'uses' => 'Rolice\Speedy\Http\Controllers\SettlementController@index']);
    Route::get('settlements/autocomplete', [ 'as' => 'speedy.settlements.autocomplete', 'uses' => 'Rolice\Speedy\Http\Controllers\SettlementController@autocomplete']);

//    Route::get('streets', ['as' => 'speedy.streets', 'uses' => 'Rolice\Speedy\Http\Controllers\StreetController@index']);
    Route::get('streets/autocomplete', [ 'as' => 'speedy.streets.autocomplete', 'uses' => 'Rolice\Speedy\Http\Controllers\StreetController@autocomplete']);


    Route::get('offices/autocomplete', [ 'as' => 'speedy.offices.autocomplete', 'uses' => 'Rolice\Speedy\Http\Controllers\OfficeController@autocomplete']);
//    Route::get('offices/dropdown', [ 'as' => 'speedy.offices.dropdown', 'uses' => 'Rolice\Speedy\Http\Controllers\OfficeController@dropdown']);
    Route::get('offices/{id}', ['as' => 'speedy.offices.show', 'uses' => 'Rolice\Speedy\Http\Controllers\OfficeController@show']);
//    Route::get('offices', ['as' => 'speedy.offices', 'uses' => 'Rolice\Speedy\Http\Controllers\OfficeController@index']);

    Route::post('waybill/issue', ['as' => 'speedy.waybill.issue', 'uses' => 'Rolice\Speedy\Http\Controllers\WaybillController@issue']);
    Route::post('waybill/calculate', [ 'as' => 'speedy.waybill.calculate', 'uses' => 'Rolice\Speedy\Http\Controllers\WaybillController@calculate']);
    Route::post('waybill/pdf', [ 'as' => 'speedy.waybill.pdf', 'uses' => 'Rolice\Speedy\Http\Controllers\WaybillController@pdf']);

    Route::get('shipment/types', [ 'as' => 'speedy.shipment.types', 'uses' => 'Rolice\Speedy\Http\Controllers\ShipmentController@index']);
});