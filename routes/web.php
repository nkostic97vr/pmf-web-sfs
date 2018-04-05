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

Auth::routes();

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', 'HomeController@index')->name('home');

Route::group(["prefix" => "/admin", "middleware" => "auth"], function () {

    Route::get("/", function () {
        return view("admin.index");
    });

    Route::get("positioning/", function() {
        return view("admin.positioning");
    });

    Route::resource("forums", "ForumsController");
    Route::resource("sections", "SectionsController");

});

Route::group(["prefix" => "/ajax"], function() {

    Route::get("/sort/{table}/{column}/{order}", [
        "as" => "ajax.sort",
        "uses" => "AjaxController@sort"
    ]);

    Route::post("/positioning/save", [
        "as" => "ajax.save.positions",
        "uses" => "AjaxController@savePositions"
    ]);

});
