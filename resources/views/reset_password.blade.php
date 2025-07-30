@extends('layouts.base')

@section('content')
<h1 class="header text-center mb-2">Reset Password</h1>

<div class="col-lg-6 mx-auto">
    <form action="#" method="POST">
        <input type="hidden" name="_token" value="{{csrf_token()}}" />
        
        <div class="my-4">
            <label for="passwordFirst" class="form-label">New Password:</label>
            <input type="password" reuired id="passwordFirst" placeholder="Enter your New Password" class="form-control">
        </div>
        <div class="my-4">
            <label for="passwordFirst" class="form-label">Confirm New Password:</label>
            <input type="password" name="new_password" reuired id="passwordConfirm" placeholder="Confirm New Password" class="form-control">
        </div>

        <button type="submit" id="submitForm" class="btn btn-primary">Reset Password</button>
    </form>
</div>
@endsection

@section('js')
<script src="/js/reset_password.js"></script>
@endsection
