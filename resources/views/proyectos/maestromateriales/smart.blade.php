@extends(smart())

@section('header-top')
	{{ HTML::style(asset('vendor/vanilla-dataTables/vanilla-dataTables.css')) }}
@endsection

@section('form-content')
	{{ Form::setModel($data) }}
	<div class="row">
		<div class="form-group col-md-6 col-xs-12">
			{{Form::label('fk_id_cliente','* Cliente')}}
			{!!Form::select('fk_id_cliente',isset($clientes)?$clientes:[],null,['id'=>'fk_id_cliente','class'=>'form-control','style'=>'width:100%'])!!}
		</div>
		<div class="form-group col-md-6 col-xs-12">
			{{Form::label('fk_id_proyecto','* Proyecto')}}
			{!!Form::select('fk_id_proyecto',[],null,['id'=>'fk_id_proyecto','disabled','class'=>'form-control','style'=>'width:100%','data-url'=>companyAction('Proyectos\ProyectosController@obtenerProyectosCliente',['id'=>'?id'])])!!}
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
    							<div class="form-group input-field col-md-6 col-sm-6">
    								{{Form::label('fk_id_clave_cliente_producto','* Clave cliente producto')}}
    								{!!Form::select('fk_id_clave_cliente_producto',[],null,['id'=>'fk_id_clave_cliente_producto','disabled','class'=>'form-control','style'=>'width:100%','data-url'=>companyAction('Proyectos\ClaveClienteProductosController@obtenerClavesCliente',['id'=>'?id'])])!!}
    {{--								{{Form::cSelect('Clave cliente producto','fk_id_clave_cliente_producto',[],['disabled'])}}--}}
    							</div>
    							<div class="form-group input-field col-md-6 col-sm-6">
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
    				<table id="productos" class="table-responsive highlight"
    					   @if(isset($data->id_proyecto_producto))
    					   data-delete="{{companyAction('Compras\OrdenesController@destroyDetail')}}"
    					   @endif
    					   >
    					<thead>
    					<tr>
    						<th>Clave cliente producto</th>
    						<th>Descripción clave</th>
    						<th>UPC</th>
    						<th>Descripción UPC</th>
    						<th>Prioridad</th>
    						<th>Cantidad</th>
    						<th>Precio sugerido</th>
    						<th>Estatus</th>
    						<th></th>
    					</tr>
    					</thead>
    					<tbody id="tbody">
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
@endsection

@section('header-bottom')
	@parent
	{{ HTML::script(asset('js/maestro_materiales.js')) }}
	{{ HTML::script(asset('vendor/vanilla-datatables/vanilla-dataTables.js')) }}
@endsection