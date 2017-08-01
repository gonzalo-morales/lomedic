@extends('layouts.dashboard')

@section('title', 'Crear')

@section('header-top')
@endsection

@section('header-bottom')
@endsection

@section('content')

	<form action="{{ companyRoute('index') }}" method="post" class="col s12">
		{{ csrf_field() }}
		{{ method_field('POST') }}
		<div class="col s12 xl8 offset-xl2">
			<div class="row">
				<div class="right">
					<button type="submit" class="waves-effect btn orange">Guardar y salir</button>
					<a href="{{ url()->previous() }}" class="waves-effect waves-teal btn-flat teal-text">Cancelar y salir</a>
				</div>
			</div>
		</div>
		<div class="col s6 xl8 offset-xl2">
			<h5>Datos Número de cuenta</h5>
			<div class="row">
				<div class="input-field col s3">
					<input type="text" name="numero_cuenta" id="numero_cuenta" class="validate" value="{{old('numero_cuenta')}}">
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
									{{ $banco->id_banco == old('fk_id_banco') ? 'selected' : '' }}
							>{{$banco->banco}}</option>
						@endforeach
					</select>
					<label for="fk_id_banco">Banco</label>
					@if ($errors->has('fk_id_banco'))
						<span class="help-block">
						<strong>{{ $errors->first('fk_id_banco') }}</strong>
					</span>
					@endif
				</div>
				<div class="input-field col s3">
					<select name="fk_id_sat_moneda" id="fk_id_sat_moneda">
						<option value="" disabled selected>Selecciona...</option>
						@foreach($coins as $moneda)
							<option value="{{$moneda->id_moneda}}"
									{{ $moneda->id_moneda == old('fk_id_sat_moneda') ? 'selected' : '' }}
							>{{$moneda->descripcion." (".$moneda->moneda.")"}}</option>
						@endforeach
					</select>
					<label for="fk_id_sat_moneda">Moneda</label>
					@if ($errors->has('fk_id_sat_moneda'))
						<span class="help-block">
						<strong>{{ $errors->first('fk_id_sat_moneda') }}</strong>
					</span>
					@endif
				</div>
				<div class="input-field col s3">
					<select name="fk_id_empresa" id="fk_id_empresa">
						<option value="" disabled selected>Selecciona...</option>
						@foreach($companies as $empresa)
							<option value="{{$empresa->id_empresa}}"
									{{ $empresa->id_empresa== old('fk_id_empresa') ? 'selected' : '' }}
							>{{$empresa->nombre_comercial}}</option>
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
		</div>
	</form>
@endsection
