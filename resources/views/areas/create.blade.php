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
		<h5>Datos del area</h5>
		<div class="row">
			<div class="input-field col s12 m7">
				<input type="text" name="area" id="area" class="validate">
				<label for="Area">Area:</label>
				@if ($errors->has('area'))
					<span class="help-block">
						<strong>{{ $errors->first('area') }}</strong>
					</span>
				@endif
			</div>
			<div class="input-field col s12 m5">
				<input type="text" name="clave_area" id="clave_area" class="validate">
				<label for="Calve">Calve:</label>
				@if ($errors->has('clave_area'))
					<span class="help-block">
						<strong>{{ $errors->first('clave_area') }}</strong>
					</span>
				@endif
			</div>
		</div>
	</div>
</form>
@endsection
