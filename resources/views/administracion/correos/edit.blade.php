@extends('layouts.dashboard')

@section('title', 'Editar')

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
	<h4>Editar {{ trans_choice('messages.'.$entity, 0) }}</h4>
</div>

<div class="col s12 xl8 offset-xl2">
	<div class="row">
		<form action="{{ companyRoute('update') }}" method="post" class="col s12">
			{{ csrf_field() }}
			{{ method_field('PUT') }}
			<div class="row">
				<div class="input-field col s6">
					<input type="text" name="correo" id="correo" class="validate" value="{{ $data->correo}}">
					<label for="correo">Correo</label>
					@if ($errors->has('correo'))
						<span class="help-block">
							<strong>{{ $errors->first('correo') }}</strong>
						</span>
					@endif
				</div>
				<div class="input-field col s3">
					<select name="fk_id_usuario" id="fk_id_usuario" class="validate">
						@foreach($users as $usuario)
							<option {{$usuario->id_usuario == $data->fk_id_usuario ? ' selected ' : ''}} value="{{$usuario->id_usuario}}">{{$usuario->usuario}}</option>
						@endforeach
					</select>
					<label for="fk_id_usuario">Usuario</label>
					@if ($errors->has('fk_id_usuario'))
						<span class="help-block">
							<strong>{{ $errors->first('fk_id_usuario') }}</strong>
						</span>
					@endif
				</div>
				<div class="input-field col s3">
					<select name="fk_id_empresa" id="fk_id_empresa" class="validate">
						@foreach($companies as $empresa)
							<option {{$empresa->id_empresa == $data->fk_id_empresa ? ' selected ' : ''}} value="{{$empresa->id_empresa}}">{{$empresa->nombre_comercial}}</option>
						@endforeach
					</select>
					<label for="fk_id_empresa">Empresa</label>
					@if ($errors->has('fk_id_empresa'))
						<span class="help-block">
							<strong>{{ $errors->first('fk_id_empresa') }}</strong>
						</span>
					@endif
				</div>
			</div>
			<div class="row">
				<div class="col s2">
					<p>
						<input type="hidden" name="activo" value="0">
						<input type="checkbox" id="activo" name="activo" {{$data->activo ? 'checked':''}}>
						<label for="activo">Activo</label>
					</p>
				</div>
				<div class="col s10">
					<button class="waves-effect waves-light btn right">Guardar {{ trans_choice('messages.'.$entity, 0) }}</button>
				</div>
			</div>
		</form>
	</div>
</div>
@endsection
