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
                <li class="breadcrumb-item active">FAQs</li>
            </ul>
        </div>
    </div>
    <section>
        <div class="container-fluid">
            <header>
                <div class="row">
                    <div class="col-md-7">
                        <h2 class="h3 display">FAQs</h2>
                    </div>
                    <div class="col-md-5">
                        <a href="{{route('admin.add_faqs')}}" class="btn btn-primary pull-right rounded-pill">Add Faq</a>
                    </div>
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
                                <th>Question</th>
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
                                            <td width="88%">{{ $l->question }}</td>

                                            <td width="10%">
                                                <a href="{{ route('admin.edit_faq', [ $l->id ]) }}" class="p-2" title="View/Edit FAQ">
                                                    <span class="fa fa-edit"></span>
                                                </a>

                                                <a href="#" class="p-2 del_faq" data-id="{{ $l->id }}" title="Delete FAQ">
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


            $('.del_faq').on('click', function(){
                event.preventDefault();
                var id = $(this).data("id");

                swal({
                        title: "Are you sure? ",
                        text: "You will not be able to recover this FAQ record!",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonClass: "btn-danger",
                        confirmButtonText: "Yes, delete it!",
                        closeOnConfirm: false
                    },
                    function (isConfirm) {
                        if (isConfirm) {
                            $.ajax({
                                url: "{{ route('admin.delete_faqs') }}",
                                type: "post",
                                data: {
                                    "id": id
                                },
                                success: function (result) {

                                    if (result == 'error')
                                    {
                                        swal({
                                            title: "Error in deleting FAQ!",
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
                                            title: "FAQ has been deleted!",
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
        });
    </script>
@endsection
