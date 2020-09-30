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





//App Setting Controller 
Route::post('settinginfo', 'API\appsettingController@settingindex');
Route::post('contactus', 'API\appsettingController@contactus');
Route::post('home', 'API\appsettingController@home');
Route::post('addtransfer', 'API\appsettingController@addtransfer');


//Item Controller 
Route::post('allcities', 'API\itemController@allcities');
Route::post('showitem', 'API\itemController@showitem');



//Order Controller 
Route::post('makeorder', 'API\orderController@makeorder');



Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});