@extends('layouts.dashboard')

@section('title', 'Ver')

@section('header-top')
@endsection

@section('header-bottom')
	<script src="{{ asset('js/modulos.js') }}"></script>
@endsection

@section('content')
<div class="row">
	<div class="col-12">
		<div class="text-right">
			<a href="{{ companyRoute('edit', ['id' => $data->id_modulo]) }}" class="btn btn-primary"><i class="material-icons align-middle">mode_edit</i> Editar</a>
			<a href="{{ companyRoute('index') }}" class="btn btn-default">Regresar</a>
		</div>
	</div>
</div>
<div class="col-12">
	<h5 class="display-4">Datos modulo</h5>
	<div class="row">
		<div class="col-sm-12 col-md-5">
			<div class="form-group">
				<label for="nombre">Nombre:</label>
				<input type="text" name="nombre" id="nombre" class="validate form-control"  readonly value="{{ $data->nombre }}">
				@if ($errors->has('nombre'))
					<span class="help-block">
						<strong>{{ $errors->first('nombre') }}</strong>
					</span>
				@endif
			</div>
		</div>
		<div class="col-sm-12 col-md-7">
			<div class="form-group">
				<label for="descripcion">Descripcion:</label>
				<input type="text" name="descripcion" id="descripcion" class="validate form-control"  readonly value="{{ $data->descripcion }}">
				@if ($errors->has('descripcion'))
					<span class="help-block">
						<strong>{{ $errors->first('descripcion') }}</strong>
					</span>
				@endif
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12 col-md-4">
			<div class="form-group">
				<label for="url">URL:</label>
				<input type="text" name="url" id="url" class="validate form-control"  readonly value="{{ $data->url }}">
				@if ($errors->has('url'))
					<span class="help-block">
						<strong>{{ $errors->first('url') }}</strong>
					</span>
				@endif
			</div>
		</div>
		<div class="col-sm-12 col-md-4">
			<div class="form-group">
				<label for="iconos">Definir ícono para identificar</label>
				<select id="iconos" name="icono" disabled class="form-control">
					<option disabled selected>Icono ...</option>
					<option value="icono-1" {{ $data->icono == 'icono-1' ? 'selected' : '' }}>Option 1</option>
					<option value="icono-2" {{ $data->icono == 'icono-2' ? 'selected' : '' }}>Option 2</option>
					<option value="icono-3" {{ $data->icono == 'icono-3' ? 'selected' : '' }}>Option 3</option>
				</select>
				@if ($errors->has('icono'))
					<span class="help-block">
						<strong>{{ $errors->first('icono') }}</strong>
					</span>
				@endif
			</div>
		</div>
		<div class="col-sm-12 col-md-4">
			<div class="form-group">
					<label for="empresas_modulos">Seleccione las empresas en que se mostrará el módulo:</label>
					<select id="empresas_modulos" class="form-control" name="empresas[]" multiple disabled="">
					<option disabled selected>Empresas ...</option>
					@foreach ($empresas as $empresa)
					<option value="{{$empresa->id_empresa}}" {{ in_array( $empresa->id_empresa , $data->empresas->pluck('id_empresa')->toArray() ) ? 'selected' :'' }} >{{$empresa->nombre_comercial}}</option>
					@endforeach
				</select>
				@if ($errors->has('empresas'))
					<span class="help-block">
						<strong>{{ $errors->first('empresas') }}</strong>
					</span>
				@endif
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-6 col-md-3">
			<div class="form-check">
				<input type="checkbox" id="accion_menu" name="accion_menu" disabled {{$data->accion_menu ? 'checked' : ''}} />
				<label for="accion_menu">Acción Menu</label>
				@if ($errors->has('accion_menu'))
					<span class="help-block">
						<strong>{{ $errors->first('accion_menu') }}</strong>
					</span>
				@endif
			</div>
		</div>
		<div class="col-sm-6 col-md-3">
			<div class="form-check">
				<input type="checkbox" id="accion_barra" name="accion_barra" disabled {{$data->accion_barra ? 'checked' : ''}} />
				<label for="accion_barra">Acción Barra</label>
				@if ($errors->has('accion_barra'))
					<span class="help-block">
						<strong>{{ $errors->first('accion_barra') }}</strong>
					</span>
				@endif
			</div>
		</div>
		<div class="col-sm-6 col-md-3">
			<div class="form-check">
				<input type="checkbox" id="accion_tabla" name="accion_tabla" disabled {{$data->accion_tabla ? 'checked' : ''}} />
				<label for="accion_tabla">Acción Tabla</label>
				@if ($errors->has('accion_tabla'))
					<span class="help-block">
						<strong>{{ $errors->first('accion_tabla') }}</strong>
					</span>
				@endif
			</div>
		</div>
		<div class="col-sm-6 col-md-3">
			<div class="form-check">
				<input type="checkbox" id="modulo_seguro" name="modulo_seguro" disabled {{$data->modulo_seguro ? 'checked' : ''}} />
				<label for="modulo_seguro">Modo seguro</label>
				@if ($errors->has('modulo_seguro'))
					<span class="help-block">
						<strong>{{ $errors->first('modulo_seguro') }}</strong>
					</span>
				@endif
			</div>
		</div>
	</div>
</div>

@endsection
