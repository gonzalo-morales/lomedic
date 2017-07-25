@extends('layouts.dashboard')

@section('title', 'Editar')

@section('header-top')
@endsection

@section('header-bottom')
	<script src="{{ asset('js/modulos.js') }}"></script>
@endsection

@section('content')
<form action="{{ route("$entity.update", ['company'=> $company, 'id' => $data->id_familia]) }}" method="post" class="col s12">
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
		<h5>Editar Familia de Producto</h5>
		<div class="row">
			<div class="input-field col s12 m5">
				<input type="text" name="descripcion" id="descripcion" class="validate" value="{{ $data->descripcion }}">
				<label for="Familia">Familia:</label>
				@if ($errors->has('descripcion'))
					<span class="help-block">
						<strong>{{ $errors->first('descripcion') }}</strong>
					</span>
				@endif
			</div>
			<div class="input-field col s12 m4">
				<input type="text" name="nomenclatura" id="nomenclatura" class="validate" value="{{ $data->nomenclatura }}">
				<label for="Nomenclatura">Nomenclatura:</label>
				@if ($errors->has('nomenclatura'))
					<span class="help-block">
						<strong>{{ $errors->first('nomenclatura') }}</strong>
					</span>
				@endif
			</div>
			<div class="input-field col s12 m3">
				<input type="checkbox" id="estatus" name="estatus" {{$data->estatus ? 'checked' : ''}} />
				<label for="Estatus">Estatus:</label>
				@if ($errors->has('estatus'))
					<span class="help-block">
						<strong>{{ $errors->first('estatus') }}</strong>
					</span>
				@endif
			</div>
		</div>
	</div>
</form>
@endsection
