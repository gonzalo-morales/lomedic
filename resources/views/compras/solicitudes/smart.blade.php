@extends(smart())
@section('form-title', 'Solicitudes de Compra')

@section('header-top')
	<link rel="stylesheet" href="{{ asset('vendor/vanilla-datatables/vanilla-dataTables.css') }}">
@endsection
@section('header-bottom')
	@parent
	<script src="{{ asset('vendor/vanilla-datatables/vanilla-dataTables.js') }}"></script>
	@if(!Route::currentRouteNamed(currentRouteName('index')))
    <script type="text/javascript">
        var proveedores_js = '{{$js_proveedores ?? ''}}';
    </script>
	<script type="text/javascript" src="{{ asset('js/solicitudes_compras.js') }}"></script>
	@endif
@endsection

@section('form-content')
	{{ Form::setModel($data) }}
	<div class="row">
		<div class="form-group col-md-4 col-sm-6">
			{{ Form::label('fk_id_solicitante', '* Solicitante') }}
			{!! Form::select('fk_id_solicitante',isset($empleados)?$empleados:[],null,['id'=>'fk_id_solicitante','data-url'=>companyAction('RecursosHumanos\EmpleadosController@obtenerEmpleado'),'class'=>'form-control','style'=>'width:100%']) !!}
			{{ $errors->has('fk_id_solicitante') ? HTML::tag('span', $errors->first('fk_id_solicitante'), ['class'=>'help-block deep-orange-text']) : '' }}
			{{Form::hidden('_id_solicitante',null,['id'=>'_id_solicitante','data-url'=>companyAction('Administracion\SucursalesController@sucursalesEmpleado',['id'=>'?id'])])}}
		</div>
		<div class="form-group input-field col-md-4 col-sm-6">
			{{--Se utilizan estas comprobaciones debido a que este campo se carga dinámicamente con base en el solicitante seleccionado y no se muestra el que está por defecto sin esto--}}
			{{Form::cSelect('*Sucursal','fk_id_sucursal',$sucursalesempleado ?? [])}}
			{!! Form::hidden('sucursal_defecto',null,['id'=>'sucursal_defecto']) !!}
		</div>
		<div class="form-group input-field col-md-2 col-sm-6">
			{{ Form::label('fecha_necesidad', '* ¿Para cuándo se necesita?') }}
			{!! Form::text('fecha_necesidad',null,['id'=>'fecha_necesidad','class'=>'datepicker form-control','value'=>old('fecha_necesidad'),'placeholder'=>'Selecciona una fecha']) !!}
		</div>
		<div class="form-group input-field col-md-2 col-sm-6">
			{{--{!! Form::select('fk_id_estatus_solicitud', \App\Http\Models\Compras\EstatusSolicitudes::all()->pluck('estatus','id_estatus'),null, ['id'=>'fk_id_sucursal']) !!}--}}
			{{ Form::label('estatus_solicitud', '* Estatus de la solicitud') }}
			@if(Route::currentRouteNamed(currentRouteName('edit')) || Route::currentRouteNamed(currentRouteName('show')))
				{!! Form::text('estatus_solicitud',null,['disabled','class'=>'form-control']) !!}
			@elseif(Route::currentRouteNamed(currentRouteName('create')))
				{!! Form::text('estatus_solicitud','Abierto',['disabled','class'=>'form-control']) !!}
			@endif
		</div>
		{{--Si la solicitud está cancelada--}}
			@if(isset($data->fk_id_estatus_solicitud) && $data->fk_id_estatus_solicitud ==3)
				<div class="form-group input-field col-md-3 col-sm-12">
					{{ Form::label('fecha_cancelacion','Fecha de cancelación') }}
					{!! Form::text('fecha_cancelacion',null,['disabled','class'=>'form-control']) !!}
				</div>
				<div class="form-group input-field col-md-9 col-sm-12">
					{{ Form::label('motivo_cancelacion','Motivo de la cancelación') }}
					{!! Form::text('motivo_cancelacion',null,['disabled','class'=>'form-control']) !!}
				</div>
			@endif
	</div>
	<div class="row">
		<div class="col-sm-12">
			<h3>Detalle de la solicitud</h3>
			<div class="card z-depth-1-half">
				@if(!Route::currentRouteNamed(currentRouteName('show')))
				<div class="card-header">
					<fieldset name="detalle-form" id="detalle-form">
						<div class="row">
							<div class="form-group input-field col-md-3 col-sm-6">
								{{--{{Form::label('fk_id_sku','SKU')}}--}}
								{{--{!!Form::select('fk_id_sku',isset($skus)?$skus:[],null,['id'=>'fk_id_sku','class'=>'form-control','style'=>'width:100%'])!!}--}}
								{{Form::cSelectWithDisabled('*SKU','fk_id_sku',$skus??[],['id'=>'fk_id_sku','class'=>'select2'])}}
							</div>
							<div class="form-group input-field col-md-3 col-sm-6">
								{{Form::label('fk_id_upc','Código de barras')}}
								<div id="loadingUPC" class="w-100 h-100 text-center text-white align-middle loadingData" style="display: none">
									Cargando datos... <i class="material-icons align-middle loading">cached</i>
								</div>
								{!! Form::select('fk_id_upc',[],null,['id'=>'fk_id_upc','disabled',
								'data-url'=>companyAction('Inventarios\ProductosController@obtenerUpcs',['id'=>'?id']),
								'class'=>'form-control','style'=>'width:100%']) !!}
							</div>
							<div class="form-group input-field col-md-3 col-sm-6">
                                <div id="loadingproveedor" class="w-100 h-100 text-center text-white align-middle loadingData" style="display: none">
                                    Cargando datos... <i class="material-icons align-middle loading">cached</i>
                                </div>
                                {{Form::cSelect('Proveedor','fk_id_proveedor',$proveedores ?? [],['data-url'=>companyAction('HomeController@index').'/SociosNegocio.sociosnegocio/api'])}}
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
							<div class="form-group input-field col-md-2 col-sm-4">
								{{Form::label('fk_id_unidad_medida','Unidad de medida')}}
								{!! Form::select('fk_id_unidad_medida',
								isset($unidadesmedidas) ? $unidadesmedidas : [],
								null,['id'=>'fk_id_unidad_medida','class'=>'form-control select2','style'=>'width:100%']) !!}
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
										data-position="bottom" data-delay="50" data-tooltip="Agregar" type="button" onclick="agregarProducto()" id="agregar"><i
											class="material-icons">add</i></button>
									</div>
								</div>
							</div>
						</div>
					</fieldset>
				</div>
				@endif
			    <div class="card-body" style="height: auto">
					<table id="productos" class="table-responsive highlight" data-url="{{companyAction('Compras\SolicitudesController@store')}}"
					data-delete="{{companyAction('Compras\DetalleSolicitudesController@destroyMultiple')}}"
					data-impuestos="{{companyAction('Administracion\ImpuestosController@obtenerImpuestos')}}"
							data-porcentaje="{{companyAction('Administracion\ImpuestosController@obtenerPorcentaje',['id'=>'?id'])}}">
						<thead>
							<tr>
								<th>Documento</th>
								<th>SKU</th>
								<th>Código de Barras</th>
								<th>Proveedor</th>
								<th>Fecha necesidad</th>
								<th>Proyecto</th>
								<th>Cantidad</th>
								<th>Unidad de medida</th>
								<th>Tipo de impuesto</th>
								<th>Precio unitario</th>
								<th>Importe</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
						@if(!empty($detalles_documento) && Route::currentRouteNamed(currentRouteName('create')))
							@foreach($detalles_documento as $index => $detalle)
								<tr>
									<td>
										{{Form::hidden('_detalles['.$index.'][fk_id_documento_base]',$detalle->fk_id_documento)}}
										{{$detalle->fk_id_documento}}
									</td>
									<td>
										{{Form::hidden('_detalles['.$index.'][fk_id_tipo_documento_base]',$detalle->fk_id_tipo_documento)}}
										{!! Form::hidden('_detalles['.$index.'][fk_id_sku]',$detalle->fk_id_sku) !!}
										{{$detalle->sku->sku ?? ''}}
									</td>
									<td>
										{!! Form::hidden('_detalles['.$index.'][fk_id_upc]',$detalle->fk_id_upc) !!}
										{{$detalle->upc->upc ?? ''}}
									</td>
									<td>
{{--										{!! Form::hidden('detalles['.$detalle->id_documento_detalle.'][fk_id_proveedor]',$detalle->fk_id_proveedor) !!}--}}
										{{Form::Select('_detalles['.$index.'][fk_id_proveedor]',isset($proveedores) ? $proveedores->prepend('Selecciona un proveedor',0) : [],$detalle->fk_id_proveedor ?? 0,['class'=>'form-control custom-select'],[0=>['disabled']])}}
									</td>
									<td>
										{!! Form::hidden('_detalles['.$index.'][fecha_necesario]',$detalle->fecha_necesario ?? Carbon\Carbon::now()->toDateString()) !!}
										{{$detalle->fecha_necesario ?? Carbon\Carbon::now()->toDateString()}}
									</td>
									<td>
										{{Form::Select('_detalles['.$index.'][fk_id_proyecto]',isset($proyectos) ? $proyectos->prepend('Selecciona un proyecto',0) : [],$detalle->fk_id_proveedor ?? 0,['class'=>'form-control custom-select'],[0=>['disabled']])}}
									</td>
									<td>
										{{Form::hidden('_detalles['.$index.'][cantidad]',$detalle->cantidad)}}
										{{$detalle->cantidad}}
									</td>
									<td>
										{{Form::Select('_detalles['.$index.'][fk_id_unidad_medida]',isset($unidadesmedidas) ? $unidadesmedidas->prepend('Selecciona una unidad',0) : [],$detalle->fk_id_unidad_medida ?? 0,['class'=>'form-control custom-select requerido'],[0=>['disabled']])}}
									</td>
									<td>
										{{Form::Select('_detalles['.$index.'][fk_id_impuesto]',isset($impuestos) ? $impuestos->prepend('Selecciona una unidad',0) : [],$detalle->fk_id_impuesto ?? 0,['class'=>'form-control custom-select requerido'],[0=>['disabled']])}}
									</td>
									<td>
										{{Form::hidden('_detalles['.$index.'][precio_unitario]',number_format($detalle->precio_unitario,2,'.',''))}}
										{{number_format($detalle->precio_unitario,2,'.','')}}
									</td>
									<td>
										{{Form::hidden('_detalles['.$index.'][importe]',number_format($detalle->importe,2,'.',''))}}
										{{number_format($detalle->importe,2,'.','')}}
									</td>
									<td>
										<button class="btn is-icon text-primary bg-white "
										   type="button" data-item-id="{{$detalle->id_documento_detalle}}"
										   id="{{$detalle->id_documento_detalle}}" data-delay="50"
										   onclick="borrarFila_edit(this)" data-delete-type="single">
											<i class="material-icons">delete</i>
										</button>
									</td>
								</tr>
							@endforeach
						@elseif( isset( $data->detalleSolicitudes ) )
							@foreach( $data->detalleSolicitudes as $detalle)
								<tr>
									<td>
										{{$detalle->fk_id_documento_base ?? 'N/A'}}
									</td>
									<td>
										{!! Form::hidden('detalles['.$detalle->id_documento_detalle.'][id_documento_detalle]',$detalle->id_documento_detalle,['class'=>'id']) !!}
										{!! Form::hidden('detalles['.$detalle->id_documento_detalle.'][fk_id_sku]',$detalle->fk_id_sku) !!}
										{{$detalle->sku->sku}}
										{!! Form::hidden('detalles['.$detalle->id_documento_detalle.'][fk_id_documento_base]',$detalle->fk_id_documento_base) !!}
									</td>
									<td>
										{!! Form::hidden('detalles['.$detalle->id_documento_detalle.'][fk_id_upc]',$detalle->fk_id_upc) !!}
										{{$detalle->upc->upc ?? ''}}
									</td>
									<td>
										{!! Form::hidden('detalles['.$detalle->id_documento_detalle.'][fk_id_proveedor]',$detalle->fk_id_proveedor) !!}
										{{$detalle->proveedor->nombre_comercial ?? 'Sin proveedor'}}
									</td>
									<td>
										{!! Form::hidden('detalles['.$detalle->id_documento_detalle.'][fecha_necesario]',$detalle->fecha_necesario) !!}
										{{$detalle->fecha_necesario}}</td>
									<td>
										@if(!Route::currentRouteNamed(currentRouteName('edit')))
											{!! Form::hidden('detalles['.$detalle->id_documento_detalle.'][fk_id_proyecto]',$detalle->fk_id_proyecto) !!}
											{{$detalle->proyecto->proyecto ?? 'Sin proyecto'}}
										@else
											{!! Form::select('detalles['.$detalle->id_documento_detalle.'][fk_id_proyecto]',
													isset($proyectos) ? $proyectos->prepend('Selecciona un proyecto','0') : [],
													$detalle->fk_id_proyecto,['id'=>'detalles['.$detalle->id_documento_detalle.'][fk_id_proyecto]',
													'class'=>'form-control detalle_select','style'=>'width:100%'])
											!!}
										@endif
									</td>
									<td>
										@if (!Route::currentRouteNamed(currentRouteName('edit')))
											{!! Form::hidden('detalles['.$detalle->id_documento_detalle.'][cantidad]',$detalle->cantidad) !!}
											{{$detalle->cantidad}}
										@else
											{!! Form::text('detalles['.$detalle->id_documento_detalle.'][cantidad]',$detalle->cantidad,
											['class'=>'form-control cantidad',
											'id'=>'cantidad'.$detalle->id_documento_detalle,
											'onkeypress'=>'total_producto_row('.$detalle->id_documento_detalle.',"old")']) !!}
										@endif
									</td>
									<td>
										{!! Form::hidden('detalles['.$detalle->id_documento_detalle.'][fk_unidad_medida]',$detalle->fk_unidad_medida) !!}
										{{$detalle->unidad_medida->nombre}}
									</td>
									<td>
										@if (!Route::currentRouteNamed(currentRouteName('edit')))
											{!! Form::hidden('detalles['.$detalle->id_documento_detalle.'][fk_id_impuesto]',$detalle->fk_id_impuesto) !!}
											{{$detalle->impuesto->impuesto}}
										@else
											{!! Form::select('detalles['.$detalle->id_documento_detalle.'][fk_id_impuesto]',$impuestos,
													$detalle->fk_id_impuesto,['class'=>'form-control detalle_select','style'=>'width:100%','id'=>'fk_id_impuesto'.$detalle->id_documento_detalle,
													'onchange'=>'total_producto_row('.$detalle->id_documento_detalle.',"old")'])
											!!}
										@endif
									</td>
									<td>
										@if(!Route::currentRouteNamed(currentRouteName('edit')))
											{!! Form::hidden('detalles['.$detalle->id_documento_detalle.'][precio_unitario]',$detalle->precio_unitario) !!}
											{{number_format($detalle->precio_unitario,2,'.','')}}
										@else
											{!! Form::text('detalles['.$detalle->id_documento_detalle.'][precio_unitario]',number_format($detalle->precio_unitario,2,'.','')
											,['class'=>'form-control precio_unitario','onkeypress'=>'total_producto_row('.$detalle->id_documento_detalle.',"old")',
											'id'=>'precio_unitario'.$detalle->id_documento_detalle]) !!}
										@endif
									</td>
									<td>
										@if (!Route::currentRouteNamed(currentRouteName('edit')))
											{!! Form::hidden('detalles['.$detalle->id_documento_detalle.'][importe]',$detalle->importe) !!}
											{{number_format($detalle->importe,2,'.','')}}
										@else
											{!! Form::text('detalles['.$detalle->id_documento_detalle.'][importe]',number_format($detalle->importe,2,'.','')
											,['class'=>'form-control','id'=>'importe'.$detalle->id_documento_detalle,'readonly','style'=>'min-width:100px'])!!}
										@endif
									<td>
										{{--Si se va a editar, agrega el botón para "eliminar" la fila--}}
										@if(Route::currentRouteNamed(currentRouteName('edit')) && $data->fk_id_estatus_solicitud == 1)
											<button class="btn is-icon text-primary bg-white "
											   type="button" data-item-id="{{$detalle->id_documento_detalle}}"
											   id="{{$detalle->id_documento_detalle}}" data-delay="50"
											   onclick="borrarFila_edit(this)" data-delete-type="single">
											<i class="material-icons">delete</i></button>
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
	</div>
@endsection

{{-- DONT DELETE --}}

@index
	@section('smart-js')
		<script type="text/javascript">
            if ( sessionStorage.reloadAfterPageLoad ) {
                sessionStorage.clear();
                $.toaster({
                    priority: 'success', title: 'Exito', message: 'Solicitud cancelada',
                    settings:{'timeout': 5000, 'toaster':{'css':{'top':'5em'}}}
                });
            }
		</script>
	@parent
	<script type="text/javascript">
         rivets.binders['hide-delete'] = {
         	bind: function (el) {
         		if(el.dataset.fk_id_estatus_solicitud != 1)
         		{
             		console.log(el);
         			$(el).hide();
         		}
         	}
         };
         rivets.binders['hide-update'] = {
             bind: function (el) {
                 if(el.dataset.fk_id_estatus_solicitud != 1)
                 {
                     $(el).hide();
                 }
             }
         };
         rivets.binders['hide-oferta'] = {
                 bind: function (el) {
    				 if(el.dataset.fk_id_estatus_solicitud != 1)
    				 {
    				     $(el).hide();
    				 }
                 }
    		 };
         rivets.binders['hide-comprar'] = {
             bind: function (el) {
				 if(el.dataset.fk_id_estatus_solicitud != 1)
				 {
				     $(el).hide();
				 }
             }
		 };
         rivets.binders['get-comprar-url'] = {
             bind: function (el) {
                 el.href = el.href.replace('#ID#',el.dataset.itemId);
//				 el.href = window['smart-view'].dataset.itemSupplyUrl.replace('#ID#',el.dataset.itemId);
             }
		 };
         rivets.binders['get-offer-url'] = {
             bind: function (el) {
				 el.href = el.href.replace('#','{{$menuempresa->conexion}}/compras/solicitudes/'+el.dataset.itemId+'/ofertas/crear');
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
        window['smart-model'].collections.itemsOptions.offer = {a: {
        'html': '<i class="material-icons">attach_money</i>',
        'class': 'btn is-icon',
		'href':'#',
		'rv-get-offer-url':'',
		'rv-hide-oferta':'',
		'data-toggle':'tooltip',
		'title':'Crear oferta'
        }};
		window['smart-model'].collections.itemsOptions.supply = {a: {
		'html': '<i class="material-icons">shopping_cart</i>',
{{--		'href' : '{!! companyAction('Compras\OrdenesController@createSolicitudOrden',['id'=>'#ID#']) !!}',--}}
		'href' : '{!! url($menuempresa->conexion."/compras/#ID#/1/ordenes/crear") !!}',
		'class': 'btn is-icon',
		'rv-hide-comprar':'',
		'rv-get-comprar-url':'',
		'data-toggle':'tooltip',
		'title':'Ordenar'
        }};
		window['smart-model'].actions.itemsCancel = function(e, rv, motivo){
		    if(!motivo.motivo_cancelacion){
                $.toaster({
                    priority : 'danger',
                    title : '¡Error!',
                    message : 'Por favor escribe un motivo por el que se está cancelando esta solicitud de compra',
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
                $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
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
                title: '¿Estas seguro que deseas cancelar la solicitud?',
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

@crear
	@section('form-title','Agregar Solicitud')
@endif

@editar
	@section('form-title','Editar Solicitud')
@endif

@ver
	@section('extraButtons')
		@parent
		{!! HTML::decode(link_to(companyAction('impress',['id'=>$data->id_documento]), '<i class="material-icons align-middle">print</i> Imprimir', ['class'=>'btn btn-info imprimir'])) !!}
		@if($data->fk_id_estatus_solicitud == 1)
			{!! HTML::decode(link_to(url($menuempresa->conexion.'/compras/'.$data->id_documento.'/1/ordenes/crear'),'<i class="material-icons align-middle">shopping_cart</i> Ordenar',['class'=>'btn btn-info'])) !!}
			{!! HTML::decode(link_to(url($menuempresa->conexion.'/compras/solicitudes/'.$data->id_documento.'/ofertas/crear'),'<i class="material-icons align-middle">attach_money</i> Oferta',['class'=>'btn btn-info'])) !!}
		@endif
	@endsection
	@section('form-title','Datos de la Solicitud')
@endif