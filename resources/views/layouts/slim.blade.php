<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>SIM - @yield('title')</title>
	{{ HTML::meta('viewport', 'width=device-width, initial-scale=1') }}
	{{ HTML::favicon(asset("img/logotipos/$menuempresa->logotipo")) }}
	{{ HTML::style(asset('css/bootstrap/dist/css/bootstrap.min.css')) }}
	{{ HTML::style(asset('css/style.css')) }}
	
</head>
<body>
<div class="black-text" style="max-width: 94%;">
	@yield('content')
</div>
<!-- jQuery CDN -->
{{ HTML::script('https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js') }}

<!-- jQuery local fallback -->
<script>window.jQuery || document.write('<script src="{{asset('js/jquery.min.js') }}"><\/script>')</script>

{{ HTML::script('https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.6/umd/popper.min.js') }}
{{ HTML::script(asset('js/popper.min.js')) }}

<!-- Bootstrap JS CDN -->
{{ HTML::script('https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js') }}
{{ HTML::script(asset('js/bootstrap.min.js')) }}
</body>
</html>
