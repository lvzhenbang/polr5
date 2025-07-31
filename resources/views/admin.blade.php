@extends('layouts.base')

@section('css')
<link rel="stylesheet" href="/css/admin.css">
<link rel="stylesheet" href="/css/dataTables.bootstrap5.css">
@endsection

@section('content')
<div class="row">
    <div class="col-md-2">
        <ul class="nav nav-underline flex-md-row flex-lg-column admin-nav" role="tablist">
            <li class="nav-item" role="presentation" aria-current="Home">
                <a class="nav-link active" href="#home" data-bs-toggle="tab" data-bs-target="#home-pane">Dashboard</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" href="#links" data-bs-toggle="tab" data-bs-target="#links-pane">Links</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" href="#settings" data-bs-toggle="tab" data-bs-target="#settings-pane">Reset Password</a>
            </li>

            @if ($role == $admin_role)
            <li class="nav-item" role="presentation">
                <a class="nav-link" href="#admin" data-bs-toggle="tab" data-bs-target="#admin-pane">Users</a>
            </li>
            @endif

            @if ($api_active == 1)
            <li class="nav-item" role="presentation">
                <a class="nav-link" href="#developer" data-bs-toggle="tab" data-bs-target="#developer-pane">Developer</a>
            </li>
            @endif
        </ul>
    </div>
    <div class="col-md-10">
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade show active" id="home-pane">
                <h2>Welcome to your {{env('APP_NAME')}} dashboard!</h2>
                <p>Use the links on the left hand side to navigate your {{env('APP_NAME')}} dashboard.</p>
            </div>            
            
            <div role="tabpanel" class="tab-pane fade" id="links-pane">                
                <h3 class="d-none d-lg-block mb-5">Links</h3>
                @if ($role == $admin_role)
                    @include('snippets.link_table', [
                        'table_id' => 'admin_links_table'
                    ])
                @else
                    @include('snippets.link_table', [
                        'table_id' => 'user_links_table'
                    ])
                @endif
            </div>

            <div role="tabpanel" class="tab-pane fade" id="settings-pane">
                <h3 class="d-none d-lg-block mb-5">Reset Password</h3>
                <div class="col-lg-6">
                    <form action="/admin/action/change_password" method="POST">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">                        
                        <div class="mb-4">
                            <label for="password" class="form-label">Old Password</label>
                            <input class="form-control" type="password" required name="current_password" placeholder="Enter your old passsword">
                        </div>
                        <div class="mb-4">
                            <label for="password" class="form-label">New Password</label>
                            <input class="form-control" type="password" required name="new_password" placeholder="Enter your new passsword">
                        </div>
                        <button type="submit" class="btn btn-success">Submit</button>
                    </form>
                </div>
            </div>

            @if ($role == $admin_role)
            <div role="tabpanel" class="tab-pane fade" id="admin-pane">
                <h3  class="d-none d-lg-block mb-5">Users</h3>
                <button onclick="$scope.addNewUserModal(event)" class="btn btn-primary btn-sm">New</button>

                @include('snippets.user_table', [
                    'table_id' => 'admin_users_table'
                ])
            </div>
            @endif

            @if ($api_active == 1)
            <div role="tabpanel" class="tab-pane fade" id="developer-pane">
                <div class="mb-4">
                    <h3 class="d-none d-lg-block mb-5">Developer</h3>

                    <p>API keys and documentation for developers.</p>
                    <p>
                        Documentation:
                        <a href='http://docs.polr.me/en/latest/developer-guide/api/'>http://docs.polr.me/en/latest/developer-guide/api/</a>
                    </p>
                </div>

                <div class="mb-4">
                    <h4>API Key: </h4>
                    <div class="row">
                        <div class="col-md-8">
                            <input type="text" class="form-control status-display" disabled value="{{$api_key}}">
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-danger btn-sm" id="api-reset-key" onclick="$scope.generateNewAPIKey(event, '{{$user_id}}', true)">Reset</button>
                        </div>
                    </div>
                </div>


                <div class="mb-0">
                    <h4>API Quota: </h4>
                    <div class="api-quota">
                        <code>@if ($api_quota == -1) unlimited @else {{$api_quota}} @endif</code>
                        <span> requests per minute</span>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

</div>

<div class="angular-modals"></div>


@endsection

@section('js')
{{-- Include modal templates --}}
@include('snippets.modals')

{{-- Include extra JS --}}
<script src="/js/dataTables.min.js"></script>
<script src="/js/dataTables.bootstrap5.js"></script>
<script src="/js/api.js"></script>
<script src="/js/AdminCtrl.js"></script>
@endsection
