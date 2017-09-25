
@section('content-width', 's12 m7 xl8 offset-xl2')

@section('form-content')
{{ Form::setModel($data) }}

<div class="row">
	<div class="form-group col-md-6 col-xs-12">
		<label for="nombre_sucursal">Sucursal</label>
		<input type="text" name="nombre_sucursal" id="nombre_sucursal" class="form-control">
		@if ($errors->has('nombre_sucursal'))
			<span class="help-block">
				<strong>{{ $errors->first('nombre_sucursal') }}</strong>
			</span>
		@endif
	</div>
	<div class="form-group col-md-6 col-xs-12">
		<label for="fk_id_supervisor">Supervisor</label>
		<select name="fk_id_supervisor" id="fk_id_supervisor" class="form-control"></select>
	</div>
</div>
<div class="row">
	<div class="form-group col-md-6 col-xs-12">
		<label for="latitud">Latitud</label>
		<input type="text" name="latitud" id="latitud" class="form-control">
		@if ($errors->has('latitud'))
			<span class="help-block">
				<strong>{{ $errors->first('latitud') }}</strong>
			</span>
		@endif
	</div>
	<div class="form-group col-md-6 col-xs-12">
			<label for="longitud">Longitud</label>
			<input type="text" id="longitud" name="longitud" class="form-control"/>
		@if ($errors->has('longitud'))
			<span class="help-block">
				<strong>{{ $errors->first('longitud') }}</strong>
			</span>
		@endif
	</div>
</div>
<div class="row">
	<div class="form-group col-md-6 col-xs-12">
		<label for="fk_id_tipo_sucursal">Tipo Sucursal</label>
		<select name="fk_id_tipo_sucursal" id="fk_id_tipo_sucursal" class="form-control"></select>
	</div>
	<div class="form-group col-md-6 col-xs-12">
		<label for="registro_sanitario">Registro Sanitario</label>
		<input type="text" name="registro_sanitario" id="registro_sanitario" class="form-control">
		@if ($errors->has('registro_sanitario'))
			<span class="help-block">
				<strong>{{ $errors->first('registro_sanitario') }}</strong>
			</span>
		@endif
	</div>
</div>
<div class="row">
	<div class="form-group col-md-4 col-xs-12">
		<label for="fk_id_cliente">Cliente</label>
		<select name="fk_id_cliente" id="fk_id_cliente" class="form-control"></select>
	</div>
	<div class="form-group col-md-4 col-xs-12">
		<label for="fk_id_localidad">Localidad</label>
		<select name="fk_id_localidad" id="fk_id_localidad" class="form-control"></select>
	</div>
	<div class="form-check col-md-4 col-xs-12">
		<p>
			<input type="checkbox" id="embarque" name="embarque" />
			<label for="embarque">Embarque</label>
		</p>
	</div>
</div>
<div class="row">
	<div class="form-group col-md-4 col-xs-12">
		<label for="fk_id_municipio">Municipio</label>
		<select name="fk_id_municipio" id="fk_id_municipio" class="form-control"></select>
	</div>
	<div class="form-group col-md-4 col-xs-12">
		<label for="fk_id_estado">Estado</label>
		<select name="fk_id_estado" id="fk_id_estado" class="form-control"></select>
	</div>
	<div class="form-group col-md-4 col-xs-12">
		<label for="fk_id_pais">País</label>
		<select name="fk_id_pais" id="fk_id_pais" class="form-control"></select>
	</div>
</div>
<div class="row">
	<div class="form-group col-md-6 col-xs-12">
		<label for="calle">Calle</label>
		<input type="text" name="calle" id="calle" class="form-control">
		@if ($errors->has('calle'))
			<span class="help-block">
				<strong>{{ $errors->first('calle') }}</strong>
			</span>
		@endif
	</div>
	<div class="form-group col-md-3 col-xs-12">
		<label for="no_exterior">Número exterior</label>
		<input type="text" name="no_exterior" id="no_exterior" class="form-control">
		@if ($errors->has('no_exterior'))
			<span class="help-block">
				<strong>{{ $errors->first('no_exterior') }}</strong>
			</span>
		@endif
	</div>
	<div class="form-group col-md-3 col-xs-12">
		<label for="no_interior">Número Interior</label>
		<input type="text" name="no_interior" id="no_interior" class="form-control">
		@if ($errors->has('no_interior'))
			<span class="help-block">
				<strong>{{ $errors->first('no_interior') }}</strong>
			</span>
		@endif
	</div>
</div>
<div class="row">
	<div class="form-group col-md-4 col-xs-12">
		<label for="telefono1">Teléfono 1</label>
		<input type="text" name="telefono1" id="telefono1" class="form-control">
		@if ($errors->has('telefono1'))
			<span class="help-block">
				<strong>{{ $errors->first('telefono1') }}</strong>
			</span>
		@endif
	</div>
	<div class="form-group col-md-4 col-xs-12">
		<label for="telefono2">Teléfono 2</label>
		<input type="text" name="telefono2" id="telefono2" class="form-control">
		@if ($errors->has('telefono2'))
			<span class="help-block">
				<strong>{{ $errors->first('telefono2') }}</strong>
			</span>
		@endif
	</div>
	<div class="form-group col-md-4 col-xs-12">
		<label for="clave_presupuestal">Clave Presupuestal</label>
		<input type="text" name="clave_presupuestal" id="clave_presupuestal" class="form-control">
		@if ($errors->has('clave_presupuestal'))
			<span class="help-block">
				<strong>{{ $errors->first('clave_presupuestal') }}</strong>
			</span>
		@endif
	</div>
</div>
<div class="col-md-12 col-xs-12">
	<h6>Datos militares</h6>
</div>
<div class="row">
	<div class="form-group col-md-4 col-xs-12">
		<label for="tipo_batallon">Tipo de batallón</label>
		<input type="text" name="tipo_batallon" id="tipo_batallon" class="form-control">
		@if ($errors->has('tipo_batallon'))
			<span class="help-block">
				<strong>{{ $errors->first('tipo_batallon') }}</strong>
			</span>
		@endif
	</div>
	<div class="form-group col-md-4 col-xs-12">
		<label for="region">Región</label>
		<input type="text" name="region" id="region" class="form-control">
		@if ($errors->has('region'))
			<span class="help-block">
				<strong>{{ $errors->first('region') }}</strong>
			</span>
		@endif
	</div>
	<div class="form-group col-md-4 col-xs-12">
		<label for="zona_militar">Zona Militar</label>
		<input type="text" name="zona_militar" id="zona_militar" class="form-control">
		@if ($errors->has('zona_militar'))
			<span class="help-block">
				<strong>{{ $errors->first('zona_militar') }}</strong>
			</span>
		@endif
	</div>
</div>


<div class="row">
	<div class="form-group col-md-12 col-xs-12">
		{{ Form::label('nombre', 'Parentesco:') }}
		{{ Form::text('nombre', null, ['id'=>'nombre','class'=>'form-control']) }}
		{{ $errors->has('nombre') ? HTML::tag('span', $errors->first('nombre'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
</div>
	<div class="row">
		<div class="form-check col-md-6 col-xs-12">
			{{ Form::hidden('activo', 0) }}
			{{ Form::checkbox('activo', 1, old('activo'), ['id'=>'activo']) }}
			{{ Form::label('activo', '¿Activo?') }}
			{{ $errors->has('activo') ?  HTML::tag('span', $errors->first('activo'), ['class'=>'help-block deep-orange-text']) : '' }}
		</div>
	</div>
@endsection

{{-- DONT DELETE --}}
@if (Route::currentRouteNamed(currentRouteName('index')))
	@include('layouts.smart.index')
@endif

@if (Route::currentRouteNamed(currentRouteName('create')))
	@include('layouts.smart.create')
@endif

@if (Route::currentRouteNamed(currentRouteName('edit')))
	@include('layouts.smart.edit')
@endif

@if (Route::currentRouteNamed(currentRouteName('show')))
	@include('layouts.smart.show')
@endif

@if (Route::currentRouteNamed(currentRouteName('export')))
	@include('layouts.smart.export')
@endif