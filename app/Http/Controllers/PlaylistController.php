<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Alert;
use App\Models\Song;
use App\Models\Playlist;
use App\Models\Playlistsong;
use Illuminate\Http\Request;

class PlaylistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $playlist = Playlist::all();
        // dd($playlist);
        return view('playlist.index', compact('playlist'));

    }

    public function listsongbyplaylist($playlist_id)
        {

            $song['data'] = Playlistsong::select('songs.id','songs.file as filemp3','songs.duration as duration','songs.title as songname','songs.cover as songcover','artists.name as artistname','artists.cover as artistcover','playlistsongs.id as playlistid')
            ->join('songs','songs.id','playlistsongs.song_id')
            ->join('artists','artists.id','songs.artist_id')
            ->where('playlistsongs.playlist_id',$playlist_id)
            ->get();

        return response()->json($song);

    }


    public function listallsongbyplaylist($playlist_id)
        {

            $song = Song::select('songs.id','songs.file as filemp3','songs.duration as duration','songs.title as songname','songs.cover as songcover','artists.name as artistname','artists.cover as artistcover')
            ->join('artists','artists.id','songs.artist_id')

            ->get();

            foreach ($song as $s ) {
                $newsong=Playlistsong::where('song_id',$s['id'])
                ->where('playlist_id',$playlist_id)
                ->get();

                $s['status']=1;
                if ($newsong->isEmpty()) {

                $s['status']=0;


                # code...
                }
                    $newpl[]=$s;
                # code...
            }

            $res['data']=$newpl;


        return response()->json($res);

    }
    public function getPlaylist($song_id)
        {
        // $newpl[];
        $playlist = Playlist::all();
        foreach ($playlist as $pl ) {
            $song=Playlistsong::where('song_id',$song_id)
                                ->where('playlist_id',$pl->id)
                                ->get();

             $pl['status']=1;
             if ($song->isEmpty()) {

                $pl['status']=0;


                 # code...
             }
             $newpl[]=$pl;

            # code...
        }

        $res['playlist']=$newpl;


        return response()->json($res);

    }

    public function addtoplaylist($playlist_id,$song_id)
    {
        $check=Playlistsong::where('song_id',$song_id)->where('playlist_id',$playlist_id)->get();
        $playlist=new Playlistsong();

        if ($check->isEmpty()) {

            $playlist->song_id=$song_id;
            $playlist->playlist_id=$playlist_id;
            $playlist->save();
            # code...
        }
        else{
            $playlist->status="exist";
        }
        $res['response']=$playlist;

        return response()->json($res);

    }

    public function rmplaylist($playlist_id,$song_id)
    {
        $check=Playlistsong::where('song_id',$song_id)->where('playlist_id',$playlist_id)->delete();

        $res['response']="success";

        return response()->json($res);

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
        if($request->hasfile('image'))  {

            $file=$request->file('image');
            $filename=$request->input('name');
            $filename = preg_replace('/\s*/', '', $filename);
            // convert the string to all lowercase
            $filename = strtolower($filename);
            $path = Storage::putFileAs('public/playlist', $request->file('image'),$filename.'.'.$file->extension());


                $playlist = new Playlist();
                $playlist->name=$request->input('name');
                $playlist->cover=$filename.'.'.$file->extension();
                $playlist->save();
                Alert::success('Success', 'Playlist Tersimpan');


            }
            else {
                Alert::error('Gagal', 'Genre Playlist Tersimpan');

            }

            //

             return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Playlist  $playlist
     * @return \Illuminate\Http\Response
     */
    public function show(Playlist $playlist)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Playlist  $playlist
     * @return \Illuminate\Http\Response
     */
    public function edit(Playlist $playlist)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Playlist  $playlist
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Playlist $playlist)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Playlist  $playlist
     * @return \Illuminate\Http\Response
     */
    public function destroy(Playlist $playlist)
    {
        $playlist->delete();
        Alert::success('Success', 'Playlist Berhasil Dihapus');
        return redirect()->route('playlist.index');
        //
    }
}
