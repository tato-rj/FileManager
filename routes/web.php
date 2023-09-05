<?php

use Illuminate\Support\Facades\Route;

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

Route::get('', 'VideosController@index')->name('home');

Route::middleware('api.token')->post('upload', 'VideosController@upload')->name('upload');

Route::middleware('api.token')->delete('delete', 'VideosController@destroy')->name('delete');

Route::prefix('tokens')->name('tokens.')->group(function() {

	Route::get('', 'ApiTokensController@index')->name('index');

	Route::post('', 'ApiTokensController@store')->name('store');

	Route::delete('revoke', 'ApiTokensController@revoke')->name('revoke');

});

Route::prefix('webhook')->name('webhook.')->group(function() {

	Route::post('{video}', 'WebhookController@resend')->name('resend');

});