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

Route::middleware('auth')->post('upload', 'VideosController@upload')->name('upload');

Route::middleware('auth')->delete('{video}', 'VideosController@destroy')->name('delete');

Route::prefix('webhook')->name('webhook.')->group(function() {

	Route::get('{video}', 'VideosController@webhook')->name('show');

});