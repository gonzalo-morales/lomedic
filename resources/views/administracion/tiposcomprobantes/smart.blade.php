
@section('content-width')

@section('form-content')
{{ Form::setModel($data) }}
<div class="row">
	<div class="form-group col-sm-6 col-md-3">
		{{ Form::label('tipo_comprobante', '* Tipo Comprobante:') }}
		{{ Form::text('tipo_comprobante', null, ['id'=>'tipo_comprobante','class'=>'form-control']) }}
		{{ $errors->has('tipo_comprobante') ? HTML::tag('span', $errors->first('tipo_comprobante'), ['class'=>'help-block text-danger']) : '' }}
	</div>
	<div class="form-group col-sm-6 col-md-3">
		{{ Form::label('limite', '* Límite:') }}
		{{ Form::number('limite', null, ['id'=>'limite','class'=>'form-control']) }}
		{{ $errors->has('limite') ? HTML::tag('span', $errors->first('limite'), ['class'=>'help-block text-danger']) : '' }}
	</div>
	<div class="form-group col-md-6 col-sm-12">
		{{ Form::label('descripcion', '* Descripción:') }}
		{{ Form::text('descripcion', null, ['id'=>'descripcion','class'=>'form-control']) }}
		{{ $errors->has('descripcion') ? HTML::tag('span', $errors->first('descripcion'), ['class'=>'help-block text-danger']) : '' }}
	</div>
	<div  class="col-md-12 text-center mt-2">
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