@extends('admin.layout')

{{-- page title --}}
@section('title','Dashboard |')

@section('vendor-style')
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet"> -->
@endsection

@section('content')

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            {{--                    <h1 class="h3 mb-0 text-gray-800">Admin Dashboard</h1>--}}
            {{--                    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i--}}
            {{--                            class="fa fa-download fa-sm text-white-50"></i> Generate Report</a>--}}
        </div>

        <!-- Content Row -->
        <div class="row">
        <h2> Welcome Admin Dashboard</h2>

            <!-- Earnings (Monthly) Card Example -->
            <!-- <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Statistics Page</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{@$statistics_page[0]->page_name}} : {{$statistics_count}}</div>
                            </div>
                            <div class="col-auto">
                                <i class="text-gray-300"></i>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->

            <!-- Earnings (Monthly) Card Example -->
            <!-- <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Earnings</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->

            <!-- Earnings (Monthly) Card Example -->
            <!-- <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total User's</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
        </div>


        <!-- Content Row -->

       

    </div>
    <!-- /.container-fluid -->

@endsection

@section('page-script')

    
@endsection
