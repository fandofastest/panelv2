<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;

use App\Models\Genre;
use Illuminate\Http\Request;
use Alert;

class GenreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $genre=Genre::all();
        // dd($genre);

        // dd(Storage::url('genre/test.png'));
        //
        return view('genre/index',compact('genre'));
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
        // dd($request);  
        if($request->hasfile('image'))  {
            
        $file=$request->file('image');    
        $filename=$request->input('genre');    
        $filename = preg_replace('/\s*/', '', $filename);
        // convert the string to all lowercase
        $filename = strtolower($filename);
        $path = Storage::putFileAs('public/genre', $request->file('image'),$filename.'.'.$file->extension());

            
            $genre = new Genre();
            $genre->name=$request->input('genre');
            $genre->cover=$filename.'.'.$file->extension();
            $genre->save();
            Alert::success('Success', 'Genre Tersimpan');


        }
        else {
            Alert::error('Gagal', 'Genre Gagal Tersimpan');

        }
        
        //

         return back();
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Genre  $genre
     * @return \Illuminate\Http\Response
     */
    public function show(Genre $genre)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Genre  $genre
     * @return \Illuminate\Http\Response
     */
    public function edit(Genre $genre)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Genre  $genre
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Genre $genre)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Genre  $genre
     * @return \Illuminate\Http\Response
     */
    public function destroy(Int $id)
    {   
       $delete= Genre::where('id', $id)->delete();
       if($delete==1){
        Alert::success('Success', 'Genre Terhapus');
       }
       else {
        Alert::error('Gagal', 'Genre Gagal Dihapus');

       }
        return redirect()->route('genre');
        //
    }
}
