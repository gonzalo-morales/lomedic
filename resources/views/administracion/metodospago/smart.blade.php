
@section('content-width', 's12')

@section('form-content')
{{ Form::setModel($data) }}
<div class="row">
	<div class="form-group col-md-6 col-xs-12">
		{{ Form::label('metodo_pago', 'Método de pago') }}
		{{ Form::text('metodo_pago', null, ['id'=>'metodo_pago','class'=>'form-control']) }}
		{{ $errors->has('metodo_pago') ? HTML::tag('span', $errors->first('metodo_pago'), ['class'=>'help-block text-danger']) : '' }}
	</div>
	<div class="form-group col-md-6 col-xs-12">
		{{ Form::label('descripcion', 'Descripción') }}
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