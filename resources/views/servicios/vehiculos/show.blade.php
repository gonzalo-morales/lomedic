@extends('layouts.dashboard')

@section('title', 'Ver')

@section('header-top')
@endsection

@section('header-bottom')
@endsection

@section('content')
<div class="col s12 xl8 offset-xl2">
	<div class="row">
		<div class="col s6">
			<p class="left-align">
				<a href="{{ companyRoute('index',['company'=> $company]) }}" class="waves-effect waves-light btn">Regresar</a> <br>
			</p>
		</div>
		<div class="col s6">
			<p class="right-align">
				<a href="{{ companyRoute('edit', ['company'=> $company, 'id' => $data->id_vehiculo]) }}" class="waves-effect waves-light btn"><i class="material-icons right">mode_edit</i>Editar</a>
			</p>
		</div>
	</div>
	<div class="divider"></div>
</div>


<div class="col s12 xl8 offset-xl2">
	<div class="row">
		<div class="input-field col s2">
			<input type="text" name="marca" id="marca" class="validate" readonly value="{{ $data->marca['marca'] }}">
			<label for="marca">Marca</label>
		</div>
		<div class="input-field col s2">
			<input type="text" name="modelo" id="modelo" class="validate" readonly value="{{ $data->modelo }}">
			<label for="modelo">Tipo</label>
		</div>
	</div>
	<div class="row">
		<div class="input-field col s2">
			<input type="text" name="numeroSerie" id="numeroSerie" class="validate" readonly value="{{ $data->numero_serie }}">
			<label for="numeroSerie">Número de Serie</label>
		</div>
		<div class="input-field col s1">
			<input type="text" name="modelo" id="modelo" class="validate" readonly value="{{ $data->modelos['modelo'] }}">
			<label for="modelo">Modelo</label>
		</div>
		<div class="input-field col s1">
			<input type="text" name="placa" id="placa" class="validate" readonly value="{{ $data->placa }}">
			<label for="placa">Placa</label>
		</div>
		<div class="input-field col s1">
			<input type="text" name="capacidad" id="capacidad" class="validate" readonly value="{{ $data->capacidad_tanque }}">
			<label for="capacidad">Capacidad</label>
		</div>
		<div class="input-field col s1">
			<input type="text" name="rendimiento" id="rendimiento" class="validate" readonly value="{{ $data->rendimiento }}">
			<label for="rendimiento">Rendimiento</label>
		</div>
		<div class="input-field col s2">
			<input type="text" name="lineasPorTanque" id="lineasPorTanque" class="validate" readonly value="{{ $data->lineas_tanque }}">
			<label for="lineasPorTanque">Líneas por Tanque</label>
		</div>
		<div class="input-field col s2">
			<input type="text" name="litrosPorLinea" id="litrosPorLinea" class="validate" readonly value="{{ $data->litros_linea }}">
			<label for="litrosPorLinea">Litros por Línea</label>
		</div>
		<div class="input-field col s2">
			<input type="text" name="numeroTarjeta" id="numeroTarjeta" class="validate" readonly value="{{ $data->no_tarjeta }}">
			<label for="numeroTarjeta">Número de Tarjeta</label>
		</div>
	</div>

	<div class="row">
		<div class="input-field col s2">
			<input type="text" name="combustible" id="combustible" class="validate" readonly value="{{ $data->combustibles['combustible'] }}">
			<label for="combustible">Combustible</label>
		</div>
		<div class="input-field col s2">
			<input type="text" name="folioChecklist" id="folioChecklist" class="validate" readonly value="{{ $data->folio }}">
			<label for="folioChecklist">Folio Checklist</label>
		</div>
		<div class="input-field col s2">
			<input type="text" name="sucursal" id="sucursal" class="validate" readonly value="{{ $data->sucursales['nombre_sucursal'] }}">
			<label for="sucursal">Sucursal</label>
		</div>
		<div class="input-field col s2">
			<input type="text" name="iave" id="iave" class="validate" readonly value="{{ $data->iave}}">
			<label for="iave">IAVE</label>
		</div>
		<div class="input-field col s4">
			@if ($data->activo == 1)
			<p>
				<input type="checkbox" id="activo" name="activo" disabled checked="1">
				<label for="activo">¿Activo?</label>
			</p>
			@else
			<p>
				<input type="checkbox" id="activo" name="activo" disabled>
				<label for="activo">¿Activo?</label>
			</p>
			@endif
		</div>
	</div>
</div>
@endsection
