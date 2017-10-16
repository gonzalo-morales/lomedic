
@section('content-width', 's12')

@section('form-content')
{{ Form::setModel($data) }}
<div class="row">
	<div class="col-md-4 col-sm-4">
		<div class="form-group">
			{{ Form::cSelectWithDisabled('Tipo de inventario', 'fk_id_sucursal', [
			'1' => 'Periodico',
			'2' => 'Ciclico',
			'3' => 'Extraordinario',
			]) }}
		</div>
	</div>
	<div class="col-md-4 col-sm-4">
		<div class="form-group">
			{{ Form::cSelectWithDisabled('Sucursal', 'fk_id_sucursal', $sucursales ?? [], [
				'data-target-url' => companyRoute('administracion.sucursales.show', ['id' => '#ID#']),
				'data-target-el' => 'fk_id_estado',
				'data-target-with' => '["almacenes:id_almacen,fk_id_sucursal,almacen"]',
				'data-target-value' => 'almacenes,id_almacen,almacen'
			]) }}
		</div>
	</div>
	<div class="col-md-4 col-sm-4">
		<div class="form-group">
			{{ Form::cSelect('Almacén', 'fk_id_almacen', $almacenes ?? []) }}
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12 col-sm-12 mb-3">
		<div id="app" class="card">
			<div class="card-header text-center">
				<div class="row">
					<div class="col-sm-12">
						<p>Tipo de <b>captura</b> para el producto</p>
						<ul class="nav btn-group justify-content-center" id="myTab" role="tablist">
							<li class="nav-item">
								<a class="btn btn-info active" data-toggle="tab" href="#scanner" role="tab"><i class="material-icons align-middle">settings_remote</i> Captura con scanner</a>
							</li>
							<li class="nav-item">
								<a class="btn btn-info" data-toggle="tab" href="#manual" role="tab"><i class="material-icons align-middle">keyboard</i> Captura manual</a>
							</li>
							<li class="nav-item">
								<a class="btn btn-info" data-toggle="tab" href="#importar" role="tab"><i class="material-icons align-middle">get_app</i> Importar Excel</a>
							</li>
						</ul>
					</div>
					<div class="col-sm-12">
						<div class="tab-content">
							<div class="tab-pane active" id="scanner" role="tabpanel">
								<div class="row justify-content-center">
									<div class="col-12 col-md-6 col-lg-4">
										<div class="form-group">
											{{ Form::cText('Código de barras', 'codebar-only', ['ref' => 'codeba']) }}
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
													{{ Form::cDate('Caducidad', 'caducidad', ['ref' => 'caducidad']) }}
												</div>
											</div>
											<div class="col-12 col-md-4 col-sm-6">
												<div class="form-group">
													{{ Form::cSelectWithDisabled('Almacén', 'almacen', []) }}
												</div>
											</div>
											<div class="col-12 col-md-4 col-sm-6">
												<div class="form-group">
													{{ Form::cSelectWithDisabled('Ubicación', 'ubicacion', []) }}
												</div>
											</div>
										</div>
									</div>
									<div class="col-12 col-lg-4">
										<div class="form-group full-height">
											{{ Form::cTextArea('Observaciones', 'observaciones', ['rows' => 2]) }}
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
									</div><!--/row forms-->
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
							<th>Rack</th>
							<th>Ubicación</th>
							<th>Posición</th>
							<th>Nivel</th>
							<th>Estatus</th>
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
								<span v-if="!ubicacion.editar" v-text="ubicacion.rack"></span>
								<input type="text" class="form-control" v-show="ubicacion.editar" v-bind:name="'relations[has][ubicaciones]['+index+'][rack]'" v-model="ubicacion.rack">
							</td>
							<td>
								<span v-if="!ubicacion.editar" v-text="ubicacion.ubicacion"></span>
								<input type="text" class="form-control" v-show="ubicacion.editar" v-bind:name="'relations[has][ubicaciones]['+index+'][ubicacion]'" v-model="ubicacion.ubicacion">
							</td>
							<td>
								<span v-if="!ubicacion.editar" v-text="ubicacion.posicion"></span>
								<input type="text" class="form-control" v-show="ubicacion.editar" v-bind:name="'relations[has][ubicaciones]['+index+'][posicion]'" v-model="ubicacion.posicion">
							</td>
							<td>
								<span v-if="!ubicacion.editar" v-text="ubicacion.nivel"></span>
								<input type="text" class="form-control" v-show="ubicacion.editar" v-bind:name="'relations[has][ubicaciones]['+index+'][nivel]'" v-model="ubicacion.nivel">
							</td>
							<td>
								<input type="hidden" v-bind:name="'relations[has][ubicaciones]['+index+'][activo]'" v-bind:value="ubicacion.activo">
								<div class="form-check">
									<label class="form-check-label">
										<input class="form-check-input" type="checkbox" v-if="ubicacion.editar" v-model="ubicacion.activo" value="1">
										<span v-bind:class="ubicacion.activo?'text-success':'text-danger'" v-text="ubicacion.activo?'Activo':'Inactivo'"></span>
									</label>
								</div>
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
	$('[data-target-url]').on('change', function() {
		let data = $(this).data(), values = data.targetValue.split(',');
		$.get(data.targetUrl.replace('#ID#', this.value), {with: data.targetWith} , function(request){
			let target = $('#'+data.targetEl).empty(), options = [];
			options.push('<option value="0" selected disabled>Seleccione una opcion ...</option>')
			if (request.success) {
				let i, estados = request.data[values[0]];
				for (i in estados) {
					options.push('<option value="'+estados[i][values[1]]+'">'+estados[i][values[2]]+'</option>')
				}
			}
			target.append(options.join())
		})
	});


	var app = new Vue({
		el: '#app',
		data: {
			buffer: {id_ubicacion: null, rack: '', ubicacion: '', posicion: '', nivel: '', activo: 0, eliminar: 0},
			ubicaciones: @json($ubicaciones ?? []),
		},
		methods: {
			append: function(e) {
				var data, isValid = true, valid;
				// Recorremos referencias
				data = Object.keys(this.$refs).reduce(function(acc, item){
					// Validamos campo
					var valid = $('#form-model').validate().element( '#' + item );
					if (!valid) isValid = valid;
					acc[item] = (this.$refs[item].type === 'checkbox') ? this.$refs[item].checked : this.$refs[item].value;
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
		}
	});
	jQuery(document).ready(function(){
		function addRules() {
			$('#rack').rules('add',{
				required: true,
				messages:{
					required: 'El campo rack es requerido.'
				}
			});
			$('#ubicacion').rules('add',{
				required: true,
				messages:{
					required: 'El campo ubicacion es requerido.'
				}
			});
			$('#posicion').rules('add',{
				required: true,
				messages:{
					required: 'El campo posicion es requerido.'
				}
			});
			$('#nivel').rules('add',{
				required: true,
				messages:{
					required: 'El campo nivel es requerido.'
				}
			});
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