<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	<!--meta para caracteres especiales-->
	<meta charset="utf-8">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="robots" content="noindex,nofollow" />
	<base href="{{url('/')}}" />
	<title>{{ config('app.name', '') }} - @yield('title')</title>
	{{ HTML::meta('viewport', 'width=device-width, initial-scale=1') }}
	{{ HTML::meta('csrf-token', csrf_token()) }}
	{{ HTML::favicon(asset("img/sim2.svg")) }}
	
	<!-- Bootstrap CSS local fallback -->
	{{ HTML::style(asset('css/bootstrap/dist/css/bootstrap.min.css')) }}
	{{ HTML::style(asset('vendor/btn-load/style.css')) }}
	{{ HTML::style(asset('css/style.css')) }}
	
	<link rel="dns-prefetch" href="//ajax.googleapis.com">
    <link rel="dns-prefetch" href="//maxcdn.bootstrapcdn.com">
    <link rel="dns-prefetch" href="//cdnjs.cloudflare.com">
</head>
<body  class="bg-login">

@yield('content')

<!-- jQuery CDN -->
{{ HTML::script('https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js') }}
<!-- jQuery local fallback -->
<script>window.jQuery || document.write('<script src="{{asset('js/jquery.min.js') }}"><\/script>')</script>

{{ HTML::script('https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.6/umd/popper.min.js') }}
{{ HTML::script(asset('js/popper.min.js')) }}
  
<!-- Bootstrap JS CDN -->
{{ HTML::script('https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js') }}
<!-- Bootstrap JS local fallback -->
<script>if(typeof($.fn.modal) === 'undefined') {document.write('<script src="{{asset('js/bootstrap.min.js') }}"><\/script>')}</script>

{{ HTML::script(asset('vendor/btn-load/script.js')) }}
</body>
</html>