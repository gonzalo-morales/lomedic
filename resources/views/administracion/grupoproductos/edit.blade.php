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
		<form action="{{ companyRoute("update", ['company'=> $company, 'id' => $data->id_grupo]) }}" method="post" class="col s12">
			{{ csrf_field() }}
			{{ method_field('PUT') }}
			<div class="row">
				<div class="input-field col s12">
					<input type="text" name="descripcion" id="descripcion" class="validate" value="{{ $data->descripcion }}">
					<label for="descripcion">Descripcion</label>
					@if ($errors->has('descripcion'))
						<span class="help-block">
							<strong>{{ $errors->first('laboratorio') }}</strong>
						</span>
					@endif
				</div>
			</div>
			<div class="row">
				<div class="input-field col s12">
					<input type="text" name="descripcion_producto" id="descripcion_producto" class="validate" value="{{ $data->descripcion_producto }}">
					<label for="descripcion_producto">Descripcion del puesto</label>
					@if ($errors->has('descripcion_producto'))
						<span class="help-block">
							<strong>{{ $errors->first('descripcion_producto') }}</strong>
						</span>
					@endif
				</div>
			</div>
			<div class="row">
				<div class="input-field col s12">
					<input type="text" name="nomenclatura" id="nomenclatura" class="validate" value="{{ $data->nomenclatura }}">
					<label for="nomenclatura">Descripcion del puesto</label>
					@if ($errors->has('nomenclatura'))
						<span class="help-block">
							<strong>{{ $errors->first('nomenclatura') }}</strong>
						</span>
					@endif
				</div>
			</div>
			<div class="row">
				<div class="input-field col s12">
					<input type="text" name="tipo" id="tipo" class="validate" value="{{ $data->tipo }}">
					<label for="tipo">Descripcion del puesto</label>
					@if ($errors->has('tipo'))
						<span class="help-block">
							<strong>{{ $errors->first('tipo') }}</strong>
						</span>
					@endif
				</div>
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
			<div class="row">
				<div class="col s12">
					<button class="waves-effect waves-light btn right">Guardar {{ trans_choice('messages.'.$entity, 0) }}</button>
				</div>
			</div>
		</form>
	</div>
</div>
@endsection
