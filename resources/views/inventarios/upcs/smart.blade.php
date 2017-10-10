
@section('content-width', 's12')

@section('header-bottom')
    @parent
    <script type="text/javascript">
		$(document).ready(function() {
        	$("#fk_id_presentacion_venta>option[value='']").attr('disabled',true);
        	$("#fk_id_laboratorio>option[value='']").attr('disabled',true);
        	$("#fk_id_pais_origen>option[value='']").attr('disabled',true);
        	
        	$("#fk_id_presentacion_venta").select2();
        	$("#fk_id_laboratorio").select2();
        	$("#fk_id_pais_origen").select2();

        	@if(Route::currentRouteNamed(currentRouteName('show')))
            	$("#fk_id_presentacion_venta").prop("disabled", true);
            	$("#fk_id_laboratorio").prop("disabled", true);
            	$("#fk_id_pais_origen").prop("disabled", true);
            @endif
        });
    </script>
@endsection

@section('form-content')
{{ Form::setModel($data) }}
    <div class="row">
    	<div class="col-sm-12 col-md-6 col-lg-4">
    		<div class="form-group">
    			{{ Form::cText('Upc', 'upc') }}
    		</div>
    	</div>
    	<div class="col-sm-12 col-md-6 col-lg-4">
    		<div class="form-group">
    			{{ Form::cText('Registro Sanitario', 'registro_sanitario') }}
    		</div>
    	</div>
    	<div class="col-sm-12 col-md-6 col-lg-4">
    		<div class="form-group">
    			{{ Form::cText('Nombre Comercial', 'nombre_comercial') }}
    		</div>
    	</div>
    
    	<div class="col-sm-12 col-md-6 col-lg-4">
    		<div class="form-group">
    			{{ Form::cText('Marca', 'marca') }}
    		</div>
    	</div>
    	<div class="col-sm-12 col-md-6 col-lg-4">
    		<div class="form-group">
    			{{ Form::cSelect('Presentacion Venta', 'fk_id_presentacion_venta', $presentaciones ?? []) }}
    		</div>
    	</div>
    	<div class="col-sm-12 col-md-6 col-lg-4">
    		<div class="form-group">
    			{{ Form::cSelect('Laboratorio', 'fk_id_laboratorio', $laboratorios ?? []) }}
    		</div>
    	</div>

    	<div class="col-sm-12 col-md-8 col-lg-4">
    		<div class="form-group">
    			{{ Form::cSelect('Pais Origen', 'fk_id_pais_origen', $paises ?? []) }}
    		</div>
    	</div>
    	
    	<div class="col-sm-6 col-md-4 col-lg-2">
    		<div class="form-group">
    			{{ Form::cText('Peso', 'peso') }}
    		</div>
    	</div>
    	<div class="col-sm-6 col-md-4 col-lg-2">
    		<div class="form-group">
    			{{ Form::cText('Longitud', 'longitud') }}
    		</div>
    	</div>
    	<div class="col-sm-6 col-md-4 col-lg-2">
    		<div class="form-group">
    			{{ Form::cText('Ancho', 'ancho') }}
    		</div>
    	</div>
    	<div class="col-sm-6 col-md-4 col-lg-2">
    		<div class="form-group">
    			{{ Form::cText('Altura', 'altura') }}
    		</div>
    	</div>
    
    	<div class="col-sm-6 col-md-6 col-lg-4">
    		{{ Form::cCheckbox('Producto Descontinuado', 'descontinuado') }}
    	</div>
    	
    	<div class="col-sm-6 col-md-6 col-lg-4">
    		{{ Form::cCheckbox('Activo', 'activo') }}
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