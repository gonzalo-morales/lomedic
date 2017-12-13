
@section('content-width', 's12')

@section('form-content')
{{ Form::setModel($data) }}
<div id="app" data-api-endpoint="{{ companyRoute('api.index', ['entity' => '#ENTITY#'], false) }}">
	<div class="row">
		<div class="col-sm-4">
			<div class="form-group" v-cloak>
				{{ currentRouteName('show') }}
				{{ Form::cSelectWithDisabled('Tipo de salida', 'fk_tipo_salida', $tipos_salida ?? [], [
					!isCurrentRouteName('show') ? 'v-select2' : 'dummy' => 'fk_tipo_salida',
					'v-model.number' => 'fk_tipo_salida',
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
					!isCurrentRouteName('show') ? 'v-select2' : 'dummy' => 'fk_id_socio_negocio',
					'v-model.number' => 'fk_id_socio_negocio',
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
					!isCurrentRouteName('show') ? 'v-select2' : 'dummy' => 'fk_id_proyecto',
					'v-model.number' => 'fk_id_proyecto',
					'v-on:change' => 'onChangeUPC',
					'v-validate' => '"required|not_in:0"',
					'v-bind:class' => '{"is-invalid": errors.has("main.fk_id_proyecto")}',
					'data-vv-as' => 'UPC',
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
							!isCurrentRouteName('show') ? 'v-select2' : 'dummy' => 'entrega_por',
							'v-model.number' => 'entrega_por',
							'v-validate' => '"required|not_in:0"',
							'v-bind:class' => '{"is-invalid": errors.has("main.entrega_por")}',
							'data-vv-as' => 'Tipo de salida',
							'data-vv-name' => 'entrega_por',
							'data-vv-scope' => 'main',
						]) }}
						<span v-show="errors.has('main.entrega_por')" class="help-block help-block-error small">@{{ errors.first('main.entrega_por') }}</span>
					</div>
				</div>
				<transition name="vue-slide-fade" mode="out-in">
				<div class="col-sm-6 col-lg-8 vue-slide-fade-item" v-if="entrega_por == 2" key="x1">
					<div class="form-group" v-cloak>
						{{ Form::cText('Paqueteria', 'paqueteria', [
							'v-model' => 'paqueteria',
							'v-validate' => '{required:entrega_por == 2}',
							'v-bind:class' => '{"is-invalid": errors.has("main.paqueteria")}',
							'data-vv-as' => 'Paqueteria',
							'data-vv-name' => 'paqueteria',
							'data-vv-scope' => 'main',
						]) }}
						<span v-show="errors.has('main.paqueteria')" class="help-block help-block-error small">@{{ errors.first('main.paqueteria') }}</span>
					</div>
				</div>
				<div class="col-sm-6 col-lg-8 vue-slide-fade-item" v-if="entrega_por == 1" key="x2">
					<div class="row">
						<div class="col-sm-6 col-lg-6">
							<div class="form-group" v-cloak>
								{{ Form::cText('Nombre de conductor', 'nombre_conductor', [
									'v-model' => 'nombre_conductor',
									'v-validate' => '{required:entrega_por == 1}',
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
									'v-model' => 'placas_vehiculo',
									'v-validate' => '{required:entrega_por == 1}',
									'v-bind:class' => '{"is-invalid": errors.has("main.placas_vehiculo")}',
									'data-vv-as' => 'Nombre de conductor',
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
					!isCurrentRouteName('show') ? 'v-select2' : 'dummy' => 'fk_tipo_entrega',
					'v-model.number' => 'fk_tipo_entrega',
					'v-validate' => '"required|not_in:0"',
					'v-bind:class' => '{"is-invalid": errors.has("main.fk_tipo_entrega")}',
					'data-vv-as' => 'Tipo de salida',
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
					!isCurrentRouteName('show') ? 'v-select2' : 'dummy' => 'fk_id_direccion_entrega',
					'v-model.number' => 'fk_id_direccion_entrega',
					'v-on:change' => 'onChangeUPC',
					'v-validate' => '"required|not_in:0"',
					'v-bind:class' => '{"is-invalid": errors.has("main.fk_id_direccion_entrega")}',
					'data-vv-as' => 'UPC',
					'data-vv-name' => 'fk_id_direccion_entrega',
					'data-vv-scope' => 'main',
				]) }}
				<span v-show="errors.has('main.fk_id_direccion_entrega')" class="help-block help-block-error small">@{{ errors.first('main.fk_id_direccion_entrega') }}</span>
			</div>
		</div>
		<div class="col-sm-6 col-lg-4">
			<div class="form-group" v-cloak>
				{{ Form::cText('Fecha de entrega', 'fecha_entrega', [
					'v-model' => 'fecha_entrega',
					'v-pickadate' => 'fecha_entrega',
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
									'v-select2' => 'nuffer.id_sku',
									'v-model.number' => 'nuffer.id_sku',
									'v-on:change' => 'onChangeSKU',
									'v-validate' => '"required|not_in:0"',
									'v-bind:class' => '{"is-invalid": errors.has("header.id_sku")}',
									'data-vv-as' => 'SKU',
									'data-vv-name' => 'id_sku',
									'data-vv-scope' => 'header',
								]) }}
								<span v-show="errors.has('header.id_sku')" class="help-block help-block-error small">@{{ errors.first('header.id_sku') }}</span>
							</div>
						</div>
						<div class="col-sm-6 col-lg-3">
							<div class="form-group" v-cloak>
								{{ Form::cSelectWithDisabled('UPC', '', [], [
									'ref' => 'id_upc',
									'v-select2' => 'nuffer.id_upc',
									'v-model.number' => 'nuffer.id_upc',
									'v-on:change' => 'onChangeUPC',
									'v-validate' => '"required|not_in:0"',
									'v-bind:class' => '{"is-invalid": errors.has("header.id_upc")}',
									'data-vv-as' => 'UPC',
									'data-vv-name' => 'id_upc',
									'data-vv-scope' => 'header',
								]) }}
								<span v-show="errors.has('header.id_upc')" class="help-block help-block-error small">@{{ errors.first('header.id_upc') }}</span>
							</div>
						</div>
						<div class="col-sm-6 col-lg-3">
							<div class="form-group" v-cloak>
								{{ Form::cNumber('Cantidad', '', [
									'min' => 0,
									'v-model.number' => 'nuffer.cantidad',
									'v-bind:class' => '{"is-invalid": errors.has("header.cantidad")}',
									'v-validate' => '"required|min_value:1"',
									'data-vv-as' => 'Cantidad',
									'data-vv-name' => 'cantidad',
									'data-vv-scope' => 'header',
								]) }}
								<span v-show="errors.has('header.cantidad')" class="help-block help-block-error small">@{{ errors.first('header.cantidad') }}</span>
							</div>
						</div>
						<div class="col-sm-6 col-lg-3">
							<div class="form-group" v-cloak>
								{{ Form::cSelectWithDisabled('Almacén de salida', '', $almacenes ?? [], [
									'v-select2' => 'nuffer.id_almacen',
									'v-model.number' => 'nuffer.id_almacen',
									'v-on:change' => 'onChangeAlmacen',
									'v-validate' => '"required|not_in:0"',
									'v-bind:class' => '{"is-invalid": errors.has("header.id_almacen")}',
									'data-vv-as' => 'Almacén de salida',
									'data-vv-name' => 'id_almacen',
									'data-vv-scope' => 'header',
								]) }}
								<span v-show="errors.has('header.id_almacen')" class="help-block help-block-error small">@{{ errors.first('header.id_almacen') }}</span>
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
								<th>Producto</th>
								<th>Cantidad</th>
								<th>Almacén de salida</th>
								<th></th>
							</tr>
						</thead>
						<tbody v-cloak name="vue-list" is="transition-group" v-on:after-enter="afterAppend">
							<template v-for="upc,index in upcs_computed" v-bind:class="{'vue-list-item':true,'table-dark':upc.eliminar}">
								<tr v-bind:data-index.prop.camel="index" v-bind:key="upc.idx">
									<td rowspan="3">
										 <span v-text="index + 1"></span>
									</td>
									<td rowspan="2">
										<strong>SKU: </strong>
										<span v-text="upc.sku"></span>
									</td>
									<td rowspan="2">
										<strong>UPC: </strong>
										<span v-text="upc.upc"></span>
									</td>
									<td rowspan="2">
										<strong>Marca: </strong>
										<span v-text="upc.marca"></span>
									</td>
									<td>Solicitado: <span v-text="upc.cantidad"></span></td>
									<td><strong>A surtir:</strong> <span v-text="upc.surtir"></span></td>
									<td>Almacén: <span v-text="upc.almacen"></span></td>
									<td rowspan="3">
										@if (!isCurrentRouteName('show'))
										<a href="#" v-on:click.prevent="removeOrUndo(index)">
											<i class="material-icons" v-text="upc.eliminar ? 'undo' : 'delete'"></i>
											<span v-text="upc.eliminar ? 'Deshacer' : 'Eliminar'"></span>
										</a>
										@endif
									</td>
								</tr>
								<tr v-bind:data-index.prop.camel="index" v-bind:key="upc.idx">
									<td>Stock: <span v-text="upc.stock"></span></td>
									<td><strong>Pendiente:</strong> <span v-text="upc.pendiente"></span></td>
									<td>Sucursal: <span v-text="upc.sucursal"></span></td>
								</tr>
								<tr v-bind:data-index.prop.camel="index" v-bind:key="upc.idx">
									<td colspan="6">
										 <span v-text="upc.descripcion"></span>
									</td>
								</tr>

							</template>
							<tr class="text-center vue-list-item" v-if="upcs.length == 0"  v-bind:key="'empty'">
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

$(function(){
if ($('#app').length) {

	var ID = function () {
		return '_' + Math.random().toString(36).substr(2, 9);
	};

	function updateSelect2 (el, binding) {
		if (!$(el).data('select2')) {
			$(el).select2().on('select2:select', function(e) {
				el.dispatchEvent(new Event('change', { target: e.target }));
			});
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
			// Private
			buffer: {
				id_detalle: null,
				id_sku: 0,
				sku: '',
				id_upc: 0,
				upc: '',
				cantidad: 0,
				id_almacen: 0,
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
							options.push('<option value="' + response[i].id_direccion + '">' + response[i].direccion_concat + '</option>');
						}
					} else {
						options.push('<option value="0" selected disabled>Sin resultados ...</option>');
					}
					$(this).append(options.join());
				})
			},
			onChangeSKU: function(e) {
				var vm = this;
				// Sucursales de entrega
				this.selectCascade(this.$refs.id_upc, 'inventarios.productos', {
					param_js: '{{$api_sku ?? ''}}', $id_sku: e.target.value
				}, function(response) {
					var options = [], i;
					$(this).empty();
					/* Si hay resultados */
					if (response.length > 0) {
						vm.nuffer.sku = response[0].sku
						if (response[0].upcs.length > 0) {
							options.push('<option value="0" selected disabled>...</option>');
							for (i in response[0].upcs) {
								options.push('<option data-upc="'+response[0].upcs[i].upc+'" data-marca="'+response[0].upcs[i].marca+'" data-descripcion="'+response[0].upcs[i].descripcion+'" value="' + response[0].upcs[i].id_upc + '">' + response[0].upcs[i].upc + ' - ' + response[0].upcs[i].descripcion + '</option>');
							}
						} else {
							options.push('<option value="0" selected disabled>Sin resultados ...</option>');
						}
					} else {
						vm.nuffer.sku = '';
					}
					$(this).append(options.join(''));
				})
			},
			onChangeUPC: function(e) {
				var data = e.target.options[e.target.selectedIndex].dataset;
				this.nuffer.marca = data.marca;
				this.nuffer.descripcion = data.descripcion;
				this.nuffer.upc = data.upc;
			},
			onChangeAlmacen: function(e) {
				var data = e.target.options[e.target.selectedIndex].innerHTML.split(' - ');
				this.nuffer.almacen = data[0];
				this.nuffer.sucursal = data[1];
			},
			verifyStock: function(upc) {
				console.log('checking stock', upc)
				var endpoint = this.$root.$el.dataset.apiEndpoint.replace('#ENTITY#', 'inventarios.inventarios_detalle');
				return this.enQueue(function() {
					return $.get(endpoint, { param_js: '{{$api_verify_stock ?? ''}}', $fk_id_almacen: 48, $codigo_barras: '000000'}, function(data) {
						console.log('queue', data[0])
						Vue.set(this, 'stock', data[0].cantidad_toma)
						Vue.set(this, 'surtir', Math.min(data[0].cantidad_toma, upc.cantidad))
						Vue.set(this, 'pendiente', Math.max(upc.cantidad - data[0].cantidad_toma, 0))
					}.bind(this))
				}.bind(upc))
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
				var upc = this.upcs[el.dataIndex];
					upc.props.queue = this.verifyStock(upc);
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
					if (!item.idx) {
						item.idx = ID();
					}
					if (!item.props) {
						Vue.set(item, 'props', {
							queue: null,
						})
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
}
})
</script>
@endsection

{{-- DONT DELETE --}}
@if (Route::currentRouteNamed(currentRouteName('index')))
@include('layouts.smart.index')
@endif

@if (Route::currentRouteNamed(currentRouteName('create')))
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