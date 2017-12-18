@section('content-width')

@section('form-content')
{{ Form::setModel($data) }}
<div class="row">

{{-- Campos --}}
  <div class="col-sm-12">
    <h5>Datos generales</h5>
    <div class="row">
      <div class="col-md-3 col-sm-6 col-12">
        <div class="form-group">
          {{ Form::cSelect('* Localidades','fk_id_localidad', $localidades ?? [],[
            'data-url' => companyAction('HomeController@index').'/administracion.localidades/api',
            'style' => 'width:100%;',
            'class' => !Route::currentRouteNamed(currentRouteName('show')) ? 'select2' : ''
          ]) }}
        </div>
      </div>
      <div class="col-md-3 col-sm-6 col-12">
        <div class="form-group">
        <div id="loadingsucursales" class="w-100 h-100 text-center text-white align-middle loadingData" style="display: none">
          Cargando datos... <i class="material-icons align-middle loading">cached</i>
        </div>
          {{ Form::cSelect('* Sucursal','fk_id_sucursal', $sucursales ?? [],[
            'data-url' => companyAction('HomeController@index').'/administracion.sucursales/api',
            'style' => 'width:100%;',
            'class' => !Route::currentRouteNamed(currentRouteName('show')) ? 'select2' : ''
          ]) }}
        </div>
      </div>
      <div class="col-md-3 col-sm-6 col-12">
        <div class="form-group">
        <div id="loadingalmacenes" class="w-100 h-100 text-center text-white align-middle loadingData" style="display: none">
          Cargando datos... <i class="material-icons align-middle loading">cached</i>
        </div>
          {{ Form::cSelect('* Almacén','fk_id_almacen', $almacenes ?? [],[
            'data-url' => companyAction('HomeController@index').'/inventarios.almacenes/api',
            'style' => 'width:100%;',
            'class' => !Route::currentRouteNamed(currentRouteName('show')) ? 'select2' : ''
          ]) }}
        </div>
      </div>
      <div class="col-md-3 col-sm-6 col-12">
        <div class="form-group">
        <div id="loadingubicaciones" class="w-100 h-100 text-center text-white align-middle loadingData" style="display: none">
          Cargando datos... <i class="material-icons align-middle loading">cached</i>
        </div>
          {{ Form::cSelect('* Ubicación','fk_id_ubicacion', $ubicaciones ?? [],[
            'data-url' => companyAction('HomeController@index').'/inventarios.ubicaciones/api',
            'style' => 'width:100%;',
            'class' => !Route::currentRouteNamed(currentRouteName('show')) ? 'select2' : ''
          ]) }}
        </div>
      </div>
    </div>
  </div><!--/col-sm.12-->

  <div class="col-sm-12">
    <h5>Movimiento de Ubicaciones/Almacenes</h5>
    <p>Agrega y cambia de ubicación/almacén los UPC's/SKU's que requieras:</p>
    <div class="card z-depth-1-half">
      <div class="card-header">
        <form id="overallForm">
        <fieldset id="detalle-form">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              {{ Form::cSelect('* Sucursal','fk_id_sucursal', $sucursales ?? [],[
                'data-url' => companyAction('HomeController@index').'/administracion.sucursales/api',
                'style' => 'width:100%;',
                'class' => !Route::currentRouteNamed(currentRouteName('show')) ? 'select2' : ''
              ]) }}
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
        <table id="factConcepts" class="table table-responsive-sm table-striped table-hover">
          <thead>
            <tr>
              <th>#</th>
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
{{--             @if(Route::currentRouteNamed(currentRouteName('show')) || Route::currentRouteNamed(currentRouteName('edit')))
              @foreach($data->detalle->where('eliminar',0) as $row => $detalle)
                <tr>
                  <td><input type="hidden" value="{{$detalle->id_detalle_gastos}}" name="relations[has][detalle][{{$row}}][id_detalle_gastos]">{{ $detalle->folio }}</td>
                  <td>{{ $detalle->tipo->tipo_concepto }}{{ Form::hidden('relations[has][detalle]['.$row.'][fk_id_tipo]',$detalle->fk_id_tipo) }}</td>
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
            @endif --}}
          </tbody>
        </table>
      </div>
    </div><!--/card-->
  </div><!--/col-sm-12-->

</div>
@endsection

@section('header-bottom')
@parent
	<script type="text/javascript">
    // Variables para tomar los datos relacionados
  	var js_almacen = '{{ $almacen_js ?? '' }}';
  	var js_sucursal = '{{ $sucursal_js ?? '' }}';
    var js_ubicacion = '{{ $ubicacion_js ?? '' }}';
  </script>
	<script src="{{ asset('js/stock.js') }}"></script>
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