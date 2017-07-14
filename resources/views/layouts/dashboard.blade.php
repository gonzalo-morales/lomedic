<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	<!--meta para caracteres especiales-->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<!--Let browser know website is optimized for mobile-->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>{{ config('app.name', 'Laravel') }}</title>
	<!--Import Google Icon Font-->
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<!--Import materialize.css-->
	<!--<link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>-->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.99.0/css/materialize.min.css">
	<!--dataTable.css-->
	<link href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css" type="text/css" rel="stylesheet" media="screen,projection" />
	<link href="https://cdn.datatables.net/buttons/1.3.1/css/buttons.dataTables.min.css" type="text/css" rel="stylesheet" />
	<!--estilo css personal-->
	<link type="text/css" rel="stylesheet" href="{{ asset('css/style.css') }}"  media="screen,projection"/>
</head>
<body class="grey lighten-3">

<div class="navbar-fixed">
	<nav class="top-nav teal z-depth-0 nav-extended">
		<div class="nav-wrapper">
			<a href="#" data-activates="slide-out" class="button-collapse"><i class="material-icons">menu</i></a>
			<a href="#!" data-activates="enteDrop" data-beloworigin="true" class="brand-logo dropdown-button logo-enterprise">
				<img src="http://www.technologytell.com/gaming/files/2013/02/twitch-logo.png" alt="Sesión activa" class="circle responsive-img"> Entreprise
			</a>
			<ul class="right">
				<li><a href="#" data-activates="slide-help" class="help-collapse"><i class="material-icons">live_help</i></a></li>
				<li><a class="dropdown-button" href="#!" data-activates="notDrop" data-beloworigin="true"><i class="material-icons">add_alert</i></a></li>
			</ul>
		</div><!--/nav-wrapper-->
	</nav><!--/nav-->
</div>

<ul id="slide-out" class="side-nav">
	<li>
		<div class="user-view center">
			<object id="front-page-logo" class="Sim" type="image/svg+xml" data="img/sim2.svg" name="SIM">Your browser does not support SVG</object>
			<div class="background">
				<img src="img/userBG2.jpg">
			</div>
			<a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
				<span class="white-text name">
					<i class="tiny material-icons">power_settings_new</i> CERRAR SESIÓN
				</span>
			</a>
			<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
				{{ csrf_field() }}
			</form>


			<a href="#"><span class="white-text name">Hernando Fernando Hernández Fernández</span></a>
			<a href="#"><span class="white-text">Facturación</span></a>
		</div>
	</li>
	<!--enteprises dropdown-->
	<ul id='enteDrop' class='dropdown-content'>
		<li><a href="#!"><i class="material-icons">view_module</i>four</a></li>
		<li><a href="#!"><i class="material-icons">cloud</i>five</a></li>
	</ul>
	<!--notifications dropdown-->
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
	</ul><!--/dropdown-->
				<li class="active"><a href="#!"><i class="material-icons">supervisor_account</i>Administración</a></li>
				<li><a href="#!"><i class="material-icons">store</i>Almacén</a></li>
				<li><a href="#!"><i class="material-icons">class</i>Auditoria</a></li>
				<li><a href="#!"><i class="material-icons">payment</i>Compra</a></li>
				<li><a href="#!"><i class="material-icons">receipt</i>Pedidos</a></li>
				<li><a href="#!"><i class="material-icons">assignment_ind</i>Recursos Humanos</a></li>
				<li><a href="#!"><i class="material-icons">assignment</i>Reportes</a></li>
			</ul><!--/slide-out, principal nav-->
			<ul id="slide-help" class="side-nav">
				<li>
				<div class="user-view center">
					<span><i class="material-icons medium white-text">live_help</i></span>
					<div class="background">
						<img src="img/helpBG2.png">
					</div>
					<a class="white-text" href="#"><span class="name">Sección de ayuda</span></a>
					<a href="#ticketHelp" class="waves-effect waves-light btn-flat white-text">Crear ticket/solicitud</a>
				</div>
				</li>
				<li><a href="#!">Proceso NAUS1234</a></li>
				<li><a href="#!">Proceso NAUS1234</a></li>
				<li><a href="#!">Proceso NAUS1234</a></li>
				<li><a href="#!">Proceso NAUS1234</a></li>
			</ul><!--/slide-help, users help-->

			<div class="row teal">
				<div class="col s12">
					<a href="#!" class="breadcrumb">Home</a>
					<a href="#!" class="breadcrumb">Section</a>
				</div>
			</div><!--/breadcrum-->

<div class="row">
@yield('content')
</div><!--/row section-->

		<div id="ticketHelp" class="modal modal-fixed-footer">
			<div class="modal-content">
				<h4>Modal Header</h4>
				<p>A bunch of text</p>
			</div>
			<div class="modal-footer">
				<a href="#!" class="modal-action modal-close waves-effect waves-blue btn-flat">Agree</a>
			</div>
		</div><!--/Modal de ayuda-->

<!--Import jQuery before materialize.js-->
<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="js/materialize.min.js"></script>
<!--<script type="text/javascript" src="js/tableData.js"></script>-->
<script type="text/javascript" src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<!--CDN tableData-->
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/pdfmake-0.1.18/dt-1.10.12/b-1.2.2/b-colvis-1.2.2/b-html5-1.2.2/b-print-1.2.2/cr-1.3.2/r-2.1.0/datatables.min.js"></script>
<!--JS que manda a llamar los dataTables-->
<script type="text/javascript" src="js/medData.js"></script>
<!--Charts-->
<script type="text/javascript" src="js/dataChart.js"></script>
<!--CDN chartsJS, esta versión viene con http://momentjs.com/ incluído-->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.min.js"></script>
<script type="text/javascript" src="js/initiateComponents.js"></script>
</body>
</html>