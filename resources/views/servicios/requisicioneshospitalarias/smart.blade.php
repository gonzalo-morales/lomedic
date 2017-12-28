@section('header-bottom')
    @parent

    @if (!Route::currentRouteNamed(currentRouteName('index')) && !Route::currentRouteNamed(currentRouteName('show')) )
        <script type="text/javascript" src="{{ asset('js/requisicioneshospitalarias.js') }}"></script>
    @endif

@endsection
@section('content-width', 's12')

@section('form-content')
    {{ Form::setModel($data) }}

    {{--@if (Route::currentRouteNamed(currentRouteName('create')) || Route::currentRouteNamed(currentRouteName('show')))--}}

    <div class="row">
        <div class="col-sm-12">
            <div class="card z-depth-1-half">
                <div class="card-header">
                    <div class="row">
                        <div class="col-12 mb-3">
                            @if(Route::currentRouteNamed(currentRouteName('create')))
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
                                                                    {{ Form::cSelect('Área de la consulta', 'fk_id_area', $areas ?? [],['class'=>'select2']) }}
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
                                                <th>Área de la consulta</th>
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

                                        {{--@if (!Route::currentRouteNamed(currentRouteName('edit')))--}}
                                            {{--<div class="row">--}}
                                                {{--<div class="col-sm-12">--}}
                                                    {{--<div class="form-group">--}}
                                                        {{--{{ Form::cTextArea('Observaciones adicionales:', 'observaciones', isset($data->observaciones)?$data->observaciones:null) }}--}}
                                                    {{--</div>--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                        {{--@endif--}}
                                    </div>
                                </div>
                            </div>
                        {{--@else--}}
                            {{--@if($data->fk_id_estatus_receta == 2)--}}
                                {{--<h1>Esta receta ya fue surtida en su totalidad y no se puede editar.</h1>--}}
                            {{--@elseif($data->fk_id_estatus_receta == 3)--}}
                                {{--<h1>Esta receta esta siendo surtida parcialmente y no se puede editar.</h1>--}}
                            {{--@elseif($data->fk_id_estatus_receta == 4)--}}
                                {{--<h1>Esta receta esta cancelada y no se puede editar.</h1>--}}
                            {{--@endif--}}
                        {{--@endif--}}



                    </div>
                    </div>
                </div>
            </div>


        @endsection



{{--@endif--}}




{{--@if (Route::currentRouteNamed(currentRouteName('surtir')))--}}
{{--@section('form-title', 'Surtir requisicion')--}}
{{--@section('form-header')--}}
    {{--{!! Form::open(['method'=>'put', 'url' => companyRoute('surtir'), 'id' => 'form-model', 'class' => 'col-sm-12']) !!}--}}
{{--@endsection--}}

{{--@section('form-actions')--}}

    {{--<div class="text-right ">--}}
        {{--@if(in_array($data->id_estatus,[1,2]))--}}
            {{--<button type="submit" id="surtir_requisicion" onclick="return surtirRequisicion()" class="btn btn-danger"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> Guardar</button>--}}
        {{--@endif--}}
        {{--<a class="btn btn-default" href="{{ companyRoute('index') }}"> Cerrar</a>--}}
    {{--</div>--}}
{{--@endsection--}}

{{--<div class="panel-body">--}}

    {{--<div class="row">--}}
        {{--<div class="col-sm-4">--}}
            {{--<div class="form-group">--}}
                {{--{{ Form::label('id_localidad', 'Localidad:') }}--}}
                {{--{{ Form::select('id_localidad', $localidades, null, ['id'=>'id_localidad','class'=>'js-data-example-ajax1 form-control','style'=>'width:100%','disabled'=>'true']) }}--}}
                {{--{{ $errors->has('id_localidad') ? HTML::tag('span', $errors->first('id_localidad'), ['class'=>'help-block deep-orange-text']) : '' }}--}}
            {{--</div>--}}
        {{--</div>--}}
        {{--<div class="col-sm-4">--}}
            {{--<div class="form-group">--}}
                {{--{{ Form::label('id_solicitante', 'Solicitante:') }}--}}
                {{--{{ Form::select('id_solicitante', $solicitante, null, ['id'=>'id_solicitante','class'=>'js-data-example-ajax1 form-control','style'=>'width:100%','disabled'=>'true']) }}--}}
                {{--{{ $errors->has('id_solicitante') ? HTML::tag('span', $errors->first('id_solicitante'), ['class'=>'help-block deep-orange-text']) : '' }}--}}
            {{--</div>--}}
        {{--</div>--}}
        {{--<div class="col-sm-2 col-xs-6">--}}
            {{--<div class="form-group">--}}
                {{--{{ Form::label('id_estatus', 'Estatus:') }}--}}
                {{--{{ Form::select('id_estatus', $estatus, null, ['id'=>'id_estatus','class'=>'js-data-example-ajax1 form-control','style'=>'width:100%','disabled'=>'true']) }}--}}
                {{--{{ $errors->has('id_estatus') ? HTML::tag('span', $errors->first('id_estatus'), ['class'=>'help-block deep-orange-text']) : '' }}--}}
            {{--</div>--}}
        {{--</div>--}}
        {{--<div class="col-sm-2 col-xs-6">--}}
            {{--<div class="form-group">--}}
                {{--<label for="fecha">*Fecha:</label>--}}
                {{--<div id="datetimepicker3" class="input-group">--}}
                    {{--<input type="text" class="form-control" name="fecha_requerido" value="{{$datos_requisicion->fecha_requerido}}" data-format="yyyy-MM-dd" disabled='true'>--}}
                    {{--<span class="input-group-btn add-on">--}}
                                {{--<button data-date-icon="icon-calendar" class="btn btn-check" type="button"><span class="glyphicon glyphicon-calendar"></span></button>--}}
                              {{--</span>--}}
                {{--</div><!-- /input-group -->--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div><!--/row-->--}}

    {{--<div class="divider"></div>--}}
    {{--<div class="row">--}}
        {{--<div class="col-md-12 table-responsive" >--}}
            {{--<table class="table table-hover table-striped">--}}
                {{--<thead>--}}
                {{--<tr>--}}
                    {{--<th>Área</th>--}}
                    {{--<th>Clave</th>--}}
                    {{--<th>Producto</th>--}}
                    {{--<th>Cantidad solicitada</th>--}}
                    {{--<th>Cantidad surtida</th>--}}
                    {{--<th>Cantidad a surtir</th>--}}
                {{--</tr>--}}
                {{--</thead>--}}
                {{--<tbody id="lista_productos">--}}
                {{--@foreach($detalle_requisicion as $index => $detalle)--}}
                    {{--<tr>--}}
                        {{--<td>{{$detalle->area}}</td>--}}
                        {{--<td>{{$detalle->clave_cliente}}</td>--}}
                        {{--<td>{{$detalle->descripcion}}</td>--}}
                        {{--<td>{{$detalle->cantidad_pedida}}</td>--}}
                        {{--<td>{{$detalle->cantidad_surtida}}</td>--}}
                        {{--<td>--}}
                            {{--<div class="input-group">--}}
                                {{--@if( $detalle->cantidad_surtida < $detalle->cantidad_pedida )--}}
                                    {{--<input type="number" class="form-control" id="renglon_{{$index}}" name="datos_requisicion[{{$index}}][cantidad]" placeholder="Ej: 6" maxlength="4">--}}
                                    {{--<input type="hidden" name="datos_requisicion[{{$index}}][id]" value="{{$detalle->id_requisicion_detalle}}">--}}
                                    {{--<input type="hidden" name="datos_requisicion[{{$index}}][cantidad_surtida]" value="{{$detalle->cantidad_surtida}}">--}}
                                {{--@else--}}
                                    {{--<label>Producto entregado en su totalidad</label>--}}
                                {{--@endif--}}
                            {{--</div><!-- /input-group -->--}}
                        {{--</td>--}}
                    {{--</tr>--}}
                {{--@endforeach--}}
                {{--</tbody>--}}
            {{--</table>--}}
        {{--</div>--}}
    {{--</div>--}}
{{--</div><!--/panel-body-->--}}

{{--@endif--}}
{{--@endsection--}}


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


