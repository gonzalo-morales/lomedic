<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>@yield('title')</title>
	{{ HTML::meta('viewport', 'width=device-width, initial-scale=1') }}
	{{ HTML::favicon(asset("img/logotipos/$menuempresa->logotipo")) }}
	{{ HTML::style(asset('css/style.css')) }}
</head>
<body>
<div class="black-text" style="max-width: 94%;">
	@yield('content')
</div>
</body>
</html>
