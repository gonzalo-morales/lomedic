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
	<h5>Datos del Tipo Combustible</h5>
	<div class="row">
		<div class="input-field col s12 m5">
			<input type="text" name="combustible" id="combustible" class="validate"  readonly value="{{ $data->combustible }}">
			<label for="Combustible">Combustible:</label>
			@if ($errors->has('combustible'))
				<span class="help-block">
					<strong>{{ $errors->first('combustible') }}</strong>
				</span>
			@endif
		</div>
		<div class="input-field col s12 m7">
			<input type="checkbox" id="activo" name="activo" readonly {{$data->activo ? 'checked' : ''}} />
			<label for="activo">Estatus:</label>
			@if ($errors->has('activo'))
				<span class="help-block">
					<strong>{{ $errors->first('activo') }}</strong>
				</span>
			@endif
		</div>
	</div>
</div>
@endsection
