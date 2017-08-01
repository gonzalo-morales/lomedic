@extends('layouts.dashboard')

@section('title', 'Motivos Ajustes')

@section('header-top')
	<!--dataTable.css-->
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.3.1/css/buttons.dataTables.min.css">
@endsection

@section('header-bottom')
	<script src="//cdn.datatables.net/1.10.15/js/jquery.dataTables.js"></script>
	<script src="{{ asset('js/dataTableGeneralConfig.js') }}"></script>
	<!-- <script src="https://cdn.datatables.net/v/dt/jszip-2.5.0/pdfmake-0.1.18/dt-1.10.12/b-1.2.2/b-colvis-1.2.2/b-html5-1.2.2/b-print-1.2.2/cr-1.3.2/r-2.1.0/datatables.min.js"></script> -->
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
				<th>Descripción</th>
				<th>Activo</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			@foreach ($data as $motivoajuste)
				<tr>
					<td>{{ $motivoajuste->descripcion }}</td>
					<td>
						<input type="checkbox" id="activo" name="activo" disabled @if($motivoajuste->activo) checked="{{ $motivoajuste->activo}}"@endif>
						<label for="activo"></label>
					</td>
					<td class="width-auto">
						<a href="{{ companyRoute("show", ['company'=> $company, 'id' => $motivoajuste->id_motivo_ajuste]) }}" class="waves-effect waves-light btn btn-flat no-padding"><i class="material-icons">visibility</i></a>
						<a href="{{ companyRoute("edit", ['company'=> $company, 'id' => $motivoajuste->id_motivo_ajuste]) }}" class="waves-effect waves-light btn btn-flat no-padding"><i class="material-icons">mode_edit</i></a>
						<a href="#" class="waves-effect waves-light btn btn-flat no-padding" onclick="event.preventDefault(); document.getElementById('delete-form-{{ $motivoajuste->id_motivo_ajuste}}').submit();"><i class="material-icons">delete</i></a>
						<form id="delete-form-{{ $motivoajuste->id_motivo_ajuste }}" action="{{ companyRoute("destroy", ['company'=> $company, 'id' => $motivoajuste->id_motivo_ajuste]) }}" method="POST" style="display: none;">
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
