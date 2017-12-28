@section('content-width')

@section('form-content')
{{ Form::setModel($data) }}
<div class="row">

{{-- Campos --}}
  <div class="col-sm-12">
    <h5>Datos generales</h5>
    <div class="row">
      <div class="col-md-6 col-sm-12">
        <div class="form-group">
          {{ Form::cSelect('* Sucursales','fk_id_sucursal', $sucursales ?? [],[
            'data-url' => companyAction('HomeController@index').'/administracion.sucursales/api',
            'style' => 'width:100%;',
            'class' => !Route::currentRouteNamed(currentRouteName('show')) ? 'select2' : ''
          ]) }}
        </div>
      </div>
      <div class="col-md-6 col-sm-12">
        <div class="form-group">
        <div id="loadingalmacenes" class="w-100 h-100 text-center text-white align-middle loadingData" style="display: none">
          Cargando almacenes... <i class="material-icons align-middle loading">cached</i>
        </div>
          {{ Form::cSelect('* Almacenes','fk_id_almacen', $almacenes ?? [],[
            'data-url' => companyAction('HomeController@index').'/inventarios.almacenes/api',
            'style' => 'width:100%;',
            'class' => !Route::currentRouteNamed(currentRouteName('show')) ? 'select2' : ''
          ]) }}
        </div>
      </div>
      <div class="col-12">
        <div class="form-group">
          {{ Form::hidden(Auth::id()) }}
          {{ Form::hidden($fechaActual) }}
          {{ Form::hidden('total_productos','0', ['id'=>'total_productos']) }}
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
          <div class="col-md-6 col-sm-12">
            <div class="form-group">
            <div id="loadingskus" class="w-100 h-100 text-center text-white align-middle loadingData" style="display: none">
              Cargando sku(s)... <i class="material-icons align-middle loading">cached</i>
            </div>
              {{ Form::cSelect('* SKU','fk_id_sku', $skus ?? [],[
                'data-url' => companyAction('HomeController@index').'/inventarios.stock/api',
                'style' => 'width:100%;',
                'class' => !Route::currentRouteNamed(currentRouteName('show')) ? 'select2' : ''
              ]) }}
            </div>
          </div>
          <div class="col-md-6 col-sm-12">
            <div class="form-group">
            <div id="loadingupcs" class="w-100 h-100 text-center text-white align-middle loadingData" style="display: none">
              Cargando upc(s)... <i class="material-icons align-middle loading">cached</i>
            </div>
              {{ Form::cSelect('* UPC(s)','fk_id_upc', $upcs ?? [],[
                'data-url' => companyAction('HomeController@index').'/inventarios.stock/api',
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
              <th>SKU</th>
              <th>UPC(s)</th>
              <th>Lote</th>
              <th>Fecha Caducidad</th>
              <th>Almacen/Ubicación actual</th>
              <th>Stock o cantidad actual</th>
              <th>Nueva Ubicación</th>
              <th>Cantidad a mover</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody id="detalle-form-body" class="no-data">
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
    var js_almacen = '{{ $almacen_js ?? '' }}'
    var js_sku = '{{ $sku_js ?? '' }}'
  </script>
	<script src="{{ asset('js/movimiento_almacen.js') }}"></script>
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