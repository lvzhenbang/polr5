@extends('layouts.base')

@section('css')
<link rel="stylesheet" href="/css/shorten_result.css" />
@endsection

@section('content')
<div class="col-lg-6 mx-auto">
<h3 class="mb-5">Shortened URL</h3>
    <div class="input-group mb-4">
        <input type="text" class="form-control" value="{{$short_url}}" id="short_url" disabled>
        <button class="btn btn-outline-secondary" id="clipboard-copy" data-bs-trigger="focus" data-bs-toggle="tooltip" data-bsplacement="bottom" data-bs-title="Copied!">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M4 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM2 5a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-1h1v1a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h1v1z"/>
            </svg>
        </button>
    </div>
    <button id="generate-qr-code" class="btn btn-primary">Generate QR Code</button>
    <a href="{{route('index')}}" class="btn btn-info">Shorten another</a>

    <div class="qr-code-container"></div>
</div>
@endsection


@section('js')
<script src="/js/qrcode.min.js"></script>
<script src="/js/shorten_result.js"></script>
@endsection
