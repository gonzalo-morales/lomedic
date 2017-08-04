@extends('layouts.dashboard')

@section('title', 'Vehículos')

@section('header-top')
	<!--dataTable.css-->
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.3.1/css/buttons.dataTables.min.css">
@endsection

@section('header-bottom')
	<script src="//cdn.datatables.net/1.10.15/js/jquery.dataTables.js"></script>
	<script src="{{ asset('js/dataTableGeneralConfig.js') }}"></script>
@endsection

@section('content')
<div class="col s12 xl8 offset-xl2">
	<p class="right">
		<a href="{{ companyRoute('create') }}" class="waves-effect waves-light btn orange">Nuevo</a>
		<a href="{{ companyRoute('index') }}" class="waves-effect waves-light btn"><i class="material-icons">cached</i></a>
	</p>
</div>
@if (session('success'))
<div class="col s12 xl8 offset-xl2">
	<div class="alert alert-success">
		{{ session('success') }}
	</div>
</div>
@endif
<div class="col s12 xl8 offset-xl2">
	<table class="striped responsive-table highlight">
		<thead>
			<tr>
				<th>Marca</th>
				<th>Modelo</th>
				<th>Tipo</th>
				<th>Placa</th>
				<th>Num de Serie</th>
				<th>Activo</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			@foreach ($data as $vehiculos)
				<tr>
					<td>{{ $vehiculos->marca['marca'] }}</td>
					<td>{{ $vehiculos->modelo }}</td>
					<td>{{ $vehiculos->modelos['modelo'] }}</td>
					<td>{{ $vehiculos->placa }}</td>
					<td>{{ $vehiculos->numero_serie }}</td>
					<td>
						<input type="checkbox" id="activo" name="activo" disabled @if($vehiculos->activo) checked="{{ $vehiculos->activo}}"@endif>
						<label for="activo"></label>
					</td>
					<td class="width-auto">
						<a href="{{ companyRoute("show", ['company'=> $company, 'id' => $vehiculos->id_vehiculo]) }}" class="waves-effect waves-light btn btn-flat no-padding"><i class="material-icons">visibility</i></a>
						<a href="{{ companyRoute("edit", ['company'=> $company, 'id' => $vehiculos->id_vehiculo]) }}" class="waves-effect waves-light btn btn-flat no-padding"><i class="material-icons">mode_edit</i></a>
						<a href="#" class="waves-effect waves-light btn btn-flat no-padding" onclick="event.preventDefault(); document.getElementById('delete-form-{{ $vehiculos->id_vehiculo}}').submit();"><i class="material-icons">delete</i></a>
						<form id="delete-form-{{ $vehiculos->id_vehiculo }}" action="{{ companyRoute("destroy", ['company'=> $company, 'id' => $vehiculos->id_vehiculo]) }}" method="POST" style="display: none;">
							{{ csrf_field() }}
							{{ method_field('DELETE') }}
						</form>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
</div>
@endsection
