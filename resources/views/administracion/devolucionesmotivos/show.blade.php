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
				<a href="{{ companyRoute("edit", ['company'=> $company, 'id' => $data->id_devolucion_motivo]) }}" class="waves-effect waves-light btn"><i class="material-icons right">mode_edit</i>Editar</a>
			</p>
		</div>
	</div>
	<div class="divider"></div>
</div>

<div class="col s12 xl8 offset-xl2">
	<div class="row">
		<div class="input-field col s4">
			<input type="text" name="sustancia_activa" id="sustancia_activa" class="" value="{{$data->devolucion_motivo}}" readonly>
			<label for="sustancia_activa">Sustancia Activa</label>
		</div>
		<div class="input-field col s4">
			<input class="field" id="localidad" name="solicitante_devolucion" type="radio" value="false" {{!$data->solicitante_devolucion ? 'checked' : ''}} disabled>{{-- localidad --}}
			<label for="localidad">Localidad</label>
			<br>
			<input class="field" id="proveedor" name="solicitante_devolucion" type="radio" value="true"{{$data->solicitante_devolucion ? 'checked' : ''}} disabled>{{-- proveedor --}}
			<label for="proveedor">Proveedor</label>
		</div>
		<div class="input-field col s4">
			<input type="hidden" name="activo" value="0">
			<input type="checkbox" name="activo" id="activo" clase="validate" {{$data->activo ? 'checked':''}} disabled>
			<label for="activo">¿Activo?</label>
		</div>
	</div>
</div>
@endsection
