<html class="loading" lang="en">
<!-- BEGIN: Head-->
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">

    <title>@yield('title') | Dynamic Website</title>
    <link rel="apple-touch-icon" href="{{asset('public/images/favicon/apple-touch-icon-152x152.png')}}">
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('public/images/favicon/favicon-32x32.png')}}">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">
    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="{{asset('public/vendor/bootstrap/css/bootstrap.min.css')}}">
    <!-- Font Awesome CSS-->
    <link rel="stylesheet" href="{{asset('public/vendor/font-awesome/css/font-awesome.min.css')}}">
    <!-- Fontastic Custom icon font-->
    <link rel="stylesheet" href="{{asset('public/css/fontastic.css')}}">
    <!-- Google fonts - Roboto -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
    <!-- jQuery Circle-->
    <link rel="stylesheet" href="{{asset('public/css/grasp_mobile_progress_circle-1.0.0.min.css')}}">
    <!-- Custom Scrollbar-->
    <link rel="stylesheet" href="{{asset('public/vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.css')}}">
    <!-- theme stylesheet-->
    <link rel="stylesheet" href="{{asset('public/css/style.blue.css')}}" id="theme-stylesheet">
    <!-- Custom stylesheet - for your changes-->
    <link rel="stylesheet" href="{{asset('public/css/custom.css')}}">
    <!-- Favicon-->
    <link rel="shortcut icon" href="{{asset('public/img/favicon.ico')}}">
    <style>
        body {
            height: 100%;
            /*background-repeat: no-repeat;*/
            background-image: url("{{asset("public/img/background.jpg")}}");
        }

    </style>
</head>
<!-- END: Head-->

<body>


<section>
    <div class="container-fluid">
        {{--        <div class="login-page">--}}
        <div class="container">
            @if(!empty($error))
                <div class="alert alert-danger custom-alert-danger  alert-block  text-center" id="successMessage">
                    <button type="button" class="close custom-alert-close" data-dismiss="alert">×</button>
                    <span class="text-danger">{{ $error }}</span></div>
            @endif
            <div class="d-flex align-items-center justify-content-center h-100">
                <div class="col-sm-12 col-md-6 text-center ">
                    <div class="card shadow-lg p-3 mb-5 rounded">
                        <div class="card-header" ><h3>Enter Verification Code </h3>
                            @if(session()->has('success'))
                                <div class="alert alert-success custom-alert-success   alert-block text-center">
                                    <button type="button" class="close custom-alert-close" data-dismiss="alert">×</button>
                                    <span>{{ session()->get('success') }}</span>
                                </div>
                            @endif


                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('verify') }}">

                                @csrf
                                <input type="hidden" name="email" value="{{$email}}">
{{--                                <input type="hidden" name="mobile" value="{{$mobile}}">--}}
                                <div class="col-md-8 offset-md-2 col-sm-12">
                                    <div class="form-group">
                                        <label for="email" class="pull-left">Code</label>
                                        <input type="number" name="code" autofocus required>

                                    </div>
                                    <div class="row mt-4 pull-right">
                                        <div class="col-sm-12 ">
                                            <button class="btn btn-primary mr-2" type="submit" name="action">
                                                <i class="fa fa-login"></i>
                                                {{ __('Send') }}
                                            </button>

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{--        </div>--}}
    </div>
</section>

<footer class="main-footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <p>Dynamic Website &copy; 2021</p>
            </div>
            <div class="col-sm-6 text-right">
            </div>
        </div>
    </div>
</footer>

<script src="{{asset('public/vendor/jquery/jquery.min.js')}}"></script>
<script src="{{asset('public/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('public/js/grasp_mobile_progress_circle-1.0.0.min.js')}}"></script>
</body>

</html>

