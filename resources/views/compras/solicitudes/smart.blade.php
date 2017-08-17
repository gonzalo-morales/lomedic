@section('header-top')
	<!--dataTable.css-->
	<link rel="stylesheet" href="{{ asset('vendor/vanilla-datatables/vanilla-dataTables.css') }}">
@endsection
@section('header-bottom')
	<script type="text/javascript" src="{{ asset('js/jquery.ui.autocomplete2.js') }}"></script>
	<script type="text/javascript" src="{{ asset('js/solicitudes_compras.js') }}"></script>
	<script src="{{ asset('vendor/vanilla-datatables/vanilla-dataTables.js') }}"></script>
	{{--{{ app('App\Http\Models\Administracion\Unidadesmedidas')}}--}}
@endsection
@section('content-width', 's12')

@section('form-content')
<div class="row">
	<div class="input-field col s4 m4">
		{!! Form::text('solicitante','Es para mí', ['id'=>'solicitante','autocomplete'=>'off','data-url'=>companyAction('RecursosHumanos\EmpleadosController@obtenerEmpleados'),'data-url2'=>companyAction('RecursosHumanos\EmpleadosController@obtenerEmpleado')]) !!}
		{{ Form::label('solicitante', '* Solicitante') }}
		{{ $errors->has('solicitante') ? HTML::tag('span', $errors->first('solicitante'), ['class'=>'help-block deep-orange-text']) : '' }}
		{{Form::hidden('fk_id_solicitante',null,['id'=>'fk_id_solicitante','data-url'=>companyAction('Administracion\SucursalesController@sucursalesEmpleado',['id'=>'?id'])])}}
	</div>
	<div class="input-field col s4 m4">
		{!! Form::select('fk_id_sucursal', [],null, ['id'=>'fk_id_sucursal','disabled'=>'true']) !!}
		{{ Form::label('fk_id_sucursal', '* Sucursal') }}
		{{ $errors->has('fk_id_sucursal') ? HTML::tag('span', $errors->first('fk_id_sucursal'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
	<div class="input-field col s2 m2">
		{{ Form::label('fecha_necesidad', '* ¿Para cuándo se necesita?') }}
		{!! Form::text('fecha_necesidad',null,['id'=>'fecha_necesidad','class'=>'datepicker','value'=>old('fecha_necesidad')]) !!}
	</div>
	<div class="input-field col s2 m2">
		{!! Form::select('fk_id_estatus_solicitud', \App\Http\Models\Compras\EstatusSolicitudes::all()->pluck('estatus','id_estatus'),null, ['id'=>'fk_id_sucursal']) !!}
		{{ Form::label('fk_id_estatus_solicitud', '* Estatus de la solicitud') }}
		{{ $errors->has('fk_id_estatus_solicitud') ? HTML::tag('span', $errors->first('fk_id_estatus_solicitud'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
</div>
<div class="divider"></div>
<div class="row">
	<div class="col s12 m12">
		<h5>Detalle de la solicitud</h5>
		<div class="card">
			<div class="card-image teal lighten-5">
				<div class="row" id="prueba">
					<div class="input-field col s4">
						{!!Form::text('fk_id_sku',null,['id'=>'fk_id_sku','autocomplete'=>'off','class'=>'validate',
						'data-url'=>companyAction('Inventarios\SkusController@obtenerSkus'),'aria-required'=>'true'])!!}
						{{Form::label('fk_id_sku','SKU')}}
					</div>
					<div class="input-field col s4">
						{!! Form::select('fk_id_codigo_barras',[],null,['id'=>'fk_id_codigo_barras','disabled',
						'data-url'=>companyAction('Inventarios\CodigosBarrasController@obtenerCodigosBarras',['id'=>'?id'])]) !!}
						{{Form::label('fk_id_codigo_barras','Código de barras')}}
					</div>
					<div class="input-field col s4">
						{!!Form::text('fk_id_proveedor',null,['id'=>'fk_id_proveedor','autocomplete'=>'off','class'=>'validate'])!!}
						{{Form::label('fk_id_proveedor','Proveedor')}}
					</div>
					<div class="input-field col s6">
						{!! Form::number('cantidad','1',['id'=>'cantidad','min'=>'1','class'=>'validate']) !!}
						{{Form::label('cantidad','Cantidad')}}
					</div>
					<div class="input-field col s6">
						{!! Form::select('fk_id_unidad_medida',
						\App\Http\Models\Administracion\Unidadesmedidas::where('activo','1')->pluck('nombre','id_unidad_medida')
						,null,['id'=>'fk_id_unidad_medida']) !!}
						{{Form::label('fk_id_unidad_medida','Unidad de medida')}}
					</div>
					<div class="input-field col s4">
						{!! Form::select('fk_id_impuesto',
						\App\Http\Models\Finanzas\Impuestos::where('activo','1')->pluck('impuesto','id_impuesto')
						,null,['id'=>'fk_id_impuesto',
						'data-url'=>companyAction('Finanzas\ImpuestosController@obtenerPorcentaje',['id'=>'?id'])]) !!}
						{{Form::label('fk_id_impuesto','Tipo de impuesto')}}
						{{Form::hidden('impuesto',null,['id'=>'impuesto'])}}
					</div>
					<div class="input-field col s4">
						{!! Form::text('precio_unitario',old('precio_unitario'),['id'=>'precio_unitario']) !!}
						{{Form::label('precio_unitario','Precio unitario')}}
					</div>
					<div class="input-field col s4">
						{!! Form::text('total',old('total'),['id'=>'total','readonly']) !!}
						{{Form::label('total','Total')}}
					</div>
					<div class="input-field col s6">
						{!!Form::text('fk_id_proyecto',null,['id'=>'fk_id_proyecto','autocomplete'=>'off','class'=>'validate',
						'data-url'=> companyAction('Proyectos\ProyectosController@obtenerProyectos')])!!}
						{{Form::label('fk_id_proyecto','Proyecto')}}
					</div>
					<div class="input-field col s6">
						{{ Form::label('fecha_necesario', '* ¿Para cuándo se necesita?') }}
						{!! Form::text('fecha_necesario',null,['id'=>'fecha_necesario','class'=>'datepicker','value'=>old('fecha_necesario')]) !!}
					</div>
					<button class="btn-floating btn-large orange halfway-fab waves-effect waves-light tooltipped"
							data-position="bottom" data-delay="50" data-tooltip="Agregar" type="button" onclick="agregarProducto()"><i
								class="material-icons">add</i></button>
				</div>
			</div>
			<div class="divider"></div>
			<div class="card-content">
				<table id="productos" class="responsive-table highlight" data-url="{{companyAction('Compras\SolicitudesController@store')}}">
					<thead>
						<tr>
							<th id="idsku">fk_id_sku</th>
							<th>SKU</th>
							<th id="idcodigobarras">fk_id_codigo_barras</th>
							<th>Código de barras</th>
							<th id="idproveedor">fk_id_proveedor</th>
							<th>Proveedor</th>
							<th>Cantidad</th>
							<th id="idunidadmedida" >fk_id_unidad_medida</th>
							<th>Unidad de medida</th>
							<th id="idimpuesto" >fk_id_impuesto</th>
							<th>Tipo de impuesto</th>
							<th>Precio unitario</th>
							<th>Total</th>
							<th id="idproyecto" >fk_id_proyecto</th>
							<th>Proyecto</th>
							<th>Fecha necesidad</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
					{{--Columna dummy--}}
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
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