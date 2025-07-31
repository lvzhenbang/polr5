<svg xmlns="http://www.w3.org/2000/svg" class="d-none">
    <symbol id="check2" viewBox="0 0 16 16">
        <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z" fill="currentColor"></path>
    </symbol>
    <symbol id="circle-half" viewBox="0 0 16 16">
        <path d="M8 15A7 7 0 1 0 8 1v14zm0 1A8 8 0 1 1 8 0a8 8 0 0 1 0 16z" fill="currentColor"></path>
    </symbol>
    <symbol id="moon-stars-fill" viewBox="0 0 16 16">
        <path d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278z" fill="currentColor"></path>
        <path d="M10.794 3.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387a1.734 1.734 0 0 0-1.097 1.097l-.387 1.162a.217.217 0 0 1-.412 0l-.387-1.162A1.734 1.734 0 0 0 9.31 6.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387a1.734 1.734 0 0 0 1.097-1.097l.387-1.162zM13.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.156 1.156 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.156 1.156 0 0 0-.732-.732l-.774-.258a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732L13.863.1z" fill="currentColor"></path>
    </symbol>
    <symbol id="sun-fill" viewBox="0 0 16 16">
        <path d="M8 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z" fill="currentColor"></path>
    </symbol>
</svg>
<nav class="navbar navbar-expand-lg sticky-top border-bottom border-black border-opacity-10">
    <div class="container">
        <!-- signin/signup/admin for pc -->
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16" class="bi" height="24" width="24" aria-hidden="true"> <path fill-rule="evenodd" d="M2.5 11.5A.5.5 0 0 1 3 11h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4A.5.5 0 0 1 3 7h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4A.5.5 0 0 1 3 3h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z" fill="currentColor"></path> </svg>
        </button>
        <!-- nav brand -->
        <a class="navbar-brand" href="{{ route('index') }}">{{env('APP_NAME')}}</a>
        <!-- navigation -->
        <div class="offcanvas offcanvas-start" tabindex="-1" id="navbar">
            <div class="offcanvas-header px-4 pb-0">
                <h5 class="offcanvas-title">{{env('APP_NAME')}} Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close" data-bs-target="#navbar"></button>
            </div>
            <div class="offcanvas-body p-4 pt-0 p-lg-0">
                <hr class="d-lg-none">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item col-12"><a class="nav-link" href="{{ route('about') }}">About</a></li>
                </ul>
            </div>
        </div>

        <ul class="navbar-nav flex-row flex-wrap">
            <!-- signin/signup/admin -->
            @if (empty(session('username')))
            <li class="nav-item">
                <a class="btn-link nav-link px-0 px-lg-2 py-2 d-flex align-items-center" href="/login">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"/>
                    </svg>
                </a>
            </li>
            @else
            <li class="nav-item dropdown">
                <button class="btn btn-link nav-link px-0 px-lg-2 py-2 d-flex align-items-center dropdown-toggle" data-bs-display="static" data-bs-toggle="dropdown">
                    {{session('username')}}
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenu" data-bs-popper="static">
                    <li><a class="dropdown-item" href="{{ route('admin') }}">Dashboard</a></li>
                    <!-- <li><a class="dropdown-item" href="{{ route('admin') }}#settings">Reset Password</a></li> -->
                    <li><a class="dropdown-item" href="{{ route('logout') }}">Logout</a></li>
                </ul>
            </li>
            @endif
            <li class="nav-item py-2 py-lg-1 col-12 col-lg-auto"> <div class="vr d-none d-lg-flex h-100 mx-lg-2"></div> <hr class="d-lg-none my-2"> </li>
            <li class="nav-item dropdown">
                <button class="btn btn-link nav-link px-0 px-lg-2 py-2 d-flex align-items-center dropdown-toggle" id="bd-theme" type="button" aria-expanded="false" data-bs-toggle="dropdown" data-bs-display="static" aria-label="Toggle theme (dark)">
                    <svg class="my-1 theme-icon-active" width="16px" height="16px"><use href="#sun-fill"></use></svg>
                    <span class="d-lg-none ms-2" id="bd-theme-text">Toggle theme</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="bd-theme-text">
                    <li>
                        <button type="button" class="dropdown-item d-flex align-items-center active" data-bs-theme-value="light" aria-pressed="false">
                            <svg class="me-2 opacity-50" width="16px" height="16px"><use href="#sun-fill"></use></svg>
                            Light
                            <svg class="ms-auto d-none" width="16px" height="16px"><use href="#check2"></use></svg>
                        </button>
                    </li>
                    <li>
                        <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark" aria-pressed="true">
                            <svg class="me-2 opacity-50" width="16px" height="16px"><use href="#moon-stars-fill"></use></svg>
                            Dark
                            <svg class="ms-auto d-none" width="16px" height="16px"><use href="#check2"></use></svg>
                        </button>
                    </li>
                    <li>
                        <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="auto" aria-pressed="false">
                            <svg class="me-2 opacity-50" width="16px" height="16px"><use href="#circle-half"></use></svg>
                            Auto
                            <svg class="ms-auto d-none" width="16px" height="16px"><use href="#check2"></use></svg>
                        </button>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>

