<html class="loading" lang="en">
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Fontastic Custom icon font-->
    <link rel="stylesheet" href="{{asset('css/fontastic.css')}}">
    
    <!-- Google fonts - Roboto -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
    
    <!-- jQuery Circle-->
    <link rel="stylesheet" href="{{asset('css/grasp_mobile_progress_circle-1.0.0.min.css')}}">
    
    <!-- Custom Scrollbar-->
    <link rel="stylesheet" href="{{asset('vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.css')}}">
    
    <!-- Theme stylesheet-->
    <link rel="stylesheet" href="{{asset('css/style.blue.css')}}" id="theme-stylesheet">
    
    <!-- Custom stylesheet -->
    <link rel="stylesheet" href="{{asset('css/custom.css')}}">

    <!-- Favicon-->
    <link rel="shortcut icon" href="{{asset('img/favicon.ico')}}">

    <style>
        body {
            height: 100%;
            background-color: #000;
        }
        .input-group-text {
            cursor: pointer;
        }
    </style>
</head>

<body>
<section>
    <div class="container-fluid">
        <div class="container">
            <div class="d-flex align-items-center justify-content-center h-100">
                <div class="col-sm-12 col-md-6 text-center">
                    <div class="card shadow-lg p-3 mb-5 rounded">
                        <div class="card-header"><h3>Admin : Login</h3></div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.signin') }}">
                                @csrf

                                @if($errors->any())
                                    <div class="alert alert-danger alert-block" id="successMessage">
                                        <button type="button" class="close" data-dismiss="alert">×</button>
                                        <span>{!! implode('', $errors->all('<div>:message</div>')) !!}</span>
                                    </div>
                                @endif

                                @if(session()->has('success'))
                                    <div class="alert alert-success">
                                        <button type="button" class="close" data-dismiss="alert">×</button>
                                        <span>{{ session()->get('success') }}</span>
                                    </div>
                                @endif

                                <div class="col-md-8 offset-md-2 col-sm-12">
                                    <div class="form-group">
                                        <label for="email" class="pull-left">{{ __('E-Mail Address') }}</label>
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                                               name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                        @error('email')
                                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="password" class="pull-left">{{ __('Password') }}</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                                   id="password" name="password" required autocomplete="current-password">
                                            <div class="input-group-append">
                                                <span class="input-group-text" onclick="togglePasswordVisibility('password', this)">
                                                    <i class="fa fa-eye-slash" id="togglePasswordIcon"></i>
                                                </span>
                                            </div>
                                        </div>
                                        @error('password')
                                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>

                                    <div class="row mt-4 pull-right">
                                        <div class="col-sm-12">
                                            <button class="btn btn-primary mr-2" type="submit" name="action">
                                                <i class="fa fa-login"></i> {{ __('Login') }}
                                            </button>
                                            <button type="reset" class="btn mb-1">
                                                <i class="fa fa-arrow-circle-left"></i>
                                                <a class="btn btn-link text-danger" href="{{ route('admin.forgot_pass') }}">
                                                    {{ __('Forgot Your Password?') }}
                                                </a>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div> <!-- card-body -->
                    </div> <!-- card -->
                </div>
            </div>
        </div>
    </div>
</section>

<footer class="main-footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <p>Pickle Heroes © 2025</p>
            </div>
            <div class="col-sm-6 text-right"></div>
        </div>
    </div>
</footer>

<!-- Scripts -->
<script src="{{asset('vendor/jquery/jquery.min.js')}}"></script>
<script src="{{asset('vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('js/grasp_mobile_progress_circle-1.0.0.min.js')}}"></script>

<!-- Show/Hide Password Script -->
<script>
    function togglePasswordVisibility(inputId, iconElement) {
        const input = document.getElementById(inputId);
        const icon = iconElement.querySelector('i');

        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        } else {
            input.type = "password";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        }
    }
</script>
</body>
</html>
