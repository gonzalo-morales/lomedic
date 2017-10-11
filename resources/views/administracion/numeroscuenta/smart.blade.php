
@section('content-width', 's12')

@section('form-content')
{{ Form::setModel($data) }}

<div class="row">
	<div class="form-group col-md-12 col-xs-12">
		{{ Form::label('numero_cuenta', 'Número de cuenta') }}
		{{ Form::text('numero_cuenta', null, ['id'=>'numero_cuenta','class'=>'form-control','placeholder'=>'No. de cuenta']) }}
		{{ $errors->has('numero_cuenta') ? HTML::tag('span', $errors->first('numero_cuenta'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
	<div class="form-group col-md-4 col-xs-12">
		{{ Form::label('fk_id_banco', 'Banco') }}
		{{ Form::select('fk_id_banco', ($bancos ?? []), null, ['id'=>'fk_id_banco', 'class'=>'form-control']) }}
		{{ $errors->has('fk_id_banco') ? HTML::tag('span', $errors->first('fk_id_banco'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
	<div class="form-group col-md-4 col-xs-12">
		{{ Form::label('fk_id_sat_moneda', 'Moneda') }}
		{{ Form::select('fk_id_sat_moneda', ($monedas ?? []), null, ['id'=>'fk_id_sat_moneda', 'class'=>'form-control']) }}
		{{ $errors->has('fk_id_sat_moneda') ? HTML::tag('span', $errors->first('fk_id_sat_moneda'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
	<div class="form-group col-md-4 col-xs-12">
		{{ Form::label('fk_id_empresa', 'Empresa') }}
		{{ Form::select('fk_id_empresa', ($companies ?? []), null, ['id'=>'fk_id_empresa', 'class'=>'form-control']) }}
		{{ $errors->has('fk_id_empresa') ? HTML::tag('span', $errors->first('fk_id_empresa'), ['class'=>'help-block deep-orange-text']) : '' }}
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