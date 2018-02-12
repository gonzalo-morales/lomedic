@extends(smart())
@section('content-width', 's12')

@if (Route::currentRouteNamed(currentRouteName('index')))
    @section('form-title', 'Requisiciónes hospitalarias')
@elseif(Route::currentRouteNamed(currentRouteName('create')))
    @section('form-title', 'Nueva requisición hospitalaria')
@elseif(Route::currentRouteNamed(currentRouteName('edit')))
    @section('form-title', 'Editar requisición hospitalaria')
@elseif(Route::currentRouteNamed(currentRouteName('show')))
    @section('form-title', 'Requisición hospitalaria')
@endif

@section('header-bottom')
    @parent
    @if (!Route::currentRouteNamed(currentRouteName('index')) && !Route::currentRouteNamed(currentRouteName('show')) )
        <script type="text/javascript" src="{{ asset('js/requisicioneshospitalarias.js') }}"></script>
    @endif
@endsection

@section('form-content')
    {{ Form::setModel($data) }}
    @if(Route::currentRouteNamed(currentRouteName('create')))
    <div class="card z-depth-1-half">
        <div class="row">
            <div class="card-body row table-responsive">
                <div class="col-12 mb-3">

                        <div class="tab-content">
                            <div class="tab-pane active" role="tabpanel">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            {{ Form::cSelect('Sucursal', 'fk_id_sucursal', $localidades ?? [],['class'=>'select2']) }}
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            {{ Form::cSelect('Solicitante', 'fk_id_solicitante', $solicitante ?? [],['class'=>'select2']) }}
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            {{ Form::cSelect('Programa', 'fk_id_programa', $programas ?? [],['class'=>'select2']) }}
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            {{Form::cDate('Fecha de solicitud','fecha_requerimiento')}}
                                        </div>
                                    </div>
                                    <input type="hidden" id="fecha_captura" name="fecha_captura" value="{{date('Y-m-d H:i:s')}}">
                                    <input type="hidden" id="fk_id_usuario_captura" name="fk_id_usuario_captura" value="{{$fk_id_usuario_captura}}">

                                </div><!--/row forms-->
                            </div>
                        </div><!--/row-->

                </div>
            </div>
        </div>
    </div>
    @endif
    <div class="card z-depth-1-half">
        @if(Route::currentRouteNamed(currentRouteName('create')) ||  Route::currentRouteNamed(currentRouteName('edit')))
            <div class="card-header">
                <div class="row">
                    <div class="col-12 mb-3">
                        <div class="tab-content">
                            <div class="tab-pane active" role="tabpanel">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            {{ Form::cSelect('&Aacute;rea de la consulta', 'fk_id_area', $areas ?? [],['class'=>'select2']) }}
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            {{ Form::cSelect('Medicamento', 'medicamento', $skus ?? [],['class'=>'select2','data-url'=>companyRoute('getMedicamentos')]) }}
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            {{ Form::cText('Cantidad', 'cantidad') }}
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12 my-2">
                                        <div class="sep sepBtn">
                                            <button id="agregar" class="btn btn-primary btn-large btn-circle" data-placement="bottom" data-delay="100" data-tooltip="Agregar" data-toggle="tooltip" data-action="add" title="Agregar" type="button"><i class="material-icons">add</i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!--/row-->
                </div>
            </div>
        @endif

        <div class="card-body row table-responsive">
            <table class="table highlight mt-3" id="tContactos">
                <thead>
                <tr>
                    <th>&Aacute;rea de la consulta</th>
                    <th>Codigo</th>
                    <th>Medicamento</th>
                    <th>Cantidad</th>
                    <th></th>
                </tr>
                </thead>
                <tbody class="medicine_detail">
                @if(!Route::currentRouteNamed(currentRouteName('create')))
                     @if(isset($data->detalles))
                        @foreach($data->detalles->where('eliminar',0) as $row => $detalle)
                            <tr>
                                <td>{{$detalle->area['area']}}</td>
                                <td>{{$detalle->claveClienteProducto['clave_producto_cliente'] }}</td>
                                <td>{{$detalle->claveClienteProducto->sku['descripcion']}}</td>
                                <td>{{$detalle->cantidad_solicitada}}</td>
                                <td>
                                    <a data-delete-type="single"  data-toggle="tooltip" data-placement="top" title="Borrar"  id="{{$row}}" aria-describedby="tooltip687783" onclick="eliminarFila(this)" ><i class="material-icons text-primary">delete</i></a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection