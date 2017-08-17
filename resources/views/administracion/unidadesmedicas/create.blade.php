@extends('layouts.dashboard')

@section('title', 'Crear')

@section('header-top')
@endsection

@section('header-bottom')
	<script src="{{ asset('solicitudes') }}"></script>
@endsection

@section('content')
	<div class="col s12 xl8 offset-xl2">
		<p class="left-align">
			<a href="{{ url()->previous() }}" class="waves-effect waves-light btn">Regresar</a> <br>
		</p>
		<div class="divider"></div>
	</div>
	<div class="col s12 xl8 offset-xl2">
		<h4>Nueva unidad medica</h4>
	</div>

	<div class="col s12 xl8 offset-xl2">
		<div class="row">
			<form action="{{ companyRoute("index", ['company'=> $company]) }}" method="post" class="col s12">
				{{ csrf_field() }}
				{{ method_field('POST') }}
				<div class="row">
					<div class="input-field col s12">
						<input type="text" name="nombre" id="nombre" class="validate">
						<label for="Nombre Corto">Nombre</label>
						@if ($errors->has('nombre'))
							<span class="help-block">
							<strong>{{ $errors->first('nombre') }}</strong>
						</span>
						@endif
					</div>
				</div>
				<div class="row">

				<div class="row">
					<div class="col s12">
						<p>
							<input type="checkbox" id="activo" name="activo" />
							<label for="activo">¿Inactivo?</label>
						</p>
						@if ($errors->has('activo'))
							<span class="help-block">
							<strong>{{ $errors->first('activo') }}</strong>
						</span>
						@endif
					</div>
				</div>
				<div class="row">
					<div class="col s12">
						<button class="waves-effect waves-light btn right">Guardar Usuario</button>
					</div>
				</div>
			</form>
		</div>
	</div>
@endsection
