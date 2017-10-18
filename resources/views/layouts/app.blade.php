<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	<!--meta para caracteres especiales-->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>{{ config('app.name', '') }} - @yield('title')</title>
	{{ HTML::meta('viewport', 'width=device-width, initial-scale=1') }}
	{{ HTML::meta('csrf-token', csrf_token()) }}
	{{ HTML::favicon(asset("img/sim2.svg")) }}
	
	<!-- Bootstrap CSS local fallback -->
	{{ HTML::style(asset('css/bootstrap.min.css')) }}
	
	{{ HTML::style(asset('css/style.css'), ['media'=>'screen,projection']) }}
</head>
<body  style="background-image: url('../img/pills2.jpg');-webkit-background-size: cover;-moz-background-size: cover;-o-background-size: cover;background-size: cover;background-repeat: no-repeat;">

@yield('content')

<!--@ include('layouts.ticket')-->

<!-- jQuery CDN -->
{{ HTML::script('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js') }}
<!-- jQuery local fallback -->
<script>window.jQuery || document.write('<script src="{{asset('js/jquery.min.js') }}"><\/script>')</script>

{{ HTML::script('https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js') }}
{{ HTML::script(asset('js/popper.min.js')) }}
  
<!-- Bootstrap JS CDN -->
{{ HTML::script('https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js') }}
<!-- Bootstrap JS local fallback -->
<script>if(typeof($.fn.modal) === 'undefined') {document.write('<script src="{{asset('js/bootstrap.min.js') }}"><\/script>')}</script>


</body>
</html>

