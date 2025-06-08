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
                <li class="breadcrumb-item active"><a href="{{ route('admin.list_cms_page') }}">CMS Pages</a></li>
                <li class="breadcrumb-item ">{{ isset($edit) ? 'View/Edit CMS page' : 'Add CMS Page'}}</li>
            </ul>
        </div>
    </div>
    <section>
        <div class="container-fluid">
            <header>
                <div class="row">
                    <div class="col-md-7">
                        <h2 class="h3 display">{{ isset($edit) ? 'View/Edit CMS page' : 'Add CMS page'}}</h2>
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
                    @php if(isset($edit)){ $route = 'admin.update_cms_page'; }else{ $route = 'admin.store_cms_page'; } @endphp
                    <form id="create_user" action="{{ route($route) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @if(isset($edit))
                            <input type="hidden" name="xid" value="{{ $edit->id }}">
                        @endif
                        <div class="row">
                            <div class="form-group col-sm-12 col-md-9">
                                <label for="page_title">Page Title<span class="text-danger">*</span></label>
                                <input id="page_title" name="page_title" type="text" class="form-control validate" value="{{ isset($edit->page_title) ? $edit->page_title : '' }}" readonly>
                            </div>
                            <div class="form-group col-sm-12 col-md-9">
                                <label for="page_content">Page Content<span class="text-danger">*</span></label>
                                <textarea class="ckeditor form-control" name="page_content" id="page_content" required>
                                    {{ isset($edit->content) ? $edit->content : '' }}
                                </textarea>
                            </div>
                            
                        </div>

                        <div class="row mt-4 pull-left">
                            <div class="col-sm-12 ">
                                <button class="btn btn-primary mr-2" type="submit" >
                                    <i class="fa fa-arrow-circle-up"></i>
                                    {{ isset($edit) ? 'Update' : 'Add' }}
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


