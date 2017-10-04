
@section('content-width', 's12 m7 xl8 offset-xl2')

@section('form-content')
{{ Form::setModel($data) }}
<div class="row">

	<div class="form-group col-md-4 col-xs-12">
		{{ Form::cText('Sucursal', 'sucursal') }}
	</div>

	<div class="form-group col-md-4 col-xs-12">
		{{ Form::cSelect('Tipo de Sucursal', 'tipo', ['Matriz', 'Farmacia', 'CEDIS']) }}
	</div>

	<div class="form-group col-md-4 col-xs-12">
		{{ Form::cSelect('Localidad', 'fk_id_localidad') }}
	</div>

	<div class="form-group col-md-4 col-xs-12">
		{{ Form::label('fk_id_red', 'Red') }}
		{{ Form::select('fk_id_red', (isset($users) ? $users : []), null, ['id'=>'fk_id_red','class'=>'form-control select']) }}
		{{ $errors->has('fk_id_red') ? HTML::tag('span', $errors->first('fk_id_red'), ['class'=>'help-block error-help-block']) : '' }}
	</div>

	<div class="form-group col-md-4 col-xs-12">
		{{ Form::label('fk_id_cliente', 'Cliente') }}
		{{ Form::select('fk_id_cliente', (isset($users) ? $users : []), null, ['id'=>'fk_id_cliente','class'=>'form-control select']) }}
		{{ $errors->has('fk_id_cliente') ? HTML::tag('span', $errors->first('fk_id_cliente'), ['class'=>'help-block error-help-block']) : '' }}
	</div>

	<div class="form-group col-md-4 col-xs-12">
		{{ Form::label('responsable', 'Responsable') }}
		{{ Form::text('responsable', null, ['id'=>'responsable','class'=>'form-control']) }}
		{{ $errors->has('responsable') ? HTML::tag('span', $errors->first('responsable'), ['class'=>'help-block error-help-block']) : '' }}
	</div>

	<div class="form-group col-md-4 col-xs-12">
		{{ Form::label('telefono_1', 'Teléfono') }}
		{{ Form::text('telefono_1', null, ['id'=>'telefono_1','class'=>'form-control']) }}
		{{ $errors->has('telefono_1') ? HTML::tag('span', $errors->first('telefono_1'), ['class'=>'help-block error-help-block']) : '' }}
	</div>

	<div class="form-group col-md-4 col-xs-12">
		{{ Form::label('telefono_2', 'Teléfono alternativo') }}
		{{ Form::text('telefono_2', null, ['id'=>'telefono_2','class'=>'form-control']) }}
		{{ $errors->has('telefono_2') ? HTML::tag('span', $errors->first('telefono_2'), ['class'=>'help-block error-help-block']) : '' }}
	</div>

	<div class="form-group col-md-4 col-xs-12">
		{{ Form::label('calle', 'Calle') }}
		{{ Form::text('calle', null, ['id'=>'calle','class'=>'form-control']) }}
		{{ $errors->has('calle') ? HTML::tag('span', $errors->first('calle'), ['class'=>'help-block error-help-block']) : '' }}
	</div>

	<div class="form-group col-md-4 col-xs-12">
		{{ Form::label('numero_interior', 'Num. Interior') }}
		{{ Form::text('numero_interior', null, ['id'=>'numero_interior','class'=>'form-control']) }}
		{{ $errors->has('numero_interior') ? HTML::tag('span', $errors->first('numero_interior'), ['class'=>'help-block error-help-block']) : '' }}
	</div>

	<div class="form-group col-md-4 col-xs-12">
		{{ Form::label('numero_exterior', 'Num. Exterior') }}
		{{ Form::text('numero_exterior', null, ['id'=>'numero_exterior','class'=>'form-control']) }}
		{{ $errors->has('numero_exterior') ? HTML::tag('span', $errors->first('numero_exterior'), ['class'=>'help-block error-help-block']) : '' }}
	</div>

	<div class="form-group col-md-4 col-xs-12">
		{{ Form::label('colonia', 'Colonia') }}
		{{ Form::text('colonia', null, ['id'=>'colonia','class'=>'form-control']) }}
		{{ $errors->has('colonia') ? HTML::tag('span', $errors->first('colonia'), ['class'=>'help-block error-help-block']) : '' }}
	</div>

	<div class="form-group col-md-4 col-xs-12">
		{{ Form::label('codigo_postal', 'Codigo Postal') }}
		{{ Form::text('codigo_postal', null, ['id'=>'codigo_postal','class'=>'form-control']) }}
		{{ $errors->has('codigo_postal') ? HTML::tag('span', $errors->first('codigo_postal'), ['class'=>'help-block error-help-block']) : '' }}
	</div>

	<div class="form-group col-md-4 col-xs-12">
		{{ Form::label('fk_id_municipio', 'Municipio') }}
		{{ Form::select('fk_id_municipio', (isset($users) ? $users : []), null, ['id'=>'fk_id_municipio','class'=>'form-control select']) }}
		{{ $errors->has('fk_id_municipio') ? HTML::tag('span', $errors->first('fk_id_municipio'), ['class'=>'help-block error-help-block']) : '' }}
	</div>

	<div class="form-group col-md-4 col-xs-12">
		{{ Form::label('fk_id_estado', 'Estado') }}
		{{ Form::select('fk_id_estado', (isset($users) ? $users : []), null, ['id'=>'fk_id_estado','class'=>'form-control select']) }}
		{{ $errors->has('fk_id_estado') ? HTML::tag('span', $errors->first('fk_id_estado'), ['class'=>'help-block error-help-block']) : '' }}
	</div>

	<div class="form-group col-md-4 col-xs-12">
		{{ Form::label('fk_id_pais', 'Pais') }}
		{{ Form::select('fk_id_pais', (isset($users) ? $users : []), null, ['id'=>'fk_id_pais','class'=>'form-control select']) }}
		{{ $errors->has('fk_id_pais') ? HTML::tag('span', $errors->first('fk_id_pais'), ['class'=>'help-block error-help-block']) : '' }}
	</div>

	<div class="form-group col-md-4 col-xs-12">
		{{ Form::label('latitud', 'Latitud') }}
		{{ Form::text('latitud', null, ['id'=>'latitud','class'=>'form-control']) }}
		{{ $errors->has('latitud') ? HTML::tag('span', $errors->first('latitud'), ['class'=>'help-block error-help-block']) : '' }}
	</div>

	<div class="form-group col-md-4 col-xs-12">
		{{ Form::label('longitud', 'Longitud') }}
		{{ Form::text('longitud', null, ['id'=>'longitud','class'=>'form-control']) }}
		{{ $errors->has('longitud') ? HTML::tag('span', $errors->first('longitud'), ['class'=>'help-block error-help-block']) : '' }}
	</div>

	<div class="form-group col-md-4 col-12">
		{{ Form::label('registro_sanitario', 'Registro sanitario') }}
		{{ Form::text('registro_sanitario', null, ['id'=>'registro_sanitario','class'=>'form-control']) }}
		{{ $errors->has('registro_sanitario') ? HTML::tag('span', $errors->first('registro_sanitario'), ['class'=>'help-block error-help-block']) : '' }}
	</div>

	<div class="form-group col-md-4 col-xs-12">
		{{ Form::cCheckboxYesOrNo('¿Tiene inventario?', 'inventario') }}
	</div>

	<div class="form-group col-md-4 col-xs-12">
		{{ Form::cCheckboxYesOrNo('¿Tiene enbarque?', 'enbarque') }}
	</div>

	<div class="form-group col-md-4 col-xs-12">
		{{ Form::label('tipo_batallon', 'Tipo batallón') }}
		{{ Form::text('tipo_batallon', null, ['id'=>'tipo_batallon','class'=>'form-control']) }}
		{{ $errors->has('tipo_batallon') ? HTML::tag('span', $errors->first('tipo_batallon'), ['class'=>'help-block error-help-block']) : '' }}
	</div>

	<div class="form-group col-md-4 col-xs-12">
		{{ Form::label('region', 'Region') }}
		{{ Form::text('region', null, ['id'=>'region','class'=>'form-control']) }}
		{{ $errors->has('region') ? HTML::tag('span', $errors->first('region'), ['class'=>'help-block error-help-block']) : '' }}
	</div>

	<div class="form-group col-md-4 col-xs-12">
		{{ Form::label('zona_militar', 'Zona militar') }}
		{{ Form::text('zona_militar', null, ['id'=>'zona_militar','class'=>'form-control']) }}
		{{ $errors->has('zona_militar') ? HTML::tag('span', $errors->first('zona_militar'), ['class'=>'help-block error-help-block']) : '' }}
	</div>
</div>
<div  class="col-md-12 text-center mt-4">
	<div class="alert alert-warning" role="alert">
		Recuerda que al no estar <b>activo</b>, este <b>dato</b> no se mostrará en los modulos correspondientes que se requieran.
	</div>
	<div data-toggle="buttons">
		<label class="btn btn-secondary form-check-label {{ !empty($data->activo) || old('activo') ? 'active':''}}">
			{{Form::checkbox('activo',true,old('activo'),['id'=>'activo',Route::currentRouteNamed(currentRouteName('show'))?'disabled':''])}}
			Activo
		</label>
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