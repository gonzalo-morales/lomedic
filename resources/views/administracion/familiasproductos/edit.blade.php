@extends('layouts.dashboard')

@section('title', 'Editar')

@section('header-top')
@endsection

@section('header-bottom')
	<script src="{{ asset('js/modulos.js') }}"></script>
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
				<input type="text" name="tipo_presentacion" id="tipo_presentacion" class="validate" value="{{ $data->tipo_presentacion }}">
				<label for="tipo_presentacion">Tipo presentacion:</label>
				@if ($errors->has('tipo_presentacion'))
					<span class="help-block">
						<strong>{{ $errors->first('tipo_presentacion') }}</strong>
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
			<div class="input-field col s12 m4">
				<input type="text" name="tipo" id="tipo" class="validate" value="{{ $data->tipo }}">
				<label for="tipo">Tipo:</label>
				@if ($errors->has('tipo'))
					<span class="help-block">
						<strong>{{ $errors->first('tipo') }}</strong>
					</span>
				@endif
			</div>
			<div class="row">
				<div class="col s12">
					<p>
						<input type="hidden" name="activo" value="0">
						<input type="checkbox" id="activo" name="activo" {{$data->activo ? 'checked':''}}/>
						<label for="activo">¿Activo?</label>
					</p>
					@if ($errors->has('activo'))
						<span class="help-block">
							<strong>{{ $errors->first('activo') }}</strong>
						</span>
					@endif
				</div>
			</div>
		</div>
	</div>
</form>
@endsection
