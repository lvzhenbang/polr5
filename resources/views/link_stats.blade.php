@extends('layouts.base')

@section('css')
<link rel="stylesheet" href="/css/dataTables.bootstrap5.css">
<link rel="stylesheet" href="/css/jquery-jvectormap.css">
<link rel="stylesheet" href="/css/flatpickr.min.css">
<link rel="stylesheet" href="/css/stats.css">
@endsection

@section('content')
<div class="stats-header mb-3">
    <h3 class="py-3 text-center">Stats</h3>
    <div class="row">
        <div class="col-md-3 offset-md-3 link-meta">
            <p>
                <b>Short Link: </b>
                <a target="_blank" href="{{ env('APP_PROTOCOL') }}/{{ env('APP_ADDRESS') }}/{{ $link->short_url }}">
                    {{ env('APP_ADDRESS') }}/{{ $link->short_url }}
                </a>
            </p>
            <p>
                <b>Long Link: </b>
                <a target="_blank" href="{{ $link->long_url }}">{{ Str::limit($link->long_url, 50) }}</a>
            </p>
            {{-- <p>
                <em>Tip: Clear the right date bound (bottom box) to set it to the current date and time. New
                clicks will not show unless the right date bound is set to the current time.</em>
            </p> --}}
        </div>
        <div class="col-md-3">
            <form action="" method="GET">
                <div class="input-group mb-2 date">
                    <input type="text" name="left_bound" class="form-control" id="left-bound-picker">
                    <label for="left-bound-picker" class="form-label mb-0 input-group-text">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16px" height="16px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-calendar"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    </label>
                </div>
                <div class="input-group mb-2 date">
                    <input type="text" name="right_bound" class="form-control" id="right-bound-picker">
                    <label for="right-bound-picker" class="form-label mb-0 input-group-text">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16px" height="16px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-calendar"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    </label>
                </div>

                <button type="submit" class="btn btn-primary">Refresh</button>
            </form>
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-8">
        <h4 class="d-block">Traffic over Time <span class="fs-4 text-primary">(total: {{ $link->clicks }})</span></h4>
        <canvas id="dayChart"></canvas>
    </div>
    <div class="col-md-4">
        <h4 class="d-block">Traffic sources</h4>
        <canvas id="refererChart"></canvas>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-4">
        <h4 class="d-block">Browsers</h4>
        <canvas id="browserChart"></canvas>
    </div>
    <div class="col-md-4">
        <h4 class="d-block">Operation Systems</h4>
        <canvas id="osChart"></canvas>
    </div>
    <div class="col-md-4">
        <h4 class="d-block">Devices</h4>
        <canvas id="deviceChart"></canvas>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <h4 class="d-block">Map</h4>
        <div id="mapChart"></div>
    </div>
    <div class="col-md-6">
        <h4 class="d-block">Referers</h4>
        <table class="table table-hover" id="refererTable">
            <thead>
                <tr>
                    <th>Host</th>
                    <th>Clicks</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($referer_stats as $referer)
                    <tr>
                        <td>{{ $referer->label }}</td>
                        <td>{{ $referer->clicks }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>

@endsection

@section('js')
{{-- Load data --}}
<script>
// Load data
var dayData = JSON.parse('{!! json_encode($day_stats) !!}');
var refererData = JSON.parse('{!! json_encode($referer_stats) !!}');
var countryData = JSON.parse('{!! json_encode($country_stats) !!}');
var browserData = JSON.parse('{!! json_encode($browser_stats) !!}');
var osData = JSON.parse('{!! json_encode($os_stats) !!}');
var deviceData = JSON.parse('{!! json_encode($device_stats) !!}');

// Load datepicker dates
var datePickerLeftBound = '{{ $left_bound }}';
var datePickerRightBound = '{{ $right_bound }}';
</script>

{{-- Include extra JS --}}
<script src="/js/lodash.min.js"></script>
<script src="/js/chart.bundle.min.js"></script>
<script src="/js/dataTables.min.js"></script>
<script src="/js/dataTables.bootstrap5.js"></script>
<script src="/js/jquery-jvectormap.min.js"></script>
<script src="/js/jquery-jvectormap-world-mill.js"></script>
<script src="/js/moment.min.js"></script>
<script src="/js/flatpickr.min.js"></script>
<script src="/js/StatsCtrl.js"></script>
@endsection
