@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')

    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-12 mb-5 mb-xl-0">
                <div class="card bg-gradient-default shadow">
                    <div class="card-header bg-transparent">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="text-uppercase text-light ls-1 mb-1">Overview</h6>
                                <h2 class="text-white mb-0">List Album</h2>
                            </div>
                            <div class="col">
                                <ul class="nav nav-pills justify-content-end">
                                    <li class="nav-item mr-2 mr-md-0" data-toggle="chart"  >
                                        <a type="button" href="#" class="nav-link py-2 px-3 active"  data-toggle="modal" data-target="#modaladdalbum">
                                            <span class="d-none d-md-block">Add Album</span>

                                        </a>
                                    </li>

                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Chart -->

                            <!-- Chart wrapper -->
                            <div class="table-responsive">















                                <div>
                                    <table class="table align-items-center table-dark">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th scope="col" class="sort" data-sort="name">No</th>
                                                <th scope="col" class="sort" data-sort="budget">Album Name</th>
                                                <th scope="col" class="sort" data-sort="budget">Artist Name</th>
                                                <th scope="col" class="sort" data-sort="budget">Genre Name</th>
                                                <th scope="col" class="sort" data-sort="budget">Deskripsi</th>

                                            </tr>
                                        </thead>
                                        <tbody class="list">
                                            @foreach ($album as $item)



                                            <tr>
                                                <td scope="row">
                                                    <div class="media align-items-center">


                                                        <div class="media-body">
                                                        <span class="name mb-0 text-sm">{{$item['id']}}</span>
                                                        </div>
                                                    </div>
                                                </td>


                                                <td scope="row">
                                                    <div class="media align-items-center">

                                                        <a href="#" class="avatar rounded-circle mr-3">


                                                        <img alt="Image placeholder" src="{{ asset('storage/album/'.$item['albumcover'])}}">


                                                        </a>
                                                        <div class="media-body">
                                                        <span class="name mb-0 text-sm">{{$item['albumname']}}</span>
                                                        </div>
                                                    </div>
                                                </td>

                                                <td scope="row">
                                                    <div class="media align-items-center">

                                                        <a href="#" class="avatar rounded-circle mr-3">


                                                        <img alt="Image placeholder" src="{{ asset('storage/artist/'.$item['artistcover'])}}">


                                                        </a>
                                                        <div class="media-body">
                                                        <span class="name mb-0 text-sm">{{$item['artistname']}}</span>
                                                        </div>
                                                    </div>
                                                </td>

                                                <td scope="row">
                                                    <div class="media align-items-center">

                                                        <a href="#" class="avatar rounded-circle mr-3">


                                                        <img alt="Image placeholder" src="{{ asset('storage/genre/'.$item['genrecover'])}}">


                                                        </a>
                                                        <div class="media-body">
                                                        <span class="name mb-0 text-sm">{{$item['genrename']}}</span>
                                                        </div>
                                                    </div>
                                                </td>

                                                <td scope="row">
                                                    <div class="media align-items-center">


                                                        <div class="media-body">
                                                        <span class="name mb-0 text-sm">{{ Str::limit($item['deskripsi'],10)}}</span>
                                                        </div>
                                                    </div>
                                                </td>


                                                <td class="text-right">

                                                    <form action="{{ route('album.destroy',$item->id) }}" method="POST">

                                                        {{-- <a class="btn btn-info" href="{{ route('artist.show',$item->id) }}">Show</a> --}}

                                                        {{-- <a class="btn btn-primary" href="{{ route('artist.edit',$item->id) }}">Edit</a> --}}

                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="submit" class="btn btn-danger">Delete</button>
                                                    </form>

                                                </td>
                                            </tr>
                                            @endforeach






                                        </tbody>
                                    </table>
                                </div>

                                </div>

                    </div>
                </div>
            </div>

        </div>
        <div class="row mt-5">
            <div class="col-xl-8 mb-5 mb-xl-0">

            </div>
            <div class="col-xl-4">

            </div>
        </div>

        <!-- Modal -->
                <div class="modal fade" id="modaladdalbum" tabindex="-1" role="dialog" aria-labelledby="modaladdalbum" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modaladdalbum">Add New Album</h5>




                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <form method="POST" action="{{ route('album.store') }}" enctype="multipart/form-data" >
                            @csrf
                            <div class="form-group">
                             <input class="form-control" name="year" type="text" placeholder="Year" id="example-text-input" required>
                           
                            </div>
                            
                            <div class="form-group">
                             <input class="form-control" name="name" type="text" placeholder="Album Name" id="example-text-input" required>
                            </div>
                            <div class="form-group">
                           
                        </div>

                            <div class="form-group">
                                <select  class="form-control" name="artist" id="artist" data-toggle="select" title="Simple select" data-live-search="true" data-live-search-placeholder="Search ..." required >
                                  <option>Pilih Artist</option>
                                  @foreach ($artist as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
                                  @endforeach

                                </select>
                              </div>

                              <div class="form-group">
                                <select  class="form-control" name="genre" id="genre" data-toggle="select" title="Simple select" data-live-search="true" data-live-search-placeholder="Search ..." required >
                                  <option>Pilih Genre</option>
                                  @foreach ($genre as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
                                  @endforeach

                                </select>
                              </div>


                            <div class="custom-file">
                                <input name="image" type="file" class="custom-file-input" id="customFileLang" lang="en" required>
                                <label class="custom-file-label" for="customFileLang">Select file</label>
                            </div>
                            <div class="form-group">
                                <textarea class="form-control"  placeholder="Deskripsi" id="exampleFormControlTextarea1" rows="3" name="deskripsi"></textarea>
                            </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </form>
                    </div>
                    </div>

                </div>
                </div>

        @include('layouts.footers.auth')
    </div>
@endsection

@push('js')
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.extension.js"></script>
@endpush
