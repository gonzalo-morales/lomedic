@extends('layouts.dashboard')

@section('title', 'Editar')

@section('header-top')
@endsection

@section('header-bottom')
		<script src="{{ asset('js/vehiculos.js') }}"></script>
@endsection

@section('content')
<div class="col s12 xl8 offset-xl2">
	<p class="left-align">
		<a href="{{ url()->previous() }}" class="waves-effect waves-light btn">Regresar</a> <br>
	</p>
	<div class="divider"></div>
</div>
<div class="col s12 xl8 offset-xl2">
	<h4>Editar {{ trans_choice('messages.'.$entity, 0) }}</h4>
</div>

<div class="col s12 xl8 offset-xl2">
	<div class="row">
		<form action="{{ companyRoute("update", ['company'=> $company, 'id' => $data->id_vehiculo]) }}" method="post" class="col s12">
			{{ csrf_field() }}
			{{ method_field('PUT') }}
			<div class="row">
				<div class="input-field col s2 ">
					<select name="marca" id="marca">
						<option disabled selected>Marca</option>
						@foreach ($marcas as $marca)
							<option value="{{$marca->id_marca}}" {{ ($data->fk_id_marca == $marca->id_marca) ? 'selected' :'' }}
							data-url="{{companyRoute('Servicios\VehiculosController@getModelos',['id' => $marca->id_marca])}}"
							>{{$marca->marca}}</option>
						@endforeach
					</select>
					@if ($errors->has('marca'))
						<span class="help-block">
							<strong>{{ $errors->first('marca') }}</strong>
						</span>
					@endif
				</div>
				<div class="input-field col s2 ">
					<select name="modelos" id="modelos">
						<option disabled selected>Modelo</option>
							@foreach ($modelos as $modelo)
								<option value="{{$modelo->id_modelo}}" {{ ($data->fk_id_modelo == $modelo->id_modelo) ? 'selected' :'' }}
									>{{$modelo->modelo}}</option>
							@endforeach
					</select>
					@if ($errors->has('modelos'))
						<span class="help-block">
							<strong>{{ $errors->first('modelos') }}</strong>
						</span>
					@endif
				</div>
			</div>
			<div class="row">
				<div class="input-field col s2">
					<input type="text" name="numeroSerie" id="numeroSerie" class="validate" value="{{$data->numero_serie}}">
					<label for="numeroSerie">Número de Serie</label>
					@if ($errors->has('numeroSerie'))
						<span class="help-block">
							<strong>{{ $errors->first('numeroSerie') }}</strong>
						</span>
					@endif
				</div>
				<div class="input-field col s1">
					<input type="text" name="modelo" id="modelo" class="validate" value="{{$data->modelo}}">
					<label for="modelo">Modelo</label>
					@if ($errors->has('modelo'))
						<span class="help-block">
							<strong>{{ $errors->first('modelo') }}</strong>
						</span>
					@endif
				</div>
				<div class="input-field col s1">
					<input type="text" name="placa" id="placa" class="validate" value="{{$data->placa}}">
					<label for="placa">Placa</label>
					@if ($errors->has('placa'))
						<span class="help-block">
							<strong>{{ $errors->first('placa') }}</strong>
						</span>
					@endif
				</div>
				<div class="input-field col s1">
					<input type="text" name="capacidad" id="capacidad" class="validate" value="{{$data->capacidad_tanque}}">
					<label for="capacidad">Capacidad </label>
					@if ($errors->has('capacidad'))
						<span class="help-block">
							<strong>{{ $errors->first('capacidad') }}</strong>
						</span>
					@endif
				</div>
				<div class="input-field col s1">
					<input type="text" name="rendimiento" id="rendimiento" class="validate" value="{{$data->rendimiento}}">
					<label for="rendimiento">Rendimiento</label>
					@if ($errors->has('rendimiento'))
						<span class="help-block">
							<strong>{{ $errors->first('rendimiento') }}</strong>
						</span>
					@endif
				</div>
				<div class="input-field col s2">
					<input type="text" name="lineasPorTanque" id="lineasPorTanque" class="validate" value="{{$data->lineas_tanque}}">
					<label for="lineasPorTanque">Líneas por Tanque</label>
					@if ($errors->has('lineasPorTanque'))
						<span class="help-block">
							<strong>{{ $errors->first('lineasPorTanque') }}</strong>
						</span>
					@endif
				</div>
				<div class="input-field col s2">
					<input type="text" name="litrosPorLinea" id="litrosPorLinea" class="validate" value="{{$data->litros_linea}}">
					<label for="litrosPorLinea">Litros por Línea</label>
					@if ($errors->has('litrosPorLinea'))
						<span class="help-block">
							<strong>{{ $errors->first('litrosPorLinea') }}</strong>
						</span>
					@endif
				</div>
				<div class="input-field col s2">
					<input type="text" name="numeroTarjeta" id="numeroTarjeta" class="validate" value="{{$data->no_tarjeta}}">
					<label for="numeroTarjeta">Número de Tarjeta</label>
					@if ($errors->has('numeroTarjeta'))
						<span class="help-block">
							<strong>{{ $errors->first('numeroTarjeta') }}</strong>
						</span>
					@endif
				</div>

			</div>
			<div class="row">
				<div class="input-field col s2 ">
					<select name="combustible">
						<option disabled selected>Combustible</option>
						@foreach ($combustibles as $combustible)
						<option value="{{$combustible->id_combustible}}" {{ ($data->fk_id_combustible == $combustible->id_combustible) ? 'selected' :'' }} >{{$combustible->combustible}}</option>
						@endforeach
					</select>
					@if ($errors->has('combustible'))
						<span class="help-block">
							<strong>{{ $errors->first('combustible') }}</strong>
						</span>
					@endif
				</div>
				<div class="input-field col s2">
					<input type="text" name="folioChecklist" id="folioChecklist" class="validate" value="{{$data->folio}}">
					<label for="folioChecklist">Folio Checklist</label>
					@if ($errors->has('folioChecklist'))
						<span class="help-block">
							<strong>{{ $errors->first('folioChecklist') }}</strong>
						</span>
					@endif
				</div>
				<div class="input-field col s2 ">
					<select name="sucursal">
						<option disabled selected>Sucursal</option>
						@foreach ($sucursales as $sucursal)
						<option value="{{$sucursal->id_sucursal}}" {{ ($data->fk_id_sucursal == $sucursal->id_sucursal) ? 'selected' :'' }}>{{$sucursal->nombre_sucursal}}</option>
						@endforeach
					</select>
					@if ($errors->has('sucursal'))
						<span class="help-block">
							<strong>{{ $errors->first('sucursal') }}</strong>
						</span>
					@endif
				</div>
				<div class="input-field col s2">
					<input type="text" name="iave" id="iave" class="validate" value="{{$data->iave}}">
					<label for="iave">IAVE</label>
					@if ($errors->has('iave'))
						<span class="help-block">
							<strong>{{ $errors->first('iave') }}</strong>
						</span>
					@endif
				</div>


				<div class="input-field col s4">
					<p>
						@if ($data->activo == 1)
						<input type="hidden" name="activo" value="0">
						<input type="checkbox" id="activo"  name="activo" checked="true"/>
						@else
						<input type="hidden" name="activo" value="0">
						<input type="checkbox" id="activo"  name="activo" />
						@endif
						<label for="activo">¿Activo?</label>
					</p>

					@if ($errors->has('activo'))
					<span class="help-block">
						<strong>{{ $errors->first('activo') }}</strong>
					</span>
					@endif
				</div>
			</div>
			<div class="row">
				<div class="col s12">
					<button class="waves-effect waves-light btn right">Guardar {{ trans_choice('messages.'.$entity, 0) }}</button>
				</div>
			</div>
		</form>
	</div>
</div>
@endsection
