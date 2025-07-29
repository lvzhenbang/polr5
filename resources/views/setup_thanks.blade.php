@extends('layouts.minimal')

@section('title')
Setup Completed
@endsection

@section('css')
<link rel='stylesheet' href='/css/bootstrap.min.css'>
<link rel='stylesheet' href='/css/setup.css'>
@endsection

@section('content')
<div class="navbar bg-dark sticky-top">
    <a class="navbar-brand text-white" href="/">Polr</a>
</div>

<div class='container'>

    <div class='col-lg-6 p-3 mx-auto my-4 rounded bg-white'>
        <div class='text-center'>
            <img class='setup-logo' src='/img/logo.png'>
        </div>
        <h2>Setup Complete</h2>
        <p>Your Polr setup is complete. To continue, you may <a class="link-secondary" href='{{route('login')}}'>login</a> or
            access your <a class="link-secondary" href='{{route('index')}}'>home page</a>.
        </p>
        <p>Consider taking a look at the <a class="link-secondary" href='http://docs.polr.me/'>docs</a> or <a class="link-secondary" href='//github.com/cydrobolt/polr'>README</a>
            for assistance.
        </p>
        <p>You may also join us on IRC at <a class="link-secondary" href='//webchat.freenode.net/?channels=#polr'><code>#polr</code></a> on freenode for assistance or questions.</p>

        <p>Thanks for using Polr!</p>
    </div>
</div>


@endsection
