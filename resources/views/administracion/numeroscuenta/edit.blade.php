@extends('layouts.dashboard')

@section('title', 'Editar')

@section('header-top')
@endsection

@section('header-bottom')
@endsection

@section('content')
<form action="{{ companyRoute('update') }}" method="post" class="col s12">
	{{ csrf_field() }}
	{{ method_field('PUT') }}
	<div class="col s12 xl8 offset-xl2">
		<div class="row">
			<div class="right">
				<button type="submit" class="waves-effect btn orange">Guardar y salir</button>
				<a href="{{ url()->previous() }}" class="waves-effect waves-teal btn-flat teal-text">Cancelar y salir</a>
			</div>
		</div>
	</div>
	<div class="col s12 xl8 offset-xl2">
		<h5>Editar Número de cuenta</h5>
		<div class="row">
			<div class="input-field col s3">
				<input type="text" name="numero_cuenta" id="numero_cuenta" class="validate" value="{{$data->numero_cuenta}}">
				<label for="numero_cuenta">Número de cuenta</label>
				@if ($errors->has('numero_cuenta'))
					<span class="help-block">
						<strong>{{ $errors->first('numero_cuenta') }}</strong>
					</span>
				@endif
			</div>
			<div class="input-field col s3">
				<select name="fk_id_banco" id="fk_id_banco">
					<option value="" disabled selected>Selecciona...</option>
					@foreach($banks as $banco)
						<option value="{{$banco->id_banco}}"
								{{ $banco->id_banco == $data->fk_id_banco ? 'selected' : '' }}
						>{{$banco->banco}}</option>
					@endforeach
				</select>
				<label for="fk_id_banco">Banco</label>
			</div>
			<div class="input-field col s3">
				<select name="fk_id_sat_moneda" id="fk_id_sat_moneda">
					<option value="" disabled selected>Selecciona...</option>
					@foreach($coins as $moneda)
						<option value="{{$moneda->id_moneda}}"
								{{ $moneda->id_moneda == $data->fk_id_sat_moneda ? 'selected' : '' }}
						>{{$moneda->descripcion." (".$moneda->moneda.")"}}</option>
					@endforeach
				</select>
				<label for="fk_id_sat_moneda">Moneda</label>
			</div>
			<div class="input-field col s3">
				<select name="fk_id_empresa" id="fk_id_empresa">
					<option value="" disabled selected>Selecciona...</option>
					@foreach($companies as $empresa)
						<option value="{{$empresa->id_empresa}}"
								{{ $empresa->id_empresa== $data->fk_id_empresa ? 'selected' : '' }}
						>{{$empresa->nombre_comercial}}</option>
					@endforeach
				</select>
				<label for="fk_id_empresa">Empresa</label>
			</div>
		</div>
		<div class="row">
			<div class="input-field col s12">
				<input type="hidden" name="activo" value="0">
				<input type="checkbox" id="activo" name="activo" {{$data->activo ? 'checked' : ''}} />
				<label for="activo">Estatus:</label>
				@if ($errors->has('activo'))
					<span class="help-block">
						<strong>{{ $errors->first('activo') }}</strong>
					</span>
				@endif
			</div>
		</div>
	</div>
</form>
@endsection
