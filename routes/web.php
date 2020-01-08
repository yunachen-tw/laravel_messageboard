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

// 登入
Route::get('/login', 'LoginController@index')->name('login');
Route::post('/login/check', 'LoginController@loginCheck');
// 註冊
Route::get('/register', 'LoginController@register');
Route::post('/register/check', 'LoginController@registerCheck');
// 登出
Route::get('/logout', 'LoginController@logout');

// 留言板列表
Route::get('/boardlist', 'BoardController@showlist');
Route::get('/boardlist/user/{user_id}', 'BoardController@showUserBoardlist');
Route::get('/boards/{board_id}', 'BoardController@showMessage')->middleware('auth');
Route::get('/manage', 'BoardController@showManage')->middleware('auth');
Route::delete('/boards/{board_id}', 'BoardController@deleteBoard')->middleware('auth');
Route::post('/boards', 'BoardController@addBoard')->middleware('auth');
Route::post('/messages', 'BoardController@addMessage')->middleware('auth');
Route::delete('/messages/{message_id}', 'BoardController@deleteMessage')->middleware('auth');
Route::post('/messages/{message_id}/scores', 'BoardController@scoreMessage')->middleware('auth');
