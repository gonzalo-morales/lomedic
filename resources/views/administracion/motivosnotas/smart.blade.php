
@section('content-width', 's12')

@section('form-content')
{{ Form::setModel($data) }}
<div class="row">
	<div class="form-group col-md-6 col-xs-12">
		{{ Form::label('motivo', 'Motivo') }}
		{{ Form::text('motivo', null, ['id'=>'motivo','class'=>'form-control']) }}
		{{ $errors->has('motivo') ? HTML::tag('span', $errors->first('motivo'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
	<div class="form-group col-md-6 col-xs-12">
		{{ Form::label('tipo', 'Tipo') }}
		{{ Form::select('tipo', ['1' => 'Cuentas por Pagar', '2' => 'Cuentas por Cobrar'], null, ['id'=>'tipo','class'=>'form-control']) }}
		{{ $errors->has('tipo') ? HTML::tag('span', $errors->first('tipo'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
	<div  class="col-md-12 text-center mt-4">
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