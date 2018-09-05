<?php

use Illuminate\Support\Facades\Redis;


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

Route::get('/redis', function() {
    // Redis::set('friend', 'momo');
    // Redis::lpush('frameworks', ['vuejs', 'laravel']);
    // Redis::sadd('frontend-frameworks', ['angular', 'ember']);
    // dd(Redis::smembers('frontend-frameworks'));
    // dd(Redis::get('friend'));
    // dd(Redis::lrange('frameworks', 0, -1));
    // dd('test');
});

Route::get('/', 'FrontendController@welcome');

Route::get('/register/confirm/', 'ConfirmEmailController@index')->name('confirm-email');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/logout', function() {
    auth()->logout();
    return redirect('/');
});

// Route::middleware('admin')->prefix('admin')->group(function() {
//     Route::resource('series', 'SeriesController');
//     Route::resource('{series_by_id}/lessons', 'LessonController');
// });
