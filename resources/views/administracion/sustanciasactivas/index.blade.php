@extends('layouts.dashboard')

@section('title', 'Sustancias Activas')

@section('header-top')
	<!--dataTable.css-->
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.3.1/css/buttons.dataTables.min.css">
@endsection

@section('header-bottom')
	<script src="//cdn.datatables.net/1.10.15/js/jquery.dataTables.js"></script>
	<script src="{{asset('solicitudes')}}"></script>
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
				<th>Sustancia Activa</th>
				<th>Gramaje</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
		@foreach ($data as $sustancia)
		<tr>
			<td>{{ $sustancia->sustancia_activa }}</td>
			<td>
				<p>
					<input value="{{$sustancia->opcion_gramaje}}" type="checkbox" id="opcion_gramaje" name="opcion_gramaje" disabled @if($sustancia->opcion_gramaje) checked="{{ $sustancia->opcion_gramaje}}"@endif>
					<label for="opcion_gramaje"></label>
				</p>
			</td>
			<td class="width-auto">
				<a href="{{ companyRoute('show', ['id' => $sustancia->id_sustancia_activa]) }}" class="waves-effect waves-light btn btn-flat no-padding"><i class="material-icons">visibility</i></a>
				<a href="{{ companyRoute('edit', ['id' => $sustancia->id_sustancia_activa]) }}" class="waves-effect waves-light btn btn-flat no-padding"><i class="material-icons">mode_edit</i></a>
				<a href="#" class="waves-effect waves-light btn btn-flat no-padding" onclick="event.preventDefault(); document.getElementById('delete-form{{$sustancia->id_sustancia_activa}}').submit();"><i class="material-icons">delete</i></a>
				<form id="delete-form{{$sustancia->id_sustancia_activa}}" action="{{ companyRoute('destroy', ['id' => $sustancia->id_sustancia_activa]) }}" method="POST" style="display: none;">
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
