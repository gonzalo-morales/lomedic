@section('header-top')
	<link rel="stylesheet" href="{{ asset('vendor/vanilla-datatables/vanilla-dataTables.css') }}">
	<link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
	<link rel="stylesheet" href="{{ asset('css/pickadate/default.css') }}">
	<link rel="stylesheet" href="{{ asset('css/pickadate/default.date.css') }}">
@endsection
@section('header-bottom')
	@parent
	{{--<script type="text/javascript" src="{{ asset('js/jquery.ui.autocomplete2.js') }}"></script>--}}
	<script type="text/javascript" src="{{ asset('js/select2.full.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('js/pickadate/picker.js') }}"></script>
	<script type="text/javascript" src="{{ asset('js/pickadate/picker.date.js') }}"></script>


	{{--<script type="text/javascript" src="{{ asset('js/pickadate/translations/es_ES.js') }}"></script>--}}
	<script src="{{ asset('vendor/vanilla-datatables/vanilla-dataTables.js') }}"></script>
	@if(!Route::currentRouteNamed(currentRouteName('index')))
		<script type="text/javascript" src="{{ asset('js/solicitudes_compras.js') }}"></script>
	@endif
@endsection

@section('form-actions')
	<div class="row">
		<div class="col-md-12 col-xs-12">
			{{ Form::button('Guardar', ['type' =>'submit', 'class'=>'btn btn-primary']) }}
			@if (!Route::currentRouteNamed(currentRouteName('index')) && !Route::currentRouteNamed(currentRouteName('create')))
				{!! HTML::decode(link_to(companyAction('impress',['id'=>$data->id_solicitud]), '<i class="material-icons">print</i> Imprimir', ['class'=>'btn nav-link imprimir'])) !!}
				@if($data->fk_id_estatus_solicitud == 1 && !Route::currentRouteNamed(currentRouteName('edit')))
					{!! HTML::decode(link_to(companyRoute('edit'), 'Editar', ['class'=>'btn nav-link'])) !!}
				@endif
			@endif
			{!! HTML::decode(link_to(companyRoute('index'), 'Cerrar', ['class'=>'btn nav-link '])) !!}
		</div>
	</div>
@endsection

@section('content-width', 's12')

@section('form-content')
{{ Form::setModel($data) }}
<div class="container-fluid">
	<div class="row">
		<div class="form-group col-md-3 col-xs-12">
	{{--		{!! Form::text(array_has($data,'fk_id_solicitante')?'solicitante_formated':'solicitante',null, ['id'=>'solicitante','autocomplete'=>'off','data-url'=>companyAction('RecursosHumanos\EmpleadosController@obtenerEmpleados'),'data-url2'=>companyAction('RecursosHumanos\EmpleadosController@obtenerEmpleado')]) !!}--}}
			{{ Form::label('fk_id_solicitante', '* Solicitante') }}
			{!! Form::select('fk_id_solicitante',isset($empleados)?$empleados:[],null,['id'=>'fk_id_solicitante','data-url'=>companyAction('RecursosHumanos\EmpleadosController@obtenerEmpleado'),'class'=>'form-control','style'=>'width:100%']) !!}
			{{ $errors->has('fk_id_solicitante') ? HTML::tag('span', $errors->first('fk_id_solicitante'), ['class'=>'help-block deep-orange-text']) : '' }}
			{{Form::hidden('id_solicitante',null,['id'=>'id_solicitante','data-url'=>companyAction('Administracion\SucursalesController@sucursalesEmpleado',['id'=>'?id'])])}}
		</div>
		<div class="form-group input-field col-md-3 col-xs-12">
			{{--Se utilizan estas comprobaciones debido a que este campo se carga dinámicamente con base en el solicitante seleccionado y no se muestra el que está por defecto sin esto--}}
			@if(Route::currentRouteNamed(currentRouteName('edit')))
				{{ Form::label('fk_id_sucursal', '* Sucursal') }}
				{!! Form::select('fk_id_sucursal', isset($sucursalesempleado)?$sucursalesempleado:[],null, ['id'=>'fk_id_sucursal','class'=>'form-control','style'=>'width:100%']) !!}
				{!! Form::hidden('sucursal_defecto',$data->fk_id_sucursal,['id'=>'sucursal_defecto']) !!}
			@elseif(Route::currentRouteNamed(currentRouteName('show')))
				{{ Form::label('fk_id_sucursal', '* Sucursal') }}
				{!! Form::text('sucursal',$data->sucursales->where('id_sucursal',$data->fk_id_sucursal)->first()->nombre_sucursal,['class'=>'form-control','style'=>'width:100%']) !!}
			@elseif(Route::currentRouteNamed(currentRouteName('create')))
				{{ Form::label('fk_id_sucursal', '* Sucursal') }}
				{!! Form::select('fk_id_sucursal', isset($sucursalesempleado)?$sucursalesempleado:[],null, ['id'=>'fk_id_sucursal','class'=>'form-control','style'=>'width:100%']) !!}
			@endif
			{{ $errors->has('fk_id_sucursal') ? HTML::tag('span', $errors->first('fk_id_sucursal'), ['class'=>'help-block deep-orange-text']) : '' }}
		</div>
		<div class="form-group input-field col-md-3 col-xs-12">
			{{ Form::label('fecha_necesidad', '* ¿Para cuándo se necesita?') }}
			{!! Form::text('fecha_necesidad',null,['id'=>'fecha_necesidad','class'=>'datepicker form-control','value'=>old('fecha_necesidad')]) !!}
		</div>
		<div class="form-group input-field col-md-3 col-xs-12">
			{{--{!! Form::select('fk_id_estatus_solicitud', \App\Http\Models\Compras\EstatusSolicitudes::all()->pluck('estatus','id_estatus'),null, ['id'=>'fk_id_sucursal']) !!}--}}
			{{ Form::label('estatus_solicitud', '* Estatus de la solicitud') }}
			@if(Route::currentRouteNamed(currentRouteName('edit')) || Route::currentRouteNamed(currentRouteName('show')))
				{!! Form::text('estatus_solicitud',$data->estatus->estatus,['disabled','class'=>'form-control']) !!}
			@elseif(Route::currentRouteNamed(currentRouteName('create')))
				{!! Form::text('estatus_solicitud','Abierto',['disabled','class'=>'form-control']) !!}
			@endif
		</div>
		{{--Si la solicitud está cancelada--}}
			@if(isset($data->fk_id_estatus_solicitud) && $data->fk_id_estatus_solicitud ==3)
				<div class="form-group input-field col-md-3 col-xs-12">
					{!! Form::text('fecha_cancelacion',$data->fecha_cancelacion,['disabled','class'=>'form-control']) !!}
					{{ Form::label('fecha_cancelacion','Fecha de cancelación') }}
				</div>
				<div class="form-group input-field col-md-3 col-xs-12">
					{!! Form::text('motivo_cancelacion',$data->motivo_cancelacion,['disabled','class'=>'form-control']) !!}
					{{ Form::label('motivo_cancelacion','Motivo de la cancelación') }}
				</div>
			@endif
	</div>
<div class="divider"></div>
	<div class="row">
		<div class="col s12 m12">
			<h5>Detalle de la solicitud</h5>
			<div class="card">
				<div class="card-image teal lighten-5">
					<div class="row">
						<div class="form-group input-field col-md-4 col-xs-12">
							{{Form::label('fk_id_sku','SKU')}}
							{!!Form::select('fk_id_sku',isset($skus)?$skus:[],null,['id'=>'fk_id_sku','class'=>'form-control','style'=>'width:100%'])!!}
						</div>
						<div class="form-group input-field col-md-4 col-xs-12">
							{{Form::label('fk_id_codigo_barras','Código de barras')}}
							{!! Form::select('fk_id_codigo_barras',[],null,['id'=>'fk_id_codigo_barras','disabled',
							'data-url'=>companyAction('Inventarios\CodigosBarrasController@obtenerCodigosBarras',['id'=>'?id']),
							'class'=>'form-control','style'=>'width:100%']) !!}
						</div>
						<div class="form-group input-field col-md-4 col-xs-12">
							{{Form::label('fk_id_proveedor','Proveedor')}}
							{!!Form::select('fk_id_proveedor',[],null,['id'=>'fk_id_proveedor','autocomplete'=>'off','class'=>'validate form-control','style'=>'width:100%'])!!}
						</div>
						<div class="form-group input-field col-md-4 col-xs-12">
							{{ Form::label('fecha_necesario', '* ¿Para cuándo se necesita?') }}
							{!! Form::text('fecha_necesario',null,['id'=>'fecha_necesario','class'=>'datepicker form-control','value'=>old('fecha_necesario')]) !!}
						</div>
						<div class="form-group input-field col-md-4 col-xs-12">
							{{Form::label('fk_id_proyecto','Proyecto')}}
							{!!Form::select('fk_id_proyecto',isset($proyectos)?$proyectos:[],null,['id'=>'fk_id_proyecto','autocomplete'=>'off','class'=>'validate form-control','style'=>'width:100%',
])!!}
						</div>
						<div class="form-group input-field col-md-4 col-xs-12">
							{{Form::label('cantidad','Cantidad')}}
							{!! Form::text('cantidad','1',['id'=>'cantidad','min'=>'1','class'=>'validate form-control cantidad','autocomplete'=>'off']) !!}
						</div>
						<div class="form-group input-field col-md-4 col-xs-12">
							{{Form::label('fk_id_unidad_medida','Unidad de medida')}}
							{!! Form::select('fk_id_unidad_medida',
							isset($unidadesmedidas) ? $unidadesmedidas : [],
							null,['id'=>'fk_id_unidad_medida','class'=>'form-control','style'=>'width:100%']) !!}
						</div>
						<div class="form-group input-field col-md-4 col-xs-12">
							{{Form::label('fk_id_impuesto','Tipo de impuesto')}}
							{{--{{dd($impuestos)}}--}}
							{!! Form::select('fk_id_impuesto',[]
                            	,null,['id'=>'fk_id_impuesto',
                            	'data-url'=>companyAction('Finanzas\ImpuestosController@obtenerImpuestos'),
                            	'class'=>'form-control','style'=>'width:100%']) !!}
							{{Form::hidden('impuesto',null,['id'=>'impuesto'])}}
						</div>
						<div class="form-group input-field col-md-4 col-xs-12">
							{{Form::label('precio_unitario','Precio unitario',['class'=>'validate'])}}
							{!! Form::text('precio_unitario',old('precio_unitario'),['id'=>'precio_unitario','class'=>'validate form-control precio','autocomplete'=>'off']) !!}
						</div>
						<button class="btn-floating btn-large orange halfway-fab waves-effect waves-light tooltipped"
								data-position="bottom" data-delay="50" data-tooltip="Agregar" type="button" onclick="agregarProducto()"><i
									class="material-icons">add</i></button>
					</div>
				</div>
				<div class="divider"></div>
				<div class="table-responsive">
					<table id="productos" class="responsive-table highlight" data-url="{{companyAction('Compras\SolicitudesController@store')}}"
					data-delete="{{companyAction('Compras\DetalleSolicitudesController@destroyMultiple')}}"
					data-impuestos="{{companyAction('Finanzas\ImpuestosController@obtenerImpuestos')}}"
							data-porcentaje="{{companyAction('Finanzas\ImpuestosController@obtenerPorcentaje',['id'=>'?id'])}}">
						<thead>
							<tr>
								<th id="idsku">SKU</th>
								<th id="idcodigobarras">Código de Barras</th>
								<th id="idproveedor">Proveedor</th>
								<th>Fecha necesidad</th>
								<th id="idproyecto" >Proyecto</th>
								<th>Cantidad</th>
								<th id="idunidadmedida" >Unidad de medida</th>
								<th id="idimpuesto" >Tipo de impuesto</th>
								<th>Precio unitario</th>
								<th>Total</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
						@if( isset( $detalles ) )
							@foreach( $detalles as $detalle)
								<tr>
									<td>
										{!! Form::hidden('detalles['.$detalle->id_solicitud_detalle.'][id_solicitud_detalle]',$detalle->id_solicitud_detalle) !!}
										{!! Form::hidden('detalles['.$detalle->id_solicitud_detalle.'][fk_id_sku]',$detalle->fk_id_sku) !!}
										{{$detalle->sku->sku}}
									</td>
									<td>
										{!! Form::hidden('detalles['.$detalle->id_solicitud_detalle.'][fk_id_codigo_barras]',$detalle->fk_id_codigo_barras) !!}
										{{$detalle->codigo_barras->descripcion}}
									</td>
									<td>
										{!! Form::hidden('detalles['.$detalle->id_solicitud_detalle.'][fk_id_proveedor]',$detalle->fk_id_proveedor) !!}
									</td>
									<td>
										{!! Form::hidden('detalles['.$detalle->id_solicitud_detalle.'][fecha_necesario]',$detalle->fecha_necesario) !!}
										{{$detalle->fecha_necesario}}</td>
									<td>
										@if(!Route::currentRouteNamed(currentRouteName('edit')))
											{!! Form::hidden('detalles['.$detalle->id_solicitud_detalle.'][fk_id_proyecto]',$detalle->fk_id_proyecto) !!}
											{{$detalle->proyecto->proyecto}}
										@else
											{!! Form::select('detalles['.$detalle->id_solicitud_detalle.'][fk_id_proyecto]',
													isset($proyectos) ? $proyectos : null,
													$detalle->id_proyecto,['id'=>'detalles['.$detalle->id_solicitud_detalle.'][fk_id_proyecto]',
													'class'=>'detalle_row_select'])
											!!}
										@endif
									</td>
									<td>
										@if (!Route::currentRouteNamed(currentRouteName('edit')))
											{!! Form::hidden('detalles['.$detalle->id_solicitud_detalle.'][cantidad]',$detalle->cantidad) !!}
											{{$detalle->cantidad}}
										@else
											{!! Form::text('detalles['.$detalle->id_solicitud_detalle.'][cantidad]',$detalle->cantidad,
											['class'=>'',
											'id'=>'cantidad'.$detalle->id_solicitud_detalle,
											'onkeyup' =>'validateCantidad(this)',
											'onkeypress'=>'total_producto_row('.$detalle->id_solicitud_detalle.',"old")']) !!}
										@endif
									</td>
									<td>
										{!! Form::hidden('detalles['.$detalle->id_solicitud_detalle.'][fk_unidad_medida]',$detalle->fk_unidad_medida) !!}
										{{$detalle->unidad_medida->nombre}}
									</td>
									<td>
										@if (!Route::currentRouteNamed(currentRouteName('edit')))
											{!! Form::hidden('detalles['.$detalle->id_solicitud_detalle.'][fk_id_impuesto]',$detalle->fk_id_impuesto) !!}
											{{$detalle->impuesto->impuesto}}
										@else
											{!! Form::select('detalles['.$detalle->id_solicitud_detalle.'][fk_id_impuesto]',$impuestos,
													$detalle->fk_id_impuesto,['id'=>'fk_id_impuesto'.$detalle->id_solicitud_detalle,
													'onchange'=>'total_producto_row('.$detalle->id_solicitud_detalle.',"old")'])
											!!}
										@endif
									</td>
									<td>
										@if(!Route::currentRouteNamed(currentRouteName('edit')))
											{!! Form::hidden('detalles['.$detalle->id_solicitud_detalle.'][precio_unitario]',$detalle->precio_unitario) !!}
											{{number_format($detalle->precio_unitario,2,'.','')}}
										@else
											{!! Form::text('detalles['.$detalle->id_solicitud_detalle.'][precio_unitario]',number_format($detalle->precio_unitario,2,'.','')
											,['class'=>'','onkeyup' =>'validatePrecioUnitario(this)','onkeypress'=>'total_producto_row('.$detalle->id_solicitud_detalle.',"old")',
											'id'=>'precio_unitario'.$detalle->id_solicitud_detalle]) !!}
										@endif
									</td>
									<td>
										@if (!Route::currentRouteNamed(currentRouteName('edit')))
											{!! Form::hidden('detalles['.$detalle->id_solicitud_detalle.'][total]',$detalle->total) !!}
											{{number_format($detalle->total,2,'.','')}}
										@else
											{!! Form::text('detalles['.$detalle->id_solicitud_detalle.'][total]',number_format($detalle->total,2,'.','')
											,['class'=>'','id'=>'total'.$detalle->id_solicitud_detalle,'readonly'])!!}
										@endif
									<td>
										{{--Si se va a editar, agrega el botón para "eliminar" la fila--}}
										@if(Route::currentRouteNamed(currentRouteName('edit')))
											<a href="#" class="btn-flat teal lighten-5 halfway-fab waves-effect waves-light"
											   type="button" data-item-id="{{$detalle->id_solicitud_detalle}}"
											   id="{{$detalle->id_solicitud_detalle}}" data-delay="50"
											   onclick="borrarFila_edit(this)" data-delete-type="single">
											<i class="material-icons">delete</i></a>
										@endif
									</td>
								</tr>
							@endforeach
						@endif
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection

{{-- DONT DELETE --}}
@if (Route::currentRouteNamed(currentRouteName('index')))
	@include(currentRouteName('index'))
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