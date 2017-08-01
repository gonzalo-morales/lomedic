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
		<h5>Editar Acciones</h5>
		<div class="row">
			<div class="input-field col s4 m5">
				<input type="text" name="accion" id="accion" class="validate" value="{{ $data->accion}}">
				<label for="accion">Acción</label>
				@if ($errors->has('accion'))
					<span class="help-block">
						<strong>{{ $errors->first('accion') }}</strong>
					</span>
				@endif
			</div>
			<div class="input-field col s4">
				<select name="fk_id_subcategoria" id="fk_id_subcategoria">
					<option value="" disabled selected>Selecciona...</option>
					@foreach($subcategories as $subcategoria)
						<option value="{{$subcategoria->id_subcategoria}}"
								{{ $subcategoria->id_subcategoria == $data->fk_id_subcategoria ? 'selected' : '' }}
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
