@section('header-bottom')
	{{-- @parent --}}
	{{-- @if (!Route::currentRouteNamed(currentRouteName('index'))) --}}
		{{-- {{HTML::script(asset('js/seguimientoDesviacion.js'))}} --}}
	{{-- @endif --}}
@endsection


@section('form-content')
{{-- {{ Form::setModel($data) }} --}}
{{--{{dd($data)}}--}}
{{-- @if (Route::currentRouteNamed(currentRouteName('show')) || Route::currentRouteNamed(currentRouteName('edit')))
	<div class="row">
		<div class="col-md-12 text-center text-success">
			<h3>Autorización de la {{$data->fk_id_tipo_documento == 10 ? 'solicitud de pago' : 'orden de compra'}} No. {{$data->id_documento}}</h3>
		</div>
	</div>
@endif --}}
{{-- @if (!Route::currentRouteNamed(currentRouteName('index')))
	<div class="row">
		data-url="{{ companyAction('Compras\SeguimientoDesviacion@verDetalleDesviacion') }}
		<div class="form-group col-md-4 col-sm-6">
			{{ Form::cSelect('Tipo de Documento','id_tipo_documento',['-1'=>'Selecciona una opcion...','3'=>'Orden de Compra','7'=>'Factura']) }}
		</div>
		<div class="form-group input-field col-md-3 col-sm-6">
			{{Form::label('id_documento','* No. Documento')}}
			{!!Form::select('id_documento',[],null,['id'=>'id_documento','class'=>'form-control','style'=>'width:100%','data-url'=>companyAction('Compras\SeguimientoDesviacionesController@getDocumentos'),
									'data-url-desviaciones'=>companyAction('Compras\SeguimientoDesviacionesController@getDesviaciones')])!!}
		</div>
	</div>
@endif --}}

<div class="card-body row table-responsive table-hover">
	<table class="table highlight mt-3" id="tDetalleDesviacion">
		<thead>
		<tr>
			<th>ID Detalle</th>
			<th>OC</th>
			<th>Factura</th>
			<th>Precio OC</th>
			<th>Precio Factura</th>
			<th>Precio Factura</th>
			<th>Precio Desviación</th>
			<th>Cantidad Factura</th>
			<th>Cantidad Factura</th>
			<th>Cantidad Desviación</th>
			<th></th>
		</tr>
		</thead>
		<tbody class="desviacion_detail">

		</tbody>
	</table>
</div>

@include('layouts.smart.show')
