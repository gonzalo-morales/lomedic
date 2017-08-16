<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>SIM - @yield('title')</title>
	{{ HTML::meta('viewport', 'width=device-width, initial-scale=1') }}
	{{ HTML::favicon(asset("img/$empresa->logotipo")) }}
	{{ HTML::style('https://cdnjs.cloudflare.com/ajax/libs/materialize/0.99.0/css/materialize.min.css') }}
	{{ HTML::style(asset('css/style.css')) }}
	
</head>
<body>
<div class="black-text" style="max-width: 94%;">
	@yield('content')
</div>
{{ HTML::script('https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.2/jquery.min.js') }}
{{ HTML::script('https://cdnjs.cloudflare.com/ajax/libs/materialize/0.99.0/js/materialize.min.js') }}
{{ HTML::script(asset('js/InitiateComponents.js')) }}
</body>
</html>
