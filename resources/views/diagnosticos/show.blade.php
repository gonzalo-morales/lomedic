@extends('layouts.dashboard')

@section('title', 'Ver')

@section('header-top')
@endsection

@section('header-bottom')
	<script src="{{ asset('js/modulos.js') }}"></script>
@endsection

@section('content')
<div class="col s12 xl8 offset-xl2">
	<div class="row">
		<div class="col s12">
			<p class="right-align">
				<a href="{{ route("$entity.edit", ['company'=> $company, 'id' => $data->id_diagnostico]) }}" class="waves-effect waves-light btn orange"><i class="material-icons right">mode_edit</i>Editar</a>
				<a href="{{ route("$entity.index",['company'=> $company]) }}" class="waves-effect waves-light btn btn-flat teal-text">Regresar</a>
			</p>
		</div>
	</div>
</div>
<div class="col s12 xl8 offset-xl2">
	<h5>Datos del Diagnostico</h5>
	<div class="row">
		<div class="input-field col s12 m5">
			<input type="text" name="clave_diagnostico" id="clave_diagnostico" class="validate"  readonly value="{{ $data->clave_diagnostico }}">
			<label for="Clave">Clave:</label>
			@if ($errors->has('clave_diagnostico'))
				<span class="help-block">
					<strong>{{ $errors->first('clave_diagnostico') }}</strong>
				</span>
			@endif
		</div>
		<div class="input-field col s12 m7">
			<input type="text" name="diagnostico" id="diagnostico" class="validate"  readonly value="{{ $data->diagnostico }}">
			<label for="Diagnostico">Diagnostico:</label>
			@if ($errors->has('diagnostico'))
				<span class="help-block">
					<strong>{{ $errors->first('diagnostico') }}</strong>
				</span>
			@endif
		</div>
	</div>
	<div class="row">
		<div class="input-field col s12 m4">
			<input type="text" name="medicamento_sugerido" id="medicamento_sugerido" class="validate"  rea	donly value="{{ $data->medicamento_sugerido }}">
			<label for="Medicamento Sugerido">Medicamento Sugerido:</label>
			@if ($errors->has('medicamento_sugerido'))
				<span class="help-block">
					<strong>{{ $errors->first('medicamento_sugerido') }}</strong>
				</span>
			@endif
		</div>
		<div class="input-field col s12 l6 xl3">
			<p>
				<input type="checkbox" id="estatus" name="estatus" readonly {{$data->estatus ? 'checked' : ''}} />
				<label for="Estatus">Estatus</label>
				@if ($errors->has('estatus'))
					<span class="help-block">
						<strong>{{ $errors->first('estatus') }}</strong>
					</span>
				@endif
			</p>
		</div>
	</div>
</div>
@endsection
