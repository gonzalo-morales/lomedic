<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
    	<meta charset="utf-8">
    	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<meta name="robots" content="noindex,nofollow" />
    	<base href="{{url('/')}}" />
    	<title>@yield('title')</title>
    	{{ HTML::meta('viewport', 'width=device-width, initial-scale=1') }}
    	{{ HTML::favicon(asset("img/logotipos/$menuempresa->logotipo")) }}
    </head>
    <body>
    <div class="black-text" style="max-width: 100%;">
    	@yield('content')
    </div>
    </body>
</html>