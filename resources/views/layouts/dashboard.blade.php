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
<div class="w-100 fixed-top">
	<nav class="navbar navbar-default bg-light">
        <div class="navbar-header">
            <button type="button" id="sidebarCollapse" class="btn-primary navbar-btn d-flex align-items-center"><i class="material-icons">menu</i></button>
        </div>
        <button type="button" id="rigth-sidebarCollapse" class="btn-primary navbar-btn d-flex align-items-center"><i class="material-icons">live_help</i></button>
    </nav>
	<ol class="breadcrumb bg-light rounded-0 z-depth-1-half">
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
    <nav id="sidebar" class="active bg-primary text-white">
    	<div id="sidebar-content">
            <div class="sidebar-header text-center" style="position: relative;">
                <div class="title">
                	<div class="text-center"><object id="front-page-logo" class="sim" type="image/svg+xml" data="{{asset('img/sim2.svg')}}" name="SIM">Your browser does not support SVG</object></div>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="white-text w-100">
        				<i class="tiny material-icons">power_settings_new</i> CERRAR SESION
        			</a>
                    <a href="#"><p class="d-flex justify-content-center"><small>{{ Auth::User()->nombre_corto }}</small></p></a>
                
                <strong>
                	<center><object id="front-page-logo" class="Sim w-100" type="image/svg+xml" data="{{asset('img/sim2.svg')}}" name="SIM">Your browser does not support SVG</object></center>
    				<a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="d-flex justify-content-center">
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



@include('layouts.ticket')

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

<!-- jQuery Nicescroll CDN -->
{{ HTML::script('https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.6.8-fix/jquery.nicescroll.min.js') }}

<script type="text/javascript">
     $(document).ready(function () {
    	 $("#sidebar").niceScroll({
             cursorcolor: '#26a69a',
             cursorwidth: 5,
             cursorborder: 'none'
         });

         $('#sidebarCollapse').on('click', function () {
             $('#sidebar').toggleClass('active');
         });

         
         $("#rigth-sidebar").niceScroll({
             cursorcolor: '#26a69a',
             cursorwidth: 4,
             cursorborder: 'none'
         });

         $('.dismiss, .overlay').on('click', function () {
            $('#rigth-sidebar').removeClass('active');
            $('.overlay').fadeOut();
         });

         $('#rigth-sidebarCollapse').on('click', function () {
             $('#rigth-sidebar').addClass('active');
             $('.overlay').fadeIn();
             $('.collapse.in').toggleClass('in');
             $('a[aria-expanded=true]').attr('aria-expanded', 'false');
         });
     });
</script>

@yield('header-bottom')
</body>
</html>