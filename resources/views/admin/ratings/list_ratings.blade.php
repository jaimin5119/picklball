@extends('admin.layout')

{{-- page title --}}
@section('title','Master Demo Project')

@section('vendor-style')

@endsection
@section('content')
    <div class="breadcrumb-holder">
        <div class="container-fluid">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dash') }}">Dashboard</a></li>
                {{--                <li class="breadcrumb-item active"><a href="{{route('manageasset')}}">Manage Asset </a></li>--}}
                <li class="breadcrumb-item active">Ratings</li>
            </ul>
        </div>
    </div>
    <section>
        <div class="container-fluid">
            <header>
                <div class="row">
                    <div class="col-md-7">
                        <h2 class="h3 display">Ratings</h2>
                    </div>
                    {{-- <div class="col-md-5">
                        <a href="{{route('admin.add_cms_page')}}" class="btn btn-primary pull-right rounded-pill">Add CMS Page</a>
                    </div> --}}
                </div>
            </header>
            <div class="card">

                <div class="card-body p-4">
                    @if($errors->any())
                        <div class="alert alert-danger  custom-alert-danger   alert-block  " id="successMessage">
                            <button type="button" class="close custom-alert-close" data-dismiss="alert">×</button>
                            <span>{!! implode('', $errors->all('<div>:message</div>')) !!}</span>
                        </div>
                    @endif
                    @if(session()->has('success'))
                        <div class="alert alert-success ">
                            <button type="button" class="close custom-alert-close" data-dismiss="alert">×</button>
                            <span>{{ session()->get('success') }}</span>
                        </div>
                    @endif
                    <div class="table-responsive">
                        <table id="page-length-option" class="table table-striped table-hover multiselect">
                            <thead>
                            <tr>
                                <th>
                                    <center>No</center>
                                </th>
                                <th>User Name</th>
                                <!-- <th>Review Title</th> -->
                                <th>Review</th>
                                <th>Rating</th>
                                <th>Visiblity</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                                @if(isset($list) && !empty($list))
                                    @foreach($list as $key => $l)
                                        <tr>
                                            <td width="2%">
                                                <center>{{ $loop->iteration }}</center>
                                            </td>
                                            <td width="10%">{{ $l->first_name.' '.$l->last_name }}</td>
                                            <!-- <td width="10%">{{ $l->title }}</td> -->
                                            <td width="30%">{{ $l->details }}</td>
                                            <td width="10%">
                                                
                                                @for($i=0; $i < 5; ++$i)
                                                    <i class="fa fa-star{{$l->rating == $i +.5? '-half' : ''}}{{$l->rating <= $i ? '-o':''}}" aria-hidden="true"></i>
                                                @endfor
                                            </td>
                                            <td width="10%">
                                                
                                                @if($l->display == 1)
                                                    <span class="badge badge-success">Visible</span>
                                                @else
                                                    <span class="badge badge-danger">Hidden</span>
                                                @endif
                                            </td>
                                            <td width="10%">
                                                
                                                    @if($l->display == 0)
                                                        
                                                        <a href="#" class="p-2 vchoice" data-id="{{ $l->id.'__'.$l->display }}" title="View/Edit FAQ" d_status="visible">Show</a>
                                                    @else
                                                        <a href="#" class="p-2 vchoice" data-id="{{ $l->id.'__'.$l->display }}" title="View/Edit FAQ" d_status="hidden">Hide</a>
                                                    @endif
                                                    
                                                    <a href="#" class="p-2 del_rating" data-id="{{ $l->id }}" title="Delete">
                                                        <span class="fa fa-trash"></span>
                                                    </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection


{{-- page script --}}
@section('page-script')
    <script>
        $(document).ready(function () {
            $('#page-length-option').DataTable();


            $('.del_rating').on('click', function(){
                event.preventDefault();
                var id = $(this).data("id");

                swal({
                        title: "Are you sure? ",
                        text: "You will not be able to recover this rating record!",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonClass: "btn-danger",
                        confirmButtonText: "Yes, delete it!",
                        closeOnConfirm: false
                    },
                    function (isConfirm) {
                        if (isConfirm) {
                            $.ajax({
                                url: "{{ route('admin.delete_rating') }}",
                                type: "post",
                                data: {
                                    "id": id
                                },
                                success: function (result) {

                                    if (result == 'error')
                                    {
                                        swal({
                                            title: "Error in deleting rating record!",
                                            type: "warning",
                                            showCancelButton: true,
                                            showConfirmButton: false,
                                        }, function () {
                                            location.reload();
                                        });
                                    }
                                    else
                                    {
                                        // alert(result);
                                        //console.log(result);
                                        swal({
                                            title: "Rating has been deleted!",
                                            type: "success",
                                        }, function () {
                                            location.reload();
                                        });
                                    }

                                }
                            });
                        }
                    }
                );
            });


            $('.vchoice').on('click', function(){
                event.preventDefault();
                var set = $(this).attr("d_status");
                var id = $(this).data("id");
                // alert(id);
                var btn = 'btn-success';
                
                if(set == 'hidden'){
                    btn = 'btn-danger'; 
                }
                

                swal({
                        title: "Are you sure? ",
                        text: "You want to make this rating "+set+"!",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonClass: btn,
                        confirmButtonText: "Yes, make it "+set+"!",
                        closeOnConfirm: false
                    },
                    function (isConfirm) {
                        if (isConfirm) {
                            $.ajax({
                                url: "{{ route('admin.rating_visiblity') }}",
                                type: "post",
                                data: {
                                    "id": id
                                },
                                success: function (result) {
                                    
                                    swal({
                                        title: "Rating has been set "+set+"!",
                                        type: "success",
                                    }, function () {
                                        location.reload();
                                    });
                                }
                            });
                        }
                    });
            });
        });
    </script>
@endsection
