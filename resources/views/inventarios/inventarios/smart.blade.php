
@section('content-width', 's12')

@section('form-content')
{{ Form::setModel($data) }}
<div class="row">
	<div class="col-md-4 col-sm-4">
		<div class="form-group">
			{{ Form::cSelectWithDisabled('Tipo de inventario', 'fk_tipo_inventario', [
			'1' => 'Periodico',
			'2' => 'Ciclico',
			'3' => 'Extraordinario',
			], ['class' => 'select2']) }}
		</div>
	</div>
	<div class="col-md-4 col-sm-4">
		<div class="form-group">
			{{ Form::cSelectWithDisabled('Sucursal', 'fk_id_sucursal', $sucursales ?? [], [
				'class' => 'select2 select-cascade',
				'data-target-url' => companyRoute('administracion.sucursales.show', ['id' => '#ID#']),
				'data-target-el' => '[targeted="fk_id_almacen"]',
				'data-target-with' => '["almacenes:id_almacen,fk_id_sucursal,almacen"]',
				'data-target-value' => 'almacenes,id_almacen,almacen'
			]) }}
		</div>
	</div>
	<div class="col-md-4 col-sm-4">
		<div class="form-group">
			{{ Form::cSelect('Almacén', 'fk_id_almacen', $almacenes ?? [], ['id' => 'some','class' => 'select2', 'targeted' => 'fk_id_almacen']) }}
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12 col-sm-12 mb-3">
		<div id="app" class="card z-depth-1-half">
			<div class="card-header text-center">
				<div class="row">
					<div class="col-sm-12">
						<p>Tipo de <b>captura</b> para el producto</p>
						<ul class="nav nav-tabs btn-group justify-content-center border-0 mb-3" role="tablist">
							<a class="btn m-0 btn-info active" data-toggle="tab" href="#scanner" role="tab" aria-expanded="true"><i class="material-icons align-middle">settings_remote</i> Captura con scanner</a>
							<a class="btn m-0 btn-info" data-toggle="tab" href="#manual" role="tab"><i class="material-icons align-middle">keyboard</i> Captura manual</a>
							<a class="btn m-0 btn-info" data-toggle="tab" href="#importar" role="tab"><i class="material-icons align-middle">get_app</i> Importar Excel</a>
						</ul>
					</div>
					<div class="col-sm-12">
						<div class="tab-content">
							<div class="tab-pane active" id="scanner" role="tabpanel">
								<div class="row justify-content-center">
									<div class="col-12 col-md-6 col-lg-4">
										<div class="form-group">
											{{ Form::cText('Código de barras', 'codebar-only', [
												'ref' => 'codeba',
												'v-on:keydown' => 'onKeydownCodebar',
												'v-on:keyup.enter' => 'onEnterCodebar'
											]) }}
										</div>
									</div>
								</div>
							</div>
							<div class="tab-pane" id="manual" role="tabpanel">
								<div class="row">
									<div class="col-12 col-lg-8">
										<div class="row">
											<div class="col-12 col-md-4 col-sm-6">
												<div class="form-group">
													{{ Form::cText('Código de barras', 'codebar', ['ref' => 'codebar']) }}
												</div>
											</div>
											<div class="col-12 col-md-4 col-sm-6">
												<div class="form-group">
													{{ Form::cNumber('Cantidad', 'cantidad', ['ref' => 'cantidad']) }}
												</div>
											</div>
											<div class="col-12 col-md-4 col-sm-6">
												<div class="form-group">
													{{ Form::cText('Lote', 'lote', ['ref' => 'lote']) }}
												</div>
											</div>
											<div class="col-12 col-md-4 col-sm-6">
												<div class="form-group">
													{{ Form::cText('Caducidad', 'caducidad', ['ref' => 'caducidad']) }}
												</div>
											</div>
											<div class="col-12 col-md-4 col-sm-6">
												<div class="form-group">
													{{ Form::cSelectWithDisabled('Almacén', 'almacen', [], [
														'class' => 'select2 select-cascade',
														'targeted' => 'fk_id_almacen',
														'data-target-url' => companyRoute('almacenes.show', ['id' => '#ID#']),
														'data-target-el' => '[targeted="fk_id_ubicacion"]',
														'data-target-with' => '["ubicaciones:id_ubicacion,fk_id_almacen,ubicacion"]',
														'data-target-value' => 'ubicaciones,id_ubicacion,ubicacion',
														'ref' => 'almacen'
													]) }}
												</div>
											</div>
											<div class="col-12 col-md-4 col-sm-6">
												<div class="form-group">
													{{ Form::cSelectWithDisabled('Ubicación', 'ubicacion', [], [
														'class' => 'select2',
														'targeted' => 'fk_id_ubicacion',
														'ref' => 'ubicacion'
													]) }}
												</div>
											</div>
										</div>
									</div>
									<div class="col-12 col-lg-4">
										<div class="form-group full-height">
											{{ Form::cTextArea('Observaciones', 'observaciones', ['ref' => 'observaciones', 'rows' => 2]) }}
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
								<div class="row">
									<div class="col-sm-12 col-md-4">
										<div class="form-group">
											<label for="importExcel">Selecciona el archivo <b>Excel</b> a importar</label>
											<input type="file" class="form-control-file btn" id="importExcel">
										</div>
									</div>
									<div class="col-sm-12 col-md-8">
										<p>Formato del archivo <b>Excel:</b></p>
										<img src="img/índice.png" class="img-fluid float-left" alt="Formato de ejemplo">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="card-body">
				<table class="table table-hover table-responsive table-striped" style="table-layout: fixed">
					<thead>
						<tr>
							<th>#</th>
							<th>Código de barras</th>
							<th>Cantidad</th>
							<th>Lote</th>
							<th>Caducidad</th>
							<th>Almacén</th>
							<th>Ubicación</th>
							<th>Observaciones</th>
							<th></th>
						</tr>
					</thead>
					<tbody v-if="ubicaciones.length" v-cloak>
						<tr v-for="ubicacion,index in computedUbicaciones" v-bind:class="{'table-dark':ubicacion.eliminar}">
							<th scope="row">
								<span v-text="index + 1"></span>
								<input type="hidden" v-bind:name="'relations[has][ubicaciones]['+index+'][id_ubicacion]'" v-bind:value="ubicacion.id_ubicacion">
								<input type="hidden" v-bind:name="'relations[has][ubicaciones]['+index+'][eliminar]'" v-bind:value="ubicacion.eliminar">
							</th>
							<td>
								<span v-if="!ubicacion.editar" v-text="ubicacion.codebar"></span>
								<input type="text" class="form-control" v-show="ubicacion.editar" v-bind:name="'relations[has][ubicaciones]['+index+'][codebar]'" v-model="ubicacion.codebar">
							</td>
							<td>
								<span v-if="!ubicacion.editar" v-text="ubicacion.cantidad"></span>
								<input type="text" class="form-control" v-show="ubicacion.editar" v-bind:name="'relations[has][ubicaciones]['+index+'][cantidad]'" v-model="ubicacion.cantidad">
							</td>
							<td>
								<span v-if="!ubicacion.editar" v-text="ubicacion.lote"></span>
								<input type="text" class="form-control" v-show="ubicacion.editar" v-bind:name="'relations[has][ubicaciones]['+index+'][lote]'" v-model="ubicacion.lote">
							</td>
							<td>
								<span v-if="!ubicacion.editar" v-text="ubicacion.caducidad"></span>
								<input type="text" class="form-control" v-show="ubicacion.editar" v-bind:name="'relations[has][ubicaciones]['+index+'][caducidad]'" v-model="ubicacion.caducidad">
							</td>
							<td>
								<span v-if="!ubicacion.editar" v-text="ubicacion.almacen"></span>
								<input type="text" class="form-control" v-show="ubicacion.editar" v-bind:name="'relations[has][ubicaciones]['+index+'][almacen]'" v-model="ubicacion.almacen">
							</td>
							<td>
								<span v-if="!ubicacion.editar" v-text="ubicacion.ubicacion"></span>
								<input type="text" class="form-control" v-show="ubicacion.editar" v-bind:name="'relations[has][ubicaciones]['+index+'][ubicacion]'" v-model="ubicacion.ubicacion">
							</td>
							<td>
								<span v-if="!ubicacion.editar" v-text="ubicacion.observaciones"></span>
								<input type="text" class="form-control" v-show="ubicacion.editar" v-bind:name="'relations[has][ubicaciones]['+index+'][observaciones]'" v-model="ubicacion.observaciones">
							</td>
							<td style="width: 1px !important;">
								@if (!Route::currentRouteNamed(currentRouteName('show')))
								<a href="#" v-if="ubicacion.eliminar" v-on:click.prevent="removeOrUndo(index)"><i class="material-icons">undo</i> Deshacer</a>
								<template v-else>
									<a href="#" v-if="ubicacion.editar" v-on:click.prevent="editOrDone(index)"><i class="material-icons">done</i></a>
									<template v-else>
										<a href="#" v-on:click.prevent="editOrDone(index)"><i class="material-icons">mode_edit</i></a>
										<a href="#" v-on:click.prevent="removeOrUndo(index)"><i class="material-icons">delete</i></a>
									</template>
								</template>
								@endif
							</td>
						</tr>
					</tbody>
					<tbody v-else>
						<tr class="text-center">
							<td colspan="7">Agrega una o más Ubicaciones.</td>
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
<script src="{{ asset('vendor/vue/vue.2.4.4.min.js') }}"></script>
<script type="text/javascript">
	// $('[name="caducidad"]').pickadate({
	//     selectMonths: true, // Creates a dropdown to control month
	//     selectYears: 3, // Creates a dropdown of 3 years to control year
	//     min: true,
	//     format: 'yyyy/mm/dd'
	// });
	var app = new Vue({
		el: '#app',
		data: {
			buffer: {id_ubicacion: null, codebar: '', cantidad: '', lote: '', caducidad: '', almacen: '', ubicacion: '', observaciones: '', eliminar: 0},
			ubicaciones: @json($ubicaciones ?? []),
		},
		methods: {
			onKeydownCodebar: function(e) {
				if (!new RegExp('^[a-zA-Z0-9]+$').test(e.key) || e.ctrlKey || e.keyCode == 13) {
					e.preventDefault();
				}
			},
			onEnterCodebar: function(e) {
				e.preventDefault();
				console.log('onEnterCodebar')
			},
			append: function(e) {
				var data, isValid = true, valid;
				// Recorremos referencias
				data = Object.keys(this.$refs).reduce(function(acc, item){
					// Validamos campo
					// var valid = $('#form-model').validate().element( '#' + item );
					// if (!valid) isValid = valid;
					acc[item] = this.$refs[item].value;
					return acc;
				}.bind(this), {});
				if (!isValid) { return; }
				Object.keys(this.$refs).forEach(function(key){ this.$refs[key].value = '' }.bind(this));
				this.ubicaciones.push(JSON.parse(JSON.stringify($.extend(this.buffer, data))))
			},
			editOrDone: function(index) {
				this.ubicaciones[index].editar = !this.ubicaciones[index].editar;
			},
			removeOrUndo: function(index) {
				if (!this.ubicaciones[index].id_ubicacion) {
					this.ubicaciones.splice(index ,1); return;
				}
				this.ubicaciones[index].eliminar = !this.ubicaciones[index].eliminar;
			}
		},
		computed: {
			computedUbicaciones: function() {
				return this.ubicaciones.reduce(function(acc, item){
					if (!item.editar) { Vue.set(item, 'editar', false)}
						return acc.concat(item)
				}, []);
			}
		},
		mounted: function(e) {

			$('[name="caducidad"]').pickadate({
				selectMonths: true, // Creates a dropdown to control month
				selectYears: 3, // Creates a dropdown of 3 years to control year
				min: true,
				format: 'yyyy/mm/dd'
			});

		}
	});
	jQuery(document).ready(function(){
		function addRules() {
			// $('#rack').rules('add',{
			// 	required: true,
			// 	messages:{
			// 		required: 'El campo rack es requerido.'
			// 	}
			// });
			// $('#ubicacion').rules('add',{
			// 	required: true,
			// 	messages:{
			// 		required: 'El campo ubicacion es requerido.'
			// 	}
			// });
			// $('#posicion').rules('add',{
			// 	required: true,
			// 	messages:{
			// 		required: 'El campo posicion es requerido.'
			// 	}
			// });
			// $('#nivel').rules('add',{
			// 	required: true,
			// 	messages:{
			// 		required: 'El campo nivel es requerido.'
			// 	}
			// });
		}
		$('#form-model').on('submit', function(e) {
			e.preventDefault();

			Object.keys(app.$refs).forEach(function(key){
				$(app.$refs[key]).rules('remove')
			});

			if ($(this).validate().form()) {
				$(this).validate().destroy();
				this.submit();
			} else {
				addRules();
			}
		});
		if ($('#form-model').length) {
			addRules();
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