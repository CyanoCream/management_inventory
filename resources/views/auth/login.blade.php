@extends('layouts.guest')

@section('content')
    <div class="col-lg-6">
        <div class="auth-cover-wrapper bg-primary-100">
            <div class="auth-cover">
                <div class="title text-center">
                    <h1 class="text-primary mb-10">{{ __('Login') }}</h1>
                </div>
                <div class="cover-image">
                    <img src="{{ asset('images/auth/signin-image.svg') }}" alt="">
                </div>
                <div class="shape-image">
                    <img src="{{ asset('images/auth/shape.svg') }}" alt="">
                </div>
            </div>
        </div>
    </div>
    <!-- end col -->
    <div class="col-lg-6">
        <div class="signin-wrapper">
            <div class="form-wrapper">
                <h6 class="mb-15">{{ __('Login') }}</h6>
                <form action="{{ route('login') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-12">
                            <div class="input-style-1">
                                <label for="username">{{ __('Email') }}</label>
                                <input @error('username') class="form-control is-invalid" @enderror type="text" name="username" id="email" placeholder="{{ __('Username') }}" required autocomplete="username" autofocus>
                                @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <!-- end col -->
                        <div class="col-12">
                            <div class="input-style-1">
                                <label for="password">{{ __('Password') }}</label>
                                <input type="password" @error('password') class="form-control is-invalid" @enderror name="password" id="password" placeholder="{{ __('Password') }}" required autocomplete="current-password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <!-- end col -->
                        <div class="col-xxl-6 col-lg-12 col-md-6">
                            <div class="form-check checkbox-style mb-30">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" value="" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    {{ __('Remember Me') }}</label>
                            </div>
                        </div>
                        <!-- end col -->
                        @if (Route::has('password.request'))
                            <div class="col-xxl-6 col-lg-12 col-md-6">
                                <div class="text-start text-md-end text-lg-start text-xxl-end mb-30">
                                    <a href="{{ route('password.request') }}">{{ __('Forgot Your Password?') }}</a>
                                </div>
                            </div>
                        @endif
                    <!-- end col -->
                        <div class="col-12">
                            <div class="button-group d-flex justify-content-center flex-wrap">
                                <button type="submit" class="main-btn primary-btn btn-hover w-100 text-center">
                                    {{ __('Login') }}
                                </button>
                            </div>
                        </div>

                        @if (Route::has('register'))
                        <div class="singin-option pt-40">
                            <p class="text-sm text-medium text-dark text-center">
                                Don’t have any account yet?
                                <a href="{{ route('register') }}">Create an account</a>
                            </p>
                        </div>
                        @endif
                    </div>
                    <!-- end row -->
                </form>
            </div>
        </div>
    </div>
    <!-- end col -->
@endsection
