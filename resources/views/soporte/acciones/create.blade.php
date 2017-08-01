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
		<div class="col s12 xl8 offset-xl2">
			<h5>Datos Acciones</h5>
			<div class="row">
				<div class="input-field col s6">
					<input type="text" name="accion" class="validate" value="{{old('accion')}}">
					<label for="accion">Acción</label>
					@if ($errors->has('accion'))
						<span class="help-block">
						<strong>{{ $errors->first('accion') }}</strong>
					</span>
					@endif
				</div>
				<div class="input-field col s6">
					<select name="fk_id_subcategoria" id="fk_id_subcategoria">
						<option value="" disabled selected>Selecciona...</option>
						@foreach($subcategories as $subcategoria)
							<option value="{{$subcategoria->id_subcategoria}}"
									{{ $subcategoria->id_subcategoria == old('fk_id_subcategoria') ? 'selected' : '' }}
							>{{$subcategoria->subcategoria}}</option>
						@endforeach
					</select>
					<label for="fk_id_subcategoria">Subcategoria</label>
					@if ($errors->has('fk_id_subcategoria'))
						<span class="help-block">
						<strong>{{ $errors->first('fk_id_subcategoria') }}</strong>
					</span>
					@endif
				</div>
			</div>
		</div>
	</form>
@endsection