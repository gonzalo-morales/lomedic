@extends('layouts.dashboard')

@section('title', 'Crear')

@section('header-top')
@endsection

@section('header-bottom')
	<script src="{{ asset('js/modulos.js') }}"></script>
@endsection

@section('content')
<form action="{{ companyRoute('index') }}" method="post" class="col-12">
	@csrf
	{{ method_field('POST') }}
	<div class="row">
		<div class="col-12">
			<div class="text-right">
				<button type="submit" class="btn btn-primary progress-button">Guardar y salir</button>
				<a href="{{ url()->previous() }}" class="btn btn-default progress-button">Cancelar y salir</a>
			</div>
		</div>
	</div>
	<div class="col-12">
		<h5 class="display-4">Crear modulo</h5>
			<div class="row">
				<div class="col-sm-12 col-md-5">
					<div class="form-group">
						<label for="nombre">Nombre:</label>
						<input type="text" name="nombre" id="nombre" class="validate form-control">
						@if ($errors->has('nombre'))
							
							<span class="help-block text-danger">
								
								{{ $errors->first('nombre') }}
							</span>
						@endif
					</div>
				</div>
				<div class="col-sm-12 col-md-7">
					<div class="form-group">
						<label for="descripcion">Descripcion:</label>
						<input type="text" name="descripcion" id="descripcion" class="validate form-control">
						
						@if ($errors->has('descripcion'))
							
							<span class="help-block text-danger">
								
								{{ $errors->first('descripcion') }}
							</span>
						@endif
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12 col-md-3">
					<div class="form-group">
						<label for="url">URL:</label>
						<input type="text" name="url" id="url" class="validate form-control">
						@if ($errors->has('url'))
							
							<span class="help-block text-danger">
								{{ $errors->first('url') }}
							</span>
						@endif
					</div>
				</div>
				<div class="col-sm-12 col-md-3">
					<div class="form-group">
						<label for="iconos">Definir ícono para identificar</label>
						<select id="iconos" class="form-control" name="icono">
							<option disabled selected>Icono ...</option>
							<option value="icono-1">Option 1</option>
							<option value="icono-2">Option 2</option>
							<option value="icono-3">Option 3</option>
						</select>
						@if ($errors->has('icono'))
							
							<span class="help-block text-danger">
								{{ $errors->first('icono') }}
							</span>
						@endif
					</div>
				</div>
				<div class="col-sm-12 col-md-3">
					<div class="form-group">
						<label for="modulos_multy">Seleccione el/los módulo(s):</label>
						<select id="modulos_multy" class="form-control" name="modulos[]" multiple>
							<option disabled selected>modulos ...</option>
							@foreach ($modulos as $modulo)
							<option value="{{$modulo->id_modulo}}">{{$modulo->nombre}}</option>
							@endforeach
						</select>
						@if ($errors->has('modulos'))
							
							<span class="help-block text-danger">
								{{ $errors->first('modulos') }}
							</span>
						@endif
					</div>
				</div>
				<div class="col-sm-12 col-md-3">
					<div class="form-group">
						<label for="empresas_modulos">Seleccione las empresas en que se mostrará el módulo:</label>
						<select id="empresas_modulos" class="form-control" name="empresas[]" multiple>
							<option disabled selected>Empresas ...</option>
							@foreach ($empresas as $empresa)
							<option value="{{$empresa->id_empresa}}">{{$empresa->nombre_comercial}}</option>
							@endforeach
						</select>
						@if ($errors->has('empresas'))
							
							<span class="help-block text-danger">
								{{ $errors->first('empresas') }}
							</span>
						@endif
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-6 col-md-3">
					<div class="form-check">
						<input type="checkbox" id="accion_menu" name="accion_menu" />
						<label for="accion_menu">Acción Menu</label>
						@if ($errors->has('accion_menu'))
							
							<span class="help-block text-danger">
								{{ $errors->first('accion_menu') }}
							</span>
						@endif
					</div>
				</div>
				<div class="col-sm-6 col-md-3">
					<div class="form-check">
						<input type="checkbox" id="accion_barra" name="accion_barra" />
						<label for="accion_barra">Acción Barra</label>
						@if ($errors->has('accion_barra'))
							
							<span class="help-block text-danger">
								{{ $errors->first('accion_barra') }}
							</span>
						@endif
					</div>
				</div>
				<div class="col-sm-6 col-md-3">
					<div class="form-check">
						<input type="checkbox" id="accion_tabla" name="accion_tabla" />
						<label for="accion_tabla">Acción Tabla</label>
						@if ($errors->has('accion_tabla'))
							
							<span class="help-block text-danger">
								{{ $errors->first('accion_tabla') }}
							</span>
						@endif
					</div>
				</div>
				<div class="col-sm-6 col-md-3">
					<div class="form-check">
						<input type="checkbox" id="modulo_seguro" name="modulo_seguro" />
						<label for="modulo_seguro">Modo seguro</label>
						@if ($errors->has('modulo_seguro'))
							
							<span class="help-block text-danger">
								{{ $errors->first('modulo_seguro') }}
							</span>
						@endif
					</div>
				</div>
			</div>
	</div>
</form>
@endsection
