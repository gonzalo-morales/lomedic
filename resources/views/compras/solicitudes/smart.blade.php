@extends(smart())
@section('form-title', 'Solicitudes de Compra')

{{--  @section('header-top')
	<link rel="stylesheet" href="{{ asset('vendor/vanilla-datatables/vanilla-dataTables.css') }}">
@endsection  --}}
@section('header-bottom')
	@parent
	@if(!Route::currentRouteNamed(currentRouteName('index')))
    <script type="text/javascript">
		var proveedores_js = '{{$js_proveedores ?? ''}}';
		var sucursales_js = '{{$js_sucursales ?? ''}}';
		var usuarios_js = '{{$js_usuarios ?? ''}}';
		var porcentaje_js = '{{ $js_porcentaje ?? '' }}';
    </script>
	<script type="text/javascript" src="{{ asset('js/solicitudes_compras.js') }}"></script>
	@endif
@endsection

@section('form-content')
	{{ Form::setModel($data) }}
	<div class="row">
		<div class="form-group col-md-4 col-sm-6">
			{{Form::cSelect('* Solicitante','fk_id_solicitante',$usuarios ?? [],[
				'style' => 'width:100%;',
				'class' => !Route::currentRouteNamed(currentRouteName('show')) ? 'select2' : '',
			])}}
		</div>
		<div class="form-group input-field col-md-4 col-sm-6">
			<div id="loadingsucursales" class="w-100 h-100 text-center text-white align-middle loadingData" style="display: none">
				Cargando datos... <i class="material-icons align-middle loading">cached</i>
			</div>
			{{--Se utilizan estas comprobaciones debido a que este campo se carga dinámicamente con base en el solicitante seleccionado y no se muestra el que está por defecto sin esto--}}
			{{Form::cSelect('*Sucursal','fk_id_sucursal',$sucursales ?? [],[
                'data-url' => companyAction('HomeController@index').'/administracion.sucursales/api',
                'style' => 'width:100%;',
                'class' => !Route::currentRouteNamed(currentRouteName('show')) ? 'select2' : '',
			])}}
			{{ Form::hidden('fk_id_departamento',isset($data->fk_id_departamento) ? $data->fk_id_departamento : 0, ['id'=>'fk_id_departamento','data-url'=> companyAction('HomeController@index').'/administracion.usuarios/api']) }}
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
			{{ Form::hidden('total_solicitud',
				isset($data->total_solicitud) ? $data->total_solicitud : 0,
				['id' => 'sumImporteSolicitud']
			) }}
			{{ Form::hidden('total_impuesto',
				isset($data->total_impuesto) ? $data->total_impuesto : 0,
				['id' => 'sumImpuestoSolicitud']
			) }}
			{{ Form::hidden('total_subtotal',
				isset($data->total_subtotal) ? $data->total_subtotal : 0,
				['id' => 'sumSubtotalSolicitud']
			) }}
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
								<div id="whaitplease" class="w-100 h-100 text-center text-white align-middle loadingData" style="display: none">
									Espere porfavor... <i class="material-icons align-middle loading">cached</i>
								</div>
								{{Form::cSelectWithDisabled('*SKU','fk_id_sku',$skus??[],['id'=>'fk_id_sku','class'=>'select2'])}}
							</div>
							<div class="form-group input-field col-md-3 col-sm-6">
								{{Form::label('fk_id_upc','* Código de barras')}}
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
                                {{Form::cSelect('* Proveedor','fk_id_proveedor',$proveedores ?? [],[
									'style' => 'width:100%;',
									'class' => !Route::currentRouteNamed(currentRouteName('show')) ? 'select2' : '',
									'data-url'=>companyAction('HomeController@index').'/sociosnegocio.sociosnegocio/api',
								])}}
							</div>
							<div class="form-group input-field col-md-3 col-sm-6">
								{{Form::cSelect('* Proyecto','fk_id_proyecto',$proyectos ?? [],[
									'style' => 'width:100%;',
									'class' => !Route::currentRouteNamed(currentRouteName('show')) ? 'select2' : '',
									'data-url'=>companyAction('HomeController@index').'/sociosnegocio.sociosnegocio/api',
								])}}
							</div>
							<div class="form-group input-field col-md-2 col-sm-4">
								{{ Form::label('fecha_necesario', '* ¿Para cuándo se necesita?') }}
								{!! Form::text('fecha_necesario',null,['id'=>'fecha_necesario','class'=>'datepicker form-control','value'=>old('fecha_necesario'),'placeholder'=>'Selecciona una fecha']) !!}
							</div>
							<div class="form-group input-field col-md-2 col-sm-4">
								{{Form::label('cantidad','* Cantidad')}}
								{!! Form::number('cantidad','1',['id'=>'cantidad','min'=>'1','class'=>'validate form-control cantidad','autocomplete'=>'off']) !!}
							</div>
							<div class="form-group input-field col-md-2 col-sm-4">
								{{Form::label('fk_id_unidad_medida','* Unidad de medida')}}
								{!! Form::select('fk_id_unidad_medida',
								isset($unidadesmedidas) ? $unidadesmedidas : [],
								null,['id'=>'fk_id_unidad_medida','class'=>'form-control select2','style'=>'width:100%']) !!}
							</div>
							<div class="form-group input-field col-md-2 col-sm-6">
								{{Form::label('fk_id_impuesto','* Tipo de impuesto')}}
								{!! Form::select('fk_id_impuesto',$impuestos ?? []
									,null,['id'=>'fk_id_impuesto',
									'data-url'=>companyAction('HomeController@index').'/administracion.impuestos/api',
									'class'=>'form-control','style'=>'width:100%']) !!}
								{{Form::hidden('impuesto',null,['id'=>'impuesto'])}}
							</div>
							<div class="form-group input-field col-md-2 col-sm-6">
								<div id="loadingprecio" class="w-100 h-100 text-center text-white align-middle loadingData" style="display: none">
									Actualizando precio... <i class="material-icons align-middle loading">cached</i>
								</div>
								{{Form::label('precio_unitario','* Precio unitario',['class'=>'validate'])}}

								{!! Form::number('precio_unitario',old('precio_unitario'),['id'=>'precio_unitario','placeholder'=>'0.00','class'=>'validate form-control precio_unitario','autocomplete'=>'off']) !!}
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
					<table id="productos" class="table table-responsive-sm highlight" data-url="{{companyAction('Compras\SolicitudesController@store')}}"
					data-delete="{{companyAction('Compras\DetalleSolicitudesController@destroyMultiple')}}">
						<thead>
							<tr>
								<th>Documento</th>
								<th>SKU(s)</th>
								<th>UPC(s)</th>
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
										{{Form::hidden('relations[has][detalle]['.$index.'][fk_id_documento_base]',$detalle->fk_id_documento)}}
										{{$detalle->fk_id_documento}}
									</td>
									<td>
										{!! Form::hidden('relations[has][detalle]['.$index.'][fecha_necesario]',$detalle->fecha_necesario ?? Carbon\Carbon::now()->toDateString()) !!}
										{{Form::hidden('relations[has][detalle]['.$index.'][fk_id_tipo_documento_base]',$detalle->fk_id_tipo_documento)}}
										{!! Form::hidden('relations[has][detalle]['.$index.'][fk_id_sku]',$detalle->fk_id_sku) !!}
										{!! Form::hidden('relations[has][detalle]['.$index.'][fk_id_upc]',$detalle->fk_id_upc) !!}
										{{$detalle->sku->sku ?? ''}}
									</td>
									<td>
										{{$detalle->upc->upc ?? ''}}
									</td>
									<td>
{{--										{!! Form::hidden('detalles['.$detalle->id_documento_detalle.'][fk_id_proveedor]',$detalle->fk_id_proveedor) !!}--}}
										{{Form::Select('relations[has][detalle]['.$index.'][fk_id_proveedor]',isset($proveedores) ? $proveedores : [], $detalle->fk_id_proveedor ?? 0,['class'=>'form-control custom-select'],[0=>['disabled']])}}
									</td>
									<td>
										{{$detalle->fecha_necesario ?? Carbon\Carbon::now()->toDateString()}}
									</td>
									<td>
										{{Form::Select('relations[has][detalle]['.$index.'][fk_id_proyecto]',isset($proyectos) ? $proyectos : [],$detalle->fk_id_proveedor ?? 0,['class'=>'form-control custom-select'],[0=>['disabled']])}}
									</td>
									<td>
										{{Form::hidden('relations[has][detalle]['.$index.'][cantidad]',$detalle->cantidad)}}
										{{$detalle->cantidad}}
									</td>
									<td>
										{{Form::Select('relations[has][detalle]['.$index.'][fk_id_unidad_medida]',isset($unidadesmedidas) ? $unidadesmedidas : [],$detalle->fk_id_unidad_medida ?? 0,['class'=>'form-control custom-select requerido'],[0=>['disabled']])}}
									</td>
									<td>
										{{Form::Select('relations[has][detalle]['.$index.'][fk_id_impuesto]',isset($impuestos) ? $impuestos  : [],$detalle->fk_id_impuesto ?? 0,['class'=>'form-control custom-select requerido'],[0=>['disabled']])}}
									</td>
									<td>
										{{Form::hidden('relations[has][detalle]['.$index.'][precio_unitario]',number_format($detalle->precio_unitario,2,'.',''))}}
										{{number_format($detalle->precio_unitario,2,'.','')}}
									</td>
									<td>
										{{Form::hidden('relations[has][detalle]['.$index.'][importe]',number_format($detalle->importe,2,'.',''))}}
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
							@elseif( isset( $data->detalle ) )
							@foreach( $data->detalle as $detalle)
							{{--  {{dump($detalle)}}  --}}
									<tr>
										<th>
											{{$detalle->fk_id_documento_base ?? 'N/A'}}
										</th>
										<td>
											<img style="max-height:40px" src="img/sku.png" alt="sku"/>
											{{$detalle->sku->sku}}
											{!! Form::hidden('relations[has][detalle]['.$detalle->id_documento_detalle.'][id_documento_detalle]',$detalle->id_documento_detalle,['class'=>'id']) !!}
											{!! Form::hidden('relations[has][detalle]['.$detalle->id_documento_detalle.'][fk_id_sku]',$detalle->fk_id_sku) !!}
											{!! Form::hidden('relations[has][detalle]['.$detalle->id_documento_detalle.'][fk_id_solicitud]',$detalle->fk_id_solicitud) !!}
											{!! Form::hidden('relations[has][detalle]['.$detalle->id_documento_detalle.'][fk_id_documento_base]',$detalle->fk_id_documento_base) !!}
											{!! Form::hidden('relations[has][detalle]['.$detalle->id_documento_detalle.'][fk_id_upc]',$detalle->fk_id_upc) !!}
											{!! Form::hidden('relations[has][detalle]['.$detalle->id_documento_detalle.'][fk_id_proveedor]',$detalle->fk_id_proveedor) !!}
											{!! Form::hidden('relations[has][detalle]['.$detalle->id_documento_detalle.'][fecha_necesario]',$detalle->fecha_necesario) !!}
											{!! Form::hidden('relations[has][detalle]['.$detalle->id_documento_detalle.'][fk_unidad_medida]',$detalle->fk_id_unidad_medida) !!}
											{!! Form::hidden('relations[has][detalle]['.$detalle->id_documento_detalle.'][fk_id_proyecto]',$detalle->fk_id_proyecto) !!}
											{!! Form::hidden('relations[has][detalle]['.$detalle->id_documento_detalle.'][cantidad]',$detalle->cantidad) !!}
										</td>
										<td>
											<img style="max-height:40px" src="img/upc.png" alt="upc"/>
											{{$detalle->upc->upc ?? ''}}
										</td>
										<td>
											{{$detalle->proveedor->nombre_comercial ?? 'Sin proveedor'}}
										</td>
										<td><i class="material-icons align-middle">today</i>
											{{$detalle->fecha_necesario}}</td>
										<td>
											@if(!Route::currentRouteNamed(currentRouteName('edit')))
												{{$detalle->proyecto->proyecto ?? 'Sin proyecto'}}
											@else
												{!! Form::select('relations[has][detalle]['.$detalle->id_documento_detalle.'][fk_id_proyecto]',
														isset($proyectos) ? $proyectos->prepend('Selecciona un proyecto','0') : [],
														$detalle->fk_id_proyecto,['id'=>'relations[has][detalle]['.$detalle->id_documento_detalle.'][fk_id_proyecto]',
														'class'=>'form-control detalle_select','style'=>'width:100%'])
												!!}
											@endif
										</td>
										<td>
											@if (!Route::currentRouteNamed(currentRouteName('edit')))
												{{$detalle->cantidad}}
											@else
												{!! Form::number('relations[has][detalle]['.$detalle->id_documento_detalle.'][cantidad]',$detalle->cantidad,
												['class'=>'form-control rowCantidad',
												'style'=>'width:80px',
												'id'=>'cantidad'.$detalle->id_documento_detalle,
												'onkeyup'=>'total_producto_row(this)']) !!}
											@endif
										</td>
										<td>
											{{$detalle->unidad_medida->nombre}}
										</td>
										<td>
											@if (!Route::currentRouteNamed(currentRouteName('edit')))
												{!! Form::hidden('relations[has][detalle]['.$detalle->id_documento_detalle.'][fk_id_impuesto]',$detalle->fk_id_impuesto) !!}
												{{$detalle->impuesto->impuesto}}
												@else
												{!! Form::select('relations[has][detalle]['.$detalle->id_documento_detalle.'][fk_id_impuesto]',$impuestos,
														$detalle->fk_id_impuesto,['class'=>'form-control detalle_select selectImpuestos','style'=>'width:100%','id'=>'fk_id_impuesto'.$detalle->id_documento_detalle,
														'onchange'=>'getImpuestos(this)'])
														!!}
												{{Form::hidden('',null,['class'=>'rowImpuestos'])}}
											@endif
										</td>
										<td>
											@if(!Route::currentRouteNamed(currentRouteName('edit')))
												{!! Form::hidden('relations[has][detalle]['.$detalle->id_documento_detalle.'][precio_unitario]',$detalle->precio_unitario) !!}
												{{number_format($detalle->precio_unitario,2,'.','')}}
											@else
												{!! Form::number('relations[has][detalle]['.$detalle->id_documento_detalle.'][precio_unitario]',number_format($detalle->precio_unitario,2,'.','')
												,['class'=>'form-control rowPrecioUnitario','onkeyup'=>'total_producto_row(this)',
												'style'=>'width:80px',
												'id'=>'precio_unitario'.$detalle->id_documento_detalle]) !!}
											@endif
										</td>
										<td class="position-relative">
											@if (!Route::currentRouteNamed(currentRouteName('edit')))
												{!! Form::hidden('relations[has][detalle]['.$detalle->id_documento_detalle.'][importe]',$detalle->importe) !!}
												{{number_format($detalle->importe,2,'.','')}}
											@else
												<div class="w-100 h-100 text-center text-white align-middle loadingData" style="display: none">Calculando el total... <i class="material-icons align-middle loading">cached</i></div>
												{!! Form::number('relations[has][detalle]['.$detalle->id_documento_detalle.'][importe]',number_format($detalle->importe,2,'.','')
												,['class'=>'form-control rowTotal','id'=>'importe'.$detalle->id_documento_detalle,'readonly','style'=>'width:100px'])!!}
											@endif
										<td>
											{{--Si se va a editar, agrega el botón para "eliminar" la fila--}}
											@if(Route::currentRouteNamed(currentRouteName('edit')) && $data->fk_id_estatus_solicitud == 1)
												<button class="btn is-icon text-primary bg-white "
												type="button" data-item-id="{{$detalle->id_documento_detalle}}"
												id="{{$detalle->id_documento_detalle}}" data-delay="50"
												onclick="borrarFila(this)" data-delete-type="single">
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