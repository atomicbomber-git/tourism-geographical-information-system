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

use App\Http\Controllers\DijkstraRouteController;

Auth::routes();
Route::get('/', 'HomeController@index')->name('home');

Route::group(['prefix' => '/site', 'as' => 'site.'], function() {
    Route::get('/index', 'SiteController@index')->name('index');
    Route::get('/create', 'SiteController@create')->name('create');
    Route::get('/search', 'SiteController@search')->name('search');
    Route::get('/detail/{site}', 'SiteController@detail')->name('detail');
    Route::get('/edit/{site}', 'SiteController@edit')->name('edit');
    Route::post('/update/{site}', 'SiteController@update')->name('update');
    Route::post('/store', 'SiteController@store')->name('store');
    Route::get('/map', 'SiteController@map')->name('map');
    Route::delete('/delete/{site}', 'SiteController@delete')->name('delete');
    Route::get('/image/{site}', 'SiteController@image')->name('image');
    Route::get('/thumbnail/{site}', 'SiteController@thumbnail')->name('thumbnail');
});

Route::group(['prefix' => '/site-analysis', 'as' => 'site-analysis.'], function() {
    Route::get('/create', 'AnalysisController@create')->name('create');
    Route::post('/result', 'AnalysisController@result')->name('result');
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

Route::group(['prefix' => '/site-category', 'as' => 'site-category.'], function() {
    Route::get('/index', 'SiteCategoryController@index')->name('index');
    Route::get('/create', 'SiteCategoryController@create')->name('create');
    Route::post('/store', 'SiteCategoryController@store')->name('store');
    Route::get('/edit/{site_category}', 'SiteCategoryController@edit')->name('edit');
    Route::post('/update/{site_category}', 'SiteCategoryController@update')->name('update');
    Route::post('/delete/{site_category}', 'SiteCategoryController@delete')->name('delete');
});

Route::group(['prefix' => '/site-category-item', 'as' => 'site-category-item.'], function() {
    Route::get('/index/{site_category}', 'SiteCategoryItemController@index')->name('index');
});

Route::group(['prefix' => '/slide', 'as' => 'slide.'], function() {
    Route::get('/index', 'SlideController@index')->name('index');
    Route::get('/create', 'SlideController@create')->name('create');
    Route::post('/store', 'SlideController@store')->name('store');
    Route::post('/delete/{slide}', 'SlideController@delete')->name('delete');
    Route::get('/edit/{slide}', 'SlideController@edit')->name('edit');
    Route::post('/update/{slide}', 'SlideController@update')->name('update');
    Route::get('/image/{slide}', 'SlideController@image')->name('image');
});

Route::group(['prefix' => '/dijkstra-route', 'as' => 'dijkstra-route.'], function() {
    Route::get('/create', [DijkstraRouteController::class, 'create'])->name('create');
    Route::get('/show', [DijkstraRouteController::class, 'show'])->name('show');
});
