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

	Route::get('/',function(){
		if (Auth::check()){
			return redirect("/home");
		}else{
			return redirect("/auth/login");
		}
	});
	
	Route::get('/auth/login',function()
	{
        return view('pages.login');
	});

	// evernote
	Route::get("/evernote/authorize",'EvernoteController@getTemporaryCredentials');
	Route::get("/evernote/callback",'EvernoteController@callback');

	Route::get("/evernote/getNotebook",'EvernoteController@getNotebookGuid');
	Route::get("/evernote/testtitle",'EvernoteController@testNote');


	// login
	Route::auth();
	Route::get('auth/{provider}', 'LoginController@login');
	Route::get('callback/{provider}', 'LoginController@callback');
	Route::post('callback/{provider}', 'LoginController@callback');
	Route::get('logout','LoginController@logout');


	// amaon api testing
	// Route::get('/', ['middleware' => 'auth', 'uses' => 'NoteController@index']);
	// Route::get('index', ['middleware' => 'auth', 'uses' => 'NoteController@index']);
	// //Route::post('search', ['middleware' => 'auth', 'uses' => 'NoteController@search']);
	// Route::get('searchbooklists/{page?}', ['middleware' => 'auth', 'uses' => 'NoteController@searchBookLists']);
	// Route::post('notesubmit', ['middleware' => 'auth', 'uses' => 'NoteController@notesubmit']);

	// memo
	Route::get('home', ['middleware' => 'auth', 'uses' => 'MemoController@index']);
	Route::get('memo/{isbn}', ['middleware' => 'auth', 'uses' => 'MemoController@show']);
	Route::post('memo/post', ['middleware' => 'auth', 'uses' => 'MemoController@post']);
	Route::get('memo/delete/{id}', ['middleware' => 'auth', 'uses' => 'MemoController@delete']);
	Route::get('memo/edit/{id}', ['middleware' => 'auth', 'uses' => 'MemoController@edit']);
	Route::post('memo/edit/{id}/save', ['middleware' => 'auth', 'uses' => 'MemoController@update']);

	// search
	Route::post('search', ['middleware' => 'auth', 'uses' => 'SearchController@postSearch']);
	Route::get('search', ['middleware' => 'auth', 'uses' => 'SearchController@search']);

	// wishList
	Route::post('wishlist/add',['middleware' => 'auth', 'uses' => 'WishListController@addWishList']);
	Route::post('wishlist/delete',['middleware' => 'auth', 'uses' => 'WishListController@deleteFromWishList']);
	Route::get('wishlist/show/{page?}',['middleware' => 'auth', 'uses' => 'WishListController@show']);



});
