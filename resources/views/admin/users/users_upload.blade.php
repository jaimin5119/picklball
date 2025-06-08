@extends('admin.layout')

{{-- page title --}}
@section('title','Users')

@section('vendor-style')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection


@section('content')

    <div class="breadcrumb-holder">
        <div class="container-fluid">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dash') }}">Dashboard</a></li>
                <li class="breadcrumb-item active"><a href="{{ route('admin.list_faqs') }}">Bluck Upload Users</a></li>
                
            </ul>
        </div>
    </div>
    <section>
        <div class="container-fluid">
            <header>
                <div class="row">
                    <div class="col-md-7">
                        <h2 class="h3 display">Add Bluck Upload</h2>
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
                    <form action="{{ route('admin.bulk_store_user') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="form-group col-sm-12 col-md-9">
                                <label for="question">Users Csv file Insert<span class="text-danger">*</span></label>
                                <input type="file" name="user_file" class="form-control" required>
                            </div>
                        </div>
                        <div class="row mt-4 pull-left">
                            <div class="col-sm-12 ">
                                <button class="btn btn-primary mr-2" type="submit" >
                                    <i class="fa fa-arrow-circle-up"></i>
                                    Add
                                </button>
                               
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-body p-4">
                   <form action="{{ route('admin.bulk_update_user') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="form-group col-sm-12 col-md-9">
                                <label for="question">Users Csv file Update<span class="text-danger">*</span></label>
                                <input type="file" name="user_file_update" class="form-control" required>
                            </div>
                        </div>
                        <div class="row mt-4 pull-left">
                            <div class="col-sm-12 ">
                                <button class="btn btn-primary mr-2" type="submit" >
                                    <i class="fa fa-arrow-circle-up"></i>
                                    Update
                                </button>
                               
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>


@endsection




{{-- page scripts --}}
@section('page-script')

@endsection


