@section('content-width', 's12')
@section('header-bottom')
    @parent
    @if(!Route::currentRouteNamed(currentRouteName('index')))
    	{{ HTML::script(asset('js/inventarios/productos.js')) }}
    @endif
@endsection

@section('form-content')
{{ Form::setModel($data) }}
<div class="card container-fluid z-depth-1-half p-3 my-2">
    <div class="row">
    	<div class="col-sm-6 col-md-8 col-lg-9 row">
        	<div class="col-sm-12 col-md-6 col-lg-2">
        		<div class="form-group">
        			{{ Form::cSelect('Serie Sku', 'fk_id_serie_sku', $seriesku ?? [], !Route::currentRouteNamed(currentRouteName('create')) ? ['disabled'=>true] : ['data-url'=>companyAction('Administracion\SeriesSkusController@getSerie',['id'=>'?id'])]) }}
        		</div>
        	</div>
        	<div class="col-sm-12 col-md-6 col-lg-3">
        		<div class="form-group">
        			<i class="material-icons text-danger float-left" data-toggle="tooltip" data-placement="top" title="El numero de serie puede cambiar si otro usuario genero un sku antes de que se guardara este. Verificalo despues de guardarlo.">warning</i>
        			{{ Form::cText('Sku', 'sku', ['placeholder'=>'Ejemplo: SO-1922-09','disabled'=>true]) }}
        		</div>
        	</div>
        	<div class="col-sm-12 col-md-12 col-lg-7">
        		<div class="form-group">
        			{{ Form::cText('Descripcion Corta', 'descripcion_corta') }}
        		</div>
        	</div>
        	<div class="col-sm-12 col-md-6 col-lg-2">
        		{{ Form::cText('Presentacion', 'presentacion') }}
        	</div>
        	<div class="col-sm-12 col-md-6 col-lg-3">
        		<div class="form-group">
        			{{ Form::cSelect('Unidad Medida', 'fk_id_unidad_medida', $unidadmedida ?? []) }}
        		</div>
        	</div>
    	</div>
    	<div class="col-sm-6 col-md-4 col-lg-3 row">
        	<div class="col-sm-12">
        		<div class="form-group">
        			{{ Form::cCheckbox('Articulo de Venta', 'articulo_venta') }}
        		</div>
        	</div>
        	<div class="col-sm-12">
        		<div class="form-group">
        			{{ Form::cCheckbox('Articulo de Compra', 'articulo_compra') }}
        		</div>
        	</div>
        	<div class="col-sm-12">
        		<div class="form-group">
        			{{ Form::cCheckbox('Articulo de Inventario', 'articulo_inventario') }}
        		</div>
        	</div>
        	<div class="col-sm-12">
        		<div class="form-group">
        			{{ Form::cCheckbox('Maneja Lote', 'maneja_lote') }}
        		</div>
        	</div>
        </div>
    </div>
</div>

<!--/Inicio Tabs-->
<div id="detallesku" class="w-100 container-fluid z-depth-1-half mt-2 px-0">
    <div class="card" style="min-height: 555px">
        <div class="card-header text-center py-2">
        <h4>Informacion del producto</h4>
        <div class="divider my-2"></div>
        
        <ul id="clothing-nav" class="nav nav-pills nav-justified" role="tablist">
            <li class="nav-item">
            	<a class="nav-link active" role="tab" data-toggle="tab" href="#tab-general" id="general-tab" aria-controls="general" aria-expanded="true">General</a>
            </li>
            <li class="nav-item">
            	<a class="nav-link" role="tab" data-toggle="tab" href="#tab-upcs" id="upcs-tab" aria-controls="upcs" aria-expanded="true">Upc's</a>
            </li>
            <li class="nav-item">
            	<a class="nav-link" role="tab" data-toggle="tab" href="#tab-venta" id="venta-tab" aria-controls="venta" aria-expanded="true">Venta</a>
            </li>
            <li class="nav-item">
            	<a class="nav-link" role="tab" data-toggle="tab" href="#tab-compra" id="compra-tab" aria-controls="compra" aria-expanded="true">Compra</a>
            </li>
            <li class="nav-item">
            	<a class="nav-link" role="tab" data-toggle="tab" href="#tab-inventario" id="inventario-tab" aria-controls="inventario" aria-expanded="true">Inventario</a>
            </li>
            <li class="nav-item">
            	<a class="nav-link" role="tab" data-toggle="tab" href="#tab-planificacion" id="planificacion-tab" aria-controls="planificacion" aria-expanded="true">Planificacion</a>
            </li>
            <li class="nav-item">
            	<a class="nav-link" role="tab" data-toggle="tab" href="#tab-especificaciones" id="especificaciones-tab" aria-controls="especificaciones" aria-expanded="true">Especificaciones</a>
            </li>
        </ul>
        </div>
        
    <!-- Content Panel -->
    <div class="card-body">
        <div id="clothing-nav-content" class="tab-content">
            <div role="tabpanel" class="tab-pane fade show active" id="tab-general" aria-labelledby="general-tab">
            	<div class="row">
        	  		<div class="col-sm-12 col-md-6 col-lg-4">
                		<div class="form-group">
                			{{ Form::cSelect('Impuesto', 'fk_id_impuesto', $impuesto ?? []) }}
                		</div>
                	</div>
        	  		<div class="col-sm-12 col-md-6 col-lg-4">
                		<div class="form-group">
                			{{ Form::cSelect('Subgrupo', 'fk_id_subgrupo', $subgrupo ?? []) }}
                		</div>
                	</div>
                	<div class="col-sm-12 col-md-6 col-lg-4">
                		<div class="form-group">
                			{{ Form::cSelect('Familia', 'fk_id_familia', $familia ?? []) }}
                		</div>
                	</div>
                	<div class="col-sm-12 col-md-12 col-lg-6 row">
                		<div class="col-sm-12 col-md-4 col-lg-4">
                    		<div class="form-group">
                    			{{ Form::cCheckboxBtn('Estatus', 'Activo', 'activo', $data['activo'] ?? null, 'Inactivo') }}
                    		</div>
                    	</div>
                    	<div class="col-sm-6 col-md-4 col-lg-4">
                    		<div class="form-group">
                    			{{ Form::cText('Desde', 'activo_desde') }}
                    		</div>
                    	</div>
                    	<div class="col-sm-6 col-md-4 col-lg-4">
                    		<div class="form-group">
                    			{{ Form::cText('Hasta', 'activo_hasta') }}
                    		</div>
                    	</div>
                	</div>
            	</div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="tab-upcs" aria-labelledby="upcs-tab">
            	<div class="row">
            		<div class="card col-sm-12 mb-3 p-0">
            			<div class="card-header">
            				<h4>Relacion Sku - Upc's</h4>
            				<p>Aquí puedes relacionar los codigos de barra correspondientes al Sku</p>
        					<div class="row">
        						<div class="col-sm-8 form-group">
    								{{ Form::cText('Upc', 'upc') }}
        						</div>
        						<div class="col-sm-4 form-group">
    								{{ Form::cText('Cantidad', 'cantidad') }}
        						</div>
        					</div>
                			<div class="col-sm-12 text-center my-3">
            					<div class="sep sepBtn">
            						<button class="btn btn-primary btn-large btn-circle" data-position="bottom" data-delay="50" data-toggle="Agregar" title="Agregar" type="button"><i class="material-icons">add</i></button>
            					</div>
            				</div>
            			</div>
        				<div class="card-body">
        					<table id="upcs" class="table table-responsive table-hover">
        						<thead>
        							<tr>
        								<th>#</th>
        								<th>Upc</th>
        								<th>Nombre Comercial</th>
        								<th>Descripcion</th>
        								<th>Laboratorio</th>
        								<th>Cantidad</th>
        								<th></th>
        							</tr>
        						</thead>
        						<tbody>
        							<tr>
        								<th scope="row">1</th>
        								<td>23454643140</td>
        								<td>Tempra Tabletas C/40</td>
        								<td>Paracetamol 250 mg Caja con 40 tabletas </td>
        								<td>BRISTOL-MYERS SQUIBB DE MEXICO, S. DE R. L. DE C.V.</td>
        								<td>1</td>
        								<td><a href="#" data-toggle="Eliminar" data-placement="top" title="Eliminar"><i class="material-icons">delete</i></a></td>
        							</tr>
        							<tr>
        								<th scope="row">2</th>
        								<td>23454643120</td>
        								<td>Tempra Tabletas C/20</td>
        								<td>Paracetamol 250 mg Caja con 20 tabletas</td>
        								<td>BRISTOL-MYERS SQUIBB DE MEXICO, S. DE R. L. DE C.V.</td>
        								<td>2</td>
        								<td><a href="#" data-toggle="Eliminar" data-placement="top" title="Eliminar"><i class="material-icons">delete</i></a></td>
        							</tr>
        							<tr>
        								<th scope="row">3</th>
        								<td>23454643110</td>
        								<td>Tempra Tabletas C/10</td>
        								<td>Paracetamol 250 mg Caja con 10 tabletas</td>
        								<td>BRISTOL-MYERS SQUIBB DE MEXICO, S. DE R. L. DE C.V.</td>
        								<td>4</td>
        								<td><a href="#" data-toggle="Eliminar" data-placement="top" title="Eliminar"><i class="material-icons">delete</i></a></td>
        							</tr>
        						</tbody>
        					</table>
        				</div>
            		</div>
        		</div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="tab-venta" aria-labelledby="venta-tab">
            	<div class="row">
    	  			<div class="form-group col-sm-12 col-md-6 col-lg-3">
            			{{ Form::cSelect('Unidad Medida Venta', 'fk_id_unidad_medida_venta', $unidadmedida ?? []) }}
            		</div>
    	  		</div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="tab-compra" aria-labelledby="compra-tab">
            	<div class="row">
    	  			<div class="form-group col-sm-12 col-md-6 col-lg-3">
            			{{ Form::cSelect('Proveedor Predeterminado', 'fk_id_proveedor', $sociosnegocio ?? []) }}
            		</div>
    	  			<div class="form-group col-sm-12 col-md-6 col-lg-3">
            			{{ Form::cSelect('Unidad Medida Compra', 'fk_id_unidad_medida_compra', $unidadmedida ?? []) }}
            		</div>
    	  		</div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="tab-inventario" aria-labelledby="inventario-tab">
            	<div class="row">
    	  			<div class="form-group col-sm-12 col-md-6 col-lg-2">
                		{{ Form::cText('Necesario', 'necesario',['placeholder'=>'Ejm: 40']) }}
                	</div>
                	<div class="form-group col-sm-12 col-md-6 col-lg-2">
                		{{ Form::cText('Minimo', 'minimo',['placeholder'=>'Ejm: 10']) }}
                	</div>
    	  			<div class="form-group col-sm-12 col-md-6 col-lg-2">
                		{{ Form::cText('Maximo', 'maximo',['placeholder'=>'Ejm: 90']) }}
                	</div>
                	<div class="form-group col-sm-12 col-md-6 col-lg-2">
            			{{ Form::cSelect('Metodo Valoracion', 'fk_id_metodo_valoracion', $metodovaloracion ?? ['Costos estimados','Costo estandar','Costos promedio','PEPS','UEPS','Lotes espec�fico']) }}
            		</div>
    	  		</div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="tab-planificacion" aria-labelledby="planificacion-tab">
            	<div class="row">
    	  			<div class="form-group col-sm-12 col-md-6 col-lg-2">
            			{{ Form::cText('Punto Reorden', 'punto_reorden',['placeholder'=>'Ejm: 15']) }}
            		</div>
            		<div class="form-group col-sm-12 col-md-6 col-lg-2">
            			{{ Form::cSelect('Intervalo del periodo', 'fk_id_intervalo', $intervaloperiodo?? ['Diario','Semanal','Quincenal','Menual','Trimestral','Semestral']) }}
            		</div>
            		<div class="form-group col-sm-12 col-md-6 col-lg-2">
            			{{ Form::cText('Cantidad minima por periodo', 'minima_periodo',['placeholder'=>'Ejm: 5']) }}
            		</div>
    	  			<div class="form-group col-sm-12 col-md-6 col-lg-2">
                		{{ Form::cText('Tiempo lead (Días)', 'tiempo_lead',['placeholder'=>'Ejm: 7']) }}
                	</div>
                	<div class="form-group col-sm-12 col-md-6 col-lg-2">
                		{{ Form::cText('Días de tolerancia', 'dias_tolerancia',['placeholder'=>'Ejm: 3']) }}
                	</div>
    	  		</div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="tab-especificaciones" aria-labelledby="especificaciones-tab">
            	<div class="row">
        			<div class="col-sm-6 form-group">
                		{{ Form::cTextArea('Descripcion', 'descripcion') }}
                	</div>
                	<div class="col-sm-6 form-group">
                		{{ Form::cTextArea('Descripcion de Cenefas', 'descripcion_cenefas') }}
                	</div>
                	<div class="col-sm-6 form-group">
                		{{ Form::cTextArea('Descripcion en Ticket', 'descripcion_ticket') }}
                	</div>
                	<div class="col-sm-6 form-group">
                		{{ Form::cTextArea('Descripcion en Rack', 'descripcion_rack') }}
                	</div>
                	<div class="col-sm-6 form-group">
                		{{ Form::cTextArea('Descripcion Cuadro Basico Nacional', 'descripcion_cbn') }}
                	</div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Content Panel -->
	</div>
</div>
<!--/Fin Tabs-->
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