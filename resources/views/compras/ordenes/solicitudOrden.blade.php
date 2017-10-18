@section('header-top')
	<link rel="stylesheet" href="{{ asset('vendor/vanilla-datatables/vanilla-dataTables.css') }}">
@endsection
@section('header-bottom')
	@parent
	<script src="{{ asset('vendor/vanilla-datatables/vanilla-dataTables.js') }}"></script>
	@if (!Route::currentRouteNamed(currentRouteName('index')))
		<script type="text/javascript" src="{{ asset('js/solicitudOrden.js') }}"></script>
	@endif
@endsection

@section('content-width', 's12')

@section('form-content')
{{ Form::setModel($data) }}
{{--{{dd($detalleSolicitud)}}--}}
{{Form::hidden('id_solicitud',$solicitud->id_solicitud)}}
<div class="row">
	<div class="form-group col-md-3 col-sm-12">
		{{ Form::label('fk_id_socio_negocio', '* Proveedor a surtir') }}
		{!! Form::select('fk_id_socio_negocio',[],null,['id'=>'fk_id_socio_negocio','class'=>'form-control select2','style'=>'width:100%','data-url'=>companyAction('getProveedores')]) !!}
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
		{!! Form::select('fk_id_sucursal',isset($sucursales)?$sucursales:[],$solicitud->fk_id_sucursal,['id'=>'fk_id_sucursal_','class'=>'form-control select2','style'=>'width:100%']) !!}
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
		<div class="card">
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
							{!! Form::text('precio_unitario',old('precio_unitario'),['id'=>'precio_unitario','placeholder'=>'0.00','class'=>'validate form-control precio_unitario','autocomplete'=>'off']) !!}
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
					   data-impuestos="{{companyAction('Administracion\ImpuestosController@obtenerImpuestos')}}"
					   data-porcentaje="{{companyAction('Administracion\ImpuestosController@obtenerPorcentaje',['id'=>'?id'])}}">
					<thead>
					<tr>
						<th>Solicitud</th>
						<th id="idsku">SKU</th>
						<th id="idupc">UPC</th>
						<th id="nombrecomercial">Nombre comercial</th>
						<th id="descripcion">Descripción</th>
						<th id="idcliente">Cliente</th>
						<th id="idproyecto" >Proyecto</th>
						<th id="fechanecesario" >Fecha límite</th>
						<th>Cantidad</th>
						<th id="idimpuesto" >Tipo de impuesto</th>
						<th>Precio unitario</th>
						<th>Total</th>
						<th></th>
					</tr>
					</thead>
					<tbody>
						@foreach( $detalleSolicitud as $detalle)
							<tr>
								<td>
									{{$detalle->fk_id_solicitud}}
									{!! Form::hidden('detalles['.$detalle->id_solicitud_detalle.'][fk_id_solicitud]',$detalle->fk_id_solicitud) !!}
								</td>
								<td>
									{{--{!! Form::hidden('detalles['.$detalle->id_solicitud_detalle.'][id_orden_detalle]',$detalle->id_solicitud_detalle) !!}--}}
									{!! Form::hidden('detalles['.$detalle->id_solicitud_detalle.'][fk_id_sku]',$detalle->fk_id_sku) !!}
									{{$detalle->sku->sku}}
								</td>
								<td>
									{!! Form::hidden('detalles['.$detalle->id_solicitud_detalle.'][fk_id_upc]',$detalle->fk_id_upc) !!}
									{{isset($detalle->upc->upc)?$detalle->upc->upc:'UPC no seleccionado'}}
								</td>
								<td>
									{{$detalle->sku->nombre_comercial}}
								</td>
								<td>
									{{$detalle->sku->descripcion}}
								</td>
								<td>
									{!! Form::hidden('detalles['.$detalle->id_solicitud_detalle.'][fk_id_cliente]',$detalle->fk_id_cliente) !!}
									{{isset($detalle->cliente->nombre_corto)?$detalle->cliente->nombre_corto:'Sin cliente'}}
								</td>
								<td>
									{!! Form::hidden('detalles['.$detalle->id_solicitud_detalle.'][fk_id_proyecto]',$detalle->fk_id_proyecto) !!}
									{{isset($detalle->proyecto->proyecto)?$detalle->proyecto->proyecto:'Sin proyecto'}}
								</td>
								<td>
									{!! Form::hidden('detalles['.$detalle->id_solicitud_detalle.'][fecha_necesario]',$detalle->fecha_necesario) !!}
									{{$detalle->fecha_necesario}}
								</td>
								<td>
									{!! Form::hidden('detalles['.$detalle->id_solicitud_detalle.'][cantidad]',$detalle->cantidad) !!}
									{{$detalle->cantidad}}
								</td>
								<td>
									{!! Form::hidden('detalles['.$detalle->id_solicitud_detalle.'][fk_id_impuesto]',$detalle->fk_id_impuesto) !!}
									{{$detalle->impuesto->impuesto}}
								</td>
								<td>
									{!! Form::hidden('detalles['.$detalle->id_solicitud_detalle.'][precio_unitario]',$detalle->precio_unitario) !!}
									{{number_format($detalle->precio_unitario,2,'.','')}}
								</td>
								<td>
									<input type="text" class="form-control total" name="{{'detalles['.$detalle->id_solicitud_detalle.'][total]'}}" readonly value="{{number_format($detalle->total,2,'.','')}}">
								<td>
									{{--Si se va a editar, agrega el botón para "eliminar" la fila--}}
										<button class="btn is-icon text-primary bg-white "
										   type="button" data-item-id="{{$detalle->id_solicitud_detalle}}"
										   id="{{$detalle->id_solicitud_detalle}}" data-delay="50" data-delete-type="single" onclick="borrarFila(this)">
											<i class="material-icons">delete</i></button>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<div class="form-group col-md-2 col-sm-6 float-right">
	{{ Form::label('total_orden', 'Total de la orden') }}
	{!! Form::text('total_orden', null,['class'=>'form-control','disabled','placeholder'=>'Total']) !!}
</div>
@endsection

{{-- DONT DELETE --}}

@if (currentRouteName('solicitudOrden'))
@section('form-title')
	<h1 class="display-4">Agregar Orden de Compra</h1>
@endsection
	@include('layouts.smart.create')
@endif


{{--@if (currentRouteName('createSolicitudOrden'))--}}
	{{--@include('layouts.smart.create')--}}
{{--@endif--}}