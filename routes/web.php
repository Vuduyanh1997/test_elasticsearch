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

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', 'SearchController@index');
Route::get('/post/{slug}', 'SearchController@showContent')->name('slug');
Route::post('/search', 'SearchController@search');

Auth::routes();
Route::middleware('auth')->group(function () {
	Route::get('/home', 'HomeController@index')->name('home');
	Route::get('/posts', 'PostController@index')->name('posts.index');
	Route::post('/posts/list', 'PostController@getList')->name('posts.list');
	Route::get('/posts/create', 'PostController@create')->name('posts.create');
	Route::post('/posts/store', 'PostController@store')->name('posts.store');
	Route::get('/posts/edit/{slug}', 'PostController@edit')->name('posts.edit');
	Route::post('/posts/update', 'PostController@update')->name('posts.update');
	Route::post('/posts/destroy', 'PostController@destroy')->name('posts.destroy');
});