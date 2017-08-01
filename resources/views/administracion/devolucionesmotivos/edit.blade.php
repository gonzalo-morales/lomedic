@extends('layouts.dashboard')

@section('title', 'Editar')

@section('header-top')
@endsection

@section('header-bottom')
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
		<form action="{{ companyRoute("update", ['company'=> $company, 'id' => $data->id_devolucion_motivo]) }}" method="post" class="col s12">
			{{ csrf_field() }}
			{{ method_field('PUT') }}
			<div class="row">
				<div class="input-field col s4">
					<input type="text" name="devolucion_motivo" id="devolucion_motivo" class="validate" value="{{$data->devolucion_motivo}}">
					<label for="devolucion_motivo">Motivo de devolución</label>
					@if ($errors->has('devolucion_motivo'))
						<span class="help-block">
							<strong>{{ $errors->first('devolucion_motivo') }}</strong>
						</span>
					@endif
				</div>
				<div class="input-field col s4">
					<input class="field" id="localidad" name="solicitante_devolucion" type="radio" value="false" {{!$data->solicitante_devolucion ? 'checked' : ''}}>{{-- localidad --}}
					<label for="localidad">Localidad</label>
					<br>
					<input class="field" id="proveedor" name="solicitante_devolucion" type="radio" value="true"{{$data->solicitante_devolucion ? 'checked' : ''}}>{{-- proveedor --}}
					<label for="proveedor">Proveedor</label>
				</div>
				<div class="input-field col s4">
					<input type="hidden" name="activo" value="0">
					<input type="checkbox" name="activo" id="activo" clase="validate" {{$data->activo ? 'checked':''}}>
					<label for="activo">¿Activo?</label>
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
