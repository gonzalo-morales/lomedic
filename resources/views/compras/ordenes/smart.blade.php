@extends(smart())
@section('content-width', 's12')
@section('form-title', 'Ordenes de Compra')

@section('header-top')
	<link rel="stylesheet" href="{{ asset('vendor/vanilla-datatables/vanilla-dataTables.css') }}">
@endsection
@section('header-bottom')
	@parent
	<script src="{{ asset('vendor/vanilla-datatables/vanilla-dataTables.js') }}"></script>
	@if (!Route::currentRouteNamed(currentRouteName('index')))
		<script type="text/javascript" src="{{ asset('js/ordenes_compras.js') }}"></script>
	@endif
@endsection

@section('form-actions')
	{{-- {{ dd($data) }} --}}
   <div class="col-md-12 col-xs-12">
       <div class="text-right">
		   @if(!Route::currentRouteNamed(currentRouteName('create')))
	           @can('create', currentEntity())
	               {{ link_to(companyRoute('create'), 'Nuevo', ['class'=>'btn btn-primary progress-button']) }}
	           @endcan
		   @else
			   {{ Form::button('Guardar', ['type' =>'submit', 'class'=>'btn btn-primary progress-button']) }}
		   @endif
		   @if(Route::currentRouteNamed(currentRouteName('show')))
	           @can('update', currentEntity())
	               @if($data->fk_id_estatus_autorizacion == 1 || $data->fk_id_estatus_autorizacion == 3)
	                   {{ link_to(companyRoute('edit'), 'Editar', ['class'=>'btn btn-info progress-button']) }}
	               @endif
			   @endcan
		   @endif
		   @if(Route::currentRouteNamed(currentRouteName('edit')))
				@if($data->fk_id_estatus_autorizacion == 1 || $data->fk_id_estatus_autorizacion == 3)
					{{ Form::button('Guardar', ['type' =>'submit', 'class'=>'btn btn-primary progress-button']) }}
				@endif
		   @endif
		   @if(Route::currentRouteNamed(currentRouteName('update')))
		   		{{ Form::button('Guardar', ['type' =>'submit', 'class'=>'btn btn-primary progress-button']) }}
		   @endif
		   {{-- @if(Route::currentRouteNamed(currentRouteName('index')))
			   {{ link_to(companyRoute('edit'), 'Editar', ['class'=>'btn btn-info progress-button']) }}
		   @endif --}}
           {{ link_to(companyRoute('index'), 'Cerrar', ['class'=>'btn btn-default progress-button']) }}
       </div>
   </div>
@endsection

@section('form-content')
{{ Form::setModel($data) }}
@if (Route::currentRouteNamed(currentRouteName('show')) || Route::currentRouteNamed(currentRouteName('edit')))
	<div class="row">
		<div class="col-md-12 text-center text-success">
			<h3>Orden No. {{$data->id_orden}}</h3>
		</div>
	</div>
@endif
<div class="row">
	<div class="form-group col-md-3 col-sm-12">
		{{ Form::label('fk_id_socio_negocio', '* Proveedor a surtir') }}
		@if(Route::currentRouteNamed(currentRouteName('show')))
			{!! Form::select('fk_id_socio_negocio',isset($proveedores)?$proveedores:[],null,['id'=>'fk_id_socio_negocio','class'=>'form-control select2','style'=>'width:100%','data-url'=>companyAction('getProveedores')]) !!}
		@else
			{!! Form::select('fk_id_socio_negocio',[],null,['id'=>'fk_id_socio_negocio','class'=>'form-control select2','style'=>'width:100%','data-url'=>companyAction('getProveedores')]) !!}
		@endif
		{{ $errors->has('fk_id_socio_negocio') ? HTML::tag('span', $errors->first('fk_id_socio_negocio'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
	<div class="form-group col-md-3 col-sm-12">
		{{ Form::label('fk_id_empresa', 'Otra empresa realiza la compra') }}
		<div class="input-group">
			<span class="input-group-addon">
				<input type="checkbox" id="otra_empresa" {{isset($data->fk_id_empresa)?'checked':''}}>
			</span>
			{!! Form::select('fk_id_empresa',isset($companies)?$companies:[],null,['id'=>'fk_id_empresa_','class'=>'form-control','style'=>'width:100%',!isset($data->fk_id_empresa)?'disabled':'']) !!}
		</div>
		{{ $errors->has('fk_id_empresa') ? HTML::tag('span', $errors->first('fk_id_empresa'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
	<div class="form-group text-center col-md-3 col-sm-6">
		{{ Form::label('', 'Días/Fecha') }}
		<div class="input-group">
			{!! Form::text('tiempo_entrega', null,['id'=>'tiempo_entrega','class'=>'form-control','readonly','placeholder'=>'Días para la entrega']) !!}
			{!! Form::text('fecha_estimada_entrega', null,['id'=>'fecha_estimada_entrega','class'=>'form-control','readonly','placeholder'=>'Fecha estimada']) !!}
		</div>
	</div>
	<div class="form-group col-md-3 col-sm-6">
		{{ Form::label('fk_id_sucursal', '* Sucursal de entrega') }}
		{!! Form::select('fk_id_sucursal',isset($sucursales)?$sucursales:[],null,['id'=>'fk_id_sucursal_','class'=>'form-control select2','style'=>'width:100%']) !!}
		{{ $errors->has('fk_id_sucursal') ? HTML::tag('span', $errors->first('fk_id_sucursal'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
	<div class="form-group col-md-3 col-sm-6">
		{{ Form::label('fk_id_condicion_pago', '* Condición de pago') }}
		{!! Form::select('fk_id_condicion_pago',isset($condicionesPago)?$condicionesPago:[],null,['id'=>'fk_id_condicion_pago','class'=>'form-control select2','style'=>'width:100%']) !!}
		{{ $errors->has('fk_id_condicion_pago') ? HTML::tag('span', $errors->first('fk_id_condicion_pago'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
	<div class="form-group col-md-3 col-sm-6">
		{{ Form::label('fk_id_tipo_entrega', '* Tipo de entrega') }}
		{!! Form::select('fk_id_tipo_entrega',isset($tiposEntrega)?$tiposEntrega:[],null,['id'=>'fk_id_tipo_entrega','class'=>'form-control select2','style'=>'width:100%']) !!}
		{{ $errors->has('fk_id_tipo_entrega') ? HTML::tag('span', $errors->first('fk_id_tipo_entrega'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
	<div class="form-group col-md-3 col-sm-6">
		{{Form::cCheckboxYesOrNo('¿Importación?','importacion')}}
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<h3>Detalle de la orden</h3>
		<div class="card z-depth-1-half">
			@if(!Route::currentRouteNamed(currentRouteName('show')))
			<div class="card-header">
				<fieldset name="detalle-form" id="detalle-form">
					<div class="row">
						<div class="form-group input-field col-md-3 col-sm-6">
							{{Form::label('fk_id_sku','* SKU')}}
							{!!Form::select('fk_id_sku',[],null,['id'=>'fk_id_sku','class'=>'form-control','style'=>'width:100%','data-url'=>companyAction('Inventarios\ProductosController@obtenerSkus')])!!}
						</div>
						<div class="form-group input-field col-md-3 col-sm-6">
							{{Form::label('fk_id_upc','UPC')}}
							<div class="input-group">
								<span class="input-group-addon">
									<input type="checkbox" id="activo_upc">
								</span>
								{!! Form::select('fk_id_upc',[],null,['id'=>'fk_id_upc','disabled',
								'data-url'=>companyAction('Inventarios\ProductosController@obtenerUpcs',['id'=>'?id']),
								'class'=>'form-control','style'=>'width:100%']) !!}
							</div>
						</div>
						<div class="form-group input-field col-md-3 col-sm-6">
							{{Form::label('fk_id_cliente','Cliente')}}
							{!!Form::select('fk_id_cliente',isset($clientes)?$clientes:[],null,['id'=>'fk_id_cliente','autocomplete'=>'off','class'=>'form-control','style'=>'width:100%'])!!}
						</div>
						<div class="form-group input-field col-md-3 col-sm-6">
							{{Form::label('fk_id_proyecto','Proyecto')}}
							{!!Form::select('fk_id_proyecto',isset($proyectos)?$proyectos:[],null,['id'=>'fk_id_proyecto','autocomplete'=>'off','class'=>'validate form-control','style'=>'width:100%',])!!}
						</div>
						<div class="form-group input-field col-md-2 col-sm-4">
							{{ Form::label('fecha_necesario', '* ¿Para cuándo se necesita?') }}
							{!! Form::text('fecha_necesario',null,['id'=>'fecha_necesario','class'=>'datepicker form-control','value'=>old('fecha_necesario'),'placeholder'=>'Selecciona una fecha']) !!}
						</div>
						<div class="form-group input-field col-md-2 col-sm-4">
							{{Form::label('cantidad','Cantidad')}}
							{!! Form::text('cantidad','1',['id'=>'cantidad','min'=>'1','class'=>'validate form-control cantidad','autocomplete'=>'off']) !!}
						</div>
						<div class="form-group input-field col-md-2 col-sm-6">
							{{Form::label('fk_id_impuesto','Tipo de impuesto')}}
							{!! Form::select('fk_id_impuesto',[]
                                ,null,['id'=>'fk_id_impuesto',
                                'data-url'=>companyAction('Administracion\ImpuestosController@obtenerImpuestos'),
                                'class'=>'form-control','style'=>'width:100%']) !!}
							{{Form::hidden('impuesto',null,['id'=>'impuesto'])}}
						</div>
						<div class="form-group input-field col-md-2 col-sm-6">
							{{Form::label('precio_unitario','Precio unitario',['class'=>'validate'])}}
							{!! Form::text('precio_unitario',null,['id'=>'precio_unitario','placeholder'=>'0.00','class'=>'validate form-control precio_unitario','autocomplete'=>'off']) !!}
						</div>
						<div class="form-group input-field col-md-2 col-sm-6">
							{{Form::label('descuento','Descuento',['class'=>'validate'])}}
							<div class="input-group">
								{!! Form::text('descuento',null,['id'=>'descuento','placeholder'=>'00.0000','class'=>'form-control','autocomplete'=>'off']) !!}
								{!! Form::label('','%',['class'=>'input-group-addon']) !!}
							</div>
						</div>
						<div class="col-sm-12 text-center">
							<div class="sep">
								<div class="sepBtn">
									<button style="width: 4em; height:4em; border-radius:50%;" class="btn btn-primary btn-large tooltipped "
											data-position="bottom" data-delay="50" data-tooltip="Agregar" type="button" id="agregar"><i
												class="material-icons">add</i></button>
								</div>
							</div>
						</div>
					</div>
				</fieldset>
			</div>
			@endif
			<div class="card-body">
				<table id="productos" class="table-responsive highlight" data-url="{{companyAction('Compras\ordenesController@store')}}"
					   @if(isset($data->id_orden))
					   data-delete="{{companyAction('Compras\OrdenesController@destroyDetail')}}"
					   @endif
					   data-impuestos="{{companyAction('Administracion\ImpuestosController@obtenerImpuestos')}}"
					   data-porcentaje="{{companyAction('Administracion\ImpuestosController@obtenerPorcentaje',['id'=>'?id'])}}">
					<thead>
					<tr>
						<th>Documento</th>
						<th id="idsku">SKU</th>
						<th id="idupc">UPC</th>
						<th id="descripcioncorta">Producto</th>
						<th id="descripcion">Descripción</th>
						<th id="idcliente">Cliente</th>
						<th id="idproyecto" >Proyecto</th>
						<th id="fechanecesario" >Fecha límite</th>
						<th>Cantidad</th>
						<th>Descuento</th>
						<th id="idimpuesto" >Tipo de impuesto</th>
						<th>Precio unitario</th>
						<th>Total</th>
						<th></th>
					</tr>
					</thead>
					<tbody>
					@if(!empty($documento))
						@foreach($detalles_documento as $detalle)
							<tr>
								<td>
									{!! Form::hidden('detalles['.$detalle->getKey().'][fk_id_tipo_documento_parent]',$tipo_documento) !!}
									{!! Form::hidden('detalles['.$detalle->getKey().'][fk_id_documento_parent]',$detalle->fk_id_documento) !!}
									{{isset($detalle->fk_id_documento)?$detalle->fk_id_documento:'N/A'}}
								</td>
								<td>
									{!! Form::hidden('detalles['.$detalle->getKey().'][fk_id_sku]',$detalle->fk_id_sku) !!}
									{{$detalle->sku->sku}}
								</td>
								<td>
									{!! Form::hidden('detalles['.$detalle->getKey().'][fk_id_upc]',$detalle->fk_id_upc) !!}
									{{isset($detalle->fk_id_upc)?$detalle->upc->upc:'UPC no seleccionado'}}
								</td>
								<td>
									{{$detalle->sku->descripcion_corta}}
								</td>
								<td>
									{{$detalle->sku->descripcion}}
								</td>
								<td>
									{!! Form::hidden('detalles['.$detalle->getKey().'][fk_id_cliente]',$detalle->fk_id_cliente) !!}
									{{isset($detalle->cliente->nombre_corto)?$detalle->cliente->nombre_corto:'Sin cliente'}}
								</td>
								<td>
									{!! Form::hidden('detalles['.$detalle->getKey().'][fk_id_proyecto]',$detalle->fk_id_proyecto) !!}
									{{isset($detalle->proyecto->proyecto)?$detalle->proyecto->proyecto:'Sin proyecto'}}
								</td>
								<td>
									{!! Form::hidden('detalles['.$detalle->getKey().'][fecha_necesario]',$detalle->fecha_necesario) !!}
									{{$detalle->fecha_necesario??'Sin fecha'}}
								</td>
								<td>
									{!! Form::hidden('detalles['.$detalle->getKey().'][cantidad]',$detalle->cantidad,['class'=>'cantidad_row']) !!}
									{{$detalle->cantidad}}
								</td>
								<td>
									{!! Form::hidden('detalles['.$detalle->getKey().'][descuento_detalle]',$detalle->descuento_detalle,['class'=>'descuento_row']) !!}
									{{$detalle->descuento_detalle??0}}
								</td>
								<td>
									{!! Form::hidden('detalles['.$detalle->getKey().'][fk_id_impuesto]',$detalle->fk_id_impuesto) !!}
									{!! Form::hidden('',$detalle->impuesto->porcentaje,['class'=>'porcentaje']) !!}
									{{$detalle->impuesto->impuesto}}
								</td>
								<td>
									{!! Form::hidden('detalles['.$detalle->getKey().'][precio_unitario]',$detalle->precio_unitario,['class'=>'precio_unitario_row']) !!}
									{{number_format($detalle->precio_unitario,2,'.','')}}
								</td>
								<td>
									<input type="text" class="form-control total_row" style="min-width: 100px" name="{{'detalles['.$detalle->getKey().'][total]'}}" readonly value="{{number_format($detalle->total,2,'.','')}}">
								</td>
								<td>
										<button class="btn is-icon text-primary bg-white "
												type="button" data-item-id="{{$detalle->getKey()}}"
												id="{{$detalle->getKey()}}" data-delay="50"
												onclick="borrarFila(this)" data-delete-type="single">
											<i class="material-icons">delete</i></button>
								</td>
							</tr>
						@endforeach
					@elseif( isset( $detalles ) )
						@foreach( $detalles as $detalle)
							<tr>
								<td>
									{{isset($detalle->fk_id_documento_parent)?$detalle->fk_id_tipo_documento_parent.' - '.$detalle->fk_id_documento_parent:'N/A'}}
								</td>
								<td>
									{!! Form::hidden('detalles['.$detalle->id_orden_detalle.'][id_orden_detalle]',$detalle->id_orden_detalle) !!}
									{!! Form::hidden('detalles['.$detalle->id_orden_detalle.'][fk_id_sku]',$detalle->fk_id_sku) !!}
									{{$detalle->sku->sku}}
								</td>
								<td>
									{!! Form::hidden('detalles['.$detalle->id_orden_detalle.'][fk_id_upc]',$detalle->fk_id_upc) !!}
									{{isset($detalle->fk_id_upc)?$detalle->upc->upc:'UPC no seleccionado'}}
								</td>
								<td>
									{{$detalle->sku->descripcion_corta}}
								</td>
								<td>
									{{$detalle->sku->descripcion}}
								</td>
								<td>
									{!! Form::hidden('detalles['.$detalle->id_orden_detalle.'][fk_id_cliente]',$detalle->fk_id_cliente) !!}
									{{isset($detalle->cliente->nombre_corto)?$detalle->cliente->nombre_corto:'Sin cliente'}}
								</td>
								<td>
									{!! Form::hidden('detalles['.$detalle->id_orden_detalle.'][fk_id_proyecto]',$detalle->fk_id_proyecto) !!}
									{{isset($detalle->proyecto->proyecto)?$detalle->proyecto->proyecto:'Sin proyecto'}}
								</td>
								<td>
									{!! Form::hidden('detalles['.$detalle->id_orden_detalle.'][fecha_necesario]',$detalle->fecha_necesario) !!}
									{{$detalle->fecha_necesario}}
								</td>
								<td>
									{!! Form::hidden('detalles['.$detalle->id_orden_detalle.'][cantidad]',$detalle->cantidad,['class'=>'cantidad_row']) !!}
									{{$detalle->cantidad}}
								</td>
								<td>
									{!! Form::hidden('detalles['.$detalle->getKey().'][descuento_detalle]',$detalle->descuento_detalle,['class'=>'descuento_row']) !!}
									{{$detalle->descuento_detalle??0}}
								</td>
								<td>
									{!! Form::hidden('detalles['.$detalle->id_orden_detalle.'][fk_id_impuesto]',$detalle->fk_id_impuesto) !!}
									{!! Form::hidden('detalles['.$detalle->id_orden_detalle.'][fk_id_impuesto]',$detalle->impuesto->porcentaje,['class'=>'porcentaje']) !!}
									{{$detalle->impuesto->impuesto}}
								</td>
								<td>
									{!! Form::hidden('detalles['.$detalle->id_orden_detalle.'][precio_unitario]',$detalle->precio_unitario,['class'=>'precio_unitario_row']) !!}
									{{number_format($detalle->precio_unitario,2,'.','')}}
								</td>
								<td>
									<input type="text" class="form-control total_row" style="min-width: 100px" name="{{'detalles['.$detalle->id_orden_detalle.'][total]'}}" readonly value="{{number_format($detalle->total,2,'.','')}}">
								</td>
								<td>
									{{--Si se va a editar, agrega el botón para "eliminar" la fila--}}
									@if(Route::currentRouteNamed(currentRouteName('edit')) && $data->fk_id_estatus_orden == 1)
										<button class="btn is-icon text-primary bg-white "
										   type="button" data-item-id="{{$detalle->id_orden_detalle}}"
										   id="{{$detalle->id_orden_detalle}}" data-delay="50"
										   onclick="borrarFila_edit(this)" data-delete-type="single">
											<i class="material-icons">delete</i></button>
									@endif
								</td>
							</tr>
						@endforeach
					@endif
					</tbody>
					<tfoot class="table-dark">
					<tr>
						<td colspan="2"></td>
						<td colspan="2">
								{{ Form::label('subtotal_lbl', 'Subtotal',['class'=>'h5']) }}
								<span class="">$</span>
							{{ Form::label('subtotal_lbl', '0.00',['class'=>'h5','id'=>'subtotal_lbl']) }}
							{{Form::hidden('subtotal',null,['id'=>'subtotal'])}}
							{{--{!! Form::text('subtotal', null,['class'=>'form-control','disabled','placeholder'=>'0.00']) !!}--}}
						</td>
						<td colspan="4">
{{--							{{ Form::label('0','',['class'=>'h5','id'=>'descuento_porcentaje']) }}--}}
							<div class="form-group col-sm-6">
								{{ Form::label('', 'Descuento: ',['class'=>'h5']) }}
								<div class="input-group">
									{{Form::text('descuento_porcentaje',null,['class'=>'form-control','placeholder'=>'00.0000','id'=>'descuento_porcentaje'])}}
									<span class="input-group-addon">%</span>
									<span class="input-group-addon">=</span>
									<span class="input-group-addon">$</span>
									{{--{{ Form::label('', '0.00',['class'=>'h5','id'=>'descuento_general']) }}--}}
									{{Form::text('descuento_general',null,['class'=>'form-control','placeholder'=>'00000.00','id'=>'descuento_general'])}}
									{{Form::hidden('descuento_total',null,['id'=>'descuento_total'])}}
								</div>
							</div>
						</td>
						<td colspan="2">
								{{ Form::label('', 'Impuesto',['class'=>'h5']) }}
								<span>$</span>
								{{ Form::label('impuesto', '0.0',['class'=>'h5','id'=>'impuesto_lbl']) }}
								{{Form::hidden('impuesto',null,['id'=>'impuesto_total'])}}
						</td>
						<td colspan="3">
							<div class="input-group">
								{{ Form::label('total_orden', 'Total',['class'=>'input-group-addon']) }}
								<span class="input-group-addon">$</span>
								{!! Form::text('total_orden', null,['class'=>'form-control','readonly','placeholder'=>'0.00']) !!}
							</div>
						</td>
						<td></td>
					</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
</div>

{{-- Mostrar solo a usuarios autorizados para autorizar ordenes de compra --}}
@if(!empty($data->autorizaciones) && $data->fk_id_estatus_autorizacion != 1)
<div id="autorizaciones" class="row card z-depth-1-half">
	<div class="card-header">
		<div class="col-md-12 col-sm-12">
			<h3 class="text-danger text-center">Autorizaciones</h3>
		</div>
	</div>
	<div class="card-body">
		<div class="text-right">
			<a href="#" class="btn p-2 btn-dark" id="reload"><i class="material-icons align-middle">cached</i>Recargar</a>
		</div>
		<table class="table">
			<thead>
				<tr>
					<th>Condición</th>
					<th>Fecha</th>
					<th>Estatus</th>
					<th>Fecha estatus</th>
					<th>Autorizó</th>
					<th>Observación</th>
					<th></th>
				</tr>
			</thead>
			<tbody id="autorizaciones_detalle">
				@foreach($data->autorizaciones->where('activo',1)->where('eliminar',0) as $autorizacion)
					@foreach($condiciones as $condicion)
						@if($autorizacion->fk_id_condicion == $condicion->id_condicion)
							<tr>
								<td>{{$autorizacion->condicion->nombre}}{{Form::hidden('',$autorizacion->id_autorizacion)}}{{Form::hidden('',$autorizacion->observaciones)}}{{Form::hidden('',$autorizacion->fk_id_estatus)}}</td>
								<td>{{$autorizacion->fecha_creacion}}</td>
								@if($autorizacion->fk_id_estatus == 1)
									<td>{{$autorizacion->estatus->estatus}}</td>
								@elseif($autorizacion->fk_id_estatus == 2)
									<td class="text-info">{{$autorizacion->estatus->estatus}}</td>
								@elseif($autorizacion->fk_id_estatus == 3)
									<td class="text-danger">{{$autorizacion->estatus->estatus}}</td>
								@else
									<td class="text-success">{{$autorizacion->estatus->estatus}}</td>
								@endif
								<td>{{$autorizacion->fecha_autorizacion}}</td>
								@if($autorizacion->fk_id_estatus == 4)
									@if($autorizacion->usuario->nombre_corto <> '')
										<td>{{$autorizacion->usuario->nombre_corto}}</td>
									@else
										<td>--</td>
									@endif
								@else
									<td>--</td>
								@endif
								<td>{{$autorizacion->observaciones}}</td>
								<td>
									@if(Route::currentRouteNamed(currentRouteName('edit')))
									 {{--Ventana de autorizaciones --}}
									<button class="condicion text-primary btn btn_tables is-icon eliminar bg-white" type="button" data-toggle="modal" data-target="#autorizacion">
									<i class="material-icons">new_releases</i>
									</button>
									@endif
								</td>
							</tr>
						@endif
					@endforeach
				@endforeach
			</tbody>
		</table>
	</div>
</div>
@endif

<div id="autorizacion" class="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">¿Autorizar el pago?</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					{{Form::hidden('',null,['id'=>'id_autorizacion','data-url'=>companyAction('Compras\AutorizacionesController@update',['id'=>'?id'])])}}
					<div class="form-group col-md-12 col-sm-6">
						{{Form::label('','Tipo de autorizacion')}}
						{{Form::text('',null,['class'=>'form-control','readonly','id'=>'motivo_autorizacion'])}}
					</div>
					<div class="form-group text-center col-md-12 col-sm-6">
						{{Form::cRadio('¿Autorizado?','fk_id_estatus',[4=>'Autorizado',3=>'No Autorizado'])}}
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



@endsection

{{-- DONT DELETE --}}
@if (Route::currentRouteNamed(currentRouteName('index')))
	@section('smart-js')
		<script type="text/javascript">
            if ( sessionStorage.reloadAfterPageLoad ) {
                sessionStorage.clear();
                $.toaster({
                    priority: 'success', title: '¡Éxito!', message: 'Orden cancelada',
                    settings:{'timeout': 5000, 'toaster':{'css':{'top':'5em'}}}
                });
            }
		</script>
		@parent
		<script type="text/javascript">
			rivets.binders['hide-delete'] = {
				bind: function (el) {
					if(el.dataset.fk_id_estatus_orden != 1 {{-- || el.dataset.fk_id_estatus_autorizacion == 2 || el.dataset.fk_id_estatus_autorizacion == 4 --}})
					{
						$(el).hide();
					}
				}
			};
			rivets.binders['hide-update'] = {
				bind: function (el) {
					if(el.dataset.fk_id_estatus_orden != 1 {{--|| el.dataset.fk_id_estatus_autorizacion == 2 || el.dataset.fk_id_estatus_autorizacion == 4 --}})
					{
						$(el).hide();
					}
				}
			};
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
				window['smart-model'].actions.itemsCancel = function(e, rv, motivo){
				if(!motivo.motivo_cancelacion){
					$.toaster({
						priority : 'danger',
						title : '¡Error!',
						message : 'Por favor escribe un motivo por el que se está cancelando esta orden de compra',
						settings:{
							'timeout':10000,
							'toaster':{
								'css':{
									'top':'5em'
								}
							}
						}
					});
				}else{
					let data = {motivo};
					$.delete(this.dataset.deleteUrl,data,function (response) {
						if(response.success){
							sessionStorage.reloadAfterPageLoad = true;
							location.reload();
						}
					})
				}
			};
			window['smart-model'].actions.showModalCancelar = function(e, rv) {
				e.preventDefault();

				let modal = window['smart-modal'];
				modal.view = rivets.bind(modal, {
					title: '¿Estas seguro que deseas cancelar la orden?',
					content: '<form  id="cancel-form">' +
					'<div class="alert alert-warning text-center"><span class="text-danger">La cancelación de un documento es irreversible.</span><br>'+
					'Para continuar, especifique el motivo y de click en cancelar.</div>'+
					'<div class="form-group">' +
					'<label for="recipient-name" class="form-control-label">Motivo de cancelación:</label>' +
					'<input type="text" class="form-control" id="motivo_cancelacion" name="motivo_cancelacion">' +
					'</div>' +
					'</form>',
					buttons: [
						{button: {
							'text': 'Cerrar',
							'class': 'btn btn-secondary',
							'data-dismiss': 'modal',
						}},
						{button: {
							'html': 'Cancelar',
							'class': 'btn btn-danger',
							'rv-on-click': 'action',
						}}
					],
					action: function(e,rv) {
						var formData = new FormData(document.querySelector('#cancel-form')), convertedJSON = {}, it = formData.entries(), n;

						while(n = it.next()) {
							if(!n || n.done) break;
							convertedJSON[n.value[0]] = n.value[1];
						}
						console.log(convertedJSON);
						window['smart-model'].actions.itemsCancel.call(this, e, rv,convertedJSON);
					}.bind(this),
					// Opcionales
					onModalShow: function() {

						let btn = modal.querySelector('[rv-on-click="action"]');

						// Copiamos data a boton de modal
						for (var i in this.dataset) btn.dataset[i] = this.dataset[i];

					}.bind(this),
					// onModalHide: function() {}
				});
				// Abrimos modal
				$(modal).modal('show');
			};
		</script>
	@endsection
@endif

@if (Route::currentRouteNamed(currentRouteName('create')))
@section('form-title')
	<h1 class="display-4">Agregar Orden de Compra</h1>
@endsection
@endif

@if (Route::currentRouteNamed(currentRouteName('edit')))
	@section('form-title')
		<h1 class="display-4">Editar Orden de Compra</h1>
	@endsection
@endif

@if (Route::currentRouteNamed(currentRouteName('show')))
	@section('extraButtons')
		@parent
		{!!isset($data->id_orden) ? HTML::decode(link_to(companyAction('impress',['id'=>$data->id_orden]), '<i class="material-icons align-middle">print</i> Imprimir', ['class'=>'btn btn-info imprimir'])) : ''!!}
	@endsection
	@section('form-title')
		<h1 class="display-4">Datos de la Orden de Compra</h1>
	@endsection
@endif
