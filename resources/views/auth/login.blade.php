@extends('layouts.app')

@section('title', 'Iniciar Sesion')

@section('content')
<div class="container">
	<div class="row justify-content-center">
	<div class="col-sm-10 col-md-8 col-lg-5 mt-5">
		<div class="card card-block text-center" style="background: rgba(0,0,0,0.3);"> <!-- class="card-panel hoverable row"> -->
        	<h4 class="mt-3 text-white">¡Bienvenido!</h4>
			{!! Form::open(['route' => 'login', 'id' => 'form-login', 'class' => 'card-body center']) !!}
    			<div class="container-fluid">
    				<div class="form-group">
    					<object id="front-page-logo" class="Sim" type="image/svg+xml" data="{{asset('img/sim2.svg')}}" name="SIM">Your browser does not support SVG</object>
    				</div>
    				<div class="form-group">
    					<div class="input-group">
    						<span class="input-group-addon" id="username">
    							<i class="material-icons prefix">account_circle</i>
                            </span>
    						{{ Form::text('usuario', null, ['id'=>'usuario','class'=>'validate form-control','placeholder'=>'* Usuario','autofocus'=>true]) }}
    					</div>
                            {{ $errors->has('usuario') ? HTML::tag('span', $errors->first('usuario'), ['class'=>'form-text text-white bg-danger p-1 m-0 rounded-bottom']) : '' }}
    				</div>
    				<div class="form-group">
    					<div class="input-group">
    						<span class="input-group-addon" id="username">
        						<i class="material-icons prefix">vpn_key</i>
                            </span>
    						{{ Form::password('password', ['id'=>'password','class'=>'validate form-control','placeholder'=>'* Contraseña']) }}
    					</div>
                            {{ $errors->has('password') ? HTML::tag('span', $errors->first('password'), ['class'=>'form-text text-white bg-danger p-1 m-0 rounded-bottom']) : '' }}
    				</div>
    				<div class="form-group">
    					<div class=" container">
    					{{ Form::button('Entrar', ['type' =>'submit', 'class'=>'btn btn-primary progress-button']) }}
    					</div>
    				</div>
    				<div class="form-group">
    					<small><a class='card-link text-white' href="{{ route('password.request') }}"><b>¿Olvidaste contraseña?</b></a></small>
    				</div>
    			</div>
			{!! Form::close() !!}
		</div><!--/card-->
	</div>
	</div><!--/row-->
</div><!--/valign-wrapper aquí termina el login-->
@endsection