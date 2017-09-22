@extends('layouts.app')

@section('title', 'Iniciar Sesion')

@section('content')
<div class="valign-wrapper" style="height: 100vh;"><br><br><br>
	<div class="container-fluid col-sm-8 col-md-6 col-xl-3">
		<div class="card card-block text-center"> <!-- class="card-panel hoverable row"> -->
        	<h4 class="card-header text-info">¡Bienvenido!</h4>
		
			{!! Form::open(['route' => 'login', 'id' => 'form-login', 'class' => 'card-body center']) !!}
			<div class="container-fluid col-sm-12">
				<div class="form-group col-sm-12">
					<object id="front-page-logo" class="Sim" type="image/svg+xml" data="{{asset('img/sim2.svg')}}" name="SIM">Your browser does not support SVG</object>
				</div>
				
				<div class="form-group col-sm-12">
					<div class="input-group">
						<span class="input-group-addon" id="username">
							<i class="material-icons prefix">account_circle</i>
                        	<span class="oi oi-person" title="Usuario o contrase�a" aria-hidden="true"></span>
                        </span>
						{{ Form::text('usuario', null, ['id'=>'usuario','class'=>'validate col-sm-10','placeholder'=>'* Usuario','autofocus'=>true]) }}
                        {{ $errors->has('usuario') ? HTML::tag('small', $errors->first('usuario'), ['class'=>'form-text text-muted','style'=>'border:1px solid #f00']) : '' }}
					</div>
				</div>
				
				<div class="form-group col-sm-12">
					<div class="input-group">
						<span class="input-group-addon" id="username">
    						<i class="material-icons prefix">vpn_key</i>
                        	<span class="oi oi-person" title="Contrase�a" aria-hidden="true"></span>
                        </span>
						{{ Form::password('password', ['id'=>'password','class'=>'validate col-sm-10','placeholder'=>'* Contraseña']) }}
                        {{ $errors->has('password') ? HTML::tag('span', $errors->first('password'), ['class'=>'form-text text-muted']) : '' }}
					</div>
				</div>
				
				<div class="form-group col-sm-12">
					<div class=" container">
					{{ Form::button('Entrar', ['type' =>'submit', 'class'=>'btn btn-primary']) }}
					</div>
				</div>
				
				<div class="form-group col-sm-12">
					<small><a class='card-link text-secondary' href="{{ route('password.request') }}"><b>¿Olvidaste contraseña?</b></a></small>
				</div>
				
			</div>
			{!! Form::close() !!}
				
		</div><!--/card-panel hoverable row-->
	</div><!--/col s12 center-block-->
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
