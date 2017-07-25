@extends('layouts.dashboard')

@section('title', 'Crear')

@section('header-top')
@endsection

@section('header-bottom')
	<script src="{{ asset('js/modulos.js') }}"></script>
@endsection

@section('content')

<form action="{{ route("$entity.index", ['company'=> $company]) }}" method="post" class="col s12">
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
		<h5>Datos Familias de Productos</h5>
		<div class="row">
			<div class="input-field col s12 m5">
				<input type="text" name="descripcion" id="descripcion" class="validate">
				<label for="Familia">Familia:</label>
				@if ($errors->has('descripcion'))
					<span class="help-block">
						<strong class="red-text">* {{ $errors->first('descripcion') }}</strong>
					</span>
				@endif
			</div>
			<div class="input-field col s12 m4">
				<input type="text" name="nomenclatura" id="nomenclatura" class="validate">
				<label for="Nomenclatura">Nomenclatura:</label>
				@if ($errors->has('nomenclatura'))
					<span class="help-block">
						<strong class="red-text">* {{ $errors->first('nomenclatura') }}</strong>
					</span>
				@endif
			</div>
			<div class="input-field col s12 m3">
				<input type="checkbox" id="estatus" name="estatus" checked />
				<label for="Estatus">Estatus:</label>
				@if ($errors->has('estatus'))
					<span class="help-block">
						<strong class="red-text">* {{ $errors->first('estatus') }}</strong>
					</span>
				@endif
			</div>
		</div>
	</div>
</form>
@endsection
