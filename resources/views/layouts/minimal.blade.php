<!DOCTYPE html>
<html>
    <head>
        <title>Polr @yield('title')</title>
        @yield('css')
    </head>
    <body>
        @yield('content')

        <script src="/js/jquery-3.7.1.min.js"></script>
        @yield('js')
    </body>
</html>
