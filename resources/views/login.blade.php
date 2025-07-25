@extends('layouts.base')

@section('css')
<link rel='stylesheet' href='css/login.css' />
@endsection

@section('content')
<div class="center-text">
    <h1>Login</h1><br/><br/>
    <div class="col-md-3"></div>
    <div class="col-md-6">
        <form action="login" method="POST">
            <input type="text" placeholder="username" name="username" class="form-control login-field" />
            <input type="password" placeholder="password" name="password" class="form-control login-field" />
            <input type="hidden" name='_token' value='{{csrf_token()}}' />
            @if (env('POLR_ACCT_CREATION_RECAPTCHA') == false)
                <div class="captcha-box">
                    <img src="{{ captcha_src() }}" width="120" height="36" onclick="this.src='{{captcha_src()}}'+Math.random()" alt="captcha">
                    <input type="text" name="captcha" class="form-control" id="captcha" required placeholder="Please Insert Captch">
                </div>
                @error('captcha')  <div class="captcha-message">{{ $message }}</div> @enderror
            @else
                <div class="g-recaptcha" data-sitekey="{{env('POLR_RECAPTCHA_SITE_KEY')}}"></div>
            @endif
            
            <input type="submit" value="Login" class="login-submit btn btn-success" />

            <p class='login-prompts'>
            @if (env('POLR_ALLOW_ACCT_CREATION') == true)
                <small>Don't have an account? <a href='{{route('signup')}}'>Register</a></small>
            @endif

            @if (env('SETTING_PASSWORD_RECOV') == true)
                <small>Forgot your password? <a href='{{route('lost_password')}}'>Reset</a></small>
            @endif
            </p>
        </form>
    </div>
    <div class="col-md-3"></div>
</div>
@endsection

@section('js')
    @if (env('POLR_ACCT_CREATION_RECAPTCHA'))
    <script src="https://www.google.com/recaptcha/enterprise.js" async defer></script>
    @endif
@endsection
