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

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();
Route::get('/', 'HomeController@index')->name('home');

Route::group(['prefix' => '/site', 'as' => 'site.'], function() {
    Route::get('/index', 'SiteController@index')->name('index');
    Route::get('/create', 'SiteController@create')->name('create');
    Route::post('/store', 'SiteController@store')->name('store');
    Route::get('/map', 'SiteController@map')->name('map');
    Route::delete('/delete/{site}', 'SiteController@delete')->name('delete');
    Route::get('/analyze', 'AnalysisController@analyze')->name('analyze');
    Route::post('/analysis', 'AnalysisController@analysis')->name('analysis');
});

Route::group(['prefix' => '/waypoint', 'as' => 'waypoint.'], function() {
    Route::get('/index', 'WaypointController@index')->name('index');
    Route::get('/create', 'WaypointController@create')->name('create');
    Route::get('/edit/{waypoint}', 'WaypointController@edit')->name('edit');
    Route::put('/update/{waypoint}', 'WaypointController@update')->name('update');
    Route::post('/store', 'WaypointController@store')->name('store');
    Route::delete('/delete/{waypoint}', 'WaypointController@delete')->name('delete');
});

Route::group(['prefix' => '/path', 'as' => 'path.'], function() {
    Route::get('/index', 'PathController@index')->name('index');
    Route::get('/create', 'PathController@create')->name('create');
    Route::post('/store', 'PathController@store')->name('store');
    Route::delete('/delete/{path}', 'PathController@delete')->name('delete');
});

Route::group(['prefix' => '/slide', 'as' => 'slide.'], function() {
    Route::get('/image/{slide}', 'SlideController@image')->name('image');
});