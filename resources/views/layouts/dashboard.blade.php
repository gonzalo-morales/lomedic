<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>SIM - @yield('title')</title>
	{{ HTML::meta('viewport', 'width=device-width, initial-scale=1') }}
	{{ HTML::meta('csrf-token', csrf_token()) }}
	{{ HTML::favicon(asset("img/$empresa->logotipo")) }}
	{{ HTML::style('https://fonts.googleapis.com/icon?family=Material+Icons') }}
	{{ HTML::style('https://cdnjs.cloudflare.com/ajax/libs/materialize/0.99.0/css/materialize.min.css') }}
	{{ HTML::style(asset('css/style.css'), ['media'=>'screen,projection']) }}
	@yield('header-top')
</head>
<body>

<div class="navbar-fixed ">
	<nav class="top-nav {{$empresa->color}} z-depth-0 nav-extended">
		<div class="nav-wrapper">
			<a href="#" data-activates="slide-out" class="button-collapse"><i class="material-icons">menu</i></a>
			<a href="#!" data-activates="enteDrop" data-beloworigin="true" class="brand-logo dropdown-button logo-enterprise">
				{{ HTML::image(asset("img/$empresa->logotipo"), 'Logo', ['class'=>'circle responsive-img']) }} {{ $empresa->nombre_comercial }}
			</a>
			<ul class="right">
				<li><a href="#" data-activates="slide-help" class="help-collapse"><i class="material-icons">live_help</i></a></li>
				<li><a class="dropdown-button" href="#!" data-activates="notDrop" data-beloworigin="true"><i class="material-icons">add_alert</i></a></li>
			</ul>
		</div>
	</nav>
</div>

<ul id="slide-out" class="side-nav">
	<li>
		<div class="user-view center">
			<object id="front-page-logo" class="Sim" type="image/svg+xml" data="{{asset('img/sim2.svg')}}" name="SIM">Your browser does not support SVG</object>
			<div class="background">
				{{ HTML::image(asset('img/userBG2.jpg')) }}
			</div>
			<a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="white-text name">
				<i class="tiny material-icons">power_settings_new</i> CERRAR SESIÓN
			</a>
			{!! Form::open(['route' => 'logout', 'before' => 'csrf', 'id' => 'logout-form', 'class' => 'hidden']) !!} {!! Form::close() !!}
			<a href="#"><span class="white-text name">{{ Auth::User()->nombre_corto }}</span></a>
		</div>
	</li>

	<ul id='enteDrop' class='dropdown-content'>
		@foreach($empresas as $_empresa)
		<li><a target="_blank" href="{{ companyAction('HomeController@index',['company' => $_empresa->conexion]) }}">{{ HTML::image(asset("img/$_empresa->logotipo"), null, ['class'=>'circle responsive-img','width'=>'24px']) }} {{ $_empresa->nombre_comercial }}</a></li>
		@endforeach
	</ul>

	<ul id="notDrop" class="dropdown-content noti-content collection">
		<li class="collection-item">
			<p>Notification message</p>
		</li>
		<li class="collection-item">
			<p>Notification message</p>
		</li>
		<li class="collection-item">
			<p>Notification message</p>
		</li>
	</ul>

	<ul id='main-menu'>
		@if(isset($menu))
			@each('partials.menu', $menu, 'modulo')
		@endif
	</ul>
</ul>

<div class="row {{ $empresa->color }}">
	<div class="col s12">
		{{ HTML::link(companyAction('HomeController@index', ['company' => $empresa->conexion]), 'Inicio', ['class'=>'breadcrumb']) }}
		@foreach(routeNameReplace() as $key=>$item)
			@if($item !== 'index' && !empty($item))
				{{ HTML::link($key == 1 ? companyRoute('index') : '#', $item, ['class'=>'breadcrumb']) }}
			@endif
		@endforeach
	</div>
</div>

@yield('content')

@include('layouts.ticket')

<!-- scripts -->
{{ HTML::script('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js') }}
{{ HTML::script('https://cdnjs.cloudflare.com/ajax/libs/materialize/0.99.0/js/materialize.min.js') }}
{{ HTML::script(asset('js/InitiateComponents.js')) }}
@yield('header-bottom')
</body>
</html>