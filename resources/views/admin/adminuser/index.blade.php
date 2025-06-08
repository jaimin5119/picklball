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
                <li class="breadcrumb-item active">Users Listing</li>
            </ul>
        </div>
    </div>
    <section>
        <div class="container-fluid">
            <header>
                <div class="row">
                    <div class="col-md-7">
                        <h2 class="h3 display">Users Listing</h2>
                    </div>
                    <!-- <div class="col-md-5">
                       <a class="btn btn-success pull-right rounded-pill" href="{{ route('export-users') }}">Export Users</a>
                    </div> -->
                </div>
            </header>
            <div class="card">

                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table id="page-length-option" class="table table-striped table-hover multiselect">
                            <thead>
                            <tr>
                                <th>
                                    <center>No</center>
                                </th>
                                <th>Name</th>
                                <th>Email</th>
                           
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                                @if(isset($list) && !empty($list))
                                    @foreach ($list as $key => $l)
                                    <tr>
                                        <td width="2%">
                                            <center>{{ $loop->iteration }}</center>
                                        </td>
                                        <td width="10%">
                                            {{ $l->f_name.' '.$l->l_name }}
                                        </td>
                                        <td width="10%">
                                            {{ $l->email }}
                                        </td>
                                       
                                        <td width="10%">
                                       
                                        <a href="{{ route('admin.admineditPage', [ $l->id ]) }}" class="p-2" title="View/Edit FAQ">
                                                    <span class="fa fa-edit"></span>
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

            $('.bchoice').on('click', function(){
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
                        text: "You want to "+set+" this user account!",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonClass: btn,
                        confirmButtonText: "Yes, "+set+" it!",
                        closeOnConfirm: false
                    },
                    function (isConfirm) {
                        if (isConfirm) {
                            $.ajax({
                                url: "{{ route('admin.block_unblock_users') }}",
                                type: "post",
                                data: {
                                    "id": id
                                },
                                success: function (result) {
                                    
                                    swal({
                                        title: "User account has been "+set+"ed!",
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
