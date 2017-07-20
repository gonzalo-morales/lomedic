@extends('layouts.dashboard')

@section('title', 'Ver')

@section('header-top')
@endsection

@section('header-bottom')
	<script src="{{ asset('js/bancos.js') }}"></script>
@endsection

@section('content')
<div class="col s12 xl8 offset-xl2">
	<div class="row">
		<div class="col s6">
			<p class="left-align">
				<a href="{{ route("$entity.index",['company'=> $company]) }}" class="waves-effect waves-light btn">Regresar</a> <br>
			</p>
		</div>
		<div class="col s6">
			<p class="right-align">
				<a href="{{ route("$entity.edit", ['company'=> $company, 'id' => $data->id_banco]) }}" class="waves-effect waves-light btn"><i class="material-icons right">mode_edit</i>Editar</a>
			</p>
		</div>
	</div>
	<div class="divider"></div>
</div>

<div class="col s12 xl8 offset-xl2">
	<div class="row">
		<div class="input-field col s12">
			<input type="text" name="razon_social" id="razon_social" class="validate" readonly value="{{ $data->razon_social }}">
			<label for="razon_social">Razón Social</label>
		</div>
	</div>
	<div class="row">
		<div class="input-field col s6">
			<input type="text" name="banco" id="banco" class="validate" readonly value="{{ $data->banco }}">
			<label for="banco">Banco</label>
		</div>
		<div class="input-field col s6">
			<input type="text" name="rfc" id="rfc" class="validate" readonly value="{{ $data->rfc }}">
			<label for="rfc">RFC</label>
		</div>
	</div>
	<div class="row">
		<div class="col s12">
			<p>
				<input type="checkbox" id="nacional" name="nacional" disabled checked="{{ $data->razon_social }}">
				<label for="nacional">¿Banco nacional?</label>
			</p>
		</div>
	</div>
</div>
@endsection
