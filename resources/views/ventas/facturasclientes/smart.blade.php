@section('content-width', 's12')
@section('form-title', 'Facturas de Clientes')

@section('header-bottom')
	@parent
	<script type="text/javascript">
		var clientes_js = '{{ $js_clientes ?? '' }}';
    	var proyectos_js = '{{ $js_proyectos ?? '' }}';
    	var sucursales_js = '{{ $js_sucursales ?? '' }}';
    </script>
	{{ HTML::script(asset('js/ventas/facturasclientes.js')) }}
@endsection

@section('form-content')
	{{ Form::setModel($data) }}
	<div class="row">
		<div class="col-lg-8 row">
    		<div class="form-group col-md-6">
    			{{Form::cSelect('* Empresa','fk_id_empresa', $empresas ?? [])}}
    		</div>
    		<div class="form-group col-md-6">
    			{{Form::cSelect('* Cliente','fk_id_socio_negocio', $clientes ?? [], ['data-url'=>companyAction('HomeController@index').'/sociosnegocio.sociosnegocio/api'])}}
    		</div>
    		
    		<div class="form-group col-md-6">
    			{{Form::cSelect('* Proyecto','fk_id_proyecto', $proyectos ?? [], ['data-url'=>companyAction('HomeController@index').'/proyectos.proyectos/api'])}}
    		</div>
    		<div class="form-group col-md-6">
    			{{Form::cSelect('* Sucursal','fk_id_sucursal', $sucursales ?? [], ['data-url'=>companyAction('HomeController@index').'/administracion.sucursales/api'])}}
    		</div>
    	
    		<div class="form-group col-md-5">
    			{{Form::cSelect('* Uso CFDI','fk_id_uso_cfdi', $usoscfdi ?? [])}}
    		</div>
    		<div class="form-group col-md-4">
    			{{Form::cSelect('* Metodo Pago','fk_id_metodo_pago', $metodospago ?? [])}}
    		</div>
    		<div class="form-group col-md-3">
    			{{Form::cSelect('* Forma Pago','fk_id_forma_pago', $formaspago ?? [])}}
    		</div>
    		
    		<div class="form-group col-md-5">
    			{{Form::cSelect('* Condicion Pago','fk_id_condicion_pago', $condicionespago ?? [])}}
    		</div>
    		<div class="form-group col-md-4">
    			{{Form::cSelect('* Moneda','fk_id_moneda', $monedas ?? [])}}
    		</div>
    		<div class="form-group col-md-3">
    			{{Form::cText('* Tipo Cambio','tipo_cambio',['readonly'=>true])}}
    		</div>
		</div>
		
		<div class="card col-lg-4 row ml-3 mt-3">
			<div class="card-header row">
				<h5 class="col-md-12 text-center">Cfdi Relacionados</h5>
    			<div class="form-group col-md-6">
        			{{Form::cSelect('* Tipo Relacion','fk_id_tipo_relacion', $tiposrelacion ?? [])}}
        		</div>
        		<div class="form-group col-md-6">
        			{{Form::cSelect('* Factura','fk_id_factura_relacion', $facturasrelacionadas ?? [],['class'=>'select2'])}}
        		</div>
        		<div class="form-group col-md-12 my-2">
					<div class="sep sepBtn">
						<button id="agregar-contacto" class="btn btn-primary btn-large btn-circle" data-placement="bottom" data-delay="100" data-tooltip="Agregar" data-toggle="tooltip" data-action="add" title="Agregar" type="button"><i class="material-icons">add</i></button>
					</div>
				</div>
        	</div>
        	<div class="card-body row table-responsive">
        		<table class="table highlight mt-3" id="tContactos">
					<thead>
						<tr>
							<th>Tipo Relacion</th>
							<th>Factura</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
					@if(isset($data->relaciones)) 
						@foreach($data->relaciones->where('eliminar',0) as $key=>$detalle)
						<tr>
							<td>
								{!! Form::hidden('contactos['.$key.'][id_contacto]',$detalle->id_contacto,['class'=>'id_contacto']) !!}
								{{$detalle->tipocontacto->tipo_contacto}}
								{!! Form::hidden('contactos['.$key.'][fk_id_tipo_contacto]',$detalle->fk_id_tipo_contacto,['class'=>'fk_id_tipo_contacto']) !!}
							</td>
							<td>
								{{$detalle->nombre}}
								{!! Form::hidden('contactos['.$key.'][nombre]',$detalle->nombre) !!} 
							</td>
					
							<td><button class="btn is-icon text-primary bg-white" type="button" data-delay="50" onclick="borrarContacto(this)"> <i class="material-icons">delete</i></button></td>
						</tr>
						@endforeach
					@endif
					</tbody>
				</table>
        	</div>
		</div>
	</div>
		
	<div class="card z-depth-1-half mt-3">
		<div class="card-header">
			<div class="mb-0 pb-0">
    			<ul class="nav nav-pills nav-justified">
    				<li class="nav-item"><a class="nav-link active"  role="tab" data-toggle="tab"  href="#concepto">Concepto</a></li>
    				<li class="nav-item"><a class="nav-link" role="tab" data-toggle="tab"  href="#pedido">Pedido</a></li>
    				<li class="nav-item"><a class="nav-link" role="tab" data-toggle="tab"  href="#entrega">Entregas</a></li>
    				<li class="nav-item"><a class="nav-link" role="tab" data-toggle="tab"  href="#receta">Recetas</a></li>
    				<li class="nav-item"><a class="nav-link" role="tab" data-toggle="tab"  href="#requisicion">Requisiciones</a></li>
    			</ul>
    		</div>
    		<div class="tab-content">
    			<div  class="tab-pane active" id="concepto" role="tabpanel">
    				<div class="row py-2">
        				<div class="form-group col-md-6">
                			{{Form::cSelect('* Producto','fk_id_producto', $tiposrelaciones ?? [])}}
                		</div>
                		<div class="form-group col-md-3">
                			{{Form::cNumber('* Cantidad','cantidad')}}
                		</div>
                		<div class="form-group col-md-3">
                			{{Form::cNumber('* Precio Unitario','precio_unitario')}}
                		</div>
                		<div class="form-group col-md-3">
                			{{Form::cNumber('* Descuento','descuento')}}
                		</div>
                		<div class="form-group col-md-3">
                			{{Form::cSelect('* Impuesto','fk_id_impuesto', $tiposrelaciones ?? [])}}
                		</div>
                		<div class="form-group col-md-3">
                			{{Form::cText('Pedimento','pedimento')}}
                		</div>
                		<div class="form-group col-md-3">
                			{{Form::cText('Cuenta Predial','cuenta_predial')}}
                		</div>
                		<div class="form-group col-md-12 my-2">
                			<div class="sep sepBtn">
                				<button id="agregar-concepto" class="btn btn-primary btn-large btn-circle" data-placement="bottom" data-delay="100" data-tooltip="Agregar" data-toggle="tooltip" data-action="add" title="Agregar" type="button"><i class="material-icons">add</i></button>
                			</div>
                		</div>
                	</div>
    			</div><!-- Fin TAB -->
    			
    			<div  class="tab-pane" id="pedido" role="tabpanel">
    				<div class="row py-2">
                		
                		<div class="form-group col-md-12 my-2">
                			<div class="sep sepBtn">
                				<button id="agregar-pedido" class="btn btn-primary btn-large btn-circle" data-placement="bottom" data-delay="100" data-tooltip="Agregar" data-toggle="tooltip" data-action="add" title="Agregar" type="button"><i class="material-icons">add</i></button>
                			</div>
                		</div>
                	</div>
    			</div><!-- Fin TAB -->
    			
    			<div  class="tab-pane" id="entrega" role="tabpanel">
    				<div class="row py-2">
                		
                		<div class="form-group col-md-12 my-2">
                			<div class="sep sepBtn">
                				<button id="agregar-entrega" class="btn btn-primary btn-large btn-circle" data-placement="bottom" data-delay="100" data-tooltip="Agregar" data-toggle="tooltip" data-action="add" title="Agregar" type="button"><i class="material-icons">add</i></button>
                			</div>
                		</div>
                	</div>
    			</div><!-- Fin TAB -->
    			
    			<div  class="tab-pane" id="receta" role="tabpanel">
    				<div class="row py-2">
                		
                		<div class="form-group col-md-12 my-2">
                			<div class="sep sepBtn">
                				<button id="agregar-receta" class="btn btn-primary btn-large btn-circle" data-placement="bottom" data-delay="100" data-tooltip="Agregar" data-toggle="tooltip" data-action="add" title="Agregar" type="button"><i class="material-icons">add</i></button>
                			</div>
                		</div>
                	</div>
    			</div><!-- Fin TAB -->
    			
    			<div  class="tab-pane" id="requisicion" role="tabpanel">
    				<div class="row py-2">
                		
                		<div class="form-group col-md-12 my-2">
                			<div class="sep sepBtn">
                				<button id="agregar-requisicion" class="btn btn-primary btn-large btn-circle" data-placement="bottom" data-delay="100" data-tooltip="Agregar" data-toggle="tooltip" data-action="add" title="Agregar" type="button"><i class="material-icons">add</i></button>
                			</div>
                		</div>
                	</div>
    			</div><!-- Fin TAB -->
    		</div>
    	</div>
    	<div class="card-body row table-responsive">
    		<table class="table highlight mt-3" id="tContactos">
        		<thead>
    				<tr>
    					<th>Clave Producto</th>
    					<th>Codigo</th>
    					<th>Concepto</th>
    					<th>Unidad Medida</th>
    					<th>Cantidad</th>
    					<th>Precio Unitario</th>
    					<th>Descuento</th>
    					<th>Impuesto</th>
    					<th>Pedimento</th>
    					<th>Cuenta Predial</th>
    					<th>Importe</th>
    					<th></th>
    				</tr>
    			</thead>
    			<tbody>
    			@if(isset($data->detalle)) 
    				@foreach($data->detalle->where('eliminar',0) as $key=>$detalle)
    				<tr>
    					<td>
    						{!! Form::hidden('contactos['.$key.'][id_factura_detalle]',$detalle->id_factura_detalle,['class'=>'id_factura_detalle']) !!}
    						{{$detalle->claveproducto->clave_producto_servicio}}
    						{!! Form::hidden('contactos['.$key.'][fk_id_clave_producto_servicio]',$detalle->fk_id_clave_producto_servicio,['class'=>'fk_id_clave_producto_servicio']) !!}
    					</td>
    					<td>
    						{{$detalle->sku->sku}}
    						{!! Form::hidden('contactos['.$key.'][fk_id_sku]',$detalle->fk_id_sku) !!} 
    					</td>
    					<td>
    						{{$detalle->upc->descripcion}}<br>
    						{{$detalle->sku->descripcion}}
    					</td>
    					<td>
    						{{$detalle->unidadmedida->clave_unidad.' - '.$detalle->unidadmedida->descripcion}}
    					</td>
    					<td>
    						{{$detalle->cantidad}}
    					</td>
    					<td>
    						{{$detalle->precio_unitario}}
    					</td>
    					<td>
    						{{$detalle->descuento}}
    					</td>
    					<td>
    						{{$detalle->impuesto->impuesto}}
    					</td>
    					<td>
    						{{$detalle->pedimento}}
    					</td>
    					<td>
    						{{$detalle->cuenta_predial}}
    					</td>
    					<td>
    						{{$detalle->importe}}
    					</td>
    			
    					<td><button class="btn is-icon text-primary bg-white" type="button" data-delay="50" onclick="borrarContacto(this)"> <i class="material-icons">delete</i></button></td>
    				</tr>
    				@endforeach
    			@endif
    			</tbody>
    		</table>
    	</div>
    	<div class="card-footer">
    		<table class="table highlight mt-3 float-right w-25 text-right" id="tContactos">
    			<tbody>
    				<tr>
    					<th>SUBTOTAL</th>
    					<td>$ 11,000.00</td>
    					<td>&nbsp;</td>
    				</tr>
    				<tr>
    					<th>DESCUENTO</th>
    					<td>$ 1,000.00</td>
    					<td>&nbsp;</td>
    				</tr>
    				<tr>
    					<th>IMPUESTOS</th>
    					<td>&nbsp;</td>
    					<td>&nbsp;</td>
    				</tr>
    				<tr>
    					<th class="pl-4">IVA 16%</th>
    					<td>$ 1,600.00</td>
    					<td>&nbsp;</td>
    				</tr>
    				<tr>
    					<th class="pl-4">IEPS</th>
    					<td>$ 1,000.00</td>
    					<td>&nbsp;</td>
    				</tr>
    				<tr>
    					<th>TOTAL</th>
    					<td>$ 12,600.00</td>
    					<td>&nbsp;</td>
    				</tr>
    			</tbody>
    		</table>
    	</div>
	</div>
		
		<!--
		<div class="form-group col-md-4">
			{{Form::label('fecha_contrato','* Fecha de creación del contrato')}}
			{{Form::text('fecha_contrato',null,['id'=>'fecha_contrato',"class"=>'form-control datepicker'])}}
		</div>
		<div class="form-group col-md-4">
			{{Form::label('fecha_inicio_contrato','* Fecha inicio del contrato')}}
			{{Form::text('fecha_inicio_contrato',null,['id'=>'fecha_inicio_contrato',"class"=>'form-control datepicker'])}}
		</div>
		<div class="form-group col-md-4">
			{{Form::label('fecha_fin_contrato','* Fecha fin del contrato')}}
			{{Form::text('fecha_fin_contrato',null,['id'=>'fecha_fin_contrato',"class"=>'form-control datepicker'])}}
		</div>
        <div class="form-group col-md-3">
            {{Form::cText('Número de proyecto','numero_proyecto',['maxlength'=>'50'])}}
        </div>
		<div class="form-group col-md-3">
			{{Form::cText('Número de contrato','numero_contrato',['maxlength'=>'200'])}}
		</div>
		<div class="form-group col-md-3">
			{{Form::label('monto_adjudicado','Monto adjudicado')}}
            <i class="material-icons" data-toggle="tooltip" data-placement="top" title="Ej. 9999999999.00">help</i>
			{{Form::text('monto_adjudicado',isset($data->monto_adjudicado)?number_format($data->monto_adjudicado,2,'.',''):null,['id'=>'monto_adjudicado','class'=>'form-control','maxlength'=>'13'])}}
		</div>
		<div class="form-group col-md-3">
			{{Form::cSelectWithDisabled('* Clasificación','fk_id_clasificacion_proyecto',isset($clasificaciones)?$clasificaciones:[])}}
		</div>
		<div class="form-group col-md-3">
			{{Form::cText('Plazo','plazo')}}
		</div>
		<div class="form-group col-md-6">
			{{Form::cText('Representante legal','representante_legal',['maxlength'=>'200'])}}
		</div>
		<div class="form-group col-md-3">
			{{Form::cText('Número de fianza','numero_fianza',['maxlength'=>'60'])}}
		</div>
		<div class="form-group col-md-12 text-center">
            {{Form::cCheckboxSwitch('Activo','activo','1')}}
			{{--<div data-toggle="buttons">--}}
				{{--<label class="btn btn-secondary form-check-label {{ !empty($data->activo) || old('activo') ? 'active':''}}">--}}
					{{--{{Form::checkbox('activo',true,old('activo'),['id'=>'activo',Route::currentRouteNamed(currentRouteName('show'))?'disabled':''])}}--}}
					{{--Activo--}}
				{{--</label>--}}
			{{--</div>--}}
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