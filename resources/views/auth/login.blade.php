<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Orient</title>
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,300i,400,400i,500,600,700,800" rel="stylesheet">
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link href="/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="/css/style.css" rel="stylesheet">
</head>
<body class="bg_main">
    <div id="wrapper">
        <div class="log_con">
            <div class="container-fluid">
                <!-- Row -->
                <div class="table-struct full-width">
                    <div class="table-cell vertical-align-middle auth-form-wrap">
                        <div class="auth-form">
                            <div class="row m-0">
                                <div class="col-md-6">
                                    <div class="login-left">
                                        <div class="logo-company"> <img src="images/orichem.jpeg" alt="" /> </div>
                                    </div>
                                </div>
                                <div class="col-md-6" style="background-color: #f5f5f5">
                                    <div class="login-right">
                                        <h3>Login</h3>
                                        <form method="POST" action="{{ route('login') }}">
                                            @csrf
                                            <div class="form-group">
                                                <div class="user"> <span class="fa fa-user-alt"></span>
                                                    <input id="username" type="username" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" value="{{ old('username') }}" placeholder="Username" required autofocus>
                                                    @if ($errors->has('username'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('username') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="clearfix"></div>
                                                <div class="pass"> <span class="fa fa-unlock"></span>
                                                    <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="password" required>

                                                    @if ($errors->has('password'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('password') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            {{-- <div class="form-group">
                                                <div class="col-md-6" style="height: auto;">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    
                                                        <label class="form-check-label" for="remember">
                                                            {{ __('Remember Me') }}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div> --}}

                                            @if (Route::has('password.request'))
                                                <a class="f_pass" href="{{ route('password.request') }}">Forgot password?</a>
                                            @endif

                                            <div class="form-group">
                                                <button type="submit" class="btn btn-info btn-login">LOG <span>IN</span></button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="Log_footer"> © 2019 Orient Engineering Services All rights reserved.<br> Design &amp; Developed by <a href="https://allomate.com" target="_blank">Allomate Solutions</a> </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="/js/jquery-3.3.1.slim.min.js"></script>
    <script src="/js/popper.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
</body>
</html>