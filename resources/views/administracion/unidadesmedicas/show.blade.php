@extends('layouts.dashboard')

@section('title', 'Ver')

@section('header-top')
@endsection

@section('header-bottom')
	<script src="{{ asset('solicitudes') }}"></script>
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
					<a href="{{ companyRoute("edit", ['company'=> $company, 'id' => $data->id_unidad_medica]) }}" class="waves-effect waves-light btn"><i class="material-icons right">mode_edit</i>Editar</a>
				</p>
			</div>
		</div>
		<div class="divider"></div>
	</div>

	<div class="col s12 xl8 offset-xl2">
		<div class="row">
			<div class="input-field col s12">
				<input type="text" name="nombre" id="nombre" class="validate" readonly value="{{ $data->nombre }}">
				<label for="nombre">Descripcion del puesto</label>
			</div>
		</div>
		<div class="row">
			<div class="col s12">
				<p>
					<input type="checkbox" id="activo" name="activo" disabled checked="{{ $data->activo }}">
					<label for="nacional">¿Activo?</label>
				</p>
			</div>
		</div>
	</div>
@endsection
