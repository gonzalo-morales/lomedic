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
			<h5>Datos Marca</h5>
			<div class="row">
				<div class="input-field col s6">
					<input type="text" name="modelo" id="modelo" class="validate" value="{{old('modelo')}}">
					<label for="modelo">Modelo</label>
					@if ($errors->has('modelo'))
						<span class="help-block">
						<strong>{{ $errors->first('modelo') }}</strong>
					</span>
					@endif
				</div>
				<div class="input-field col s6 m6">
					<select name="fk_id_marca" id="fk_id_marca">
						<option value="" disabled selected>Selecciona...</option>
					@foreach($brands as $marca)
							<option value="{{$marca->id_marca}}"
									{{ $marca->id_marca == old('fk_id_marca') ? 'selected' : '' }}
							>{{$marca->marca}}</option>
					@endforeach
					</select>
					<label for="fk_id_marca">Marca</label>
				</div>
			</div>
		</div>
	</form>
@endsection
