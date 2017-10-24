@section('header-top')
	<link rel="stylesheet" href="{{ asset('vendor/vanilla-datatables/vanilla-dataTables.css') }}">
@endsection
@section('header-bottom')
	@parent
	<script src="{{ asset('vendor/vanilla-datatables/vanilla-dataTables.js') }}"></script>
	@if (!Route::currentRouteNamed(currentRouteName('index')))
		<script type="text/javascript" src="{{ asset('js/ofertas_compras.js') }}"></script>
	@endif
@endsection

@section('content-width', 's12')

@section('form-content')
{{ Form::setModel($data) }}
{{--{{dd($company)}}--}}
@if (Route::currentRouteNamed(currentRouteName('show')) || Route::currentRouteNamed(currentRouteName('edit')))
	<div class="row">
		<div class="col-md-12 text-center text-success">
			<h3>Oferta No. {{$data->id_oferta}}</h3>
		</div>
	</div>
@endif
<div class="row">
	<div class="form-group col-md-3 col-sm-12">
		{{ Form::label('fk_id_empresa', 'Otra empresa realiza la oferta') }}
		<div class="input-group">
			<span class="input-group-addon">
				<input type="checkbox" id="otra_empresa" {{isset($data->fk_id_empresa) && $data->fk_id_empresa != $actual_company_id?'checked':''}}>
			</span>
			{!! Form::select('fk_id_empresa',isset($companies)?$companies->prepend('...','0'):[],null,['id'=>'fk_id_empresa','class'=>'form-control','style'=>'width:100%',isset($data->fk_id_empresa) && $data->fk_id_empresa != $actual_company_id?'disabled':'']) !!}
		</div>
		{{ $errors->has('fk_id_empresa') ? HTML::tag('span', $errors->first('fk_id_empresa'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
	<div class="form-group col-md-3 col-sm-6">
		{!! Form::cSelectWithDisabled('* Sucursal','fk_id_sucursal',isset($sucursales)?$sucursales:[]) !!}
	</div>
	<div class="form-group col-md-3 col-sm-6">
{{--			{!! Form::cSelect('*Proveedor','fk_id_proveedor',isset($proveedores)?$proveedores:[],['class'=>'select2']) !!}--}}
			{!! Form::cSelectWithDisabled('*Proveedor','fk_id_proveedor',isset($proveedores)?$proveedores:[],['data-url'=>companyAction('Compras\OrdenesController@getProveedores')])!!}
	</div>
	<div class="form-group text-center col-md-3 col-sm-6">
		{{ Form::label('', 'Días/Fecha') }}
		<div class="input-group">
			{!! Form::text('tiempo_entrega', null,['id'=>'tiempo_entrega','class'=>'form-control','readonly','placeholder'=>'Días para la entrega']) !!}
			{!! Form::text('fecha_estimada_entrega', null,['id'=>'fecha_estimada_entrega','data-date-format'=>'mm-dd-yyyy','class'=>'form-control','readonly','placeholder'=>'Fecha estimada']) !!}
		</div>
	</div>
	<div class="form-group col-md-3">
		{!! Form::cText('* Vigencia de la oferta','vigencia',['class'=>'datepicker'])!!}
	</div>
	<div class="form-group col-md-3">
		{!! Form::cTextArea('Condiciones de la oferta','condiciones_oferta',['rows'=>3,'maxlength'=>'255'])!!}
	</div>
	<div class="form-group col-md-3 col-sm-6">
		{!! Form::cSelectWithDisabled('*Moneda','fk_id_moneda',isset($monedas)?$monedas:[],[])!!}
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<h3>Detalle de la oferta</h3>
		<div class="card z-depth-1-half">
			@if(!Route::currentRouteNamed(currentRouteName('show')))
			<div class="card-header">
				<fieldset name="detalle-form" id="detalle-form">
					<div class="row">
						<div class="form-group input-field col-md-3 col-sm-6">
							{!! Form::cSelectWithDisabled('* SKU','fk_id_sku',[],[
								'data-url'=>companyAction('Inventarios\ProductosController@obtenerSkus'),
								'class'=>'select-cascade',
								'data-target-url' => companyRoute('inventarios.productos.show', ['id' => '#ID#']),
								'data-target-el' => '#fk_id_upc',
								'data-target-with' => '["upcs:id_upc,fk_id_sku,upc"]',
								'data-target-value' => 'upcs,id_upc,upc'
							]) !!}
						</div>
						<div class="form-group input-field col-md-3 col-sm-6">
							{!! Form::cSelectWithDisabled('UPC','fk_id_upc',[],[
								'data-url'=>companyAction('Inventarios\ProductosController@obtenerUpcs',['id'=>'?id']),'style'=>'width:100%',]) !!}
						</div>
						<div class="form-group input-field col-md-3 col-sm-6">
							{!! Form::cSelect('Cliente','fk_id_cliente',isset($clientes)?$clientes:[])!!}
						</div>
						<div class="form-group input-field col-md-3 col-sm-6">
							{!! Form::cSelect('Proyecto','fk_id_proyecto',isset($proyectos)?$proyectos:[])!!}
						</div>
						<div class="form-group input-field col-md-2 col-sm-6">
							{!! Form::cSelectWithDisabled('Unidad medida','fk_id_unidad_medida',isset($unidadesmedidas)?$unidadesmedidas:[],[]) !!}
						</div>
						<div class="form-group input-field col-md-2 col-sm-4">
							{!! Form::cText('* Cantidad','cantidad',['autocomplete'=>'off','placeholder'=>'0'])!!}
						</div>
						<div class="form-group input-field col-md-2 col-sm-6">
							{!! Form::cSelectWithDisabled('Tipo de impuesto','fk_id_impuesto',[],['data-url'=>companyAction('Administracion\ImpuestosController@obtenerImpuestos')])!!}
						</div>
						<div class="form-group input-field col-md-2 col-sm-6">
							{!! Form::label('precio_unitario','* Precio Unitario') !!}
							<div class="input-group">
								<span class="input-group-addon">$</span>
								{!! Form::text('precio_unitario',null,['placeholder'=>'999999.00','class'=>'form-control']) !!}
							</div>
						</div>
						<div class="form-group input-field col-md-2 col-sm-6">
							{!! Form::label('descuento_detalle','Descuento producto') !!}
							<div class="input-group">
								{!! Form::text('descuento_detalle',null,['placeholder'=>'99.0000','class'=>'form-control']) !!}
								<span class="input-group-addon">%</span>
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
				<table id="productos" class="table-responsive highlight" data-url="{{companyAction('Compras\OfertasController@store')}}"
					   @if(isset($data->id_oferta))
					   data-delete="{{companyAction('Compras\OfertasController@destroy')}}"
					   @endif
					   data-impuestos="{{companyAction('Administracion\ImpuestosController@obtenerImpuestos')}}">
					<thead>
					<tr align="center">
						<th>Solicitud</th>
						<th>SKU</th>
						<th>UPC</th>
						<th>Descripción</th>
						<th>Producto</th>
						<th>Cliente</th>
						<th>Proyecto</th>
						<th>Unidad de medida</th>
						<th>Cantidad</th>
						<th>Tipo de impuesto</th>
						<th>Precio unitario</th>
						<th>Descuento (%)</th>
						<th>Total</th>
						<th></th>
					</tr>
					</thead>
					<tbody>
					@if(isset($solicitud) && Route::currentRouteNamed(currentRouteName('create')))
						@foreach( $solicitud->detalleSolicitudes->where('cerrado',false) as $detalle)
							<tr class="list-left bg-light">
								<td>
									{{isset($detalle->fk_id_solicitud)?$detalle->fk_id_solicitud:'N/A'}}
									{!! Form::hidden('detalles['.$detalle->id_solicitud_detalle.'][fk_id_solicitud]',$detalle->fk_id_solicitud) !!}
								</td>
								<td>
									{!! Form::hidden('detalles['.$detalle->id_solicitud_detalle.'][fk_id_sku]',$detalle->fk_id_sku) !!}
									{{$detalle->sku->sku}}
								</td>
								<td>
									{!! Form::hidden('detalles['.$detalle->id_solicitud_detalle.'][fk_id_upc]',isset($detalle->fk_id_upc)?$detalle->fk_id_upc:'') !!}
									{{isset($detalle->fk_id_upc)?$detalle->upc->upc:'UPC no seleccionado'}}
								</td>
								<td>
									{{str_limit($detalle->sku->descripcion_corta,250)}}
								</td>
								<td>
									{{str_limit($detalle->sku->descripcion,250)}}
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
									{!! Form::hidden('detalles['.$detalle->id_solicitud_detalle.'][fk_id_unidad_medida]',$detalle->fk_id_unidad_medida) !!}
									{{$detalle->unidad_medida->nombre}}
								</td>
								<td>
									{!! Form::text('detalles['.$detalle->id_solicitud_detalle.'][cantidad]',
									$detalle->cantidad,
									['class'=>'form-control cantidad','id'=>'cantidad'.$detalle->id_solicitud_detalle,'style'=>'min-width:100px','placeholder'=>'0','onkeyup'=>'total_row('.$detalle->id_solicitud_detalle.')']) !!}
								</td>
								<td>
									{!! Form::hidden('detalles['.$detalle->id_solicitud_detalle.'][fk_id_impuesto]',
									$detalle->fk_id_impuesto,
									['data-porcentaje'=>$detalle->impuesto->porcentaje,'id'=>'fk_id_impuesto'.$detalle->id_solicitud_detalle]) !!}
									{{$detalle->impuesto->impuesto}}
								</td>
								<td>
									{!! Form::text('detalles['.$detalle->id_solicitud_detalle.'][precio_unitario]',
									number_format($detalle->precio_unitario,2,'.',''),
									['class'=>'form-control precio','id'=>'precio_unitario'.$detalle->id_solicitud_detalle,'style'=>'min-width:100px','placeholder'=>'999999.00','onkeyup'=>'total_row('.$detalle->id_solicitud_detalle.')']) !!}
								</td>
								<td>
									{!! Form::text('detalles['.$detalle->id_solicitud_detalle.'][descuento_detalle]',
									null,
									['class'=>'form-control descuento','id'=>'descuento_detalle'.$detalle->id_solicitud_detalle,'style'=>'min-width:100px','placeholder'=>'99.0000','onkeyup'=>'total_row('.$detalle->id_solicitud_detalle.')']) !!}
								</td>
								<td>
									<input type="text" class="form-control total" id="total{{$detalle->id_solicitud_detalle}}" style="min-width: 100px" name="{{'detalles['.$detalle->id_solicitud_detalle.'][total]'}}" readonly value="{{number_format($detalle->total,2,'.','')}}">
								</td>
								<td>
									<button class="btn is-icon text-primary bg-white"
											type="button" data-item-id="{{$detalle->id_oferta_detalle}}"
											id="{{$detalle->id_oferta_detalle}}" data-delay="50"
											onclick="borrarFila(this)" data-delete-type="single">
										<i class="material-icons">delete</i>
									</button>
								</td>
							</tr>
						@endforeach
					@elseif( isset( $data->DetalleOfertas ) )
						@foreach( $data->DetalleOfertas->where('cerrado',false) as $detalle)
							<tr class="{{isset($detalle->fk_id_solicitud)?'list-left bg-light':''}}">
								<td>
									{{isset($detalle->fk_id_solicitud)?$detalle->fk_id_solicitud:'N/A'}}
								</td>
								<td>
									{!! Form::hidden('detalles['.$detalle->id_oferta_detalle.'][id_oferta_detalle]',$detalle->id_oferta_detalle) !!}
									{!! Form::hidden('detalles['.$detalle->id_oferta_detalle.'][fk_id_sku]',$detalle->fk_id_sku) !!}
									{{$detalle->sku->sku}}
								</td>
								<td>
									{!! Form::hidden('detalles['.$detalle->id_oferta_detalle.'][fk_id_upc]',$detalle->fk_id_upc) !!}
									{{isset($detalle->fk_id_upc)?$detalle->upc->upc:'UPC no seleccionado'}}
								</td>
								<td>
									{{$detalle->sku->descripcion_corta}}
								</td>
								<td>
									{{$detalle->sku->descripcion}}
								</td>
								<td>
									{!! Form::hidden('detalles['.$detalle->id_oferta_detalle.'][fk_id_cliente]',$detalle->fk_id_cliente) !!}
									{{isset($detalle->cliente->nombre_corto)?$detalle->cliente->nombre_corto:'Sin cliente'}}
								</td>
								<td>
									{!! Form::hidden('detalles['.$detalle->id_oferta_detalle.'][fk_id_proyecto]',$detalle->fk_id_proyecto) !!}
									{{isset($detalle->proyecto->proyecto)?$detalle->proyecto->proyecto:'Sin proyecto'}}
								</td>
								<td>
									{!! Form::hidden('detalles['.$detalle->id_oferta_detalle.'][fk_id_unidad_medida]',$detalle->fk_id_unidad_medida) !!}
									{{$detalle->unidadMedida->nombre}}
								</td>
								<td>
									{!! Form::hidden('detalles['.$detalle->id_oferta_detalle.'][cantidad]',$detalle->cantidad) !!}
									{{$detalle->cantidad}}
								</td>
								<td>
									{!! Form::hidden('detalles['.$detalle->id_oferta_detalle.'][fk_id_impuesto]',$detalle->fk_id_impuesto) !!}
									{{$detalle->impuesto->impuesto}}
								</td>
								<td>
									{!! Form::hidden('detalles['.$detalle->id_oferta_detalle.'][precio_unitario]',$detalle->precio_unitario) !!}
									{{number_format($detalle->precio_unitario,2,'.','')}}
								</td>
								<td>
									{!! Form::hidden('detalles['.$detalle->id_oferta_detalle.'][descuento_detalle]',$detalle->descuento_detalle) !!}
									{{number_format($detalle->descuento_detalle,4,'.','')}}
								</td>
								<td>
									<input type="text" class="form-control total" style="min-width: 100px" name="{{'detalles['.$detalle->id_oferta_detalle.'][total]'}}" readonly value="{{number_format($detalle->total,2,'.','')}}">
								</td>
								<td>
									{{--Si se va a editar, agrega el botón para "eliminar" la fila--}}
									@if(Route::currentRouteNamed(currentRouteName('edit')) && $data->fk_id_estatus_oferta == 1)
										<button class="btn is-icon text-primary bg-white "
										   type="button" data-item-id="{{$detalle->id_oferta_detalle}}"
										   id="{{$detalle->id_oferta_detalle}}" data-delay="50"
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
							<td colspan="5"></td>
							<td colspan="3">
								{!! Form::label('descuento_oferta','Descuento General') !!}
								<div class="form-group col-md-12">
									<div class="input-group">
										{!! Form::text('descuento_oferta',null,['placeholder'=>'99.0000','class'=>'form-control','id'=>'descuento_oferta']) !!}
										<span class="input-group-addon">%</span>
									</div>
								</div>
							</td>
							<td colspan="4">
								{{ Form::label('total_orden', 'Total de la oferta') }}
								<div class="form-group col-md-12">
									<div class="input-group">
										<span class="input-group-addon">$</span>
										{!! Form::text('total_orden', null,['class'=>'form-control','disabled','placeholder'=>'0.00']) !!}
									</div>
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
@endsection

{{-- DONT DELETE --}}
@if (Route::currentRouteNamed(currentRouteName('index')))
@section('smart-js')
	<script type="text/javascript">
        if ( sessionStorage.reloadAfterPageLoad ) {
            sessionStorage.clear();
            $.toaster({
                priority: 'success', title: 'Exito', message: 'Oferta cancelada',
                settings:{'timeout': 5000, 'toaster':{'css':{'top':'5em'}}}
            });
        }
	</script>
	@parent
	<script type="text/javascript">
        rivets.binders['hide-delete'] = {
            bind: function (el) {
                if(el.dataset.fk_id_estatus_oferta != 1)
                {
                    $(el).hide();
                }
            }
        };
        rivets.binders['hide-update'] = {
            bind: function (el) {
                if(el.dataset.fk_id_estatus_oferta != 1)
                {
                    $(el).hide();
                }
            }
        };
        rivets.binders['hide-comprar'] = {
            bind: function (el) {
                if(el.dataset.fk_id_estatus_oferta != 1)
                {
                    $(el).hide();
                }
            }
        };
        rivets.binders['get-comprar-url'] = {
            bind: function (el) {
                el.href = el.href.replace('#ID#',el.dataset.itemId);
            }
        };
		@can('update', currentEntity())
            window['smart-model'].collections.itemsOptions.edit ={a: {
            'html': '<i class="material-icons">mode_edit</i>',
            'class': 'btn is-icon',
            'rv-get-edit-url': '',
            'rv-hide-update':''
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
            'rv-hide-delete':''
        }};
		@endcan
        window['smart-model'].collections.itemsOptions.supply = {a: {
            'html': '<i class="material-icons">shopping_cart</i>',
            'href' : '{!! url($company."/compras/#ID#/2/ordenes/crear") !!}',
//            'href' : '#',
            'class': 'btn is-icon',
            'rv-hide-comprar':'',
            'rv-get-comprar-url':''
        }};
        window['smart-model'].actions.itemsCancel = function(e, rv){
                $.delete(this.dataset.deleteUrl,function (response) {
                    if(response.success){
                        sessionStorage.reloadAfterPageLoad = true;
                        location.reload();
                    }
                });
        };
        window['smart-model'].actions.showModalCancelar = function(e, rv) {
            e.preventDefault();
            let modal = window['smart-modal'];
            modal.view = rivets.bind(modal, {
                title: '¿Estas seguro que deseas cancelar la solicitud?',
                content: '<form  id="cancel-form">' +
                '<div class="form-group">' +
//                '<label for="recipient-name" class="form-control-label">Cancelar:</label>' +
//                '<input type="text" class="form-control" id="motivo_cancelacion" name="motivo_cancelacion">' +
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

@include('layouts.smart.index')
@endif

@if (Route::currentRouteNamed(currentRouteName('create')))
@section('form-title')
	<h1 class="display-4">Agregar Oferta de Compra</h1>
@endsection
	@include('layouts.smart.create')
@endif

@if (Route::currentRouteNamed(currentRouteName('edit')))
	@section('form-title')
		<h1 class="display-4">Editar Oferta de Compra</h1>
	@endsection
	@include('layouts.smart.edit')
@endif

@if (Route::currentRouteNamed(currentRouteName('show')))
	@section('extraButtons')
		@parent
		{!!isset($data->id_oferta) ? HTML::decode(link_to(companyAction('impress',['id'=>$data->id_oferta]), '<i class="material-icons align-middle">print</i> Imprimir', ['class'=>'btn btn-info imprimir'])) : ''!!}
		{!! $data->fk_id_estatus_oferta == 1 ? HTML::decode(link_to(url($company.'/compras/'.$data->id_oferta.'/2/ordenes/crear',[1,2,3]), '<i class="material-icons align-middle">shopping_cart</i> Ordenar', ['class'=>'btn btn-info imprimir'])) : '' !!}
	@endsection
@section('form-title')
		<h1 class="display-4">Datos de la Oferta de Compra</h1>
	@endsection
	@include('layouts.smart.show')
@endif
