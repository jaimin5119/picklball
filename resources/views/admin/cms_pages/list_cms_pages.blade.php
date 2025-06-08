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
                <li class="breadcrumb-item active">CMS Pages</li>
            </ul>
        </div>
    </div>
    <section>
        <div class="container-fluid">
            <header>
                <div class="row">
                    <div class="col-md-7">
                        <h2 class="h3 display">CMS Pages</h2>
                    </div>
                    <div class="col-md-5">
                        <a href="{{route('admin.add_cms_page')}}" class="btn btn-primary pull-right rounded-pill">Add CMS Page</a>
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
                                <th>Page Title</th>
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
                                            <td width="88%">{{ $l->page_title }}</td>

                                            <td width="10%">
                                                <a href="{{ route('admin.edit_cms_page', [ $l->id ]) }}" class="p-2" title="View/Edit FAQ">
                                                    <span class="fa fa-edit"></span>
                                                </a>

                                                <!-- <a href="#" class="p-2 del_faq" data-id="{{ $l->id }}" title="Delete FAQ">
                                                    <span class="fa fa-trash"></span>
                                                </a> -->
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


            
        });
    </script>
@endsection
