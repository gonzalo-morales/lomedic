@section('content-width', 's12')

@section('form-content')
	{{ Form::setModel($data) }}
    <div class="row">
    	<div class="form-group col-md-6">
    		{{Form::cSelectWithDisabled('* Cliente','fk_id_cliente',$clientes ?? [],['class'=>'select2'])}}
    	</div>
    	<div class="form-group col-md-3">
			{{Form::cText('* Clave','clave_producto_cliente')}}
		</div>
		<div class="form-group col-md-3">
			{{Form::cText('* Subclave','subclave')}}
		</div>
    	<div class="form-group col-md-6">
    		{{Form::cText('* Descripcion','descripcion')}}
    	</div>
    	<div class="form-group col-md-2">
			{{Form::cText('* Presentacion','presentacion')}}
		</div>
		<div class="form-group col-md-2">
			{{Form::cText('* Cantidad Presentacion','cantidad_presentacion')}}
		</div>
		<div  class="col-md-2 text-center mt-4">
    		{{ Form::cCheckboxBtn('Dentro de Cuadro','Si','pertenece_cuadro', $data['activo'] ?? null, 'No') }}
    	</div>
		<div class="form-group col-md-6">
    		{{Form::cSelectWithDisabled('* Sku','fk_id_sku',$skus ?? [],['class'=>'select2'])}}
    	</div>
		<div class="form-group col-md-6">
    		{{Form::cSelectWithDisabled('Upc','fk_id_upc',$upcs ?? [],['class'=>'select2'])}}
    	</div>
		<div class="form-group col-md-4">
			{{Form::cText('* Marca','marca')}}
		</div>
		<div class="form-group col-md-4">
			{{Form::cText('* Fabricante','fabricante')}}
		</div>
		<div class="form-group col-md-4">
    		{{Form::cSelectWithDisabled('* Unidad Medida','fk_id_unidad_medida',$unidadesmedidas ?? [],['class'=>'select2'])}}
    	</div>
		<div class="form-group col-md-4">
    		{{Form::cSelectWithDisabled('* Clave Producto SAT','fk_id_clave_producto_servicio',$clavesproductos ?? [],['class'=>'select2'])}}
    	</div>
		<div class="form-group col-md-3">
    		{{Form::cSelectWithDisabled('* Clave Unidad Medida','fk_id_clave_unidad',$clavesunidades ?? [],['class'=>'select2'])}}
    	</div>
		<div class="form-group col-md-3">
			{{Form::cNumber('* Precio','precio')}}
		</div>
		<div class="form-group col-md-2">
			{{Form::cNumber('* Precio Referencia','precio_referencia')}}
		</div>
		<div class="form-group col-md-3">
			{{Form::cNumber('* Descuento','descuento')}}
		</div>
		<div class="form-group col-md-3">
			{{Form::cNumber('* Descuento Porcentaje','descuento_porcentaje')}}
		</div>
		<div class="form-group col-md-3">
    		{{Form::cSelectWithDisabled('* Impuesto','fk_id_impuesto',$impuestos ?? [],['class'=>'select2'])}}
    	</div>
		<div class="form-group col-md-3">
			{{Form::cNumber('* Dispensacion','dispensacion')}}
		</div>
		<div class="form-group col-md-3">
			{{Form::cNumber('* Dispensacion Porcentaje','dispensacion_porcentaje')}}
		</div>
		<div class="form-group col-md-2">
			{{Form::cNumber('* Minimo','minimo')}}
		</div>
		<div class="form-group col-md-2">
			{{Form::cNumber('* Maximo','maximo')}}
		</div>
		<div class="form-group col-md-2">
			{{Form::cNumber('* Tope Receta','tope_receta')}}
		</div>
		<div class="form-group col-md-3">
			{{Form::cNumber('* Disponibilidad','disponibilidad',['disabled'=>true])}}
		</div>
    	<div  class="col-md-12 text-center mt-4">
    		<div class="alert alert-warning" role="alert">
    			Recuerda que al no estar <b>activo</b>, este <b>dato</b> no se mostrará en los modulos correspondientes que se requieran.
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