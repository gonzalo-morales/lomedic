@section('header-top')
	<link rel="stylesheet" href="{{ asset('vendor/vanilla-datatables/vanilla-dataTables.css') }}">
@endsection
@section('header-bottom')
	@parent
	<script src="{{ asset('vendor/vanilla-datatables/vanilla-dataTables.js') }}"></script>
	<script type="text/javascript" src="{{ asset('js/ordenes_compras.js') }}"></script>
@endsection

@section('content-width', 's12')

@section('form-content')
{{ Form::setModel($data) }}
<div class="row">
	<div class="form-group col-md-3 col-sm-12">
		{{ Form::label('fk_id_socio_negocio', '* Proveedor a surtir') }}
		{!! Form::select('fk_id_socio_negocio',isset($proveedores)?$proveedores:[],null,['id'=>'fk_id_socio_negocio','class'=>'form-control select2','style'=>'width:100%']) !!}
		{{ $errors->has('fk_id_socio_negocio') ? HTML::tag('span', $errors->first('fk_id_socio_negocio'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
	<div class="form-group col-md-3 col-sm-12">
		{{ Form::label('fk_id_empresa', 'Otra empresa realiza la compra') }}
		<div class="input-group">
			<span class="input-group-addon">
				<input type="checkbox" id="otra_empresa">
			</span>
			{!! Form::select('fk_id_empresa',isset($companies)?$companies:[],null,['id'=>'fk_id_empresa','class'=>'form-control','style'=>'width:100%','disabled']) !!}
		</div>
		{{ $errors->has('fk_id_empresa') ? HTML::tag('span', $errors->first('fk_id_empresa'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
	<div class="form-group text-center col-md-3 col-sm-6">
		{{ Form::label('', 'Días/Fecha') }}
		<div class="input-group">
			{!! Form::text('tiempo_entrega', null,['class'=>'form-control','disabled','placeholder'=>'Días para la entrega']) !!}
			{!! Form::text('fecha_estimada_entrega', null,['class'=>'form-control','disabled','placeholder'=>'Fecha estimada']) !!}
		</div>
	</div>
	<div class="form-group col-md-3 col-sm-6">
		{{ Form::label('fk_id_sucursal', '* Sucursal de entrega') }}
		{!! Form::select('fk_id_sucursal',isset($sucursales)?$sucursales:[],null,['id'=>'fk_id_sucursal_','class'=>'form-control select2','style'=>'width:100%']) !!}
		{{ $errors->has('fk_id_sucursal') ? HTML::tag('span', $errors->first('fk_id_sucursal'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
	<div class="form-group col-md-3 col-sm-6">
		{{ Form::label('fk_id_condicion_pago', '* Condición de pago') }}
		{!! Form::select('fk_id_condicion_pago',isset($condicionesPago)?$condicionesPago:[],null,['id'=>'fk_id_condicion_pago','class'=>'form-control select2','style'=>'width:100%']) !!}
		{{ $errors->has('fk_id_condicion_pago') ? HTML::tag('span', $errors->first('fk_id_condicion_pago'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
	<div class="form-group col-md-3 col-sm-6">
		{{ Form::label('fk_id_tipo_entrega', '* Tipo de entrega') }}
		{!! Form::select('fk_id_tipo_entrega',isset($tiposEntrega)?$tiposEntrega:[],null,['id'=>'fk_id_tipo_entrega','class'=>'form-control select2','style'=>'width:100%']) !!}
		{{ $errors->has('fk_id_tipo_entrega') ? HTML::tag('span', $errors->first('fk_id_tipo_entrega'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<h3>Detalle de la orden</h3>
		<div class="card">
			<div class="card-header">
				<fieldset name="detalle-form" id="detalle-form">
					<div class="row">
						<div class="form-group input-field col-md-3 col-sm-6">
							{{Form::label('fk_id_sku','* SKU')}}
							{!!Form::select('fk_id_sku',[],null,['id'=>'fk_id_sku','class'=>'form-control','style'=>'width:100%','data-url'=>companyAction('Inventarios\SkusController@obtenerSkus')])!!}
						</div>
						<div class="form-group input-field col-md-3 col-sm-6">
							{{Form::label('fk_id_upc','UPC')}}
							<div class="input-group">
								<span class="input-group-addon">
									<input type="checkbox" id="activo_upc">
								</span>
								{!! Form::select('fk_id_upc',[],null,['id'=>'fk_id_upc','disabled',
								'data-url'=>companyAction('Inventarios\UpcsController@obtenerUpcs',['id'=>'?id']),
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
			<div class="card-body">
				<table id="productos" class="table-responsive highlight" data-url="{{companyAction('Compras\SolicitudesController@store')}}"
					   data-delete="{{companyAction('Compras\DetalleSolicitudesController@destroyMultiple')}}"
					   data-impuestos="{{companyAction('Administracion\ImpuestosController@obtenerImpuestos')}}"
					   data-porcentaje="{{companyAction('Administracion\ImpuestosController@obtenerPorcentaje',['id'=>'?id'])}}">
					<thead>
					<tr>
						<th id="idsku">SKU</th>
						<th id="idupc">Código de Barras</th>
						<th id="nombrecomercial">Nombre comercial</th>
						<th id="descripcion">Descripción</th>
						<th id="idcliente">Cliente</th>
						<th id="idproyecto" >Proyecto</th>
						<th id="fechanecesario" >Fecha límite</th>
						<th>Cantidad</th>
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
									{!! Form::hidden('detalles['.$detalle->id_solicitud_detalle.'][fk_id_upc]',$detalle->fk_id_upc) !!}
									{{$detalle->upc->descripcion}}
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
                                                'class'=>'detalle_select','style'=>'width:100%'])
                                        !!}
									@endif
								</td>
								<td>
									@if (!Route::currentRouteNamed(currentRouteName('edit')))
										{!! Form::hidden('detalles['.$detalle->id_solicitud_detalle.'][cantidad]',$detalle->cantidad) !!}
										{{$detalle->cantidad}}
									@else
										{!! Form::text('detalles['.$detalle->id_solicitud_detalle.'][cantidad]',$detalle->cantidad,
                                        ['class'=>'form-control cantidad',
                                        'id'=>'cantidad'.$detalle->id_solicitud_detalle,
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
                                                $detalle->fk_id_impuesto,['class'=>'detalle_select','style'=>'width:100%','id'=>'fk_id_impuesto'.$detalle->id_solicitud_detalle,
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
                                        ,['class'=>'form-control precio_unitario','onkeypress'=>'total_producto_row('.$detalle->id_solicitud_detalle.',"old")',
                                        'id'=>'precio_unitario'.$detalle->id_solicitud_detalle]) !!}
									@endif
								</td>
								<td>
								@if (!Route::currentRouteNamed(currentRouteName('edit')))
									{!! Form::hidden('detalles['.$detalle->id_solicitud_detalle.'][total]',$detalle->total) !!}
									{{number_format($detalle->total,2,'.','')}}
								@else
									{!! Form::text('detalles['.$detalle->id_solicitud_detalle.'][total]',number_format($detalle->total,2,'.','')
                                    ,['class'=>'form-control','id'=>'total'.$detalle->id_solicitud_detalle,'readonly'])!!}
								@endif
								<td>
									{{--Si se va a editar, agrega el botón para "eliminar" la fila--}}
									@if(Route::currentRouteNamed(currentRouteName('edit')) && $data->fk_id_estatus_solicitud == 1)
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
<div class="form-group col-md-2 col-sm-6 float-right">
	{{ Form::label('total_orden', 'Total de la orden') }}
	{!! Form::text('total_orden', null,['class'=>'form-control','disabled','placeholder'=>'Total']) !!}
</div>
{{--<div class="row">--}}
	{{--{{ dump($proveedores) }}--}}

	{{--<div class="input-field col s3 m3">--}}
		{{--{{ Form::select('fk_id_empresa', ($proveedores ?? []), null, ['id'=>'fk_id_empresa','class'=>'validate']) }}--}}
		{{--{{ Form::label('fk_id_empresa', 'Empresa:') }}--}}
		{{--{{ $errors->has('fk_id_empresa') ? HTML::tag('span', $errors->first('fk_id_empresa'), ['class'=>'help-block deep-orange-text']) : '' }}--}}
	{{--</div>--}}
	{{--<div class="input-field col s3 m3">--}}
		{{--<input type="text" name="" id="">--}}
		{{--<label>dfg</label>--}}
	{{--</div>--}}
	{{--<div class="input-field col s3 m3">--}}
		{{--<input type="text" name="" id="">--}}
		{{--<label>dfg</label>--}}
	{{--</div>--}}
	{{--<div class="input-field col s3 m3">--}}
		{{--<input type="text" name="" id="">--}}
		{{--<label>dfg</label>--}}
	{{--</div>--}}


	{{--<div class="input-field col s3 m3">--}}

{{--<select id="proveedor" class="combo">--}}
	{{--<option value="-1">--Seleccione una opción--</option>--}}
	{{--<option value="274">ABARROTERA LAGUNITAS S.A DE C.V</option>--}}
	{{--<option value="38">ABASTECEDORA  DE INSUMOS PARA  LA SALUD SA DE CV</option>--}}
	{{--<option value="740">ABASTECEDORA AGSA S.A DE C.V</option>--}}
	{{--<option value="723">ABASTO BASICO S.A DE C.V</option>--}}
	{{--<option value="380">ABASTOS MEDICAMENTOS </option>--}}
	{{--<option value="253">ABBOTT LABORATORIES DE MEXICO S.A. DE C.V.</option>--}}
	{{--<option value="312">ABBVIE FARMACEUTICOS S.A DE C.V</option>--}}
	{{--<option value="361">ABSORVENTES GOMAR S.A. DE C.V.</option>--}}
	{{--<option value="178">ACCORD FARMA S.A DE C.V.</option>--}}
	{{--<option value="423">ADELA GALLEGOS VALDEPINOS</option>--}}
	{{--<option value="16">ADILMERS DE R.L.  DE C.V.</option>--}}
	{{--<option value="560">ADMINISTRACIONES EMPRESARIALES LA TAPATIA SA DE CV</option>--}}
	{{--<option value="558">ADMINISTRACIONES MEXICO AMERICA LATINA SA DE CV</option>--}}
	{{--<option value="17">AG GRUPO FARMACEUTICO S.A DE C.V</option>--}}
	{{--<option value="18">AGNEVAL MEDICO DENTAL S.A DE C.V</option>--}}
	{{--<option value="319">ALBERTO RODIGUEZ PEREZ</option>--}}
	{{--<option value="636">ALDEN VALLEJO, S.A. DE C.V.</option>--}}
	{{--<option value="1">ALEACIONES DENTALES ZEYCO S.A. DE C.V.</option>--}}
	{{--<option value="587">ALEJANDRO EPIGMENIO BAUTISTA</option>--}}
	{{--<option value="462">ALEX PERFECTO DELGADILLO VAZQUEZ</option>--}}
	{{--<option value="700">ALFA WASSERMANN, S.A. DE C.V.</option>--}}
	{{--<option value="611">ALFREDO ORTEGA SAUCEDO</option>--}}
	{{--<option value="783">ALGUIEN</option>--}}
	{{--<option value="784">ALGUIEN ALGUIEN</option>--}}
	{{--<option value="510">ALICIA CLEOFAS DIAZ GUADARRAMA</option>--}}
	{{--<option value="395">ALMACEN DE DROGAS DE LA PAZ S.A DE C.V</option>--}}
	{{--<option value="683">ALMACEN DE DROGAS S.A DE C.V</option>--}}
	{{--<option value="767">ALVARTIS PHARMA S.A DE C.V</option>--}}
	{{--<option value="276">AM, BEOGY  S.A DE C.V.</option>--}}
	{{--<option value="19">AMBIDERM S.A DE C.V</option>--}}
	{{--<option value="187">AMERICA MEDICA Y ASOCIADOS S.A. DE C.V.</option>--}}
	{{--<option value="271">AMERICAN HEALTHCARE PRODUCTS S.A DE C.V</option>--}}
	{{--<option value="608">ANA ISABEL PONCE MARTINEZ</option>--}}
	{{--<option value="20">ANA MARIA CARRILLO DIAZ</option>--}}
	{{--<option value="755">ANDRES MOCTEZUMA GONZALEZ</option>--}}
	{{--<option value="682">ANDRES SAUCEDO SANCHEZ</option>--}}
	{{--<option value="21">ANESTESICOS DE JALISCO S.A. DE C.V.</option>--}}
	{{--<option value="366">ANTIBIOTICOS DE MEXICO S.A. DE C.V.</option>--}}
	{{--<option value="576">APCOSA S.A. DE C.V.</option>--}}
	{{--<option value="378">APOFAR S.A. DE C.V.</option>--}}
	{{--<option value="179">APOPHARMA S.A. DE C.V.</option>--}}
	{{--<option value="614">ARELI RAQUEL VARGAS VILLANUEVA</option>--}}
	{{--<option value="645">ARKANUM S.A. DE  C.V.</option>--}}
	{{--<option value="249">ARLEX DE MEXICO S.A DE C.V</option>--}}
	{{--<option value="297">ARMSTRONG LABORATORIOS DE MEXICO S.A DE C.V</option>--}}
	{{--<option value="763">ARTE MANUFACTURA Y COMERCIO S.A DE C.V</option>--}}
	{{--<option value="22">ASOCIACION JALISCIENSE DE ACCION CONTRA LA LEPRA A.C.</option>--}}
	{{--<option value="180">ASOFARMA DE MEXICO SA DE CV</option>--}}
	{{--<option value="214">ASOKAM S.A. DE C.V.</option>--}}
	{{--<option value="220">ASPEN LABS, S.A. DE C.V.</option>--}}
	{{--<option value="597">ASTRA ZENECA S.A. DE C.V.</option>--}}
	{{--<option value="223">ASTRID CLAUSEN ASCENCIO</option>--}}
	{{--<option value="3">ATLANTIS S.A. DE C.V.</option>--}}
	{{--<option value="465">ATRYA LAB S.A. DE C.V.</option>--}}
	{{--<option value="704">AUREA QUIMICA, MEXICANA, S.A. DE C.V.</option>--}}
	{{--<option value="543">AUSTREBERTO DOMINGUEZ SANCHEZ</option>--}}
	{{--<option value="506">AUTOMOTRIZ TOLLOCAN S.A. DE C.V.</option>--}}
	{{--<option value="690">AUTOMUNDO, S.A. DE C.V.</option>--}}
	{{--<option value="456">AUTOTRANSPORTES DE CARGA TRESGUERRAS SA DE CV</option>--}}
	{{--<option value="595">AVANTE MILENIO, S.A. DE C.V.</option>--}}
	{{--<option value="464">AXA SEGUROS, S.A. DE C.V.</option>--}}
	{{--<option value="528">BALESSA SA DE CV</option>--}}
	{{--<option value="735">BALSAS DIVISION DENTAL S.A DE C.V</option>--}}
	{{--<option value="292">BASURAMA S DE RL DE CV</option>--}}
	{{--<option value="751">BAYER DE MEXICO S.A DE C.V</option>--}}
	{{--<option value="430">BERTHA ALICIA PEREZ ARELLANO</option>--}}
	{{--<option value="181">BEXEL GROUP, S.A DE C.V.</option>--}}
	{{--<option value="414">BIO REG PHARMACEUTICALS SA DE CV</option>--}}
	{{--<option value="398">BIODISE?O DE MEXICO S.A. DE C.V.</option>--}}
	{{--<option value="399">BIOGARMED SA DE CV</option>--}}
	{{--<option value="731">BIOMATERIALES DE MEXICO S.A DE C.V</option>--}}
	{{--<option value="182">BIOMEDICA MEXICANA S.A. DE C.V.</option>--}}
	{{--<option value="23">BIOMEP S.A DE C.V</option>--}}
	{{--<option value="153">BIOSKINCO, S.A. DE C.V.</option>--}}
	{{--<option value="486">BLANCA MARISOL LOPEZ MORAN</option>--}}
	{{--<option value="25">BODYLOGIC  S.A. DE C.V.</option>--}}
	{{--<option value="26">BOEHRINGER INGELHEIM MEXICO, S.A. DE C.V.</option>--}}
	{{--<option value="215">BOSTON MEDICAL DEVICE DE MEXICO S DE RL DE CV</option>--}}
	{{--<option value="697">BRISTOL MYERS SQUIBB DE MEXICO S.A. DE C.V.</option>--}}
	{{--<option value="229">BRT FARMACEUTICA INTEGRAL S.A DE C.V</option>--}}
	{{--<option value="703">BRUCE MEDICA INTERNACIONAL S.A. DE C.V.</option>--}}
	{{--<option value="639">BRULUAGSA, SA DE CV</option>--}}
	{{--<option value="518">BSN MEDICAL DC S.A DE C.V</option>--}}
	{{--<option value="217">BUFFINGTONS DE MEXICO S.A DE C.V</option>--}}
	{{--<option value="746">BUSCAR PROVEEDOR</option>--}}
	{{--<option value="28">BYP MEDICAL S.A. DE C.V.</option>--}}
	{{--<option value="640">CALIBRACION Y METROLOGIA INDUSTRIAL DE MEXICO, S.A. DE C.V.</option>--}}
	{{--<option value="439">CALIDRUX S.A. DE C.V.</option>--}}
	{{--<option value="275">CALZADO DE TRABAJO S.A DE C.V</option>--}}
	{{--<option value="353">CANAPHARMA S.A DE C.V</option>--}}
	{{--<option value="283">CARDETEX DE AGUASCALIENTES S.A. DE C.V.</option>--}}
	{{--<option value="600">CARLOS LOMELI BOLAÑOS</option>--}}
	{{--<option value="668">CARLOS MARTIN VARO BERRA</option>--}}
	{{--<option value="339">CARLOS NAFARRATE S.A. DE C.V_1</option>--}}
	{{--<option value="197">CASA MARZAM S.A. DE C.V.</option>--}}
	{{--<option value="236">CASA SABA S.A DE C.V</option>--}}
	{{--<option value="686">CELL PHARMA, S. DE R.L. DE C.V.</option>--}}
	{{--<option value="485">CENTRAL DE SUSPENSIONES  LAGO S.A. DE C.V.</option>--}}
	{{--<option value="373">CENTRO MEDICO DE TOLUCA S.A. DE C.V.</option>--}}
	{{--<option value="303">CHIESI MEXICO S.A. DE C.V.</option>--}}
	{{--<option value="672">CHRISTIAN ALEJANDRO SANCHEZ MENDOZA</option>--}}
	{{--<option value="754">CIENTIFICA VELAQUIN S.A. DE C.V.</option>--}}
	{{--<option value="500">CLASS CHEMICAL S.A. DE C.V.</option>--}}
	{{--<option value="29">COHMEDIC. S.A. DE C.V.</option>--}}
	{{--<option value="39">COMECO S.A DE C.V.</option>--}}
	{{--<option value="30">COMERCIAL ENTERPRISE S.A DE C.V</option>--}}
	{{--<option value="739">COMERCIAL GFC</option>--}}
	{{--<option value="170">COMERCIAL MEDICA DEL BAJIO </option>--}}
	{{--<option value="347">COMERCIAL MEDICA ONCOLOGICA S.A DE C.V.</option>--}}
	{{--<option value="618">COMERCIALIZADORA  MEDHOUSE S.A DE C.V</option>--}}
	{{--<option value="559">COMERCIALIZADORA ACAMBAY SA DE CV</option>--}}
	{{--<option value="534">COMERCIALIZADORA ANDEBU SA DE CV</option>--}}
	{{--<option value="536">COMERCIALIZADORA APOZOL SA DE CV</option>--}}
	{{--<option value="537">COMERCIALIZADORA COCOYOC SA DE CV</option>--}}
	{{--<option value="538">COMERCIALIZADORA COYUCA SA DE CV</option>--}}
	{{--<option value="40">COMERCIALIZADORA DANIEL´S</option>--}}
	{{--<option value="545">COMERCIALIZADORA DE CURACION Y REGISTROS MEDICOS S DE RL. DE CV</option>--}}
	{{--<option value="707">COMERCIALIZADORA DE INSUMOS X MÁS SALUD Y MÁS VIDA S.A DE C.V</option>--}}
	{{--<option value="666">COMERCIALIZADORA DE PRODUCTOS VE SA DE CV</option>--}}
	{{--<option value="532">COMERCIALIZADORA EVERUM SA DE CV</option>--}}
	{{--<option value="562">COMERCIALIZADORA FARALLON SA DE CV</option>--}}
	{{--<option value="717">COMERCIALIZADORA FARMACEUTICA DE CHIAPAS S.A PI DE C.V</option>--}}
	{{--<option value="355">COMERCIALIZADORA FARMACEUTICA DE CHIAPAS, S.A. DE C.V.</option>--}}
	{{--<option value="31">COMERCIALIZADORA FARMACEUTICA GAMMA S.A. DE C.V.</option>--}}
	{{--<option value="710">COMERCIALIZADORA FARMACEUTICA HISAO S.A DE C.V</option>--}}
	{{--<option value="561">COMERCIALIZADORA GARIBALDI CENTER SA DE CV</option>--}}
	{{--<option value="33">COMERCIALIZADORA JIQUILPAN S.A DE C.V</option>--}}
	{{--<option value="262">COMERCIALIZADORA KELLY S.A DE C.V.</option>--}}
	{{--<option value="34">COMERCIALIZADORA KVIM SA DE CV</option>--}}
	{{--<option value="375">COMERCIALIZADORA LUSSAR S.A.DE C.V.</option>--}}
	{{--<option value="615">COMERCIALIZADORA MARTINEZ CANTU S.A. DE C.V</option>--}}
	{{--<option value="688">COMERCIALIZADORA MEDICA ELITE SA DE CV</option>--}}
	{{--<option value="535">COMERCIALIZADORA PECAMI SA DE CV</option>--}}
	{{--<option value="35">COMERCIALIZADORA PHARMACEUTICA COMPHARMA S.A DE C.V.</option>--}}
	{{--<option value="749">COMERCIALIZADORA SALYMED SA DE CV</option>--}}
	{{--<option value="533">COMERCIALIZADORA TOPZONE SA DE CV</option>--}}
	{{--<option value="226">COMERCIALIZADORA VERYMICH S.A DE C.V.</option>--}}
	{{--<option value="4">COMERSA DE OCCIDENTE S.A. DE C.V.</option>--}}
	{{--<option value="42">COMISIONES Y REPRESENTACIONES MEDICAS S.A. DE C.V</option>--}}
	{{--<option value="273">COMPRA OPTIMA S.A DE C.V.</option>--}}
	{{--<option value="444">CONSULTORIA EN SEGURIDAD PRIVADA Y LIMPIEZA SA DE CV</option>--}}
	{{--<option value="270">CONTROLADORA DE FARMACIAS S.A.P.I. DE CV</option>--}}
	{{--<option value="265">CORPORATIVO DE SERVICIOS ROUS MEXIQUENSES S DE R.L DE C.V</option>--}}
	{{--<option value="622">CORPORATIVO MEDLI S.A DE C.V</option>--}}
	{{--<option value="738">CORPORINTER S.A DE C.V.</option>--}}
	{{--<option value="495">CORRUGADOS BELLAVISTA S.A. DE C.V.</option>--}}
	{{--<option value="712">COSTCO DE MEXICO S.A DE C.V</option>--}}
	{{--<option value="652">CRV LEA, SC</option>--}}
	{{--<option value="440">CSL BEHRING, S.A. DE C.V.</option>--}}
	{{--<option value="641">CVS S.A. DE C.V</option>--}}
	{{--<option value="43">CYRGOS COMERCIALIZADORA S.A. DE C.V.</option>--}}
	{{--<option value="298">D.F CASA MARZAM S.A. DE C.V.</option>--}}
	{{--<option value="227">DALTEM PROVEE NACIONAL S.A. DE C.V.</option>--}}
	{{--<option value="183">DALTEM PROVEE NORTE, S.A. DE C.V.</option>--}}
	{{--<option value="44">DALUX SA DE CV</option>--}}
	{{--<option value="490">DANIEL ALEJANDRO IBARRA JIMENEZ</option>--}}
	{{--<option value="569">DANIEL GONZALO RAMIREZ MERCADO</option>--}}
	{{--<option value="493">DANIEL OSNAYA PEREZ</option>--}}
	{{--<option value="335">DAVID CASTILLO VASQUEZ</option>--}}
	{{--<option value="344">DAVID ELIAS SANTOYO AVILA</option>--}}
	{{--<option value="441">DAVIS MEDICAL S.A. DE C.V.</option>--}}
	{{--<option value="598">DAYNA OROPEZA MORALES</option>--}}
	{{--<option value="445">DE LA CRUZ MAYORAL JOSE SERGIO</option>--}}
	{{--<option value="45">DEGASA. S.A. DE C.V.</option>--}}
	{{--<option value="657">DEGORTS CHEMICAL S.A. DE C.V.</option>--}}
	{{--<option value="435">DELOVAC, S. DE R.L. DE C.V.</option>--}}
	{{--<option value="750">DENTAL GOMEZ FARIAS SA DE CV</option>--}}
	{{--<option value="734">DENTAURUM DE MEXICO S.A DE C.V</option>--}}
	{{--<option value="184">DENTILAB S.A. DE C.V.</option>--}}
	{{--<option value="46">DEPOSITO DENTAL VILLA DE CORTES, S.A. DE C.V.</option>--}}
	{{--<option value="48">DETALLE Y DISTRIBUCIONES S.A. DE C.V.</option>--}}
	{{--<option value="320">DEWIMED S.A</option>--}}
	{{--<option value="517">DIAZ MUÑOZ FLORENTINA</option>--}}
	{{--<option value="49">DIBITER, S.A. DE C.V.</option>--}}
	{{--<option value="185">DIFARLEX, S.A. DE C.V.</option>--}}
	{{--<option value="389">DIMEXPRESS S. DE RL DE C.V.</option>--}}
	{{--<option value="553">DIMHOS ONLY S.A. DE C.V.</option>--}}
	{{--<option value="50">DINAMICA MEDICA S.A DE C.V.</option>--}}
	{{--<option value="453">DINASTIA CALDERON SA DE CV</option>--}}
	{{--<option value="51">DISTRIBUCION W S.A. DE C.V.</option>--}}
	{{--<option value="52">DISTRIBUCIONES MEDICAS FARMAYCO S.A. DE C.V.</option>--}}
	{{--<option value="667">DISTRIBUCIONESD MEDICAS DELPICA SA DE CV</option>--}}
	{{--<option value="673">DISTRIBUCIÓN ESPECIALIZADA DE MEDICAMENTOS, SA DE CV</option>--}}
	{{--<option value="696">DISTRIBUIDORA DE EQUIPO MEDICO ESPECIALIZADO, S.A. DE C.V.</option>--}}
	{{--<option value="53">DISTRIBUIDORA DE FARMACOS Y FRAGANCIAS, S.A DE C.V.</option>--}}
	{{--<option value="5">DISTRIBUIDORA DE HOSPITALES Y MEDICAMENTOS S DE RL DE C.V.</option>--}}
	{{--<option value="765">DISTRIBUIDORA DE MATERIAL DE CURACION VILLA GOMEZ S.A DE C.V</option>--}}
	{{--<option value="401">DISTRIBUIDORA ESPECIALIZADA DE MEDICAMENTOS KM SA DE CV</option>--}}
	{{--<option value="356">DISTRIBUIDORA FARMACEUTICA DE ALBA S.A. DE C.V.</option>--}}
	{{--<option value="250">DISTRIBUIDORA HECAR SA DE CV</option>--}}
	{{--<option value="54">DISTRIBUIDORA HUGOS SA DE CV</option>--}}
	{{--<option value="351">DISTRIBUIDORA INTERNACIONAL DE MEDICAMENTOS Y EQUIPO MEDICO SA DE CV</option>--}}
	{{--<option value="36">DISTRIBUIDORA JALOMA</option>--}}
	{{--<option value="307">DISTRIBUIDORA LA ABUNDANCIA S.A DE C.V</option>--}}
	{{--<option value="359">DISTRIBUIDORA LEVIC S. DE RL DE CV</option>--}}
	{{--<option value="55">DISTRIBUIDORA MEDICA M.G, S.A. DE C.V.</option>--}}
	{{--<option value="721">DISTRIBUIDORA MEDICA ZEUS</option>--}}
	{{--<option value="564">DISTRIBUIDORA TAMEX SA DE CV</option>--}}
	{{--<option value="627">DISTRIBUIDORA Y COMERCIALIZADORA OPCIONES FARMACEUTICAS S.A. DE C.V.</option>--}}
	{{--<option value="104">DISTROMED S.A. DE C.V.</option>--}}
	{{--<option value="653">DKT DE MEXICO S.A. DE C.V.</option>--}}
	{{--<option value="186">DRAGUER MEDICAL MEXICO S.A DE C.V</option>--}}
	{{--<option value="744">DROGAS TACUBA S.A DE C.V</option>--}}
	{{--<option value="57">DROGUERIA SAN ISIDRO, S.A DE C.V.</option>--}}
	{{--<option value="411">DROGUERIA Y FARMACIA EL GLOBO</option>--}}
	{{--<option value="206">DROGUEROS S.A. DE C.V.</option>--}}
	{{--<option value="566">DULCE MARÍA NATERAS ARMENDARIZ</option>--}}
	{{--<option value="708">ECOTECNIA AMBIENTAL SA DE CV</option>--}}
	{{--<option value="556">EDENRED MEXICO S.A DE C.V</option>--}}
	{{--<option value="248">EDIGAR S.A DE C.V</option>--}}
	{{--<option value="527">EDUARDO REYES ESTRADA</option>--}}
	{{--<option value="261">EL MERCADO HOSPITALARIO S.A. DE C.V.</option>--}}
	{{--<option value="594">EL SURTIDOR DE OBSERVATORIO, S.A. DE C.V.</option>--}}
	{{--<option value="58">ELECTRO PAPER S.A DE C.V.</option>--}}
	{{--<option value="294">ELECTROMECANICO DANY SA DE CV</option>--}}
	{{--<option value="662">ELENA RUIZ RODRIGUEZ</option>--}}
	{{--<option value="278">ELI LILLY Y COMPA?IA DE MEXICO S.A DE C.V</option>--}}
	{{--<option value="24">ELIZABETH MATA CELIS</option>--}}
	{{--<option value="56">EMIFARMA, S.A. DE C.V.</option>--}}
	{{--<option value="369">EMPAQUES Y SUMINISTROS ORZA SA DE CV</option>--}}
	{{--<option value="61">ENDOMEDICA S.A. DE C.V.</option>--}}
	{{--<option value="724">ENTORNO MEDICO S.C.</option>--}}
	{{--<option value="631">EQUILIBRIO FARMACEUTICO S.A DE C.V</option>--}}
	{{--<option value="503">EQUIPOS COMERCIALES HOLTON S.A. DE C.V.</option>--}}
	{{--<option value="207">EQUIPOS DE BIOMEDICINA DE MEXICO S.A DE C.V.</option>--}}
	{{--<option value="272">EQUIPOS MEDICOS VIZCARRA S.A</option>--}}
	{{--<option value="719">ERIKA ULLOA PONCE</option>--}}
	{{--<option value="62">ESIGAR QUIRURGICA S.A. DE C.V.</option>--}}
	{{--<option value="191">ESPECIALISTAS EN ESTERILIZACION Y ENVASE S.A DE C.V.</option>--}}
	{{--<option value="591">ESPECIFICOS STENDHAL S.A DE C.V.</option>--}}
	{{--<option value="695">ESTAFETA MEXICANA SA DE CV</option>--}}
	{{--<option value="581">ESTELA MARIN JIMENEZ</option>--}}
	{{--<option value="79">ESTERIPHARMA MEXICO S.A DE C.V</option>--}}
	{{--<option value="427">ESTHER HERNANDEZ RODRIGUEZ</option>--}}
	{{--<option value="310">ESTRATEGIA HOSPITALARIA S.A DE C.V</option>--}}
	{{--<option value="468">EUGENIO NIETO ZENTENO</option>--}}
	{{--<option value="64">EUROPEA DE DISTRIBUCIONES AVANZADAS, S.A. DE C.V.</option>--}}
	{{--<option value="694">EXFARMA S.A. DE C.V.</option>--}}
	{{--<option value="507">EXPRESS LOGISTICS RAMY</option>--}}
	{{--<option value="752">EYPRO S.A DE C.V</option>--}}
	{{--<option value="626">FARMA SANA SANA S.A. DE C.V.</option>--}}
	{{--<option value="603">FARMACENTER S.A. DE C.V.</option>--}}
	{{--<option value="388">FARMACEUTICA AGO S.A DE C.V.</option>--}}
	{{--<option value="463">FARMACEUTICA GP S.A DE C.V</option>--}}
	{{--<option value="65">FARMACEUTICA HISPANOAMERICANA SA DE CV</option>--}}
	{{--<option value="66">FARMACEUTICA WANDEL S.A DE CV.</option>--}}
	{{--<option value="68">FARMACEUTICOS  MAYPO S.A. DE C.</option>--}}
	{{--<option value="243">FARMACEUTICOS ALTAMIRANO DE MEXICO S.A DE C.V</option>--}}
	{{--<option value="67">FARMACEUTICOS DE OCCIDENTE S.A. DE C.V.</option>--}}
	{{--<option value="284">FARMACEUTICOS VIDA S.A. DE C.V.</option>--}}
	{{--<option value="345">FARMACIA CARREY S.A.</option>--}}
	{{--<option value="69">FARMACIA EL FENIX CENTRO S.A. DE C.V.</option>--}}
	{{--<option value="612">FARMACIA G Y G,  S.A DE C.V</option>--}}
	{{--<option value="71">FARMACIA PARIS S.A. DE C.V</option>--}}
	{{--<option value="72">FARMACIA SAN ISIDRO</option>--}}
	{{--<option value="341">FARMACIA Y DROGUERIA TOLUCA SA DE CV</option>--}}
	{{--<option value="613">FARMACIA Y MINI SUPER DEL SUR S.A DE C.V</option>--}}
	{{--<option value="73">FARMACIAS ABC DE MEXICO S.A. DE C.V </option>--}}
	{{--<option value="321">FARMACIAS BENAVIDES S.A.B. DE C.V.</option>--}}
	{{--<option value="74">FARMACIAS DE JALISCO S.A. DE C</option>--}}
	{{--<option value="75">FARMACIAS DE SIMILARES S.A. DE  C.V</option>--}}
	{{--<option value="333">FARMACIAS DEL PUEBLO S.A DE C.V.</option>--}}
	{{--<option value="76">FARMACIAS GUADALAJARA. S.A. DE</option>--}}
	{{--<option value="331">FARMACIAS INTRA HOSPITALARIAS</option>--}}
	{{--<option value="328">FARMACIAS LA DE SIEMPRE  S.A. DE C.V.</option>--}}
	{{--<option value="394">FARMACIAS SIMITLA S.A. DE C.V.</option>--}}
	{{--<option value="593">FARMACOPEA DE LOS ESTADOS UNIDOS MEXICANOS, A.C</option>--}}
	{{--<option value="421">FARMACOS DINSA S.A DE C.V.</option>--}}
	{{--<option value="268">FARMACOS ESPECIALIZADOS S.A DE C.V</option>--}}
	{{--<option value="705">FARMACOS NACIONALES S.A. DE C.V.</option>--}}
	{{--<option value="77">FARMACUR, S.A. DE C.V.</option>--}}
	{{--<option value="232">FARMALUK S DE R.L. DE C.V.</option>--}}
	{{--<option value="433">FARMAMIGO DE TOLUCA S.A. DE C.V.</option>--}}
	{{--<option value="671">FARMANEST S.A. DE C.V.</option>--}}
	{{--<option value="663">FARMAYOREO DE ABASTOS</option>--}}
	{{--<option value="617">FELIPE MENDOZA MARTINEZ</option>--}}
	{{--<option value="371">FERNANDEZ ROJAS PAULO CESAR</option>--}}
	{{--<option value="585">FERNANDO CORTES MARTINEZ</option>--}}
	{{--<option value="720">FERNANDO NAVARRO LUNA</option>--}}
	{{--<option value="192">FERRING S.A. DE C.V.</option>--}}
	{{--<option value="293">FREDY ROGER COUTI?O GOMEZ</option>--}}
	{{--<option value="78">FRESENIUS KABI MEXICO S.A. DE C.V.</option>--}}
	{{--<option value="80">FRESENIUS MEDICAL CARE DE MEXICO S.A. DE C.V</option>--}}
	{{--<option value="81">G.8 COMERCIALIZADORA S. R.L. DE C.V.</option>--}}
	{{--<option value="568">GABRIEL SALAZAR BARRON</option>--}}
	{{--<option value="329">GALIA TEXTIL S.A DE C.V</option>--}}
	{{--<option value="267">GAMAFARMA S.A DE C.V</option>--}}
	{{--<option value="770">GANGAFARMA 2014</option>--}}
	{{--<option value="675">GARCIA HERRERA VERONICA YAZMIN</option>--}}
	{{--<option value="748">GARPI S.A DE C.V</option>--}}
	{{--<option value="82">GELPHARMA  S.A. DE C.V.</option>--}}
	{{--<option value="446">GEMETYTEC DE GUADALAJARA SA DE CV</option>--}}
	{{--<option value="83">GEMINIS FARMACIAS ESPECIALIZADAS, S.A. DE C.V.</option>--}}
	{{--<option value="469">GEN INDUSTRIAL SA DE CV</option>--}}
	{{--<option value="642">GIANT PACKING SA DE CV</option>--}}
	{{--<option value="443">GLENMARK PHARMCEUTICALS MEXICO S.A. DE C.V.</option>--}}
	{{--<option value="565">GNK</option>--}}
	{{--<option value="634">GOBIERNO DEL DISTRITO FEDERAL/ SECRETARIA DE SALUD</option>--}}
	{{--<option value="405">GOMEN HEALTH CARE S.A DE C.V</option>--}}
	{{--<option value="733">GONZALEZ RASCO NORMA PATRICIA</option>--}}
	{{--<option value="530">GRACIELA ISLAS DOMINGUEZ</option>--}}
	{{--<option value="87">GRACIELA NAVARRO CORONA</option>--}}
	{{--<option value="563">GRUNENTHAL DE MEXICO, S.A. DE C.V.</option>--}}
	{{--<option value="85">GRUPO  MORAVI S.A. DE C.V.</option>--}}
	{{--<option value="442">GRUPO ATOF DIVISION HOSPITALARIA SA DE CV</option>--}}
	{{--<option value="658">GRUPO CARBEL S.A. DE C.V.</option>--}}
	{{--<option value="86">GRUPO CH ALCANCE S.A. DE C.V.</option>--}}
	{{--<option value="638">GRUPO COM MEDICA OCSILON S.A. DE C.V.</option>--}}
	{{--<option value="402">GRUPO COMERCIAL MEDICA ANGOLA S.A. DE C.V.</option>--}}
	{{--<option value="722">GRUPO DISTRIBUIDOR MEDICAM S.A DE C.V</option>--}}
	{{--<option value="317">GRUPO EMEQUR S.A DE C.V</option>--}}
	{{--<option value="367">GRUPO EMPRESARIAL DE SERVICIOS FRAY DANIEL</option>--}}
	{{--<option value="130">GRUPO ESMOVED INTERNACIONAL S.A DE C.V.</option>--}}
	{{--<option value="621">GRUPO INTEGRAL DE SERVICIOS LEAL SA DE CV</option>--}}
	{{--<option value="557">GRUPO LINEAS COMERCIALIZADORAS MAYA SA DE CV</option>--}}
	{{--<option value="264">GRUPO MABRAGO S.A DE C.V</option>--}}
	{{--<option value="714">GRUPO QUIROPRACTICO DEL BAJIO S.A DE C.V</option>--}}
	{{--<option value="679">GRUPO SURUZA SA DE CV</option>--}}
	{{--<option value="678">GRUPO VANHEBSEN SA DE CV</option>--}}
	{{--<option value="88">GRUPO VETERINARIO FONSECA, S.A. DE C.V.</option>--}}
	{{--<option value="743">GUILLERMO CAMPOS JARAMILLO</option>--}}
	{{--<option value="348">GUILLERMO LOZANO BARRERA</option>--}}
	{{--<option value="649">GUSTAVO ARCOS DELGADO</option>--}}
	{{--<option value="628">GUSTAVO MEJIA CABAÑAS</option>--}}
	{{--<option value="531">HECTOR JIMENEZ BECERRIL</option>--}}
	{{--<option value="89">HECTOR RODRIGO PONCE SANCHEZ</option>--}}
	{{--<option value="520">HERLET MEDIC SA DE CV</option>--}}
	{{--<option value="508">HERMINIA MENDEZ CASTRO</option>--}}
	{{--<option value="91">HI-TEC MEDICAL  DE OCCIDENTE S.A. DE C.V.</option>--}}
	{{--<option value="296">HISA FARMACEUTICA SA DE CV</option>--}}
	{{--<option value="92">HOSPITALES Y QUIROFANOS S.A. DE C.V.</option>--}}
	{{--<option value="674">HOSPITECNICA S.A DE C.V</option>--}}
	{{--<option value="213">HOSPITERRA SA DE CV</option>--}}
	{{--<option value="309">HUDELZA SA DE CV</option>--}}
	{{--<option value="119">HUMANA DE EQUIPOS Y MATERIALES S.A DE C.V.</option>--}}
	{{--<option value="266">HUMBERTO PEREZ RAMIREZ</option>--}}
	{{--<option value="240">HYCEL DE MEXICO S.A DE C.V</option>--}}
	{{--<option value="37">IMADINE GUADALAJARA</option>--}}
	{{--<option value="583">IMAGEN Y RECUBRIMIENTOS PERDURABLES, S.A. DE C.V.</option>--}}
	{{--<option value="338">IMAGENES Y RX S.A DE C.V</option>--}}
	{{--<option value="93">IMFARDEL. S.A. DE  C.V.</option>--}}
	{{--<option value="656">IMPORTADORA MANUFACTURERA BRULUART S.A. DE CV</option>--}}
	{{--<option value="737">IMPORTADORA MATRIUSKA S.A DE C.V</option>--}}
	{{--<option value="644">IMPORTADORA MEDICA MEXICANA SA</option>--}}
	{{--<option value="447">IMPRE RAPID SA DE CV</option>--}}
	{{--<option value="94">IMPULSO FARMACEUTICO DE OCCIDENTE S.A. DE C.V.</option>--}}
	{{--<option value="589">IMPULSO INTEGRA POPULAR S.A. DE C.V.</option>--}}
	{{--<option value="496">IMPULSORA INDUSTRIAL DE TOLUCA S.A. DE C.V.</option>--}}
	{{--<option value="230">INDELPA S.A DE C.V.</option>--}}
	{{--<option value="387">INDUFARMA S.A. DE C.V.</option>--}}
	{{--<option value="390">INDUSTRIA ATENGO S.A. DE C.V.</option>--}}
	{{--<option value="498">INDUSTRIAL DE INSUMOS DE ALTA TECNOLOGIA S.A. DE C.V.</option>--}}
	{{--<option value="745">INDUSTRIAL POLARIS</option>--}}
	{{--<option value="6">INDUSTRIAS PLASTICAS MEDICAS S.A. DE C.V.</option>--}}
	{{--<option value="654">INDUSTRIAS QUIMICO FARMACEUTICAS AMERICANAS S.A. DE C.V.</option>--}}
	{{--<option value="592">INMOBILIARIA ULZAMA, S.A. DE C.V</option>--}}
	{{--<option value="95">INSTRUMENTAL QUIRURGICO</option>--}}
	{{--<option value="7">INSTRUMENTOS Y ACCESORIOS AUTOMATIZADOS S.A DE C.V.</option>--}}
	{{--<option value="525">INSUMOS MEDICOS MAR DE CORTES SA DE CV</option>--}}
	{{--<option value="648">INTEGRADORA DE BIENES Y SERVICIOS JIMSA, S.A. DE C.V.</option>--}}
	{{--<option value="438">INTELIGENCIA MEDICA EDJEN S.A. DE C.V.</option>--}}
	{{--<option value="730">INTELPHARMA NETWORK DISTRIBUCION S.A DE C.V</option>--}}
	{{--<option value="96">INTERCAMBIO GLOBAL LATINOAMERICA SA DE CV</option>--}}
	{{--<option value="665">INTERCONTINENTAL FREIGHT &amp; PARCEL SA DE CV</option>--}}
	{{--<option value="677">INTERCONTINENTAL FREIGHT &amp; PARCEL, SA DE CV</option>--}}
	{{--<option value="434">INTERNACIONAL FARMACEUTICA SA DE CV</option>--}}
	{{--<option value="358">INTERNATIONAL MEDICAL DEVICES S.A DE C.V</option>--}}
	{{--<option value="650">INVESTIGACION FARMACEUTICA S.A. DE C.V.</option>--}}
	{{--<option value="647">IQ MEDICAL S DE RL DE CV</option>--}}
	{{--<option value="408">IRMA GABRIELA HERNANDEZ VARGAS</option>--}}
	{{--<option value="552">ISRAEL ENCISO GONZALEZ</option>--}}
	{{--<option value="659">ITALMEX, S.A.</option>--}}
	{{--<option value="374">ITUPHARMA DISTRIBUCIONES, SA DE CV</option>--}}
	{{--<option value="670">IVOCLAR VIVADENT S.A DE C.V</option>--}}
	{{--<option value="660">JACQUELINE  HERNANDEZ. REYES</option>--}}
	{{--<option value="167">JAFR DISTRIBUIDORA </option>--}}
	{{--<option value="362">JAIME JUAREZ MONTES</option>--}}
	{{--<option value="489">JAVIER GUERRA ANAYA</option>--}}
	{{--<option value="417">JAVIER HERNANDEZ ARCOS</option>--}}
	{{--<option value="718">JAVIER OROZCO SANCHEZ</option>--}}
	{{--<option value="546">JAVIER VICENTE BELTRAN DIAZ</option>--}}
	{{--<option value="664">JESUS BEN HUR BECERRA VAZQUEZ</option>--}}
	{{--<option value="492">JESUS MIGUEL GARCIA FLORES</option>--}}
	{{--<option value="729">JHADYD S.A DE C.V</option>--}}
	{{--<option value="526">JIMENEZ VELAZQUEZ ENRIQUE JAVIER</option>--}}
	{{--<option value="480">JOAQUIN ROMERO JIMENEZ</option>--}}
	{{--<option value="651">JORGE ORACIO TORRES PEREZ</option>--}}
	{{--<option value="97">JOSE ADRIAN GAMBOA MORALES</option>--}}
	{{--<option value="368">JOSE ANTONIO BECERRA LOZANO</option>--}}
	{{--<option value="632">JOSE ISRAEL MEDINA CORTEZ</option>--}}
	{{--<option value="280">JOSE LUIS PEREZ ZARATE</option>--}}
	{{--<option value="290">JOSE LUIS VALDIVIA TRUJILLO</option>--}}
	{{--<option value="692">JOSE MANUEL PEREZ FLORES</option>--}}
	{{--<option value="379">JOSE MIGUEL ORTIZ GARZA</option>--}}
	{{--<option value="541">JOSE REYES CASTILLO JIMENEZ</option>--}}
	{{--<option value="625">JOSE SAUL TOSCANO LOPEZ</option>--}}
	{{--<option value="98">JTC PROVEEDOR MEDICO S.A. DE C.V.</option>--}}
	{{--<option value="454">JUAN CARLOS HERNANDEZ GONZALEZ</option>--}}
	{{--<option value="635">JUAN CARLOS HERRERA LOPEZ</option>--}}
	{{--<option value="596">JUAN CARLOS HERRERA MANZO</option>--}}
	{{--<option value="687">JUAN CARLOS MENDOZA NAVARRO</option>--}}
	{{--<option value="491">JUAN GABRIEL PEREZ ESQUIVEL</option>--}}
	{{--<option value="487">JUAN RAUL REYES GONZALEZ</option>--}}
	{{--<option value="99">KALTHEN DE MEXICO, S.A. DE C.V.</option>--}}
	{{--<option value="698">KARINA GUADALUPE CUESTAS ARCOS</option>--}}
	{{--<option value="548">KARLA ISABEL BAÑOS SANCHEZ</option>--}}
	{{--<option value="100">KARLA VANESSA ISORDIA MERCADO</option>--}}
	{{--<option value="295">KARTUJET SA DE CV</option>--}}
	{{--<option value="385">KEDRION MEXICANA S.A DE C.V</option>--}}
	{{--<option value="188">KENDRICK FARMACEUTICA</option>--}}
	{{--<option value="466">KRYO TECNOLOGIA S.A. DE C.V.</option>--}}
	{{--<option value="327">KUREX ESPECIALIDADES, S.A. DE C.V.</option>--}}
	{{--<option value="246">LABORATORIO  VISA S.A DE C.V.</option>--}}
	{{--<option value="189">LABORATORIO BIOQUIMICO MEXICANO S.A. DE C.V.</option>--}}
	{{--<option value="499">LABORATORIO DE CALIBRACIÓN Y PRUEBAS SIMCA S. DE R.L. DE C.V.</option>--}}
	{{--<option value="169">LABORATORIO RAAM DE SAHUAYO S.A DE C.V</option>--}}
	{{--<option value="669">LABORATORIO SALUS S.A. DE C.V</option>--}}
	{{--<option value="193">LABORATORIOS ALPHARMA S.A DE C.V</option>--}}
	{{--<option value="102">LABORATORIOS BAJA MED. S.A. DE C.V.</option>--}}
	{{--<option value="103">LABORATORIOS CRYOPHARMA S.A. DE C.V</option>--}}
	{{--<option value="540">LABORATORIOS DE CALIBRACIÓN Y CALIFICACION S.A. DE C.V.</option>--}}
	{{--<option value="364">LABORATORIOS DIBA S.A.</option>--}}
	{{--<option value="105">LABORATORIOS GRIN S.A. DE C.V</option>--}}
	{{--<option value="655">LABORATORIOS HORMONA S.A. PI DE C.V.</option>--}}
	{{--<option value="602">LABORATORIOS JALOMA SA DE CV</option>--}}
	{{--<option value="164">LABORATORIOS JAYOR, S.A DE C.V </option>--}}
	{{--<option value="383">LABORATORIOS KENER S.A. DE C.V.</option>--}}
	{{--<option value="101">LABORATORIOS LEROY S.A. DE C.V.</option>--}}
	{{--<option value="106">LABORATORIOS MAVER DE MEXICO.</option>--}}
	{{--<option value="108">LABORATORIOS PISA S.A DE C.V</option>--}}
	{{--<option value="109">LABORATORIOS PIZZARD S.A DE C.</option>--}}
	{{--<option value="701">LABORATORIOS SERRAL S.A. DE C.V.</option>--}}
	{{--<option value="221">LABORATORIOS SILANES S.A. DE C.V.</option>--}}
	{{--<option value="165">LABORATORIOS SOLFRAN</option>--}}
	{{--<option value="111">LABORATORIOS SOPHIA S.A. DE C.V.</option>--}}
	{{--<option value="316">LABORATORIOS TORRENT S.A DE C.V</option>--}}
	{{--<option value="112">LABORATORIOS VANQUISH  S.A. DE C.V.</option>--}}
	{{--<option value="8">LABORATORIOS ZAFIRO S.A. DE C.</option>--}}
	{{--<option value="311">LABORATORIOS ZEYCO S.A DE C.V.</option>--}}
	{{--<option value="194">LAMBI S.A DE C.V.</option>--}}
	{{--<option value="113">LANCETA HG SA DE CV</option>--}}
	{{--<option value="114">LANDSTEINER PHARMA S.A. DE C.V.</option>--}}
	{{--<option value="747">LANDSTEINER SCIENTIFIC SA DE CV</option>--}}
	{{--<option value="291">LARES RAMOS Y COMPAÑIA SC</option>--}}
	{{--<option value="448">LBM COMERCIALIZADORA SA DE CV</option>--}}
	{{--<option value="484">LE NORD DARURI S.A. DE C.V.</option>--}}
	{{--<option value="115">LEDESMA VAZQUEZ PHILOS S.A. DE C.V.</option>--}}
	{{--<option value="224">LEMERY S.A. DE C.V.</option>--}}
	{{--<option value="247">LEN S MEDICAL S.A DE C.V</option>--}}
	{{--<option value="116">LETICIA AREVALO SANCHEZ</option>--}}
	{{--<option value="684">LETICIA LUNA GASGA</option>--}}
	{{--<option value="437">LETICIA SILLAS IRIBE</option>--}}
	{{--<option value="571">LETRADO GRANA LAURA GEORGINA</option>--}}
	{{--<option value="336">LIN DE MEXICO S.A. DE C.V.</option>--}}
	{{--<option value="766">LITIS CONSORCIO SC</option>--}}
	{{--<option value="118">LO VENDING GROUP S.A DE C.V </option>--}}
	{{--<option value="769">LO VENDING GROUP S.A. DE C.V.</option>--}}
	{{--<option value="117">LOEFFLER SA DE CV</option>--}}
	{{--<option value="195">LOGISTICA VALEANT S.A. DE C.V.</option>--}}
	{{--<option value="196">LOGITEC MEDICA S.A. DE C.V.</option>--}}
	{{--<option value="549">LOMEDIC SA. DE CV. (FONDEN)</option>--}}
	{{--<option value="449">LOPEZ MENDOZA LUIS EDUARDO</option>--}}
	{{--<option value="726">LOZANO OROZCO HECTOR FABIAN</option>--}}
	{{--<option value="497">LUIS ALEJANDRO GAMA DOROTEO</option>--}}
	{{--<option value="482">LUIS GERARDO RAMIREZ PAZ</option>--}}
	{{--<option value="403">LUIS MANUEL RAMOS FLORES</option>--}}
	{{--<option value="419">LUIS PEDRAZA GONZALEZ</option>--}}
	{{--<option value="580">LUZ ELOISA ANAYA JIMENEZ</option>--}}
	{{--<option value="578">MA. MAGDALENA ORDOÑEZ RAMIREZ</option>--}}
	{{--<option value="381">MADAME DUBARRY S.A DE C.V</option>--}}
	{{--<option value="277">MAINEQ DE MEXICO S.A DE C.V.</option>--}}
	{{--<option value="279">MAJAPA DISTRIBUIDORA</option>--}}
	{{--<option value="120">MARCAS NESTLE, S.A DE C.V.</option>--}}
	{{--<option value="372">MARCO ANTONIO JAUREGUI  RIOS</option>--}}
	{{--<option value="241">MARIA CONCEPCION BECERRA VILLAREAL</option>--}}
	{{--<option value="582">MARIA CONCEPCION DOMINGUEZ FLORES</option>--}}
	{{--<option value="616">MARIA GUADALUPE PEREZ MEJIA</option>--}}
	{{--<option value="121">MARIA GUADALUPE SALCEDO SOTO</option>--}}
	{{--<option value="306">MARIA LILIANA OREGEL MARTINEZ</option>--}}
	{{--<option value="584">MARIA MAGDALENA ORDOÑEZ RAMIREZ</option>--}}
	{{--<option value="630">MARIO GERALD PEDRAZA GONZALEZ</option>--}}
	{{--<option value="753">MARISELA HERNAANDEZ ANDALON</option>--}}
	{{--<option value="323">MARISSA SOLIS GUTIERREZ</option>--}}
	{{--<option value="208">MARLEX HEALTH CARE S DE RL DE CV</option>--}}
	{{--<option value="244">MAS MEDICA S.A DE C.V</option>--}}
	{{--<option value="289">MAS PROMOTORA DE ARTICULOS PUBLICITARIOS SA DE CV</option>--}}
	{{--<option value="122">MATERIAL HOSPITALARIO S.A. DE C.V.</option>--}}
	{{--<option value="123">MAVI FARMACEUTICA, S.A. DE C.V.</option>--}}
	{{--<option value="346">MAXIVA S.A. DE C.V.</option>--}}
	{{--<option value="757">MAYOLI SPINDLER DE MEXICO S.A DE C.V</option>--}}
	{{--<option value="384">MAYRA PATRICIA NU?O RUBIO</option>--}}
	{{--<option value="431">MCBERRY DE MEXICO S RL DE CV</option>--}}
	{{--<option value="154">MEAD JOHNSON NUTRICIONALES DE MEXICO S.D. R.L. DE C.V.</option>--}}
	{{--<option value="633">MEDI TELEMARKETING S.A DE C.V</option>--}}
	{{--<option value="10">MEDI-LAB DE OCCIDENTE S.A. DE C.V.</option>--}}
	{{--<option value="391">MEDICA MOTOLINA S.A DE C.V</option>--}}
	{{--<option value="198">MEDICA PAVER S.A. DE C.V.</option>--}}
	{{--<option value="322">MEDICA SUPLIES SA DE CV</option>--}}
	{{--<option value="332">MEDICAL DEPOT S.A DE  C.V</option>--}}
	{{--<option value="574">MEDICAL DIMEGAR S.A DE C.V</option>--}}
	{{--<option value="210">MEDICAL EQUIPMENT SUPPLIER S.A DE C.V.</option>--}}
	{{--<option value="238">MEDICAL EXPRESS S.A DE C.V</option>--}}
	{{--<option value="211">MEDICAL RECOVERY, SA DE CV</option>--}}
	{{--<option value="225">MEDICAL STOCK S DE RL DE CV</option>--}}
	{{--<option value="234">MEDICAMENTOS NATURALES S.A. DE C.V.</option>--}}
	{{--<option value="709">MEDICAMENTOS Y SERVICIOS INTEGRALES DEL NOROESTE S.A DE C.V</option>--}}
	{{--<option value="415">MEDICEL SA DE CV</option>--}}
	{{--<option value="125">MEDICINA PARA MEXICO S.A. DE C.V.</option>--}}
	{{--<option value="126">MEDICINAS ULTRA S.A. DE C.V.</option>--}}
	{{--<option value="9">MEDICITY DE MEXICO, S.A. DE C.V.</option>--}}
	{{--<option value="175">MEDICONSA  S.A. DE C.V.</option>--}}
	{{--<option value="127">MEDICURI, S.A. DE C.V.</option>--}}
	{{--<option value="629">MEDIFLY SA DE CV</option>--}}
	{{--<option value="436">MEDIMARCAS S.A DE C.V</option>--}}
	{{--<option value="412">MERCEDES GUADALUPE ALONSO ZARAGOZA</option>--}}
	{{--<option value="132">MERCK. S.A. DE C.V.</option>--}}
	{{--<option value="609">MERZ PHARMA, S.A. DE .C.V</option>--}}
	{{--<option value="314">MICRO PHARMACEUTICALS MEXICO DE R L DE CV</option>--}}
	{{--<option value="479">MICRO PRECISION CALIBRATION DE MEXICO S DE RL DE CV</option>--}}
	{{--<option value="251">MICROPOMEX  S.A DE C.V</option>--}}
	{{--<option value="418">MIGUEL ANGEL DOMINGUEZ DELGADILLO</option>--}}
	{{--<option value="523">MIGUEL VAZQUEZ ABUNDIO</option>--}}
	{{--<option value="727">MINERVA ESMERALDA VAZQUEZ HUERTA</option>--}}
	{{--<option value="502">MINICAR TOLLOCAN S.A. DE C.V.</option>--}}
	{{--<option value="555">MOBILIARIO FUNCIONAL S.A DE C.V</option>--}}
	{{--<option value="504">MODESTO JAVIER BARRERA FLORES</option>--}}
	{{--<option value="685">MOEDANOS OTC S.A DE C.V.</option>--}}
	{{--<option value="263">MONICA MARTINEZ BAUTISTA</option>--}}
	{{--<option value="519">MONICA PELCASTRE CASTAÑEDA</option>--}}
	{{--<option value="494">MONTACARGAS VALLEJO SA DE CV</option>--}}
	{{--<option value="334">MORE PHARMA CORPORATION S DE RL DE CV</option>--}}
	{{--<option value="521">MORENO BARRERA TANIA ERENDIRA</option>--}}
	{{--<option value="242">MULTINACIONAL DE SEGURIDAD INDUSTRIAL S.A DE C.V</option>--}}
	{{--<option value="129">NADRO. S.A. DE C.V.</option>--}}
	{{--<option value="107">NAFAR LABORATORIOS S.A DE C.V</option>--}}
	{{--<option value="11">NAFARRATE SA DE CV</option>--}}
	{{--<option value="481">NAVARRO PEREZ KARINA LUCIA</option>--}}
	{{--<option value="426">NEO TECNIA S.A. DE C.V.</option>--}}
	{{--<option value="699">NEOLPHARMA S.A. DE C.V.</option>--}}
	{{--<option value="212">NIPRO MEDICAL DE MEXICO S.A DE C.V.</option>--}}
	{{--<option value="376">NITREXA S.A. DE C.V</option>--}}
	{{--<option value="756">NOPROMEX  S.A DE C.V</option>--}}
	{{--<option value="511">NORMA RIVERA SANCHEZ</option>--}}
	{{--<option value="2">NOVAG INFANCIA S.A. DE C.V.</option>--}}
	{{--<option value="573">NOVARTIS FARMACÉUTICA, S.A. DE C.V</option>--}}
	{{--<option value="131">NOVO NORDISK MEXICO. S.A. DE C.</option>--}}
	{{--<option value="620">NT MEDICA S.A DE C.V</option>--}}
	{{--<option value="607">NUCITEC, S.A. DE C.V.</option>--}}
	{{--<option value="393">NUEVA FARMACIA  Y DROGUERIA TOLEDO S.A. DE C.V.</option>--}}
	{{--<option value="409">NUEVA WAL MART DE M?XICO S DE RL DE CV</option>--}}
	{{--<option value="342">NUTRICION DEPORTIVA</option>--}}
	{{--<option value="363">NVA FARMACIA SAN BORJA S.A. DE C.V.</option>--}}
	{{--<option value="551">OCAMPO LUNA JESUS</option>--}}
	{{--<option value="199">OCTAPHARMA S.A DE C.V.</option>--}}
	{{--<option value="252">OCULUS TECHNOLOGIES OF MEXICO S.A DE C.V</option>--}}
	{{--<option value="190">OMEGA DENTAL S.A DE C.V.</option>--}}
	{{--<option value="623">OMNICARGA SA DE CV</option>--}}
	{{--<option value="12">OMNISAFE S.A. DE C.V.</option>--}}
	{{--<option value="222">ONCOLOGIA E INSUMOS KOMAE SA DE CV</option>--}}
	{{--<option value="302">ONCOMEDIC DISTRIBUIDORA DE MEDICAMENTOS S.A DE C.V</option>--}}
	{{--<option value="324">OPERADOR DE MAYORISTAS CORA S.A. DE C.V.</option>--}}
	{{--<option value="728">OPERADORA DE FARMACIAS GENERIX S.A.P.I. DE C.V.</option>--}}
	{{--<option value="676">OPERADORA OMX SA DE CV</option>--}}
	{{--<option value="63">OPERADORA PROMOTTER OPRO S.A DE C.V.</option>--}}
	{{--<option value="287">OPTIMIZACION C.H. S.A. DE C.V.</option>--}}
	{{--<option value="424">ORLANDO LOZANO SEGOVIA</option>--}}
	{{--<option value="452">OROZCO LASSO JOSE DE JESUS</option>--}}
	{{--<option value="301">ORTIZ GLOBAL S.A</option>--}}
	{{--<option value="133">OSCAR FRANCISCO VIVAS BARRETO</option>--}}
	{{--<option value="567">PABLO GARCIA RODRIGUEZ</option>--}}
	{{--<option value="764">PATRICIA ANGELINA OROZCO SANCHEZ</option>--}}
	{{--<option value="661">PAWIS COMERCIALIZADORA S.A. DE C.V.</option>--}}
	{{--<option value="259">PAZ ANGELICA ARAMBULA DE VALDEZ</option>--}}
	{{--<option value="134">PEGO S.A. DE C.V.</option>--}}
	{{--<option value="681">PEGU SERVICIOS INTEGRALES S.A DE C.V</option>--}}
	{{--<option value="509">PEST  BUGS CONTROL S.A. DE C.V.</option>--}}
	{{--<option value="590">PEST AND BUGS CONTROL, S.A DE C.V</option>--}}
	{{--<option value="200">PFIZER S.A. DE C.V.</option>--}}
	{{--<option value="13">PHARMA CIENTIFIC. S.A. DE C.V.</option>--}}
	{{--<option value="269">PHARMA CLUB S.A DE C.V</option>--}}
	{{--<option value="135">PHARMA LINE, S.A. DE C.V.</option>--}}
	{{--<option value="136">PHARMA PLUS S.A. DE C.V.</option>--}}
	{{--<option value="422">PHARMACEUTICALS HEATL CARE S.A. DE C.V.</option>--}}
	{{--<option value="256">PHARMACOS EXAKTA S.A DE C.V</option>--}}
	{{--<option value="285">PHARMASHELDON S.A. DE C.V.</option>--}}
	{{--<option value="171">PIHCSA PARA HOSPITALES S.A DE C.V.</option>--}}
	{{--<option value="14">PISA AGROPECUARIA S.A. DE C.V.</option>--}}
	{{--<option value="619">PLAZA MEDICA JALISCO S.A DE C.V</option>--}}
	{{--<option value="458">PRESTADORA ALTO NIVEL SA DE CV</option>--}}
	{{--<option value="176">PROBIOMED, S.A DE C.V.</option>--}}
	{{--<option value="128">PRODONSA MÉXICO S.A. DE C.V.</option>--}}
	{{--<option value="258">PRODUCTOS ADEX S.A DE C.V.</option>--}}
	{{--<option value="257">PRODUCTOS CIENTIFICOS S.A. DE C.V.</option>--}}
	{{--<option value="245">PRODUCTOS DE GRAN CONSUMO S.A DE C.V</option>--}}
	{{--<option value="326">PRODUCTOS E INSUMOS PARA LA SALUD, S.A. DE C.V.</option>--}}
	{{--<option value="392">PRODUCTOS FARMACEUTICOS S.A. DE C.V.</option>--}}
	{{--<option value="254">PRODUCTOS GALENO S DE R L</option>--}}
	{{--<option value="429">PRODUCTOS MEDICOS ESPECIALES SA DE CV</option>--}}
	{{--<option value="742">PRODUCTOS MENA S.A DE C.V</option>--}}
	{{--<option value="768">PRODUCTOS Y MEDICAMENTOS DE ORIENTE, S.A. DE  C.V.</option>--}}
	{{--<option value="425">PROGRAMACION COMERCIAL APLICADA SA DE CV</option>--}}
	{{--<option value="550">PROMATE EMPAQUES SA DE CV</option>--}}
	{{--<option value="138">PROMEIM S.A DE C.V.</option>--}}
	{{--<option value="139">PROMOCIONES BIOMEDICAS DE OCCIDENTE S.A DE C.V</option>--}}
	{{--<option value="428">PROQUIGAMA S.A. DE C.V.</option>--}}
	{{--<option value="325">PROQUIYMSA, S.A. DE C.V.</option>--}}
	{{--<option value="205">PROTEIN, S.A. DE C.V.</option>--}}
	{{--<option value="140">PROVEDORA OCCIDENTAL DE MEDICAMENTOS, S.A. DE C.V.</option>--}}
	{{--<option value="201">PROVEEDORA DE INSTRUMENTAL Y EQUIPO, S.A. DE C.V.</option>--}}
	{{--<option value="579">PROVEEDORA DE MATERIALES ESPECIALIZADOS GSI SA DE CV</option>--}}
	{{--<option value="524">PROVEEDORA DE MATERIALES ESPECIALIZADOS GSJ, S.A. DE C.V.</option>--}}
	{{--<option value="233">PROVEEDORA DE MEDICAMENTOS S.A DE C.V</option>--}}
	{{--<option value="141">PROVEEDORA FARMACEUTICA CRUZ, S.A DE C.V </option>--}}
	{{--<option value="386">PROVEEDORA GAMA MEDICAL SERVICE SA DE CV</option>--}}
	{{--<option value="711">PROVEEDORA MEXICANA DE ARTICULOS DE CURACION Y LABORATORIO SA DE CV</option>--}}
	{{--<option value="177">PROVEEDORA NACIONAL DE MATERIAL DE CURACION S.A DE C.V.</option>--}}
	{{--<option value="308">PROWIN DE MEXICO S.A DE C.V</option>--}}
	{{--<option value="142">PSICOFARMA. S.A. DE C.V.</option>--}}
	{{--<option value="143">PTY DE MEXICO  S.A. DE C.V.</option>--}}
	{{--<option value="516">QUIMICA WIMER SA DE CV</option>--}}
	{{--<option value="365">QUIMICA Y FARMACIA, S.A DE C.V.</option>--}}
	{{--<option value="144">QUIRMEX S.A. DE C.V.</option>--}}
	{{--<option value="145">RADIO SHACK DE MEXICO S.A. DE C.V.</option>--}}
	{{--<option value="288">RAFAEL MURILLO GARCIA</option>--}}
	{{--<option value="282">RAFAEL PEDRAZA GARCIA</option>--}}
	{{--<option value="146">RAGAR. S.A. DE  C.V.</option>--}}
	{{--<option value="741">RALCA S.A DE C.V</option>--}}
	{{--<option value="239">RANBAXY DE MEXICO S.A. DE C.V.</option>--}}
	{{--<option value="147">RANDALL LABORATORIES, S.A. DE C.V</option>--}}
	{{--<option value="501">RAUL FLORES MATIAS</option>--}}
	{{--<option value="174">REACTIVOS GUADALAJARA  S.A DE C.V</option>--}}
	{{--<option value="148">REACTIVOS Y QUIMICOS S.A. DE C.V.</option>--}}
	{{--<option value="404">REDER TOLUCA S.A DE C.V</option>--}}
	{{--<option value="732">RENTERIA LARA JESUS ANTONIO</option>--}}
	{{--<option value="255">REPRESENTACIONES  MEX-AMERICA S.A DE C.V</option>--}}
	{{--<option value="166">REPRESENTACIONES E INVESTIGACIONES MEDICAS S.A DE C.V</option>--}}
	{{--<option value="149">REPRESENTACIONES JMP S.A. DE C.V.</option>--}}
	{{--<option value="228">REUFFER LABORATORIOS S.A DE C.V</option>--}}
	{{--<option value="300">REX FARMA S.A DE C.V</option>--}}
	{{--<option value="150">REY DAVID DIAZ ARROYO</option>--}}
	{{--<option value="370">RICARDO MARTINEZ GALLARDO MENDOZA</option>--}}
	{{--<option value="599">RICO VARGAS JUAN CARLOS</option>--}}
	{{--<option value="529">RIVERA RODRIGUEZ PAULINO</option>--}}
	{{--<option value="577">ROBERTO EDUARDO TRAD ABOUMRAD</option>--}}
	{{--<option value="318">RODIMED S DE RL</option>--}}
	{{--<option value="15">ROPA QUIRURGICA DESECHABLE S.</option>--}}
	{{--<option value="330">ROSARIO MENDOZA MACEDO</option>--}}
	{{--<option value="299">S Y  M DISTRIBUTION S DE RL DE CV</option>--}}
	{{--<option value="70">SALUCOM S.A. DE C.V.</option>--}}
	{{--<option value="151">SANABRIA CORPORATIVO MEDICO S.A DE C.V</option>--}}
	{{--<option value="152">SANOFI AVENTIS WINTROP, S.A. DE C.V.</option>--}}
	{{--<option value="281">SANTIAGO LOPEZ CECILIA</option>--}}
	{{--<option value="340">SANTOYO AVILA DAVID ELIAS</option>--}}
	{{--<option value="624">SAUL GARDUÑO VAZQUEZ</option>--}}
	{{--<option value="60">SAVI DISTRIBUCIONES. S.A. DE C</option>--}}
	{{--<option value="759">SAY QUIMICA MEDICA S.A CV</option>--}}
	{{--<option value="352">SEGURIDAD INDUSTRIAL TOTAL S.A. DE C.V</option>--}}
	{{--<option value="467">SEGURIDAD INDUSTRIAL TOTAL S.A. DE C.V.</option>--}}
	{{--<option value="396">SENSIMEDICAL S.A DE C.V</option>--}}
	{{--<option value="725">SERGIO ARMANDO AMARE GOMEZ</option>--}}
	{{--<option value="539">SERGIO LUIS ESCAMILLA IÑIGUEZ</option>--}}
	{{--<option value="762">SERRAL S.A DE C.V</option>--}}
	{{--<option value="235">SERVICIO DE INGENIERIA EN MEDICINA DE OCCIDENTE S.A. DE C.V.</option>--}}
	{{--<option value="450">SERVICIO EXPRESS REGIO SA DE CV</option>--}}
	{{--<option value="315">SERVICIO Y ATENCION A HOSPITALES LIEXHO S.A DE C.V</option>--}}
	{{--<option value="349">SERVICIOS Y EQUIPOS  MEDICOS INTERNACIONALES DE TOLUCA SA DE CV</option>--}}
	{{--<option value="483">SERVILLANTAS SIETE LEGUAS S.A. DE C.V.</option>--}}
	{{--<option value="706">SIITTO TRAIDING COMPANY DE MÉXICO, S.A. DE C.V.</option>--}}
	{{--<option value="586">SILVIA GUTIERREZ SALAZAR</option>--}}
	{{--<option value="713">SILVIA MARQUEZ HERNANDEZ</option>--}}
	{{--<option value="337">SIMATECH DEL SUR S.A.</option>--}}
	{{--<option value="513">SIMCA GRUPO INDUSTRIAL S.A. DE C.V.</option>--}}
	{{--<option value="514">SISTEMAS DE TRATAMIENTO AMBIENTAL, SA DE CV</option>--}}
	{{--<option value="515">SISTEMAS DE TRATAMIENTO AMBIENTAL, SA DE CV</option>--}}
	{{--<option value="646">SISTEMAS TERMOELECTRICOS,S.A. DE C.V.</option>--}}
	{{--<option value="459">SITIO 40 LAS AGUILAS AC</option>--}}
	{{--<option value="155">SOCORRO VARGAS LARA</option>--}}
	{{--<option value="202">SOLARA S.A. DE C.V.</option>--}}
	{{--<option value="460">SOLTERO MEZA JOSE JUAN</option>--}}
	{{--<option value="455">SOLUCION INM SA DE CV</option>--}}
	{{--<option value="156">SOLUCIONES OPTIMAS HOSPITALARIAS S.A DE C.V.</option>--}}
	{{--<option value="172">SOLUCIONES QUIRURGICAS </option>--}}
	{{--<option value="572">SOLUGLOB IKON S.A. DE C.V.</option>--}}
	{{--<option value="691">SS WHITE BURS INC</option>--}}
	{{--<option value="637">SUAREZ CO Y  ASOCIADOS S.A DE C.V.</option>--}}
	{{--<option value="758">SULKA PHARMA S.A DE C.V</option>--}}
	{{--<option value="350">SUMEDIC S.A DE C.V.</option>--}}
	{{--<option value="157">SUPLEMENTOS MEDICO QUIRURGICOS S.A. DE C.V.</option>--}}
	{{--<option value="158">SUPLIDORES UNIDOS INTERNACIONALES S.A.</option>--}}
	{{--<option value="432">SURGIPLAST S.A DE C.V</option>--}}
	{{--<option value="159">SURTIDORA MEDICA DE OCCIDENTE S.A. DE C.V.</option>--}}
	{{--<option value="610">TAKEDA MEXICO, S.A. DE C.V.</option>--}}
	{{--<option value="237">TECNICA MEDICAL S.A DE C.V.</option>--}}
	{{--<option value="203">TECNOFARMA. S.A. DE C.V.</option>--}}
	{{--<option value="715">TECNOLOGIA FARMACEUTICA S.A D C.V</option>--}}
	{{--<option value="554">TELEFONOS DE MEXICO S.A.B DE C.V</option>--}}
	{{--<option value="160">TEODORO MANUEL ORTEGA GARCIA</option>--}}
	{{--<option value="357">TERALI S.A. DE C.V</option>--}}
	{{--<option value="416">TIENDAS CHEDRAUI SA DE CV</option>--}}
	{{--<option value="693">TIENDAS COMERCIAL MEXICANA S.A. DE C.V.</option>--}}
	{{--<option value="716">TIENDAS SORIANA S.A DE C.V</option>--}}
	{{--<option value="512">TOLLOCAN MOTORS S.A. DE C.V.</option>--}}
	{{--<option value="451">TONER COMPATIBLES Y GENERICOS SA DE CV</option>--}}
	{{--<option value="588">TRANS MOLAYI, S. DE R.L. DE C.V.</option>--}}
	{{--<option value="505">TRANSPORTES FERCEO S.A. DE C.V.</option>--}}
	{{--<option value="643">TUTTI DENTAL, S.A. DE C.V.</option>--}}
	{{--<option value="760">UCB DE MEXICO S.A DE C.V</option>--}}
	{{--<option value="413">UCIN MEDICA SA DE CV</option>--}}
	{{--<option value="161">ULTRA LABORATORIOS. S.A. DE  C.V</option>--}}
	{{--<option value="360">ULTRA TECNOLOGIA S.A DE C.V.</option>--}}
	{{--<option value="702">UNIMAT DE MEXICO, S.A. DE C.V.</option>--}}
	{{--<option value="343">UNKRAUT GRUPO SA DE CV</option>--}}
	{{--<option value="522">VALTIERRA ALVAREZ CARLOS</option>--}}
	{{--<option value="680">VAMASA, S.A. DE C.V.</option>--}}
	{{--<option value="461">VE GALAB SA DE CV</option>--}}
	{{--<option value="689">VERONICA ABIGAHI DIAZ ARIAS</option>--}}
	{{--<option value="407">VIBO MEDICA CONSUMIBLES MEDICOS S.A DE C.V</option>--}}
	{{--<option value="547">VIC SAM INVERSIONES, S.A DE C.V.</option>--}}
	{{--<option value="488">VIONNE  MOTORS S.A. DE C.V.</option>--}}
	{{--<option value="209">VITAE LABORATORIOS, S.A  DE C.V.</option>--}}
	{{--<option value="286">VITALIS-PHARMA DE MEXICO S.A. DE C.V.</option>--}}
	{{--<option value="163">VITASANITAS, S.A. DE C.V.</option>--}}
	{{--<option value="736">WHITE MC S.A DE C.V</option>--}}
	{{--<option value="218">WOLLER LABORATORIES S.A DE C.V</option>--}}
	{{--<option value="400">YIM DISTRIBUCIONES SA DE CV</option>--}}
	{{--<option value="544">YOLANDA MENDOZA ELVIRA</option>--}}
	{{--<option value="204">ZERIFAR S.A. DE C.V.</option>--}}
	{{--<option value="761">ZYDUS PHARMACEUTICALS MEXICO S.A DE C.V</option>--}}
	{{--<option value="570">ZYXA SA DE CV</option>--}}
{{--</select>--}}
{{--<select id="localidad" class="combo" onchange="revisar_nivel(false); this.className='combo';">--}}
	{{--<option value="-1">--Seleccione una opción--</option>--}}
	{{--<option value="432">ALMACENES GENERALES DE SANIDAD, CAMPO MIL. NO. 1-A, D.F.</option>--}}
	{{--<option value="1635">CENTRO DISTRIBUCION</option>--}}
	{{--<option value="20">D.F. MATRIZ SP</option>--}}
	{{--<option value="23">D.F. TABASCO</option>--}}
	{{--<option value="1631">FARMACIA IPEJAL PRUEBA</option>--}}
	{{--<option value="41">FARMACOS DAROVI</option>--}}
	{{--<option value="449">IMSS DELEGACION ESTATAL CHIAPAS</option>--}}
	{{--<option value="19">INTERCAMBIO GLOBAL ZACATECAS</option>--}}
	{{--<option value="1633">MATRIZ  HONDURAS</option>--}}
	{{--<option value="2">MATRIZ D.F. ABISA</option>--}}
	{{--<option value="13">MATRIZ D.F. ABISA PA</option>--}}
	{{--<option value="14">MATRIZ D.F. ABISA PBI</option>--}}
	{{--<option value="1">MATRIZ JALISCO ABISA</option>--}}
	{{--<option value="1632">MATRIZ TEST</option>--}}
	{{--<option value="3">MATRIZ TOLUCA ABISA</option>--}}
	{{--<option value="22">PEDIDOS ESPECIALES DF</option>--}}
	{{--<option value="21">PEDIDOS ESPECIALES GDL</option>--}}
	{{--<option value="76">PETROLEOS MEXICANOS</option>--}}
	{{--<option value="431">PRODUCTO PARA DIRECCION</option>--}}
	{{--<option value="42">PROVEEDORA GI</option>--}}
	{{--<option value="79">PUBLICO EN GENERAL</option>--}}
	{{--<option value="18">RENAL HEALTH - DF</option>--}}
	{{--<option value="47">UMAE HOSPITAL DE ESPECIALIDADES NO.1 CMN BAJIO</option>--}}
{{--</select>--}}

{{--<select id="atendio" class="combo" onchange="mostrarAgregar(); this.className='combo';">--}}
	{{--<option value="-1">--Seleccione una opcion--</option>--}}
	{{--<option value="388">A</option>--}}
	{{--<option value="410">AVGT</option>--}}
	{{--<option value="414">OTROOO</option>--}}
	{{--<option value="415">POLI</option>--}}
	{{--<option value="416">POLO</option>--}}
	{{--<option value="422">OTRO MAS</option>--}}
	{{--<option value="424">NUEVO NUEVO</option>--}}
	{{--<option value="431">CLAUDIA</option>--}}
	{{--<option value="-2">+ Agregar</option>--}}
{{--</select>--}}

{{--<select name="condicion_pago" class="combo" id="condicion_pago" onchange="this.className='combo';">--}}
	{{--<option value="-1">--Seleccione una opcion--</option>--}}
	{{--<option value="CONTADO">CONTADO</option>--}}
	{{--<option value="CREDITO">CREDITO</option>--}}
{{--</select>--}}

{{--Fecha promesa:--}}
{{--<input type="text" style="width: 90px" id="fecha_promesa" name="fecha_promesa" onkeyup="this.value=formateafecha(this.value,0); " onchange="fechaPromesa(this);" value="" autocomplete="off">--}}

{{--Observación:--}}
{{--<textarea id="observacion" class="a100" onkeypress="return validar(event,this,'alfanumerico');" maxlength="300" style="resize:none"></textarea>--}}

{{--Cliente:--}}

{{--<select id="cliente" class="combo" onchange="cambio_cliente(false);">--}}
{{--<option value="-1">--Seleccione una opción--</option>--}}
{{--<option value="15">ABASTECEDORA DE INSUMOS PARA LA SALUD S.A DE C.V</option>--}}
{{--<option value="29">ADMINISTRACION PUBLICA MUNICIPAL DE TINGUINDIN MICHOACAN</option>--}}
{{--<option value="9">AQUI ESTAMOS AC</option>--}}
{{--<option value="153">ARTURO MARIN PEREIDA</option>--}}
{{--<option value="24">BALESSA SA DE CV</option>--}}
{{--<option value="60">BERTHA ALICIA DE LOERA LOPEZ</option>--}}
{{--<option value="12">CAJA DE PREVISION DE LA POLICIA AUXILIAR DEL DISTRITO FEDERAL</option>--}}
{{--<option value="68">CALIXTO ALVISO DE LEON</option>--}}
{{--<option value="32">CIRUGIA PLASTICA  DEL SIGLO XXI, S.A.P.I. DE C.V.</option>--}}
{{--<option value="94">CONSTRUCTORA INTEGRAL NUMEI SA DE CV</option>--}}
{{--<option value="82">CRUZ ROJA MEXICANA IAP</option>--}}
{{--<option value="95">CYRGOS COMERCIALIZADORA SA DE CV</option>--}}
{{--<option value="43">DISTRIBUCION W SA DE CV</option>--}}
{{--<option value="47">DISTRIBUIDORA DE HOSPITALES Y MEDICAMENTOS S DE RL DE CV</option>--}}
{{--<option value="67">DISTRIBUIDORA DE PRODUCTOS PARA LAS INSTITUCIONES SA DE CV</option>--}}
{{--<option value="28">DISTRIBUIDORA INTERNACIONAL DE MEDICAMENTOS Y EQUIPO MEDICO S.A. DE C.V.</option>--}}
{{--<option value="83">DULCE MARIA NATERAS ARMENDARIZ</option>--}}
{{--<option value="53">FARMACIAS EL FENIX DEL CENTRO SA DE CV</option>--}}
{{--<option value="44">FARMACIAS LA DE SIEMPRE SA DE CV</option>--}}
{{--<option value="35">FARMACOS DAROVI S.A DE C.V</option>--}}
{{--<option value="142">FUNDACIÓN MÉXICO UNIDO PRO DERECHOS HUMANOS A.C</option>--}}
{{--<option value="92">GOBIERNO DEL DISTRITO FEDERAL/SECRETARIA DE SALUD</option>--}}
{{--<option value="101">GOBIERNO DEL DISTRITO FEDERAL/SECRETARIA DE SALUD - ALTERNOS</option>--}}
{{--<option value="158">GOBIERNO DEL DISTRITO FEDERAL/SECRETARIA DE SALUD - CLAVES ADMINISTRADAS</option>--}}
{{--<option value="140">GOBIERNO DEL DISTRITO FEDERAL/SECRETARIA DE SALUD - FC</option>--}}
{{--<option value="186">GOBIERNO DEL DISTRITO FEDERAL/SECRETARIA DE SALUD - HOSP DR BELISARIO DO</option>--}}
{{--<option value="136">GOBIERNO DEL DISTRITO FEDERAL/SECRETARIA DE SALUD - SEDESA T77</option>--}}
{{--<option value="121">GOBIERNO DEL ESTADO DE MORELOS</option>--}}
{{--<option value="46">GOBIERNO DEL ESTADO DE OAXACA SS SEGURO POPULAR</option>--}}
{{--<option value="71">GOMEN HEALTH CARE SA DE CV</option>--}}
{{--<option value="19">GRUPO COMERCIAL MEDICA ANGOLA SA DE CV</option>--}}
{{--<option value="143">GRUPO DISTRIBUIDOR IMP S.A. DE C.V.</option>--}}
{{--<option value="102">GRUPO QUIROPRACTICO DEL BAJIO S.A. DE C.V.</option>--}}
{{--<option value="187">HONDURAS SERVICIOS DE SALUD - SERVICIOS DE SALUD</option>--}}
{{--<option value="113">HOSPITAL DE LA MUJER</option>--}}
{{--<option value="122">HOSPITAL DEL NIÑO MORELENSE</option>--}}
{{--<option value="161">HOSPITAL GENERAL DE MEXICO DR. EDUARDO LICEAGA</option>--}}
{{--<option value="114">HOSPITAL REGIONAL DE ALTA ESPECIALIDAD DE CIUDAD VICTORIA</option>--}}
{{--<option value="120">HOSPITAL REGIONAL DE ALTA ESPECIALIDAD DEL BAJIO</option>--}}
{{--<option value="85">IMPLEMENTOS MEDICOS DE OCCIDENTE SA DE CV</option>--}}
{{--<option value="56">INDUSTRIAS ATENGO SA DE CV</option>--}}
{{--<option value="165">INSTITUTO CHIHUAHUENSE DE SALUD</option>--}}
{{--<option value="170">INSTITUTO CHIHUAHUENSE DE SALUD - ALM HOSP CENTRAL EDO</option>--}}
{{--<option value="180">INSTITUTO CHIHUAHUENSE DE SALUD - ALM HOSP GIN-OBS CUA</option>--}}
{{--<option value="183">INSTITUTO CHIHUAHUENSE DE SALUD - ALM HOSP GINOBS PARR</option>--}}
{{--<option value="177">INSTITUTO CHIHUAHUENSE DE SALUD - ALM HOSP INF ESP CHI</option>--}}
{{--<option value="175">INSTITUTO CHIHUAHUENSE DE SALUD - ALM HOSP REG DELICIA</option>--}}
{{--<option value="173">INSTITUTO CHIHUAHUENSE DE SALUD - ALM HOSP REG JIMENEZ</option>--}}
{{--<option value="181">INSTITUTO CHIHUAHUENSE DE SALUD - BOT HOSP GIN-OBS CUA</option>--}}
{{--<option value="178">INSTITUTO CHIHUAHUENSE DE SALUD - BOT HOSP INF ESP CHI</option>--}}
{{--<option value="176">INSTITUTO CHIHUAHUENSE DE SALUD - BOT HOSP REG DELICIA</option>--}}
{{--<option value="172">INSTITUTO CHIHUAHUENSE DE SALUD - BOT HOSP REG JIMENEZ</option>--}}
{{--<option value="182">INSTITUTO CHIHUAHUENSE DE SALUD - BOTICA INTRAHOSP SAL</option>--}}
{{--<option value="169">INSTITUTO CHIHUAHUENSE DE SALUD - FAR HOSP CENTRAL EDO</option>--}}
{{--<option value="184">INSTITUTO CHIHUAHUENSE DE SALUD - FAR HOSP GINOBS PARR</option>--}}
{{--<option value="174">INSTITUTO CHIHUAHUENSE DE SALUD - FAR ICHISAL JUAREZ</option>--}}
{{--<option value="171">INSTITUTO CHIHUAHUENSE DE SALUD - FAR INST CHIH SALUD</option>--}}
{{--<option value="185">INSTITUTO CHIHUAHUENSE DE SALUD - HOSP GRAL CD JUAREZ</option>--}}
{{--<option value="179">INSTITUTO CHIHUAHUENSE DE SALUD - HOSP MUJER CHIH</option>--}}
{{--<option value="164">INSTITUTO DE PENSIONES DEL ESTADO DE JALISCO</option>--}}
{{--<option value="195">INSTITUTO DE PENSIONES DEL ESTADO DE JALISCO - INTEGRAL</option>--}}
{{--<option value="39">INSTITUTO DE SALUD</option>--}}
{{--<option value="36">INSTITUTO DE SALUD DEL ESTADO DE MEXICO</option>--}}
{{--<option value="76">INSTITUTO DE SALUD DEL ESTADO DE MEXICO - HG IXTLAHUACA</option>--}}
{{--<option value="87">INSTITUTO DE SEGURIDAD SOCIAL DEL ESTADO DE TABASCO</option>--}}
{{--<option value="141">INSTITUTO DE SEGURIDAD Y SERVICIOS SOCIALES DE LOS TRABAJADORES DEL ESTADO - CONSOLIDADA T77</option>--}}
{{--<option value="91">INSTITUTO DE SEGURIDAD Y SERVICIOS SOCIALES DE LOS TRABAJADORES DEL ESTADO - DF</option>--}}
{{--<option value="40">INSTITUTO DE SEGURIDAD Y SERVICIOS SOCIALES DE LOS TRABAJADORES DEL ESTADO - TEPIC</option>--}}
{{--<option value="134">INSTITUTO DE SEGURIDAD Y SERVICIOS SOCIALES DE LOS TRABAJADORES DEL ESTADO DE SONORA</option>--}}
{{--<option value="137">INSTITUTO DE SERVICIOS DE SALUD DE BAJA CALIFORNIA SUR</option>--}}
{{--<option value="79">INSTITUTO DE SERVICIOS DE SALUD DEL ESTADO DE AGUASCALIENTES - AGUASCALIENTES</option>--}}
{{--<option value="61">INSTITUTO DE SERVICIOS DE SALUD PUBLICA DEL ESTADO DE BAJA CALIFORNIA</option>--}}
{{--<option value="149">INSTITUTO DE SERVICIOS DE SALUD PUBLICA DEL ESTADO DE BAJA CALIFORNIA - T77</option>--}}
{{--<option value="17">INSTITUTO JALISCIENSE DE CANCEROLOGIA</option>--}}
{{--<option value="146">INSTITUTO MATERNO INFANTIL DEL ESTADO DE MEXICO</option>--}}
{{--<option value="157">INSTITUTO MEXICANO DEL SEGURO SOCIAL - ALM DEL VER NTE</option>--}}
{{--<option value="98">INSTITUTO MEXICANO DEL SEGURO SOCIAL - CHIAPAS</option>--}}
{{--<option value="152">INSTITUTO MEXICANO DEL SEGURO SOCIAL - CMN 14</option>--}}
{{--<option value="112">INSTITUTO MEXICANO DEL SEGURO SOCIAL - CONSOLIDADA T77-2015</option>--}}
{{--<option value="167">INSTITUTO MEXICANO DEL SEGURO SOCIAL - DEL EST CHIHUAHUA</option>--}}
{{--<option value="55">INSTITUTO MEXICANO DEL SEGURO SOCIAL - DF SUR</option>--}}
{{--<option value="49">INSTITUTO MEXICANO DEL SEGURO SOCIAL - EDOMEX PTE</option>--}}
{{--<option value="97">INSTITUTO MEXICANO DEL SEGURO SOCIAL - GUANAJUATO</option>--}}
{{--<option value="77">INSTITUTO MEXICANO DEL SEGURO SOCIAL - HGO 221</option>--}}
{{--<option value="73">INSTITUTO MEXICANO DEL SEGURO SOCIAL - HGR 220</option>--}}
{{--<option value="63">INSTITUTO MEXICANO DEL SEGURO SOCIAL - HGZ 51</option>--}}
{{--<option value="62">INSTITUTO MEXICANO DEL SEGURO SOCIAL - HGZ 89</option>--}}
{{--<option value="156">INSTITUTO MEXICANO DEL SEGURO SOCIAL - HGZ1 VERACRUZ</option>--}}
{{--<option value="45">INSTITUTO MEXICANO DEL SEGURO SOCIAL - JALISCO</option>--}}
{{--<option value="147">INSTITUTO MEXICANO DEL SEGURO SOCIAL - NUEVO LEON</option>--}}
{{--<option value="59">INSTITUTO MEXICANO DEL SEGURO SOCIAL - TEPIC</option>--}}
{{--<option value="64">INSTITUTO MEXICANO DEL SEGURO SOCIAL - U.M.F. NO. 34</option>--}}
{{--<option value="159">INSTITUTO MEXICANO DEL SEGURO SOCIAL - UMAE 14 VERACRUZ</option>--}}
{{--<option value="42">INSTITUTO MEXICANO DEL SEGURO SOCIAL - UMAE GTO</option>--}}
{{--<option value="148">INSTITUTO MEXICANO DEL SEGURO SOCIAL - UMAE PEDIATRIA CMN</option>--}}
{{--<option value="41">INSTITUTO MEXICANO DEL SEGURO SOCIAL - UMAE U3</option>--}}
{{--<option value="66">INSTITUTO MEXICANO DEL SEGURO SOCIAL - UMF 171</option>--}}
{{--<option value="115">INSTITUTO NACIONAL DE CARDIOLOGIA</option>--}}
{{--<option value="118">INSTITUTO NACIONAL DE CIENCIAS MEDICAS Y NUTRICION</option>--}}
{{--<option value="116">INSTITUTO NACIONAL DE ENFERMEDADES RESPIRATORIAS ISMAEL COSIO VILLEGAS</option>--}}
{{--<option value="119">INSTITUTO NACIONAL DE NEUROLOGIA Y NEUROCIRUGIA MANUEL VELASCO SUAREZ</option>--}}
{{--<option value="86">INSTITUTO NACIONAL DE PEDIATRIA</option>--}}
{{--<option value="48">INSTITUTO NACIONAL DE PERINATOLOGIA ISIDRO ESPINOSA DE LOS REYES</option>--}}
{{--<option value="27">INTERCAMBIO GLOBAL LATINOAMERICA SA DE CV</option>--}}
{{--<option value="81">IRMIN JAIR ROMERO DIAZ</option>--}}
{{--<option value="33">KARINA LUCIA NAVARRO PEREZ</option>--}}
{{--<option value="16">LABORATORIOS LE ROY S.A DE C.V</option>--}}
{{--<option value="34">LABORATORIOS SOLFRAN S.A.</option>--}}
{{--<option value="5">LO VENDING GROUP, S.A DE C.V</option>--}}
{{--<option value="99">LOGTRADE S DE RL DE CV</option>--}}
{{--<option value="1">LOMEDIC S.A DE C.V.</option>--}}
{{--<option value="80">MARIA ANA JACARANDA CORIA CAMACHO</option>--}}
{{--<option value="89">MC KLINICAL S.A DE C.V.</option>--}}
{{--<option value="31">MEXICO ME UNO AC</option>--}}
{{--<option value="10">MUNICIPIO DE CHINICUILA MICHOACAN</option>--}}
{{--<option value="11">MUNICIPIO DE PUERTO VALLARTA</option>--}}
{{--<option value="100">MUNICIPIO DE SAN PEDRO TLAQUEPAQUE</option>--}}
{{--<option value="7">MUNICIPIO DE TUXPAN MICHOACAN</option>--}}
{{--<option value="6">OPTIMIZACION CH S.A DE C.V</option>--}}
{{--<option value="58">ORGANISMO PUBLICO DESCENTRALIZADO HOSPITAL CIVIL DE GUADALAJARA - CIVIL ANTIGUO</option>--}}
{{--<option value="160">ORGANISMO PUBLICO DESCENTRALIZADO HOSPITAL CIVIL DE GUADALAJARA - CIVIL NUEVO</option>--}}
{{--<option value="151">PENSIONES CIVILES DEL ESTADO DE CHIHUAHUA</option>--}}
{{--<option value="69">PETROLEOS MEXICANOS</option>--}}
{{--<option value="3">PICHSA PARA HOSPITALES, S.A DE C.V</option>--}}
{{--<option value="168">PRODUCTO PARA MUESTRAS DEPTO DE VENTAS - MUESTRAS</option>--}}
{{--<option value="139">PRODUCTOS Y MEDICAMENTOS DE ORIENTE, S.A. DE C.V.</option>--}}
{{--<option value="30">PROVEEDORA DE INSUMOS HAKERI SA DE CV</option>--}}
{{--<option value="37">PROVEEDORA GI SA DE CV</option>--}}
{{--<option value="72">PUBLICO EN GENERAL</option>--}}
{{--<option value="138">REGIMEN ESTATAL DE PROTECCION SOCIAL EN SALUD DE MORELOS</option>--}}
{{--<option value="13">RENAL HEALTH S.A DEC.V</option>--}}
{{--<option value="90">SALUD DE TLAXCALA</option>--}}
{{--<option value="126">SALUD DE TLAXCALA - T77</option>--}}
{{--<option value="23">SECRETARIA DE GOBERNACION. ORGANO ADMINISTRATIVO DESCONCENTRADO PREVENCION Y READAPTACION SOCIAL</option>--}}
{{--<option value="75">SECRETARIA DE LA DEFENSA NACIONAL</option>--}}
{{--<option value="84">SECRETARIA DE LA DEFENSA NACIONAL - ALM GRAL SANI</option>--}}
{{--<option value="133">SECRETARIA DE MARINA</option>--}}
{{--<option value="57">SECRETARIA DE PLANEACION, ADMINISTRACION Y FINANZAS</option>--}}
{{--<option value="110">SECRETARIA DE SALUD / CENTRO NACIONAL DE PROGRAMAS PREVENTIVOS Y CONTROL DE ENFERMEDADES</option>--}}
{{--<option value="111">SECRETARIA DE SALUD / DIRECCION GENERAL DE EPIDEMIOLOGIA</option>--}}
{{--<option value="127">SECRETARIA DE SALUD DEL ESTADO DE GUERRERO</option>--}}
{{--<option value="26">SECRETARIA DE SALUD. CENTRO NACIONAL DE PROGRAMAS PREVENTIVOS Y CONTROL DE ENFERMEDADES</option>--}}
{{--<option value="154">SECRETARIA DE SALUD/CENSIDA</option>--}}
{{--<option value="155">SECRETARIA DE SALUD/HOSPITAL DE LA MUJER</option>--}}
{{--<option value="108">SECRETARIA DE SALUD/HOSPITAL JUAREZ DEL CENTRO</option>--}}
{{--<option value="109">SECRETARIA DE SALUD/HOSPITAL NACIONAL HOMEOPATICO</option>--}}
{{--<option value="124">SECRETARIA DE SALUD/SERVICIOS DE ATENCION PSIQUIATRICA/CENTRO COMUNITARIO DE SALUD MENTAL CUAUHTEMOC</option>--}}
{{--<option value="106">SECRETARIA DE SALUD/SERVICIOS DE ATENCION PSIQUIATRICA/CENTRO COMUNITARIO DE SALUD MENTAL IZTAPALAPA</option>--}}
{{--<option value="107">SECRETARIA DE SALUD/SERVICIOS DE ATENCION PSIQUIATRICA/CENTRO COMUNITARIO DE SALUD MENTAL ZACATENCO</option>--}}
{{--<option value="105">SECRETARIA DE SALUD/SERVICIOS DE ATENCION PSIQUIATRICA/HOSPITAL PSIQUIATRICO DR. SAMUEL RAMIREZ MORENO</option>--}}
{{--<option value="103">SECRETARIA DE SALUD/SERVICIOS DE ATENCION PSIQUIATRICA/HOSPITAL PSIQUIATRICO FRAY BERNARDINO ALVAREZ</option>--}}
{{--<option value="104">SECRETARIA DE SALUD/SERVICIOS DE ATENCION PSIQUIATRICA/HOSPITAL PSIQUIATRICO INFANTIL DR. JUAN N. NAVARRO</option>--}}
{{--<option value="163">SEGUROS BANORTE SA DE CV GFB</option>--}}
{{--<option value="123">SERVICIOS DE SALUD DE BAJA CALIFORNIA</option>--}}
{{--<option value="131">SERVICIOS DE SALUD DE DURANGO</option>--}}
{{--<option value="150">SERVICIOS DE SALUD DE MORELOS - T77</option>--}}
{{--<option value="4">SERVICIOS DE SALUD DE NAYARIT</option>--}}
{{--<option value="162">SERVICIOS DE SALUD DE NAYARIT - CANCEROLOGIA NAYARIT</option>--}}
{{--<option value="78">SERVICIOS DE SALUD DE SAN LUIS POTOSI</option>--}}
{{--<option value="129">SERVICIOS DE SALUD DE SONORA</option>--}}
{{--<option value="130">SERVICIOS DE SALUD DE VERACRUZ</option>--}}
{{--<option value="25">SERVICIOS DE SALUD DE ZACATECAS</option>--}}
{{--<option value="128">SERVICIOS DE SALUD DEL ESTADO DE COLIMA</option>--}}
{{--<option value="135">SERVICIOS DE SALUD DEL ESTADO DE QUERETARO</option>--}}
{{--<option value="88">SERVICIOS DE SALUD DEL ESTADO DE TABASCO</option>--}}
{{--<option value="96">SERVICIOS DE SALUD DEL ESTADO DE TABASCO - HOSP J GRAHAM</option>--}}
{{--<option value="22">SERVICIOS DE SALUD JALISCO</option>--}}
{{--<option value="144">SERVICIOS DE SALUD JALISCO - HGO ZOQUIPAN</option>--}}
{{--<option value="210">SERVICIOS DE SALUD JALISCO - INTEGRAL</option>--}}
{{--<option value="188">SERVICIOS DE SALUD JALISCO - SP</option>--}}
{{--<option value="52">SERVICIOS DE SALUD MICHOACAN</option>--}}
{{--<option value="21">SERVICIOS DE SALUD PUBLICA DEL DISTRITO FEDERAL</option>--}}
{{--<option value="54">SERVICIOS DE SALUD PUBLICA DEL DISTRITO FEDERAL - XOCONGO</option>--}}
{{--<option value="70">SERVICIOS ESTATALES DE SALUD - GUERRERO</option>--}}
{{--<option value="2">SERVICIOS ESTATALES DE SALUD - T77</option>--}}
{{--<option value="145">SERVICIOS ESTATALES DE SALUD - T77 QROO</option>--}}
{{--<option value="18">SOLUCIONES PARA LA SALUD S.C. - VITAL CORP</option>--}}
{{--<option value="51">SOLUGLOB IKON SA DE CV - VTA DIR</option>--}}
{{--<option value="50">TECNOLOGIA DE ALTA ESPECIALIDAD S. DE R.L. DE C.V.</option>--}}
{{--<option value="93">UNIVERSIDAD TECNOLOGICA DEL VALLE DE TOLUCA</option>--}}
{{--<option value="8">ZAIRA KARINA FALCON BARRAGAN</option>--}}
{{--</select>--}}

{{--Observación Reporte:--}}
{{--<textarea id="observacion_ni" class="a100" onkeypress="return validar(event,this,'alfanumerico');" maxlength="300" style="resize:none"></textarea>--}}





	{{--</div>--}}




	{{--<div class="input-field col s4 m4">--}}
		{{--{!! Form::text(array_has($data,'fk_id_solicitante')?'solicitante_formated':'solicitante',null, ['id'=>'solicitante','autocomplete'=>'off','data-url'=>companyAction('RecursosHumanos\EmpleadosController@obtenerEmpleados'),'data-url2'=>companyAction('RecursosHumanos\EmpleadosController@obtenerEmpleado')]) !!}--}}
		{{--{{ Form::label('solicitante', '* Solicitante') }}--}}
		{{--{{ $errors->has('fk_id_solicitante') ? HTML::tag('span', $errors->first('fk_id_solicitante'), ['class'=>'help-block deep-orange-text']) : '' }}--}}
		{{--{{Form::hidden('fk_id_solicitante',null,['id'=>'fk_id_solicitante','data-url'=>companyAction('Administracion\SucursalesController@sucursalesEmpleado',['id'=>'?id'])])}}--}}
	{{--</div>--}}
	{{--<div class="input-field col s4 m4">--}}
		{{--Se utilizan estas comprobaciones debido a que este campo se carga dinámicamente con base en el solicitante seleccionado y no se muestra el que está por defecto sin esto--}}
		{{--@if(Route::currentRouteNamed(currentRouteName('edit')))--}}
			{{--{!! Form::select('fk_id_sucursal', isset($sucursalesempleado)?$sucursalesempleado:[],null, ['id'=>'fk_id_sucursal']) !!}--}}
			{{--{{ Form::label('fk_id_sucursal', '* Sucursal') }}--}}
			{{--{!! Form::hidden('sucursal_defecto',$data->fk_id_sucursal,['id'=>'sucursal_defecto']) !!}--}}
		{{--@elseif(Route::currentRouteNamed(currentRouteName('show')))--}}
			{{--{!! Form::text('sucursal',$data->sucursales->where('id_sucursal',$data->fk_id_sucursal)->first()->nombre_sucursal) !!}--}}
			{{--{{ Form::label('fk_id_sucursal', '* Sucursal') }}--}}
		{{--@elseif(Route::currentRouteNamed(currentRouteName('create')))--}}
			{{--{!! Form::select('fk_id_sucursal', isset($sucursalesempleado)?$sucursalesempleado:[],null, ['id'=>'fk_id_sucursal']) !!}--}}
			{{--{{ Form::label('fk_id_sucursal', '* Sucursal') }}--}}
		{{--@endif--}}
		{{--{{ $errors->has('fk_id_sucursal') ? HTML::tag('span', $errors->first('fk_id_sucursal'), ['class'=>'help-block deep-orange-text']) : '' }}--}}
	{{--</div>--}}
	{{--<div class="input-field col s2 m2">--}}
		{{--{{ Form::label('fecha_necesidad', '* ¿Para cuándo se necesita?') }}--}}
		{{--{!! Form::text('fecha_necesidad',null,['id'=>'fecha_necesidad','class'=>'datepicker','value'=>old('fecha_necesidad')]) !!}--}}
	{{--</div>--}}
	{{--<div class="input-field col s2 m2">--}}
		{{--{!! Form::select('fk_id_estatus_solicitud', \App\Http\Models\Compras\EstatusSolicitudes::all()->pluck('estatus','id_estatus'),null, ['id'=>'fk_id_sucursal']) !!}--}}
		{{--@if(Route::currentRouteNamed(currentRouteName('edit')) || Route::currentRouteNamed(currentRouteName('show')))--}}
			{{--{!! Form::text('estatus_solicitud',$data->estatus->estatus,['disabled']) !!}--}}
		{{--@elseif(Route::currentRouteNamed(currentRouteName('create')))--}}
			{{--{!! Form::text('estatus_solicitud','Abierto',['disabled']) !!}--}}
		{{--@endif--}}
		{{--{{ Form::label('estatus_solicitud', '* Estatus de la solicitud') }}--}}
	{{--</div>--}}
	{{--Si la solicitud está cancelada--}}
		{{--@if(isset($data->fk_id_estatus_solicitud) && $data->fk_id_estatus_solicitud ==3)--}}
			{{--<div class="input-field col s2 m2">--}}
				{{--{!! Form::text('fecha_cancelacion',$data->fecha_cancelacion,['disabled']) !!}--}}
				{{--{{ Form::label('fecha_cancelacion','Fecha de cancelación') }}--}}
			{{--</div>--}}
			{{--<div class="input-field col s10 m10">--}}
				{{--{!! Form::text('motivo_cancelacion',$data->motivo_cancelacion,['disabled']) !!}--}}
				{{--{{ Form::label('motivo_cancelacion','Motivo de la cancelación') }}--}}
			{{--</div>--}}
		{{--@endif--}}
{{--</div>--}}
{{--<div class="divider"></div>--}}
{{--<div class="row">--}}
	{{--<div class="col s12 m12">--}}
		{{--<h5>Detalle de la solicitud</h5>--}}
		{{--<div class="card">--}}
			{{--<div class="card-image teal lighten-5">--}}
				{{--<div class="row">--}}
					{{--<div class="input-field col s4">--}}
						{{--{!!Form::text('fk_id_sku',null,['id'=>'fk_id_sku','autocomplete'=>'off','class'=>'validate',--}}
						{{--'data-url'=>companyAction('Inventarios\SkusController@obtenerSkus'),'aria-required'=>'true'])!!}--}}
						{{--{{Form::label('fk_id_sku','SKU')}}--}}
					{{--</div>--}}
					{{--<div class="input-field col s4">--}}
						{{--{!! Form::select('fk_id_codigo_barras',[],null,['id'=>'fk_id_codigo_barras','disabled',--}}
						{{--'data-url'=>companyAction('Inventarios\CodigosBarrasController@obtenerCodigosBarras',['id'=>'?id'])]) !!}--}}
						{{--{{Form::label('fk_id_codigo_barras','Código de barras')}}--}}
					{{--</div>--}}
					{{--<div class="input-field col s4">--}}
						{{--{!!Form::text('fk_id_proveedor',null,['id'=>'fk_id_proveedor','autocomplete'=>'off','class'=>'validate'])!!}--}}
						{{--{{Form::label('fk_id_proveedor','Proveedor')}}--}}
					{{--</div>--}}
					{{--<div class="input-field col s4">--}}
						{{--{{ Form::label('fecha_necesario', '* ¿Para cuándo se necesita?') }}--}}
						{{--{!! Form::text('fecha_necesario',null,['id'=>'fecha_necesario','class'=>'datepicker','value'=>old('fecha_necesario')]) !!}--}}
					{{--</div>--}}
					{{--<div class="input-field col s4">--}}
						{{--{!!Form::text('fk_id_proyecto',null,['id'=>'fk_id_proyecto','autocomplete'=>'off','class'=>'validate',--}}
						{{--'data-url'=> companyAction('Proyectos\ProyectosController@obtenerProyectos')])!!}--}}
						{{--{{Form::label('fk_id_proyecto','Proyecto')}}--}}
					{{--</div>--}}
					{{--<div class="input-field col s4">--}}
						{{--{!! Form::text('cantidad','1',['id'=>'cantidad','min'=>'1','class'=>'validate','onkeyup' =>'validateCantidad(this)','autocomplete'=>'off']) !!}--}}
						{{--{{Form::label('cantidad','Cantidad')}}--}}
					{{--</div>--}}
					{{--<div class="input-field col s4">--}}
						{{--{!! Form::select('fk_id_unidad_medida',--}}
						{{--isset($unidadesmedidas) ? $unidadesmedidas : [],--}}
						{{--null,['id'=>'fk_id_unidad_medida']) !!}--}}
						{{--{{Form::label('fk_id_unidad_medida','Unidad de medida')}}--}}
					{{--</div>--}}
					{{--<div class="input-field col s4">--}}
						{{--{!! Form::select('fk_id_impuesto',(isset($impuestos) ? $impuestos : [])--}}
						{{--,null,['id'=>'fk_id_impuesto',--}}
						{{--'data-url'=>companyAction('Finanzas\ImpuestosController@obtenerPorcentaje',['id'=>'?id'])]) !!}--}}
						{{--{{Form::label('fk_id_impuesto','Tipo de impuesto')}}--}}
						{{--{{Form::hidden('impuesto',null,['id'=>'impuesto'])}}--}}
					{{--</div>--}}
					{{--<div class="input-field col s4">--}}
						{{--{!! Form::text('precio_unitario',old('precio_unitario'),['id'=>'precio_unitario','class'=>'validate','onkeyup' =>'validatePrecioUnitario(this)','autocomplete'=>'off']) !!}--}}
						{{--{{Form::label('precio_unitario','Precio unitario',['class'=>'validate'])}}--}}
					{{--</div>--}}
					{{--<button class="btn-floating btn-large orange halfway-fab waves-effect waves-light tooltipped"--}}
							{{--data-position="bottom" data-delay="50" data-tooltip="Agregar" type="button" onclick="agregarProducto()"><i--}}
								{{--class="material-icons">add</i></button>--}}
				{{--</div>--}}
			{{--</div>--}}
			{{--<div class="divider"></div>--}}
			{{--<div class="card-content">--}}
				{{--<table id="productos" class="responsive-table highlight" data-url="{{companyAction('Compras\SolicitudesController@store')}}"--}}
				{{--data-delete="{{companyAction('Compras\DetalleSolicitudesController@destroyMultiple')}}"--}}
				{{--data-impuestos="{{companyAction('Finanzas\ImpuestosController@obtenerImpuestos')}}"--}}
						{{--data-porcentaje="{{companyAction('Finanzas\ImpuestosController@obtenerPorcentaje',['id'=>'?id'])}}">--}}
					{{--<thead>--}}
						{{--<tr>--}}
							{{--<th id="idsku">SKU</th>--}}
							{{--<th id="idcodigobarras">Código de Barras</th>--}}
							{{--<th id="idproveedor">Proveedor</th>--}}
							{{--<th>Fecha necesidad</th>--}}
							{{--<th id="idproyecto" >Proyecto</th>--}}
							{{--<th>Cantidad</th>--}}
							{{--<th id="idunidadmedida" >Unidad de medida</th>--}}
							{{--<th id="idimpuesto" >Tipo de impuesto</th>--}}
							{{--<th>Precio unitario</th>--}}
							{{--<th>Total</th>--}}
							{{--<th></th>--}}
						{{--</tr>--}}
					{{--</thead>--}}
					{{--<tbody>--}}
					{{--@if( isset( $detalles ) )--}}
						{{--@foreach( $detalles as $detalle)--}}
							{{--<tr>--}}
								{{--<td>--}}
									{{--{!! Form::hidden('detalles['.$detalle->id_solicitud_detalle.'][id_solicitud_detalle]',$detalle->id_solicitud_detalle) !!}--}}
									{{--{!! Form::hidden('detalles['.$detalle->id_solicitud_detalle.'][fk_id_sku]',$detalle->fk_id_sku) !!}--}}
									{{--{{$detalle->sku->sku}}--}}
								{{--</td>--}}
								{{--<td>--}}
									{{--{!! Form::hidden('detalles['.$detalle->id_solicitud_detalle.'][fk_id_codigo_barras]',$detalle->fk_id_codigo_barras) !!}--}}
									{{--{{$detalle->codigo_barras->descripcion}}--}}
								{{--</td>--}}
								{{--<td>--}}
									{{--{!! Form::hidden('detalles['.$detalle->id_solicitud_detalle.'][fk_id_proveedor]',$detalle->fk_id_proveedor) !!}--}}
								{{--</td>--}}
								{{--<td>--}}
									{{--{!! Form::hidden('detalles['.$detalle->id_solicitud_detalle.'][fecha_necesario]',$detalle->fecha_necesario) !!}--}}
									{{--{{$detalle->fecha_necesario}}</td>--}}
								{{--<td>--}}
									{{--@if(!Route::currentRouteNamed(currentRouteName('edit')))--}}
										{{--{!! Form::hidden('detalles['.$detalle->id_solicitud_detalle.'][fk_id_proyecto]',$detalle->fk_id_proyecto) !!}--}}
										{{--{{$detalle->proyecto->proyecto}}--}}
									{{--@else--}}
										{{--{!! Form::select('detalles['.$detalle->id_solicitud_detalle.'][fk_id_proyecto]',--}}
												{{--isset($proyectos) ? $proyectos : null,--}}
												{{--$detalle->id_proyecto,['id'=>'detalles['.$detalle->id_solicitud_detalle.'][fk_id_proyecto]',--}}
												{{--'class'=>'detalle_row_select'])--}}
										{{--!!}--}}
									{{--@endif--}}
								{{--</td>--}}
								{{--<td>--}}
									{{--@if (!Route::currentRouteNamed(currentRouteName('edit')))--}}
										{{--{!! Form::hidden('detalles['.$detalle->id_solicitud_detalle.'][cantidad]',$detalle->cantidad) !!}--}}
										{{--{{$detalle->cantidad}}--}}
									{{--@else--}}
										{{--{!! Form::text('detalles['.$detalle->id_solicitud_detalle.'][cantidad]',$detalle->cantidad,--}}
										{{--['class'=>'',--}}
										{{--'id'=>'cantidad'.$detalle->id_solicitud_detalle,--}}
										{{--'onkeyup' =>'validateCantidad(this)',--}}
										{{--'onkeypress'=>'total_producto_row('.$detalle->id_solicitud_detalle.',"old")']) !!}--}}
									{{--@endif--}}
								{{--</td>--}}
								{{--<td>--}}
									{{--{!! Form::hidden('detalles['.$detalle->id_solicitud_detalle.'][fk_unidad_medida]',$detalle->fk_unidad_medida) !!}--}}
									{{--{{$detalle->unidad_medida->nombre}}--}}
								{{--</td>--}}
								{{--<td>--}}
									{{--@if (!Route::currentRouteNamed(currentRouteName('edit')))--}}
										{{--{!! Form::hidden('detalles['.$detalle->id_solicitud_detalle.'][fk_id_impuesto]',$detalle->fk_id_impuesto) !!}--}}
										{{--{{$detalle->impuesto->impuesto}}--}}
									{{--@else--}}
										{{--{!! Form::select('detalles['.$detalle->id_solicitud_detalle.'][fk_id_impuesto]',$impuestos,--}}
												{{--$detalle->fk_id_impuesto,['id'=>'fk_id_impuesto'.$detalle->id_solicitud_detalle,--}}
												{{--'onchange'=>'total_producto_row('.$detalle->id_solicitud_detalle.',"old")'])--}}
										{{--!!}--}}
									{{--@endif--}}
								{{--</td>--}}
								{{--<td>--}}
									{{--@if(!Route::currentRouteNamed(currentRouteName('edit')))--}}
										{{--{!! Form::hidden('detalles['.$detalle->id_solicitud_detalle.'][precio_unitario]',$detalle->precio_unitario) !!}--}}
										{{--{{number_format($detalle->precio_unitario,2,'.','')}}--}}
									{{--@else--}}
										{{--{!! Form::text('detalles['.$detalle->id_solicitud_detalle.'][precio_unitario]',number_format($detalle->precio_unitario,2,'.','')--}}
										{{--,['class'=>'','onkeyup' =>'validatePrecioUnitario(this)','onkeypress'=>'total_producto_row('.$detalle->id_solicitud_detalle.',"old")',--}}
										{{--'id'=>'precio_unitario'.$detalle->id_solicitud_detalle]) !!}--}}
									{{--@endif--}}
								{{--</td>--}}
								{{--<td>--}}
									{{--@if (!Route::currentRouteNamed(currentRouteName('edit')))--}}
										{{--{!! Form::hidden('detalles['.$detalle->id_solicitud_detalle.'][total]',$detalle->total) !!}--}}
										{{--{{number_format($detalle->total,2,'.','')}}--}}
									{{--@else--}}
										{{--{!! Form::text('detalles['.$detalle->id_solicitud_detalle.'][total]',number_format($detalle->total,2,'.','')--}}
										{{--,['class'=>'','id'=>'total'.$detalle->id_solicitud_detalle,'readonly'])!!}--}}
									{{--@endif--}}
								{{--<td>--}}
									{{--Si se va a editar, agrega el botón para "eliminar" la fila--}}
									{{--@if(Route::currentRouteNamed(currentRouteName('edit')))--}}
										{{--<a href="#" class="btn-flat teal lighten-5 halfway-fab waves-effect waves-light"--}}
										   {{--type="button" data-item-id="{{$detalle->id_solicitud_detalle}}"--}}
										   {{--id="{{$detalle->id_solicitud_detalle}}" data-delay="50"--}}
										   {{--onclick="borrarFila_edit(this)" data-delete-type="single">--}}
										{{--<i class="material-icons">delete</i></a>--}}
									{{--@endif--}}
								{{--</td>--}}
							{{--</tr>--}}
						{{--@endforeach--}}
					{{--@endif--}}
					{{--</tbody>--}}
				{{--</table>--}}
			{{--</div>--}}
		{{--</div>--}}
	{{--</div>--}}
{{--</div>--}}
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