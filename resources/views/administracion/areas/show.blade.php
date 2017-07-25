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
				<a href="{{ companyRoute('edit') }}" class="waves-effect waves-light btn orange"><i class="material-icons right">mode_edit</i>Editar</a>
				<a href="{{ companyRoute('index') }}" class="waves-effect waves-light btn btn-flat teal-text">Regresar</a>
			</p>
		</div>
	</div>
</div>
<div class="col s12 xl8 offset-xl2">
	<h5>Datos del Area</h5>
	<div class="row">
		<div class="input-field col s12 m5">
			<input type="text" name="area" id="area" class="validate"  readonly value="{{ $data->area }}">
			<label for="Area">Area:</label>
			@if ($errors->has('area'))
				<span class="help-block">
					<strong>{{ $errors->first('area') }}</strong>
				</span>
			@endif
		</div>
		<div class="input-field col s12 m7">
			<input type="text" name="clave_area" id="clave_area" class="validate"  readonly value="{{ $data->clave_area }}">
			<label for="Clave Area">Clave Area:</label>
			@if ($errors->has('clave_area'))
				<span class="help-block">
					<strong>{{ $errors->first('clave_area') }}</strong>
				</span>
			@endif
		</div>
	</div>
</div>
@endsection
