@section('header-top')
	{{ HTML::style(asset('vendor/vanilla-dataTables/vanilla-dataTables.css')) }}
@endsection

@section('content-width', 'w-100')

@section('form-content')
{{ Form::setModel($data) }}
	<div class="row">
		<div class="form-group col-md-6 col-xs-12">
			{{Form::cText('Proyecto','proyecto',['id'=>'proyecto'])}}
		</div>
		<div class="form-group col-md-6 col-xs-12">
			{{Form::cSelectWithDisabled('Cliente','fk_id_cliente',isset($clientes)?$clientes:[],['id'=>'fk_id_cliente'])}}
		</div>
		<div class="form-group col-md-4 col-xs-12">
			{{Form::label('fecha_contrato','Fecha de creación del contrato')}}
			{{Form::text('fecha_contrato',null,['id'=>'fecha_contrato',"class"=>'form-control datepicker'])}}
		</div>
		<div class="form-group col-md-4 col-xs-12">
			{{Form::label('fecha_inicio_contrato','Fecha inicio del contrato')}}
			{{Form::text('fecha_inicio_contrato',null,['id'=>'fecha_inicio_contrato',"class"=>'form-control datepicker'])}}
		</div>
		<div class="form-group col-md-4 col-xs-12">
			{{Form::label('fecha_fin_contrato','Fecha fin del contrato')}}
			{{Form::text('fecha_fin_contrato',null,['id'=>'fecha_fin_contrato',"class"=>'form-control datepicker'])}}
		</div>
		<div class="form-group col-md-3 col-xs-12">
			{{Form::cText('Número de contrato','numero_contrato')}}
		</div>
		<div class="form-group col-md-3 col-xs-12">
			{{Form::cText('Monto adjudicado','monto_adjudicado')}}
		</div>
		<div class="form-group col-md-3 col-xs-12">
			{{Form::cSelectWithDisabled('Clasificación','fk_id_clasificacion_proyecto',isset($clasificaciones)?$clasificaciones:[])}}
		</div>
		<div class="form-group col-md-3 col-xs-12">
			{{Form::cText('Plazo','plazo')}}
		</div>
		<div class="form-group col-md-6 col-xs-12">
			{{Form::cText('Representante legal','representante_legal')}}
		</div>
		<div class="form-group col-md-5 col-xs-12">
			{{Form::cText('Número de fianza','numero_fianza')}}
		</div>
		<div class="form-group col-md-1 col-xs-12">
			<div data-toggle="buttons">
				<label class="btn btn-secondary form-check-label {{ !empty($data->activo) || old('activo') ? 'active':''}}">
					{{Form::checkbox('activo',true,old('activo'),['id'=>'activo',Route::currentRouteNamed(currentRouteName('show'))?'disabled':''])}}
					Activo
				</label>
			</div>
		</div>
		<div class="form-goup col-md-6 text-center">
			{!! Form::button('Descargar Layout',['id'=>'layout','class'=>'btn btn-primary','name'=>'layout'])!!}
		</div>
		<div class="form-goup col-md-6">
			<label class="custom-file">
				<input type="file" id="file">
				<span class="custom-file-control"></span>
			</label>
		</div>
	</div>

<div class="row">
	<div class="col-sm-12">
		<h3>Detalle </h3>
		<div class="card">
			@if(!Route::currentRouteNamed(currentRouteName('show')))
				<div class="card-header">
					<fieldset name="detalle-form" id="detalle-form">
						<div class="row">
							<div class="form-group input-field col-md-3 col-sm-6">
								{{Form::label('fk_id_sku','* SKU')}}
								{!!Form::select('fk_id_sku',[],null,['id'=>'fk_id_sku','class'=>'form-control','style'=>'width:100%','data-url'=>companyAction('Inventarios\ProductosController@obtenerSkus')])!!}
							</div>
							<div class="form-group input-field col-md-3 col-sm-6">
								{{Form::label('fk_id_upc','UPC')}}
								<div class="input-group">
								<span class="input-group-addon">
									<input type="checkbox" id="activo_upc">
								</span>
									{!! Form::select('fk_id_upc',[],null,['id'=>'fk_id_upc','disabled',
                                    'data-url'=>companyAction('Inventarios\ProductosController@obtenerUpcs',['id'=>'?id']),
                                    'class'=>'form-control','style'=>'width:100%']) !!}
								</div>
							</div>
							<div class="form-group input-field col-md-3 col-sm-6">
								{{Form::label('fk_id_cliente','Cliente')}}
								{!!Form::select('fk_id_cliente',isset($clientes)?$clientes:[],null,['id'=>'fk_id_cliente','autocomplete'=>'off','class'=>'form-control','style'=>'width:100%'])!!}
							</div>
							<div class="form-group input-field col-md-3 col-sm-6">
								{{Form::label('fk_id_proyecto','Proyecto')}}
								{!!Form::select('fk_id_proyecto',isset($proyectos)?$proyectos:[],null,['id'=>'fk_id_proyecto','autocomplete'=>'off','class'=>'validate form-control','style'=>'width:100%',])!!}
							</div>
							<div class="form-group input-field col-md-2 col-sm-4">
								{{ Form::label('fecha_necesario', '* ¿Para cuándo se necesita?') }}
								{!! Form::text('fecha_necesario',null,['id'=>'fecha_necesario','class'=>'datepicker form-control','value'=>old('fecha_necesario'),'placeholder'=>'Selecciona una fecha']) !!}
							</div>
							<div class="form-group input-field col-md-2 col-sm-4">
								{{Form::label('cantidad','Cantidad')}}
								{!! Form::text('cantidad','1',['id'=>'cantidad','min'=>'1','class'=>'validate form-control cantidad','autocomplete'=>'off']) !!}
							</div>
							<div class="form-group input-field col-md-2 col-sm-6">
								{{Form::label('fk_id_impuesto','Tipo de impuesto')}}
								{!! Form::select('fk_id_impuesto',[]
                                    ,null,['id'=>'fk_id_impuesto',
                                    'data-url'=>companyAction('Administracion\ImpuestosController@obtenerImpuestos'),
                                    'class'=>'form-control','style'=>'width:100%']) !!}
								{{Form::hidden('impuesto',null,['id'=>'impuesto'])}}
							</div>
							<div class="form-group input-field col-md-2 col-sm-6">
								{{Form::label('precio_unitario','Precio unitario',['class'=>'validate'])}}
								{!! Form::text('precio_unitario',old('precio_unitario'),['id'=>'precio_unitario','placeholder'=>'0.00','class'=>'validate form-control precio_unitario','autocomplete'=>'off']) !!}
							</div>
							<div class="col-sm-12 text-center">
								<div class="sep">
									<div class="sepBtn">
										<button style="width: 4em; height:4em; border-radius:50%;" class="btn btn-primary btn-large tooltipped "
												data-position="bottom" data-delay="50" data-tooltip="Agregar" type="button" id="agregar"><i
													class="material-icons">add</i></button>
									</div>
								</div>
							</div>
						</div>
					</fieldset>
				</div>
			@endif
			<div class="card-body">
				<table id="productos" class="table-responsive highlight" data-url="{{companyAction('Compras\ordenesController@store')}}"
					   @if(isset($data->id_orden))
					   data-delete="{{companyAction('Compras\OrdenesController@destroyDetail')}}"
					   @endif
					   data-impuestos="{{companyAction('Administracion\ImpuestosController@obtenerImpuestos')}}"
					   data-porcentaje="{{companyAction('Administracion\ImpuestosController@obtenerPorcentaje',['id'=>'?id'])}}">
					<thead>
					<tr>
						<th>Clave producto cliente</th>
						<th>Subclave</th>
						<th>Descripción</th>
						<th>Presentación</th>
						<th>Cantidad presentación</th>
						<th>Unidad de medida</th>
						<th>Clave producto servicio SAT</th>
						<th>Clave unidad SAT</th>
						<th>Marca</th>
						<th>Fabricante</th>
						<th>Precio</th>
						<th>Precio referencia</th>
						<th>Descuento</th>
						<th>Descuento porcentaje</th>
						<th>Impuesto</th>
						<th>Dispensación</th>
						<th>Dispensación porcentaje</th>
						<th>Tipo de producto</th>
						<th>Propietario</th>
						<th>Tipo de almacen</th>
						<th>Pertenece a cuadro</th>
						<th>Mínimo</th>
						<th>Máximo</th>
						<th></th>
					</tr>
					</thead>
					<tbody>
					@if( isset( $data->detalleOrdenes ) )
						@foreach( $data->detalleOrdenes as $detalle)
							<tr>
								<td>
									{{isset($detalle->fk_id_solicitud)?$detalle->fk_id_solicitud:'N/A'}}
								</td>
								<td>
									{!! Form::hidden('detalles['.$detalle->id_orden_detalle.'][id_orden_detalle]',$detalle->id_orden_detalle) !!}
									{!! Form::hidden('detalles['.$detalle->id_orden_detalle.'][fk_id_sku]',$detalle->fk_id_sku) !!}
									{{$detalle->sku->sku}}
								</td>
								<td>
									{!! Form::hidden('detalles['.$detalle->id_orden_detalle.'][fk_id_upc]',$detalle->fk_id_upc) !!}
									{{isset($detalle->fk_id_upc)?$detalle->upc->upc:'UPC no seleccionado'}}
								</td>
								<td>
									{{$detalle->sku->nombre_comercial}}
								</td>
								<td>
									{{$detalle->sku->descripcion}}
								</td>
								<td>
									{!! Form::hidden('detalles['.$detalle->id_orden_detalle.'][fk_id_cliente]',$detalle->fk_id_cliente) !!}
									{{isset($detalle->cliente->nombre_corto)?$detalle->cliente->nombre_corto:'Sin cliente'}}
								</td>
								<td>
									{!! Form::hidden('detalles['.$detalle->id_orden_detalle.'][fk_id_proyecto]',$detalle->fk_id_proyecto) !!}
									{{isset($detalle->proyecto->proyecto)?$detalle->proyecto->proyecto:'Sin proyecto'}}
								</td>
								<td>
									{!! Form::hidden('detalles['.$detalle->id_orden_detalle.'][fecha_necesario]',$detalle->fecha_necesario) !!}
									{{$detalle->fecha_necesario}}
								</td>
								<td>
									{!! Form::hidden('detalles['.$detalle->id_orden_detalle.'][cantidad]',$detalle->cantidad) !!}
									{{$detalle->cantidad}}
								</td>
								<td>
									{!! Form::hidden('detalles['.$detalle->id_orden_detalle.'][fk_id_impuesto]',$detalle->fk_id_impuesto) !!}
									{{$detalle->impuesto->impuesto}}
								</td>
								<td>
									{!! Form::hidden('detalles['.$detalle->id_orden_detalle.'][precio_unitario]',$detalle->precio_unitario) !!}
									{{number_format($detalle->precio_unitario,2,'.','')}}
								</td>
								<td>
									<input type="text" class="form-control total" name="{{'detalles['.$detalle->id_orden_detalle.'][total]'}}" readonly value="{{number_format($detalle->total,2,'.','')}}">
								<td>
									{{--Si se va a editar, agrega el botón para "eliminar" la fila--}}
									@if(Route::currentRouteNamed(currentRouteName('edit')) && $data->fk_id_estatus_orden == 1)
										<button class="btn is-icon text-primary bg-white "
												type="button" data-item-id="{{$detalle->id_orden_detalle}}"
												id="{{$detalle->id_orden_detalle}}" data-delay="50"
												onclick="borrarFila_edit(this)" data-delete-type="single">
											<i class="material-icons">delete</i></button>
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

@section('header-bottom')
	@parent
	{{ HTML::script(asset('js/proyectos.js')) }}
	{{ HTML::script(asset('vendor/vanilla-datatables/vanilla-dataTables.js')) }}
@endsection

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