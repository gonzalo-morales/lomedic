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
	<h4>Capturar nuevo {{ trans_choice('messages.'.$entity, 0) }}</h4>
</div>

<div class="col s12 xl8 offset-xl2">
	<div class="row">
		<form action="{{ companyRoute("index", ['company'=> $company]) }}" method="post" class="col s12">
			{{ csrf_field() }}
			{{ method_field('POST') }}
			<div class="row">
				<div class="input-field col s6">
					<input type="text" name="sustancia_activa" id="sustancia_activa" class="validate" value="{{old('sustancia_activa')}}">
					<label for="sustancia_activa">Sustancia Activa</label>
					@if ($errors->has('sustancia_activa'))
						<span class="help-block">
							<strong>{{ $errors->first('sustancia_activa') }}</strong>
						</span>
					@endif
				</div>
				<div class="input-field col s6">
					<input type="checkbox" name="opcion_gramaje" id="opcion_gramaje" clase="validate"
					{{old('opcion_gramaje') ? 'checked' : ''}}
					>
					<label for="opcion_gramaje">¿Gramaje?</label>
				</div>
			</div>
			<div class="row">
				<div class="col s12">
					<button class="waves-effect waves-light btn right">Guardar {{ trans_choice('messages.'.$entity, 0) }}</button>
				</div>
			</div>
		</form>
	</div>
</div>
@endsection
