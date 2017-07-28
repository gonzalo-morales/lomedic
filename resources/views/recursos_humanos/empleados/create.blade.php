@extends('layouts.dashboard')

@section('title', 'Crear')

@section('header-top')
@endsection

@section('header-bottom')
	<script src="{{ asset('js/bancos.js') }}"></script>
    <script src="{{ asset('js/InitiateComponents.js') }}"></script>
@endsection

@section('content')
<div class="col s12 xl8 offset-xl2">
	<p class="left-align">
		<a href="{{ url()->previous() }}" class="waves-effect waves-light btn">Regresar</a> <br>
	</p>
	<div class="divider"></div>
</div>
<div class="col s12 xl8 offset-xl2">
	<h4>Capturar nuevo {{ trans_choice('messages.'.$entity, 0) }}</h4>
</div>

<div class="col s12 xl8 offset-xl2">
	<div class="row">
		<form action="{{ companyRoute("index", ['company'=> $company]) }}" method="post" class="col s12">
			{{ csrf_field() }}
			{{ method_field('POST') }}
			<div class="row">
				<div class="input-field col s4">
					<input type="text" name="nombre" id="nombre" class="validate">
					<label for="nombre">Nombre(s)</label>
					@if ($errors->has('nombre'))
						<span class="help-block">
							<strong>{{ $errors->first('nombre') }}</strong>
						</span>
					@endif
				</div>
				<div class="input-field col s4">
					<input type="text" name="apellido_paterno" id="apellido_paterno" class="validate">
					<label for="apellido_paterno">Apellido paterno</label>
					@if ($errors->has('apellido_paterno'))
						<span class="help-block">
							<strong>{{ $errors->first('apellido_paterno') }}</strong>
						</span>
					@endif
				</div>
				<div class="input-field col s4">
					<input type="text" name="apellido_materno" id="apellido_materno" class="validate">
					<label for="apellido_materno">Apellido materno</label>
					@if ($errors->has('razon_social'))
						<span class="help-block">
							<strong>{{ $errors->first('razon_social') }}</strong>
						</span>
					@endif
				</div>
			</div>
			<div class="row">
				<div class="date_picker col s2">
					<label for="fecha_nacimiento">Fecha de nacimiento</label>
					<input type="date" name="fecha_nacimiento" id="fecha_nacimiento" class="datepicker">
					@if ($errors->has('fecha_nacimiento'))
						<span class="help-block">
							<strong>{{ $errors->first('fecha_nacimiento') }}</strong>
						</span>
					@endif
				</div>
				<div class="input-field col s5">
					<input type="text" name="curp" id="curp" class="validate">
					<label for="curp">CURP</label>
					@if ($errors->has('curp'))
						<span class="help-block">
							<strong>{{ $errors->first('curp') }}</strong>
						</span>
					@endif
				</div>
				<div class="input-field col s5">
					<input type="text" name="rfc" id="rfc" class="validate">
					<label for="rfc">RFC</label>
					@if ($errors->has('rfc'))
						<span class="help-block">
							<strong>{{ $errors->first('rfc') }}</strong>
						</span>
					@endif
				</div>
			</div>
			<div class="row">
                <div class="input-field col s4">
                    <input type="text" name="correo_personal" id="correo_personal" class="validate">
                    <label for="correo_personal">Correo</label>
                    @if ($errors->has('correo_personal'))
                        <span class="help-block">
							<strong>{{ $errors->first('correo_personal') }}</strong>
						</span>
                    @endif
                </div>
			</div>
			<div class="row">
				<div class="col s12">
					<button class="waves-effect waves-light btn right">Guardar {{ trans_choice('messages.'.$entity, 0) }}</button>
				</div>
			</div>
		</form>
	</div>
</div>
@endsection
