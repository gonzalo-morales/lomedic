<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>SIM - @yield('title')</title>
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.99.0/css/materialize.min.css">
	<link rel="stylesheet" href="{{ asset('css/style.css') }}" media="screen,projection"/>
	@yield('header-top')
</head>
<body class="grey lighten-3">

<div class="navbar-fixed ">
	<nav class="top-nav {{$empresa->color}} z-depth-0 nav-extended">
		<div class="nav-wrapper">
			<a href="#" data-activates="slide-out" class="button-collapse"><i class="material-icons">menu</i></a>
			<a href="#!" data-activates="enteDrop" data-beloworigin="true" class="brand-logo dropdown-button logo-enterprise">
				<img src="{{asset("img/$empresa->logotipo")}}" alt="Sesión activa" class="circle responsive-img"> {{ $empresa->nombre_comercial }}
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
				<img src="{{asset('img/userBG2.jpg')}}">
			</div>
			<a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
				<span class="white-text name">
					<i class="tiny material-icons">power_settings_new</i> CERRAR SESIÓN
				</span>
			</a>
			<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
				{{ csrf_field() }}
			</form>
			<a href="#"><span class="white-text name">{{ Auth::User()->nombre_corto }}</span></a>
		</div>
	</li>

	<ul id='enteDrop' class='dropdown-content'>
		@foreach($empresas as $_empresa)
		<li><a target="_blank" href="{{ companyRoute('HomeController@index',['company' => $_empresa->conexion]) }}"><i class="material-icons">business</i>{{ $_empresa->nombre_comercial }}</a></li>
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
		@each('partials.menu', $menu, 'modulo')
	</ul>
</ul>

<ul id="slide-help" class="side-nav">
	<li>
		<div class="user-view center">
			<span><i class="material-icons medium white-text">live_help</i></span>
			<div class="background">
				<img src="{{asset('img/helpBG2.png')}}">
			</div>
			<a class="white-text" href="#"><span class="name">Sección de ayuda</span></a>
			<a href="#ticketHelp" class="waves-effect waves-light btn-flat white-text">Crear ticket/solicitud</a>
		</div>
	</li>
	<li><a href="#!">Proceso NAUS1234</a></li>
	<li><a href="#!">Proceso NAUS1234</a></li>
	<li><a href="#!">Proceso NAUS1234</a></li>
	<li><a href="#!">Proceso NAUS1234</a></li>
</ul>

<div class="row {{ $empresa->color }}">
	<div class="col s12">
		<a href="{{ companyRoute('HomeController@index',['company' => $empresa->conexion]) }}" class="breadcrumb">Home</a>
		<!-- <a href="#!" class="breadcrumb">Section</a> -->
	</div>
</div>

<div class="row">
	@yield('content')
</div>

<!-- scripts -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.99.0/js/materialize.min.js"></script>
<script type="text/javascript" src="{{ asset('js/InitiateComponents.js') }}"></script>
@yield('header-bottom')
</body>
</html>