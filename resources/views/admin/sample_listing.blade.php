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
                <li class="breadcrumb-item active">Sample Listing page</li>
            </ul>
        </div>
    </div>
    <section>
        <div class="container-fluid">
            <header>
                <div class="row">
                    <div class="col-md-7">
                        <h2 class="h3 display">Sample Listing page</h2>
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
                                <th>Column 1</th>
                                <th>Column 2</th>
                                <th>Column 3</th>
                                <th>Column 4</th>

                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td width="2%">
                                        <center>1</center>
                                    </td>
                                    <td width="10%">
                                        
                                    </td>
                                    <td width="10%">
                                        
                                    </td>
                                    <td width="10%">
                                        
                                    </td>
                                    <td width="10%">
                                        
                                    </td>

                                    <td width="10%">
                                        <a href="#"
                                           class="p-2">
                                            <span class="fa fa-edit"></span>
                                        </a>
                                    </td>
                                </tr>
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
