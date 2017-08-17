@extends('layouts.dashboard')

@section('title', 'Editar')

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
		<h4>Editar {{ trans_choice('messages.'.$entity, 0) }}</h4>
	</div>

	<div class="col s12 xl8 offset-xl2">
		<div class="row">
			<form action="{{ companyRoute("update", ['company'=> $company, 'id' => $data->id_unidad_medica]) }}" method="post" class="col s12">
				{{ csrf_field() }}
				{{ method_field('PUT') }}
				<div class="row">
					<div class="input-field col s12">
						<input type="text" name="nombre" id="nombre" class="validate" value="{{ $data->nombre }}">
						<label for="nombre">Nombre de la unidad medica</label>
						@if ($errors->has('nombre'))
							<span class="help-block">
							<strong>{{ $errors->first('nombre') }}</strong>
						</span>
						@endif
					</div>
				</div>

			

				<div class="row">
					<div class="col s12">
						<p>
							<input type="hidden" name="activo" value="0">
							<input type="checkbox" id="activo" name="activo" checked="{{ $data->activo }}"/>
							<label for="activo">¿Activo?</label>
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
						<button class="waves-effect waves-light btn right">Guardar</button>
					</div>
				</div>
			</form>
		</div>
	</div>
@endsection
