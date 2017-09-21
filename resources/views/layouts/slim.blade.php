<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>SIM - @yield('title')</title>
	{{ HTML::meta('viewport', 'width=device-width, initial-scale=1') }}
	{{ HTML::favicon(asset("img/$empresa->logotipo")) }}
	{{ HTML::style(asset('css/bootstrap.min.css'), ['media'=>'screen,projection'])}}
	{{ HTML::style('https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css') }}
	{{ HTML::style(asset('css/style.css')) }}
	
</head>
<body>
<div class="black-text" style="max-width: 94%;">
	@yield('content')
</div>
{{ HTML::script('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js') }}
{{ HTML::script('https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js') }}
{{ HTML::script(asset('js/bootstrap.min.js')) }}
</body>
</html>
