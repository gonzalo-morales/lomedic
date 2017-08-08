@extends('layouts.dashboard')

@section('title', 'Ver')

@section('header-top')
@endsection

@section('header-bottom')
	<script src="{{ asset('js/bancos.js') }}"></script>
@endsection

@section('content')
<div class="col s12 xl8 offset-xl2">
	<div class="row">
		<div class="col s6">
			<p class="left-align">
				<a href="{{ companyRoute("index",['company'=> $company]) }}" class="waves-effect waves-light btn">Regresar</a> <br>
			</p>
		</div>
		<div class="col s6">
			<p class="right-align">
				<a href="{{ companyRoute("edit", ['company'=> $company, 'id' => $data->id_grupo]) }}" class="waves-effect waves-light btn"><i class="material-icons right">mode_edit</i>Editar</a>
			</p>
		</div>
	</div>
</div>

<div class="col s12 xl8 offset-xl2">
	<div class="row">
		<div class="input-field col s12">
			<input type="text" name="descripcion" id="descripcion" class="validate" readonly value="{{ $data->descripcion }}">
			<label for=descripcion">Descripcion</label>
		</div>
	</div><div class="row">
		<div class="input-field col s12">
			<input type="text" name="descripcion_producto" id="descripcion_producto" class="validate" readonly value="{{ $data->descripcion_producto }}">
			<label for="descripcion_producto">Descripcion del producto</label>
		</div>
	</div>
	<div class="row">
		<div class="input-field col s12">
			<input type="text" name="nomenclatura" id="nomenclatura" class="validate" readonly value="{{ $data->nomenclatura }}">
			<label for="nomenclatura">Descripcion del puesto</label>
		</div>
	</div>
	<div class="row">
		<div class="input-field col s12">
			<input type="text" name="tipo" id="tipo" class="validate" readonly value="{{ $data->tipo }}">
			<label for="tipo">Descripcion del puesto</label>
		</div>
	</div>
	<div class="row">
		<div class="col s12">
			<p>
				<input type="checkbox" id="activo" name="activo" disabled checked="{{ $data->activo }}">
				<label for="activo">¿Activo?</label>
			</p>
		</div>
	</div>
</div>
@endsection
