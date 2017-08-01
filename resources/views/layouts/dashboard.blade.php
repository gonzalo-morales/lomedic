<!DOCTYPE html>
<html lang="es">
<head>
    <!--meta para caracteres especiales-->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - @yield('title')</title>
    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <!--<link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.99.0/css/materialize.min.css">
    <!--estilo css personal-->
    <link type="text/css" rel="stylesheet" href="{{ asset('css/style.css') }}"  media="screen,projection"/>
    @yield('header-top')
</head>

<?php
use App\Menu;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

$Menu = New Menu();
// $MainMenu =  $Menu->getMenu();
$MainMenu =  '';

$Url = URL('/');
$Usuario = Session::get('usuario');
$Company = request()->company;

$QueryUser =  DB::table('ges_cat_usuarios')->where('usuario','=',$Usuario)->get()->toarray();
$QueryCompany =  DB::table('gen_cat_empresas')->where('conexion','=',$Company)->get()->toarray();


$QueryOterCompany =  DB::table('gen_cat_empresas')->where('conexion','<>',$Company)->get()->toarray();



$NombreUsuario = !empty($QueryUser[0]->nombre_corto) ? $QueryUser[0]->nombre_corto : '<\ Usuario >';
$NombreEmpresa = !empty($QueryCompany[0]->nombre_comercial) ? $QueryCompany[0]->nombre_comercial : '<\ Empresa >';
$LogoEmpresa = !empty($QueryCompany[0]->logotipo) ? $QueryCompany[0]->logotipo : 'twitch-logo.png';

$Color = !empty($QueryCompany[0]->color) ? $QueryCompany[0]->color : 'teal';
?>
<body class="grey lighten-3">

<div class="navbar-fixed ">
    <nav class="top-nav {{ $Color }} z-depth-0 nav-extended">
        <div class="nav-wrapper">
            <a href="#" data-activates="slide-out" class="button-collapse"><i class="material-icons">menu</i></a>
            <a href="#!" data-activates="enteDrop" data-beloworigin="true" class="brand-logo dropdown-button logo-enterprise">
                <img src="/img/{{ $LogoEmpresa }}" alt="Sesión activa" class="circle responsive-img"> {{ $NombreEmpresa }}
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
            <a href="#"><span class="white-text name">{{ $NombreUsuario }}</span></a>
            <!-- <a href="#"><span class="white-text">Facturación</span></a> -->
        </div>
    </li>
    <!--enteprises dropdown-->
    <ul id='enteDrop' class='dropdown-content'>
        @foreach($QueryOterCompany as $Companys)
            <li><a target="_blank" href="{{ $Url }}/{{ $Companys->conexion }}"><i class="material-icons">business</i>{{ $Companys->nombre_comercial }}</a></li>
        @endforeach
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
                <ul id='main-menu'>
                    @each('partials.menu', $menu, 'modulo')
                </ul>
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

            <div class="row {{ $Color }}">
                <div class="col s12">
                    <a href="{{ $Url }}/{{ $Company }}" class="breadcrumb">Home</a>
                    <!-- <a href="#!" class="breadcrumb">Section</a> -->
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
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>

	@yield('header-bottom')

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.99.0/js/materialize.min.js"></script>
    <script type="text/javascript" src="{{ asset('js/InitiateComponents.js') }}"></script>

</body>
</html>