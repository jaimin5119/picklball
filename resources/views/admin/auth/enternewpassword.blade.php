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
    <link rel="stylesheet" href="{{asset('vendor/bootstrap/css/bootstrap.min.css')}}">
    <!-- Font Awesome CSS-->
    <!-- <link rel="stylesheet" href="{{asset('vendor/font-awesome/css/font-awesome.min.css')}}"> -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Fontastic Custom icon font-->
    <link rel="stylesheet" href="{{asset('css/fontastic.css')}}">
    <!-- Google fonts - Roboto -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
    <!-- jQuery Circle-->
    <link rel="stylesheet" href="{{asset('css/grasp_mobile_progress_circle-1.0.0.min.css')}}">
    <!-- Custom Scrollbar-->
    <link rel="stylesheet" href="{{asset('vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.css')}}">
    <!-- theme stylesheet-->
    <link rel="stylesheet" href="{{asset('css/style.default.css')}}" id="theme-stylesheet">
    <!-- Custom stylesheet - for your changes-->
    <link rel="stylesheet" href="{{asset('css/custom.css')}}">
    <!-- Favicon-->
    <link rel="shortcut icon" href="{{asset('img/favicon.ico')}}">
    <style>
        body, html {
            height: 100%;
            background-repeat: no-repeat;
            background-image: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));
        }

    </style>
</head>
<!-- END: Head-->

<body>


<section>
    <div class="container-fluid">
        {{--        <div class="login-page">--}}
        <div class="container">
            <div class="d-flex align-items-center justify-content-center h-100">
                <div class="col-sm-12 col-md-6 text-center ">
                    <div class="card shadow-lg p-3 mb-5 bg-white rounded">
                        <div class="card-header">Enter New Password</div>
                        <div class="card-body">
                            <form method="POST" action="" class="validatedForm">
                                @csrf
                                <input type="hidden" name="user" value="">
                                <div class="col-md-8 offset-md-2 col-sm-12">
                                    <div class="form-group">
                                        <label for="email" class="pull-left">New Password</label>
                                        <input id="password" type="text" class="form-control" name="password" required autocomplete="">
                                    </div>

                                    <div class="form-group ">
                                        <label for="password" class="pull-left">Confirm New Password</label>
                                        <input type="text" class="form-control" name="c_password" required autocomplete="">
                                        <span class="invalid-feedback" role="alert">
                                    </span>

                                    </div>

                                    <div class="row mt-4 pull-right">
                                        <div class="col-sm-12 ">
                                            <button class="btn btn-primary mr-2" type="submit" name="action">
                                                <i class="fa fa-login"></i>
                                                {{ __('Update') }}
                                            </button>
                                            <button type="reset" class="btn  mb-1">
                                                <i class="fa fa-arrow-circle-left"></i>
                                                {{--                                                @if (Route::has('password.request'))--}}
                                                <a class="btn btn-link" href="{{ route('admin.login') }}">
                                                    {{ __('login') }}
                                                </a>
                                                {{--                                                @endif--}}
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
                {{--                <p>Design by <a href="https://probsoltechnology.com" class="external">ProbSol Technology</a></p>--}}
            </div>
        </div>
    </div>
</footer>

<script src="{{asset('vendor/jquery/jquery.min.js')}}"></script>
<script src="{{asset('vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('js/grasp_mobile_progress_circle-1.0.0.min.js')}}"></script>
<script src="{{asset('vendor/jquery-validation/jquery.validate.min.js')}}"></script>

<script>
    $(".validatedForm").validate({
        onkeyup: function (element) {
            this.element(element);
        },
        rules: {

            password: {
                required: true,
                minlength: 6
            },
            c_password: {
                required: true,
                equalTo: "#password"
            }
        },
        messages: {
            password: {
                required: "Please Enter Password.",
                minlength: "password should be of 6 character or more"
            },
            c_password: {
                equalTo: "Please Enter Same Password again.",
                required: "Please Re-Enter Password."
            }

        },
        errorPlacement: function (error, element) {
            var placement = $(element).data('error');
            if (placement) {
                $(placement).append(error)
            } else {
                error.insertAfter(element);
            }
        }
    });
</script>
</body>

</html>

