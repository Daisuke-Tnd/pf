<?php
Auth::routes();

// 1) User 認証不要
Route::get('home', 'HomeController@index')->name('home');
Route::get('/', 'ItemController@index')->name('item');
Route::get('item/detail', 'ItemController@detail')->name('detail');


// 2) User ログイン後
Route::group(['middleware' => 'auth:user'], function() {
	Route::get('home', 'HomeController@index')->name('home');
	Route::get('cart', 'CartController@index');
	Route::post('cart/add', 'CartController@add')->name('cart.add');
	Route::post('cart/edit', 'CartController@edit')->name('cart.edit');
	Route::get('cart/delete', 'CartController@delete')->name('cart.delete');
});


// 3) Admin 認証不要
Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function() {
	Route::get('/', function () { return redirect('/admin/home'); });
	Route::get('login', 'LoginController@showLoginForm')->name('admin.login');
	Route::post('login', 'LoginController@login');
});


// 4) Admin ログイン後
Route::group(['prefix' => 'admin', 'namespace' => 'Admin',  'middleware' => 'auth:admin'], function() {
	Route::post('logout', 'LoginController@logout')->name('admin.logout');
	Route::get('item', 'ItemController@index');
	Route::get('item/detail', 'ItemController@detail')->name('admin.detail');
	Route::get('item/add', 'ItemController@add')->name('admin.add');
	Route::post('item/add', 'ItemController@create');
	Route::get('item/edit', 'ItemController@edit')->name('admin.edit');
	Route::post('item/edit', 'ItemController@update');
});
