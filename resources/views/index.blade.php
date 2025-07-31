@extends('layouts.base')

@section('css')
<link rel="stylesheet" href="css/index.css" />
@endsection

@section('content')
<h1 class="mb-5 text-center">{{env('APP_NAME')}}</h1>

<form method="POST" action="/shorten" role="form">
    <input type="hidden" name="_token" value="{{csrf_token()}}" />
    <input type="hidden" name="link-url" id="link-url">

    <input type="url" class="form-control long-link-input" placeholder="https://" autocomplete="off" required>

    <div class="row" id="link-options-box" style="display: none;">
        <p>Customize link</p>

        @if (!env('SETTING_PSEUDORANDOM_ENDING'))
        {{-- Show secret toggle only if using counter-based ending --}}
        <div class="btn-group col-md-4" role="group" aria-label="check group">
            <input type="radio" class="btn-check" name="options" value="p" id="btn-radio-1" checked autocomplete="off">
            <label class="btn btn-outline-primary btn-sm" for="btn-radio-1">Public</label>
            <input type="radio" class="btn-check" name="options" value="s" id="btn-radio-2" autocomplete="off">
            <label class="btn btn-sm btn-outline-primary" for="btn-radio-2">Secret</label>
        </div>
        @endif

        <div class="mb-4 mt-2">
            <div class="input-group">
                <span class="input-group-text">{{env('APP_ADDRESS')}}/</span>
                <input type="text" class="form-control" id="custom-url-field" name="custom-ending" autocomplete="off">
                <button class="btn btn-success" id="check-link-availability">Check Availability</button>
            </div>
            <div class="mt-2" id="link-availability-status"></div>
        </div>
    </div>

    <div class="row" id="utm-options-box" style="display: none;">
        <p>Add UTM</p>
        <div class="col-md-4 mb-4">
            <label for="utmSource" class="form-label">Source</label>
            <input class="form-control" id="utmSource" type="text" name="utmSource">
        </div>
        <div class="col-md-4 mb-4">
            <label for="utmMedium" class="form-label">Medium</label>
            <input class="form-control" id="utmMedium" type="text" name="utmMedium">
        </div>
        <div class="col-md-4 mb-4">
            <label for="utmCampaign" class="form-label">Campaign</label>
            <input class="form-control" id="utmCampaign" type="text" name="utmCampaign">
        </div>
    </div>
    <div class="my-3">
        <p>Destionation Url</p>
        <input type="text" class="form-control" id="destination-url">
    </div>

    <div class="text-center">
        <button type="submit" class="btn btn-primary btn-large">Shorten</button>
        <span class="btn btn-outline-secondary btn-large" id="show-link-options">Link Options</span>
        <span class="btn btn-outline-info btn-large" id="show-utm-options">UTM Options</span>
    </div>
</form>

<div id="tips" class="text-muted text-center tips">
    <span class="spinner-border spinner-border-sm text-dark" role="status"></span> Loading Tips...
</div>
@endsection

@section('js')
<script src="js/index.js"></script>
@endsection
