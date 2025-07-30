<nav class="navbar navbar-expand-lg bg-dark sticky-top">
    <div class="container">
        <!-- signin/signup/admin for pc -->
        <button class="navbar-toggler text-white" type="button" data-bs-toggle="offcanvas" data-bs-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16" class="bi" height="24" width="24" aria-hidden="true"> <path fill-rule="evenodd" d="M2.5 11.5A.5.5 0 0 1 3 11h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4A.5.5 0 0 1 3 7h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4A.5.5 0 0 1 3 3h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"></path> </svg>
        </button>
        <!-- nav brand -->
        <a class="navbar-brand text-white" href="{{ route('index') }}">{{env('APP_NAME')}}</a>
        <!-- navigation -->
        <div class="offcanvas offcanvas-start" tabindex="-1" id="navbar">
            <div class="offcanvas-header px-4 pb-0">
                <h5 class="offcanvas-title">{{env('APP_NAME')}} Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close" data-bs-target="#navbar"></button>
            </div>
            <div class="offcanvas-body p-4 pt-0 p-lg-0">
                <hr class="d-lg-none text-white">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item col-12"><a class="nav-link text-white" href="{{ route('about') }}">About</a></li>
                </ul>
            </div>
        </div>

        
        <!-- signin/signup/admin -->
        @if (empty(session('username')))
            <a class="nav-link text-white" href="/login">
                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"/>
                </svg>
            </a>
        @else
        <div class="dropdown">
            <button class="btn btn-link nav-link px-0 align-items-center text-white dropdown-toggle" data-bs-display="static" data-bs-toggle="dropdown">
                {{session('username')}}
            </button>
            <ul class="dropdown-menu dropdown-menu-end bg-white" aria-labelledby="dropdownMenu" data-bs-popper="static">
                <li><a class="dropdown-item text-black" href="{{ route('admin') }}">Dashboard</a></li>
                <!-- <li><a class="dropdown-item text-black" href="{{ route('admin') }}#settings">Reset Password</a></li> -->
                <li><a class="dropdown-item text-black" href="{{ route('logout') }}">Logout</a></li>
            </ul>
        </div>
        @endif
    </div>
</nav>

