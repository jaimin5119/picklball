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
                <li class="breadcrumb-item active">View</li>
            </ul>
        </div>
    </div>
    <section>
        <div class="container-fluid">
            <header>
                <div class="row">
                    <div class="col-md-7">
                        <h2 class="h3 display">View</h2>
                    </div>
                  
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
                                <th>User Name</th>
                                <th>Page Name</th>
                                 <th>Count</th>
                                <th>Date</th>
                                <th>Time</th>
                            </tr>
                            </thead>
                            <tbody>
                                  @if(count($list))
                                    @foreach ($list as $key => $l)
                                    <tr>
                                        <td width="2%">
                                            <center>{{$key+1}}</center>
                                        </td>
                                        <td width="10%">
                                           {{$l->first_name}} {{$l->last_name}}
                                        </td>
                                        <td width="10%">
                                            {{$l->page_name}}
                                        </td>
                                        <td width="10%">
                                            {{$l->count}}
                                        </td>
                                        <td width="10%">
                                            {{@date('d-M-Y', strtotime($l->current_date));}}
                                        </td>
                                        <td width="10%">
                                            {{@Carbon\Carbon::parse($l->current_date)->format('h:i:A' ) }}
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
