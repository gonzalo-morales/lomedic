@extends('layouts.dashboard')

@section('title', 'Ver')

@section('header-top')
@endsection

@section('header-bottom')
	<script src="{{ asset('js/dataTableGeneralConfig.js') }}"></script>
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
				<a href="{{ companyRoute('edit', ['company'=> $company, 'id' => $data->id_pais]) }}" class="waves-effect waves-light btn"><i class="material-icons right">mode_edit</i>Editar</a>
			</p>
		</div>
	</div>
	<div class="divider"></div>
</div>



<div class="col s12 xl8 offset-xl2">
	<div class="row">
		<div class="input-field col s6">
			<input type="text" name="paises" id="paises" class="validate" readonly value="{{ $data->pais }}">
			<label for="paises">País</label>
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
