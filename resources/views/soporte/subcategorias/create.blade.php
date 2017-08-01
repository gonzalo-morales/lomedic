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
			<h5>Datos Subcategorias</h5>
			<div class="row">
				<div class="input-field col s6">
					<input type="text" name="subcategoria" id="subcategoria" class="validate" value="{{old('subcategoria')}}">
					<label for="subcategoria">Subcategoría</label>
					@if ($errors->has('subcategoria'))
						<span class="help-block">
						<strong>{{ $errors->first('subcategoria') }}</strong>
					</span>
					@endif
				</div>
				<div class="input-field col s6">
					<select name="fk_id_categoria" id="fk_id_categoria">
						<option value="" disabled selected>Selecciona...</option>
						@foreach($categories as $categoria)
							<option value="{{$categoria->id_categoria}}"
									{{ $categoria->id_categoria == old('fk_id_categoria') ? 'selected' : '' }}
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
			</div>
		</div>
	</form>
@endsection