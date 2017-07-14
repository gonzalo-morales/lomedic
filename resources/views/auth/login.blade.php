@extends('layouts.app')

@section('content')
<div class="valign-wrapper">
	<div class="col s12 center-block section center-align">
	<h4>¡Bienvenido!</h4>
		<div class="card-panel hoverable row">
			<form class="section" method="POST" action="{{ route('login') }}">
				{{ csrf_field() }}
				<object id="front-page-logo" class="Sim" type="image/svg+xml" data="img/sim2.svg" name="SIM">Your browser does not support SVG</object>
				<div class="row">
					<div class="input-field col s12">
						<i class="material-icons prefix">account_circle</i>
						<input id="user" type="text" class="validate" name="email" value="{{ old('email') }}" autofocus>
						<label for="user">Correo:</label>
						@if ($errors->has('email'))
							<span class="help-block">
								<strong>{{ $errors->first('email') }}</strong>
							</span>
						@endif
					</div>
				</div>
				<div class="row">
					<div class="input-field col s12">
						<i class="material-icons prefix">vpn_key</i>
						<input id="password" type="password" class="validate" name="password">
						<label for="password">Contraseña:</label>
						@if ($errors->has('password'))
							<span class="help-block">
								<strong>{{ $errors->first('password') }}</strong>
							</span>
						@endif
						<a class='teal-text right' href="{{ route('password.request') }}"><b>¿Olvidaste contraseña?</b></a>
					</div>
				</div>
				<div class="row">
						<div class="input-field col s12">
							<select class="icons">
								<option value="" disabled selected>Selecciona:</option>
								<option value="" data-icon="img/tomatoe.jpg" class="left circle">example 1</option>
								<option value="" data-icon="img/tomatoe.jpg" class="left circle">example 2</option>
								<option value="" data-icon="img/tomatoe.jpg" class="left circle">example 3</option>
							</select>
							<label>Sistema:</label>
						</div>
					<div class="col s12">
						<button class="btn orange waves-effect waves-light" type="submit" name="enter">Entrar</button>
					</div>
				</div>
			</form><!--/section-->
		</div><!--/card-panel hoverable row-->
	</div><!--/col s12 center-block-->
</div><!--/valign-wrapper aquí termina el login-->

<!-- Modal para contraseña -->
<div id="forgotPass" class="modal">
	<div class="modal-content">
		<h4>Ingresa tu correo:</h4>
		<p>Te enviaremos al correo las instrucciones</p>
			<div class="row">
				<div class="input-field col s12">
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


