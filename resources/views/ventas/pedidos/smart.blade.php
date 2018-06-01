@extends(smart())

@section('header-bottom')
	@foreach($data as $item)
		{{dump($item)}}
	@endforeach
	@parent
	<script type="text/javascript">
		var clientes_js =   "{{ $js_clientes ?? '' }}";
    	var proyectos_js =  "{{ $js_proyectos ?? '' }}";
    	var sucursales_js = "{{ $js_sucursales ?? '' }}";
    	var contratos_js =  "{{ $js_contratos ?? '' }}";
    	var modeldata =     "{{ json_encode($data) ?? '' }}";
    </script>
    @notroute(['index','show'])
    	{{ HTML::script(asset('js/ventas/pedidos.js')) }}
    @endif
@endsection

@section('form-content')
	{{ Form::setModel($data) }}
	<div class="row">
		<div class="col-lg-8 row">
    		<div class="form-group col-md-6 col-xs-12">
    			{{Form::cSelectWithDisabled('* Localidad','fk_id_localidad',$localidades ?? [],['class' => !Route::currentRouteNamed(currentRouteName('show')) ? 'select2' : ''])}}
    		</div>
    		<div class="form-group col-md-6">
    			{{Form::cSelect('* Cliente','fk_id_socio_negocio', $clientes ?? [], ['class' => !Route::currentRouteNamed(currentRouteName('show')) ? 'select2' : ''])}}
    		</div>
    		
    		<div class="form-group col-md-6">
    			{{Form::cSelect('* Proyecto','fk_id_proyecto', $proyectos ?? [], ['class' => !Route::currentRouteNamed(currentRouteName('show')) ? 'select2' : '','data-url'=>companyAction('HomeController@index').'/proyectos.proyectos/api'])}}
    		</div>
    		<div class="form-group col-md-6">
    			{{Form::cSelect('* Sucursal','fk_id_sucursal', $sucursales ?? [], ['class' => !Route::currentRouteNamed(currentRouteName('show')) ? 'select2' : '','data-url'=>companyAction('HomeController@index').'/administracion.sucursales/api'])}}
    		</div>
    		
    		<div class="form-group col-md-6">
    			{{Form::cSelect('Contrato','fk_id_contrato', $contratos ?? [], ['class' => !Route::currentRouteNamed(currentRouteName('show')) ? 'select2' : '','data-url'=>companyAction('HomeController@index').'/proyectos.proyectos/api'])}}
    		</div>
    		<div class="form-group col-md-6">
    			{{Form::cSelect('Ejecutivo de ventas','fk_id_ejecutivo_venta', $ejecutivos ?? [], ['class' => !Route::currentRouteNamed(currentRouteName('show')) ? 'select2' : ''])}}
    		</div>
    		<div class="form-group col-md-12">
    			{{Form::cTextArea('Observaciones','observaciones',['rows'=>2])}}
    		</div>
		</div>
		
		<div class="card col-lg-4 row ml-3 mt-3">
			<div class="card-header row">
				<h3 class="col-sm-12 text-center">{{ isset($data->id_documento) ? 'No. Pedido '.$data->id_documento : ''}}</h3>
        	</div>
        	<div class="card-body row">
        		<div class="form-group col-md-12 col-xs-12">
        			{{Form::cText('No. Pedido / Orden del Cliente','no_pedido')}}
        		</div>
        		<div class="form-group col-md-6 col-xs-12">
        			{{Form::cText('* Fecha Pedido','fecha_pedido',['class'=>' datepicker '])}}
        		</div>
        		<div class="form-group col-md-6 col-xs-12">
        			{{Form::cText('* Fecha Limite','fecha_limite',['class'=>' datepicker '])}}
        		</div>
        		<div class="form-group col-md-12 col-xs-12">
        			{{Form::cSelectWithDisabled('* Moneda','fk_id_moneda',$monedas ?? [],['class' => !Route::currentRouteNamed(currentRouteName('show')) ? 'select2' : ''])}}
        		</div>
				<div class="form-group col-md-12 col-xs-12">
        			{{Form::cSelectWithDisabled('* Estatus','fk_id_estatus',$estatus ?? [])}}
        		</div>
        	</div>
		</div>
	</div>

    <div id="detallesku" class="container-fluid w-100 mt-2 px-0">
    	<div class="card text-center z-depth-1-half" style="min-height: 555px">
    		<div class="card-header py-2">
                <div class="divider my-2"></div>
    			<ul id="clothing-nav" class="nav nav-pills nav-justified" role="tablist">
    				<li class="nav-item"><a class="nav-link active" role="tab" data-toggle="tab" href="#tab-Productos" id="Productos-tab" aria-controls="Productos" aria-expanded="true">Productos</a></li>
    				<li class="nav-item"><a class="nav-link" role="tab" data-toggle="tab" href="#tab-anexos" id="anexos-tab" aria-controls="anexos" aria-expanded="true">Anexos</a></li>
    			</ul>
    		</div>
    		<!-- Content Panel -->
    		<div id="clothing-nav-content" class="card-body tab-content">
    			<div role="tabpanel" class="tab-pane fade show active" id="tab-Productos" aria-labelledby="Productos-tab">
    				<div class="card">
    					@if(!Route::currentRouteNamed(currentRouteName('show')))
    						<div class="card-header">
    							<fieldset name="detalle-form" id="detalle-form">
    								<div class="row">
    									<div class="form-group col-md-2 col-sm-6">
                                			{{Form::cNumber('* Cantidad','cantidad',['min'=>1,'step'=>1])}}
                                		</div>
    									<div class="form-group input-field col-md-4 col-sm-6">
                                            <div id="loadingfk_id_clave_cliente_producto" class="w-100 h-100 text-center text-white align-middle loadingData" style="display: none">
                                                Cargando datos... <i class="material-icons align-middle loading">cached</i>
                                            </div>
    										{{Form::cSelectWithDisabled('* Clave producto','fk_id_clave_cliente_producto', $productos ?? [],['class'=>'index','data-url'=>companyAction('Proyectos\ClaveClienteProductosController@obtenerClavesCliente',['id'=>'?id'])])}}
    									</div>
    									<div class="form-group input-field col-md-4 col-sm-6">
                                            <div id="loadingfk_id_upc" class="w-100 h-100 text-center text-white align-middle loadingData" style="display: none">
                                                Cargando datos... <i class="material-icons align-middle loading">cached</i>
                                            </div>
    										{{Form::label('fk_id_upc','UPC')}}
    										<div class="input-group">
    											<span class="input-group-addon">
    												<input type="checkbox" id="activo_upc">
    											</span>
    											{{Form::cSelect(null,'fk_id_upc',$upcs ?? [],['class'=>'index','disabled'=>true,'data-url'=>companyAction('Inventarios\ProductosController@obtenerUpcs',['id'=>'?id'])])}}
    										</div>
										</div>
										<div class="form-group input-field col-md-2 col-sm-6">
											{!! Form::label('descuento_detalle','Descuento producto') !!}
											<div class="input-group">
												<span class="input-group-addon">%</span>
												{!! Form::text('descuento_detalle',0,['placeholder'=>'99.0000','class'=>'form-control']) !!}
											</div>
										</div>
    									<div class="col-sm-12 text-center border">
    										<div class="sep">
    											<div class="sepText bg-light">ó</div>
    										</div>
    										<p class="my-2 text-center">Puedes descargar un layout e importar el excel</p>
        									<div class="row text-center">
        										<div class="form-goup col-md-12 text-center">
        											<a href="{{companyAction('layoutProductos')}}" id="layout" class="btn btn-outline-info btn-lg">Descargar Layout</a>
        										</div>
        										<div class="form-goup col-sm-2"></div>
        										<div class="form-goup col-sm-8">
        											{{ Form::cFile(null, 'file_xlsx',['accept'=>'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel','data-url'=>companyAction('importarProductos')]) }}
        										</div>
        										<div class="form-goup col-sm-2">
        											<div style="display:none;" id="campo_moneda">
        												{{ Form::cSelectWithDisabled(null,'relations[has][productos][$row_id][fk_id_moneda]', $monedas ?? [],['class'=>'fk_id_moneda']) }}
        											</div>
												</div>
        									</div>
    									</div>
    									<div class="col-sm-12 text-center mb-2">
    										<div class="sep sepBtn">
    											<button id="agregarProducto" class="btn btn-primary btn-large btn-circle" data-placement="bottom" data-delay="100" data-tooltip="Agregar" data-toggle="tooltip" data-action="add" title="Agregar" type="button"><i class="material-icons">add</i></button>
    										</div>
    									</div>
    								</div>
    							</fieldset>
    						</div>
    					</div>
    				@endif
    				<div class="card-body table-responsive">
    					<table class="table highlight" id="detalleProductos">
    						   @if(isset($data->ProyectosProductos))
    						   data-delete="{{companyAction('Proyectos\ProyectosProductosController@destroy')}}"
    							@endif
    						<thead>
    						<tr>
    							<th>Cantidad</th>
    							<th>Clave producto</th>
    							<th>Descripción Producto</th>
    							<th>UPC</th>
    							<th>Descripción UPC</th>
    							<th>Precio Unitario</th>
								<th>Descuento(%)</th>
								<th>Importe</th>
    							<th></th>
    						</tr>
    						</thead>
    						<tbody id="tbodyproductosproyectos">
                            <div class="w-100 h-100 text-center text-white align-middle loadingData loadingtabla" style="display: none;">
                                Cargando datos... <i class="material-icons align-middle loading">cached</i>
							</div>

    						@if(isset($data->detalle))
    							@foreach( $data->detalle->where('eliminar',0) as $row=>$detalle)
    								<tr id="{{$detalle->id_proyecto_producto}}">
    									<td>
    										{{ Form::hidden('relations[has][detalle]['.$row.'][index]',$row,['class'=>'index']) }}
											{{ Form::hidden('relations[has][detalle]['.$row.'][id_documento_detalle]',$detalle->id_documento_detalle) }}
											{{ Form::hidden('relations[has][detalle]['.$row.'][cantidad]',$detalle->cantidad) }}
    										{{ $detalle->cantidad }}
    									</td>
    									<td>
											{{ Form::hidden('relations[has][detalle]['.$row.'][fk_id_clave_cliente_producto]',$detalle->fk_id_clave_cliente_producto) }}
    										{{ $detalle->clavecliente->clave_producto_cliente }}
    									</td>
    									<td>
											{{ Form::hidden('relations[has][detalle]['.$row.'][fk_id_sku]',$detalle->fk_id_sku) }}
    										{{ $detalle->clavecliente->descripcion }}
    									</td>
    									<td>
											{{ Form::hidden('relations[has][detalle]['.$row.'][fk_id_upc]',$detalle->fk_id_upc) }}
    										{{$detalle->upc->upc ?? ''}}
    									</td>
    									<td>
    										{{$detalle->upc->descripcion ?? ''}}
    									</td>
    									<td>
											{{ Form::hidden('relations[has][detalle]['.$row.'][fk_id_impuesto]',$detalle->fk_id_impuesto) }}
    										{{ number_format($detalle->precio_unitario,2) }}
										</td>
    									<td>
											{{ Form::hidden('relations[has][detalle]['.$row.'][precio_unitario]',$detalle->precio_unitario) }}

											{!! Form::text('relations[has][detalle]['.$row.'][descuento]',
											number_format($detalle->descuento,2,'.',''),
											['class'=>'form-control text-center','style'=>'width:80px','readonly' => true]) !!}
    									</td>
    									<td>
											{{ Form::hidden('relations[has][detalle]['.$row.'][importe]',$detalle->importe) }}
    										{{ number_format($detalle->importe,2) }}
										</td>
    									<td>
    									@if(Route::currentRouteNamed(currentRouteName('edit')))
    										<button class="btn is-icon text-primary bg-white" type="button" data-delay="50" onclick="borrarFila(this)" data-tooltip="Producto"><i class="material-icons">delete</i></button>
    									@endif
    									</td>
    								</tr>
    							@endforeach
    						@endif
    						</tbody>
    					</table>
    				</div>
    			</div>
    			
    			<div role="tabpanel" class="tab-pane fade" id="tab-anexos" aria-labelledby="anexos-tab">
    				<div class="col-sm-12">
                		<div class="card z-depth-1-half">
                			<div class="card-header">
        						<div class="row">
        							<div class="form-group col-md-6">
        								{{ Form::cText('* Nombre', 'nombre_archivo',['maxlength'=>'60']) }}
        							</div>
        							<div class="form-group col-md-6">
        								{{ Form::cFile('* Archivo', 'archivo',['accept'=>'.xlsx,.xls,image/*,.doc, .docx,.ppt, .pptx,.txt,.pdf']) }}
        							</div>
        							<div class="form-group col-sm-12 my-3">
            							<div class="sep sepBtn">
                    						<button id="agregarAnexo" class="btn btn-primary btn-large btn-circle" data-placement="bottom" data-delay="100" data-tooltip="Agregar" data-toggle="tooltip" data-action="add" title="Agregar" type="button"><i class="material-icons">add</i></button>
                    					</div>
            						</div>
        						</div>
        					</div>
        					<div class="card-body">
        						<table class="table responsive-table highlight" id="detalleAnexos">
        							<thead>
        								<tr>
        									<th>Nombre</th>
        									<th>Archivo</th>
        									<th>Acción</th>
        								</tr>
        							</thead>
        							<tbody>
        							@if(isset($data->anexos)) 
        							@foreach($data->anexos->where('eliminar',0) as $row=>$detalle)
    								<tr>
    									<td>
    										{{ Form::hidden('relations[has][anexos]['.$row.'][index]',$row,['class'=>'index']) }}
    										{{ Form::hidden('relations[has][anexos]['.$row.'][id_anexo]',$detalle->id_anexo,['class'=>'id_anexo']) }}
    										{{$detalle->nombre}}
    									</td>
    									<td>
    										{{$detalle->archivo}}
    									</td>
    									<td>
    										<a class="btn is-icon text-primary bg-white" href="{{companyAction('descargaranexo', ['id' => $detalle->id_anexo])}}" title="Descargar Archivo">
    											<i class="material-icons">file_download</i>
    										</a>
    										@if(Route::currentRouteNamed(currentRouteName('edit')))
    											<button class="btn is-icon text-primary bg-white" type="button" data-delay="50" onclick="borrarFila(this)" data-tooltip="Anexo"> <i class="material-icons">delete</i></button>
    										@endif
    									</td>
    								</tr>
        							@endforeach
        						@endif
        							</tbody>
        						</table>
        					</div>
        				</div><!--/Here ends card-->
        			</div>
    			</div>
    			
    		</div>
    		<!-- End Content Panel -->
    	</div>
    </div>
@endsection