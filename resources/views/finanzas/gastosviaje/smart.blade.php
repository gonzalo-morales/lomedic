@extends(smart())

@section('form-content')
    {{ Form::setModel($data) }}
    <div class="row">
      <div class="col-sm-12 col-md-5">
        <h5>Datos generales</h5>
        <div class="row">
          @if(Route::currentRouteNamed(currentRouteName('show')))
     	    <div class="col-md-12 text-center text-success">
    				  <h3>{{ isset($data->id_gastos) ? 'Folio No.: '.$data->id_gastos : ''}}</h3>
              <h6><i class="material-icons align-middle">today </i>{{ isset($data->fecha) ? $data->fecha : ''}}</h6>
    			</div>
          @endif
          <div class="col-md-12">
            <div class="form-group">
            	{{ Form::cSelect('* Nombre del Empleado','fk_id_empleado', $empleados ?? [],[
                'data-url' => companyAction('HomeController@index').'/recursoshumanos.empleados/api',
                'style' => 'width:100%;',
                'class' => !Route::currentRouteNamed(currentRouteName('show')) ? 'select2' : ''
              ]) }}
            </div>
          </div>
        </div>
        @if(Route::currentRouteNamed(currentRouteName('show')))
        @else
        <div class="row">
          <div class="col-md-4 col-sm-4">
            <div class="form-group">
            	{{ Form::cText('Puesto','puesto', ['readonly'=>'true']) }}
            </div>
          </div>
          <div class="col-md-4 col-sm-4">
            <div class="form-group">
            	{{ Form::cText('Departamento','departamento', ['readonly'=>'true']) }}
            </div>
          </div>
          <div class="col-md-4 col-sm-4">
            <div class="form-group">
            	{{ Form::cText('Sucursal','sucursal', ['readonly'=>'true']) }}
            </div>
          </div>
        </div>
        @endif
        <div class="row">
          <div class="col-md-4 col-sm-4">
            <div class="form-group">
              {{ Form::cText('* Fecha inicio del viaje','periodo_inicio',['class'=>'datepicker']) }}
            </div>
          </div>
          <div class="col-md-4 col-sm-4">
            <div class="form-group">
              {{ Form::cText('* Fecha final del viaje','periodo_fin',['class'=>'datepicker']) }}
            </div>
          </div>
          <div class="col-md-4 col-sm-4">
            {{ Form::cText('Total de días que se viajó','total_dias', ['readonly'=>'true']) }}
          </div>
        </div>
        <div class="row">
          <div class="col-md-12 col-sm-12">
            <div class="form-group">
              {{ Form::cTextArea('* Motivo del viaje:','motivo_viaje', ['rows'=>3]) }}
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12 col-sm-12">
            <div class="form-group">
              {{ Form::cText('* Destino:','destino') }}
            </div>
          </div>
          <div class="col-12">
          	{{ Form::hidden('total_detalles',null, ['id'=>'total_detalles']) }}
            {{ Form::hidden('subtotal_detalles',null, ['id'=>'subtotal_detalles']) }}
            {{ Form::hidden('fecha', $fechaActual ?? '') }}
          </div>
        </div>
      </div><!--/col-md-5 col-sm-5-->
    
      <div class="col-md-7 col-sm-12">
        <h5>Facturas y conceptos</h5>
        @if(Route::currentRouteNamed(currentRouteName('show')))
        <p><i class="material-icons align-middle text-warning">info</i>Estas son las facturas y/o notas registradas que se realizaron en el viaje</p>
        @else
        <p>Agrega las facturas y/o notas realizadas de acuerdo al viaje.</p>
        @endif
    
        <div class="card z-depth-1-half">
        @if(Route::currentRouteNamed(currentRouteName('show')))
        @else
          <div class="card-header">
            <form id="overallForm">
            <fieldset id="detalle-form">
            <div class="row">
              <div class="col-md-6 col-sm-6">
                <div class="form-group">
                  {{ Form::cText('* Folio o número de factura/nota','folio_fac') }}
                </div>
              </div>
              <div class="col s12 m6">
                {{ Form::cSelect('* Concepto o tipo de factura/nota','fk_id_tipo', $conceptos ?? [],['style' =>'width:100%;']) }}
              </div>
            </div><!--/row-->
            <div class="row">
              <div class="col s4">
                <div class="input-field">
                  {{ Form::cNumber('* Subtotal','subtotal_fac') }}
                </div>
              </div>
              <div class="col s4">
                <div class="input-field">
                  {{ Form::cSelect('* IVA','fk_id_impuesto', $impuestos ?? [],['data-url'=>companyAction('HomeController@index').'/administracion.impuestos/api','style' =>'width:100%;']) }}
                  {{ Form::hidden('impuesto',null, ['id'=>'impuesto']) }}
                </div>
              </div>
              <div class="col s4">
                <div class="input-field">
                  {{ Form::cNumber('* Total','total_fac', ['readonly'=>'true']) }}
                </div>
              </div>
            </div><!--/row-->
            <div class="col-sm-12 text-center my-3">
              <div class="sep">
                <div class="sepBtn">
                  <button id="saveTable" style="width: 4em; height:4em; border-radius:50%;" class="btn btn-primary btn-large" data-position="bottom" data-delay="50" data-toggle="Agregar" title="Agregar" type="button"><i class="material-icons">add</i></button>
                </div>
              </div>
            </div>
            </fieldset>
            </form>
          </div><!--/Here ends the up section-->
          @endif
    
          <div class="card-body">
            <table id="factConcepts" class="table table-responsive-sm table-striped table-hover">
              <thead>
                <tr>
                  <th>Folio</th>
                  <th>Tipo</th>
                  <th>Subtotal</th>
                  <th>IVA(%)</th>
                  <th>Total</th>
                  @if(Route::currentRouteNamed(currentRouteName('show')))
                  @else
                  <th>Acciones</th>
                  @endif
                </tr>
              </thead>
              <tbody id="detalle-form-body">
                {{-- Si está en edit o show por cada registro $data->detalle as $detalle--}}
                @if(Route::currentRouteNamed(currentRouteName('show')) || Route::currentRouteNamed(currentRouteName('edit')))
                  @foreach($data->detalle->where('eliminar',0) as $row => $detalle)
                    <tr>
                      <td><input type="hidden" value="{{$detalle->id_detalle_gastos}}" name="relations[has][detalle][{{$row}}][id_detalle_gastos]">{{ $detalle->folio }}</td>
                      <td>{{ $detalle->concepto->tipo_concepto }}{{ Form::hidden('relations[has][detalle]['.$row.'][fk_id_tipo]',$detalle->fk_id_tipo) }}</td>
                      <td>{{ '$'.number_format($detalle->subtotal,2) }}{{ Form::hidden('relations[has][detalle]['.$row.'][subtotal]',$detalle->subtotal,['class' => 'subtotal']) }}</td>
                      <td>{{ $detalle->impuestos->impuesto }}{{ Form::hidden('relations[has][detalle]['.$row.'][fk_id_impuesto]',$detalle->fk_id_impuesto) }}</td>
                      <td>{{ '$'.number_format($detalle->total,2) }}{{ Form::hidden('relations[has][detalle]['.$row.'][total]',$detalle->total,['class' => 'total']) }}</td>
                        @if(Route::currentRouteNamed(currentRouteName('show')))
                        @else
                      <td>
                        <button data-toggle="Eliminar" data-placement="top" title="Eliminar" data-original-title="Eliminar" type="button" class="text-primary btn btn_tables is-icon eliminar bg-white" data-delay="50" onclick="borrarFila(this)"><i class="material-icons">delete</i></button>
                      </td>
                        @endif
                    </tr>
                  @endforeach
                @endif
              </tbody>
            </table>
          </div>
        </div><!--/card-->
      </div><!--/col-md-7 col-sm-7-->
    </div>
@endsection

@section('header-bottom')
	@parent
	<script type="text/javascript">
        // Variables para tomar los datos relacionados
        var js_impuesto = '{{ $impuesto_js ?? '' }}';
      	var js_departamento = '{{ $departamento_js ?? '' }}';
      	var js_puesto = '{{ $puesto_js ?? '' }}';
      	var js_sucursal = '{{ $sucursal_js ?? '' }}';
	</script>
	<script src="{{ asset('js/gastos_viaje.js') }}"></script>
@endsection