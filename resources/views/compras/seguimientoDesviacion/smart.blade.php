@section('header-bottom')
	@parent
	@if (!Route::currentRouteNamed(currentRouteName('index')))
		{{HTML::script(asset('js/seguimientoDesviacion.js'))}}
	@endif
@endsection

@section('content-width', 's12')

@section('form-content')
{{ Form::setModel($data) }}
{{--{{dd($data)}}--}}
@if (Route::currentRouteNamed(currentRouteName('show')) || Route::currentRouteNamed(currentRouteName('edit')))
	<div class="row">
		<div class="col-md-12 text-center text-success">
			{{-- <h3>Autorización de la {{$data->fk_id_tipo_documento == 10 ? 'solicitud de pago' : 'orden de compra'}} No. {{$data->fk_id_documento}}</h3> --}}
		</div>
	</div>
@endif
@if (!Route::currentRouteNamed(currentRouteName('index')))
	<div class="row">
		<div class="form-group col-md-4 col-sm-6">
			{{ Form::cSelect('Proveedor','fk_id_proveedor',$proveedores,['data-url'=>companyAction('Compras\SeguimientoDesviacionesController@getDocumentos'),'class'=>'form-control','']) }}
			{{ Form::cRadio('Autorizar?','fk_id_estatus',[4=>'Autorizado',3=>'No Autorizado']) }}
		</div>
		<div class="form-group col-md-4 col-sm-6">
			{{ Form::cSelect('Tipo de Documento','tipo_documento',['-1'=>'Selecciona una opcion...','3'=>'Orden de Compra','7'=>'Factura']) }}
		</div>
		<div class="form-group col-md-4 col-sm-6">
			{{ Form::cSelect('No. Documento','documentos') }}
		</div>
	</div>
@endif
{{-- {{ Form::cText('Proveedor','fk_id_proveedor') }} --}}

<div class="card-body row table-responsive">
	<table class="table highlight mt-3" id="tContactos">
		<thead>
		<tr>
			<th>ID Detalle Documento</th>
			<th>SKU</th>
			<th>UPC</th>
			<th>Cantidad Documento</th>
			<th>Cantidad Diferencia</th>
			<th>Precio Documento</th>
			<th>Precio Diferencia</th>
			<th></th>
		</tr>
		</thead>
		<tbody class="desviacion_detail">
		@if(isset($data->detalles))
			@foreach($data->detalles->where('eliminar',false ) as $row => $detalle)

				<tr id="{{$detalle->producto['id_sku']}}">
					<th scope="row">{{$detalle->producto['id_sku']}}</th>
					<td>
						<input name="relations[has][detalles][{{$row}}][id_receta_detalle]" type="hidden" value="{{$detalle->id_receta_detalle}}">
						<p><input id="clave_cliente" name="relations[has][detalles][{{$row}}][fk_id_clave_cliente_producto]" type="hidden" value="{{$detalle->producto['id_sku']}}">{{$detalle->producto['descripcion']}}</p>
						<p><input id="tbdosis" name="relations[has][detalles][{{$row}}][dosis]" type="hidden" value="{{$detalle->dosis}}">{{$detalle->dosis}}</p>
						<input id="tbveces_surtir" name="relations[has][detalles][{{$row}}][veces_surtir]" type="hidden" value="{{$detalle->veces_surtidas}}">
					</td>
					<td>
						<a data-delete-type="single"  data-toggle="tooltip" data-placement="top" title="Borrar"  id="{{$row}}" aria-describedby="tooltip687783" onclick="eliminarFila(this)" ><i class="material-icons text-primary">delete</i></a>
					</td>
				</tr>
			@endforeach
		@endif
		</tbody>
	</table>
</div>
@endsection

{{-- DONT DELETE --}}
@if (Route::currentRouteNamed(currentRouteName('index')))
	@section('form-title')
		<h1 class="display-4">Seguimiento Desviación</h1>
	@endsection
	@include('layouts.smart.index')
@endif

@if (Route::currentRouteNamed(currentRouteName('create')))
	@section('form-title')
		<h1 class="display-4">Desviación</h1>
	@endsection
	@include('layouts.smart.create')
@endif

@if (Route::currentRouteNamed(currentRouteName('edit')))
	@section('form-title')
		<h1 class="display-4">Desviación</h1>
	@endsection
	@include('layouts.smart.edit')
@endif

@if (Route::currentRouteNamed(currentRouteName('show')))
	@section('form-title')
		<h1 class="display-4">Seguimiento Desviación</h1>
	@endsection
	@include('layouts.smart.show')
@endif
