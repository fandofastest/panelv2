<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\AlbumController;
use App\Http\Controllers\SongController;
use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\MobileController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('artist/{artistid}', [ArtistController::class, 'getDetailJson'])->name('getDetailArtist');
Route::get('album/{albumid}', [AlbumController::class, 'getDetailJson'])->name('getDetailAlbum');
Route::get('song/{songid?}', [SongController::class, 'getDetailJson'])->name('getDetailSong');
Route::post('song/update', [SongController::class, 'update'])->name('update');

Route::get('getplaylist/{songid}', [PlaylistController::class, 'getPlaylist'])->name('getPlaylist');
Route::get('addtoplaylist/{playlist_id}/{songid}', [PlaylistController::class, 'addtoplaylist'])->name('addtoplaylist');
Route::get('rmplaylist/{playlist_id}/{songid}', [PlaylistController::class, 'rmplaylist'])->name('rmplaylist');
Route::get('listsongbyplaylist/{playlist_id}', [PlaylistController::class, 'listsongbyplaylist'])->name('listsongbyplaylist');
Route::get('listallsongbyplaylist/{playlist_id}', [PlaylistController::class, 'listallsongbyplaylist'])->name('listallsongbyplaylist');




Route::get('mobile/', [MobileController::class, 'index'])->name('index');
Route::get('mobile/find/{title}/', [MobileController::class, 'getSongByTitle'])->name('getSongByTitle');

Route::get('mobile/play/{idsong}', [MobileController::class, 'playsong'])->name('playsong');

Route::get('mobile/getallalbum/{limit?}', [MobileController::class, 'getAllAlbum'])->name('getAllAlbum');
Route::get('mobile/getallalbumwithsongs/{limit?}', [MobileController::class, 'getAllAlbumWtihSongs'])->name('getAllAlbumWtihSongs');
Route::get('mobile/getallplaylist/{limit?}', [MobileController::class, 'getAllPlaylist'])->name('getAllPlaylist');
Route::get('mobile/getallplaylistwithsong/{limit?}', [MobileController::class, 'getAllPlaylistWithSong'])->name('getAllPlaylistWithSong');
Route::get('mobile/getplaylistbyname/{name}', [MobileController::class, 'getPlaylistByName'])->name('getPlaylistByName');
Route::get('mobile/gettopchart/{limit?}', [MobileController::class, 'getTopChart'])->name('getTopChart');

Route::get('mobile/getallartist/{limit?}', [MobileController::class, 'getAllArtist'])->name('getAllArtist');
Route::get('mobile/getallgenre/{limit?}', [MobileController::class, 'getAllGenre'])->name('getAllGenre');

Route::get('mobile/get/{idsong}/{isLocal}/', [MobileController::class, 'getSongById'])->name('getSongById');
Route::get('mobile/playlistsong/{playlistid}/', [MobileController::class, 'getSongByPlaylist'])->name('getSongByPlaylist');
Route::get('mobile/genre/{genrename}/', [MobileController::class, 'getSongByGenre'])->name('getSongByGenre');
Route::get('mobile/album/{albumid}/', [MobileController::class, 'getSongByAlbum'])->name('getSongByAlbum');
Route::get('mobile/gettopalbum/{limit?}', [MobileController::class, 'getTopAlbum'])->name('getTopAlbum');
Route::get('mobile/weekly/{limit?}', [MobileController::class, 'weekly'])->name('weekly');



Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
