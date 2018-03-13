@extends('layouts.app')

@section('title', cTrans('titles.login','Iniciar Sessión'))

@section('content')
<div class="container">
	<div class="row justify-content-center">
	<div class="col-sm-10 col-md-8 col-lg-5 mt-5">
		<div class="text-right text-info mr-2">
    		@foreach(config('app.locales') as $s=>$lang)
    			<small>{{ HTML::link(url('/lang/'.$s), $s, ['class' => 'text-secondary', 'title' => $lang]) }}</small>
    		@endforeach
    	</div>
		<div class="card card-block text-center" style="background: rgba(0,0,0,0.3);">
        	<h4 class="mt-3 text-white">{{ cTrans('messages.welcome','¡Bienvenido!') }}</h4>
			{!! Form::open(['route' => 'login', 'id' => 'form-login', 'class' => 'card-body center']) !!}
    			<div class="container-fluid">
    				<div class="form-group">
    					<object id="front-page-logo" class="Sim" type="image/svg+xml" data="{{asset('img/sim2.svg')}}" name="SIM">{{ cTrans('messages.svg','Tu navegador no tiene soporte para SVG') }}</object>
    				</div>
    				<div class="form-group">
    					<div class="input-group">
    						<span class="input-group-addon" id="username">
    							<i class="material-icons prefix">account_circle</i>
                            </span>
    						{{ Form::text('usuario', $usuario ?? '', ['id'=>'usuario','class'=>'validate form-control','placeholder'=>'* '.cTrans('forms.user','Usuario'),'autofocus'=>true]) }}
    					</div>
                            {{ $errors->has('usuario') ? HTML::tag('span', $errors->first('usuario'), ['class'=>'form-text text-white bg-danger p-1 m-0 rounded-bottom']) : '' }}
    				</div>
    				<div class="form-group">
    					<div class="input-group">
    						<span class="input-group-addon" id="username">
        						<i class="material-icons prefix">vpn_key</i>
                            </span>
    						{{ Form::password('password', ['id'=>'password','class'=>'validate form-control','placeholder'=>'* '.cTrans('forms.password','Contraseña')]) }}
    					</div>
                            {{ $errors->has('password') ? HTML::tag('span', $errors->first('password'), ['class'=>'form-text text-white bg-danger p-1 m-0 rounded-bottom']) : '' }}
    				</div>
        			@if (count($errors) == 1)
                            @foreach ($errors->all() as $error)
                                <div class="alert alert-danger" role="alert" style="background: rgba(238,205,208,0.9);">{!! $error !!}</div>
                            @endforeach
                    @endif 
    				<div class="form-group">
    					<div class=" container">
    					{{ Form::button(cTrans('forms.login','Entrar'), ['type' =>'submit', 'class'=>'btn btn-primary progress-button']) }}
    					</div>
    				</div>
    				<div class="form-group">
    					<small><a class='card-link text-white' href="{{ route('password.request') }}"><b>{{ cTrans('forms.fgpassword','¿Olvidaste contraseña?') }}</b></a></small>
    				</div>
    			</div>
			{!! Form::close() !!}
		</div><!--/card-->
	</div>
	</div><!--/row-->
</div><!--/valign-wrapper aquÃ­ termina el login-->
@endsection