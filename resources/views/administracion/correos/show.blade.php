@extends('layouts.dashboard')

@section('title', 'Ver')

@section('header-top')
@endsection

@section('header-bottom')
	<script src="{{ asset('js/perfiles.js') }}"></script>
@endsection

@section('content')
<div class="col s12 xl8 offset-xl2">
	<div class="row">
		<div class="col s6">
			<p class="left-align">
				<a href="{{ companyRoute('index') }}" class="waves-effect waves-light btn">Regresar</a> <br>
			</p>
		</div>
		<div class="col s6">
			<p class="right-align">
				<a href="{{ companyRoute('edit') }}" class="waves-effect waves-light btn"><i class="material-icons right">mode_edit</i>Editar</a>
			</p>
		</div>
	</div>
	<div class="divider"></div>
</div>

<div class="col s12 xl8 offset-xl2">
	<div class="row">
		<div class="input-field col s4">
			<input type="text" name="correo" id="correo" class="validate" readonly value="{{ $data->correo}}">
			<label for="correo">Correo</label>
		</div>
		<div class="input-field col s6">
			<input type="text" name="usuario" id="usuario" class="validate" readonly value="{{ $user}}">
			<label for="usuario">Usuario</label>
		</div>
	</div>
</div>
@endsection
