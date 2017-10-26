@extends('layouts.dashboard')

@section('title', 'perfiless')

@section('header-top')
	<!--dataTable.css-->
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.3.1/css/buttons.dataTables.min.css">
@endsection

@section('header-bottom')
	<script src="//cdn.datatables.net/1.10.15/js/jquery.dataTables.js"></script>
	<script src="{{ asset('js/perfiles.js') }}"></script>
	<!-- <script src="https://cdn.datatables.net/v/dt/jszip-2.5.0/pdfmake-0.1.18/dt-1.10.12/b-1.2.2/b-colvis-1.2.2/b-html5-1.2.2/b-print-1.2.2/cr-1.3.2/r-2.1.0/datatables.min.js"></script> -->
@endsection

@section('content')
<div class="col-12">
	<p class="text-right">
		<a href="{{ companyRoute('create') }}" class="btn btn-primary progress-button">Nuevo</a>
		<a href="{{ companyRoute('index') }}" class="btn btn-info progress-button"><i class="material-icons align-middle">cached</i> Actualizar</a>
	</p>
</div>
@if (session('success'))
<div class="col-12">
	<div class="alert alert-success">
		{{ session('success') }}
	</div>
</div>
@endif
<div class="col-12">
	<table class="table table-striped table-responsive-sm table-hover">
		<thead>
			<tr>
				<th>Perfiles</th>
				<th>Descripción</th>
				<th>Activo</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
		@foreach ($data as $perfiles)
		<tr>
			<td>{{ $perfiles->nombre_perfil}}</td>
			<td>{{ $perfiles->descripcion }}</td>
			<td>
				<p>
					<input type="checkbox" id="activo" name="activo" disabled checked="{{ $perfiles->activo}}">
					<label for="activo"></label>
				</p>
			</td>
			<td class="width-auto">
				<a data-toggle="tooltip" data-placement="top" title="Ver" href="{{ companyRoute("show", ['id' => $perfiles->id_perfil,'company'=> $company]) }}" class="btn is-icon"><i class="material-icons">visibility</i></a>
				<a data-toggle="tooltip" data-placement="top" title="Editar" href="{{ companyRoute("edit", ['id' => $perfiles->id_perfil,'company'=> $company]) }}" class="btn is-icon"><i class="material-icons">mode_edit</i></a>
				<a data-toggle="tooltip" data-placement="top" title="Borrar" href="#" class="btn is-icon" onclick="event.preventDefault(); document.getElementById('delete-form{{$perfiles->id_perfil}}').submit();"><i class="material-icons">delete</i></a>
				<form id="delete-form{{$perfiles->id_perfil}}" action="{{ companyRoute("destroy", ['id' => $perfiles->id_perfil,'company'=> $company]) }}" method="POST" style="display: none;">
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
