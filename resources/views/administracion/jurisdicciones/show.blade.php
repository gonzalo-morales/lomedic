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
	<h5>Datos de la jurisdicción</h5>
	<div class="row">
		<div class="input-field col s4">
			<input type="text" name="jurisdiccion" id="jurisdiccion" class="" value="{{$data->jurisdiccion}}" readonly>
			<label for="jurisdiccion">Jurisdicción</label>
		</div>
		<div class="input-field col s4">
			<input type="text" name="fk_id_estado" id="fk_id_estado" class="" value="{{$state}}" readonly>
			<label for="fk_id_estado">Estado</label>
		</div>
		<div class="input-field col s4">
			<input type="hidden" name="activo" value="0">
			<input type="checkbox" id="activo" name="activo" {{$data->activo ? 'checked' : ''}} disabled/>
			<label for="activo">Estatus:</label>
		</div>
	</div>
</div>
@endsection
