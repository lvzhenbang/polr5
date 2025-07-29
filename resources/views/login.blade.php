@extends('layouts.base')

@section('css')
<link rel="stylesheet" href="css/login.css" />
@endsection

@section('content')
<div class="signin-box">
    <h1 class="mb-5 text-center">Sign In</h1>
    <div class="col-lg-5 col-md-8 mx-auto">
        <form action="login" method="POST">
            <input type="hidden" name="_token" value="{{csrf_token()}}" />
            <div class="mb-4">
                <label for="username" class="form-label">User Name</label>
                <input class="form-control" type="text" required name="username" placeholder="Enter your username">
            </div>
            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                @if (env('SETTING_PASSWORD_RECOV') == true)
                    <a href="{{route('lost_password')}}" class="text-muted float-end"><small>Forgot your password?</small></a>
                @endif
                <input class="form-control" type="password" required name="password" placeholder="Enter your passsword">
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
                <button class="btn btn-primary form-submit" type="submit">Log In</button>
            </div>

            @if (env('POLR_ALLOW_ACCT_CREATION') == true)
            <p class="mt-2 text-center text-muted">
                Don't have an account? <a href="{{route('signup')}}" class="text-muted ms-1"><b>Sign Up</b></a>
            </p>
            @endif

        </form>
    </div>
</div>
@endsection

@section('js')
    @if (env('POLR_ACCT_CREATION_RECAPTCHA'))
    <script src="https://www.google.com/recaptcha/enterprise.js" async defer></script>
    @endif
@endsection
