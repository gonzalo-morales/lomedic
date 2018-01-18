@extends(smart())
@section('content-width')

@section('form-content')
    {{ Form::setModel($data) }}
    <div class="row">
    
    {{-- Campos --}}

    <div id="confirmacionsucursal" class="modal" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Cambio de Sucursal</h5>
          </div>
          <div class="modal-body">
            Recuerda que al cambiar la <b>sucursal</b> perderás toda la información y avances que tengas en este momento
          </div>
          <div class="modal-footer">
            <button id="cancelarcambiosucursal" type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
            <button id="confirmarsucursal" type="button" class="btn btn-danger">Borrar y cambiar sucursal</button>
          </div>
        </div>
      </div>
    </div>

    <div id="confirmacionalmacen" class="modal" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Cambio de Almacen</h5>
          </div>
          <div class="modal-body">
            Recuerda que al cambiar el <b>almacén</b> perderás toda la información y avances que tengas en este momento
          </div>
          <div class="modal-footer">
            <button id="cancelarcambioalmacen" type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
            <button id="confirmaralmacen" type="button" class="btn btn-danger">Borrar y cambiar almacén</button>
          </div>
        </div>
      </div>
    </div>

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
                'data-url2' => companyAction('HomeController@index').'/inventarios.ubicaciones/api',
                'style' => 'width:100%;',
                'class' => !Route::currentRouteNamed(currentRouteName('show')) ? 'select2' : '',
                Route::currentRouteNamed(currentRouteName('edit')) && $data['fk_id_almacen'] > 0 ? '' : 'disabled'
              ]) }}
            </div>
          </div>
        </div>
      </div><!--/col-sm.12-->
    
      <div class="col-sm-12">
        <h5>Movimiento de Ubicaciones/Almacenes</h5>
        <p>Agrega y cambia de ubicación/almacén los UPC's/SKU's que requieras:</p>
        <div class="card z-depth-1-half">
        @if(!Route::currentRouteNamed(currentRouteName('show')))
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
                    'class' => !Route::currentRouteNamed(currentRouteName('show')) ? 'select2' : '',
                  ]) }}
                </div>
              </div>
              <div class="col-md-6 col-sm-12">
                <div class="form-group">
                <div id="loadingupcs" class="w-100 h-100 text-center text-white align-middle loadingData" style="display: none">
                  Cargando upc(s)... <i class="material-icons align-middle loading">cached</i>
                </div>
                  {{ Form::cSelect('* UPC(s)','fk_id_upc', $upcs ?? [],[
                    'style' => 'width:100%;',
                    'class' => !Route::currentRouteNamed(currentRouteName('show')) ? 'select2' : ''
                  ]) }}
                </div>
              </div>
              <div class="form-goup col-12">
                <div style="display:none;" id="campo_ubicacion">
                  {{ Form::cSelectWithDisabled(null,'relations[has][detalle][$row_id][fk_id_ubicacion]', $ubicaciones ?? [],[
                    'class'=>'fk_id_ubicacion',
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
          </div><!--/card-header-->
      @endif
          <div class="card-body">
            <table id="factConcepts" class="table table-responsive-md table-striped table-hover">
              <thead>
                <tr>
                  @if(!Route::currentRouteNamed(currentRouteName('show')))
                  <th>SKU</th>
                  <th>UPC(s)</th>
                  <th>Fecha Caducidad</th>
                  <th>Lote/Almacen-Ubicación/Stock Actual</th>
                  <th>* Nueva Ubicación</th>
                  <th>* Nuevo Lote</th>
                  <th>* Cantidad a mover</th>
                  <th>Acciones</th>
                  @else
                  <th>SKU</th>
                  <th>UPC(s)</th>
                  <th>Lote</th>
                  <th>Fecha Caducidad</th>
                  <th>Ubicación</th>
                  <th>Cantidad</th>
                  @endif
                </tr>
              </thead>
              <tbody id="detalle-form-body" class="no-data">
              {{-- Si está en edit o show por cada registro $data->detalle as $detalle--}}
              @if(Route::currentRouteNamed(currentRouteName('show')))
                @foreach($data->detalle->where('eliminar',0) as $row => $detalle)
                  <tr>
                    <th>
                      <img style="max-height:30px" src="img/sku.png" alt="sku"/> {{ $detalle->sku->sku }}
                    </th>
                    <td>
                      <img style="max-height:30px" src="img/upc.png" alt="upc"/> {{$detalle->upcs->upc}}
                    </td>
                    <td>
                      <i class="material-icons align-middle">label</i> {{$detalle->lote}}
                    </td>
                    <td class="align-middle">
                      <i class="material-icons align-middle">today</i> {{$detalle->fecha_caducidad}}
                    </td>
                    <td class="align-middle">
                      {{$detalle->ubicacion->ubicacion}}
                    </td>
                    <td>
                      <i class="material-icons align-middle">shopping_basket</i> {{$detalle->stock}}
                    </td>
                  </tr>
                  @endforeach
                @endif
    
              @if(Route::currentRouteNamed(currentRouteName('edit')))
                @foreach($data->detalle->where('eliminar',0) as $row => $detalle)
                  <tr>
                    <th>
                      <img style="max-height:30px" src="img/sku.png" alt="sku"/> {{ $detalle->sku->sku }}
                      {{ Form::hidden('index',$row) }}
                      {{ Form::hidden('relations[has][detalle]['.$row.'][fk_detalle_movimiento]',$detalle->id_detalle_movimiento) }}
                      {{ Form::hidden('relations[has][detalle]['.$row.'][fk_id_stock]',$detalle->fk_id_stock) }}
                      {{ Form::hidden('relations[has][detalle]['.$row.'][fk_id_sku]',$detalle->fk_id_sku) }}
                    </th>
                    <td>
                      <img style="max-height:30px" src="img/upc.png" alt="upc"/>{{$detalle->upcs->upc}}
                      {{ Form::hidden('relations[has][detalle]['.$row.'][fk_id_upc]',$detalle->fk_id_upc) }}
                    </td>
                    <td class="align-middle">
                      <i class="material-icons align-middle">today</i>{{$detalle->fecha_caducidad}}
                      {{ Form::hidden('relations[has][detalle]['.$row.'][fecha_caducidad]', $detalle->fecha_caducidad) }}
                    </td>
                    <td class="align-middle">
                      <i data-toggle="Lote" data-placement="top" title="Lote" data-original-title="Lote" class="material-icons align-middle">label</i> {{ $detalle->lote }}<br>
                      {{$detalle->ubicacion->ubicacion}}<br>
                      <i data-toggle="Stock actual" data-placement="top" title="Stock actual" data-original-title="Stock actual" class="material-icons align-middle">shopping_basket</i> {{$detalle->stock}}
                    </td>
                    <td>
                      {{ Form::Select('relations[has][detalle]['.$row.'][fk_id_ubicacion]',$ubicaciones_det ?? [],$detalle->fk_id_ubicacion,[
                        'style' => 'width:100%;',
                        'class' => 'form-control'
                      ]) }}
                    </td>
                    <td>
                      <div class="input-group">
                        <span class="input-group-addon">
                          <i class="material-icons align-middle">label</i>
                        </span>
                          {{ Form::Text('relations[has][detalle]['.$row.'][lote]', $detalle->lote,['class' => 'form-control','style' => 'min-width:80px']) }}
                      </div>
                    </td>
                    <td>
                      <div class="input-group">
                        <span class="input-group-addon">
                          <i class="material-icons align-middle">shopping_basket</i>
                        </span>
                        {{ Form::Text('relations[has][detalle]['.$row.'][stock]', $detalle->stock,['class' => 'form-control','style' => 'min-width:60px']) }}
                      </div>
                    </td>
                    <td>
                      <button data-toggle="Eliminar" data-placement="top" title="Eliminar" data-original-title="Eliminar" type="button" class="text-primary btn btn_tables is-icon eliminar" style="background:none;" data-delay="50" onclick="borrarFila(this)"><i class="material-icons">delete</i></button>
                    </td>
                  </tr>
                  @endforeach
                @endif
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
        var js_ubicacion = '{{ $ubicacion_js ?? '' }}'
        var js_sku = '{{ $sku_js ?? '' }}'
  	</script>
	<script src="{{ asset('js/movimiento_almacen.js') }}"></script>
@endsection