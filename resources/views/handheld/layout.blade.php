<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <!--meta para caracteres especiales-->
    <meta charset="utf-8">
    <title>{{ config('app.name', '') }} - @yield('title')</title>
    {{ HTML::meta('csrf-token', csrf_token()) }}

    <!-- Bootstrap CSS local fallback -->
    {{ HTML::style(asset('handheld/style.css')) }}
    {{ HTML::script(asset('handheld/jquery-1.12.4.min.js')) }}
    {{ HTML::script(asset('handheld/jquery.steps.min.js')) }}
</head>
<body  class="bg-login">

@yield('content')

</body>
</html>