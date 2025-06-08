<html class="loading" lang="en">
<!-- BEGIN: Head-->
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">

    <title>@yield('title') | Dynamic Website</title>
    <link rel="apple-touch-icon" href="{{asset('images/favicon/apple-touch-icon-152x152.png')}}">
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('images/favicon/favicon-32x32.png')}}">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">
    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="{{asset('public/vendor/bootstrap/css/bootstrap.min.css')}}">
    <!-- Font Awesome CSS-->
    <!-- <link rel="stylesheet" href="{{asset('public/vendor/font-awesome/css/font-awesome.min.css')}}"> -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
    <div class="container-fluid" >
        {{--        <div class="login-page">--}}
        <div class="container" >
            <div class="d-flex align-items-center justify-content-center h-100">
                <div class="col-sm-12 col-md-6 text-center">
                    <div class="card shadow-lg p-3 mb-5 rounded" >
                        <div class="card-header"><h3>Admin : Reset Password</h3>

                            
                        </div>

                        <div class="card-body">
                             <form method="POST" action="{{URL::to('update-forget-password') }}" autocomplete="off">
                        @csrf
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
                    <input type="hidden" name="token" value="{{ $token }}">
                        <div class="divLogin commonForm stable">
                            <div class="divInput">
{{--                                <div class="row">--}}
                                    <div class="form-group">
                                         <input class="form-control textdemo no-border" type="password" id="password" name="password" required/>

                                       <!--  <input id="n-pass" name="name" type="text" class="form-control textdemo no-border" required> -->
                                        <label for="password">New Password</label>
                                         @if ($errors->has('password'))
                                <div class="text-danger">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </div>
                            @endif
{{--                                    </div>--}}
                                </div>
{{--                                <div class="row">--}}
                                    <div class="form-group">
                                         <input class="form-control textdemo no-border" type="password" id="confirm_password"
                                   name="password_confirmation" required />
                                    <span id='message'></span>
                                    @if ($errors->has('password_confirmation'))
                                <div class="text-danger">
                                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                                </div>
                            @endif
                                        <!-- <input id="re-pass" name="name" type="text" class="lastInput form-control textdemo no-border" required> -->
                                        <label for="password-confirm">Re-Enter New Password</label>
{{--                                    </div>--}}
                                </div>
                            </div>
                            {{-- <div class="divInput">
                                <input type="text" placeholder="Email" class="log-put lastInput form-control">
                            </div> --}}

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary mr-2">
                                {{('Reset Password') }}
                            </button>
                               <!--  <input type="submit" class="logSubmit" value="Reset Password"> -->
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
                {{--                <p>Design by <a href="https://probsoltechnology.com" class="external">ProbSol Technology</a></p>--}}
            </div>
        </div>
    </div>
</footer>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script type="text/javascript">
    $('#password, #confirm_password').on('keyup', function () {
  if ($('#password').val() == $('#confirm_password').val()) {
    $('#message').html('Matching').css('color', 'green');
  } else
    $('#message').html('Not Matching').css('color', 'red');
});
</script>



<script src="{{asset('public/vendor/jquery/jquery.min.js')}}"></script>
<script src="{{asset('public/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('public/js/grasp_mobile_progress_circle-1.0.0.min.js')}}"></script>
</body>

</html>

