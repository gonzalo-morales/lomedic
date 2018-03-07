@extends(smart())
@section('form-title', 'Ofertas de Compra')

@section('header-top')
	<link rel="stylesheet" href="{{ asset('vendor/vanilla-datatables/vanilla-dataTables.css') }}">
@endsection

@section('header-bottom')
	@parent
	@if (!Route::currentRouteNamed(currentRouteName('index')))
		<script type="text/javascript" src="{{ asset('js/ofertas_compras.js') }}"></script>
		<script type="text/javascript">
			var proyectos_js = '{{$js_proyectos ?? ''}}';
			var tiempo_entrega_js = '{{$js_tiempo_entrega ?? ''}}';
			var porcentaje_js = '{{ $js_porcentaje ?? '' }}';
			var sucursales_js = '{{ $js_sucursales ?? '' }}';
			var upcs_js = '{{ $js_upcs ?? '' }}'
        </script>
	@endif
@endsection

@section('content-width', 's12')

@section('form-content')
    {{ Form::setModel($data) }}
    @inroute(['show','edit'])
    	<div class="row">
    		<div class="col-md-12 text-center text-success">
    			<h3>Oferta No. {{$data->id_documento}}</h3>
    		</div>
    	</div>
    @endif
    <div class="row">
    	<div class="form-group col-md-3 col-sm-12">
			@if(!Route::currentRouteNamed(currentRouteName('index')) && $companies->count() > 1 )
				{{Form::cSelect('* Empresa','fk_id_empresa',$companies ?? [],[
					'style' => 'width:100%;',
					'class' => !Route::currentRouteNamed(currentRouteName('show')) ? 'select2' : '',
				])}}
			@else
				{{Form::cSelect('* Empresa','fk_id_empresa',$companies ?? [],[
					'style' => 'width:100%;',
					'class' => !Route::currentRouteNamed(currentRouteName('show')) ? 'form-control' : '',
				])}}
			@endif
    		{{--  {{ Form::label('fk_id_empresa', 'Otra empresa realiza la oferta') }}  --}}
    		{{--  <div class="input-group">
    			<span class="input-group-addon">
    				<input type="checkbox" id="otra_empresa" {{isset($data->fk_id_empresa) && $data->fk_id_empresa != $actual_company_id?'checked':''}}>
				</span>
    			{!! Form::cselect('fk_id_empresa',isset($companies)?$companies->prepend('...','0'):[],null,['id'=>'fk_id_empresa','class'=>'form-control','style'=>'width:100%',(isset($data->fk_id_empresa) && $data->fk_id_empresa != $actual_company_id) || !isset($data->fk_id_empresa) ? 'disabled':'']) !!}
    		</div>  --}}
    	</div>
    	<div class="form-group col-md-3 col-sm-6">
			<div id="loadingsucursales" class="w-100 h-100 text-center text-white align-middle loadingData" style="display: none">
				Ingresando sucursales... <i class="material-icons align-middle loading">cached</i>
			</div>
    		{!! Form::cSelect('* Sucursal','fk_id_sucursal',$sucursales ?? [],[
				'style' => 'width:100%;',
				'data-url' => companyAction('HomeController@index').'/administracion.sucursales/api',
				'class' => !Route::currentRouteNamed(currentRouteName('show')) ? 'select2' : ''
			]) !!}
    	</div>
    	<div class="form-group col-md-3 col-sm-6">
    		{!! Form::cSelect('*Proveedor','fk_id_proveedor',$proveedores ?? [],[
				'style' => 'width:100%;',
				'data-url'=>companyAction('Compras\OrdenesController@getProveedores'),
				'class' => !Route::currentRouteNamed(currentRouteName('show')) ? 'select2' : ''
			])!!}
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
    	<div class="form-group col-md-3 col-sm-6">
			{!! Form::cSelectWithDisabled('*Moneda','fk_id_moneda',isset($monedas)?$monedas:[],['class'=>'select2'])!!}
		</div>
    	<div class="form-group col-md-6">
    		{!! Form::cTextArea('Condiciones de la oferta','condiciones_oferta',['rows'=>3,'maxlength'=>'255'])!!}
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
								{!! Form::cSelect('Cliente','fk_id_cliente',$clientes ?? [],[
									'style' => 'width:100%;',
									'class' => !Route::currentRouteNamed(currentRouteName('show')) ? 'select2' : ''
								])!!}
							</div>
							<div class="form-group input-field col-md-3 col-sm-6">
								<div id="loadingproyectos" class="w-100 h-100 text-center text-white align-middle loadingData" style="display: none">
									Agregando proyectos... <i class="material-icons align-middle loading">cached</i>
								</div>
								{!! Form::cSelect('Proyecto','fk_id_proyecto',$proyectos ?? [],[
									'style' => 'width:100%;',
									'class' => !Route::currentRouteNamed(currentRouteName('show')) ? 'select2' : '',
									'data-url' => companyAction('HomeController@index').'/proyectos.proyectos/api',
									])!!}
							</div>
    						<div class="form-group input-field col-md-3 col-sm-6">
    							{!! Form::cSelect('* SKU','fk_id_sku',$skus ?? [],[
    								'style' => 'width:100%;',
									'class' => !Route::currentRouteNamed(currentRouteName('show')) ? 'select2' : '',
    								'data-url' => companyAction('HomeController@index').'/inventarios.upcs/api',
    								'data-url-tiempo_entrega'=>companyAction('HomeController@index').'/sociosnegocio.productossociosnegocio/api'
    							]) !!}
    						</div>
    						<div class="form-group input-field col-md-3 col-sm-6">
								<div id="loadingupcs" class="w-100 h-100 text-center text-white align-middle loadingData" style="display: none">
									Ingresando UPC's... <i class="material-icons align-middle loading">cached</i>
								</div>
    							{!! Form::cSelect('UPC','fk_id_upc',$upcs ?? [],[
									'data-url' => companyAction('HomeController@index').'/inventarios.upcs/api',
    								'style' => 'width:100%;',
									'class' => !Route::currentRouteNamed(currentRouteName('show')) ? 'select2' : '',
								]) !!}
    						</div>
    						<div class="form-group input-field col-md-2 col-sm-6">
    							{!! Form::cSelect('Unidad medida','fk_id_unidad_medida',isset($unidadesmedidas)?$unidadesmedidas:[],['class'=>'select2']) !!}
    						</div>
    						<div class="form-group input-field col-md-2 col-sm-4">
    							{!! Form::cText('* Cantidad','cantidad',['autocomplete'=>'off','placeholder'=>'0'])!!}
    						</div>
    						<div class="form-group input-field col-md-2 col-sm-6">
    							{!! Form::cSelect('Tipo de impuesto','fk_id_impuesto',$impuestos ?? [],[
									'data-url'=>companyAction('HomeController@index').'/administracion.impuestos/api'
									])!!}
								{{Form::hidden('',null,['id'=>'impuesto'])}}
    						</div>
    						<div class="form-group input-field col-md-2 col-sm-6">
								<div id="loadingprecio" class="w-100 h-100 text-center text-white align-middle loadingData" style="display: none">
									Actualizando precio... <i class="material-icons align-middle loading">cached</i>
								</div>
    							{!! Form::label('precio_unitario','* Precio Unitario') !!}
    							<div class="input-group">
    								<span class="input-group-addon">$</span>
    								{!! Form::number('precio_unitario',0,['placeholder'=>'999999.00','class'=>'form-control']) !!}
    							</div>
    						</div>
    						<div class="form-group input-field col-md-2 col-sm-6">
								{!! Form::label('descuento_detalle','Descuento producto') !!}
								<div class="input-group">
									{!! Form::text('descuento_detalle',0,['placeholder'=>'99.0000','class'=>'form-control']) !!}
									<span class="input-group-addon">%</span>
								</div>
							</div>
    						<div class="col-sm-12 text-center">
    							<div class="sep">
    								<div class="sepBtn">
    									<button style="width: 4em; height:4em; border-radius:50%;" class="btn btn-primary btn-large" data-position="bottom" data-delay="50" data-toggle="Agregar" title="Agregar" onclick="agregarProducto()" type="button" id="agregar">
											<i class="material-icons">add</i>
										</button>
    								</div>
    							</div>
    						</div>
    					</div>
    				</fieldset>
    			</div>
    			@endif
    			<div class="card-body">
    				<table id="productos" class=" table table-responsive-sm table-striped table-hover">
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
								@if(Route::currentRouteNamed(currentRouteName('edit')) || Route::currentRouteNamed(currentRouteName('create')))
									<th>Eliminar</th>
								@endif
							</tr>
    					</thead>
    					<tbody>
    					@if(isset($solicitud) && Route::currentRouteNamed(currentRouteName('create')))
							@foreach( $solicitud->detalle->where('cerrado',false) as $detalle)
							{{dump($detalle)}}
    							<tr class="list-left bg-light">
    								<td>
    									{{isset($detalle->fk_id_documento)?$detalle->fk_id_documento:'N/A'}}
    									{!! Form::hidden('relations[has][detalle]['.$detalle->id_documento_detalle.'][fk_id_documento_base]',$detalle->fk_id_documento) !!}
										{!! Form::hidden('relations[has][detalle]['.$detalle->id_documento_detalle.'][fk_id_linea]',$detalle->id_documento_detalle) !!}
    								</td>
    								<td>
    									{!! Form::hidden('relations[has][detalle]['.$detalle->id_documento_detalle.'][fk_id_sku]',$detalle->fk_id_sku) !!}
    									{{$detalle->sku->sku}}
    								</td>
    								<td>
    									{!! Form::hidden('relations[has][detalle]['.$detalle->id_documento_detalle.'][fk_id_upc]',isset($detalle->fk_id_upc)?$detalle->fk_id_upc:'') !!}
    									{{isset($detalle->fk_id_upc)?$detalle->upc->upc:'UPC no seleccionado'}}
    								</td>
    								<td>
    									{{str_limit($detalle->sku->descripcion_corta,250)}}
    								</td>
    								<td>
    									{{str_limit($detalle->sku->descripcion,250)}}
    								</td>
    								<td>
    									{!! Form::hidden('relations[has][detalle]['.$detalle->id_documento_detalle.'][fk_id_cliente]',$detalle->fk_id_cliente) !!}
    									{{isset($detalle->cliente->nombre_corto)?$detalle->cliente->nombre_corto:'Sin cliente'}}
    								</td>
    								<td>
    									{!! Form::hidden('relations[has][detalle]['.$detalle->id_documento_detalle.'][fk_id_proyecto]',$detalle->fk_id_proyecto) !!}
    									{{isset($detalle->proyecto->proyecto)?$detalle->proyecto->proyecto:'Sin proyecto'}}
    								</td>
    								<td>
    									{!! Form::hidden('relations[has][detalle]['.$detalle->id_documento_detalle.'][fk_id_unidad_medida]',$detalle->fk_id_unidad_medida) !!}
    									{{$detalle->unidad_medida->nombre}}
    								</td>
    								<td>
    									{!! Form::text('relations[has][detalle]['.$detalle->id_documento_detalle.'][cantidad]',
    									$detalle->cantidad,
    									['class'=>'form-control cantidad','id'=>'cantidad'.$detalle->id_documento_detalle,'style'=>'min-width:100px','placeholder'=>'0','onkeyup'=>'total_row('.$detalle->id_documento_detalle.')']) !!}
    								</td>
    								<td>
    									{!! Form::hidden('relations[has][detalle]['.$detalle->id_documento_detalle.'][fk_id_impuesto]',
    									$detalle->fk_id_impuesto,
    									['data-porcentaje'=>$detalle->impuesto->porcentaje,'id'=>'fk_id_impuesto'.$detalle->id_documento_detalle]) !!}
    									{{$detalle->impuesto->impuesto}}
    								</td>
    								<td>
    									{!! Form::text('relations[has][detalle]['.$detalle->id_documento_detalle.'][precio_unitario]',
    									number_format($detalle->precio_unitario,2,'.',''),
    									['class'=>'form-control precio','id'=>'precio_unitario'.$detalle->id_documento_detalle,'style'=>'min-width:100px','placeholder'=>'999999.00','onkeyup'=>'total_row('.$detalle->id_documento_detalle.')']) !!}
    								</td>
    								<td>
    									{!! Form::text('relations[has][detalle]['.$detalle->id_documento_detalle.'][descuento_detalle]',
    									null,
    									['class'=>'form-control descuento','id'=>'descuento_detalle'.$detalle->id_documento_detalle,'style'=>'min-width:100px','placeholder'=>'99.0000','onkeyup'=>'total_row('.$detalle->id_documento_detalle.')']) !!}
    								</td>
    								<td>
    									<input type="text" class="form-control total" id="total{{$detalle->id_documento_detalle}}" style="min-width: 100px" name="{{'relations[has][detalle]['.$detalle->id_documento_detalle.'][total_producto]'}}" readonly value="{{number_format($detalle->importe,2,'.','')}}">
    								</td>
    								<td>
    									<button class="btn is-icon text-primary bg-white"
    											type="button" data-item-id="{{$detalle->id_documento_detalle}}"
    											id="{{$detalle->id_documento_detalle}}" data-delay="50"
    											onclick="borrarFila(this)" data-delete-type="single">
    										<i class="material-icons">delete</i>
    									</button>
    								</td>
    							</tr>
    						@endforeach
						@elseif( isset( $data->detalle ) )
    						@foreach( $data->detalle->where('cerrado',false) as $detalle)
    							<tr class="{{isset($detalle->fk_id_documento_base)?'list-left bg-light':''}}">
    								<th>
										{!! Form::hidden('relations[has][detalle]['.$detalle->id_documento_detalle.'][id_documento_detalle]',$detalle->id_documento_detalle) !!}
										{!! Form::hidden('relations[has][detalle]['.$detalle->id_documento_detalle.'][fk_id_sku]',$detalle->fk_id_sku) !!}
										{!! Form::hidden('relations[has][detalle]['.$detalle->id_documento_detalle.'][fk_id_upc]',$detalle->fk_id_upc) !!}
    									{!! Form::hidden('relations[has][detalle]['.$detalle->id_documento_detalle.'][fk_id_cliente]',$detalle->fk_id_cliente) !!}
										{!! Form::hidden('relations[has][detalle]['.$detalle->id_documento_detalle.'][fk_id_proyecto]',$detalle->fk_id_proyecto) !!}
										{!! Form::hidden('relations[has][detalle]['.$detalle->id_documento_detalle.'][cantidad]',$detalle->cantidad) !!}
										{!! Form::hidden('relations[has][detalle]['.$detalle->id_documento_detalle.'][fk_id_unidad_medida]',$detalle->fk_id_unidad_medida) !!}
										{!! Form::hidden('relations[has][detalle]['.$detalle->id_documento_detalle.'][fk_id_impuesto]',$detalle->fk_id_impuesto) !!}
										{!! Form::hidden('relations[has][detalle]['.$detalle->id_documento_detalle.'][precio_unitario]',$detalle->precio_unitario) !!}
										{!! Form::hidden('relations[has][detalle]['.$detalle->id_documento_detalle.'][descuento_detalle]',$detalle->descuento_detalle) !!}
										{!! Form::hidden('relations[has][detalle]['.$detalle->id_documento_detalle.'][total_producto]',$detalle->total_producto,['class'=>'totalRow']) !!}
										{{isset($detalle->fk_id_documento_base)?$detalle->fk_id_documento_documento:'N/A'}}
    								</th>
    								<td>
										<img style="max-height:40px" src="img/sku.png" alt="sku"/>
    									{{$detalle->sku->sku}}
    								</td>
    								<td>
										<img style="max-height:40px" src="img/upc.png" alt="upc"/>
    									{{isset($detalle->fk_id_upc)?$detalle->upc->upc:'UPC no seleccionado'}}
    								</td>
    								<td>
    									{{$detalle->sku->descripcion_corta}}
    								</td>
    								<td>
    									{{$detalle->sku->descripcion}}
    								</td>
    								<td>
    									{{isset($detalle->cliente->nombre_corto)?$detalle->cliente->nombre_corto:'Sin cliente'}}
    								</td>
    								<td>
    									{{isset($detalle->proyecto->proyecto)?$detalle->proyecto->proyecto:'Sin proyecto'}}
    								</td>
    								<td>
    									{{$detalle->unidadMedida->nombre}}
    								</td>
    								<td>
    									{{$detalle->cantidad}}
    								</td>
    								<td>
    									{{$detalle->impuesto->impuesto}}
    								</td>
    								<td>
    									{{number_format($detalle->precio_unitario,2,'.','')}}
    								</td>
    								<td>
    									{{number_format($detalle->descuento_detalle,2,'.','')}}
    								</td>
    								<td>
										{{number_format($detalle->total_producto,2,'.','')}}
    								</td>
									@if(Route::currentRouteNamed(currentRouteName('edit')) || Route::currentRouteNamed(currentRouteName('create')))
    								<td>
										<button class="btn is-icon text-primary bg-white" type="button" data-item-id="{{$detalle->id_documento_detalle}}" id="{{$detalle->id_documento_detalle}}" data-delay="50" onclick="borrarFila(this)" data-delete-type="single">
											<i class="material-icons">delete</i>
										</button>
									</td>
									@endif
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
    										{!! Form::cText('','descuento_oferta',['placeholder'=>'99.0000']) !!}
    										<span class="input-group-addon">%</span>
    									</div>
    								</div>
    							</td>
    							<td colspan="4">
    								{{ Form::label('total_oferta', 'Total de la oferta') }}
    								<div class="form-group col-md-12">
    									<div class="input-group">
											<span class="input-group-addon">$</span>
    										{!! Form::cText('','total_oferta',['readonly']) !!}
    									</div>
    								</div>
    							</td>
    							<td colspan="2"></td>
    						</tr>
    					</tfoot>
    				</table>
    			</div>
    		</div>
    	</div>
    </div>
@endsection

{{-- DONT DELETE --}}
@index
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
            window['smart-model'].collections.itemsOptions.supply = {a: {
                'html': '<i class="material-icons">shopping_cart</i>',
                'href' : '{!! url($menuempresa->conexion."/compras/#ID#/2/ordenes/crear") !!}',
    //            'href' : '#',
                'class': 'btn is-icon',
                'rv-hide-comprar':'',
                'rv-get-comprar-url':'',
                'data-toggle':'tooltip',
                'title':'Ordenar'
            }};
            window['smart-model'].actions.itemsCancel = function(e, rv){
                $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
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
                    '<div class="alert alert-warning text-center"><span class="text-danger">La cancelación de un documento es irreversible.</span><br>'+
    				'Para continuar, especifique el motivo y de click en cancelar.</div>'+
                    '<div class="form-group">' +
                    '<label for="recipient-name" class="form-control-label">Cancelar:</label>' +
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

@crear
	@section('form-title','Agregar Oferta de Compra')
@endif

@editar
	@section('form-title','Editar Oferta de Compra')
@endif

@ver
	@section('extraButtons')
		@parent
		{!!isset($data->id_documento) ? HTML::decode(link_to(companyAction('impress',['id'=>$data->id_documento]), '<i class="material-icons align-middle">print</i> Imprimir', ['class'=>'btn btn-info imprimir'])) : ''!!}
		{!! $data->fk_id_estatus_oferta == 1 ? HTML::decode(link_to(url($menuempresa->conexion.'/compras/'.$data->id_documento.'/2/ordenes/crear'), '<i class="material-icons align-middle">shopping_cart</i> Ordenar', ['class'=>'btn btn-info imprimir'])) : '' !!}
	@endsection
@section('form-title','Datos de la Oferta de Compra')
@endif