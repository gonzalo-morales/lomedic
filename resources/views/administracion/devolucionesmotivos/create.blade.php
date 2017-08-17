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
					<input type="text" name="devolucion_motivo" id="devolucion_motivo" class="validate" value="{{old('devolucion_motivo')}}">
					<label for="devolucion_motivo">Motivo de devolución</label>
					@if ($errors->has('devolucion_motivo'))
						<span class="help-block">
							<strong>{{ $errors->first('devolucion_motivo') }}</strong>
						</span>
					@endif
				</div>
				<div class="input-field col s6">
					<input class="field" id="localidad" name="solicitante_devolucion" type="radio" value="false" {{!old('solicitante_devolucion') ? 'checked' : ''}}>{{-- localidad --}}
					<label for="localidad">Localidad</label>
					<br>
					<input class="field" id="proveedor" name="solicitante_devolucion" type="radio" value="true"{{old('solicitante_devolucion') ? 'checked' : ''}}>{{-- proveedor --}}
					<label for="proveedor">Proveedor</label>
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
