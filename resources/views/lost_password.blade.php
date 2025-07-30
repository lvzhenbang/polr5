@extends('layouts.base')

@section('content')
<h1 class="header mb-2 text-center">Lost Password</h1>

<div class="col-lg-6 mx-auto">
    <form action="/lost_password" method="POST">
        <input type="hidden" name="_token" value="{{csrf_token()}}">

        <div class="my-4">
            <!-- <label for="email" class="form-label">Email:</label> -->
            <input type="email" name="email" placeholder="Enter your email" required class="form-control">
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary">Send a password reset email</button>
        </div>
    </form>
</div>
@endsection
