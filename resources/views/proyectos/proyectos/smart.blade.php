@extends(smart())
@section('content-width', 'w-100')

@section('form-content')
	{{ Form::setModel($data) }}
    <div id="confirmacion" class="modal" tabindex="-1" role="dialog">
    	<div class="modal-dialog" role="document">
    		<div class="modal-content">
    			<div class="modal-header">
    				<h5 class="modal-title">Cambio de cliente</h5>
    			</div>
    			<div class="modal-body">
    				Recuerda que al cambiar el cliente se eliminarán los datos actuales del proyecto, tales como las licitaciones, contratos, productos, anexos y finanzas
    			</div>
    			<div class="modal-footer">
    				<button id="cancelarcambiocliente" type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
    				<button id="confirmar" type="button" class="btn btn-primary">Guardar</button>
    			</div>
    		</div>
    	</div>
    </div>
    <h4>Datos del proyecto</h4>
    <div class="row">
		<div class="form-group col-md-8 col-xs-12">
			{{Form::cText('* Nombre Proyecto','proyecto',['maxlength'=>'100'])}}
		</div>
		<div class="form-group col-md-4 col-xs-12">
			{{Form::cSelectWithDisabled('* Clase Proyecto','fk_id_clasificacion_proyecto',$clasificaciones ?? [],['class'=>'select2'])}}
		</div>
		<div class="form-group col-md-8 col-xs-12">
			{{Form::cSelectWithDisabled('* Cliente','fk_id_cliente',$clientes ?? [],['class'=>'select2','data-url'=>companyAction('HomeController@index').'/Administracion.sucursales/api'])}}
		</div>
		<div class="form-group col-md-4 col-xs-12">
			{{Form::cSelectWithDisabled('* Estatus','fk_id_estatus',$estatus ?? [])}}
		</div>
		<div class="form-group col-md-4 col-xs-12">
			{{Form::cSelectWithDisabled('* Localidad','fk_id_localidad',$localidades ?? [],['class'=>'select2'])}}
		</div>
		<div class="form-group col-md-4">
			<div id="loadingsucursales" class="w-100 h-100 text-center text-white align-middle loadingData" style="display: none">
				Cargando sucursales... <i class="material-icons align-middle loading">cached</i>
			</div>
			{{Form::cSelectWithDisabled('Sucursal','fk_id_sucursal',$sucursales ?? [],['class'=>'select2','disabled'])}}
		</div>
		<div class="form-group col-md-2 col-xs-12">
			{{Form::cText('* Fecha Inicio','fecha_inicio',['class'=>' datepicker'])}}
		</div>
		<div class="form-group col-md-2 col-xs-12">
			{{Form::cText('* Fecha Terminacion','fecha_terminacion',['class'=>' datepicker'])}}
		</div>
	</div>

    <div id="detallesku" class="container-fluid w-100 mt-2 px-0">
    	<div class="card text-center z-depth-1-half" style="min-height: 555px">
    		<div class="card-header py-2">
        		<h4 class="card-title">Informacion del Proyecto</h4>
                <div class="divider my-2"></div>
    			<ul id="clothing-nav" class="nav nav-pills nav-justified" role="tablist">
    				<li class="nav-item"><a class="nav-link active" role="tab" data-toggle="tab" href="#tab-licitacion" id="licitacion-tab" aria-controls="licitacion" aria-expanded="true">Licitacion</a></li>
    				<li class="nav-item"><a class="nav-link" role="tab" data-toggle="tab" href="#tab-contratos" id="contratos-tab" aria-controls="contratos" aria-expanded="true">Contratos</a></li>
    				<li class="nav-item"><a class="nav-link" role="tab" data-toggle="tab" href="#tab-productosProyectos" id="productosProyectos-tab" aria-controls="productosProyectos" aria-expanded="true">Productos</a></li>
    				<li class="nav-item"><a class="nav-link" role="tab" data-toggle="tab" href="#tab-anexos" id="anexos-tab" aria-controls="anexos" aria-expanded="true">Anexos</a></li>
    				<li class="nav-item"><a class="nav-link" role="tab" data-toggle="tab" href="#tab-finanzas" id="finanzas-tab" aria-controls="finanzas" aria-expanded="true">Finanzas</a></li>
    			</ul>
    		</div>
    		<!-- Content Panel -->
    		<div id="clothing-nav-content" class="card-body tab-content">
    			<div role="tabpanel" class="tab-pane fade show active" id="tab-licitacion" aria-labelledby="licitacion-tab">
    				<div class="form-group">
    					<div id="loadinglicitacion" class="w-100 h-100 text-center text-white align-middle loadingData" style="display: none">
    						Cargando licitación... <i class="material-icons align-middle loading">cached</i>
    					</div>
    				</div>
    				<div class="row">
    					<div class="form-group col-md-3">
                			{{Form::cText('No. Evento','num_evento')}}
                		</div>
                		<div class="form-group col-md-3">
                			{{Form::cSelectWithDisabled('Tipo Evento','fk_id_tipo_evento', $tiposeventos ?? [],['class'=>'select2'])}}
                		</div>
                		<div class="form-group col-md-3">
                			{{Form::cSelectWithDisabled('Dependencia','fk_id_dependencia', $dependencias ?? [],['class'=>'select2'])}}
                		</div>
                		<div class="form-group col-md-3">
                			{{Form::cSelectWithDisabled('Subdependencia','fk_id_subdependencia', $subdependencias ?? [],['class'=>'select2','data-url'=>companyAction('HomeController@index').'/administracion.subdependencias/api'])}}
                		</div>
    					<div class="form-group col-md-3">
    						{{Form::cSelectWithDisabled('Modalidad entrega','fk_id_modalidad_entrega', $modalidadesentrega ?? [],['class'=>'select2'])}}
    					</div>
    					<div class="form-group col-md-3">
    						{{Form::cSelectWithDisabled('Caracter del evento','fk_id_caracter_evento',$caracterevento ?? [],['class'=>'select2'])}}
    					</div>
    					<div class="form-group col-md-3">
    						{{Form::cSelectWithDisabled('Forma de adjudicación','fk_id_forma_adjudicacion',$formaadjudicacion ?? [],['class'=>'select2'])}}
    					</div>
    					<div class="form-group col-md-3">
    						<div>
    							<label for="pena_convencional" class="float-left">Pena Convencional</label>
    							<label for="tope_pena_convencional" class="float-right">Tope Pena Convencional</label>
    						</div>
    						<div class="input-group">
    							<input name="pena_convencional" id="pena_convencional" type="number" class="form-control">
    							<span class="input-group-addon">%</span>
    							<input name="tope_pena_convencional" id="tope_pena_convencional" type="number" class="form-control">
    							<span class="input-group-addon">%</span>
    						</div>
    					</div>
    					@if(Route::currentRouteNamed(currentRouteName('create')))
    					<div class="row col-md-12 text-center">
    						<div class="form-goup col-md-4 text-center">
    							<button type="button" data-url="{{companyAction('HomeController@index').'/Liciplus.licitaciones/api'}}" id="importar_liciplus" disabled class="btn btn-info btn-lg">LICIPLUS</button>
    						</div>
    						<div class="form-goup col-md-4 text-center">
    							<button type="button" data-url="{{companyAction('HomeController@index').'/Liciplus.contratos/api'}}" id="importar_contratos" disabled class="btn btn-info btn-lg">Importar Contratos</button>
    						</div>
    						<div class="form-goup col-md-4 text-center">
    							<button type="button" data-url="{{companyAction('HomeController@index').'/Liciplus.partidas/api'}}" id="importar_productos" disabled class="btn btn-info btn-lg">Importar Productos</button>
    						</div>
    					</div>
    					@endif
    				</div>
    			</div>
    			<div role="tabpanel" class="tab-pane fade" id="tab-contratos" aria-labelledby="contratos-tab">
    				<div class="col-sm-12">
                		<div class="card z-depth-1-half">
                			<div class="card-header">
        						<div class="row">
        							<div class="form-group col-lg-3 col-md-6">
                            			{{Form::cText('* Representante legal','representante_legal',['maxlength'=>'200'])}}
                            		</div>
        							<div class="form-group col-lg-3 col-md-6">
        								{{ Form::cText('* No. Contrato', 'num_contrato') }}
        							</div>
        							<div class="form-group col-lg-2 col-md-4">
                            			{{Form::cText('* Fecha inicio','fecha_inicio_contrato',['class'=>' datepicker'])}}
                            		</div>
                            		<div class="form-group col-lg-2 col-md-4">
                            			{{Form::cText('* Fecha fin','fecha_fin_contrato',['class'=>' datepicker'])}}
                            		</div>
        							<div class="form-group col-lg-2 col-md-4">
        								{{ Form::cFile('* Archivo', 'contrato',['accept'=>'.pdf']) }}
        							</div>
        							<div class="form-group col-sm-12 my-3">
            							<div class="sep sepBtn">
                    						<button id="agregarContrato" class="btn btn-primary btn-large btn-circle" data-placement="bottom" data-delay="100" data-tooltip="Agregar" data-toggle="tooltip" data-action="add" title="Agregar" type="button"><i class="material-icons">add</i></button>
                    					</div>
            						</div>
        						</div>
        					</div>
        					<div class="card-body responsive-table">
        						<table class="table highlight" id="detalleContratos">
        							<thead>
        								<tr>
        									<th>Representante legal</th>
        									<th>Num Contrato</th>
        									<th>Fecha Inicio</th>
        									<th>Fecha Fin</th>
        									<th>Archivo</th>
        									<th></th>
        								</tr>
        							</thead>
        							<tbody>
        							@if(isset($data->contratos)) 
            							@foreach($data->contratos->where('eliminar',0) as $row=>$detalle)
        								<tr>
        									<td>
        										{{ Form::hidden('relations[has][contratos]['.$row.'][index]',$row,['class'=>'index']) }}
        										{{ Form::hidden('relations[has][contratos]['.$row.'][id_contrato]',$detalle->id_contrato) }}
        										{{$detalle->representante_legal}}
        									</td>
        									<td>
        										{{$detalle->num_contrato}}
        									</td>
        									<td>
        										{{$detalle->fecha_inicio}}
        									</td>
        									<td>
        										{{$detalle->fecha_fin}}
        									</td>
        									<td>
        										{{$detalle->archivo}}
        									</td>
        									<td>
        										<a class="btn is-icon text-primary bg-white" href="{{companyAction('descargarcontrato', ['id' => $detalle->id_contrato])}}" title="Descargar Archivo">
        											<i class="material-icons">file_download</i>
        										</a>
        										@if(Route::currentRouteNamed(currentRouteName('edit')))
        											<button class="btn is-icon text-primary bg-white" type="button" data-delay="50" onclick="borrarFila(this)" data-tooltip="Contrato"> <i class="material-icons">delete</i></button>
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
    				
    			<div role="tabpanel" class="tab-pane fade" id="tab-productosProyectos" aria-labelledby="productosProyectos-tab">
    				<div class="card">
    					@if(!Route::currentRouteNamed(currentRouteName('show')))
    						<div class="card-header">
    							<fieldset name="detalle-form" id="detalle-form">
    								<div class="row">
    									<div class="form-group input-field col-md-6 col-sm-6">
                                            <div id="loadingfk_id_clave_cliente_producto" class="w-100 h-100 text-center text-white align-middle loadingData" style="display: none">
                                                Cargando datos... <i class="material-icons align-middle loading">cached</i>
                                            </div>
    										{{Form::cSelect('* Clave cliente producto','fk_id_clave_cliente_producto', $productos ?? [],['class'=>'index','disabled'=>true,'data-url'=>companyAction('Proyectos\ClaveClienteProductosController@obtenerClavesCliente',['id'=>'?id'])])}}
    									</div>
    									<div class="form-group input-field col-md-6 col-sm-6">
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
    									<div class="col-sm-12 text-center border">
    										<div class="sep">
    											<div class="sepText bg-light">ó</div>
    										</div>
    										<p class="my-2 text-center">Puedes descargar un layout e importar el excel</p>
        									<div class="row text-center">
        										<div class="form-goup col-md-12 text-center">
        											<a href="{{companyAction('layoutProductosProyecto')}}" id="layout" class="btn btn-outline-info btn-lg">Descargar Layout</a>
        										</div>
        										<div class="form-goup col-sm-2"></div>
        										<div class="form-goup col-sm-8">
        											{{ Form::cFile(null, 'file_xlsx',['accept'=>'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel','data-url'=>companyAction('loadLayoutProductosProyectos')]) }}
        										</div>
        										<div class="form-goup col-sm-2">
        											<div style="display:none;" id="campo_moneda">
        												{{ Form::cSelectWithDisabled(null,'relations[has][productos][$row_id][fk_id_moneda]', $monedas ?? [],['class'=>'fk_id_moneda'],['100'=>['selected']]) }}
        											</div>
        										</div>
        									</div>
    										<div class="col-sm-12 text-center mt-3">
        										<div class="sep sepBtn">
    												<button id="agregarProducto" data-url="{{companyAction('Proyectos\ProyectosController@getClavesClientesProductos')}}" class="btn btn-primary btn-large btn-circle" data-placement="bottom" data-delay="100" data-tooltip="Agregar" data-toggle="tooltip" data-action="add" title="Agregar" type="button"><i class="material-icons">add</i></button>
        										</div>
        									</div>
    									</div>
    								</div>
    							</fieldset>
    						</div>
    				@endif
    				<div class="card-body table-responsive">
    					<table class="table highlight" id="detalleProductos">
    						   @if(isset($data->ProyectosProductos))
    						   data-delete="{{companyAction('Proyectos\ProyectosProductosController@destroy')}}"
    							@endif
    						<thead>
    						<tr>
    							<th>Clave cliente producto</th>
    							<th>Descripción clave</th>
    							<th>UPC</th>
    							<th>Descripción UPC</th>
    							<th>Prioridad</th>
    							<th>Cantidad</th>
    							<th>Precio venta</th>
    							<th>Moneda</th>
    							<th>Máximo</th>
    							<th>Mínimo</th>
    							<th>Punto de reorden</th>
    							<th>Estatus</th>
    							<th></th>
    						</tr>
    						</thead>
    						<tbody id="tbodyproductosproyectos">
                            <div class="w-100 h-100 text-center text-white align-middle loadingData loadingtabla" style="display: none;">
                                Cargando datos... <i class="material-icons align-middle loading">cached</i>
                            </div>
    						@if( isset( $data->productos ) )
    							@foreach( $data->productos->where('eliminar',0) as $row=>$detalle)
    								<tr id="{{$detalle->id_proyecto_producto}}">
    									<td>
    										{{ Form::hidden('relations[has][productos]['.$row.'][index]',$row,['class'=>'index']) }}
    										{{ Form::hidden('relations[has][productos]['.$row.'][id_proyecto_producto]',$detalle->id_proyecto_producto) }}
    										{{$detalle->claveClienteProducto->clave_producto_cliente}}
    									</td>
    									<td>
    										{{$detalle->claveClienteProducto->sku->descripcion_corta}}
    									</td>
    									<td>
    										{{$detalle->upc->upc ?? 'Sin UPC'}}
    									</td>
    									<td>
    										{{$detalle->upc->descripcion ?? ''}}
    									</td>
    									<td>
    										{{ Form::text('relations[has][productos]['.$row.'][prioridad]',$detalle->prioridad,['class'=>'form-control prioridad','maxlength'=>'2']) }}
    									</td>
    									<td>
    										{{ Form::text('relations[has][productos]['.$row.'][cantidad]', $detalle->cantidad,['class'=>'form-control cantidad','maxlength'=>'3']) }}
    									</td>
    									<td>
    										{{ Form::text('relations[has][productos]['.$row.'][precio_sugerido]',number_format($detalle->precio_sugerido,2),['class'=>'form-control precio_sugerido','maxlength'=>'13']) }}
    									</td>
    									<td>
    										{{ Form::select('relations[has][productos]['.$row.'][fk_id_moneda]', $monedas ?? [], $detalle->fk_id_moneda, ['class'=>'form-control custom-select fk_id_moneda']) }}
    									</td>
    									<td>
    										{{ Form::text('relations[has][productos]['.$row.'][maximo]', $detalle->maximo, ['class'=>'form-control maximo','maxlength'=>'4']) }}
    									</td>
    									<td>
    										{{ Form::text('relations[has][productos]['.$row.'][minimo]', $detalle->minimo, ['class'=>'form-control minimo','maxlength'=>'4']) }}
    									</td>
    									<td>
    										{{ Form::text('relations[has][productos]['.$row.'][numero_reorden]', $detalle->numero_reorden, ['class'=>'form-control numero_reorden','maxlength'=>'4']) }}
    									</td>
    									<td>
    										{{ Form::cCheckbox('','relations[has][productos]['.$row.'][activo]',[!empty($detalle->activo)?'checked':'']) }}
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
    			</div>
    
    			
    			<div role="tabpanel" class="tab-pane fade" id="tab-anexos" aria-labelledby="anexos-tab">
    				{{--<div class="col-sm-12">--}}
                		<div class="card">
                			<div class="card-header">
        						<div class="row">
        							<div class="form-group col-md-6">
        								{{ Form::cText('* Nombre', 'nombre_archivo') }}
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
        			{{--</div>--}}
    			</div>
    			<div role="tabpanel" class="tab-pane fade" id="tab-finanzas" aria-labelledby="finanzas-tab">
    				<div class="row">
    					<h4 class="col-sm-12">Información financiera del proyecto</h4>
    					<div class="card col-sm-11 col-lg-5 px-0 mx-5">
    						<div class="card-header">Ingresos</div>
    						<div class="card-body">
    							<div id="chart-sales"></div>
    						</div>
    					</div>
    					<div class="card col-sm-11 col-lg-5 px-0 mx-5">
    						<div class="card-header">Egresos</div>
    						<div class="card-body">
    							<div id="chart-compras"></div>
    						</div>
    					</div>
    					<div class="card col-sm-11 col-lg-5 px-0 mx-5 mt-4">
    						<div class="card-header">Gastos</div>
    						<div class="card-body">
    							<div id="chart-gastos"></div>
    						</div>
    					</div>
    					<div class="card col-sm-11 col-lg-5 px-0 mx-5 mt-4">
    						<div class="card-header">Rentabilidad</div>
    						<div class="card-body">
    							<div id="chart-rentabilidad"></div>
    						</div>
    					</div>
    				</div>
    			</div>
    			
    		</div>
    		<!-- End Content Panel -->
    	</div>
    </div>
@endsection

@section('header-bottom')
	@parent
	{{ HTML::script(asset('vendor/vanilla-datatables/vanilla-dataTables.js')) }}
    @if (!Route::currentRouteNamed(currentRouteName('index')))
	{{ HTML::script(asset('js/proyectos/proyectos.js')) }}
	{{ HTML::script(asset('js/proyectos/maestro_materiales.js')) }}
	<script type="text/javascript">
		var licitacion_js = '{{$js_licitacion ?? ''}}';
		var sucursales_js = '{{$js_sucursales ?? ''}}';
        var contratos_js = '{{$js_contratos ?? ''}}';
        var partidas_js = '{{$js_partidas ?? ''}}';
        var subdependencias_js = '{{$js_subdependencias}}'
	</script>
    <!-- Resources -->
    <style>
        #chart-sales, #chart-gastos, #chart-compras, #chart-rentabilidad {
          width: 100%;
          height: 400px;
        }						
    </style>
    
    <script src="{{asset('js/amcharts/amcharts.js')}}"></script>
    <script src="{{asset('js/amcharts/gauge.js')}}"></script>
    <script src="{{asset('js/amcharts/serial.js')}}"></script>
    <script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
    <link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
    <script src="{{asset('js/amcharts/themes/light.js')}}"></script>

    <!-- Chart code -->
    <script>
    var chart = AmCharts.makeChart("chart-sales", {
      "type": "serial",
      "theme": "light",
      "dataDateFormat": "YYYY-MM-DD",
      "precision": 2,
      "valueAxes": [{
        "id": "v1",
        "title": "Sales",
        "position": "left",
        "autoGridCount": false,
        "labelFunction": function(value) {
          return "$" + Math.round(value) + "M";
        }
      }, {
        "id": "v2",
        "title": "Market Days",
        "gridAlpha": 0,
        "position": "right",
        "autoGridCount": false
      }],
      "graphs": [{
        "id": "g3",
        "valueAxis": "v1",
        "lineColor": "#e1ede9",
        "fillColors": "#e1ede9",
        "fillAlphas": 1,
        "type": "column",
        "title": "Actual Sales",
        "valueField": "sales2",
        "clustered": false,
        "columnWidth": 0.5,
        "legendValueText": "$[[value]]M",
        "balloonText": "[[title]]<br /><b style='font-size: 130%'>$[[value]]M</b>"
      }, {
        "id": "g4",
        "valueAxis": "v1",
        "lineColor": "#62cf73",
        "fillColors": "#62cf73",
        "fillAlphas": 1,
        "type": "column",
        "title": "Target Sales",
        "valueField": "sales1",
        "clustered": false,
        "columnWidth": 0.3,
        "legendValueText": "$[[value]]M",
        "balloonText": "[[title]]<br /><b style='font-size: 130%'>$[[value]]M</b>"
      }, {
        "id": "g1",
        "valueAxis": "v2",
        "bullet": "round",
        "bulletBorderAlpha": 1,
        "bulletColor": "#FFFFFF",
        "bulletSize": 5,
        "hideBulletsCount": 50,
        "lineThickness": 2,
        "lineColor": "#20acd4",
        "type": "smoothedLine",
        "title": "Market Days",
        "useLineColorForBulletBorder": true,
        "valueField": "market1",
        "balloonText": "[[title]]<br /><b style='font-size: 130%'>[[value]]</b>"
      }, {
        "id": "g2",
        "valueAxis": "v2",
        "bullet": "round",
        "bulletBorderAlpha": 1,
        "bulletColor": "#FFFFFF",
        "bulletSize": 5,
        "hideBulletsCount": 50,
        "lineThickness": 2,
        "lineColor": "#e1ede9",
        "type": "smoothedLine",
        "dashLength": 5,
        "title": "Market Days ALL",
        "useLineColorForBulletBorder": true,
        "valueField": "market2",
        "balloonText": "[[title]]<br /><b style='font-size: 130%'>[[value]]</b>"
      }],
      "chartScrollbar": {
        "graph": "g1",
        "oppositeAxis": false,
        "offset": 30,
        "scrollbarHeight": 50,
        "backgroundAlpha": 0,
        "selectedBackgroundAlpha": 0.1,
        "selectedBackgroundColor": "#888888",
        "graphFillAlpha": 0,
        "graphLineAlpha": 0.5,
        "selectedGraphFillAlpha": 0,
        "selectedGraphLineAlpha": 1,
        "autoGridCount": true,
        "color": "#AAAAAA"
      },
      "chartCursor": {
        "pan": true,
        "valueLineEnabled": true,
        "valueLineBalloonEnabled": true,
        "cursorAlpha": 0,
        "valueLineAlpha": 0.2
      },
      "categoryField": "date",
      "categoryAxis": {
        "parseDates": true,
        "dashLength": 1,
        "minorGridEnabled": true
      },
      "legend": {
        "useGraphSettings": true,
        "position": "top"
      },
      "balloon": {
        "borderThickness": 1,
        "shadowAlpha": 0
      },
      "export": {
       "enabled": true
      },
      "dataProvider": [{
        "date": "2013-01-16",
        "market1": 71,
        "market2": 75,
        "sales1": 5,
        "sales2": 8
      }, {
        "date": "2013-01-17",
        "market1": 74,
        "market2": 78,
        "sales1": 4,
        "sales2": 6
      }, {
        "date": "2013-01-18",
        "market1": 78,
        "market2": 88,
        "sales1": 5,
        "sales2": 2
      }, {
        "date": "2013-01-19",
        "market1": 85,
        "market2": 89,
        "sales1": 8,
        "sales2": 9
      }, {
        "date": "2013-01-20",
        "market1": 82,
        "market2": 89,
        "sales1": 9,
        "sales2": 6
      }, {
        "date": "2013-01-21",
        "market1": 83,
        "market2": 85,
        "sales1": 3,
        "sales2": 5
      }, {
        "date": "2013-01-22",
        "market1": 88,
        "market2": 92,
        "sales1": 5,
        "sales2": 7
      }, {
        "date": "2013-01-23",
        "market1": 85,
        "market2": 90,
        "sales1": 7,
        "sales2": 6
      }, {
        "date": "2013-01-24",
        "market1": 85,
        "market2": 91,
        "sales1": 9,
        "sales2": 5
      }, {
        "date": "2013-01-25",
        "market1": 80,
        "market2": 84,
        "sales1": 5,
        "sales2": 8
      }, {
        "date": "2013-01-26",
        "market1": 87,
        "market2": 92,
        "sales1": 4,
        "sales2": 8
      }, {
        "date": "2013-01-27",
        "market1": 84,
        "market2": 87,
        "sales1": 3,
        "sales2": 4
      }, {
        "date": "2013-01-28",
        "market1": 83,
        "market2": 88,
        "sales1": 5,
        "sales2": 7
      }, {
        "date": "2013-01-29",
        "market1": 84,
        "market2": 87,
        "sales1": 5,
        "sales2": 8
      }, {
        "date": "2013-01-30",
        "market1": 81,
        "market2": 85,
        "sales1": 4,
        "sales2": 7
      }]
    });
    
    
    var chart = AmCharts.makeChart("chart-gastos", {
        "type": "serial",
        "theme": "light",
        "marginRight": 80,
        "marginTop": 17,
        "autoMarginOffset": 20,
        "dataProvider": [{
            "date": "2012-03-01",
            "price": 20
        }, {
            "date": "2012-03-02",
            "price": 75
        }, {
            "date": "2012-03-03",
            "price": 15
        }, {
            "date": "2012-03-04",
            "price": 75
        }, {
            "date": "2012-03-05",
            "price": 158
        }, {
            "date": "2012-03-06",
            "price": 57
        }, {
            "date": "2012-03-07",
            "price": 107
        }, {
            "date": "2012-03-08",
            "price": 89
        }, {
            "date": "2012-03-09",
            "price": 75
        }, {
            "date": "2012-03-10",
            "price": 132
        }, {
            "date": "2012-03-11",
            "price": 158
        }, {
            "date": "2012-03-12",
            "price": 56
        }, {
            "date": "2012-03-13",
            "price": 169
        }, {
            "date": "2012-03-14",
            "price": 24
        }, {
            "date": "2012-03-15",
            "price": 147
        }],
        "valueAxes": [{
            "logarithmic": true,
            "dashLength": 1,
            "guides": [{
                "dashLength": 6,
                "inside": true,
                "label": "average",
                "lineAlpha": 1,
                "value": 90.4
            }],
            "position": "left"
        }],
        "graphs": [{
            "bullet": "round",
            "id": "g1",
            "bulletBorderAlpha": 1,
            "bulletColor": "#FFFFFF",
            "bulletSize": 7,
            "lineThickness": 2,
            "title": "Price",
            "type": "smoothedLine",
            "useLineColorForBulletBorder": true,
            "valueField": "price"
        }],
        "chartScrollbar": {},
        "chartCursor": {
            "valueLineEnabled": true,
            "valueLineBalloonEnabled": true,
            "valueLineAlpha": 0.5,
            "fullWidth": true,
            "cursorAlpha": 0.05
        },
        "dataDateFormat": "YYYY-MM-DD",
        "categoryField": "date",
        "categoryAxis": {
            "parseDates": true
        },
        "export": {
            "enabled": true
        }
    });
    
    chart.addListener("dataUpdated", zoomChart);
    
    function zoomChart() {
        chart.zoomToDates(new Date(2012, 2, 2), new Date(2012, 2, 10));
    }
    
    var chart = AmCharts.makeChart( "chart-compras", {
    	  "type": "serial",
    	  "addClassNames": true,
    	  "theme": "light",
    	  "autoMargins": false,
    	  "marginLeft": 30,
    	  "marginRight": 8,
    	  "marginTop": 10,
    	  "marginBottom": 26,
    	  "balloon": {
    	    "adjustBorderColor": false,
    	    "horizontalPadding": 10,
    	    "verticalPadding": 8,
    	    "color": "#ffffff"
    	  },
    
    	  "dataProvider": [ {
    	    "year": 2009,
    	    "income": 23.5,
    	    "expenses": 21.1
    	  }, {
    	    "year": 2010,
    	    "income": 26.2,
    	    "expenses": 30.5
    	  }, {
    	    "year": 2011,
    	    "income": 30.1,
    	    "expenses": 34.9
    	  }, {
    	    "year": 2012,
    	    "income": 29.5,
    	    "expenses": 31.1
    	  }, {
    	    "year": 2013,
    	    "income": 30.6,
    	    "expenses": 28.2,
    	    "dashLengthLine": 5
    	  }, {
    	    "year": 2014,
    	    "income": 34.1,
    	    "expenses": 32.9,
    	    "dashLengthColumn": 5,
    	    "alpha": 0.2,
    	    "additional": "(projection)"
    	  } ],
    	  "valueAxes": [ {
    	    "axisAlpha": 0,
    	    "position": "left"
    	  } ],
    	  "startDuration": 1,
    	  "graphs": [ {
    	    "alphaField": "alpha",
    	    "balloonText": "<span style='font-size:12px;'>[[title]] in [[category]]:<br><span style='font-size:20px;'>[[value]]</span> [[additional]]</span>",
    	    "fillAlphas": 1,
    	    "title": "Income",
    	    "type": "column",
    	    "valueField": "income",
    	    "dashLengthField": "dashLengthColumn"
    	  }, {
    	    "id": "graph2",
    	    "balloonText": "<span style='font-size:12px;'>[[title]] in [[category]]:<br><span style='font-size:20px;'>[[value]]</span> [[additional]]</span>",
    	    "bullet": "round",
    	    "lineThickness": 3,
    	    "bulletSize": 7,
    	    "bulletBorderAlpha": 1,
    	    "bulletColor": "#FFFFFF",
    	    "useLineColorForBulletBorder": true,
    	    "bulletBorderThickness": 3,
    	    "fillAlphas": 0,
    	    "lineAlpha": 1,
    	    "title": "Expenses",
    	    "valueField": "expenses",
    	    "dashLengthField": "dashLengthLine"
    	  } ],
    	  "categoryField": "year",
    	  "categoryAxis": {
    	    "gridPosition": "start",
    	    "axisAlpha": 0,
    	    "tickLength": 0
    	  },
    	  "export": {
    	    "enabled": true
    	  }
    	} );
    
    
    	var chartr = AmCharts.makeChart("chart-rentabilidad", {
    	  "theme": "light",
    	  "type": "gauge",
    	  "axes": [{
    	    "topTextFontSize": 20,
    	    "topTextYOffset": 70,
    	    "axisColor": "#31d6ea",
    	    "axisThickness": 1,
    	    "endValue": 100,
    	    "gridInside": true,
    	    "inside": true,
    	    "radius": "50%",
    	    "valueInterval": 10,
    	    "tickColor": "#67b7dc",
    	    "startAngle": -90,
    	    "endAngle": 90,
    	    "unit": "%",
    	    "bandOutlineAlpha": 0,
    	    "bands": [{
    	      "color": "#0080ff",
    	      "endValue": 100,
    	      "innerRadius": "105%",
    	      "radius": "170%",
    	      "gradientRatio": [0.5, 0, -0.5],
    	      "startValue": 0
    	    }, {
    	      "color": "#3cd3a3",
    	      "endValue": 0,
    	      "innerRadius": "105%",
    	      "radius": "170%",
    	      "gradientRatio": [0.5, 0, -0.5],
    	      "startValue": 0
    	    }]
    	  }],
    	  "arrows": [{
    	    "alpha": 1,
    	    "innerRadius": "35%",
    	    "nailRadius": 0,
    	    "radius": "170%"
    	  }]
    	});
    
    	setInterval(randomValue, 2000);
    
    	// set random value
    	function randomValue() {
    	  var value = 34;
    	  chartr.arrows[0].setValue(value);
    	  chartr.axes[0].setTopText(value + " %");
    	  // adjust darker band to new value
    	  chartr.axes[0].bands[1].setEndValue(value);
    	}
    
    </script>
    @endif
@endsection