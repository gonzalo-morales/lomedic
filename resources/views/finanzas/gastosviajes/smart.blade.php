@section('content-width')

@section('form-content')
{{ Form::setModel($data) }}
<div class="row">

{{-- Campos --}}
  <div class="col-md-5 col-sm-5">
    <h5>Datos generales</h5>
    <div class="row">
{{-- 			<div class="col-md-12 text-center text-success">
				<h3>Factura No. {{$data->folio}}</h3>
			</div> --}}
      <div class="col-md-8 col-sm-8">
        <div class="form-group">
        	{{ Form::cSelect('* Nombre del Empleado','fk_id_empleado', $empleados ?? [],['data-url'=>companyAction('HomeController@index').'/recursoshumanos.empleados/api','style' =>'width:100%;']) }}
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          {{ Form::label('fecha','* Fecha') }}
          {{ Form::text('fecha', null, ['id'=>'fecha','class'=>'datepicker form-control']) }}
          {{ $errors->has('fecha') ? HTML::tag('span', $errors->first('fecha'), ['class' =>'help-block text-danger']) : '' }}
        </div>
      </div>
    </div>
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
    <div class="row">
      <div class="col-md-4 col-sm-4">
        <div class="form-group">
          {{ Form::label('periodo_inicio','* periodo_inicio') }}
          {{ Form::text('periodo_inicio', null, ['id'=>'periodo_inicio','class'=>'datepicker form-control']) }}
          {{ $errors->has('periodo_inicio') ? HTML::tag('span', $errors->first('periodo_inicio'), ['class' =>'help-block text-danger']) : '' }}
        </div>
      </div>
      <div class="col-md-4 col-sm-4">
        <div class="form-group">
          {{ Form::label('periodo_fin','* periodo_fin') }}
          {{ Form::text('periodo_fin', null, ['id'=>'periodo_fin','class'=>'datepicker form-control']) }}
          {{ $errors->has('periodo_fin') ? HTML::tag('span', $errors->first('periodo_fin'), ['class' =>'help-block text-danger']) : '' }}
        </div>
      </div>
      <div class="col-md-4 col-sm-4">
        {{ Form::cText('total_dias','total_dias', ['readonly'=>'true']) }}
      </div>
    </div>
    <div class="row">
      <div class="col-md-12 col-sm-12">
        <div class="form-group">
          {{ Form::label('motivo_gasto','* Motivo del viaje:') }}
          {{ Form::textarea('motivo_gasto', null, ['id'=>'motivo_gasto','class'=>'form-control','rows'=>'2']) }}
          {{ $errors->has('motivo_gasto') ? HTML::tag('span', $errors->first('motivo_gasto'), ['class' =>'help-block text-danger']) : '' }}
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12 col-sm-12">
        <div class="form-group">
          {{ Form::label('viaje_a','* Destino:') }}
          {{ Form::text('viaje_a', null, ['id'=>'viaje_a','class'=>'form-control']) }}
          {{ $errors->has('viaje_a') ? HTML::tag('span', $errors->first('viaje_a'), ['class' =>'help-block text-danger']) : '' }}
        </div>
      </div>
      <div class="col-12">
      	{{ Form::hidden('total_detalles',null, ['id'=>'total_detalles']) }}
        {{ Form::hidden('subtotal_detalles',null, ['id'=>'subtotal_detalles']) }}
      </div>
    </div>
  </div><!--/col-md-5 col-sm-5-->

  <div class="col-md-7 col-sm-7">
    <h5>Facturas y conceptos</h5>
    <p>Agrega las facturas realizadas de acuerdo al viaje.</p>
    <div class="card">
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
      <div class="card-body">
        <table id="factConcepts" class="table table-responsive-sm table-stripped table-hover">
          <thead>
            <tr>
              <th>Folio</th>
              <th>Tipo</th>
              <th>Subtotal</th>
              <th>IVA(%)</th>
              <th>Total</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody id="detalle-form-body">
            {{-- Si está en edit o show por cada registro $data->detalle as $detalle--}}
            @if(Route::currentRouteNamed(currentRouteName('show')) || Route::currentRouteNamed(currentRouteName('edit')))
              @foreach($data->detalle as $detalle)
                <tr>
                  <td>{{ $detalle->folio }}</td>
                  <td>{{ $detalle->tipo->tipo_concepto }}</td>
                  <td>{{ $detalle->subtotal }}</td>
                  <td>{{ $detalle->impuestos->impuesto }}</td>
                  <td>{{ $detalle->total }}</td>
                  <td>
                    @if(Route::currentRouteNamed(currentRouteName('show')))
                      <button data-toggle="Eliminar" data-placement="top" title="Eliminar" data-original-title="Eliminar" type="button" class="text-primary btn btn_tables is-icon eliminar bg-white" data-delay="50"><i class="material-icons">delete</i></button>
                      @else
                        <button data-toggle="Eliminar" data-placement="top" title="Eliminar" data-original-title="Eliminar" type="button" class="text-primary btn btn_tables is-icon eliminar bg-white" data-delay="50" onclick="borrarFila_edit(this)"><i class="material-icons">delete</i></button>
                    @endif
                  </td>
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