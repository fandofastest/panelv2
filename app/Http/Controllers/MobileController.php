<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Song;
use App\Models\Plays;
use App\Models\Playlist;
use App\Models\Album;
use App\Models\Genre;
use App\Models\Artist;
use App\Models\Playlistsong;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isEmpty;

class MobileController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $baseapiurl=asset('storage/');

        $song['song'] = Song::select('songs.id','albums.year',DB::raw('CONCAT("'.url('api/mobile').'/play/",songs.id) as filemp3'),DB::raw('CONCAT("'.$baseapiurl.'/songlirik/",songs.lyric) as lyric'),'songs.duration as duration','songs.title as songname',DB::raw('CONCAT("'.$baseapiurl.'/songcover/",songs.cover) as songcover'),'artists.name as artistname',DB::raw('CONCAT("'.$baseapiurl.'/artist/",artists.cover) as artistcover'),'genres.name as genrename',DB::raw('CONCAT("'.$baseapiurl.'/genre/",genres.cover) as genrecover'),'albums.name as albumname',DB::raw('CONCAT("'.$baseapiurl.'/album/",albums.cover) as albumcover'),'songs.plays')
        ->join('artists','artists.id','songs.artist_id')
        ->join('genres','genres.id','songs.genre_id')
        ->join('albums','albums.id','songs.album_id')
        ->orderByDesc('songs.created_at')
        ->get();

        return response()->json($song);

        //
    }

    public function weekly($limit=25)
    {
         $baseapiurl=asset('storage/');

        // $song['song'] = Plays::select('songs.id','albums.year',DB::raw('CONCAT("'.url('api/mobile').'/play/",songs.id) as filemp3'),DB::raw('CONCAT("'.$baseapiurl.'/songlirik/",songs.lyric) as lyric'),'songs.duration as duration','songs.title as songname',DB::raw('CONCAT("'.$baseapiurl.'/songcover/",songs.cover) as songcover'),'artists.name as artistname',DB::raw('CONCAT("'.$baseapiurl.'/artist/",artists.cover) as artistcover'),'genres.name as genrename',DB::raw('CONCAT("'.$baseapiurl.'/genre/",genres.cover) as genrecover'),'albums.name as albumname',DB::raw('CONCAT("'.$baseapiurl.'/album/",albums.cover) as albumcover'),'songs.plays')
        // ->join('artists','artists.id','songs.artist_id')
        // ->join('genres','genres.id','songs.genre_id')
        // ->join('albums','albums.id','songs.album_id')
        // ->orderByDesc('songs.created_at')
        // ->get();

        $date = \Carbon\Carbon::today()->subDays(7);


        if (empty($date)){
            $data = Plays::select('songid',DB::raw('count(*) as total'))
            ->join('songs','songs.id','plays.songid')
            // ->where('plays.created_at','>=',$date)
            ->groupBy('plays.songid')
            ->limit($limit)
            ->get();

        }

        else {
            $data = Plays::select('songid',DB::raw('count(*) as total'))
            ->join('songs','songs.id','plays.songid')
            ->where('plays.created_at','>=',$date)
            ->groupBy('plays.songid')
            ->limit($limit)
            ->get();
        }


        $new['topweekley']=[];

        foreach ($data as $mysong) {
            $song=Song::select('songs.id','albums.year',DB::raw('CONCAT("'.url('api/mobile').'/play/",songs.id) as filemp3'),DB::raw('CONCAT("'.$baseapiurl.'/songlirik/",songs.lyric) as lyric'),'songs.duration as duration','songs.title as songname',DB::raw('CONCAT("'.$baseapiurl.'/songcover/",songs.cover) as songcover'),'artists.name as artistname',DB::raw('CONCAT("'.$baseapiurl.'/artist/",artists.cover) as artistcover'),'genres.name as genrename',DB::raw('CONCAT("'.$baseapiurl.'/genre/",genres.cover) as genrecover'),'albums.name as albumname',DB::raw('CONCAT("'.$baseapiurl.'/album/",albums.cover) as albumcover'),'songs.plays')
            ->join('artists','artists.id','songs.artist_id')
            ->join('genres','genres.id','songs.genre_id')
            ->join('albums','albums.id','songs.album_id')
            ->where('songs.id',$mysong->songid)->first();
            $song->plays=$mysong->total;
            array_push($new['topweekley'],$song);

            # code...
        }
        // dd($data);

        return response()->json($new    );

        //
    }



    public function getAllAlbum($limit=25)
    {

        $baseapiurl=asset('storage/');
        $album = Album::select('.albums.id as id','year','albums.name as name',DB::raw('CONCAT("'.$baseapiurl.'/album/",albums.cover) as cover'),'albums.deskripsi as deskripsi','genres.name as genre','artists.name as artist','albums.plays',DB::raw('CONCAT("'.$baseapiurl.'/artist/",artists.cover) as artistcover'))
        ->join('genres','genres.id','albums.genre_id')
        ->join('artists','artists.id','albums.artist_id')
        ->limit($limit)
        ->orderBy('plays', 'desc')
        ->get();
        $new['album']=[];
        foreach ($album as $data ) {

                $data->totalsong=$this->countSongbyalbumid($data->id);
                array_push($new['album'],$data);

            # code...
        }

        // $album=Album::all();
        return response()->json($new);

        //
    }




    public function getAllAlbumWtihSongs($limit=25)
    {

        $baseapiurl=asset('storage/');
        $album = Album::select('.albums.id as id','year','albums.name as name',DB::raw('CONCAT("'.$baseapiurl.'/album/",albums.cover) as cover'),'albums.deskripsi as deskripsi','genres.name as genre','artists.name as artist','albums.plays',DB::raw('CONCAT("'.$baseapiurl.'/artist/",artists.cover) as artistcover'))
        ->join('genres','genres.id','albums.genre_id')
        ->join('artists','artists.id','albums.artist_id')
        ->limit($limit)
        ->orderBy('plays', 'desc')
        ->get();
        $new['album']=[];
        foreach ($album as $data ) {
                $data->totalsong=$this->countSongbyalbumid($data->id);
                $data->songs = Song::select('songs.id','albums.year',DB::raw('CONCAT("'.url('api/mobile').'/play/",songs.id) as filemp3'),'songs.duration as duration','songs.title as songname',DB::raw('CONCAT("'.$baseapiurl.'/songcover/",songs.cover) as songcover'),'artists.name as artistname',DB::raw('CONCAT("'.$baseapiurl.'/artist/",artists.cover) as artistcover'),'genres.name as genrename',DB::raw('CONCAT("'.$baseapiurl.'/genre/",genres.cover) as genrecover'),'albums.name as albumname',DB::raw('CONCAT("'.$baseapiurl.'/album/",albums.cover) as albumcover'),DB::raw('CONCAT("'.$baseapiurl.'/songlirik/",songs.lyric) as lyric'),'songs.plays')
                ->join('artists','artists.id','songs.artist_id')
                ->join('albums','albums.id','songs.album_id')
                ->join('genres','genres.id','songs.genre_id')
                ->where('albums.id',$data->id)
                ->get();
                array_push($new['album'],$data);

            # code...
        }

        // $album=Album::all();
        return response()->json($new);

        //
    }




    public function getAllPlaylist($limit=25)
    {

        $baseapiurl=asset('storage/');
        $playlist = Playlist::select('id','name',DB::raw('CONCAT("'.$baseapiurl.'/playlist/",cover) as cover'))
        ->where('id', '!=' , 1)
        ->limit($limit)
        ->get();
        $new['playlist']=[];
        foreach ($playlist as $data ) {
                $data->totalsong=$this->countSong($data->id);
                array_push($new['playlist'],$data);

            # code...
        }


        // $album=Album::all();
        return response()->json($new);

        //
    }



    public function getAllPlaylistWithSong($limit=25)
    {

        $baseapiurl=asset('storage/');
        $playlist = Playlist::select('id','name',DB::raw('CONCAT("'.$baseapiurl.'/playlist/",cover) as cover'))
        ->where('id', '!=' , 1)
        ->limit($limit)
        ->get();
        $new['result']=[];
        foreach ($playlist as $data ) {
            // dd($playlist);
            $data->totalsong=$this->countSong($data->id);
            // $data->playlistname=$data->name;
            $data->songs= Playlistsong::select('songs.id','albums.year',DB::raw('CONCAT("'.url('api/mobile').'/play/",songs.id) as filemp3'),'songs.duration as duration','songs.title as songname',DB::raw('CONCAT("'.$baseapiurl.'/songcover/",songs.cover) as songcover'),'artists.name as artistname',DB::raw('CONCAT("'.$baseapiurl.'/artist/",artists.cover) as artistcover'),'genres.name as genrename',DB::raw('CONCAT("'.$baseapiurl.'/genre/",genres.cover) as genrecover'),'albums.name as albumname',DB::raw('CONCAT("'.$baseapiurl.'/album/",albums.cover) as albumcover'),DB::raw('CONCAT("'.$baseapiurl.'/songlirik/",songs.lyric) as lyric'),'songs.plays')
            ->join('songs','songs.id','playlistsongs.song_id')
            ->join('artists','artists.id','songs.artist_id')
            ->join('albums','albums.id','songs.album_id')
            ->join('genres','genres.id','songs.genre_id')
            ->where('playlistsongs.playlist_id',$data->id)
            ->get();

            array_push($new['result'],$data);

        # code...
    }

        // $album=Album::all();
        return response()->json($new);

        //
    }





    public function getPlaylistByName($name)
    {

        $baseapiurl=asset('storage/');
        $playlist = Playlist::select('id','name',DB::raw('CONCAT("'.$baseapiurl.'/playlist/",cover) as cover'))

        ->where('name','=',$name)
        ->get();
        $new['result']=[];
        foreach ($playlist as $data ) {
                $data->totalsong=$this->countSong($data->id);
                $data->songs= Playlistsong::select('songs.id','albums.year',DB::raw('CONCAT("'.url('api/mobile').'/play/",songs.id) as filemp3'),'songs.duration as duration','songs.title as songname',DB::raw('CONCAT("'.$baseapiurl.'/songcover/",songs.cover) as songcover'),'artists.name as artistname',DB::raw('CONCAT("'.$baseapiurl.'/artist/",artists.cover) as artistcover'),'genres.name as genrename',DB::raw('CONCAT("'.$baseapiurl.'/genre/",genres.cover) as genrecover'),'albums.name as albumname',DB::raw('CONCAT("'.$baseapiurl.'/album/",albums.cover) as albumcover'),DB::raw('CONCAT("'.$baseapiurl.'/songlirik/",songs.lyric) as lyric'),'songs.plays')
                ->join('songs','songs.id','playlistsongs.song_id')
                ->join('artists','artists.id','songs.artist_id')
                ->join('albums','albums.id','songs.album_id')
                ->join('genres','genres.id','songs.genre_id')
                ->where('playlistsongs.playlist_id',$data->id)
                ->get();

                array_push($new['result'],$data);

            # code...
        }
        // dd($new['playlist']);

        // dd($new['result']);



        // $album=Album::all();
        return response()->json($new);

        //
    }


    public function getAllArtist($limit=25)
    {
        $baseapiurl=asset('storage/');
        $artist['artist'] = Artist::select('id','name',DB::raw('CONCAT("'.$baseapiurl.'/artist/",cover) as cover'))
        ->limit(5)
        ->get();
        // dd('ssss');
        return response()->json($artist);
        //
    }
    public function getAllGenre($limit=25)
    {

        $baseapiurl=asset('storage/');
        $genre['genre'] = Genre::select('id','name',DB::raw('CONCAT("'.$baseapiurl.'/genre/",cover) as cover'))
        ->limit(5)
        ->get();

        // $album=Album::all();
        return response()->json($genre);

        //
    }


    public function getSongByTitle(String $title)
    {

        $baseapiurl=asset('storage/');

        $song['song'] = Song::select('songs.id','albums.year',DB::raw('CONCAT("'.url('api/mobile').'/play/",songs.id) as filemp3'),DB::raw('CONCAT("'.$baseapiurl.'/songlirik/",songs.lyric) as lyric'),'songs.duration as duration','songs.title as songname',DB::raw('CONCAT("'.$baseapiurl.'/songcover/",songs.cover) as songcover'),'artists.name as artistname',DB::raw('CONCAT("'.$baseapiurl.'/artist/",artists.cover) as artistcover'),'genres.name as genrename',DB::raw('CONCAT("'.$baseapiurl.'/genre/",genres.cover) as genrecover'),'albums.name as albumname',DB::raw('CONCAT("'.$baseapiurl.'/album/",albums.cover) as albumcover'),'songs.plays')
        ->join('artists','artists.id','songs.artist_id')
        ->join('genres','genres.id','songs.genre_id')
        ->join('albums','albums.id','songs.album_id')
        ->where('songs.title', 'like', "%{$title}%")
        ->orWhere('artists.name', 'like', "%{$title}%")
        ->get();

        return response()->json($song);

        //
    }

    public function getSongById(String $id,String $isLocal)
    {

        $baseapiurl=asset('storage/');

        if ($isLocal=='true') {
            $baseapiurl='';

            # code...
        }

        $song['song'] = Song::select('songs.id','albums.year',DB::raw('CONCAT("'.url('api/mobile').'/play/",songs.id) as filemp3'),DB::raw('CONCAT("'.$baseapiurl.'/songlirik/",songs.lyric) as lyric'),'songs.duration as duration','songs.title as songname',DB::raw('CONCAT("'.$baseapiurl.'/songcover/",songs.cover) as songcover'),'artists.name as artistname',DB::raw('CONCAT("'.$baseapiurl.'/artist/",artists.cover) as artistcover'),'genres.name as genrename',DB::raw('CONCAT("'.$baseapiurl.'/genre/",genres.cover) as genrecover'),'albums.name as albumname',DB::raw('CONCAT("'.$baseapiurl.'/album/",albums.cover) as albumcover'),'songs.plays')
        ->join('artists','artists.id','songs.artist_id')
        ->join('genres','genres.id','songs.genre_id')
        ->join('albums','albums.id','songs.album_id')
        ->where('songs.id',$id)
        ->get();

        return response()->json($song);

        //
    }

    public function getSongByPlaylist(String $playlistid)
    {

        $baseapiurl=asset('storage/');



        $song['playlist'] = Playlistsong::select('songs.id','albums.year',DB::raw('CONCAT("'.url('api/mobile').'/play/",songs.id) as filemp3'),'songs.duration as duration','songs.title as songname',DB::raw('CONCAT("'.$baseapiurl.'/songcover/",songs.cover) as songcover'),'artists.name as artistname',DB::raw('CONCAT("'.$baseapiurl.'/artist/",artists.cover) as artistcover'),'genres.name as genrename',DB::raw('CONCAT("'.$baseapiurl.'/genre/",genres.cover) as genrecover'),'albums.name as albumname',DB::raw('CONCAT("'.$baseapiurl.'/album/",albums.cover) as albumcover'),DB::raw('CONCAT("'.$baseapiurl.'/songlirik/",songs.lyric) as lyric'),'songs.plays')
        ->join('songs','songs.id','playlistsongs.song_id')
        ->join('artists','artists.id','songs.artist_id')
        ->join('albums','albums.id','songs.album_id')
        ->join('genres','genres.id','songs.genre_id')
        ->where('playlistsongs.playlist_id',$playlistid)
        ->get();


        return response()->json($song);

        //
    }

    public function countSong(String $playlistid){

        $song = Playlistsong::select('*')
        ->join('songs','songs.id','playlistsongs.song_id')
        ->join('artists','artists.id','songs.artist_id')
        ->join('albums','albums.id','songs.album_id')
        ->join('genres','genres.id','songs.genre_id')
        ->where('playlistsongs.playlist_id',$playlistid)
        ->get();

        return count($song);

    }


    public function countSongbyalbumid(String $id){

        $song = Song::select('*')
        ->join('albums','albums.id','songs.album_id')
        ->where('songs.album_id',$id)
        ->get();

        return count($song);

    }

    public function getTopChart($limit=25)
    {

        $baseapiurl=asset('storage/');

        $song['song'] = Song::select('songs.id','albums.year',DB::raw('CONCAT("'.$baseapiurl.'/songmp3/",songs.file) as filemp3'),'songs.duration as duration','songs.title as songname',DB::raw('CONCAT("'.$baseapiurl.'/songcover/",songs.cover) as songcover'),'artists.name as artistname',DB::raw('CONCAT("'.$baseapiurl.'/artist/",artists.cover) as artistcover'),'genres.name as genrename',DB::raw('CONCAT("'.$baseapiurl.'/genre/",genres.cover) as genrecover'),'albums.name as albumname',DB::raw('CONCAT("'.$baseapiurl.'/album/",albums.cover) as albumcover'),DB::raw('CONCAT("'.$baseapiurl.'/songlirik/",songs.lyric) as lyric'),'songs.plays')
        ->join('artists','artists.id','songs.artist_id')
        ->join('albums','albums.id','songs.album_id')
        ->join('genres','genres.id','songs.genre_id')
        ->limit($limit)
        ->orderByDesc('songs.plays')
        ->get();

        return response()->json($song);

        //
    }
    public function getSongByGenre(String $genrename)
    {

        $baseapiurl=asset('storage/');

        $song['song'] = Song::select('songs.id','albums.year',DB::raw('CONCAT("'.url('api/mobile').'/play/",songs.id) as filemp3'),'songs.duration as duration','songs.title as songname',DB::raw('CONCAT("'.$baseapiurl.'/songcover/",songs.cover) as songcover'),'artists.name as artistname',DB::raw('CONCAT("'.$baseapiurl.'/artist/",artists.cover) as artistcover'),'genres.name as genrename',DB::raw('CONCAT("'.$baseapiurl.'/genre/",genres.cover) as genrecover'),'albums.name as albumname',DB::raw('CONCAT("'.$baseapiurl.'/album/",albums.cover) as albumcover'),'songs.plays')

        ->join('artists','artists.id','songs.artist_id')
        ->join('albums','albums.id','songs.album_id')
        ->join('genres','genres.id','songs.genre_id')
        ->where('genres.name',$genrename)
        ->get();

        return response()->json($song);

        //
    }
    public function getSongByAlbum(String $albumid)
    {

        $baseapiurl=asset('storage/');

        $song['song'] = Song::select('songs.id','albums.year',DB::raw('CONCAT("'.url('api/mobile').'/play/",songs.id) as filemp3'),'songs.duration as duration','songs.title as songname',DB::raw('CONCAT("'.$baseapiurl.'/songcover/",songs.cover) as songcover'),'artists.name as artistname',DB::raw('CONCAT("'.$baseapiurl.'/artist/",artists.cover) as artistcover'),'genres.name as genrename',DB::raw('CONCAT("'.$baseapiurl.'/genre/",genres.cover) as genrecover'),'albums.name as albumname',DB::raw('CONCAT("'.$baseapiurl.'/album/",albums.cover) as albumcover'),DB::raw('CONCAT("'.$baseapiurl.'/songlirik/",songs.lyric) as lyric'),'songs.plays')
        ->join('artists','artists.id','songs.artist_id')
        ->join('albums','albums.id','songs.album_id')
        ->join('genres','genres.id','songs.genre_id')
        ->where('albums.id',$albumid)
        ->get();

        return response()->json($song);

        //
    }


    public function getTopAlbum($limit=25){


        $baseapiurl=asset('storage/');
        $albumall = Album::orderByDesc('plays')->first();

        // dd($albumall);


            $song['topalbum']=Song::select('songs.id','albums.year',DB::raw('CONCAT("'.url('api/mobile').'/play/",songs.id) as filemp3'),DB::raw('CONCAT("'.$baseapiurl.'/songlirik/",songs.lyric) as lyric'),'songs.duration as duration','songs.title as songname',DB::raw('CONCAT("'.$baseapiurl.'/songcover/",songs.cover) as songcover'),'artists.name as artistname',DB::raw('CONCAT("'.$baseapiurl.'/artist/",artists.cover) as artistcover'),'genres.name as genrename',DB::raw('CONCAT("'.$baseapiurl.'/genre/",genres.cover) as genrecover'),'albums.name as albumname',DB::raw('CONCAT("'.$baseapiurl.'/album/",albums.cover) as albumcover'),'songs.plays')
            ->join('artists','artists.id','songs.artist_id')
            ->join('genres','genres.id','songs.genre_id')
            ->join('albums','albums.id','songs.album_id')
            ->where('albums.id',$albumall->id)
            ->limit($limit)
            ->get();
            // $song->plays=$mysong->total;

            # code...


        return response()->json($song);

        //
    }


    public function playsong(String $idsong){
        $song=Song::where('id',$idsong)->get()->first();
        $song->increment('plays');
        $album=Album::where('id',$song->album_id)->get()->first();
        $album->increment('plays');
        $url= asset('storage/songmp3/')."/".$song->file;
        $playsong=new Plays();
        $playsong->songid=$idsong;
        $playsong->save();
         return redirect($url);

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
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
