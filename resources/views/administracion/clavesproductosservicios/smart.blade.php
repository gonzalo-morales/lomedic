
@section('content-width', 's12')

@section('form-content')
{{ Form::setModel($data) }}
<div class="row">
	<div class="form-group col-6">
		{{ Form::label('clave_producto_servicio', '* Clave del producto y/o servicio') }}
		{{ Form::text('clave_producto_servicio', null, ['id'=>'clave_producto_servicio','class'=>'form-control']) }}
		{{ $errors->has('clave_producto_servicio') ? HTML::tag('span', $errors->first('clave_producto_servicio'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
	<div class="form-group col-6">
		{{ Form::label('vigencia', 'Vigencia') }}
		{!! Form::text('vigencia',null,['id'=>'vigencia','class'=>'datepicker form-control','value'=>old('vigencia'),'placeholder'=>'Selecciona una fecha']) !!}
	</div>
	<div class="form-group col-sm-12">
		{{ Form::label('descripcion', '* Descripción') }}
		{{ Form::text('descripcion', null, ['id'=>'descripcion','class'=>'form-control']) }}
		{{ $errors->has('descripcion') ? HTML::tag('span', $errors->first('descripcion'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
	<div  class="col-12 text-center mt-2">
		<div class="alert alert-warning" role="alert">
            Recuerda que al no estar <b>activo</b>, este <b>dato</b> no se mostrara en los modulos correspondientes que se requieran.
        </div>
        {{ Form::cCheckboxBtn('Estatus','Activo','activo', $data['activo'] ?? null, 'Inactivo') }}
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