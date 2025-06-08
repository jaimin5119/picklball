@extends('admin.layout')

{{-- page title --}}
@section('title','Admin')

@section('vendor-style')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection


@section('content')

    <div class="breadcrumb-holder">
        <div class="container-fluid">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="">Dashboard</a></li>
                <li class="breadcrumb-item active"><a href="">Change Password</a></li>
                <!-- <li class="breadcrumb-item ">Edit Gym Loyalty</li> -->
            </ul>
        </div>
    </div>
    <section>
        <div class="container-fluid">
            <div class="card">
                <div class="card-body p-4">
                    <header>
                        <div class="row">
                            <div class="col-md-7">
                                <h2 class="h3 display">Change Password</h2>
                            </div>
                        </div>

                    </header>

                   
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
                    <form action="{{ route('admin.update_pwd') }}" method="POST" enctype="multipart/form-data">  
                        @csrf


                        <div class="row">
                            <div class="form-group col-sm-12 col-md-4">
                                <label for="current_password">Current Password<span class="text-danger">*</span></label>
                                <input id="current_password" name="current_password" type="password" class="form-control validate" value="" required>
                            </div>
                            <div class="form-group col-sm-12 col-md-4">
                                <label for="password">New Password<span class="text-danger">*</span></label>
                                <input id="password" name="password" type="password" class="form-control validate" value="" required min="8" max="15">
                            </div>
                            <div class="form-group col-sm-12 col-md-4">
                                <label for="confirm_password">Confirm Password<span class="text-danger">*</span></label>
                                <input name="password_confirmation" id="password_confirmation" type="password" class="form-control validate" value="" required min="8" max="15">
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


