<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Login | MILELE</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Milele" name="description" />
    <meta content="Milele" name="author" />
    <link rel="shortcut icon" href="assets/images/favicon.ico">
    @include('partials.head-css')
    <style>
        .paragraph-class {
            color: red;
        }

        .custom-color-button {
            background-color: #012b4d;
            color: #ffffff;
            border: none;
        }

        .custom-color-button:hover {
            background-color: #216da4;
        }

        .main-logo-img {
            width: 70%;
            aspect-ratio: 3;
        }

        .logo-container {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            height: 100%;
        }

        .logo-img {
            max-width: 100%; 
            height: 100%; 
            object-fit: cover; 
            object-position: top; 
        }

        @media (max-width: 767px) {
            .side-image-container {
                display: none;
            }

            .main-logo-container {
                display: block;
                text-align: center;
            }

            .main-logo-img {
                width: 60%;
            }
        }

        /* For screens larger than md (medium) size, hide the mobile logo and show the side image */
        @media (min-width: 768px) {
            .side-image-container {
                display: block;
            }
        }

        /* .logo-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
        }

        .logo-img {
            max-height: 590px;
            max-width: 544px;
            width: 100%;
            height: auto;
        } */

    @media (min-width: 768px) {
            .side-image-container {
                display: block;
            }
        }

    </style>
</head>
@include('partials.body')

<body>
    <div class="auth-page">
        <div class="p-0">
            <div class="row g-0">
                <div class="col-xxl-3 col-lg-4 col-md-5">
                    <div class="auth-full-page-content d-flex p-sm-5 p-4">
                        <div class="w-100">
                            <div class="d-flex flex-column h-100">
                                <div class="mb-4 mb-md-5 text-center">
                                    <a href="/" class="d-block auth-logo">
                                    </a>
                                </div>
                                
                                <div class="auth-content my-auto">
                                    <div class="main-logo-container d-flex justify-content-center pb-5 mb-5">
                                        <img src="mobile-logo.png" class="main-logo-img">
                                    </div>
                                    <div class="text-center">
                                        <h5 class="mb-0">Welcome Back !</h5>
                                        <p class="text-muted mt-2">Sign in to continue to Milele.</p>
                                    </div>
                                    @if (session('success'))
                                    <div class="alert alert-success" role="alert"> {{session('success')}}
                                    </div>
                                    @endif

                                    @if (session('error'))
                                    <div class="alert alert-danger" role="alert"> {{session('error')}}
                                    </div>
                                    @endif
                                    <form method="POST" action="{{ route('otp.loginOtpGenerate') }}">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label">Username</label>
                                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                            <!-- @if(Session::has('error'))
                                                        <p class="alert paragraph-class">{{ Session::get('error') }}</p>
                                                @endif -->
                                        </div>
                                        <div class="mb-3">
                                            <div class="d-flex align-items-start">
                                                <div class="flex-grow-1">
                                                    <label class="form-label">Password</label>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <div class="">
                                                        @if (Route::has('password.request'))
                                                        <a class="btn btn-link" href="{{ route('password.request') }}">
                                                            {{ __('Forgot Your Password?') }}
                                                        </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="input-group auth-pass-inputgroup">
                                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                                @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                                <button class="btn btn-light ms-0" type="button" id="password-addon"><i class="mdi mdi-eye-outline"></i></button>
                                            </div>
                                        </div>
                                        <div class="row mb-4">
                                            <div class="col">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="remember-check">
                                                        Remember me
                                                    </label>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="mb-3">
                                            <button type="submit" class="btn btn-primary w-100 waves-effect waves-light custom-color-button">
                                                {{ __('Login') }}
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <div class="mt-4 mt-md-5 text-center">
                                    <p class="mb-0">© <script>
                                            document.write(new Date().getFullYear())
                                        </script> Powered By <i class="mdi mdi-heart text-danger"></i> Milele</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-9 col-lg-8 col-md-7 side-image-container d-none d-md-block">
                    <div class="auth-bg pt-md-5 p-4 d-flex">
                        <center>
                            <div class="bg-overlay bg-primary">
                                <div class="logo-container">
                                    <img src="{{ url('variantimages/bgm.jpg') }}" class="logo-img" alt="Responsive Logo">
                                </div>
                            </div>
                        </center>
                        <ul class="bg-bubbles">
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                        </ul>
                        <div class="row justify-content-center align-items-center">
                            <div class="col-xl-7">
                                <div class="p-0 p-sm-4 px-xl-0">
                                    <div id="reviewcarouselIndicators" class="carousel slide" data-bs-ride="carousel">
                                        <div class="carousel-indicators carousel-indicators-rounded justify-content-start ms-0 mb-0">
                                            <button type="button" data-bs-target="#reviewcarouselIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                                            <button type="button" data-bs-target="#reviewcarouselIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                                            <button type="button" data-bs-target="#reviewcarouselIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
                                        </div>
                                        <div class="carousel-inner">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('partials.vendor-scripts')
    <script src="js/pages/pass-addon.init.js"></script>
</body>

</html>