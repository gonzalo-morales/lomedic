<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>{{ config('app.name', '') }} - @yield('title')</title>
	{{ HTML::meta('viewport', 'width=device-width, initial-scale=1') }}
	{{ HTML::meta('csrf-token', csrf_token()) }}
	{{ HTML::favicon(asset("img/$empresa->logotipo")) }}
	
	<!-- Bootstrap CSS local fallback -->
	{{ HTML::style(asset('css/bootstrap.min.css')) }}
	
	{{ HTML::style(asset('css/style.css'), ['media'=>'screen,projection']) }}
	{{ HTML::style(asset('css/style-nav.css'), ['media'=>'screen,projection']) }}
	@yield('header-top')
</head>
<body>


<!--
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
		
	</ul>
</ul>-->

<!-- 
<div class="navbar-fixed ">
	<nav class="top-nav {{$empresa->color}} z-depth-0 nav-extended">
		<div class="nav-wrapper">
			<div class="nav-wrapper">
        		<a href="#" data-activates="slide-out" class="button-collapse"><i class="material-icons">menu</i></a>
        		<a href="#!" data-activates="enteDrop" data-beloworigin="true" class="brand-logo dropdown-button logo-enterprise">
        			{{ HTML::image(asset("img/$empresa->logotipo"), 'Logo', ['class'=>'circle responsive-img']) }} {{ $empresa->nombre_comercial }}
        		</a>
        	</div>
			<ul class="right">
				<li><a href="#" data-activates="slide-help" class="help-collapse"><i class="material-icons">live_help</i></a></li>
				<li><a class="dropdown-button" href="#!" data-activates="notDrop" data-beloworigin="true"><i class="material-icons">add_alert</i></a></li>
			</ul>
		</div>
	</nav>
</div>
 -->
 
<div class="w-100 fixed-top">
	<nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" id="sidebarCollapse" class="btn-info navbar-btn"><i class="material-icons">menu</i></button>
            </div>
        </div>
    </nav>
	<ol class="breadcrumb">
		<li class="breadcrumb-item">{{ HTML::link(companyAction('HomeController@index', ['company' => $empresa->conexion]), 'Inicio') }}</li>
		@foreach(routeNameReplace() as $key=>$item)
			@if($item !== 'index' && !empty($item))
				<li class="breadcrumb-item active">{{ HTML::link($key == 1 ? companyRoute('index') : '#', $item) }}</li>
			@endif
		@endforeach
	</ol>
</div>

<div class="wrapper" style="margin-top: 90px;">
    <!-- Sidebar Holder -->
    <nav id="sidebar" class="active bg-info">
    	<div id="sidebar-content">
            <div class="sidebar-header text-center" style="position: relative;">
                <div class="title w-100">
                	<div class="white-text w-100"><object id="front-page-logo" class="Sim" type="image/svg+xml" data="{{asset('img/sim2.svg')}}" name="SIM">Your browser does not support SVG</object></div>
            		<div class="white-text w-100">
                    	<a href="#"><span class="white-text w-100">{{ Auth::User()->nombre_corto }}</span></a>
                    </div>
                	<div class="white-text w-100">
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="white-text">
            				<i class="tiny material-icons">power_settings_new</i> CERRAR SESION
            			</a>
            		</div>
                </div>
                
                <strong>
                	<center><object id="front-page-logo" class="Sim w-100" type="image/svg+xml" data="{{asset('img/sim2.svg')}}" name="SIM">Your browser does not support SVG</object></center>
    				<a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="white-text name">
        				<i class="tiny material-icons">power_settings_new</i>
        			</a>
    			</strong>
    
    			{!! Form::open(['route' => 'logout', 'before' => 'csrf', 'id' => 'logout-form', 'class' => 'hidden']) !!} {!! Form::close() !!}
            </div>
    
            <ul class="list-unstyled components">
                @if(isset($menu))
    				@each('partials.menu', $menu, 'modulo')
    			@endif
            </ul>
        </div>
    </nav>

    <!-- Page Content Holder -->
    <div id="content">
        @yield('content')
    </div>
</div>




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


<script type="text/javascript">
     $(document).ready(function () {
         $('#sidebarCollapse').on('click', function () {
             $('#sidebar').toggleClass('active');
         });
     });
</script>

@yield('header-bottom')
</body>
</html>