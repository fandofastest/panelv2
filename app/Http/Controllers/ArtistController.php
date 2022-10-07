<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Alert;

use App\Models\Artist;
use App\Models\Album;
use Illuminate\Http\Request;

class ArtistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $artist = Artist::all();
        return view('artist.index', compact('artist'));

    }

    public function getDetailJson($artist_id)
    {   
        $album['data'] = Album::select('albums.id as albumid','albums.name as albumname','genres.name as genrename','genres.id as genreid')
        ->join('genres','genres.id','albums.genre_id')
        ->where('artist_id',$artist_id)
        ->get();
        
        return response()->json($album);

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
        //
        // dd($request);
        if($request->hasfile('image'))  {
            
            $file=$request->file('image');    
            $filename=$request->input('name');    
            $title=$request->input('name');
            $filename = preg_replace('/\s*/', '', $filename);
            // convert the string to all lowercase
            $filename = strtolower($filename);
            $path = Storage::putFileAs('public/artist', $request->file('image'),$filename.'.'.$file->extension());
    
                
                $artist = new Artist();
                $artist->name=$title;
                $artist->cover=$filename.'.'.$file->extension();
                $artist->save();
                Alert::success('Success', 'Artist Tersimpan');
    
    
            }
            else {
                Alert::error('Gagal', 'Artist Gagal Tersimpan');
    
            }
            
            //
    
             return back();
            
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Artist  $artist
     * @return \Illuminate\Http\Response
     */
    public function show(Artist $artist)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Artist  $artist
     * @return \Illuminate\Http\Response
     */
    public function edit(Artist $artist)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Artist  $artist
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Artist $artist)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Artist  $artist
     * @return \Illuminate\Http\Response
     */
    public function destroy(Artist $artist)
    {
        //
        $artist->delete();
        Alert::success('Success', 'Artist Dihapus');
        return redirect()->route('artist.index');
                      
    }
}
