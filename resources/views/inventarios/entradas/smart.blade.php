@section('header-top')
	<link rel="stylesheet" href="{{ asset('vendor/vanilla-datatables/vanilla-dataTables.css') }}">
@endsection
@section('header-bottom')
	@parent
	<script src="{{ asset('vendor/vanilla-datatables/vanilla-dataTables.js') }}"></script>
	{{--@if (!Route::currentRouteNamed(currentRouteName('index')))--}}
		<script type="text/javascript" src="{{ asset('js/entradas.js') }}"></script>
	{{--@endif--}}
@endsection

@section('content-width', 's12')

@section('form-content')
{{ Form::setModel($data) }}
@if (Route::currentRouteNamed(currentRouteName('show')) || Route::currentRouteNamed(currentRouteName('edit')))
	<div class="row">
		<div class="col-md-12 text-center text-success">
			<h3>No. Entrada {{$data->id_orden}}</h3>
		</div>
	</div>
@endif
<div class="row">
	<div class="col-12 mb-3">
		<div class="row">
			<div class="col-md-6 col-sm-6 col-lg-3">
				<div class="form-group ">
					{{ Form::label('id_sucursal', 'Sucursal') }}
					{!! Form::select('id_sucursal',isset($sucursales)?$sucursales:[],null,['id'=>'id_sucursal','class'=>'form-control select2','data-url'=>companyRoute('getOrdenes')]) !!}
					{{ $errors->has('id_sucursal') ? HTML::tag('span', $errors->first('id_sucursal'), ['class'=>'help-block deep-orange-text']) : '' }}
				</div>
			</div>
			<div class="col-md-6 col-sm-6 col-lg-3">
				<div class="form-group">
					{{ Form::label('id_orden', 'Orden de compra') }}
					{!! Form::select('id_orden',[],null,['id'=>'id_orden','class'=>'form-control select2','data-url'=>companyRoute('getDetalleOrden')]) !!}
					{{ $errors->has('id_orden') ? HTML::tag('span', $errors->first('id_orden'), ['class'=>'help-block deep-orange-text']) : '' }}
				</div>
			</div>
			<div class="col-md-6 col-sm-6 col-lg-3">
				<div class="form-group">
					<label for="documents">Documento</label>
					<input type="text" class="form-control" id="documents" placeholder="ABC1234">
				</div>
			</div>
			<div class="col-md-6 col-sm-6 col-lg-3">
				<div class="form-group">
					<label for="proveedor">Proveedor</label>
					<select class="form-control" id="proveedor">
						{{--<option>Seleccione...</option>--}}
						{{--<option>2</option>--}}
						{{--<option>3</option>--}}
						{{--<option>4</option>--}}
						{{--<option>5</option>--}}
					</select>
				</div>
			</div>
		</div><!--/row forms-->
{{--{{dd($sucursales)}}--}}
<div class="row">
	{{--<div class="form-group col-md-3 col-sm-12">--}}
		{{--{{ Form::label('fk_id_socio_negocio', '* Sucursal') }}--}}
		{{--{!! Form::select('fk_id_socio_negocio',isset($sucursales)?$sucursales:[],null,['id'=>'fk_id_socio_negocio','class'=>'form-control select2','style'=>'width:100%']) !!}--}}
		{{--{{ $errors->has('fk_id_socio_negocio') ? HTML::tag('span', $errors->first('fk_id_socio_negocio'), ['class'=>'help-block deep-orange-text']) : '' }}--}}
	{{--</div>--}}

	<div class="form-group col-md-3 col-sm-12">
		{{ Form::label('no_orden', '* Orden') }}
		{!! Form::select('no_orden',isset($ordenes_compra)?$ordenes_compra:[],null,['id'=>'no_orden','class'=>'form-control select2','style'=>'width:100%']) !!}
		{{ $errors->has('no_orden') ? HTML::tag('span', $errors->first('no_orden'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
	<div class="form-group text-center col-md-3 col-sm-6">
		{{ Form::label('', 'Días/Fecha') }}
		<div class="input-group">
			{!! Form::text('tiempo_entrega', null,['id'=>'tiempo_entrega','class'=>'form-control','readonly','placeholder'=>'Días para la entrega']) !!}
			{!! Form::text('fecha_estimada_entrega', null,['id'=>'fecha_estimada_entrega','class'=>'form-control','readonly','placeholder'=>'Fecha estimada']) !!}
		</div>
	</div>
	{{--<div class="form-group col-md-3 col-sm-6">--}}
		{{--{{ Form::label('fk_id_sucursal', '* Sucursal de entrega') }}--}}
		{{--{!! Form::select('fk_id_sucursal',isset($sucursales)?$sucursales:[],null,['id'=>'fk_id_sucursal_','class'=>'form-control select2','style'=>'width:100%']) !!}--}}
		{{--{{ $errors->has('fk_id_sucursal') ? HTML::tag('span', $errors->first('fk_id_sucursal'), ['class'=>'help-block deep-orange-text']) : '' }}--}}
	{{--</div>--}}
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
	{{--<div class="col-sm-12">--}}
		{{--<h3>Detalle de la orden</h3>--}}
		{{--<div class="card">--}}
			{{--@if(!Route::currentRouteNamed(currentRouteName('show')))--}}
			{{--<div class="card-header">--}}
				{{--<fieldset name="detalle-form" id="detalle-form">--}}
					{{--<div class="row">--}}
						{{--<div class="form-group input-field col-md-3 col-sm-6">--}}
							{{--{{Form::label('fk_id_sku','* SKU')}}--}}
							{{--{!!Form::select('fk_id_sku',[],null,['id'=>'fk_id_sku','class'=>'form-control','style'=>'width:100%','data-url'=>companyAction('Inventarios\ProductosController@obtenerSkus')])!!}--}}
						{{--</div>--}}
						{{--<div class="form-group input-field col-md-3 col-sm-6">--}}
							{{--{{Form::label('fk_id_upc','UPC')}}--}}
							{{--<div class="input-group">--}}
								{{--<span class="input-group-addon">--}}
									{{--<input type="checkbox" id="activo_upc">--}}
								{{--</span>--}}
								{{--{!! Form::select('fk_id_upc',[],null,['id'=>'fk_id_upc','disabled',--}}
								{{--'data-url'=>companyAction('Inventarios\ProductosController@obtenerUpcs',['id'=>'?id']),--}}
								{{--'class'=>'form-control','style'=>'width:100%']) !!}--}}
							{{--</div>--}}
						{{--</div>--}}
						{{--<div class="form-group input-field col-md-3 col-sm-6">--}}
							{{--{{Form::label('fk_id_cliente','Cliente')}}--}}
							{{--{!!Form::select('fk_id_cliente',isset($clientes)?$clientes:[],null,['id'=>'fk_id_cliente','autocomplete'=>'off','class'=>'form-control','style'=>'width:100%'])!!}--}}
						{{--</div>--}}
						{{--<div class="form-group input-field col-md-3 col-sm-6">--}}
							{{--{{Form::label('fk_id_proyecto','Proyecto')}}--}}
							{{--{!!Form::select('fk_id_proyecto',isset($proyectos)?$proyectos:[],null,['id'=>'fk_id_proyecto','autocomplete'=>'off','class'=>'validate form-control','style'=>'width:100%',])!!}--}}
						{{--</div>--}}
						{{--<div class="form-group input-field col-md-2 col-sm-4">--}}
							{{--{{ Form::label('fecha_necesario', '* ¿Para cuándo se necesita?') }}--}}
							{{--{!! Form::text('fecha_necesario',null,['id'=>'fecha_necesario','class'=>'datepicker form-control','value'=>old('fecha_necesario'),'placeholder'=>'Selecciona una fecha']) !!}--}}
						{{--</div>--}}
						{{--<div class="form-group input-field col-md-2 col-sm-4">--}}
							{{--{{Form::label('cantidad','Cantidad')}}--}}
							{{--{!! Form::text('cantidad','1',['id'=>'cantidad','min'=>'1','class'=>'validate form-control cantidad','autocomplete'=>'off']) !!}--}}
						{{--</div>--}}
						{{--<div class="form-group input-field col-md-2 col-sm-6">--}}
							{{--{{Form::label('fk_id_impuesto','Tipo de impuesto')}}--}}
							{{--{!! Form::select('fk_id_impuesto',[]--}}
                                {{--,null,['id'=>'fk_id_impuesto',--}}
                                {{--'data-url'=>companyAction('Administracion\ImpuestosController@obtenerImpuestos'),--}}
                                {{--'class'=>'form-control','style'=>'width:100%']) !!}--}}
							{{--{{Form::hidden('impuesto',null,['id'=>'impuesto'])}}--}}
						{{--</div>--}}
						{{--<div class="form-group input-field col-md-2 col-sm-6">--}}
							{{--{{Form::label('precio_unitario','Precio unitario',['class'=>'validate'])}}--}}
							{{--{!! Form::text('precio_unitario',old('precio_unitario'),['id'=>'precio_unitario','placeholder'=>'0.00','class'=>'validate form-control precio_unitario','autocomplete'=>'off']) !!}--}}
						{{--</div>--}}
						{{--<div class="col-sm-12 text-center">--}}
							{{--<div class="sep">--}}
								{{--<div class="sepBtn">--}}
									{{--<button style="width: 4em; height:4em; border-radius:50%;" class="btn btn-primary btn-large tooltipped "--}}
											{{--data-position="bottom" data-delay="50" data-tooltip="Agregar" type="button" id="agregar"><i--}}
												{{--class="material-icons">add</i></button>--}}
								{{--</div>--}}
							{{--</div>--}}
						{{--</div>--}}
					{{--</div>--}}
				{{--</fieldset>--}}
			{{--</div>--}}
			{{--@endif--}}
			{{--<div class="card-body">--}}
				{{--<table id="productos" class="table-responsive highlight" data-url="{{companyAction('Compras\ordenesController@store')}}"--}}
					   {{--@if(isset($data->id_orden))--}}
					   {{--data-delete="{{companyAction('Compras\OrdenesController@destroyDetail')}}"--}}
					   {{--@endif--}}
					   {{--data-impuestos="{{companyAction('Administracion\ImpuestosController@obtenerImpuestos')}}"--}}
					   {{--data-porcentaje="{{companyAction('Administracion\ImpuestosController@obtenerPorcentaje',['id'=>'?id'])}}">--}}
					{{--<thead>--}}

					{{--</thead>--}}
					{{--<tbody>--}}
					{{--@if( isset( $data->detalleOrdenes ) )--}}
						{{--@foreach( $data->detalleOrdenes as $detalle)--}}
							{{--<tr>--}}
								{{--<td>--}}
									{{--{{isset($detalle->fk_id_solicitud)?$detalle->fk_id_solicitud:'N/A'}}--}}
								{{--</td>--}}
								{{--<td>--}}
									{{--{!! Form::hidden('detalles['.$detalle->id_orden_detalle.'][id_orden_detalle]',$detalle->id_orden_detalle) !!}--}}
									{{--{!! Form::hidden('detalles['.$detalle->id_orden_detalle.'][fk_id_sku]',$detalle->fk_id_sku) !!}--}}
									{{--{{$detalle->sku->sku}}--}}
								{{--</td>--}}
								{{--<td>--}}
									{{--{!! Form::hidden('detalles['.$detalle->id_orden_detalle.'][fk_id_upc]',$detalle->fk_id_upc) !!}--}}
									{{--{{isset($detalle->fk_id_upc)?$detalle->upc->upc:'UPC no seleccionado'}}--}}
								{{--</td>--}}
								{{--<td>--}}
									{{--{{$detalle->sku->nombre_comercial}}--}}
								{{--</td>--}}
								{{--<td>--}}
									{{--{{$detalle->sku->descripcion}}--}}
								{{--</td>--}}
								{{--<td>--}}
									{{--{!! Form::hidden('detalles['.$detalle->id_orden_detalle.'][fk_id_cliente]',$detalle->fk_id_cliente) !!}--}}
									{{--{{isset($detalle->cliente->nombre_corto)?$detalle->cliente->nombre_corto:'Sin cliente'}}--}}
								{{--</td>--}}
								{{--<td>--}}
									{{--{!! Form::hidden('detalles['.$detalle->id_orden_detalle.'][fk_id_proyecto]',$detalle->fk_id_proyecto) !!}--}}
									{{--{{isset($detalle->proyecto->proyecto)?$detalle->proyecto->proyecto:'Sin proyecto'}}--}}
								{{--</td>--}}
								{{--<td>--}}
									{{--{!! Form::hidden('detalles['.$detalle->id_orden_detalle.'][fecha_necesario]',$detalle->fecha_necesario) !!}--}}
									{{--{{$detalle->fecha_necesario}}--}}
								{{--</td>--}}
								{{--<td>--}}
									{{--{!! Form::hidden('detalles['.$detalle->id_orden_detalle.'][cantidad]',$detalle->cantidad) !!}--}}
									{{--{{$detalle->cantidad}}--}}
								{{--</td>--}}
								{{--<td>--}}
									{{--{!! Form::hidden('detalles['.$detalle->id_orden_detalle.'][fk_id_impuesto]',$detalle->fk_id_impuesto) !!}--}}
									{{--{{$detalle->impuesto->impuesto}}--}}
								{{--</td>--}}
								{{--<td>--}}
									{{--{!! Form::hidden('detalles['.$detalle->id_orden_detalle.'][precio_unitario]',$detalle->precio_unitario) !!}--}}
									{{--{{number_format($detalle->precio_unitario,2,'.','')}}--}}
								{{--</td>--}}
								{{--<td>--}}
									{{--<input type="text" class="form-control total" name="{{'detalles['.$detalle->id_orden_detalle.'][total]'}}" readonly value="{{number_format($detalle->total,2,'.','')}}">--}}
								{{--<td>--}}
									{{--Si se va a editar, agrega el botón para "eliminar" la fila--}}
									{{--@if(Route::currentRouteNamed(currentRouteName('edit')) && $data->fk_id_estatus_orden == 1)--}}
										{{--<button class="btn is-icon text-primary bg-white "--}}
										   {{--type="button" data-item-id="{{$detalle->id_orden_detalle}}"--}}
										   {{--id="{{$detalle->id_orden_detalle}}" data-delay="50"--}}
										   {{--onclick="borrarFila_edit(this)" data-delete-type="single">--}}
											{{--<i class="material-icons">delete</i></button>--}}
									{{--@endif--}}
								{{--</td>--}}
							{{--</tr>--}}
						{{--@endforeach--}}
					{{--@endif--}}
					{{--</tbody>--}}
				{{--</table>--}}
			{{--</div>--}}
		{{--</div>--}}
	{{--</div>--}}
{{--</div>--}}

@endsection

{{-- DONT DELETE --}}
@if (Route::currentRouteNamed(currentRouteName('index')))
	@section('smart-js')
		<script type="text/javascript">
            if ( sessionStorage.reloadAfterPageLoad ) {
                sessionStorage.clear();
                $.toaster({
                    priority: 'success', title: 'Exito', message: 'Orden cancelada',
                    settings:{'timeout': 5000, 'toaster':{'css':{'top':'5em'}}}
                });
            }
		</script>
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
			rivets.binders['hide-update'] = {
				bind: function (el) {
					if(el.dataset.fk_id_estatus_orden != 1)
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
	@include('layouts.smart.index')
@endif

@if (Route::currentRouteNamed(currentRouteName('create')))
	@include('layouts.smart.create')
@endif

@if (Route::currentRouteNamed(currentRouteName('edit')))
	@include('layouts.smart.edit')
@endif

@if (Route::currentRouteNamed(currentRouteName('show')))
	@section('extraButtons')
		@parent
		{!!isset($data->id_orden) ? HTML::decode(link_to(companyAction('impress',['id'=>$data->id_orden]), '<i class="material-icons">print</i> Imprimir', ['class'=>'btn btn-default imprimir'])) : ''!!}
	@endsection
	@include('layouts.smart.show')
@endif

{{--@if (currentRouteName('createSolicitudOrden'))--}}
	{{--@include('layouts.smart.create')--}}
{{--@endif--}}