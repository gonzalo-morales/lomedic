<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <!--meta para caracteres especiales-->
    <meta charset="utf-8">
    <title>{{ config('app.name', '') }} - @yield('title')</title>
    {{ HTML::meta('csrf-token', csrf_token()) }}

    {{ HTML::style(asset('handheld/style.css')) }}
    {{ HTML::script(asset('handheld/jquery-1.12.4.min.js')) }}
</head>
<body>

@yield('content')

</body>
</html>