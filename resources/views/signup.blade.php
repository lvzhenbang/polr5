@extends('layouts.base')

@section('css')
<link rel='stylesheet' href='/css/signup.css' />
@endsection

@section('content')
<div class="row">
    <div class='col-lg-6 col-md-8 mx-auto'>
        <h2 class='mb-5 text-center'>Register</h2>

        <form action='/signup' method='POST'>
            <input type="hidden" name='_token' value='{{csrf_token()}}' />
            <div class="mb-4">
                <label for="username" class="form-label">User Name</label>
                <input class="form-control" type="text" require name="username" placeholder="Enter your username">
            </div>
            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <input class="form-control" type="password" require name="password" placeholder="Enter your passsword">
            </div>      
            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <input class="form-control" type="email" require name="email" placeholder="polr5@polr.com">
            </div>

            @if (env('POLR_ACCT_CREATION_RECAPTCHA') == false)
                <div class="captcha-box mb-4">
                    <img src="{{ captcha_src() }}" width="120" height="36" onclick="this.src='{{captcha_src()}}'+Math.random()" alt="captcha">
                    <input type="text" name="captcha" class="form-control" id="captcha" required placeholder="Please Insert Captch">
                </div>
                @error('captcha')  <div class="captcha-message">{{ $message }}</div> @enderror
            @else
                <div class="g-recaptcha" data-sitekey="{{env('POLR_RECAPTCHA_SITE_KEY')}}"></div>
            @endif

            <div class="mb-4 mb-sm-0 text-center">
                <button class="btn btn-primary form-submit" type="submit">Sign Up</button>
            </div>

            <p class="mt-2 text-center text-muted">
                Already have an account? <a href="{{route('login')}}" class="text-muted ms-1"><b>Log In</b></a>
            </p>
        </form>
    </div>
    <div class="col-lg-6 d-md-none d-none d-lg-block">
        <div class="right-col-one">
            <h4>Username</h4>
            <p>The username you will use to login to {{env('APP_NAME')}}.</p>
        </p>
        <div class="right-col-next">
            <div class="right-col">
                <h4>Password</h4>
                <p>The secure password you will use to login to {{env('APP_NAME')}}.</p>
            </p>
        </div>
        <div class="right-col-next">
            <h4>Email</h4>
            <p>The email you will use to verify your account or to recover your account.</p>
        </p>
    </div>
</div>
@endsection

@section('js')
    @if (env('POLR_ACCT_CREATION_RECAPTCHA'))
    <script src="https://www.google.com/recaptcha/enterprise.js" async defer></script>
    @endif
@endsection