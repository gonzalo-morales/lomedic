<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	<meta charset="utf-8">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="robots" content="noindex,nofollow" />
	<base href="{{url('/')}}" />
	<title>{{ config('app.name', '') }} - @yield('title')</title>
	{{ HTML::meta('viewport', 'width=device-width, initial-scale=1') }}
	{{ HTML::meta('csrf-token', csrf_token()) }}
	{{ HTML::favicon(asset("img/logotipos/$menuempresa->icono")) }}
	<!-- Bootstrap CSS local fallback -->
	{{ HTML::style(asset('css/bootstrap/dist/css/bootstrap.min.css')) }}
	<!-- Select2 CSS local -->
	{{ HTML::style(asset('css/select2.min.css')) }}
	{{ HTML::style(asset('css/select2-bootstrap.min.css')) }}
	{{ HTML::style(asset('css/pickadate/default.css')) }}
	{{ HTML::style(asset('css/pickadate/default.date.css')) }}
	{{ HTML::style(asset('vendor/btn-load/style.css')) }}

    {{ HTML::style(asset('css/style.css'), ['media'=>'screen,projection']) }}
    {{ HTML::style(asset('css/style-nav.css'), ['media'=>'screen,projection']) }}

    @if(!isset(request()->kendoWindow))
        {{ HTML::style(asset('css/kendo/web/kendo.common-material.min.css')) }}
        {{ HTML::style(asset('css/kendo/web/kendo.material.min.css')) }}
        {{ HTML::style(asset('css/kendo/web/kendo.material.mobile.min.css')) }}
    @endif
	@yield('header-top')
    
    <link rel="dns-prefetch" href="//ajax.googleapis.com">
    <link rel="dns-prefetch" href="//maxcdn.bootstrapcdn.com">
    <link rel="dns-prefetch" href="//cdnjs.cloudflare.com">
</head>
<body>
@if(!isset(request()->kendoWindow))
    <div class="w-100 fixed-top z-depth-1-half" id="top-nav">
    	<nav class="navbar navbar-default bg-white">
            <div class="navbar-header d-flex flex-row">
                <button type="button" id="sidebarCollapse" class="btn-warning navbar-btn d-flex align-items-center" title="Menu" onclick="menu()"><i class="material-icons">menu</i></button>
                <div class="btn-group">
                    <a href="#!" class="navbar-btn nav-link dropdown-toggle d-flex align-items-center dropdown-toggle" title="{{cTrans('messages.company','Empresa')}}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            			{{ HTML::image(asset("img/logotipos/$menuempresa->icono"), null, ['width'=>'25px','class'=>'mr-2']) }} {{ $menuempresa->nombre_comercial }}
            		</a>
                    <ul id='enteDrop' class="dropdown-menu z-depth-2" aria-labelledby="dropdownMenu2">
                		@foreach($menuempresas as $_empresa)
                		<li class="dropdown-item">
                			<a target="_blank" href="{{ companyAction('HomeController@index',['company' => $_empresa->conexion]) }}">
                				{{ HTML::image(asset("img/logotipos/$_empresa->icono"), null, ['class'=>'circle responsive-img mr-1','width'=>'24px']) }} {{ $_empresa->nombre_comercial }}
                			</a>
                		</li>
                		@endforeach
                	</ul>
                </div>
            </div>
            <a class="d-flex align-items-center" href="{{asset(request()->company)}}" title="{{cTrans('messages.home','Inicio')}}"><i class='material-icons left'>home</i></a>
            <button type="button" id="rigth-sidebarCollapse" class="btn-warning navbar-btn d-flex align-items-center" title="{{cTrans('messages.help','Ayuda')}}"><i class="material-icons">live_help</i></button>
        </nav>
    	
    </div>
@endif
    @if(isset(request()->kendoWindow))
    <div class="wrapper">
    @else
    <div class="wrapper" style="margin-top: 44.5px;">
        <!-- Sidebar Holder -->
        <nav id="sidebar" class="bg-dark text-white {{ cookie::get('menu_active','active') }}">
        	<div id="sidebar-content">
                <div class="sidebar-header text-center" style="position: relative;">
                    <div class="title">
                    	<div class="text-center"><object id="front-page-logo" class="sim" type="image/svg+xml" data="{{asset('img/sim2.svg')}}" name="SIM">Su navegador no soporta imagenes SVG</object></div>
                        <a href="#" class="mt-3"><p class="mt-3 d-flex justify-content-center w-100"><small>{{ Auth::User()->nombre_corto }}</small></p></a>
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="white-text w-100 text-uppercase">
            				<i class="tiny material-icons">power_settings_new</i> {{cTrans('forms.logout','Cerrar Sesión')}}
            			</a>
                    </div>
                    <strong>
                    	<a href="#" title="{{ Auth::User()->nombre_corto }}" data-toggle="tooltip" data-placement="right">
                    		<object id="front-page-logo" class="sim w-50" type="image/svg+xml" data="{{asset('img/sim2.svg')}}" name="SIM">Su navegador no soporta imagenes  SVG</object>
                    	</a>
        				<a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="d-flex justify-content-center" title="{{cTrans('forms.logout','Cerrar Sesión')}}" data-toggle="tooltip" data-placement="right">
            				<i class="tiny material-icons">power_settings_new</i>
            			</a>
        			</strong>

        			{!! Form::open(['route' => 'logout', 'before' => 'csrf', 'id' => 'logout-form', 'class' => 'hidden']) !!} {!! Form::close() !!}
                </div>

                <ul id="menu-conten" class="list-unstyled components text-center">
                	{!! Form::cText(null,'filter-menu',['placeholder'=>cTrans('forms.menu_search','Buscar en menu'),'class'=>'mt-2 p-1']) !!}
                    @if(isset($menu))
        				@each('partials.menu', $menu, 'modulo')
        			@endif
                </ul>
            </div>
        </nav>
    @endif
        <!-- Page Content Holder -->
        <div id="content" class="pt-3 bg-light">
            <div id="onload"></div>
            <div class="pl-2">
            	<!-- <ol class="col-sm-12 breadcrumb bg-light p-1 m-0">
            		<li class="breadcrumb-item" id="bread-home">{{ HTML::link(companyAction('HomeController@index', ['company' => $menuempresa->conexion]), 'inicio') }}</li>
            		foreach(routeNameReplace() as $key=>$item)
            			if($item !== 'index' && !empty($item))
            				<li class="breadcrumb-item active">{{-- HTML::link($key == 1 ? companyRoute('index') : '#', $item) --}}</li>
            			endif
            		endforeach
            	</ol> -->
                <h4 class="col-sm-12 display-4">@yield('form-title')</h4>
        	</div>
            @yield('content')
        </div>
    </div>

@include('layouts.ticket')

<!-- jQuery CDN -->
{{ HTML::script('https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js') }}

<!-- jQuery local fallback -->
<script>window.jQuery || document.write('<script src="{{asset('js/jquery.min.js') }}"><\/script>')</script>

{{ HTML::script('https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.6/umd/popper.min.js') }}
{{ HTML::script(asset('js/popper.min.js')) }}

<!-- Bootstrap JS CDN -->
{{ HTML::script('https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js') }}

<!-- Bootstrap JS local fallback -->
<script>if(typeof($.fn.modal) === 'undefined') {document.write('<script src="{{asset('js/bootstrap/bootstrap.min.js') }}"><\/script>')}</script>

<!-- Kendo UI -->
{{ HTML::script(asset('js/kendo/kendo.all.min.js')) }}

<!-- jQuery js Validation local-->
{{ HTML::script('vendor/jsvalidation/js/jsvalidation.min.js') }}

<!-- jQuery Nicescroll local-->
{{ HTML::script('js/jquery.nicescroll.min.js') }}

{{ HTML::script(asset('js/toaster.js')) }}
{{ HTML::script(asset('js/select2.full.min.js')) }}
{{ HTML::script(asset('js/app.js')) }}
{{ HTML::script(asset('vendor/btn-load/script.js')) }}

@if(!isset(request()->kendoWindow))
    {{ HTML::script(asset('js/ticket.js')) }}
@endif

@yield('header-bottom')

</body>
</html>