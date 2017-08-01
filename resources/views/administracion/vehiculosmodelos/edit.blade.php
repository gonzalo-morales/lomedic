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
		<h5>Editar Marca</h5>
		<div class="row">
			<div class="input-field col s4">
				<input type="text" name="modelo" id="modelo" class="validate" value="{{$data->modelo}}">
				<label for="modelo">Modelo</label>
				@if ($errors->has('modelo'))
					<span class="help-block">
						<strong>{{ $errors->first('modelo') }}</strong>
					</span>
				@endif
			</div>
			<div class="input-field col s4">
				<select name="fk_id_marca">
					<option value="" disabled selected>Selecciona...</option>
					@foreach($brands as $marca)
						<option value="{{$marca->id_marca}}" {{$marca->id_marca == $data->fk_id_marca ? 'selected':''}}>
							{{$marca->marca}}
						</option>
					@endforeach
				</select>
				<label for="fk_id_marca">Marca</label>
			</div>
			<div class="input-field col s4">
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
