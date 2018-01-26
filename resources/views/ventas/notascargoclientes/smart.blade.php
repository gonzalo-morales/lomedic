@extends(smart())
@section('content-width', 's12')
@section('form-title', 'Notas de Cargo de Clientes')

@section('header-bottom')
	@parent
	<script type="text/javascript">
        var empresa_js    = '{{ $js_empresa ?? '' }}';
        var cliente_js    = '{{ $js_cliente ?? '' }}';
        var clientes_js   = '{{ $js_clientes ?? '' }}';
        var series_js     = '{{ $js_series ?? '' }}';
        var serie_js      = '{{ $js_serie ?? '' }}';
        var proyectos_js  = '{{ $js_proyectos ?? '' }}';
        var sucursales_js = '{{ $js_sucursales ?? '' }}';
        var impuestos_js  = '{{ $js_impuestos ?? '' }}';
	</script>
	{{ HTML::script(asset('js/ventas/notascargoclientes.js')) }}
@endsection

@section('form-content')
	{{ Form::setModel($data) }}
	<div class="row mx-0 my-3">
		<div class="card col-lg-7">
			<div class="card-header row">
				<h5 class="col-md-12 text-center">Emisor</h5>
			</div>
			<div class="card-body row">
				<div class="form-group col-md-8">
					{{Form::cSelect('* Empresa','fk_id_empresa', $empresas ?? [],['class'=>'select2','disabled'=>!Route::currentRouteNamed(currentRouteName('create')),'data-url'=>ApiAction('administracion.empresas')])}}
				</div>
				<div class="form-group col-md-4">
					{{Form::cText('Rfc','rfc',['disabled'=>true])}}
				</div>
				<div class="form-group col-md-6">
					{{Form::cSelect('* Regimen Fiscal','fk_id_regimen_fiscal', $regimens ?? [],['disabled'=>true])}}
				</div>
				<div class="form-group col-md-6">
					{{Form::cSelect('* Pais','fk_id_pais', $paises ?? [],['disabled'=>true])}}
				</div>
				<div class="form-group col-md-6">
					{{Form::cSelect('* Estado','fk_id_estado', $estados ?? [],['disabled'=>true])}}
				</div>
				<div class="form-group col-md-6">
					{{Form::cSelect('* Municipio','fk_id_municipio', $municipios ?? [],['disabled'=>true])}}
				</div>
				<div class="form-group col-md-6">
					{{Form::cText('Colonia','colonia',['disabled'=>true])}}
				</div>
				<div class="form-group col-md-6">
					{{Form::cText('Calle','calle',['disabled'=>true])}}
				</div>
				<div class="form-group col-md-4">
					{{Form::cText('No. Exterior','no_exterior',['disabled'=>true])}}
				</div>
				<div class="form-group col-md-4">
					{{Form::cText('No. Interior','no_interior',['disabled'=>true])}}
				</div>
				<div class="form-group col-md-4">
					{{Form::cText('Codigo Postal','codigo_postal',['disabled'=>true])}}
				</div>
			</div>
		</div>

		<div class="card col-lg-5">
			<div class="card-header row">
				<h5 class="col-md-12 text-center">Informacion del CFDI</h5>
			</div>
			<div class="card-body row">
				<div class="form-group col-md-4">
					{{Form::cSelect('* Serie','fk_id_serie', $series ?? [],['class'=>'select2','disabled'=>!Route::currentRouteNamed(currentRouteName('create')),'data-url'=>ApiAction('administracion.seriesdocumentos')])}}
				</div>
				<div class="form-group col-md-3">
					<i class="material-icons text-danger float-left" data-toggle="tooltip" data-placement="top" title="" data-original-title="El folio puede cambiar si otro usuario genero una nota de crédito antes de que se guardara esta. Verificalo despues de guardarlo.">warning</i>
					{{Form::hidden('serie',null,['id'=>'serie'])}}
					{{Form::cText('* Folio','folio',['readonly'=>true])}}
				</div>
				<div class="form-group col-md-5">
					{{Form::cText('* Fecha','fecha_creacion',['readonly'=>true])}}
				</div>

				<div class="form-group col-md-7">
					{{Form::cSelect('* Uso CFDI','fk_id_uso_cfdi', $usoscfdi ?? [],['class'=>'select2'])}}
				</div>
				<div class="form-group col-md-5">
					{{Form::cSelect('* Metodo Pago','fk_id_metodo_pago', $metodospago ?? [])}}
				</div>

				<div class="form-group col-md-7">
					{{Form::cSelect('* Forma Pago','fk_id_forma_pago', $formaspago ?? [], ['class'=>'select2'])}}
				</div>

				<div class="form-group col-md-5">
					{{Form::cSelect('* Condicion Pago','fk_id_condicion_pago', $condicionespago ?? [], ['class'=>'select2'])}}
				</div>
				<div class="form-group col-md-7">
					{{Form::cSelect('* Moneda','fk_id_moneda', $monedas ?? [], ['class'=>'select2','disabled'=>!Route::currentRouteNamed(currentRouteName('create'))])}}
				</div>
				<div class="form-group col-md-5">
					{{Form::cText('* Tipo Cambio','tipo_cambio',['readonly'=>true])}}
				</div>
			</div>
		</div>
	</div>

	<div class="row mx-0 my-2">
		<div class="card col-lg-7">
			<div class="card-header row">
				<h5 class="col-md-12 text-center">Receptor</h5>
			</div>
			<div class="card-body row">
				<div class="form-group col-md-8">
					{{Form::cSelect('* Cliente','fk_id_socio_negocio', $clientes ?? [], ['class'=>'select2','data-url'=>ApiAction('sociosnegocio.sociosnegocio')])}}
				</div>
				<div class="form-group col-md-4">
					{{Form::cText('Rfc','rfc_cliente',['disabled'=>true])}}
				</div>
				<div class="form-group col-md-6">
					{{Form::cSelect('* Proyecto','fk_id_proyecto', $proyectos ?? [], ['class'=>'select2','data-url'=>ApiAction('proyectos.proyectos')])}}
				</div>
				<div class="form-group col-md-6">
					{{Form::cSelect('* Sucursal','fk_id_sucursal', $sucursales ?? [], ['class'=>'select2','data-url'=>ApiAction('administracion.sucursales')])}}
				</div>
			</div>
		</div>

		<div class="card col-lg-5">
			<div class="card-header row">
				<h5 class="col-md-12 text-center">CFDI Relacionados</h5>
				<div class="form-group col-md-12">
					{{Form::cSelect('* Tipo Relacion','fk_id_tipo_relacion', $tiposrelacion ?? [],['data-url'=>companyAction('Ventas\NotasCargoClientesController@getProductosRelacionados')])}}
				</div>
				<div class="form-group col-md-5">
					{{--					{!!dd(companyAction('Ventas\FacturasClientesController@getFacturasDetalles',['detalles'=>'ids?']))!!}--}}
					{!!Form::cSelect('* Factura','fk_id_factura_relacion', $facturasrelacionadas ?? [],['class'=>'select2'])!!}
				</div>
				<div class="form-group col-md-2 d-flex align-items-center justify-content-center">
					<span>O</span>
				</div>
				<div class="form-group col-md-5">
					{{Form::cSelect('* Nota Crédito','fk_id_nota_credito_relacion', $notascreditorelacionadas ?? [],['class'=>'select2'])}}
				</div>
				@if(!Route::currentRouteNamed(currentRouteName('view')))
					<div class="form-group col-md-12 my-2">
						<div class="sep sepBtn">
							<button id="agregarRelacion" class="btn btn-primary btn-large btn-circle" data-placement="bottom" data-delay="100" data-tooltip="Agregar" data-toggle="tooltip" data-action="add" title="Agregar" type="button"><i class="material-icons">add</i></button>
						</div>
					</div>
				@endif
			</div>
			<div class="card-body row table-responsive">
				<table class="table highlight mt-3" id="detalleRelaciones">
					<thead>
					<tr>
						<th>Tipo Relacion</th>
						<th>Documento</th>
						<th></th>
					</tr>
					</thead>
					<tbody>
					@if(isset($data->relaciones))
						@foreach($data->relaciones->where('eliminar',0) as $row=>$detalle)
							<tr>
								<td>
									{!! Form::hidden('relations[has][relaciones]['.$row.'][id_relacion_cfdi_cliente]',$detalle->id_contacto,['class'=>'id_contacto']) !!}
									{!! Form::hidden('relations[has][relaciones]['.$row.'][fk_id_tipo_relacion]',$detalle->fk_id_tipo_relacion,['class'=>'fk_id_tipo_relacion']) !!}
									{{$detalle->tiporelacion->tipo_relacion}}
								</td>
								<td>
									{!! Form::hidden('relations[has][relaciones]['.$row.'][fk_id_documento]',$detalle->fk_id_documento) !!}
									{{$detalle->documento->uuid}}
								</td>
								<td>
									@if(!Route::currentRouteNamed(currentRouteName('view')))
										<button class="btn is-icon text-primary bg-white" type="button" data-delay="50" onclick="borrarContacto(this)"> <i class="material-icons">delete</i></button>
									@endif
								</td>
							</tr>
						@endforeach
					@endif
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<div class="card z-depth-1-half my-3">
		<div class="card-header">
			<div class="row py-2">
				<div class="form-group col-md-12">
					<div id="loadingfk_id_producto" class="w-100 h-100 text-center text-white align-middle loadingData" style="display: none">
						Cargando datos... <i class="material-icons align-middle loading">cached</i>
					</div>
					{{Form::cSelect('* Producto','fk_id_producto', $productos ?? [], ['class'=>'select2'])}}
				</div>
				<div class="form-group col-md-3">
					{{Form::cNumber('* Cantidad','cantidad')}}
				</div>
				<div class="form-group col-md-3">
					{{Form::cNumber('* Precio Unitario','precio_unitario')}}
				</div>
				<div class="form-group col-md-3">
					{{Form::cNumber('* Descuento','descuento_producto')}}
				</div>
				<div class="form-group col-md-3">
					{{Form::cSelect('* Impuesto','fk_id_impuesto', $impuestos ?? [], ['class'=>'select2','data-url'=>ApiAction('administracion.impuestos')])}}
				</div>
				@if(!Route::currentRouteNamed(currentRouteName('view')))
					<div class="form-group col-md-12 my-2">
						<div class="sep sepBtn">
							<button id="agregar-concepto" class="btn btn-primary btn-large btn-circle" data-placement="bottom" data-delay="100" data-tooltip="Agregar" data-toggle="tooltip" data-action="add" title="Agregar" type="button"><i class="material-icons">add</i></button>
						</div>
					</div>
				@endif
			</div>
		</div>
		<div class="card-body row table-responsive">
			<table class="table highlight mt-3" id="tConceptos">
				<thead>
				<tr>
					<th>Factura Referencia</th>
					<th>Clave Producto</th>
					<th>Codigo</th>
					<th>Concepto</th>
					<th>Unidad Medida</th>
					<th>Cantidad</th>
					<th>Precio Unitario</th>
					<th>Descuento</th>
					<th>Impuesto</th>
					<th>Pedimento</th>
					<th>Cuenta Predial</th>
					<th>Importe</th>
					<th></th>
				</tr>
				</thead>
				<tbody>
				@if(isset($data->detalle))
					@foreach($data->detalle->where('eliminar',0) as $key=>$detalle)
						<tr>
							<td>
								{!! Form::hidden('contactos['.$key.'][id_factura_detalle]',$detalle->id_factura_detalle,['class'=>'id_factura_detalle']) !!}
								{{$detalle->claveproducto->clave_producto_servicio}}
								{!! Form::hidden('contactos['.$key.'][fk_id_clave_producto_servicio]',$detalle->fk_id_clave_producto_servicio,['class'=>'fk_id_clave_producto_servicio']) !!}
							</td>
							<td>
								{{$detalle->sku->sku}}
								{!! Form::hidden('contactos['.$key.'][fk_id_sku]',$detalle->fk_id_sku) !!}
							</td>
							<td>
								{{$detalle->upc->descripcion}}<br>
								{{$detalle->sku->descripcion}}
							</td>
							<td>
								{{$detalle->unidadmedida->clave_unidad.' - '.$detalle->unidadmedida->descripcion}}
							</td>
							<td>
								{{$detalle->cantidad}}
							</td>
							<td>
								{{$detalle->precio_unitario}}
							</td>
							<td>
								{{$detalle->descuento}}
							</td>
							<td>
								{{$detalle->impuestos->impuesto}}
							</td>
							<td>
								{{Form::cText('','cuenta_predial')}}
							</td>
							<td>
								{{Form::cText('','cuenta_predial')}}
							</td>
							<td>
								{{$detalle->importe}}
							</td>
							<td>
								@if(!Route::currentRouteNamed(currentRouteName('view')))
									<button class="btn is-icon text-primary bg-white" type="button" data-delay="50" onclick="borrarContacto(this)"> <i class="material-icons">delete</i></button>
								@endif
							</td>
						</tr>
					@endforeach
				@endif
				</tbody>
			</table>
		</div>
		<div class="card-footer">
			<table class="table highlight mt-3 float-right w-25 text-right" id="tContactos">
				<tbody>

				<tr>
					<th>SUBTOTAL</th>
					<td>{{Form::hidden('subtotal',null,['id'=>'subtotal'])}}<span id="subtotal_span"></span></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<th>DESCUENTO GENERAL</th>
					<td>{{Form::cNumber('','descuento')}}</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<th>TOTAL DESCUENTOS</th>
					<td><span id="descuento_span"></span></td>
					<td>&nbsp;</td>
				</tr>
				<tr id="impuestos_factura" data-toggle="collapse" data-target="#impuestos_accordion" class="clickable">
					<th><button type="button" data-tooltip="Ver descripción de impuestos" data-toggle="tooltip" title="Ver descripción de impuestos" class="btn btn-secondary is-icon"><i class="material-icons add">add</i></button> IMPUESTOS</th>
					<td>{{Form::hidden('impuestos',null,['id'=>'impuestos'])}}<span id="impuesto_label"></span></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td colspan="3">
						<div id="impuestos_accordion" class="collapse">
							<table id="impuestos_descripcion" class="w-100 text-right">
								<tbody></tbody>
							</table>
						</div>
					</td>
				</tr>
				{{--<tr>--}}
				{{--<th class="pl-4">IVA 16%</th>--}}
				{{--<td>$ 1,600.00</td>--}}
				{{--<td>&nbsp;</td>--}}
				{{--</tr>--}}
				{{--<tr>--}}
				{{--<th class="pl-4">IEPS</th>--}}
				{{--<td>$ 1,000.00</td>--}}
				{{--<td>&nbsp;</td>--}}
				{{--</tr>--}}
				<tr>
					<th>TOTAL</th>
					<td>{{Form::hidden('total',null,['id'=>'total'])}}<span id="total_span"></span></td>
					<td>&nbsp;</td>
				</tr>
				</tbody>
			</table>
		</div>
	</div>
@endsection
@if(Route::currentRouteNamed(currentRouteName('edit')) || Route::currentRouteNamed(currentRouteName('create')))
@section('form-actions')
	<div class="col-md-12 col-xs-12">
		<div class="text-right">
			{{ Form::button('Guardar y Timbrar', ['id'=>'timbrar','type' =>'submit', 'class'=>'btn btn-primary progress-button']) }}
			{{ Form::button('Guardar', ['type' =>'submit', 'class'=>'btn btn-secondary progress-button']) }}
			{{ link_to(companyRoute('index'), 'Cerrar', ['class'=>'btn btn-default progress-button']) }}
		</div>
	</div>
@endsection
@endif