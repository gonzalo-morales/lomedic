@extends('layouts.dashboard')

@section('title', 'Crear')

@section('header-top')
@endsection

@section('header-bottom')
	<script src="{{ asset('js/correos.js') }}"></script>
@endsection

@section('content')
<div class="col s12 xl8 offset-xl2">
	<p class="left-align">
		<a href="{{ url()->previous() }}" class="waves-effect waves-light btn">Regresar</a> <br>
	</p>
	<div class="divider"></div>
</div>
<div class="col s12 xl8 offset-xl2">
	<h4>Capturar nuevo {{ trans_choice('messages.'.$entity, 0) }}</h4>
</div>

<div class="col s12 xl8 offset-xl2">
	<div class="row">
		<form action="{{ companyRoute('index') }}" method="post" class="col s12">
			{{ csrf_field() }}
			{{ method_field('POST') }}
			<div class="row">
				<div class="input-field col s4">
					<input type="text" name="correo" id="correo" class="validate">
					<label for="correo">Correo</label>
					@if ($errors->has('correo'))
						<span class="help-block">
							<strong>{{ $errors->first('correo') }}</strong>
						</span>
					@endif
				</div>
				<div class="input-field col s4">
					<select name="fk_id_empresa" id="fk_id_empresa">
							@foreach($companies as $empresa)
							<option value="{{$empresa->id_empresa}}">{{$empresa->nombre_comercial}}</option>
						@endforeach
					</select>
					<label for="fk_id_empresa">Empresa</label>
				</div>
				<div class="input-field col s4">
					<select name="fk_id_usuario" id="fk_id_usuario">
						@foreach($users as $user)
							<option value="{{$user->id_usuario}}">{{$user->usuario}}</option>
						@endforeach
					</select>
					<label for="fk_id_usuario">Usuario</label>
				</div>
			</div>
			<div class="row">
				<div class="col s12">
					<button class="waves-effect waves-light btn right">Guardar {{ trans_choice('messages.'.$entity, 0) }}</button>
				</div>
			</div>
		</form>
	</div>
</div>
@endsection
