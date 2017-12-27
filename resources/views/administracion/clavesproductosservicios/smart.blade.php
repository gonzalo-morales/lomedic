
@section('content-width', 's12')

@section('header-bottom')
	@parent
	@if (!Route::currentRouteNamed(currentRouteName('index')))
	<script type="text/javascript">
        // Inicializar los datepicker para las fechas necesarias
        $('.datepicker').pickadate({
            selectMonths: true, // Creates a dropdown to control month
            selectYears: 3, // Creates a dropdown of 3 years to control year
            format: 'yyyy-mm-dd'
        });
    </script>
    @endif
@endsection

@section('form-content')
{{ Form::setModel($data) }}
<div class="row">
	<div class="form-group col-6">
		{{ Form::cText('* Clave del producto y/o servicio','clave_producto_servicio') }}
	</div>
	<div class="form-group col-6">
		{!! Form::cText('Vigencia','vigencia',['class'=>'datepicker']) !!}
	</div>
	<div class="form-group col-sm-12">
		{{ Form::cText('* Descripción','descripcion') }}
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