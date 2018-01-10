@section('content-width', 's12 ml2')

@section('form-content')
    {{ Form::setModel($data) }}
    <div class="row">
    	<div class="form-group col-md-4 col-xs-12">
    		{{ Form::cText('* Nombres','nombre') }}
    	</div>
    	<div class="form-group col-md-4 col-xs-12">
    		{{ Form::cText('* Apellido Paterno','apellido_paterno') }}
    	</div>
    	<div class="form-group col-md-4 col-xs-12">
    		{{ Form::cText('Apellido Materno','apellido_materno') }}
    	</div>
    </div>
    <div class="row">
    	<div class="form-group col-md-4 col-xs-12">
    		{{ Form::cText('* Fecha Nacimiento','fecha_nacimiento') }}
    	</div>
    	<div class="form-group col-md-4 col-xs-12">
    		{{ Form::cText('* Curp','curp') }}
    	</div>
    	<div class="form-group col-md-4 col-xs-12">
    		{{ Form::cText('* Rfc','rfc') }}
    	</div>
    </div>
    <div class="row">
    	<div class="form-group col-md-4 col-xs-12">
    		{{ Form::cText('Correo Personal','correo_personal') }}
    	</div>
    	<div class="form-group col-md-4 col-xs-12">
    		{{ Form::cText('Telefono','telefono') }}
    	</div>
    	<div class="form-group col-md-4 col-xs-12">
    		{{ Form::cText('Celular','celular') }}
    	</div>
    </div>
    <div class="row">
    	<div class="form-group col-md-4 col-xs-12">
    		{{Form::cSelect('* Empresa Alta Imss','fk_id_empresa_alta_imss', $companies ?? [],['class'=>'select2'])}}
    	</div>
    	<div class="form-group col-md-4 col-xs-12">
    		{{ Form::cNumber('* Numero Imss','numero_imss') }}
    	</div>
    	<div class="form-group col-md-4 col-xs-12">
    		{{Form::cSelect('* Empresa Laboral','fk_id_empresa_laboral', $companies ?? [],['class'=>'select2'])}}
    	</div>
    </div>
    <div class="row">
    	<div class="form-group col-md-4 col-xs-12">
    		{{Form::cSelect('* Departamento','fk_id_departamento', $departments ?? [],['class'=>'select2'])}}
    	</div>
    	<div class="form-group col-md-4 col-xs-12">
    		{{Form::cSelect('* Puesto','fk_id_puesto', $titles ?? [],['class'=>'select2'])}}
    	</div>
    	<div class="form-group col-md-4 col-xs-12">
    		{{Form::cSelect('* Sucursal','fk_id_sucursal', $offices ?? [],['class'=>'select2'])}}
    	</div>
    </div>
    <div class="row">
    	<div class="form-group col-md-6 col-xs-12">
    		{{ Form::cNumber('Factor Descuento','factor_descuento') }}
    	</div>
    	<div class="form-group col-md-6 col-xs-12">
    		{{ Form::cNumber('Numero Infonavit','numero_infonavit') }}
    	</div>
    </div>
    <div  class="col-md-12 text-center mt-4">
    	<div class="alert alert-warning" role="alert">
            Recuerda que al no estar <b>activo</b>, este <b>dato</b> no se mostrara en los modulos correspondientes que se requieran.
        </div>
        {{ Form::cCheckboxBtn('Estatus','Activo','activo', $data['activo'] ?? null, 'Inactivo') }}
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