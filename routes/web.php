<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SongController;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\AlbumController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\PlaylistController;


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
	return redirect('song');

});

Auth::routes();

Route::get('home/', [HomeController::class, 'index'])->name('home');

Auth::routes();



// Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home');
// Route::get('/song', 'App\Http\Controllers\Songcontroller@index')->name('song');
// Route::get('/album', 'App\Http\Controllers\HomeController@index')->name('album');
// Route::get('/artist', 'App\Http\Controllers\HomeController@index')->name('artist');

Route::group(['middleware' => 'auth'], function () {
	Route::resource('user', 'App\Http\Controllers\UserController', ['except' => ['show']]);
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
	Route::put('profile.update', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);
    Route::put('profile.password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);


	// Route::get('song', [Songcontroller::class, 'index'])->name('song');
	// Route::get('album/', [AlbumController::class, 'index'])->name('album');
	// Route::get('artist', [ArtistController::class, 'index'])->name('artist');

    Route::resource('song', SongController::class);

	Route::resource('album', AlbumController::class);

	Route::resource('artist', ArtistController::class);

	Route::resource('playlist', PlaylistController::class);



	Route::get('genre/', [GenreController::class, 'index'])->name('genre');
	Route::post('genre/add', [GenreController::class, 'store'])->name('genreadd');
	Route::get('genre/hapus/{id}', [GenreController::class, 'destroy'])->name('genrehapus');

});

