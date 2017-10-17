@extends('layouts.app')

@section('title', 'Iniciar Sesion')

@section('content')

<div class="container">
	<div class="row justify-content-center">
	<div class="col-md-5 mt-5">
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
					{{ Form::button('Entrar', ['type' =>'submit', 'class'=>'btn btn-primary']) }}
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

<!-- Modal para contraseña -->
<div id="forgotPass" class="modal">
	<div class="modal-content">
		<h4>Ingresa tu correo:</h4>
		<p>Te enviaremos al correo las instrucciones</p>
			<div class="row">
				<div class="input-group col s12">
					<i class="material-icons prefix">mail</i>
					<input id="email" type="email" class="validate">
					<label for="email">Correo:</label>
				</div>
			</div>
	</div>
	<div class="modal-footer">
		<button class="modal-action modal-close waves-effect waves-teal btn-flat teal-text">Cancelar</button>
		<button class="modal-action waves-effect waves-light btn blue darken-4" type="submit" name="send">Enviar</button>
	</div>
</div>
@endsection
