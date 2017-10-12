
@section('content-width', 's12')

@section('form-content')
{{ Form::setModel($data) }}
<div class="row">
	<div class="col-md-4 col-sm-4">
		<div class="form-group">
			{{ Form::cSelectWithDisabled('Sucursal', 'fk_id_sucursal', $sucursales ?? []) }}
		</div>
	</div>
	<div class="col-md-4 col-sm-4">
		<div class="form-group">
			{{ Form::cText('Almacén', 'almacen', ['placeholder' => 'Ejemplo: Curación']) }}
		</div>
	</div>
	<div class="col-md-4 col-sm-4">
		<div class="form-group">
			{{ Form::cSelectWithDisabled('Tipo almacén', 'fk_id_tipo_almacen', $tipos ?? []) }}
		</div>
	</div>
	<div class="col-md-12 col-sm-12 text-center">
		{{ Form::cCheckbox('Almacén con Inventario (Podrás darlo de alta después)', 'inventario') }}
	</div>
</div>
<div class="row">
	<div class="col-md-12 col-sm-12 mb-3">
		<div id="app" class="card">
			@if (!Route::currentRouteNamed(currentRouteName('show')))
			<div class="card-header">
				<h4>Ubicaciones</h4>
				<p>Aquí puedes dar de alta las ubicaciones de acuerdo al orden de tu almacén</p>
				<div class="row">
					<div class="col-md-2 col-sm-2 col-3">
						<div class="form-group">
							{{ Form::cText('Rack', 'rack', ['v-model' => 'temp.rack']) }}
						</div>
					</div>
					<div class="col-md-2 col-sm-2 col-3">
						<div class="form-group">
							{{ Form::cText('Ubicación', 'ubicacion', ['v-model' => 'temp.ubicacion']) }}
						</div>
					</div>
					<div class="col-md-2 col-sm-2 col-3">
						<div class="form-group">
							{{ Form::cText('Posición', 'posicion', ['v-model' => 'temp.posicion']) }}
						</div>
					</div>
					<div class="col-md-2 col-sm-2 col-3">
						<div class="form-group">
							{{ Form::cText('Nivel', 'nivel', ['v-model' => 'temp.nivel']) }}
						</div>
					</div>
					<div class="col-md-4 col-sm-4 col-12 text-center">
						{{ Form::cCheckboxYesOrNo('Estatus', 'activo', ['v-model' => 'temp.activo']) }}
					</div>
				</div>
				<div class="row" hidden>
					<div v-text="temp.rack" class="col-md-2 col-sm-2 col-3"></div>
					<div v-text="temp.ubicacion" class="col-md-2 col-sm-2 col-3"></div>
					<div v-text="temp.posicion" class="col-md-2 col-sm-2 col-3"></div>
					<div v-text="temp.nivel" class="col-md-2 col-sm-2 col-3"></div>
					<div v-text="temp.activo" class="col-md-4 col-sm-4 col-12"></div>
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
var app = new Vue({
	el: '#app',
	data: {
		settings: {
			softDelete: {{ Route::currentRouteNamed(currentRouteName('create')) ? 'false' : 'true' }}
		},
		temp: {id_ubicacion: null, rack: '', ubicacion: '', posicion: '', nivel: '', activo: 0, eliminar: 0},
		ubicaciones: @json($ubicaciones ?? []),
	},
	methods: {
		append: function(e) {
			this.ubicaciones.push(JSON.parse(JSON.stringify(this.temp)))
		},
		editOrDone: function(index) {
			this.ubicaciones[index].editar = !this.ubicaciones[index].editar;
		},
		removeOrUndo: function(index) {
			if (!this.settings.softDelete) {
				this.ubicaciones.splice(index ,1); return;
			}
			this.ubicaciones[index].eliminar = !this.ubicaciones[index].eliminar;
		}
	},
	computed: {
		computedUbicaciones: function() {
			return this.ubicaciones.reduce(function(acc, item){
				if (!item.editar) {
					Vue.set(item, 'editar', false)
				}
				return acc.concat(item)
			}, []);
		}
	}
});

jQuery(document).ready(function(){

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