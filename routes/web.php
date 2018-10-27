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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::redirect('/', '/waypoint/index');

Route::group(['prefix' => '/site', 'as' => 'site.'], function() {
    Route::get('/map', 'SiteController@map')->name('map');
});

Route::group(['prefix' => '/waypoint', 'as' => 'waypoint.'], function() {
    Route::get('/index', 'WaypointController@index')->name('index');
    Route::get('/create', 'WaypointController@create')->name('create');
    Route::post('/store', 'WaypointController@store')->name('store');
    Route::delete('/delete/{waypoint}', 'WaypointController@delete')->name('delete');
});