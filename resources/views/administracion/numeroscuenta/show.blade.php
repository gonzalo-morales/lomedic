@extends('layouts.dashboard')

@section('title', 'Ver')

@section('header-top')
@endsection

@section('header-bottom')
	<script src="{{ asset('js/modulos.js') }}"></script>
@endsection

@section('content')
<div class="col s12 xl8 offset-xl2">
	<div class="row">
		<div class="col s12">
			<p class="right-align">
				<a href="{{ companyRoute('edit') }}" class="waves-effect waves-light btn orange"><i class="material-icons right">mode_edit</i>Editar</a>
				<a href="{{ companyRoute('index') }}" class="waves-effect waves-light btn btn-flat teal-text">Regresar</a>
			</p>
		</div>
	</div>
</div>
<div class="col s12 xl8 offset-xl2">
	<h5>Datos del número de cuenta</h5>
	<div class="row">
		<div class="input-field col s3">
			<input type="text" name="numero_cuenta" id="numero_cuenta" class="" value="{{$data->numero_cuenta}}" readonly>
			<label for="jurisdiccion">Número de cuenta</label>
		</div>
		<div class="input-field col s3">
			<input type="text" name="fk_id_banco" id="fk_id_banco" class="" value="{{$bank}}" readonly>
			<label for="fk_id_estado">Banco</label>
		</div>
		<div class="input-field col s3">
			<input type="text" name="fk_id_sat_moneda" id="fk_id_sat_moneda" class="" value="{{$coin}}" readonly>
			<label for="fk_id_sat_moneda">Moneda</label>
		</div>
		<div class="input-field col s3">
			<input type="text" name="fk_id_empresa" id="fk_id_empresa" class="" value="{{$company_owner}}" readonly>
			<label for="fk_id_empresa">Empresa</label>
		</div>
	</div>
	<div>
		<div class="input-field col s12">
			<input type="hidden" name="activo" value="0">
			<input type="checkbox" id="activo" name="activo" {{$data->activo ? 'checked' : ''}} disabled/>
			<label for="activo">Estatus:</label>
		</div>
	</div>
</div>
@endsection
