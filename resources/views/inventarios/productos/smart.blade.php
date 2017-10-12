
@section('content-width', 's12')

@section('form-content')
{{ Form::setModel($data) }}
<div class="row">
	<div class="col-sm-6 col-md-4 col-lg-2">
		<div class="form-group">
			{{ Form::cSelect('Serie Sku', 'fk_id_serie_sku', $seriesku ?? [0=>'Serie Manual']) }}
		</div>
	</div>
	<div class="col-sm-6 col-md-4 col-lg-3">
		<div class="form-group">
			{{ Form::cText('Sku', 'sku', ['placeholder' => 'Ejemplo: SO-1922-09']) }}
		</div>
	</div>
	<div class="col-sm-12 col-md-4 col-lg-3">
		<div class="form-group">
			{{ Form::cText('Nombre Comercial', 'nombre_comercial') }}
		</div>
	</div>
	<div class="col-sm-12 col-md-6 col-lg-4">
		<div class="form-group">
			{{ Form::cText('Descripcion', 'descripcion') }}
		</div>
	</div>
	<div class="col-sm-12 col-md-6 col-lg-2 text-center">
		{{ Form::cText('Presentacion', 'presentacion') }}
	</div>
	<div class="col-sm-12 col-md-6 col-lg-2">
		<div class="form-group">
			{{ Form::cSelect('Unidad Medida', 'fk_id_unidad_medida', $unidadmedida ?? []) }}
		</div>
	</div>
	<div class="col-sm-12 col-md-6 col-lg-4">
		<div class="form-group">
			{{ Form::cSelect('Grupo', 'fk_id_grupo', $grupo ?? []) }}
		</div>
	</div>
	<div class="col-sm-12 col-md-6 col-lg-4">
		<div class="form-group">
			{{ Form::cSelect('Subgrupo', 'fk_id_subgrupo', $subgrupo ?? []) }}
		</div>
	</div>
	
	<div class="col-sm-6 col-md-3 col-lg-2">
		<div class="form-group">
			{{ Form::cCheckboxBtn('Maneja Lote', 'Si','maneja_lote', 1, 'No') }}
		</div>
	</div>
	<div class="col-sm-6 col-md-3 col-lg-2">
		<div class="form-group">
			{{ Form::cCheckboxBtn('Se Vende', 'Si','articulo_venta', 1, 'No') }}
		</div>
	</div>
	<div class="col-sm-6 col-md-3 col-lg-2">
		<div class="form-group">
			{{ Form::cCheckboxBtn('Se Compra', 'Si','articulo_compra', 1, 'No') }}
		</div>
	</div>
	<div class="col-sm-6 col-md-3 col-lg-2">
		<div class="form-group">
			{{ Form::cCheckboxBtn('Maneja Inventario', 'Si','articulo_inventario', 1, 'No') }}
		</div>
	</div>
</div>
<!--
<div class="row">
	<div class="col-md-12 col-sm-12 mb-3">
		<div class="card">
			<div class="card-header">
				<h4>Ubicaciones</h4>
				<p>Aquí puedes dar de alta las ubicaciones de acuerdo al orden de tu almacén</p>
				<fieldset>
					<div class="row">
						<div class="col-md-2 col-sm-2 col-3">
							<div class="form-group">
								{{ Form::cText('Rack', 'rack') }}
							</div>
						</div>
						<div class="col-md-2 col-sm-2 col-3">
							<div class="form-group">
								{{ Form::cText('Ubicación', 'ubicacion') }}
							</div>
						</div>
						<div class="col-md-2 col-sm-2 col-3">
							<div class="form-group">
								{{ Form::cText('Posición', 'posicion') }}
							</div>
						</div>
						<div class="col-md-2 col-sm-2 col-3">
							<div class="form-group">
								{{ Form::cText('Nivel', 'nivel') }}
							</div>
						</div>
						<div class="col-md-4 col-sm-4 col-12 text-center">
							{{ Form::cCheckboxYesOrNo('Estatus', 'estatus') }}
						</div>
					</div>
				</div>
				<div class="col-sm-12 text-center my-3">
					<div class="sep">
						<div class="sepBtn">
							<button style="width: 4em; height:4em; border-radius:50%;" class="btn btn-primary btn-large" data-position="bottom" data-delay="50" data-toggle="Agregar" title="Agregar" type="button"><i class="material-icons">add</i></button>
						</div>
					</div>
				</div>
				<div class="card-body">
					<table class="table table-hover table-responsive table-striped">
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
						<tbody>
							<tr>
								<th scope="row">1</th>
								<td>A1</td>
								<td>01</td>
								<td>0</td>
								<td>1</td>
								<td class="text-success">Activo</td>
								<td>
									<a href="#" data-toggle="Eliminar" data-placement="top" title="Eliminar"><i class="material-icons">delete</i></a>
								</td>
							</tr>
							<tr>
								<th scope="row">2</th>
								<td>A2</td>
								<td>02</td>
								<td>0</td>
								<td>2</td>
								<td class="text-danger">Inactivo</td>
								<td>
									<a href="#" data-toggle="Eliminar" data-placement="top" title="Eliminar"><i class="material-icons">delete</i></a>
								</td>
							</tr>
							<tr>
								<th scope="row">3</th>
								<td>A3</td>
								<td>03</td>
								<td>0</td>
								<td>3</td>
								<td class="text-danger">Inactivo</td>
								<td>
									<a href="#" data-toggle="Eliminar" data-placement="top" title="Eliminar"><i class="material-icons">delete</i></a>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</fieldset>
		</div>
	</div>
 -->

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