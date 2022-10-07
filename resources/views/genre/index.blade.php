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
                                <h2 class="text-white mb-0">Genre</h2>
                            </div>
                            <div class="col">
                                <ul class="nav nav-pills justify-content-end">
                                    <li class="nav-item mr-2 mr-md-0" data-toggle="chart"  >
                                        <a type="button" href="#" class="nav-link py-2 px-3 active"  data-toggle="modal" data-target="#modaladdgenre">
                                            <span class="d-none d-md-block">Add Genre</span>
                                            
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
                                                <th scope="col" class="sort" data-sort="budget">Genre</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody class="list">
                                            @foreach ($genre as $item)
                                                
                                           

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
                                                          
                                                            
                                                        <img alt="Image placeholder" src="{{ asset('storage/genre/'.$item['cover'])}}">
                                                            
                                                          
                                                        </a>
                                                        <div class="media-body">
                                                        <span class="name mb-0 text-sm">{{$item['name']}}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                
                                                
                                            
                                                <td class="text-right">
                                                    
                                                <a href="{{url('genre/hapus/'.$item->id)}}" type="button" class="btn btn-danger">Hapus</a>

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
                <div class="modal fade" id="modaladdgenre" tabindex="-1" role="dialog" aria-labelledby="modaladdgenre" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modaladdgenre">Add New Genre</h5>
                       

                        

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                     
                        <form method="POST" action="/genre/add" enctype="multipart/form-data" >
                            @csrf

                            
                            <div class="form-group">
                             <input class="form-control" name="genre" type="text" placeholder="Genre Name" id="example-text-input" required>
                            </div>
                            <div class="custom-file">
                                <input name="image" type="file" class="custom-file-input" id="customFileLang" lang="en" required>
                                <label class="custom-file-label" for="customFileLang">Select file</label>
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