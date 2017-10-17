@section('header-top')
	{{ HTML::style(asset('vendor/vanilla-dataTables/vanilla-dataTables.css')) }}
@endsection

@section('content-width', 'w-100')

@section('form-content')
{{ Form::setModel($data) }}
<div class="card z-depth-1-half">
	<div class="card-header py-2 text-center">
        <h4 class="card-title text-danger">Datos del proyecto</h4>
    </div>
    <div class="card-body row">
		<div class="form-group col-md-6 col-xs-12">
			{{Form::cText('Proyecto','proyecto',['id'=>'proyecto','maxlength'=>'255','data-url'=>companyAction('Proyectos\ProyectosController@obtenerProyectosCliente',['id'=>'?id'])])}}
		</div>
		<div class="form-group col-md-6 col-xs-12">
			{{Form::cSelectWithDisabled('Cliente','fk_id_cliente',isset($clientes)?$clientes:[])}}
			{{--{{Form::cSelectWithDisabled('Cliente','fk_id_cliente',isset($clientes)?$clientes:[],['id'=>'fk_id_cliente'])}}--}}
			{{--{{Form::label('fk_id_cliente','Cliente')}}--}}
			{{--{{Form::select('fk_id_cliente',isset($clientes)?$clientes->prepend('...',null):[],null,['id'=>'fk_id_cliente','class'=>'form-control'])}}--}}
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
			{{Form::cText('Número de contrato','numero_contrato',['maxlength'=>'200'])}}
		</div>
		<div class="form-group col-md-3 col-xs-12">
			{{Form::label('monto_adjudicado','Monto adjudicado')}}
			{{Form::text('monto_adjudicado',isset($data->monto_adjudicado)?number_format($data->monto_adjudicado,2,'.',''):null,['id'=>'monto_adjudicado','class'=>'form-control','maxlength'=>'13'])}}
		</div>
		<div class="form-group col-md-3 col-xs-12">
			{{Form::cSelectWithDisabled('Clasificación','fk_id_clasificacion_proyecto',isset($clasificaciones)?$clasificaciones:[])}}
		</div>
		<div class="form-group col-md-3 col-xs-12">
			{{Form::cText('Plazo','plazo')}}
		</div>
		<div class="form-group col-md-6 col-xs-12">
			{{Form::cText('Representante legal','representante_legal',['maxlength'=>'200'])}}
		</div>
		<div class="form-group col-md-5 col-xs-12">
			{{Form::cText('Número de fianza','numero_fianza',['maxlength'=>'60'])}}
		</div>
		<div class="form-group col-md-1 col-xs-12">
            {{Form::cCheckboxSwitch('Activo','activo','1')}}
			{{--<div data-toggle="buttons">--}}
				{{--<label class="btn btn-secondary form-check-label {{ !empty($data->activo) || old('activo') ? 'active':''}}">--}}
					{{--{{Form::checkbox('activo',true,old('activo'),['id'=>'activo',Route::currentRouteNamed(currentRouteName('show'))?'disabled':''])}}--}}
					{{--Activo--}}
				{{--</label>--}}
			{{--</div>--}}
		</div>
	</div>
</div>

<div id="detallesku" class="container-fluid w-100 mt-2 px-0">
	<div class="card text-center z-depth-1-half" style="min-height: 555px">
		<div class="card-header py-2">
    		<h4 class="card-title text-info">Informacion del Proyecto</h4>
            <div class="divider my-2"></div>
			<ul id="clothing-nav" class="nav nav-pills nav-justified" role="tablist">
				<li class="nav-item">
					<a class="nav-link active" role="tab" data-toggle="tab" href="#tab-productosProyectos" id="productosProyectos-tab" aria-controls="productosProyectos" aria-expanded="true">Productos</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" role="tab" data-toggle="tab" href="#tab-general" id="General-tab" aria-controls="general" aria-expanded="true">General</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" role="tab" data-toggle="tab" href="#tab-finanzas" id="finanzas-tab" aria-controls="finanzas" aria-expanded="true">Finanzas</a>
				</li>
			</ul>
		</div>
		<!-- Content Panel -->
			<div id="clothing-nav-content" class="card-body tab-content">
				<div role="tabpanel" class="tab-pane fade show active" id="tab-productosProyectos" aria-labelledby="productosProyectos-tab">
					<div class="card">
						@if(!Route::currentRouteNamed(currentRouteName('show')))
							<div class="card-header">
								<fieldset name="detalle-form" id="detalle-form">
									<div class="row">
										<div class="form-group input-field col-md-6 col-sm-6">
                                            <div id="loadingfk_id_clave_cliente_producto" class="w-100 h-100 text-center text-white align-middle loadingData" style="display: none">
                                                Cargando datos... <i class="material-icons align-middle loading">cached</i>
                                            </div>
											{{Form::label('fk_id_clave_cliente_producto','* Clave cliente producto')}}
											{!!Form::select('fk_id_clave_cliente_producto',[],null,['id'=>'fk_id_clave_cliente_producto','disabled','class'=>'form-control','style'=>'width:100%','data-url'=>companyAction('Proyectos\ClaveClienteProductosController@obtenerClavesCliente',['id'=>'?id'])])!!}
											{{--								{{Form::cSelect('Clave cliente producto','fk_id_clave_cliente_producto',[],['disabled'])}}--}}
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
												{!! Form::select('fk_id_upc',[],null,['id'=>'fk_id_upc','disabled',
                                                'data-url'=>companyAction('Inventarios\ProductosController@obtenerUpcs',['id'=>'?id']),
                                                'class'=>'form-control','style'=>'width:100%']) !!}
											</div>
										</div>
									</div>
									<div>
										<div class="col-sm-12 text-center border">
											<div class="sep">
												<div class="sepText bg-light">
													ó
												</div>
											</div>
										</div>
										<p class="text-center mt-2">Puedes descargar un layout e importar el excel</p>
									</div>
									<div class="row mt-3">
										<div class="form-goup col-md-6 text-center">
											<a href="{{companyAction('layoutProductosProyecto')}}" id="layout" class="btn btn-primary">Descargar Layout</a>
{{--												{!! Form::button('Descargar Layout',['id'=>'layout','class'=>'btn btn-primary','name'=>'layout','href'=>])!!}--}}
											</div>
											<div class="form-goup col-md-6">
												<label class="custom-file">
													<input type="file" id="file_xlsx" name="file_xlsx" data-url="{{companyAction('loadLayoutProductosProyectos')}}">
													<span class="custom-file-control"></span>
												</label>
											</div>
											<div class="col-sm-12 text-center">
												<div class="sep">
													<div class="sepBtn">
														<button style="width: 4em; height:4em; border-radius:50%;" class="btn btn-primary btn-large tooltipped "
															data-position="bottom" data-delay="50" data-tooltip="Agregar" type="button" id="agregar">
														<i class="material-icons">add</i>
													</button>
												</div>
											</div>
										</div>
								</div>
							</fieldset>
						</div>
					@endif
					<div class="card-body">
						<table id="productosproyectos" class="table-responsive highlight"
							   @if(isset($data->ProyectosProductos))
							   data-delete="{{companyAction('Proyectos\ProyectosProductosController@destroy')}}"
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
							@if( isset( $data->ProyectosProductos ) )
								@foreach( $data->ProyectosProductos->where('eliminar',false) as $ProyectoProducto )
									<tr id="{{$ProyectoProducto->id_proyecto_producto}}">
										<td>
											{!! Form::hidden('productoProyecto['.$ProyectoProducto->id_proyecto_producto.'][id_proyecto_producto]',$ProyectoProducto->id_proyecto_producto) !!}
											{{$ProyectoProducto->claveClienteProducto->clave_producto_cliente}}
										</td>
										<td>
											{{$ProyectoProducto->claveClienteProducto->sku->descripcion_corta}}
										</td>
										<td>
											{{isset($ProyectoProducto->upc)?$ProyectoProducto->upc->upc:'Sin UPC'}}
										</td>
										<td>
											{{isset($ProyectoProducto->upc)?$ProyectoProducto->upc->descripcion:''}}
										</td>
										<td>
											{!!
												Form::text('productoProyecto['.$ProyectoProducto->id_proyecto_producto.'][prioridad]',
												$ProyectoProducto->prioridad,
												['class'=>'form-control prioridad','maxlength'=>'2'])
											!!}
										</td>
										<td>
											{!!
												Form::text('productoProyecto['.$ProyectoProducto->id_proyecto_producto.'][cantidad]',
												$ProyectoProducto->cantidad,
												['class'=>'form-control cantidad','maxlength'=>'3'])
											!!}
										</td>
										<td>
											{!!
												Form::text('productoProyecto['.$ProyectoProducto->id_proyecto_producto.'][precio_sugerido]',
												bcdiv($ProyectoProducto->precio_sugerido,'1',2),
												['class'=>'form-control precio_sugerido','maxlength'=>'13'])
											!!}
										</td>
										<td>
											{!!
												Form::text('productoProyecto['.$ProyectoProducto->id_proyecto_producto.'][maximo]',
												$ProyectoProducto->maximo,
												['class'=>'form-control maximo','maxlength'=>'4'])
											!!}
										</td>
										<td>
											{!!
												Form::text('productoProyecto['.$ProyectoProducto->id_proyecto_producto.'][minimo]',
												$ProyectoProducto->minimo,
												['class'=>'form-control minimo','maxlength'=>'4'])
											!!}
										</td>
										<td>
											{!!
												Form::text('productoProyecto['.$ProyectoProducto->id_proyecto_producto.'][numero_reorden]',
												$ProyectoProducto->numero_reorden,
												['class'=>'form-control numero_reorden','maxlength'=>'4'])
											!!}
										</td>
										<td>
											{!! Form::cCheckbox('','productoProyecto['.$ProyectoProducto->id_proyecto_producto.'][activo]',[!empty($ProyectoProducto->activo)?'checked':'']) !!}
										</td>
										<td>
											{{--Si se va a editar, agrega el botón para "eliminar" la fila--}}
											@if(Route::currentRouteNamed(currentRouteName('edit')))
												<button class="btn is-icon text-primary bg-white "
														type="button" data-item-id="{{$ProyectoProducto->id_proyecto_producto}}"
														id="{{$ProyectoProducto->id_proyecto_producto}}" data-delay="50"
														onclick="borrarFilaProyectoProducto_edit(this)" data-delete-type="single">
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
			<div role="tabpanel" class="tab-pane fade" id="tab-general" aria-labelledby="general-tab">
				Datos generales del proyecto
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

@section('header-bottom')
	@parent
	{{ HTML::script(asset('vendor/vanilla-datatables/vanilla-dataTables.js')) }}
	{{ HTML::script(asset('js/proyectos.js')) }}
	{{ HTML::script(asset('js/maestro_materiales.js')) }}
	
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