<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Alert;
use App\Models\Song;
use App\Models\Album;
use App\Models\MP3File;
use App\Models\Artist;
use Illuminate\Http\Request;

class SongController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $artist=Artist::all();

             $song = Song::select('songs.id','songs.file as filemp3','songs.duration as duration','songs.title as songname','songs.cover as songcover','artists.name as artistname','artists.cover as artistcover','genres.name as genrename','genres.cover as genrecover','albums.name as albumname','albums.cover as albumcover')
                        ->join('artists','artists.id','songs.artist_id')
                        ->join('genres','genres.id','songs.genre_id')
                        ->join('albums','albums.id','songs.album_id')
                        ->paginate(10);



        // dd($song);
        // $genre=Genre::all();

        return view('song.index', compact('song','artist'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getDetailJson($song_id=null)
    {



        if($song_id==null){
            $song['data']= Song::select('songs.id','songs.file as filemp3','songs.duration as duration','songs.title as songname','songs.cover as songcover','artists.name as artistname','artists.cover as artistcover','genres.name as genrename','genres.cover as genrecover','albums.name as albumname','albums.cover as albumcover')
        ->join('artists','artists.id','songs.artist_id')
        ->join('genres','genres.id','songs.genre_id')
        ->join('albums','albums.id','songs.album_id')
        ->get();
            # code...
        }

        else {
            $song['data']= Song::select('songs.id','songs.file as filemp3','songs.duration as duration','songs.title as songname','songs.cover as songcover','artists.name as artistname','artists.cover as artistcover','genres.name as genrename','genres.cover as genrecover','albums.name as albumname','albums.cover as albumcover')
        ->join('artists','artists.id','songs.artist_id')
        ->join('genres','genres.id','songs.genre_id')
        ->join('albums','albums.id','songs.album_id')
        ->where('songs.id',$song_id)
        ->get();

        }

        // $song->get();

        return response()->json($song);

    }



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
        if($request->hasfile('mp3'))  {
         $title =pathinfo($request->file('mp3')->getClientOriginalName(),PATHINFO_FILENAME);
        //  dd($title);
        $filemp3=$request->file('mp3');

        $mp3file = new MP3File($filemp3);//http://www.npr.org/rss/podcast.php?id=510282
        $duration1 = $mp3file->getDurationEstimate();//(faster) for CBR only
        $duration1= gmdate("i:s", $duration1);
        //  dd($duration1);

        $filename=$title;
        $filename = preg_replace('/\s*/', '', $filename);
        // convert the string to all lowercase
        $filename = strtolower($filename);
        $pathmp3 = Storage::putFileAs('public/songmp3', $request->file('mp3'),$filename.'.'.$filemp3->extension());
        // dd($pathmp3);

        $filelirik=$request->file('lirik');
        $pathlirik = Storage::putFileAs('public/songlirik', $request->file('lirik'),$filename.'.txt');


        $filecover=$request->file('image');
        $pathcover = Storage::putFileAs('public/songcover', $request->file('image'),$filename.'.'.$filecover->extension());

            $song = new Song();
            $song->title=$title;
            $song->artist_id=$request->input('artist');
            $song->album_id=$request->input('album');
            $song->genre_id=$request->input('genre');
            $song->lyric=$filename.'.txt';
            $song->cover=$filename.'.'.$filecover->extension();
            $song->file=$filename.'.'.$filemp3->extension();
            $song->duration=$duration1;
            $song->plays=$request->input('initplay');
            $album=Album::where('id',$request->input('album'));
            $album->increment('plays',$request->input('initplay'));



            $song->save();
            Alert::success('Success', 'Album Tersimpan');


        }
        else {
            Alert::error('Gagal', 'Album Gagal Tersimpan');

        }
        //
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Song  $song
     * @return \Illuminate\Http\Response
     */
    public function show(Song $song)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Song  $song
     * @return \Illuminate\Http\Response
     */
    public function edit(Song $song)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Song  $song
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
                $jenis=$request->jenis;

                if ($jenis=='title') {
                    $song=Song::where('id',$request->id)
                    ->update(['title' => $request->title]);

                }

                if ($jenis=='cover') {

                    $filecover=$request->file('file');
                    $filename=$request->title.time();
                    $filename = preg_replace('/\s*/', '', $filename);
                    $filename=strtolower($filename);
                    $pathcover = Storage::putFileAs('public/songcover', $filecover,$filename.'.'.$filecover->extension());

                    $song=Song::where('id',$request->id)
                    ->update(['cover' => $filename.'.'.$filecover->extension()]);

                }
                if ($jenis=='mp3') {

                    $filecover=$request->file('file');
                    $filename=$request->title.time();
                    $filename = preg_replace('/\s*/', '', $filename);
                    $filename=strtolower($filename);
                    $mp3file = new MP3File($filecover);//http://www.npr.org/rss/podcast.php?id=510282
                    $duration1 = $mp3file->getDurationEstimate();//(faster) for CBR only
                    $duration1= gmdate("i:s", $duration1);

                    $pathcover = Storage::putFileAs('public/songmp3', $filecover,$filename.'.'.$filecover->extension());

                    $song=Song::where('id',$request->id)
                    ->update(['file' => $filename.'.'.$filecover->extension(),'duration' => $duration1]);

                }
                if ($jenis=='lyric') {

                    $filecover=$request->file('file');
                    $filename=$request->title.time();
                    $filename = preg_replace('/\s*/', '', $filename);
                    $filename=strtolower($filename);
                    $pathcover = Storage::putFileAs('public/songlirik', $filecover,$filename.'.txt');

                    $song=Song::where('id',$request->id)
                    ->update(['lyric' => $filename.'.txt']);

                }



        return response()->json($song);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Song  $song
     * @return \Illuminate\Http\Response
     */
    public function destroy(Song $song)
    {
        $song->delete();
        Alert::success('Success', 'Song Berhasil Dihapus');
        return redirect()->route('song.index');
        //
    }
}
