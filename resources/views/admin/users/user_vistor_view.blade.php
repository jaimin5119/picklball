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
                <li class="breadcrumb-item active">Vistor View</li>
            </ul>
        </div>
    </div>
    <section>
        <div class="container-fluid">
            <header>
                <div class="row">
                    <div class="col-md-7">
                        <h2 class="h3 display">Vistor View</h2>
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
                                <th>Page Name</th>
                                <th>Count</th>
                                <th>Date</th>
                                <th>Time</th>
                               
                            </tr>
                            </thead>
                            <tbody>
                                 @if(count($view_reg))
                                <tr>
                                    <td width="2%">1</td>
                                    <td width="10%">Register</td>
                                    <td width="10%">
                                        <a href="{{route('admin.vistor_reg_page', [ $view->user_id]) }}" class="p-2" title="View">
                                        {{$view_reg->count();}}</a></td>
                                    <td width="10%">{{@date('d-M-Y', strtotime($reg_date->current_date));}}</td>
                                    <td width="10%">{{@Carbon\Carbon::parse($reg_date->current_date)->format('h:i:A' ) }}</td>
                                </tr>
                                 @endif
                                @if(count($view_login))
                                <tr>
                                    <td width="2%">2</td>
                                    <td width="10%">Login</td>
                                    <td width="10%">
                                         <a href="{{route('admin.vistor_login_page', [ $view->user_id]) }}" class="p-2" title="View">
                                            {{@$view_login->count()}}</a></td>
                                    <td width="10%">{{@date('d-M-Y', strtotime($login_date->current_date));}}</td>
                                    <td width="10%">{{@Carbon\Carbon::parse($login_date->current_date)->format('h:i:A' ) }}</td>
                                </tr>
                                 @endif
                                @if(count($view_dash))
                                <tr>
                                    <td width="2%">1</td>
                                    <td width="10%">Dashboard</td>
                                    <td width="10%">
                                        <a href="{{route('admin.vistor_dash_page', [ $view->user_id]) }}" class="p-2" title="View">
                                             {{$view_dash->count();}}
                                        </a>
                                    </td>
                                    <td width="10%">{{@date('d-M-Y', strtotime($dash_date->current_date));}}</td>
                                    <td width="10%">{{@Carbon\Carbon::parse($dash_date->current_date)->format('h:i:A' ) }}</td>
                                </tr>
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
