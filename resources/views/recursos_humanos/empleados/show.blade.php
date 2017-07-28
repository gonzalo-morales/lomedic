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
				<a href="{{ companyRoute("index",['company'=> $company]) }}" class="waves-effect waves-light btn">Regresar</a> <br>
			</p>
		</div>
		<div class="col s6">
			<p class="right-align">
				<a href="{{ companyRoute("edit", ['company'=> $company, 'id' => $data->id_empleado]) }}" class="waves-effect waves-light btn"><i class="material-icons right">mode_edit</i>Editar</a>
			</p>
		</div>
	</div>
	<div class="divider"></div>
</div>

<div class="col s12 xl8 offset-xl2">
	<div class="row">
		<div class="input-field col s4">
			<input type="text" name="nombre" id="nombre" class=" " value="{{$data->nombre}}" readonly>
			<label for="nombre">Nombre(s)</label>
		</div>
		<div class="input-field col s4">
			<input type="text" name="apellido_paterno" id="apellido_paterno" class=" " value="{{$data->apellido_paterno}}" readonly>
			<label for="apellido_paterno">Apellido paterno</label>
		</div>
		<div class="input-field col s4">
			<input type="text" name="apellido_materno" id="apellido_materno" class=" " value="{{$data->apellido_materno}}" readonly>
			<label for="apellido_materno">Apellido materno</label>
		</div>
	</div>
	<div class="row">
		<div class="input-field col s2">
			<input type="text" name="fecha_nacimiento" id="fecha_nacimiento" class=" " value="{{$data->fecha_nacimiento}}" readonly>
			<label for="fecha_nacimiento">Fecha de Nacimiento</label>
		</div>
		<div class="input-field col s5">
			<input type="text" name="curp" id="curp" class=" " value="{{$data->curp}}" readonly>
			<label for="curp">CURP</label>
		</div>
		<div class="input-field col s5">
			<input type="text" name="rfc" id="rfc" class=" " value="{{$data->rfc}}" readonly>
			<label for="rfc">RFC</label>
		</div>
	</div>
	<div class="row">
		<div class="input-field col s4">
			<input type="text" name="correo_personal" id="correo_personal" class=" " value="{{$data->correo_personal}}" readonly>
			<label for="correo_personal">Correo</label>
		</div>
		<div class="input-field col s4">
			<input type="text" name="telefono" id="telefono" class=" " value="{{$data->telefono}}" readonly>
			<label for="telefono">Teléfono</label>
		</div>
		<div class="input-field col s4">
			<input type="text" name="celular" id="celular" class=" " value="{{$data->celular}}" readonly>
			<label for="celular">Celular</label>
		</div>
	</div>
	<div class="row">
		<div class="input-field col s4">
			<input type="text" name="fk_id_empresa_alta_imss" id="fk_id_empresa_alta_imss" value="{{$empresa_alta_imss->nombre_comercial}}" readonly>
			<label for="fk_id_empresa_alta_imss">Empresa alta IMSS</label>
		</div>
		<div class="input-field col s4">
			<input type="text" name="numero_imss" id="numero_imss" class=" " value="{{$data->numero_imss}}" readonly>
			<label for="numero_imss">Número IMSS</label>
		</div>
		<div class="input-field col s4">
			<input type="text" name="fk_id_empresa_laboral" id="fk_id_empresa_laboral" value="{{$empresa_laboral->nombre_comercial}}" readonly>
			<label for="fk_id_empresa_laboral">Empresa Laboral</label>
		</div>
	</div>
	<div class="row">
		<div class="input-field col s4">
			<input type="text" name="numero_infonavit" id="numero_infonavit" class=" " readonly value="{{$data->numero_infonavit}}">
			<label for="numero_infonavit">Número INFONAVIT</label>
		</div>
	</div>
</div>
@endsection
