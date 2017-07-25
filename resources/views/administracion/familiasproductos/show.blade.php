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
	<h5>Datos de la Familia</h5>
	<div class="row">
		<div class="input-field col s12 m5">
			<input type="text" name="descripcion" id="descripcion" class="validate"  readonly value="{{ $data->descripcion }}">
			<label for="Familia">Familia:</label>
			@if ($errors->has('descripcion'))
				<span class="help-block">
					<strong>{{ $errors->first('descripcion') }}</strong>
				</span>
			@endif
		</div>
		<div class="input-field col s12 m4">
			<input type="text" name="nomenclatura" id="nomenclatura" class="validate"  readonly value="{{ $data->nomenclatura }}">
			<label for="Nomenclatura">Nomenclatura:</label>
			@if ($errors->has('nomenclatura'))
				<span class="help-block">
					<strong>{{ $errors->first('nomenclatura') }}</strong>
				</span>
			@endif
		</div>
		<div class="input-field col s12 m3">
			<input type="checkbox" id="estatus" name="estatus" readonly {{$data->estatus ? 'checked' : ''}} />
			<label for="Estatus">Estatus:</label>
			@if ($errors->has('estatus'))
				<span class="help-block">
					<strong>{{ $errors->first('estatus') }}</strong>
				</span>
			@endif
		</div>
	</div>
</div>
@endsection
