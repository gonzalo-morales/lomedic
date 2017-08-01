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
		<h5>Editar Subcategoría</h5>
		<div class="row">
			<div class="input-field col s4 m5">
				<input type="text" name="subcategoria" id="subcategoria" class="validate" value="{{ $data->subcategoria}}">
				<label for="subcategoria">Subcategoría</label>
				@if ($errors->has('subcategoria'))
					<span class="help-block">
						<strong>{{ $errors->first('subcategoria') }}</strong>
					</span>
				@endif
			</div>
			<div class="input-field col s4">
				<select name="fk_id_categoria" id="fk_id_categoria">
					<option value="" disabled selected>Selecciona...</option>
					@foreach($categories as $categoria)
						<option value="{{$categoria->id_categoria}}"
								{{ $categoria->id_categoria == $data->fk_id_categoria ? 'selected' : '' }}
						>{{$categoria->categoria}}</option>
					@endforeach
				</select>
				<label for="fk_id_categoria">Categoria</label>
				@if ($errors->has('fk_id_categoria'))
					<span class="help-block">
						<strong>{{ $errors->first('fk_id_categoria') }}</strong>
					</span>
				@endif
			</div>
			<div class="input-field col s4 m7">
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
