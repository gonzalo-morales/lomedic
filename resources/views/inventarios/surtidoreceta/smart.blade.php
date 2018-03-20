@section('header-bottom')
    @parent

    @if (!Route::currentRouteNamed(currentRouteName('index')) && !Route::currentRouteNamed(currentRouteName('show')) )
        <script type="text/javascript" src="{{ asset('js/surtidoreceta.js') }}"></script>
    @endif

@endsection
@section('content-width', 's12')

@if (Route::currentRouteNamed(currentRouteName('index')))
    @section('form-title', 'Surtidos de recetas')
@elseif(Route::currentRouteNamed(currentRouteName('create')))
    @section('form-title', 'Nuevo surtido de receta')
@elseif(Route::currentRouteNamed(currentRouteName('edit')))
    @section('form-title', 'Editar surtido de receta')
@elseif(Route::currentRouteNamed(currentRouteName('show')))
    @section('form-title', 'Surtido de receta')
@endif

@section('form-content')
    {{ Form::setModel($data) }}
    {{--<div class="row">--}}
        <div class="col-sm-12">
            {{--<div class="card z-depth-1-half">--}}
                {{--<div class="card-header">--}}
                    <div class="row">
                        <div class="col-12 mb-3">
                            @if(Route::currentRouteNamed(currentRouteName('create')))
                            <div class="card z-depth-1-half">
                            <div class="card-body row table-responsive">
                            <div class="tab-content">
                                <div class="tab-pane active" role="tabpanel">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                {{ Form::cSelect('Sucursal', 'fk_id_sucursal', $sucursales ?? [],['class'=>'select2','data-url'=>companyRoute('getReceta')]) }}
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                {{ Form::cSelect('Numero de receta', 'fk_id_receta', $recetas ?? [],['class'=>'select2','data-url'=>companyRoute('getRecetaDetalle')]) }}
                                            </div>
                                        </div>
                                        <input type="hidden" name="fk_id_usuario_captura" value="{{$fk_id_usuario_captura}}">
                                    </div>
                                </div>
                            </div><!--/row-->
                            </div>
                            </div><!--/row-->
                            @endif
                            <div class="card z-depth-1-half">
                                 <div class="card-body row table-responsive">
                                        <table class="table highlight mt-3" id="tContactos">
                                            <thead>
                                            <tr>
                                                <th>Clave producto</th>
                                                <th>Descripcion</th>
                                                <th>Cantidad solicitada</th>
                                                <th>Cantidad surtida</th>
                                                @if(!Route::currentRouteNamed(currentRouteName('show')))
                                                <th>Cantidad disponible</th>
                                                <th>Cantidad a surtir</th>
                                                <th>Precio unitario</th>
                                                <th>Importe</th>
                                                <th></th>
                                                @endif
                                            </tr>
                                            </thead>
                                            <tbody id="detalle">
                                                @if(!Route::currentRouteNamed(currentRouteName('create')))
                                                     @if(isset($data->detalles))
                                                         {{--{{dd($data->detalles)}}--}}
                                                        @foreach($data->detalles->where('eliminar',0) as $row => $detalle)
                                                            <tr>
                                                                {{--<td>{{$detalle->area['area']}}</td>--}}
                                                                <td>{{$detalle->claveClienteProducto['clave_producto_cliente'] }}</td>
                                                                <td>{{$detalle->claveClienteProducto->sku['descripcion']}}</td>
                                                                <td>{{$detalle->cantidad_solicitada}}</td>
                                                                @if(Route::currentRouteNamed(currentRouteName('edit')))
                                                                    @if($detalle->cantidad_surtida < $detalle->cantidad_solicitada )
                                                                        <td class="cantidad_surtida">{{$detalle->cantidad_surtida}}</td>
                                                                    @else
                                                                        <td>Producto entregado en su totalidad.</td>
                                                                    @endif
                                                                        <td class="cantidad_disponible">{{$detalle->claveClienteProducto->stock($detalle->claveClienteProducto->fk_id_sku,$detalle->claveClienteProducto->fk_id_upc)->first()}}</td>
                                                                    @if($detalle->cantidad_surtida < $detalle->cantidad_solicitada )
                                                                            <td><input type="number" onchange="calculatotal(this)" name="relations[has][detalles][{{$row}}][cantidad_surtida]" min="0" max="{{$detalle->cantidad_solicitada-$detalle->cantidad_surtida}}" class="form-control cantidad" value="0"></td>
                                                                    @else
                                                                            <td><input type="number" onchange="calculatotal(this)" name="relations[has][detalles][{{$row}}][cantidad_surtida]" min="0" max="{{$detalle->cantidad_solicitada-$detalle->cantidad_surtida}}" class="form-control cantidad" value="0" disabled></td>
                                                                    @endif
                                                                        <td>$ {{ number_format($detalle->claveClienteProducto->precio, 2, '.', '')}}</td>
                                                                        <td class="text-right total">$ {{ number_format(($detalle->claveClienteProducto->precio*$detalle->cantidad_surtida), 2, '.', '')}}</td>
                                                                        <input type="hidden" class="cantidad_inicial_disponible" value="{{$detalle->claveClienteProducto->stock($detalle->claveClienteProducto->fk_id_sku,$detalle->claveClienteProducto->fk_id_upc)->first()}}"/>
                                                                        <input type="hidden" name="relations[has][detalles][{{$row}}][id_detalle_receta]"  value="{{$detalle->id_detalle_receta}}"/>
                                                                        <input type="hidden" name="relations[has][detalles][{{$row}}][fk_id_surtido_receta]"  value="{{$detalle->fk_id_surtido_receta}}"/>
                                                                        <input type="hidden" name="relations[has][detalles][{{$row}}][fk_id_clave_cliente_producto]"  value="{{$detalle->fk_id_clave_cliente_producto}}"/>
                                                                        <input type="hidden" name="relations[has][detalles][{{$row}}][cantidad_solicitada]"  value="{{$detalle->cantidad_solicitada}}"/>
                                                                        <input type="hidden" name="relations[has][detalles][{{$row}}][precio_unitario]" class="precio" value="{{$detalle->claveClienteProducto->precio}}">
                                                                        <input type="hidden" name="relations[has][detalles][{{$row}}][importe]" class="importe" value="{{$detalle->claveClienteProducto->precio*$detalle->cantidad_surtida}}">
                                                                @endif
                                                                {{--<td>--}}
                                                                    {{--<a data-delete-type="single"  data-toggle="tooltip" data-placement="top" title="Borrar"  id="{{$row}}" aria-describedby="tooltip687783" onclick="eliminarFila(this)" ><i class="material-icons text-primary">delete</i></a>--}}
                                                                {{--</td>--}}
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                @endif
                                            </tbody>
                                        </table>
                                     @if(Route::currentRouteNamed(currentRouteName('create')))
                                     <div class="row" >
                                         <div class="col-sm-12">
                                             <div class="form-group">
                                                 {{ Form::cTextArea('Observaciones', 'observaciones',['rows'=>'3'] ) }}
                                             </div>
                                         </div>
                                     </div>
                                     @endif
                                 </div>
                            </div>
                    </div>
                    {{--</div>--}}
                {{--</div>--}}
            </div>


@endsection
{{-- DONT DELETE --}}
@if (Route::currentRouteNamed(currentRouteName('create')))
    @include('layouts.smart.create')
@endif
@if (Route::currentRouteNamed(currentRouteName('index')))
    {{--@section('title', 'Requisiciones Hospitalarias')--}}
@include('layouts.smart.index')
@endif

@if (Route::currentRouteNamed(currentRouteName('edit')))
    @include('layouts.smart.edit')
@endif
@if (Route::currentRouteNamed(currentRouteName('surtir')))
    @include('layouts.smart.edit')
    {{--<script>--}}
        {{--var detalle_requisicion = {!!json_encode($detalle_requisicion)!!};--}}
    {{--</script>--}}
@endif

@if (Route::currentRouteNamed(currentRouteName('show')))
    @section('form-title', ' Datos de las Requisiciones Hospitalarias')
@include('layouts.smart.show')

@endif

@if (Route::currentRouteNamed(currentRouteName('export')))
    @include('layouts.smart.export')
@endif


