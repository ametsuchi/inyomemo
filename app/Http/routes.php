<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/
Route::group(['middleware' => 'web'],function()
{

	Route::get('/auth/login',function()
	{
        return view('pages.login');
	});


	Route::auth();
	Route::get('auth/{provider}', 'LoginController@login');
	Route::get('callback/{provider}', 'LoginController@callback');

	// amaon api testing
	Route::get('/', ['middleware' => 'auth', 'uses' => 'NoteController@index']);
	Route::get('index', ['middleware' => 'auth', 'uses' => 'NoteController@index']);
	Route::get('item/{isbn}', ['middleware' => 'auth', 'uses' => 'NoteController@item']);
	Route::post('search', ['middleware' => 'auth', 'uses' => 'NoteController@search']);
	Route::get('searchbooklists/{page?}', ['middleware' => 'auth', 'uses' => 'NoteController@searchBookLists']);
	Route::post('notesubmit', ['middleware' => 'auth', 'uses' => 'NoteController@notesubmit']);

	Route::get('home', ['middleware' => 'auth', 'uses' => 'MemoController@index']);

});
