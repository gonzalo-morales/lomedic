@extends('layouts.dashboard')

@section('title', 'Ver')

@section('header-top')
@endsection

@section('header-bottom')

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
	<h5>Datos de Prioridad</h5>
	<div class="row">
		<div class="input-field col s12 m5">
			<input type="text" name="prioridad" id="prioridad" class="validate"  readonly value="{{ $data->prioridad}}">
			<label for="prioridad">Prioridad</label>
			@if ($errors->has('prioridad'))
				<span class="help-block">
					<strong>{{ $errors->first('prioridad') }}</strong>
				</span>
			@endif
		</div>
		<div class="input-field col s12 m7">
			<input type="checkbox" id="activo" name="activo" disabled {{$data->activo ? 'checked' : ''}} />
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
