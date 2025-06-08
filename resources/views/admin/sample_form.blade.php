@extends('admin.layout')

{{-- page title --}}
@section('title','Master Demo Project')

@section('vendor-style')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection


@section('content')

    <div class="breadcrumb-holder">
        <div class="container-fluid">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dash') }}">Dashboard</a></li>
{{--            <li class="breadcrumb-item active"><a href="{{url('/loyalty')}}">Loyalty management </a></li> --}}
                <li class="breadcrumb-item ">Sample form page</li>
            </ul>
        </div>
    </div>
    <section>
        <div class="container-fluid">
            <header>
                <div class="row">
                    <div class="col-md-7">
                        <h2 class="h3 display">Sample form page</h2>
                    </div>
                </div>
            </header>
            <div class="card">
                <div class="card-body p-4">
                    

                    {{-- @if ($errors->any()) --}}
                        {{-- @foreach ($errors->all() as $error) --}}
                            <!-- <div class="card-alert card gradient-45deg-red-pink">
                                <div class="card-content white-text">
                                    <p>
                                        <i class="material-icons">error</i>{{-- $error --}}
                                    </p>
                                </div>
                                <button type="button" class="close white-text" data-dismiss="alert"
                                        aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div> -->
                        {{-- @endforeach --}}
                    {{-- @endif --}}
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
                    <form id="create_user" action="" method="POST" enctype="multipart/form-data">  @csrf


                        <div class="row">
                            <div class="form-group col-sm-12 col-md-4">
                                <label for="first_name">Full Name <span class="text-danger">*</span></label>
                                <input id="first_name" name="name" type="text" class="form-control validate" value="" >
                            </div>
                            <div class="form-group col-sm-12 col-md-4">
                                <label for="email">Email <span class="text-danger">*</span></label>
                                <input id="email" name="email" type="email" class="form-control validate" value="" >
                            </div>
                            <div class="form-group col-sm-12 col-md-4">
                                <label for="other">other field<span class="text-danger">*</span></label>
                                <input name="other" type="number" class="form-control validate" min="0" value="" >
                            </div>

                        </div>

                        <div class="row mt-4 pull-right">
                            <div class="col-sm-12 ">
                                <button class="btn btn-primary mr-2" type="submit" >
                                    <i class="fa fa-arrow-circle-up"></i>
                                    Update
                                </button>
                                <button type="reset" class="btn btn-secondary  mb-1">
                                    <i class="fa fa-arrow-circle-left"></i>
                                    <a href="{{url()->previous()}}" class="text-white">Cancel</a>
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


