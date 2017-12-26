
@section('content-width', 's12')

@section('form-content')
{{ Form::setModel($data) }}
<div id="app" data-api-endpoint="{{ companyRoute('api.index', ['entity' => '#ENTITY#'], false) }}">
	@if (isCurrentRouteName(currentRouteName('pendings')))
	<input type="hidden" name="id_salida_pendiente" value="{{ request()->salida }}">
	@endif
	<div class="row">
		<div class="col-sm-4">
			<div class="form-group" v-cloak>
				{{ Form::cSelectWithDisabled('Tipo de salida', 'fk_tipo_salida', $tipos_salida ?? [], [
					!isCurrentRouteName('show') ? 'v-select2' : 'dummy' => 'main.fk_tipo_salida',
					'v-model.number' => 'main.fk_tipo_salida',
					'v-validate' => '"required|not_in:0"',
					'v-bind:class' => '{"is-invalid": errors.has("main.fk_tipo_salida")}',
					'data-vv-as' => 'Tipo de salida',
					'data-vv-name' => 'fk_tipo_salida',
					'data-vv-scope' => 'main',
				]) }}
				<span v-show="errors.has('main.fk_tipo_salida')" class="help-block help-block-error small">@{{ errors.first('main.fk_tipo_salida') }}</span>
			</div>
		</div>
		<div class="col-sm-4">
			<div class="form-group" v-cloak>
				{{ Form::cSelectWithDisabled('Cliente', 'fk_id_socio_negocio', $clientes ?? [], [
					!isCurrentRouteName('show') ? 'v-select2' : 'dummy' => 'main.fk_id_socio_negocio',
					'v-model.number' => 'main.fk_id_socio_negocio',
					'v-on:change' => 'onChangeCliente',
					'v-validate' => '"required|not_in:0"',
					'v-bind:class' => '{"is-invalid": errors.has("main.fk_id_socio_negocio")}',
					'data-vv-as' => 'Cliente',
					'data-vv-name' => 'fk_id_socio_negocio',
					'data-vv-scope' => 'main',
				]) }}
				<span v-show="errors.has('main.fk_id_socio_negocio')" class="help-block help-block-error small">@{{ errors.first('main.fk_id_socio_negocio') }}</span>
			</div>
		</div>
		<div class="col-sm-4">
			<div class="form-group" v-cloak>
				{{ Form::cSelectWithDisabled('Proyecto', 'fk_id_proyecto', $proyectos ?? [], [
					'ref' => 'fk_id_proyecto',
					!isCurrentRouteName('show') ? 'v-select2' : 'dummy' => 'main.fk_id_proyecto',
					'v-model.number' => 'main.fk_id_proyecto',
					'v-validate' => '"required|not_in:0"',
					'v-bind:class' => '{"is-invalid": errors.has("main.fk_id_proyecto")}',
					'data-vv-as' => 'Proyecto',
					'data-vv-name' => 'fk_id_proyecto',
					'data-vv-scope' => 'main',
				]) }}
				<span v-show="errors.has('main.fk_id_proyecto')" class="help-block help-block-error small">@{{ errors.first('main.fk_id_proyecto') }}</span>
			</div>
		</div>
		<div class="col-sm-12">
			<p class="font-weight-light">Información de entrega</p>
			<hr>
			<div class="row">
				<div class="col-sm-6 col-lg-4">
					<div class="form-group" v-cloak>
						{{ Form::cSelectWithDisabled('Entrega por', 'entrega_por', [
							'1' => 'Empresa',
							'2' => 'Paquetería'
						], [
							!isCurrentRouteName('show') ? 'v-select2' : 'dummy' => 'main.entrega_por',
							'v-model.number' => 'main.entrega_por',
							'v-on:change' => 'onChangeEntregaPor',
							'v-validate' => '"required|not_in:0"',
							'v-bind:class' => '{"is-invalid": errors.has("main.entrega_por")}',
							'data-vv-as' => 'Entrega por',
							'data-vv-name' => 'entrega_por',
							'data-vv-scope' => 'main',
						]) }}
						<span v-show="errors.has('main.entrega_por')" class="help-block help-block-error small">@{{ errors.first('main.entrega_por') }}</span>
					</div>
				</div>
				<transition name="vue-slide-fade" mode="out-in">
				<div class="col-sm-6 col-lg-8 vue-slide-fade-item" v-if="main.entrega_por == 2" key="x1">
					<div class="form-group" v-cloak>
						{{ Form::cText('Paqueteria', 'paqueteria', [
							'v-model' => 'main.paqueteria',
							'v-validate' => '{required:main.entrega_por == 2}',
							'v-bind:class' => '{"is-invalid": errors.has("main.paqueteria")}',
							'data-vv-as' => 'Paqueteria',
							'data-vv-name' => 'paqueteria',
							'data-vv-scope' => 'main',
						]) }}
						<span v-show="errors.has('main.paqueteria')" class="help-block help-block-error small">@{{ errors.first('main.paqueteria') }}</span>
					</div>
				</div>
				<div class="col-sm-6 col-lg-8 vue-slide-fade-item" v-if="main.entrega_por == 1" key="x2">
					<div class="row">
						<div class="col-sm-6 col-lg-6">
							<div class="form-group" v-cloak>
								{{ Form::cText('Nombre de conductor', 'nombre_conductor', [
									'v-model' => 'main.nombre_conductor',
									'v-validate' => '{required:main.entrega_por == 1}',
									'v-bind:class' => '{"is-invalid": errors.has("main.nombre_conductor")}',
									'data-vv-as' => 'Nombre de conductor',
									'data-vv-name' => 'nombre_conductor',
									'data-vv-scope' => 'main',
								]) }}
								<span v-show="errors.has('main.nombre_conductor')" class="help-block help-block-error small">@{{ errors.first('main.nombre_conductor') }}</span>
							</div>
						</div>
						<div class="col-sm-6 col-lg-6">
							<div class="form-group" v-cloak>
								{{ Form::cText('Placas del vehiculo', 'placas_vehiculo', [
									'v-model' => 'main.placas_vehiculo',
									'v-validate' => '{required:main.entrega_por == 1}',
									'v-bind:class' => '{"is-invalid": errors.has("main.placas_vehiculo")}',
									'data-vv-as' => 'Placas del vehiculo',
									'data-vv-name' => 'placas_vehiculo',
									'data-vv-scope' => 'main',
								]) }}
								<span v-show="errors.has('main.placas_vehiculo')" class="help-block help-block-error small">@{{ errors.first('main.placas_vehiculo') }}</span>
							</div>
						</div>
					</div>
				</div>
				</transition>
			</div>
		</div>
		<div class="col-sm-6 col-lg-4">
			<div class="form-group" v-cloak>
				{{ Form::cSelectWithDisabled('Tipo de entrega', 'fk_tipo_entrega', $tipos_entrega ?? [], [
					!isCurrentRouteName('show') ? 'v-select2' : 'dummy' => 'main.fk_tipo_entrega',
					'v-model.number' => 'main.fk_tipo_entrega',
					'v-validate' => '"required|not_in:0"',
					'v-bind:class' => '{"is-invalid": errors.has("main.fk_tipo_entrega")}',
					'data-vv-as' => 'Tipo de entrega',
					'data-vv-name' => 'fk_tipo_entrega',
					'data-vv-scope' => 'main',
				]) }}
				<span v-show="errors.has('main.fk_tipo_entrega')" class="help-block help-block-error small">@{{ errors.first('main.fk_tipo_entrega') }}</span>
			</div>
		</div>
		<div class="col-sm-6 col-lg-4">
			<div class="form-group" v-cloak>
				{{ Form::cSelectWithDisabled('Sucursal de entrega', 'fk_id_direccion_entrega', $sucursales_entrega ?? [], [
					'ref' => 'fk_id_direccion_entrega',
					!isCurrentRouteName('show') ? 'v-select2.theme' : 'dummy' => 'main.fk_id_direccion_entrega',
					'data-s2-theme' => 's2ThemeSucursal',
					'v-model.number' => 'main.fk_id_direccion_entrega',
					'v-validate' => '"required|not_in:0"',
					'v-bind:class' => '{"is-invalid": errors.has("main.fk_id_direccion_entrega")}',
					'data-vv-as' => 'Sucursal de entrega',
					'data-vv-name' => 'fk_id_direccion_entrega',
					'data-vv-scope' => 'main',
				]) }}
				<span v-show="errors.has('main.fk_id_direccion_entrega')" class="help-block help-block-error small">@{{ errors.first('main.fk_id_direccion_entrega') }}</span>
			</div>
		</div>
		<div class="col-sm-6 col-lg-4">
			<div class="form-group" v-cloak>
				{{ Form::cText('Fecha de entrega', 'fecha_entrega', [
					'v-model' => 'main.fecha_entrega',
					'v-pickadate' => 'main.fecha_entrega',
					'v-validate' => '"required"',
					'v-bind:class' => '{"is-invalid": errors.has("main.fecha_entrega")}',
					'data-vv-as' => 'Fecha de entrega',
					'data-vv-name' => 'fecha_entrega',
					'data-vv-scope' => 'main',
					'v-on:keydown.enter.prevent'
				]) }}
				<span v-show="errors.has('main.fecha_entrega')" class="help-block help-block-error small">@{{ errors.first('main.fecha_entrega') }}</span>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 col-sm-12 mb-3">
			<div id="app" class="card z-depth-1-half">
				@if (!isCurrentRouteName('show'))
				<div class="card-header">
					<h4>Productos</h4>
					<p>Agrega los productos que requieras.</p>
					<div class="row">
						<div class="col-sm-6 col-lg-3">
							<div class="form-group" v-cloak>
								{{ Form::cSelectWithDisabled('SKU', '', $skus ?? [], [
									'v-select2.theme' => 'nuffer.fk_id_sku',
									'data-s2-theme' => 's2ThemeSKU',
									'v-model.number' => 'nuffer.fk_id_sku',
									'v-on:change' => 'onChangeSKU',
									'v-validate' => '"required|not_in:0"',
									'v-bind:class' => '{"is-invalid": errors.has("header.fk_id_sku")}',
									'data-vv-as' => 'SKU',
									'data-vv-name' => 'fk_id_sku',
									'data-vv-scope' => 'header',
								], $skus_data ?? []) }}
								<span v-show="errors.has('header.fk_id_sku')" class="help-block help-block-error small">@{{ errors.first('header.fk_id_sku') }}</span>
							</div>
						</div>
						<div class="col-sm-6 col-lg-3">
							<div class="form-group" v-cloak>
								{{ Form::cSelectWithDisabled('UPC', '', [], [
									'ref' => 'fk_id_upc',
									'v-select2.theme' => 'nuffer.fk_id_upc',
									'data-s2-theme' => 's2ThemeUPC',
									'v-model.number' => 'nuffer.fk_id_upc',
									'v-on:change' => 'onChangeUPC',
									'v-validate' => '"required|not_in:0"',
									'v-bind:class' => '{"is-invalid": errors.has("header.fk_id_upc")}',
									'data-vv-as' => 'UPC',
									'data-vv-name' => 'fk_id_upc',
									'data-vv-scope' => 'header',
								]) }}
								<span v-show="errors.has('header.fk_id_upc')" class="help-block help-block-error small">@{{ errors.first('header.fk_id_upc') }}</span>
							</div>
						</div>
						<div class="col-sm-6 col-lg-3">
							<div class="form-group" v-cloak>
								{{ Form::cNumber('Cant. Solicitada', '', [
									'min' => 0,
									'v-model.number' => 'nuffer.cantidad_solicitada',
									'v-bind:class' => '{"is-invalid": errors.has("header.cantidad_solicitada")}',
									'v-validate' => '"required|min_value:1"',
									'data-vv-as' => 'Cantidad',
									'data-vv-name' => 'cantidad_solicitada',
									'data-vv-scope' => 'header',
								]) }}
								<span v-show="errors.has('header.cantidad_solicitada')" class="help-block help-block-error small">@{{ errors.first('header.cantidad_solicitada') }}</span>
							</div>
						</div>
						<div class="col-sm-6 col-lg-3">
							<div class="form-group" v-cloak>
								{{ Form::cSelectWithDisabled('Ubicación de salida', '', [], [
									'ref' => 'fk_id_ubicacion',
									'v-select2.theme' => 'nuffer.fk_id_ubicacion',
									'data-s2-theme' => 's2ThemeUbicacion',
									'v-model.number' => 'nuffer.fk_id_ubicacion',
									'v-on:change' => 'onChangeUbicacion',
									'v-validate' => '"required|not_in:0"',
									'v-bind:class' => '{"is-invalid": errors.has("header.fk_id_ubicacion")}',
									'data-vv-as' => 'Ubicación de salida',
									'data-vv-name' => 'fk_id_ubicacion',
									'data-vv-scope' => 'header',
								]) }}
								<span v-show="errors.has('header.fk_id_ubicacion')" class="help-block help-block-error small">@{{ errors.first('header.fk_id_ubicacion') }}</span>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-12 text-center my-3">
					<div class="sep">
						<div class="sepBtn">
							<button v-on:click="append" style="width: 4em; height:4em; border-radius:50%;" class="btn btn-primary btn-large" data-position="bottom" data-delay="50" data-toggle="Agregar" title="Agregar" type="button"><i class="material-icons">add</i></button>
						</div>
					</div>
				</div>
				@endif
				<div class="card-body">
					<table class="table table-hover table-responsive-sm table-striped" style="table-layout: fixed;position: relative;">
						<thead >
							<tr ref="trhead">
								<th>#</th>
								<th>SKU</th>
								<th>UPC</th>
								<th>Marca</th>
								<th>Descripción</th>
								<th>Cant. Solicitada</th>
								<th>Stock</th>
								<th>Cant. a Surtir</th>
								<th>Cant. Pendiente</th>
								<th>Ubicación de salida</th>
								<th></th>
							</tr>
						</thead>
						<tbody v-cloak name="vue-list" is="transition-group" v-on:after-enter="afterAppend" appear>
							<tr v-for="upc,index in upcs_computed" v-bind:class="{'vue-list-item':true,'table-dark':upc.eliminar}" v-bind:data-index.prop.camel="index" v-bind:key="upc._idx">
								<th scope="row">
									<span v-text="index + 1"></span>
									<input type="hidden" v-bind:name="'relations[has][detalle]['+index+'][fk_id_sku]'" v-bind:value="upc.fk_id_sku">
									<input type="hidden" v-bind:name="'relations[has][detalle]['+index+'][fk_id_upc]'" v-bind:value="upc.fk_id_upc">
									<input type="hidden" v-bind:name="'relations[has][detalle]['+index+'][cantidad_solicitada]'" v-bind:value="upc.cantidad_solicitada">
									<input type="hidden" v-bind:name="'relations[has][detalle]['+index+'][cantidad_surtida]'" v-bind:value="upc.cantidad_surtida">
									<input type="hidden" v-bind:name="'relations[has][detalle]['+index+'][cantidad_pendiente]'" v-bind:value="upc.cantidad_pendiente">
									<input type="hidden" v-bind:name="'relations[has][detalle]['+index+'][fk_id_ubicacion]'" v-bind:value="upc.fk_id_ubicacion">
									<input type="hidden" v-bind:name="'relations[has][detalle]['+index+'][fk_id_almacen]'" v-bind:value="upc.fk_id_almacen">
									<input type="hidden" v-bind:name="'relations[has][detalle]['+index+'][lote]'" v-bind:value="upc.lote">
									<input type="hidden" v-bind:name="'relations[has][detalle]['+index+'][eliminar]'" v-bind:value="upc.eliminar">
								</th>
								<td>
									<span v-text="upc.sku"></span>
								</td>
								<td>
									<span v-text="upc.upc"></span>
								</td>
								<td>
									<span v-text="upc.marca"></span>
								</td>
								<td>
									<span v-text="upc.descripcion"></span>
								</td>
								<td>
									<span v-text="upc.cantidad_solicitada"></span>
								</td>
								<td>
									<span v-text="upc.stock"></span>
								</td>
								<td>
									<span v-text="upc.cantidad_surtida"></span>
								</td>
								<td>
									<span v-text="upc.cantidad_pendiente"></span>
								</td>
								<td>
									<span v-text="upc.ubicacion"></span> <br>
									<small><span v-text="upc.almacen"></span></small>
								</td>

								<td>
									@if (!isCurrentRouteName('show'))
									<a href="#" v-on:click.prevent="removeOrUndo(index)">
										<i class="material-icons" v-text="upc.eliminar ? 'undo' : 'delete'"></i>
										<span v-text="upc.eliminar ? 'Deshacer' : 'Eliminar'"></span>
									</a>
									@endif
								</td>
							</tr>
							<tr class="text-center vue-list-item" v-if="upcs.length == 0" v-bind:data-index.prop.camel="'empty'"  v-bind:key="'empty'">
								<td v-bind:colspan="this.$refs.trhead ? this.$refs.trhead.cells.length : 1">Agrega uno o más productos.</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('header-bottom')
@parent
<script src="{{ asset('vendor/vue/vue.js') }}"></script>
<script type="text/javascript">
if (window['smart-model']) {
	rivets.binders['get-pending-url'] = {
		bind: function (el) {
			if (el.dataset.estatus_text.toLowerCase() !== 'surtido parcial') {
				$(el).hide();
			} else {
				el.href = '{{ companyRoute('pendings', ['salida' => '#ID#' ]) }}'.replace('#ID#', el.dataset.itemId);
			}
		}
	};
	rivets.binders['hide-update-or-delete'] = {
		bind: function (el) {
			if (el.dataset.estatus_text.toLowerCase() !== 'abierto') {
				$(el).hide();
			}
		}
	};
	rivets.binders['hide-header-update-or-delete'] = {
		routine: function(el, value) {
			if (value.length > 0) {
				var showMe = rivets.formatters.values(window['smart-model'].collections.items).reduce(function(acc,item) {
					acc.push([].reduce.call(datatable.data[item].cells, function(acc, ceil, index) {
						if (datatable.activeHeadings[index].textContent !== '') {
							acc[datatable.activeHeadings[index].textContent.trim(' ').toLowerCase()] = ceil.textContent.trim(' ').toLowerCase();
						}
					    return acc;
					}, {}))
					return acc;
				}, []).every(function(a){
					return a.estatus === 'abierto';
				})
				if (showMe) {
					$(el).show();
				} else {
					$(el).hide();
				}
			}
		}
	};
	@can('delete', currentEntity())
	window['smart-model'].collections.headerOptionsOnChecks.splice(1, 1, {button: {
		'class': 'btn btn-danger',
		'rv-on-click': 'actions.showModalCancel',
		'data-delete-type': 'multiple',
		'data-delete-url': '{{companyRoute('destroyMultiple')}}',
		'html': '<i class="material-icons left align-middle">not_interested</i>Cancelar (<span rv-text="collections.items | length"></span>)',
		'rv-hide-header-update-or-delete':'collections.items',
	}});
	@endcan
	@can('update', currentEntity())
	window['smart-model'].collections.itemsOptions.edit = {a: {
		'html': '<i class="material-icons">mode_edit</i>',
		'class': 'btn is-icon',
		'rv-get-edit-url': '',
		'rv-hide-update-or-delete':'',
		'data-toggle':'tooltip',
		'title':'Editar'
	}};
	@endcan
	@can('delete', currentEntity())
	window['smart-model'].collections.itemsOptions.delete = {a: {
		'html': '<i class="material-icons">not_interested</i>',
		'href' : '#',
		'class': 'btn is-icon',
		'rv-on-click': 'actions.showModalCancel',
		'rv-get-delete-url': '',
		'data-delete-type': 'single',
		'rv-hide-update-or-delete':'',
		'data-toggle':'tooltip',
		'title':'Cancelar'
	}};
	window['smart-model'].collections.itemsOptions.pendings = {a: {
		'html': '<i class="material-icons">assignment_return</i>',
		'class': 'btn is-icon',
		'rv-get-pending-url': '',
		'data-toggle':'tooltip',
		'title':'Surtir Pendientes'
	}};
	@endcan
	window['smart-model'].actions.showModalCancel = function(e, rv) {
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
			action: function(e) {
				var formData = new FormData(document.querySelector('#cancel-form')), convertedJSON = {}, it = formData.entries(), n;
				while(n = it.next()) {
					if(!n || n.done) break;
					convertedJSON[n.value[0]] = n.value[1];
				}
				window['smart-model'].actions.itemsCancel.call(this, e, rv, convertedJSON);
			}.bind(this),
			// Opcionales
			onModalShow: function() {
				let btn = modal.querySelector('[rv-on-click="action"]');
				// Copiamos data a boton de modal
				for (var i in this.dataset) btn.dataset[i] = this.dataset[i];
			}.bind(this),
		});
		// Abrimos modal
		$(modal).modal('show');
	};
	window['smart-model'].actions.itemsCancel = function(e, rv, motivo){
		e.preventDefault();
		if(!motivo.motivo_cancelacion){
			$.toaster({
				priority : 'danger',
				title : '¡Error!',
				message : 'Por favor escribe un motivo por el que se está cancelando el documento.',
	            settings:{'timeout': 10000, 'toaster':{'css':{'top':'5em'}}}
			});
		}else{
			let data, tablerows;
			let token = document.querySelector("meta[name='csrf-token']").getAttribute("content");

			switch (this.dataset.deleteType) {
				case 'multiple':
					data =  {ids: rivets.formatters.keys(rv.collections.items),'_token':token, motivo_cancelacion: motivo.motivo_cancelacion};
					tablerows = rivets.formatters.values(rv.collections.items);
				break;
				case 'single':
					data =  {'_token':token, motivo_cancelacion: motivo.motivo_cancelacion};
					tablerows = [this.parentNode.parentNode.dataIndex];
				break;
			}

			$(window['smart-modal']).modal('hide');

			$.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
			$.delete(this.dataset.deleteUrl, data, function (response) {
				if(response.success){
					sessionStorage.cancelCallback = true;
					location.reload();
				}
			})
		}
	};
	if ( sessionStorage.cancelCallback ) {
		sessionStorage.clear();
		$.toaster({
			priority: 'success', title: 'Exito', message: 'Documento cancelado correctamente.',
			settings:{'timeout': 5000, 'toaster':{'css':{'top':'5em'}}}
		});
	}
}

$(function(){if ($('#app').length) {

	var ID = function () {
		return '_' + Math.random().toString(36).substr(2, 9);
	};

	String.prototype.supplant = function (o) {
		return this.replace(/{([^{}]*)}/g,
			function (a, b) {
				var r = o[b];
				return typeof r === 'string' || typeof r === 'number' ? r : a;
			}
		);
	};

	function updateSelect2 (el, binding, vnode) {
		if (!$(el).data('select2')) {
			$(el).select2(!binding.modifiers.theme ? {} : {escapeMarkup: function (markup) { return markup; }, templateResult: vnode.context[el.dataset.s2Theme]}).on('select2:select', function(e) {
				el.dispatchEvent(new Event('change', { target: e.target }));
			});
		} else {
			if (binding.oldValue !== binding.value && document.body === document.activeElement) {
				el.parentNode.querySelector('.select2-selection').focus()
			}
		}
		el.style.display = 'none';
		$(el).val(binding.value).trigger('change')
	}
	Vue.directive('select2', {inserted: updateSelect2, componentUpdated: updateSelect2});

	function updatePickadate (el, binding) {
		if ($(el).pickadate('picker')) {
			$(el).addClass('picker__input')
		}
		$(el).val(binding.value).pickadate({
			selectMonths: true, /* Creates a dropdown to control month */
			selectYears: 3, /* Creates a dropdown of 3 years to control year */
			min: true,
			format: 'yyyy-mm-dd',
			onSet: function(context) {
				el.dispatchEvent(new Event('input'));
			}
		})
	}
	Vue.directive('pickadate', {inserted: updatePickadate, componentUpdated: updatePickadate});

	Vue.use(VeeValidate, {
		locale: 'es',
	});

	window.vapp = new Vue({
		el: '#app',
		data: {
			// Public
			main: {
				fk_tipo_salida: {{ $data['fk_tipo_salida'] ?? 0 }},
				fk_id_socio_negocio: {{ $data['fk_id_socio_negocio'] ?? 0 }},
				fk_id_proyecto: {{ $data['fk_id_proyecto'] ?? 0 }},
				entrega_por: {{ $data['entrega_por'] ?? 0 }},
				paqueteria: '{{ $data['paqueteria'] ?? '' }}',
				nombre_conductor: '{{ $data['nombre_conductor'] ?? '' }}',
				placas_vehiculo: '{{ $data['placas_vehiculo'] ?? '' }}',
				fk_tipo_entrega: {{ $data['fk_tipo_entrega'] ?? 0 }},
				fk_id_direccion_entrega: {{ $data['fk_id_direccion_entrega'] ?? 0 }},
				fecha_entrega: '{{ $data['fecha_entrega'] ?? '' }}',
			},
			// Private
			buffer: {
				id_detalle: null,
				fk_id_sku: 0,
				sku: '',
				fk_id_upc: 0,
				upc: '',
				lote:'',
				stock: '---',
				cantidad_solicitada: 0,
				cantidad_surtida: 0,
				cantidad_pendiente: 0,
				fk_id_almacen: 0,
				almacen:'',
				fk_id_ubicacion: 0,
				ubicacion: '',
				almacen: '',
				sucursal: '',
				marca: '',
				descripcion: '',
				eliminar: false,
			},
			nuffer: {},
			upcs: @json($upcs ?? []),
			queue: [],
		},
		methods: {
			/* Limpiamos notificacion de errores */
			clearErrorScope: function(scope) {
				this.$nextTick(function() {
					this.errors.clear(scope)
				}.bind(this))
			},
			/* Cola de validaciones */
			enQueue: function(fn) {
				if (this.queue.length === 0) {
					this.queue.push(fn)
					this.nextQueue()
				} else {
					this.queue.push(fn)
				}
				return this.queue.slice(-1).shift();
			},
			nextQueue: function() {
				if (this.queue.length === 0) return;
				this.queue[0]().then(function() {
					this.queue.splice(0, 1)
					this.nextQueue()
				}.bind(this))
			},
			selectCascade: function(target, route, data, callback) {
				target.value = 0;
				target.dispatchEvent(new Event('change'));
				this.$nextTick(function() {
					this.errors.clear(target.dataset.vvScope)
				}.bind(this))
				$(target).html('<option value="0" selected disabled>Obteniendo ...</option>')
				$.get(this.$root.$el.dataset.apiEndpoint.replace('#ENTITY#', route), data, function(response){
					if (callback) callback.call(this, response)
				}.bind(target))
			},
			onChangeCliente: function(e) {
				// Proyectos
				this.selectCascade(this.$refs.fk_id_proyecto, 'proyectos.proyectos', {
					param_js: '{{$api_proyectos ?? ''}}', $fk_id_cliente: e.target.value
				}, function(response) {
					var options = [], i;
					$(this).empty();
					/* Si hay resultados */
					if (response.length > 0) {
						options.push('<option value="0" selected disabled>Selecciona ...</option>');
						for (i in response) {
							options.push('<option value="' + response[i].id_proyecto + '">' + response[i].proyecto + '</option>');
						}
					} else {
						options.push('<option value="0" selected disabled>Sin resultados ...</option>');
					}
					$(this).append(options.join());
				})
				// Sucursales de entrega
				this.selectCascade(this.$refs.fk_id_direccion_entrega, 'sociosnegocio.direccionessociosnegocio', {
					param_js: '{{$api_direcciones ?? ''}}', $fk_id_socio_negocio: e.target.value
				}, function(response) {
					var options = [], i;
					$(this).empty();
					/* Si hay resultados */
					if (response.length > 0) {
						options.push('<option value="0" selected disabled>Selecciona ...</option>');
						for (i in response) {
							options.push('<option has-data data-calle="{c} #{n}" data-ext="CP.{cp} {m}, {e}" value="{v}">{dc}</option>'.supplant({
								c: response[i].calle,
								n: response[i].num_exterior + (response[i].num_interior != '' ? '-' + response[i].num_interior : ''),
								cp: response[i].codigo_postal,
								m: response[i].municipio.municipio,
								e: response[i].estado.estado,
								dc: response[i].direccion_concat,
								v: response[i].id_direccion
							}))
						}
					} else {
						options.push('<option value="0" selected disabled>Sin resultados ...</option>');
					}
					$(this).append(options.join());
				})
			},
			onChangeEntregaPor: function(e) {
				this.main.nombre_conductor = '';
				this.main.paqueteria = '';
				this.main.placas_vehiculo = '';
			},
			s2ThemeSucursal: function(option) {
				if (option.element && option.element.hasAttribute('has-data')) {
					return '<div>' + option.element.dataset.calle + '<br /> <small>' + option.element.dataset.ext +'</small></div>';
				}
				return option.text;
			},
			s2ThemeSKU: function(option) {
				if (option.element && option.element.dataset.sku) {
					return '<div>' + option.element.dataset.descripcion_corta + '<br /> <small>' + option.element.dataset.sku +'</small></div>';
				}
				return option.text;
			},
			onChangeSKU: function(e) {
				var data = e.target.options[e.target.selectedIndex].dataset;
				this.nuffer.sku = data.sku;
				// Sucursales de entrega
				this.selectCascade(this.$refs.fk_id_upc, 'inventarios.productos', {
					param_js: '{{$api_sku ?? ''}}', $fk_id_sku: e.target.value
				}, function(response) {
					var options = [], i;
					$(this).empty();
					/* Si hay resultados */
					if (response.length > 0) {
						if (response[0].upcs.length > 0) {
							options.push('<option value="0" selected disabled>...</option>');
							for (i in response[0].upcs) {
								options.push('<option data-upc="'+response[0].upcs[i].upc+'" data-marca="'+response[0].upcs[i].marca+'" data-descripcion="'+response[0].upcs[i].descripcion+'" value="' + response[0].upcs[i].id_upc + '">' + response[0].upcs[i].upc + ' - ' + response[0].upcs[i].descripcion + '</option>');
							}
						} else {
							options.push('<option value="0" selected disabled>Sin resultados ...</option>');
						}
					}
					$(this).append(options.join(''));
				})
			},
			s2ThemeUPC: function(option) {
				if (option.element && option.element.dataset.upc) {
					return '<div>' + option.element.dataset.descripcion + '<br /> <small>' + option.element.dataset.upc +'</small></div>';
				}
				return option.text;
			},
			onChangeUPC: function(e) {
				if (e.target.value != 0) {
					var data = e.target.options[e.target.selectedIndex].dataset;
					this.nuffer.marca = data.marca;
					this.nuffer.descripcion = data.descripcion;
					this.nuffer.upc = data.upc;
					// Almacenes de salida
					this.selectCascade(this.$refs.fk_id_ubicacion, 'inventarios.stock', {
						param_js: '{{$api_ubicaciones ?? ''}}', $fk_id_sku: this.nuffer.fk_id_sku, $fk_id_upc: this.nuffer.fk_id_upc
					}, function(response) {
						var options = [], i;
						$(this).empty();
						/* Si hay resultados */
						if (response.length > 0) {
							options.push('<option value="0" selected disabled>Selecciona ...</option>');
							for (i in response) {
								options.push('<option data-lote="{l}" data-stock="{st}" data-nomenclatura="{n}" data-fk_id_almacen="{ia}" data-almacen="{a}" data-sucursal="{s}" value="{v}">{n} ({st}) - {a}</option>'.supplant({
									l: response[i].lote,
									st: response[i].stock,
									n: response[i].ubicacion.nomenclatura,
									ia: response[i].ubicacion.fk_id_almacen,
									a: response[i].ubicacion.almacen.almacen,
									s: response[i].ubicacion.almacen.sucursal.sucursal,
									v: response[i].fk_id_ubicacion
								}))
							}
						} else {
							options.push('<option value="0" selected disabled>Sin resultados ...</option>');
						}
						$(this).append(options.join());
					})
				}
			},
			s2ThemeUbicacion: function(option) {
				if (option.element && option.element.dataset.almacen) {
					return '<div>{n} ({st}) <br /> <small>{a} / {s}</small></div>'.supplant({
						n: option.element.dataset.nomenclatura,
						st: option.element.dataset.stock,
						a: option.element.dataset.almacen,
						s: option.element.dataset.sucursal
					});
				}
				return option.text;
			},
			onChangeUbicacion: function(e) {
				if (e.target.value != 0) {
					var data = e.target.options[e.target.selectedIndex].dataset;
					this.nuffer.almacen = data.almacen;
					this.nuffer.fk_id_almacen = data.fk_id_almacen;
					this.nuffer.lote = data.lote;
					this.nuffer.ubicacion = data.nomenclatura;
					this.nuffer.stock = data.stock;
					this.nuffer.sucursal = data.sucursal;
				}
			},
			verifyStock: function(upc) {
				if (upc.stock === '---') {
					var endpoint = this.$root.$el.dataset.apiEndpoint.replace('#ENTITY#', 'inventarios.stock');
					return this.enQueue(function() {
						return $.get(endpoint, { param_js: '{{$api_verify_stock ?? ''}}', $fk_id_almacen: upc.fk_id_almacen, $fk_id_ubicacion: upc.fk_id_ubicacion, $fk_id_sku: upc.fk_id_sku, $fk_id_upc: upc.fk_id_upc}, function(data) {
							this.stock = data.length > 0 ? data[0].stock: 0;
							this.cantidad_surtida = Math.min(this.stock, upc.cantidad_solicitada);
							this.cantidad_pendiente = Math.max(upc.cantidad_solicitada - this.stock, 0);
						}.bind(this))
					}.bind(upc))
				} else {
					upc.cantidad_surtida = Math.min(upc.stock, upc.cantidad_solicitada);
					upc.cantidad_pendiente = Math.max(upc.cantidad_solicitada - upc.stock, 0);
				}
			},
			append: function(e) {
				/* Validamos campos */
				this.$validator.validateAll('header').then(function(isValid){
					if (isValid) {
						/* upc --enviamos--> upcs */
						this.upcs.splice(0,0,JSON.parse(JSON.stringify($.extend(true, {}, this.nuffer))));
						/* Limpiamos campos */
						this.$nextTick(function() {
							this.nuffer = $.extend(true, {}, this.buffer);
							this.clearErrorScope('header')
						}.bind(this))
					}
				}.bind(this))
			},
			afterAppend: function (el) {
				if (el.dataIndex != 'empty') {
					var upc = this.upcs[el.dataIndex];
					if (upc.id_detalle === null) {
						upc._props.queue = this.verifyStock(upc);
					}
				}
			},
			removeOrUndo: function(index) {
				if (!this.upcs[index].id_detalle) {
					this.upcs.splice(index ,1); return;
				}
				this.upcs[index].eliminar = !this.upcs[index].eliminar;
			}
		},
		computed: {
			upcs_computed: function() {
				return this.upcs.reduce(function(acc, item){
					if (!item._idx) {
						item._idx = ID();
					}
					if (!item._props) {
						Vue.set(item, '_props', {
							queue: null,
						})
					}
					if (!item.stock) {
						Vue.set(item, 'stock', '---')
					}
					return acc.concat(item)
				}, []);
			}
		},
		beforeMount: function() {
			var vm = this;

			/* First */
			this.nuffer = $.extend(true, {}, this.buffer);

			$('#form-model').on('submit', function(e) {
				e.preventDefault();
				var form = this;
				/* Validamos campos */
				vm.$validator.validateAll('main').then(function(isValid){
					// Si no hay detalle
					if (!vm.upcs.length) {
						$.toaster({
							priority: 'danger', title: 'Error', message: 'Se requiere al menos un detalle.',
							settings:{'timeout': 5000, 'toaster':{'css':{'top':'5em'}}}
						});
					} else {
						if (isValid) {
							form.submit();
						}
					}
				})
			}).validate().destroy();
		},
	});
}})
</script>
@endsection

{{-- DONT DELETE --}}
@if (Route::currentRouteNamed(currentRouteName('index')))
@include('layouts.smart.index')
@endif

@if (Route::currentRouteNamed(currentRouteName('create')))
@include('layouts.smart.create')
@endif

@if (Route::currentRouteNamed(currentRouteName('pendings')))
@include('layouts.smart.create')
@endif

@if (Route::currentRouteNamed(currentRouteName('edit')))
@include('layouts.smart.edit')
@endif

@if (Route::currentRouteNamed(currentRouteName('show')))
@include('layouts.smart.show')
@endif

@if (Route::currentRouteNamed(currentRouteName('export')))
@include('layouts.smart.export')
@endif