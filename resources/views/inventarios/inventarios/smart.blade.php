@extends(smart())
@section('content-width', 's12')

@section('form-content')
	{{ Form::setModel($data) }}
    <div class="row">
    	<div class="col-md-3 col-sm-3">
    		<div class="form-group">
    			{{ Form::cSelectWithDisabled('Tipo de inventario', 'fk_tipo_inventario', $tipos ?? [], ['class' => !Route::currentRouteNamed(currentRouteName('show')) ? 'select2' : '']) }}
    		</div>
    	</div>
    	<div class="col-md-3 col-sm-3">
    		<div class="form-group">
    			{{ Form::cSelectWithDisabled('Sucursal', 'fk_id_sucursal', $sucursales ?? [], [
    				'class' => !Route::currentRouteNamed(currentRouteName('show')) ? 'select2 select-cascade' : '',
    				'data-target-url' => companyRoute('administracion.sucursales.show', ['id' => '#ID#']),
    				'data-target-el' => '[targeted="fk_id_almacen"]',
    				'data-target-with' => '["almacenes:id_almacen,fk_id_sucursal,almacen"]',
    				'data-target-value' => 'almacenes,id_almacen,almacen'
    			]) }}
    		</div>
    	</div>
    	<div class="col-md-3 col-sm-3">
    		<div class="form-group">
    			{{ Form::cSelect('Almacén', 'fk_id_almacen', $almacenes ?? [], ['id' => 'some','class' => !Route::currentRouteNamed(currentRouteName('show')) ? 'select2' : '', 'targeted' => 'fk_id_almacen']) }}
    		</div>
    	</div>
    	<div class="col-md-3 col-sm-3">
    		<div class="form-group">
    			{{ Form::cSelect('Tipo de captura', 'tipo_captura', [1 => 'Manual', 2 => 'HandHeld'], ['class' => 'custom-select']) }}
    		</div>
    	</div>
    </div>
    <div class="row">
    	<div class="col-md-12 col-sm-12 mb-3">
    		<div id="app" class="card z-depth-1-half" data-api-endpoint="{{ companyRoute('api.index', ['entity' => '#ENTITY#'], false) }}">
    			@if (!Route::currentRouteNamed(currentRouteName('show')))
    			<div v-if="tipo_captura == 1" class="card-header text-center">
    				<div class="row">
    					<div class="col-sm-12">
    						<p>Tipo de <b>captura</b> para el producto</p>
    						<ul class="nav nav-tabs btn-group justify-content-center border-0 mb-3" role="tablist">
    							<li>
    								<a class="btn m-0 btn-info active" data-toggle="tab" href="#scanner" role="tab" aria-controls="scanner" aria-selected="true"><i class="material-icons align-middle">settings_remote</i> Captura con scanner</a>
    							</li>
    							<li>
    								<a class="btn m-0 btn-info" data-toggle="tab" href="#manual" role="tab" aria-controls="manual" aria-selected="false"><i class="material-icons align-middle">keyboard</i> Captura manual</a>
    							</li>
    							<li>
    								<a class="btn m-0 btn-info" data-toggle="tab" href="#importar" role="tab" aria-controls="importar" aria-selected="false"><i class="material-icons align-middle">get_app</i> Importar Excel</a>
    							</li>
    						</ul>
    					</div>
    					<div class="col-sm-12">
    						<div class="tab-content">
    							<div class="tab-pane active" id="scanner" role="tabpanel">
    								<div class="row justify-content-center">
    									<div class="col-12 col-md-6 col-lg-4">
    										<div class="form-group" v-cloak>
    											{{ Form::cText('Código de barras', '', [
    												'v-on:keydown.enter.prevent' => '',
    												'v-validate' => '"required|alpha_num"',
    												'v-bind:class' => '{"is-invalid": errors.has("scanner.codigo_barras")}',
    												'data-vv-as' => 'Código de barras',
    												'data-vv-name' => 'codigo_barras',
    												'data-vv-scope' => 'scanner',
    												'v-on:blur' => 'clearErrorScope("scanner")',
    												'v-on:keyup.enter.prevent' => 'onEnterCodebar',
    											]) }}
    											<span v-show="errors.has('scanner.codigo_barras')" class="help-block help-block-error small">@{{ errors.first('scanner.codigo_barras') }}</span>
    										</div>
    									</div>
    								</div>
    							</div>
    							<div class="tab-pane" id="manual" role="tabpanel">
    								<div class="row">
    									<div class="col-12 col-lg-8">
    										<div class="row">
    											<div class="col-12 col-md-4 col-sm-6">
    												<div class="form-group" v-cloak>
    													{{ Form::cText('Código de barras', '', [
    														'v-model' => 'nuffer.codigo_barras',
    														'v-validate' => '"required|alpha_num"',
    														'v-bind:class' => '{"is-invalid": errors.has("header.codigo_barras")}',
    														'data-vv-as' => 'Código de barras',
    														'data-vv-name' => 'codigo_barras',
    														'data-vv-scope' => 'header',
    														'v-on:keydown.enter.prevent',
    													]) }}
    													<span v-show="errors.has('header.codigo_barras')" class="help-block help-block-error small">@{{ errors.first('header.codigo_barras') }}</span>
    												</div>
    											</div>
    											<div class="col-12 col-md-4 col-sm-6">
    												<div class="form-group" v-cloak>
    													{{ Form::cNumber('Cantidad', '', [
    														'min' => 0,
    														'v-model.number' => 'nuffer.cantidad_toma',
    														'v-bind:class' => '{"is-invalid": errors.has("header.cantidad_toma")}',
    														'v-validate' => '"required|min_value:1"',
    														'data-vv-as' => 'Cantidad',
    														'data-vv-name' => 'cantidad_toma',
    														'data-vv-scope' => 'header',
    														'v-on:keydown.enter.prevent',
    													]) }}
    													<span v-show="errors.has('header.cantidad_toma')" class="help-block help-block-error small">@{{ errors.first('header.cantidad_toma') }}</span>
    												</div>
    											</div>
    											<div class="col-12 col-md-4 col-sm-6">
    												<div class="form-group" v-cloak>
    													{{ Form::cText('Lote', '', [
    														'v-model' => 'nuffer.no_lote',
    														'v-validate' => '"required"',
    														'v-bind:class' => '{"is-invalid": errors.has("header.no_lote")}',
    														'data-vv-as' => 'Lote',
    														'data-vv-name' => 'no_lote',
    														'data-vv-scope' => 'header',
    														'v-on:keydown.enter.prevent'
    													]) }}
    													<span v-show="errors.has('header.no_lote')" class="help-block help-block-error small">@{{ errors.first('header.no_lote') }}</span>
    												</div>
    											</div>
    											<div class="col-12 col-md-4 col-sm-6">
    												<div class="form-group" v-cloak>
    													{{ Form::cText('Caducidad', '', [
    														'v-model' => 'nuffer.caducidad',
    														'v-pickadate' => 'nuffer.caducidad',
    														'v-validate' => '"required"',
    														'v-bind:class' => '{"is-invalid": errors.has("header.caducidad")}',
    														'data-vv-as' => 'Caducidad',
    														'data-vv-name' => 'caducidad',
    														'data-vv-scope' => 'header',
    														'v-on:keydown.enter.prevent'
    													]) }}
    													<span v-show="errors.has('header.caducidad')" class="help-block help-block-error small">@{{ errors.first('header.caducidad') }}</span>
    												</div>
    											</div>
    											<div class="col-12 col-md-4 col-sm-6">
    												<div class="form-group" v-cloak>
    													<label>Almacén</label>
    													<select class="form-control" v-select2="nuffer.fk_id_almacen" v-model.number="nuffer.fk_id_almacen" v-on:change="onChangeAlmacenes($event, true)" v-validate="'required|not_in:0'" v-bind:class="{'is-invalid': errors.has('header.fk_id_almacen')}" data-vv-as="Almacén" data-vv-name="fk_id_almacen" data-vv-scope="header">
    														<option v-for="almacen in almacenes" v-bind:value="almacen.value" v-bind:selected="almacen.selected" v-bind:disabled="almacen.disabled">@{{ almacen.text }}</option>
    													</select>
    													<span v-show="errors.has('header.fk_id_almacen')" class="help-block help-block-error small">@{{ errors.first('header.fk_id_almacen') }}</span>
    												</div>
    											</div>
    											<div class="col-12 col-md-4 col-sm-6">
    												<div class="form-group" v-cloak>
    													<label>Ubicación</label>
    													<select class="form-control" v-select2="nuffer.fk_id_ubicacion" v-model.number="nuffer.fk_id_ubicacion" v-validate="'required|not_in:0'" v-bind:class="{'is-invalid': errors.has('header.fk_id_ubicacion')}" data-vv-as="Ubicación" data-vv-name="fk_id_ubicacion" data-vv-scope="header">
    														<option v-for="ubicacion in ubicaciones[nuffer.fk_id_almacen]" v-bind:value="ubicacion.value" v-bind:selected="ubicacion.selected" v-bind:disabled="ubicacion.disabled">@{{ ubicacion.text }}</option>
    													</select>
    													<span v-show="errors.has('header.fk_id_ubicacion')" class="help-block help-block-error small">@{{ errors.first('header.fk_id_ubicacion') }}</span>
    												</div>
    											</div>
    										</div>
    									</div>
    									<div class="col-12 col-lg-4">
    										<div class="form-group full-height" v-cloak>
    											{{ Form::cTextArea('Observaciones', '', [
    												'rows' => 2,
    												'v-model' => 'nuffer.observaciones',
    											]) }}
    										</div>
    									</div>
    									<div class="col-sm-12 text-center">
    										<div class="sep">
    											<div class="sepBtn">
    												<button v-on:click="append" style="width: 4em; height:4em; border-radius:50%;" class="btn btn-primary btn-large" data-position="bottom" data-delay="50" data-toggle="Agregar" title="Agregar" type="button"><i class="material-icons">add</i></button>
    											</div>
    										</div>
    									</div>
    								</div>
    							</div>
    							<div class="tab-pane" id="importar" role="tabpanel">
    								<div class="row justify-content-center">
    									<div class="col-12 col-md-6 col-lg-4">
    										<div class="form-group">
    											<label for="importExcel">Selecciona el archivo <b>Excel</b> a importar</label>
    											<input type="file" accept=".csv" class="form-control" id="importExcel"
    											v-validate='"ext:csv"'
    											v-bind:class='{"is-invalid": errors.has("import.csv")}'
    											data-vv-as='archivo a importar'
    											data-vv-name="csv"
    											data-vv-scope='import'
    											v-on:change="importCSV">
    											<a href="#" v-on:click.prevent="downloadDummyCSV" class="small">Formato de archivo de importación</a>
    											<span v-show="errors.has('import.csv')" class="help-block help-block-error small">@{{ errors.first('import.csv') }}</span>
    										</div>
    									</div>
    								</div>
    							</div>
    						</div>
    					</div>
    				</div>
    			</div>
    			@endif
    			<div class="card-body">
    				<table class="table table-hover table-responsive-sm table-striped" style="table-layout: fixed">
    					<thead>
    						<tr>
    							<th style="width: 1px;">#</th>
    							<th>Código de barras</th>
    							<th>Descripción</th>
    							<th>Cantidad</th>
    							<th>Lote</th>
    							<th>Caducidad</th>
    							<th>Almacén</th>
    							<th>Ubicación</th>
    							<th>Observaciones</th>
    							<th></th>
    						</tr>
    					</thead>
    					<tbody v-if="upcs.length" v-cloak>
    						<tr v-for="upc,index in computedUpcs" v-bind:class="{'table-dark':upc.eliminar}" v-bind:data-index.prop.camel="index">
    							<th scope="row">
    								<span v-text="index + 1"></span>
    								<input type="hidden" v-bind:name="'relations[has][detalle]['+index+'][id_detalle]'" v-bind:value="upc.id_detalle">
    								<input type="hidden" v-bind:name="'relations[has][detalle]['+index+'][eliminar]'" v-bind:value="upc.eliminar">
    							</th>
    							<td>
    								<span v-if="!upc.props.editar && !errors.any('upcs-'+index)" v-text="upc.codigo_barras"></span>
    								<input type="text" class="form-control"
    									{{-- General --}}
    									v-show="upc.props.editar || errors.any('upcs-'+index)"
    									v-model="upc.codigo_barras"
    									v-bind:name="'relations[has][detalle]['+index+'][codigo_barras]'"
    									{{-- Validation --}}
    									v-validate="'required|alpha_num|verify_codebar:'+index"
    									v-bind:class="{'is-invalid': errors.has('upcs-'+index+'.codigo_barras')}"
    									data-vv-as="Código de barras"
    									data-vv-name="codigo_barras"
    									v-bind:data-vv-scope="'upcs-'+index"
    									{{-- Prevent submit --}}
    									v-on:keydown.enter.prevent
    									v-on:keyup.enter.prevent="onValidateCodebar">
    								<span v-show="errors.has('upcs-'+index+'.codigo_barras')" class="help-block help-block-error small">@{{ errors.first('upcs-'+index+'.codigo_barras') }}</span>
    							</td>
    							<td>
    								<span v-text="upc.upc.descripcion"></span>
    							</td>
    							<td>
    								<span v-if="!upc.props.editar && !errors.any('upcs-'+index)" v-text="upc.cantidad_toma"></span>
    								<input type="number" min="0" class="form-control"
    									{{-- General --}}
    									v-show="upc.props.editar || errors.any('upcs-'+index)"
    									v-model="upc.cantidad_toma"
    									v-bind:name="'relations[has][detalle]['+index+'][cantidad_toma]'"
    									{{-- Validation --}}
    									v-validate="'required|min_value:1'"
    									v-bind:class="{'is-invalid': errors.has('upcs-'+index+'.cantidad_toma')}"
    									data-vv-as="Cantidad"
    									data-vv-name="cantidad_toma"
    									v-bind:data-vv-scope="'upcs-'+index"
    									{{-- Prevent submit --}}
    									v-on:keydown.enter.prevent>
    								<span v-show="errors.has('upcs-'+index+'.cantidad_toma')" class="help-block help-block-error small">@{{ errors.first('upcs-'+index+'.cantidad_toma') }}</span>
    							</td>
    							<td>
    								<span v-if="!upc.props.editar && !errors.any('upcs-'+index)" v-text="upc.no_lote"></span>
    								<input type="text" class="form-control"
    									{{-- General --}}
    									v-show="upc.props.editar || errors.any('upcs-'+index)"
    									v-model="upc.no_lote"
    									v-bind:name="'relations[has][detalle]['+index+'][no_lote]'"
    									{{-- Validation --}}
    									v-validate="'required'"
    									v-bind:class="{'is-invalid': errors.has('upcs-'+index+'.no_lote')}"
    									data-vv-as="Lote"
    									data-vv-name="no_lote"
    									v-bind:data-vv-scope="'upcs-'+index"
    									{{-- Prevent submit --}}
    									v-on:keydown.enter.prevent>
    								<span v-show="errors.has('upcs-'+index+'.no_lote')" class="help-block help-block-error small">@{{ errors.first('upcs-'+index+'.no_lote') }}</span>
    							</td>
    							<td>
    								<span v-if="!upc.props.editar && !errors.any('upcs-'+index)" v-text="upc.caducidad"></span>
    								<input type="text" class="form-control"
    									{{-- General --}}
    									v-show="upc.props.editar || errors.any('upcs-'+index)"
    									v-model="upc.caducidad"
    									v-bind:name="'relations[has][detalle]['+index+'][caducidad]'"
    									v-pickadate="upc.caducidad"
    									{{-- Validation --}}
    									v-validate="'required'"
    									v-bind:class="{'is-invalid': errors.has('upcs-'+index+'.caducidad')}"
    									data-vv-as="Caducidad"
    									data-vv-name="caducidad"
    									v-bind:data-vv-scope="'upcs-'+index"
    									{{-- Prevent submit --}}
    									v-on:keydown.enter.prevent>
    								<span v-show="errors.has('upcs-'+index+'.caducidad')" class="help-block help-block-error small">@{{ errors.first('upcs-'+index+'.caducidad') }}</span>
    							</td>
    							<td>
    								<span v-if="!upc.props.editar && !errors.any('upcs-'+index)" v-text="almacenes[upc.fk_id_almacen].text"></span>
    								<div v-show="upc.props.editar || errors.any('upcs-'+index)">
    									<select class="form-control"
    										{{-- General --}}
    										v-model.number="upc.fk_id_almacen"
    										v-bind:name="'relations[has][detalle]['+index+'][fk_id_almacen]'"
    										v-on:change="onChangeAlmacenes"
    										v-select2="upc.fk_id_almacen"
    										{{-- Validation --}}
    										v-validate="'required|not_in:0'"
    										v-bind:class="{'is-invalid': errors.has('upcs-'+index+'.fk_id_almacen')}"
    										data-vv-as="Almacén"
    										data-vv-name="fk_id_almacen"
    										v-bind:data-vv-scope="'upcs-'+index">
    										<option v-for="almacen in almacenes" v-bind:value="almacen.value" v-bind:selected="almacen.selected" v-bind:disabled="almacen.disabled">@{{ almacen.text }}</option>
    									</select>
    								</div>
    								<span v-show="errors.has('upcs-'+index+'.fk_id_almacen')" class="help-block help-block-error small">@{{ errors.first('upcs-'+index+'.fk_id_almacen') }}</span>
    							</td>
    							<td>
    								<span v-if="!upc.props.editar && !errors.any('upcs-'+index)" v-text="ubicaciones[upc.fk_id_almacen][upc.fk_id_ubicacion].text"></span>
    								<div v-show="upc.props.editar || errors.any('upcs-'+index)">
    									<select class="form-control"
    										{{-- General --}}
    										v-model.number="upc.fk_id_ubicacion"
    										v-bind:name="'relations[has][detalle]['+index+'][fk_id_ubicacion]'"
    										v-select2="upc.fk_id_ubicacion"
    										{{-- Validation --}}
    										v-validate="'required|not_in:0'"
    										v-bind:class="{'is-invalid': errors.has('upcs-'+index+'.fk_id_ubicacion')}"
    										data-vv-as="Almacén"
    										data-vv-name="fk_id_ubicacion"
    										v-bind:data-vv-scope="'upcs-'+index">
    										<option v-for="ubicacion in ubicaciones[upc.fk_id_almacen]" v-bind:value="ubicacion.value" v-bind:selected="ubicacion.selected" v-bind:disabled="ubicacion.disabled">@{{ ubicacion.text }}</option>
    									</select>
    								</div>
    								<span v-show="errors.has('upcs-'+index+'.fk_id_ubicacion')" class="help-block help-block-error small">@{{ errors.first('upcs-'+index+'.fk_id_ubicacion') }}</span>
    							</td>
    							<td>
    								<span v-if="!upc.props.editar && !errors.any('upcs-'+index)" v-text="upc.observaciones"></span>
    								<textarea class="form-control" rows="2"
    									{{-- General --}}
    									v-show="upc.props.editar || errors.any('upcs-'+index)"
    									v-model="upc.observaciones"
    									v-bind:name="'relations[has][detalle]['+index+'][observaciones]'"
    									{{-- Prevent submit --}}
    									v-on:keydown.enter.prevent>
    								</textarea>
    							</td>
    							<td style="width: 1px !important;position: relative;">
    								<div v-if="upc.props.valido == 'unknow'"  class="w-100 h-100 text-center text-white align-middle loadingData">Validando ...<i class="material-icons align-middle loading">cached</i></div>
    								@if (!Route::currentRouteNamed(currentRouteName('show')))
    								<a href="#" v-if="(!upc.eliminar && upc.props.editar && upc.props.valido != 'unknow') || errors.any('upcs-'+index)" v-on:click.prevent="editOrDone"><i class="material-icons">done</i> Cerrar</a>
    								<a href="#" v-if="!upc.eliminar && !upc.props.editar && !errors.any('upcs-'+index)" v-on:click.prevent="editOrDone"><i class="material-icons">mode_edit</i> Editar</a>
    								<a href="#" v-on:click.prevent="removeOrUndo">
    									<i class="material-icons" v-text="upc.eliminar ? 'undo' : 'delete'"></i>
    									<span v-text="upc.eliminar ? 'Deshacer' : 'Eliminar'"></span>
    								</a>
    								@endif
    							</td>
    						</tr>
    					</tbody>
    					<tbody v-else>
    						<tr class="text-center">
    							<td colspan="10">Agrega uno o más detalles.</td>
    						</tr>
    					</tbody>
    				</table>
    			</div>
    		</div>
    	</div>
    </div>
@endsection

@section('header-bottom')
    @parent
    <script src="{{ asset('vendor/vue/vue.js') }}"></script>
    <script src="{{ asset('vendor/papaparse/papaparse.min.js') }}"></script>
    <script type="text/javascript">
    	function debounce(func, wait, immediate) {
    		var timeout;
    		return function() {
    			var context = this, args = arguments;
    			var later = function() {
    				timeout = null;
    				if (!immediate) func.apply(context, args);
    			};
    			var callNow = immediate && !timeout;
    			clearTimeout(timeout);
    			timeout = setTimeout(later, wait);
    			if (callNow) func.apply(context, args);
    		};
    	};
    
    	$(function(){
    		if ($('#app').length) {
    			function updateSelect2 (el, binding) {
    				$(el).off().val(binding.value).select2().on('select2:select', function(e){
    					el.dispatchEvent(new Event('change', { target: e.target }));
    				});
    			}
    			Vue.directive('select2', {inserted: updateSelect2, componentUpdated: updateSelect2});
    
    			function updatePickadate (el, binding) {
    				if ( $(el).pickadate('picker') ) {
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
    				data: function() {
    					return {
    						tipo_captura: 1,
    						queue: [],
    						buffer: {
    							id_detalle: null,
    							codigo_barras: '',
    							cantidad_toma: 0,
    							no_lote: '',
    							caducidad: '',
    							fk_id_almacen: 0,
    							fk_id_ubicacion: 0,
    							observaciones: '',
    							eliminar: false,
    							props: {
    								valido: 'unknow',
    								editar: false,
    								queue: null,
    							},
    							upc: {
    								descripcion: ''
    							}
    						},
    						nuffer: {},
    						upcs: @json($upcs ?? []),
    						/* Catalogos con defaults */
    						almacenes: @json($vue_almacenes ?? []),
    						ubicaciones: @json($vue_ubicaciones ?? [])
    						/* almacenes: {0: {value: 0, text: '...', selected: true, disabled: true}}, */
    						/* ubicaciones: {0: {0: {value: 0, text: '...', selected: true, disabled: true}}} */
    					}
    				},
    				methods: {
    					/* Limpiamos notificacion de errores */
    					clearErrorScope: function(scope) {
    						this.$nextTick(function() {
    							this.errors.clear(scope)
    						}.bind(this))
    					},
    					/* Buscamos indice-posicion de <td> */
    					getDataIndex: function(e) {
    						var dataIndex;
    						for (var i in e.path) {
    							if (e.path[i].dataIndex >= 0) {
    								dataIndex = e.path[i].dataIndex;
    								break;
    							}
    						}
    						return dataIndex;
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
    					/* Codebar-only */
    					onKeydownCodebar: function(e) {
    						if (!new RegExp('^[a-zA-Z0-9]+$').test(e.key) || e.ctrlKey || e.keyCode == 13) {
    							e.preventDefault();
    						}
    					},
    					onEnterCodebar: function(e) {
    						/* Validamos campo */
    						this.$validator.validateAll('scanner').then(function(isValid){
    							if (isValid) {
    								/* codebar --enviamos--> upcs */
    								this.upcs.push(JSON.parse(JSON.stringify($.extend(true, {}, this.buffer, {
    									codigo_barras: e.target.value, props: {editar: true}
    								}))))
    								/* Obtenemos ultimo upc (recien agregado) */
    								var upc = this.upcs.slice(-1).shift();
    									upc.props.queue = this.remoteValidateCodebar(upc);
    								/* Limpiamos campo */
    								e.target.value = '';
    							}
    						}.bind(this))
    					},
    					/* Revalidacion de codigo de barra en detalle */
    					onValidateCodebar: function(e) {
    						/* Obtenemos indice de fila */
    						var index = this.getDataIndex(e);
    						/* Upc actual */
    						var upc = this.upcs[index];
    							upc.props.valido = 'unknow';
    							upc.props.queue = this.remoteValidateCodebar(upc);
    					},
    					/* Agregamos a cola de validaciones */
    					remoteValidateCodebar: function(upc) {
    						var endpoint = this.$root.$el.dataset.apiEndpoint.replace('#ENTITY#', 'inventarios.upcs');
    						return this.enQueue(function() {
    							return $.get(endpoint, { param_js: '{{$api_codebar ?? ''}}', $codigo_barras: this.codigo_barras
    								/* conditions: [{'where': ['upc', this.codigo_barras]}], */
    							}, function(data) {
    								this.props.valido = (data.length > 0);
    								/* Si no valido */
    								if (!this.props.valido) {
    									this.props.editar = true
    								} else {
    									this.upc.descripcion = data[0].descripcion;
    								}
    							}.bind(this))
    						}.bind(upc))
    					},
    					onChangeAlmacenes: function(e, isHead) {
    						if (isHead) {
    							this.nuffer.fk_id_ubicacion = 0;
    						} else {
    							/* Obtenemos indice de fila */
    							var index = this.getDataIndex(e);
    							this.upcs[index].fk_id_ubicacion = 0;
    						}
    						/* Almacen seleccionado */
    						var fk_id_almacen = e.target.value;
    						/* Si no existe almacen en arreglo de ubicaciones (ajax) */
    						if (!this.ubicaciones[fk_id_almacen]) {
    							this.ubicaciones[fk_id_almacen] = {0:{value: 0, text: 'Obteniendo ...', selected: true, disabled: true}};
    							$.get(this.$root.$el.dataset.apiEndpoint.replace('#ENTITY#', 'inventarios.ubicaciones'), {
    								/* conditions: [{'where': ['fk_id_almacen', fk_id_almacen]}], */
    								param_js: '{{$api_almacen ?? ''}}', $fk_id_almacen: fk_id_almacen
    							}, function(data) {
    								/* Si hay resultados */
    								if (data.length > 0) {
    									this.ubicaciones[fk_id_almacen] = data.reduce(function(acc, item){
    										acc[item.id_ubicacion] = {
    											value: item.id_ubicacion,
    											text: item.ubicacion,
    										}
    										return acc;
    									}, {0:{value: 0, text: 'Selecciona ...', selected: true, disabled: true}});
    								} else {
    									this.ubicaciones[fk_id_almacen] = {0:{value: 0, text: 'Sin resultados ...', selected: true, disabled: true}};
    								}
    								this.$forceUpdate();
    							}.bind(this))
    						} else {
    							this.$forceUpdate();
    						}
    					},
    					append: function(e) {
    						/* Validamos campos */
    						this.$validator.validateAll('header').then(function(isValid){
    							if (isValid) {
    								/* upc --enviamos--> upcs */
    								this.upcs.push(JSON.parse(JSON.stringify($.extend(true, {}, this.nuffer))));
    								/* Obtenemos ultimo upc (recien agregado) */
    								var upc = this.upcs.slice(-1).shift();
    									upc.props.queue = this.remoteValidateCodebar(upc);
    								/* Limpiamos campos */
    								this.nuffer = $.extend(true, {}, this.buffer);
    								this.clearErrorScope('header');
    							}
    						}.bind(this))
    					},
    					editOrDone: function(e) {
    						var index = this.getDataIndex(e);
    						this.$validator.validateAll('upcs-' + index).then(function(isValid){
    							if (isValid) {
    								this.upcs[index].props.editar = !this.upcs[index].props.editar;
    							}
    						}.bind(this))
    					},
    					removeOrUndo: function(e) {
    						var index = this.getDataIndex(e);
    						if (!this.upcs[index].id_detalle) {
    							var queueIndex = this.queue.indexOf(this.upcs[index].props.queue);
    							if (queueIndex >= 0) {
    								this.queue.splice(queueIndex, 1);
    							}
    							this.upcs.splice(index, 1);
    							return;
    						}
    						this.upcs[index].eliminar = !this.upcs[index].eliminar;
    					},
    					importCSV: function(e) {
    						/* Validamos archivo */
    						this.$validator.validateAll('import').then(function(isValid){
    							if (isValid) {
    								/* Recorremos almacenes */
    								var search = Object.keys(this.almacenes).reduce(function(acc, item){
    									/* Recorremos ubicaciones de almacen */
    									var ubi = Object.keys(this.ubicaciones[this.almacenes[item].value]).reduce(function(acc, itemu){
    										acc[ this.ubicaciones[this.almacenes[item].value][itemu].text.toUpperCase() ] = this.ubicaciones[this.almacenes[item].value][itemu].value;
    										return acc;
    									}.bind(this), {})
    									acc[ this.almacenes[item].text.toUpperCase() ] = {value: this.almacenes[item].value, ubicaciones: ubi };
    									return acc;
    								}.bind(this), {})
    
    								Papa.parse(e.target.files[0], {
    									header: true,
    									skipEmptyLines: true,
    									complete: function(parse) {
    										/* Parse ok */
    										if (parse.errors.length == 0) {
    											/* Agregamos lineas */
    											for (var i in parse.data) {
    												/* Buscamos identificador de almacen y ubicacion */
    												var search_almacenes_keys =  Object.keys(search);
    												var index_almacen = search_almacenes_keys.indexOf(parse.data[i].fk_id_almacen.toUpperCase());
    												if (index_almacen !== -1) {
    													parse.data[i].fk_id_almacen = search[search_almacenes_keys[index_almacen]].value;
    													var search_ubicaciones_keys =  Object.keys(search[search_almacenes_keys[index_almacen]].ubicaciones);
    													var index_ubicacion = search_ubicaciones_keys.indexOf(parse.data[i].fk_id_ubicacion.toUpperCase());
    													if (index_ubicacion !== -1) {
    														parse.data[i].fk_id_ubicacion = search[search_almacenes_keys[index_almacen]].ubicaciones[search_ubicaciones_keys[index_ubicacion]];
    													} else {
    														parse.data[i].fk_id_ubicacion = 0
    													}
    												} else {
    													parse.data[i].fk_id_almacen	= 0
    													parse.data[i].fk_id_ubicacion = 0
    												}
    
    												if (parse.data[i].codigo_barras !== '') {
    													this.upcs.push(JSON.parse(JSON.stringify($.extend(true, {}, this.buffer, parse.data[i], {props: {editar: true}}))));
    													/* Obtenemos ultimo upc (recien agregado) */
    													var upc = this.upcs.slice(-1).shift();
    														upc.props.queue = this.remoteValidateCodebar(upc);
    												}
    											}
    										}
    										e.target.value = '';
    									}.bind(this)
    								})
    							}
    						}.bind(this))
    					},
    					downloadDummyCSV: function() {
    
    						var csv, uri, link;
    
    						csv = Papa.unparse({
    							fields: ['codigo_barras', 'cantidad_toma', 'no_lote', 'caducidad', 'fk_id_almacen', 'fk_id_ubicacion', 'observaciones'],
    							data: [
    								['12345678', '10', 'Numero de lote', '2017-11-30', 'Ejemplo', 'Ejemplo', 'Algun comentario ...'],
    								['12345678', '10', 'Numero de lote', '2017-11-30', 'Ejemplo', 'Ejemplo', 'Algun comentario ...'],
    								['12345678', '10', 'Numero de lote', '2017-11-30', 'Ejemplo', 'Ejemplo', 'Algun comentario ...'],
    							]
    						});
    
    						uri = 'data:application/csv;charset=utf-8,' + encodeURIComponent(csv);
    
    						/* Create a link to trigger the download */
    						link = document.createElement('a');
    						link.href = uri;
    						link.download = 'formato.csv';
    
    						/* Append the link */
    						document.body.appendChild(link);
    
    						/* Trigger the download */
    						link.click();
    
    						/* Remove the link */
    						document.body.removeChild(link);
    					}
    				},
    				watch: {
    					trace: function(after, before) {
    
    						var changes = {index: null, path: '', old:'', new:''};
    						var trace = function(after, before, acc) {
    							return Object.keys(after).some(function(index){
    								if(!acc.index) {
    									acc.index = index;
    								}
    								if (typeof after[index] == 'object' && after[index] != null) {
    									var isDiff = trace(after[index], before[index], acc)
    									if (isDiff) {
    										acc.index = index;
    										acc.path = index + '.' + acc.path;
    									}
    									return isDiff;
    								}
    								/* Si existe nodo */
    								if (before) {
    									var isDiff = after[index] != before[index];
    									if (isDiff) {
    										acc.type = 'modified';
    										acc.path += index;
    										acc.old = before[index];
    										acc.new = after[index];
    									}
    								} else {
    									acc.type = 'added';
    									acc.path += index;
    									acc.old = null;
    									acc.new = after;
    									return true
    								}
    								return isDiff
    							}.bind(this));
    						}
    
    						trace(after, before, changes)
    
    						if (changes.path == (changes.index + '.codigo_barras')) {
    							this.upcs[changes.index].props.valido = false;
    							/* Validar campo */
    							this.$validator.validate('upcs-'+ changes.index + '.codigo_barras')
    						}
    
    						if (changes.path == (changes.index + '.props.valido')) {
    							/* Validar campo */
    							this.$validator.validate('upcs-'+ changes.index + '.codigo_barras')
    						}
    
    					},
    				},
    				computed: {
    					trace: function() {
    						return this.upcs.reduce(function(acc, item){
    							return acc.concat($.extend(true, {}, item))
    						}, [])
    					},
    					computedUpcs: function() {
    						return this.upcs.reduce(function(acc, item){
    							if (!item.props) {
    								Vue.set(item, 'props', {
    									valido: true,
    									editar: false,
    									queue: null,
    								})
    							}
    							if (!item.eliminar) {
    								Vue.set(item, 'eliminar', false)
    							}
    							return acc.concat(item)
    						}, []);
    					}
    				},
    				beforeMount: function() {
    					var vm = this;
    
    					/* First */
    					this.nuffer = $.extend(true, {}, this.buffer);
    
    					this.$validator.extend('verify_codebar', {
    						getMessage: function(field) {
    							return field + ' incorrecto. "Enter" para revalidar.'
    						},
    						validate: function(value, index, a) {
    							return {valid: this.upcs[index[0]].props.valido === true};
    						}.bind(this),
    					});
    
    					function getAlmacenesUbicaciones(el) {
    						$.get(vm.$root.$el.dataset.apiEndpoint.replace('#ENTITY#', 'inventarios.almacenes'), {
    							param_js: '{{$api_almacenes_ubicaciones ?? ''}}', $fk_id_sucursal: el.value
    							/* conditions: [{'where':['fk_id_sucursal', this.value]}], */
    							/* with: ['ubicaciones:id_ubicacion,fk_id_almacen,ubicacion'], */
    						}, function(almacenes){
    							/* defaults */
    							var _almacenes = {0: {value: 0, text: '...'}}, _ubicaciones = {0: {0: {value: 0, text: '...'}}};
    							/* Recorremos almacenes */
    							almacenes.forEach(function(almacen){
    								_almacenes[almacen.id_almacen] = {value: almacen.id_almacen, text: almacen.almacen};
    								_ubicaciones[almacen.id_almacen] = {};
    								/* Si tiene ubicaciones, las recorremos */
    								if (almacen.ubicaciones.length > 0) {
    									_ubicaciones[almacen.id_almacen][0] = {value: 0, text: 'Selecciona ...'}
    									almacen.ubicaciones.forEach(function(ubicacion){
    										_ubicaciones[almacen.id_almacen][ubicacion.id_ubicacion] = {value: ubicacion.id_ubicacion, text: ubicacion.ubicacion}
    									})
    								} else {
    									_ubicaciones[almacen.id_almacen][0] = {value: 0, text: 'Sin resultados ...'}
    								}
    							})
    							vm.almacenes = _almacenes;
    							vm.ubicaciones = _ubicaciones;
    						})
    					}
    
    					$('#fk_id_sucursal').on('change', function(){
    
    						vm.nuffer.fk_id_almacen= 0;
    						vm.nuffer.fk_id_ubicacion = 0;
    						vm.upcs.forEach(function(item){
    							item.fk_id_almacen = 0;
    							item.fk_id_ubicacion = 0;
    						});
    
    						getAlmacenesUbicaciones(this);
    					})
    					if ($('#fk_id_sucursal').val() != 0) {
    						getAlmacenesUbicaciones($('#fk_id_sucursal')[0]);
    					}
    
    					$('[name="fk_id_almacen"]').on('beforeupdate', function (e) {
    						vm.almacenes = {0:{value: 0, text: 'Obteniendo ...', selected: true, disabled: true}}
    					});
    
    					$('#form-model').on('submit', function(e) {
    						e.preventDefault();
    						var form = this;
    
    						/* Obtenemos scopes upcs-* a validar */
    						var scopes = Object.keys(vm.fields).filter(function(item){
    							return item.indexOf('upcs-') !== -1
    						});
    						scopes_promises = scopes.map(function(item) {
    							return vm.$validator.validateAll(item.replace('$', ''))
    						});
    						/* Si todos resuelven validados */
    						Promise.all(scopes_promises).then(function(results){
    							var areValids = results.every(function(result){
    								return result;
    							})
    							/* Si detalle y formulario estan validados */
    							if (areValids && $(form).validate().form()) {
    								form.submit()
    							}
    						})
    					});
    				}
    			});
    		}
    	})
    </script>
@endsection