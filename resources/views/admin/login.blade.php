<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Title Page-->
    <title>Login</title>

    <!-- Fontfaces CSS-->
    <link href="{{ asset('adminHtml/css/font-face.css') }}" rel="stylesheet" media="all">
    <link href="{{ asset('adminHtml/vendor/font-awesome-4.7/css/font-awesome.min.css') }}" rel="stylesheet" media="all">
    <link href="{{ asset('adminHtml/vendor/font-awesome-5/css/fontawesome-all.min.css') }}" rel="stylesheet" media="all">
    <link href="{{ asset('adminHtml/vendor/mdi-font/css/material-design-iconic-font.min.css') }}" rel="stylesheet" media="all">
    <link href="{{ asset('adminHtml/vendor/bootstrap-4.1/bootstrap.min.css') }}" rel="stylesheet" media="all">
    <link href="{{ asset('adminHtml/css/theme.css') }}" rel="stylesheet" media="all">

    <style>
        .social-login-container {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.social-login-button {
    width: 100%;
    max-width: 300px;
    margin-bottom: 20px;
    padding: 15px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s;
    display: flex;
    justify-content: center;
    align-items: center;
}

.social-login-button a {
    text-decoration: none;
    color: #fff;
    font-weight: bold;
    margin-left: 10px;
}

.social-login-button.google {
    background-color: #4285F4;
}

.social-login-button.facebook {
    background-color: #1877F2;
}

.social-login-button.linkedin {
    background-color: #0077B5;
}

.social-login-button:hover {
    filter: brightness(90%);
}


    </style>

</head>

<body class="animsition">
    <div class="page-wrapper">
        <div class="page-content--bge5">
            <div class="container">
                <div class="login-wrap">
                    <div class="login-content">
                        <div class="login-logo">
                            {{Config::get('constant.site_name')}}
                        </div>
                        <div class="login-form">
                            <form action="{{ route('admin.auth') }}" method="post">
                                @csrf
                                <div class="form-group">
                                    <label>Email Address</label>
                                    <input class="au-input au-input--full" type="email" name="email" placeholder="Email" required>
                                </div>
                                <div class="form-group">
                                    <label>Password</label>
                                    <input class="au-input au-input--full" type="password" name="password" placeholder="Password" required>
                                </div>
                                @if(!empty(session('error')))
                                <div class="alert alert-danger">
                                    {{session('error')}}                                          
                                </div>
                                @endif  
                                <div class="login-checkbox">
                                    <label>
                                        <input type="checkbox" name="remember">Remember Me
                                    </label>
                                    <label>
                                        <a href="#">Forgotten Password?</a>
                                    </label>
                                </div>
                                <button class="au-btn au-btn--block au-btn--green m-b-20" type="submit">sign in</button>
                                <div class="social-login-container">
                                    <button class="social-login-button google">
                                        <a href="{{ route('google.login') }}">Sign in with Google</a>
                                    </button>

                                    <button class="social-login-button facebook">
                                        <a href="{{ route('facebook.login') }}">Sign in with Facebook</a>
                                    </button>

                                    <button class="social-login-button linkedin">
                                        <a href="{{ route('linkedin.login') }}">Sign in with LinkedIn</a>
                                    </button>
                                </div>

                            </form>
                            <div class="register-link">
                                <p>
                                    Don't you have account?
                                    <a href="#">Sign Up Here</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Jquery JS-->
    <script src="{{ asset('adminHtml/vendor/jquery-3.2.1.min.js') }}"></script>
    <!-- Bootstrap JS-->
    <script src="{{ asset('adminHtml/vendor/bootstrap-4.1/bootstrap.min.js') }}"></script>
    <script src="{{ asset('adminHtml/vendor/bootstrap-4.1/popper.min.js') }}"></script>
    <script src="{{ asset('adminHtml/vendor/bootstrap-4.1/bootstrap.min.js') }}"></script>
    <!-- Vendor JS       -->
    <script src="{{ asset('adminHtml/vendor/wow/wow.min.js') }}"></script>

    <script src="{{ asset('adminHtml/js/main.js') }}"></script>


 

</body>

</html>
<!-- end document-->