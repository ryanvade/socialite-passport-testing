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


Route::get('/auth/login/github', 'GithubController@oAuthLogin');
Route::get('/auth/github/callback', 'GithubController@handleCallback');

Route::get('/embed', function() {
  $user = auth()->check()? auth()->user() : null;
  return view('embed', ['user' => $user]);
});

Route::get('/auth/success', function() {
  return view('success');
});
