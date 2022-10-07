<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Alert;

use App\Models\Album;
use App\Models\Artist;
use App\Models\Genre;
use Illuminate\Http\Request;

class AlbumController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        // $datakehadiran      =   Kehadiran::join('users','users.username','kehadirans.npm')->where('meeting_id',$idmeeting)->get();
        $album = Album::select('albums.id','year','albums.name as albumname','albums.cover as albumcover','albums.deskripsi as deskripsi','artists.name as artistname','artists.cover as artistcover','genres.name as genrename','genres.cover as genrecover')
                        ->join('artists','artists.id','albums.artist_id')
                        ->join('genres','genres.id','albums.genre_id')
                        ->get();


        $artist=Artist::all();
        // dd($album);
        $genre=Genre::all();

        return view('album.index', compact('album','artist','genre'));

    }


    public function getDetailJson($album_id)
    {
        $genre['data'] = Album::select('albums.id as albumid','albums.name as albumname','albums.deskripsi as deskripsi','genres.name as genrename','genres.id as genreid')
        ->join('genres','genres.id','albums.genre_id')
        ->where('albums.id',$album_id)
        ->get();

        return response()->json($genre);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->hasfile('image'))  {

            $file=$request->file('image');
            $filename=$request->input('name');
            $filename = preg_replace('/\s*/', '', $filename);




            $path = Storage::putFileAs('public/album', $request->file('image'),$filename.'.'.$file->extension());


                $album = new Album();
                $album->name=$request->input('name');
                $album->artist_id=$request->input('artist');
                $album->genre_id=$request->input('genre');
                $album->deskripsi=$request->input('deskripsi');
                $album->cover=$filename.'.'.$file->extension();
                $album->year=$request->input('year');
                $album->save();
                Alert::success('Success', 'Album Tersimpan');


            }
            else {
                Alert::error('Gagal', 'Album Gagal Tersimpan');

            }

            //

             return back();
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function show(Album $album)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function edit(Album $album)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Album $album)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function destroy(Album $album)
    {
        $album->delete();
        Alert::success('Success', 'Artist Dihapus');
        return redirect()->route('artist.index');
        //
    }
}
