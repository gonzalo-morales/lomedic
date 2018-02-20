{{-- @section('header-bottom')
	@parent
	@if (!Route::currentRouteNamed(currentRouteName('index')))
		{{HTML::script(asset('js/seguimientoDesviacion.js'))}}
	@endif
@endsection

@section('content-width', 's12') --}}

{{-- @extends(smart()) --}}
@section('content-width', 's12')
@section('form-title', 'Seguimiento Desviación')

{{-- @section('header-top')
	<link rel="stylesheet" href="{{ asset('vendor/vanilla-datatables/vanilla-dataTables.css') }}">
@endsection --}}
@section('header-bottom')
	@parent
	{{-- <script src="{{ asset('vendor/vanilla-datatables/vanilla-dataTables.js') }}"></script> --}}
	@if (!Route::currentRouteNamed(currentRouteName('index')))
		<script type="text/javascript" src="{{ asset('js/seguimientoDesviacion.js') }}"></script>
	@endif
@endsection

@section('form-actions')
   <div class="col-md-12 col-xs-12">
       <div class="text-right">
		   {{-- @if(!Route::currentRouteNamed(currentRouteName('create')))
	           @can('create', currentEntity())
	               {{ link_to(companyRoute('create'), 'Nuevo', ['class'=>'btn btn-primary progress-button']) }}
	           @endcan
		   @else
			   {{ Form::button('Guardar', ['type' =>'submit', 'class'=>'btn btn-primary progress-button']) }}
		   @endif
		   @if(Route::currentRouteNamed(currentRouteName('show')))
	           @can('update', currentEntity())
	                   {{ link_to(companyRoute('edit'), 'Editar', ['class'=>'btn btn-info progress-button']) }}
			   @endcan
		   @endif
		   @if(Route::currentRouteNamed(currentRouteName('update')))
		   		{{ Form::button('Guardar', ['type' =>'submit', 'class'=>'btn btn-primary progress-button']) }}
		   @endif --}}
           {{ link_to(companyRoute('index'), 'Cerrar', ['class'=>'btn btn-default progress-button']) }}
       </div>
   </div>
@endsection

@section('form-content')
{{ Form::setModel($data) }}
{{--{{dd($data)}}--}}
@if (Route::currentRouteNamed(currentRouteName('show')) || Route::currentRouteNamed(currentRouteName('edit')))
<div class="row">
	<div class="col-sm-12">
		{{-- <div class="card z-depth-1-half"> --}}
		{{-- <div class="text-right">
			<a href="#" class="btn p-2 btn-dark" id="reload"><i class="material-icons align-middle">cached</i>Recargar</a>
		</div> --}}
	<div class="card-body row table-responsive table-hover">
		<table class="table highlight mt-3" id="tDetalleDesviacion">
			<thead>
			<tr>
				<th>ID</th>
				<th>OC</th>
				<th>Factura</th>
				<th>SKU</th>
				<th>UPC</th>
				<th>Descripción</th>
				<th>Precio OC</th>
				<th>Precio Factura</th>
				<th>Precio Desviación</th>
				<th>Cantidad OC</th>
				<th>Cantidad Entrada</th>
				<th>Cantidad Desviación</th>
				<th>Estatus</th>
				<th></th>
			</tr>
			</thead>
			<tbody class="desviacion_detail">
				@foreach ($detalleDesviacion as $detDesviacion)
					@if ($data->id_seguimiento_desviacion === $detDesviacion->fk_id_seguimiento_desviacion)


					<tr>
						<td>{{ $detDesviacion->id_detalle_seguimiento_desviacion }}</td>
						<td>{{ $detDesviacion->fk_id_orden_compra }}</td>
						@isset($detDesviacion->facturaProveedor->serie_factura)
							<td>
								({{ $detDesviacion->fk_id_factura_proveedor }})
								{{ $detDesviacion->facturaProveedor->serie_factura . $detDesviacion->facturaProveedor->folio_factura }}</td>
						@else
							<td> - </td>
						@endisset
						<td>{{ $detDesviacion->detalleOrden->sku->sku}}</td>
						<td>{{ $detDesviacion->detalleOrden->upc->upc}}</td>
						<td>{{ $detDesviacion->detalleOrden->sku->descripcion_corta}}</td>
						<td>{{ $detDesviacion->precio_orden_compra }}</td>
						<td>{{ $detDesviacion->precio_factura }}</td>
						<td>{{ $detDesviacion->precio_desviacion }}</td>
						<td>{{ $detDesviacion->cantidad_orden_compra }}</td>
						<td>{{ $detDesviacion->cantidad_entrada }}</td>
						<td>{{ $detDesviacion->cantidad_desviacion }}</td>
							{{-- {{ $detDesviacion->fk_id_estatus }} --}}
						@if($detDesviacion->fk_id_estatus == 2)
							<td class="text-info">{{$detDesviacion->estatus->estatus}}
								{{ Form::hidden('',$detDesviacion->fk_id_estatus) }}
								{{ Form::hidden('',$detDesviacion->observaciones) }}
							</td>
						@elseif($detDesviacion->fk_id_estatus == 3 OR $detDesviacion->fk_id_estatus == 5)
							<td class="text-danger">{{$detDesviacion->estatus->estatus}}
								{{ Form::hidden('',$detDesviacion->fk_id_estatus) }}
								{{ Form::hidden('',$detDesviacion->observaciones) }}
							</td>
						@elseif($detDesviacion->fk_id_estatus == 4)
							<td class="text-success">{{$detDesviacion->estatus->estatus}}
								{{ Form::hidden('',$detDesviacion->fk_id_estatus) }}
								{{ Form::hidden('',$detDesviacion->observaciones) }}
							</td>
						@endif
						<td></td>
						<td>
							<button class="desviacion btn is-icon text-primary bg-white " type="button" data-item-id="{{$detDesviacion->getKey()}}" id="{{$detDesviacion->getKey()}}" data-delay="50" onclick="autorizar(this)" data-delete-type="single">
								<i class="material-icons">feedback</i>
							</button>
						</td>
					</tr>
					@endif
				@endforeach

			</tbody>
		</table>
	</div>
	{{-- </div> --}}
	</div>
	</div>
@endif
{{-- @if (!Route::currentRouteNamed(currentRouteName('index')))
	<div class="row">
		<div class="form-group col-md-4 col-sm-6">
			{{ Form::cSelect('Proveedor','fk_id_proveedor',$proveedores) }}
		</div>
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
{{-- {{ Form::cText('Proveedor','fk_id_proveedor') }} --}}


@endsection


<div id="autorizacion" class="modal fade " tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">¿Autorizar la desviación?</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					{{Form::hidden('',null,['id'=>'id_detalle_seguimiento_desviacion','data-url'=>companyAction('Compras\DetalleSeguimientoDesviacionController@actualizarEstatus')])}}
					{{-- <div class="form-group col-md-12 col-sm-6">
						{{Form::label('','Tipo de autorizacion')}}
						{{Form::text('',null,['class'=>'form-control','readonly','id'=>'motivo_autorizacion'])}}
					</div> --}}
					<div class="form-group text-center col-md-12 col-sm-6">
						{{Form::cRadio('Estatus','fk_id_estatus',[4=>'Autorizar',3=>'Rechazar'])}}
					</div>
					<div class="form-group col-md-12 col-sm-12">
						{{Form::cTextArea('Motivo','observaciones',['readonly','style'=>'resize:none;'])}}
					</div>
				</div>
			</div>
			<div class="modal-footer">
				@if(Route::currentRouteNamed(currentRouteName('edit')))
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
				<button id="guardar_autorizacion" type="button" class="btn btn-primary">Guardar</button>
				@endif
			</div>
		</div>
	</div>
</div>

{{-- DONT DELETE --}}
@if (Route::currentRouteNamed(currentRouteName('index')))
	@section('smart-js')
		@parent
		<script type="text/javascript">
			rivets.binders['hide-delete'] = {
				bind: function (el) {
					if(el.dataset.fk_id_estatus_orden != 1)
					{
						$(el).hide();
					}
				}
			};
			// rivets.binders['hide-update'] = {
			// 	bind: function (el) {
			// 		if(el.dataset.fk_id_estatus_orden != 1)
			// 		{
			// 			$(el).hide();
			// 		}
			// 	}
			// };
			@can('update', currentEntity())
				window['smart-model'].collections.itemsOptions.edit = {a: {
				'html': '<i class="material-icons">mode_edit</i>',
				'class': 'btn is-icon',
				'rv-get-edit-url': '',
				'rv-hide-update':'',
				'data-toggle':'tooltip',
				'title':'Editar'
			}};
			@endcan
			@can('delete', currentEntity())
				window['smart-model'].collections.itemsOptions.delete = {a: {
				'html': '<i class="material-icons">not_interested</i>',
				'href' : '#',
				'class': 'btn is-icon',
				'rv-on-click': 'actions.showModalCancelar',
				'rv-get-delete-url': '',
				'data-delete-type': 'single',
				'rv-hide-delete':'',
				'data-toggle':'tooltip',
				'title':'Cancelar'
			}};
			@endcan
		</script>
	@endsection


	@section('form-title')
		<h1 class="display-4">Seguimiento Desviación</h1>
	@endsection
	@include('layouts.smart.index')
@endif

@if (Route::currentRouteNamed(currentRouteName('create')))
	@section('form-title')
		<h1 class="display-4">Seguimiento Desviación</h1>
	@endsection
	@include('layouts.smart.create')
@endif

@if (Route::currentRouteNamed(currentRouteName('edit')))
	@section('form-title')
		<h1 class="display-4">Seguimiento Desviación</h1>
	@endsection
	@include('layouts.smart.edit')
@endif

@if (Route::currentRouteNamed(currentRouteName('show')))
	@section('form-title')
		<h1 class="display-4">Seguimiento Desviación</h1>
	@endsection
	@include('layouts.smart.show')
@endif
