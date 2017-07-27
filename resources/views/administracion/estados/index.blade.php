@extends('layouts.dashboard')

@section('title', 'Métodos de Pago')

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
				<th>Estado</th>
				<th>País</th>
				<th>Activo</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			@foreach ($data as $estado)
				<tr>
					<td>{{ $estado->estado }}</td>
					<td>{{ $estado->paises['pais'] }}</td>
					<td>
						<input type="checkbox" id="activo" name="activo" disabled @if($estado->activo) checked="{{ $estado->activo}}"@endif>
						<label for="activo"></label>
					</td>
					<td class="width-auto">
						<a href="{{ companyRoute('show', ['id' => $estado->id_estado]) }}" class="waves-effect waves-light btn btn-flat no-padding"><i class="material-icons">visibility</i></a>
						<a href="{{ companyRoute('edit', ['id' => $estado->id_estado]) }}" class="waves-effect waves-light btn btn-flat no-padding"><i class="material-icons">mode_edit</i></a>
						<a href="#" class="waves-effect waves-light btn btn-flat no-padding" onclick="event.preventDefault(); document.getElementById('delete-form{{$estado->id_estado}}').submit();"><i class="material-icons">delete</i></a>
						<form id="delete-form{{$estado->id_estado}}" action="{{ companyRoute('destroy', ['id' => $estado->id_estado]) }}" method="POST" style="display: none;">
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
