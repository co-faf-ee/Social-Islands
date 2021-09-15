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
//use Auth;
//Route::resource('signup', 'UsersController');

Route::get('/', 'PagesController@getHome');

Route::get('/signup', 'UsersController@showSignUp')->middleware('guest');
Route::post('/signup', 'UsersController@signUp')->middleware('guest');

Route::get('/login', 'UsersController@showLogin')->middleware('guest');
Route::post('/login', 'AuthUserController@doLogin')->middleware('guest');
Route::get('/logout', 'AuthUserController@Logout')->middleware('auth');

Route::get('/dashboard', 'UsersController@showDashboard')->middleware('auth');

Route::get('/store', 'StoreController@index');
//Route::get('/store/hidden', 'StoreController@showOldAssets')->middleware('auth');
Route::get('/store/shirts', 'StoreController@indexShirt');
Route::get('/store/trousers', 'StoreController@indexPants');
Route::get('/store/create', 'StoreController@create')->middleware('auth');
Route::post('/store/create', 'StoreController@store')->middleware('auth');
Route::get('/store/create/asset', 'StoreController@createAsset')->middleware('auth');
Route::post('/store/create/asset', 'StoreController@upload')->middleware('auth');
Route::get('/store/assets/pending', 'StoreController@showUploads')->middleware('auth');
Route::get('/store/assets/moderate', 'StoreController@showUploadsMod')->middleware('auth');
Route::get('/store/asset/accept/{id}', 'StoreController@acceptAsset')->middleware('auth');
Route::get('/store/asset/reject/{id}', 'StoreController@rejectAsset')->middleware('auth');
Route::get('/store/{id}', 'StoreController@show'); // Do not have to be signed in to view items
Route::post('/store/{id}', 'StoreController@buy')->middleware('auth');
Route::get('/store/{id}/sell', 'StoreController@showSell')->middleware('auth');
Route::post('/store/{id}/sell', 'StoreController@doSell')->middleware('auth');
Route::get('/store/{id}/buy/{saleid}', 'StoreController@buySale')->middleware('auth');
Route::get('/store/{id}/remove/{saleid}', 'StoreController@removeSale')->middleware('auth');
Route::post('/store/{id}/c', 'StoreController@comment')->middleware('auth');
//Route::post('/store/{id}/comment', 'StoreController@comment')->middleware('auth');

Route::get('/islands/create', 'IslandsController@create')->middleware('auth');
Route::post('/islands/create', 'IslandsController@store')->middleware('auth');
Route::get('/islands/', 'IslandsController@index');
Route::get('/islands/{id}', 'IslandsController@show');
Route::get('/islands/edit/{id}', 'IslandsController@edit')->middleware('auth');
Route::post('/islands/edit/{id}', 'IslandsController@update')->middleware('auth');
Route::get('/islands/{id}/play', 'IslandsController@play')->middleware('auth');
Route::get('/islands/{id}/playtest', 'IslandsController@playtest')->middleware('auth');


Route::get('/search/', 'UsersController@showSearch')->middleware('auth');

Route::get('/user/{id}', 'UsersController@showUser')->middleware('auth');
Route::get('/user/{id}/ban', 'UsersController@showBanMod')->middleware('auth');
Route::post('/user/{id}/ban', 'UsersController@doBan')->middleware('auth');
Route::get('/user/{id}/unban', 'UsersController@doUnban')->middleware('auth');

Route::get('/avatar', 'UsersController@showCustomise')->middleware('auth');
Route::get('/avatar/{id}', 'UsersController@doCustomise')->middleware('auth');
Route::get('/avatar/skin/{id}', 'UsersController@doCustomiseSkin')->middleware('auth');
Route::get('/avatarclean', 'UsersController@resetCustomise')->middleware('auth');

// Avatar get layers
Route::get('/avatar/category/{layer}', 'UsersController@showCustomiseCategory')->middleware('auth');

Route::get('/account', 'UsersController@showSettings')->middleware('auth');
Route::get('/account/dark', 'UsersController@toggleDark')->middleware('auth');
Route::post('/account/bio', 'UsersController@updateBio')->middleware('auth');
Route::get('/account/genkey', 'UsersController@Genkey')->middleware('auth');

Route::get('/verify/request', 'UsersController@SendVerify')->middleware('auth');
Route::get('/verify/', 'UsersController@ShowVerify')->middleware('auth');
Route::get('/v/{hash}', 'UsersController@ConfirmVerify')->middleware('auth');

Route::get('/chat', 'UsersController@ShowChat');
//Route::get('/chat/test/{test}', 'UsersController@TestChat');
Route::post('/chat/send', 'UsersController@SendChat'); // post not get
Route::get('/chat/send', 'UsersController@AbortChat'); // ugly hack
//Route::get('500', function(){abort(500);});

Route::get('/panel', 'UsersController@showPanel')->middleware('auth');

Route::get('/banned', 'UsersController@showBanned')->middleware('auth');

Route::get('/upgrade', 'UsersController@showUpgrade')->middleware('auth');
//Route::get('/games', 'MessagesController@showMessages');
