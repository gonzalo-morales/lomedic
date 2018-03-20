@extends(smart())
@section('content-width', 's12')

@section('header-bottom')
    @parent
    @if (!Route::currentRouteNamed(currentRouteName('index')) && !Route::currentRouteNamed(currentRouteName('show')) )
        <script type="text/javascript" src="{{ asset('js/recetas.js') }}"></script>
    @endif
@endsection

@if(!Route::currentRouteNamed(currentRouteName('index')) && !Route::currentRouteNamed(currentRouteName('create')))
@section('left-actions')
    {!! HTML::decode(link_to(companyAction('Servicios\RecetasController@impress',['id'=>$data->id_receta]), '<i class="material-icons align-middle">print</i> Imprimir', ['class'=>'btn btn-info imprimir'])) !!}
@endsection
@endif

@section('form-content')
    {{ Form::setModel($data) }}
    @if (Route::currentRouteNamed(currentRouteName('create')) || Route::currentRouteNamed(currentRouteName('show')) || Route::currentRouteNamed(currentRouteName('edit')))
        @if (Route::currentRouteNamed(currentRouteName('create')) || Route::currentRouteNamed(currentRouteName('show')))
            <div class="row">
                <div class="col-sm-12">
                    <div class="card z-depth-1-half">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <div class="tab-content">
                                        <div class="tab-pane active" role="tabpanel">
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        {{ Form::cSelect('Sucursal', 'fk_id_sucursal', $localidades ?? [],['class'=>'select2','data-url'=>companyRoute('getProyectos')]) }}
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        {{ Form::cSelect('Proyecto', 'fk_id_proyecto', $proyectos ?? [],['class'=>'select2']) }}
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        {{ Form::cSelect('Medico', 'fk_id_medico', $medicos ?? [],['class'=>'select2']) }}
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        {{ Form::cSelect('Programa', 'fk_id_programa', $programas ?? [],['class'=>'select2']) }}
                                                    </div>
                                                </div>
                                            </div><!--/row forms-->
                                            <div class="row">
                                                {{--<h5>hola</h5>--}}
                                                <div class="col-sm-3">
                                                    <h6>Nombre paciente no afiliado</h6>
                                                    {{--<h4>¿Surtido recurrente?</h4>--}}
                                                    <div class="input-group my-group">
                                                        <div class="input-group-btn" role="group" aria-label="surtido" data-toggle="buttons">
                                                            {{--<label class="btn btn-check btn-default">--}}
                                                                {{Form::cCheckboxBtn(' ','Externo','tipo_servicio',(empty($data->fk_id_afiliacion) ? 0 : 1),'Afiliado')}}
                                                            {{--</label>--}}
                                                        </div>
                                                        {{ Form::cText(' ', 'nombre_paciente_no_afiliado',['disabled'])}}
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        {{ Form::cSelect('Afiliación/Paciente', 'fk_id_afiliado', $afiliados ?? [] ,['class'=>'select2','data-url'=>companyRoute('getAfiliados')]) }}
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        {{ Form::cSelect('Área de la consulta', 'fk_id_area', $areas ?? [],['class'=>'select2']) }}
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        {{ Form::cSelect('Diagnóstico', 'fk_id_diagnostico', $diagnosticos ?? [''=>''],['class'=>'select2','data-url'=>companyRoute('getDiagnosticos')]) }}

                                                    </div>
                                                </div>

                                            </div>

                                            <div class="row">

                                                <div class="col-sm-2 col-xs-6">
                                                    <div class="form-group">
                                                        {{ Form::cText('Peso:', 'peso',['class'=>'form-control peso'])}}
                                                    </div>
                                                </div>
                                                <div class="col-sm-2 col-xs-6">
                                                    <div class="form-group">
                                                        {{ Form::cText('Altura:', 'altura',['class'=>'form-control peso'])}}
                                                    </div>
                                                </div>
                                                <div class="col-sm-4 col-xs-6">
                                                    <div class="form-group">
                                                        <label for="presion">Presión:</label>
                                                        <div class="input-group">
                                                            {{ Form::cNumber(' ', 'presion_sistolica',['class'=>'form-control integer','placeholder'=>'Ej: 120'])}}
                                                            <span class="input-group-addon" id="presion-addon">/</span>
                                                            {{ Form::cNumber(' ', 'presion_diastolica',['class'=>'form-control integer','placeholder'=>'Ej: 80'])}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><!--/row-->
                                        </div>
                                    </div><!--/row-->
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                {{--@if (!Route::currentRouteNamed(currentRouteName('show')))--}}
                    @if( $data['fk_id_estatus_receta'] == 1)
                        <div class="card z-depth-1-half">
                            <div class="card-header">
                                @if (!Route::currentRouteNamed(currentRouteName('show')))
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <div class="tab-content">
                                            <div class="tab-pane active" role="tabpanel">
                                                <div class="row">
                                                    <div class="form-group" style="width: 100%;">
                                                        <div class="col-sm-12">
                                                            {{ Form::cSelect('Medicamento', 'medicamento', $skus ?? [],['class'=>'select2','data-url'=>companyRoute('getMedicamentos')]) }}
                                                        </div>
                                                    </div>
                                                </div><!--/row forms-->

                                                <div class="row">

                                                    <div class="col-sm-4 border-right">
                                                        <h4>*Dosis:</h4>
                                                        <div class="input-group my-group">
                                                            <div class="input-group-btn" role="group" aria-label="dosis" data-toggle="buttons">
                                                                <label class="btn btn-check btn-default">
                                                                    <input name="dosis14" id="dosis14" autocomplete="off" class="btn btn-default dosis_checkbox" type="checkbox">1/4
                                                                </label>
                                                                <label class="btn btn-check btn-default">
                                                                    <input name="dosis12" id="dosis12" autocomplete="off" class="btn btn-default dosis_checkbox" type="checkbox">1/2
                                                                </label>
                                                            </div>
                                                            <input id="dosis" min="1" class="form-control integer" placeholder="Ej. 6" name="dosis" type="number">
                                                            <input id="_dosis" class="_dosis form-control" disabled="" name="_dosis" type="text">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4 border-right">
                                                        <h4>*Cada:</h4>
                                                        <div class="input-group my-group">
                                                            {{Form::number('cada',null,['id'=>'cada','min'=>1,'class'=>'form-control integer','placeholder'=>'Ej. 6','min'=>'1'])}}
                                                            {{Form::select('_cada',['1'=>'Hora(s)','24'=>'Día(s)','168'=>'Semana(s)','720'=>'Mes(es)'],null,['id'=>'_cada','class' => '_cada form-control'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <h4>*Por:</h4>
                                                        <div class="input-group my-group">
                                                            {{Form::number('por',null,['id'=>'por','min'=>1,'class'=>'number-only form-control integer','placeholder'=>'Ej. 6','min'=>'1'])}}
                                                            {{Form::select('_por',['24'=>'Día(s)','168'=>'Semana(s)','720'=>'Mes(es)'],null,['id'=>'_por','class' => '_por form-control'])}}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <h4>En caso de presentar:</h4>
                                                        {{Form::textarea('nota_medicamento',null,['class' => 'form-control','style'=>'resize:vertical','rows'=>'1','id'=>'nota_medicamento'])}}
                                                    </div>
                                                    <div class="col-sm-6 border-right">
                                                        <h4>¿Surtido recurrente?</h4>
                                                        <div class="input-group my-group">
                                                            <div class="input-group-btn" role="group" aria-label="surtido" data-toggle="buttons">
                                                                <label class="btn btn-check btn-default">
                                                                    <input type="checkbox" name="surtido_recurrente" id="surtido_recurrente" autocomplete="off" class="btn btn-default checkbox_surtido" onchange="resurtir();">Recurrente
                                                                </label>
                                                            </div>
                                                            {{Form::number('surtido_numero',null,['id'=>'surtido_numero','min'=>1,'placeholder'=>'Ej: 6','class'=>'integer form-control','disabled'])}}
                                                            {{Form::select('surtido_tiempo',['24'=>'DÃ­a(s)','168'=>'Semana(s)','720'=>'Mes(es)'],null,['id'=>'surtido_tiempo','class'=>'form-control','disabled'])}}
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-12 my-2">
                                                        <div class="sep sepBtn">
                                                            <button class="btn btn-primary btn-large btn-circle" data-placement="bottom" data-delay="100" data-tooltip="Agregar" data-toggle="tooltip" data-action="add" title="Agregar" type="button" onclick="agregar_medicamento();"><i class="material-icons">add</i></button>
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
                                        <th>Codigo</th>
                                        <th>Medicamento recetado</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody class="medicine_detail">
                                    @if(isset($data->detalles))
                                        @foreach($data->detalles->where('eliminar',false ) as $row => $detalle)
                                            <tr id="{{$detalle->producto['id_sku']}}">
                                                <th scope="row">{{$detalle->claveClienteProducto->producto->sku}}</th>
                                                <td>
                                                    <input name="relations[has][detalles][{{$row}}][id_receta_detalle]" type="hidden" value="{{$detalle->id_receta_detalle}}">
                                                    <p><input id="clave_cliente" name="relations[has][detalles][{{$row}}][fk_id_clave_cliente_producto]" type="hidden" value="{{$detalle->producto['id_sku']}}">{{$detalle->producto['descripcion']}}</p>
                                                    <p><input id="tbdosis" name="relations[has][detalles][{{$row}}][dosis]" type="hidden" value="{{$detalle->dosis}}">{{$detalle->dosis}}</p>
                                                    <input id="tbveces_surtir" name="relations[has][detalles][{{$row}}][veces_surtir]" type="hidden" value="{{$detalle->veces_surtidas}}">
                                                </td>
                                                <td>
                                                    <a data-delete-type="single"  data-toggle="tooltip" data-placement="top" title="Borrar"  id="{{$row}}" aria-describedby="tooltip687783" onclick="eliminarFila(this)" ><i class="material-icons text-primary">delete</i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>

                                @if (!Route::currentRouteNamed(currentRouteName('edit')))
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                {{ Form::cTextArea('Observaciones adicionales:', 'observaciones', isset($data->observaciones)?$data->observaciones:null) }}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @else
                        @if($data->fk_id_estatus_receta == 2)
                            <h1>Esta receta ya fue surtida en su totalidad y no se puede editar.</h1>
                        @elseif($data->fk_id_estatus_receta == 3)
                            <h1>Esta receta esta siendo surtida parcialmente y no se puede editar.</h1>
                        @elseif($data->fk_id_estatus_receta == 4)
                            <h1>Esta receta esta cancelada y no se puede editar.</h1>
                        @endif
                    @endif
                {{--@endif--}}
                {{--@if (Route::currentRouteNamed(currentRouteName('show')))--}}

                    {{--<div class="card z-depth-1-half">--}}
                        {{--<div class="card-header">--}}
                            {{--<div class="row">--}}
                                {{--<div class="col-12 mb-3">--}}
                                    {{--<div class="tab-content">--}}
                                        {{--<div class="tab-pane active" role="tabpanel">--}}
                                            {{--<div class="row">--}}
                                                {{--<div class="col-sm-12 table-responsive">--}}

                                                    {{--<table class="table table-hover" id="detalle" data-url="{{companyRoute('verifyStock')}}">--}}
                                                        {{--<thead>--}}
                                                        {{--<tr>--}}
                                                            {{--<th>Sku</th>--}}
                                                            {{--<th>Medicamento recetado</th>--}}
                                                            {{--<th>Cantidad solicitada</th>--}}
                                                            {{--<th>Cantidad surtida</th>--}}
                                                        {{--</tr>--}}
                                                        {{--</thead>--}}
                                                        {{--<tbody class="medicine_detail">--}}
                                                        {{--@if(isset($data->detalles))--}}
                                                            {{--@foreach($data->detalles->where('eliminar',0) as $detalle)--}}
                                                                {{--<tr>--}}
                                                                    {{--<th scope="row">{{$detalle->producto['id_sku']}}</th>--}}
                                                                    {{--<td>--}}
                                                                        {{--<p><input id="clave_cliente" name="_detalle[{{$detalle->producto['id_sku']}}][clave_cliente]" type="hidden" value="{{$detalle->producto['id_sku']}}">{{$detalle->producto['descripcion']}}</p>--}}
                                                                        {{--<p><input id="tbdosis" name="_detalle[{{$detalle->producto['id_sku']}}][dosis]" type="hidden" value="{{$detalle->dosis}}">{{$detalle->dosis}}</p>--}}
                                                                        {{--<input id="_detalle" name="_detalle[{{$detalle->producto['id_sku']}}][fk_id_cuadro]" type="hidden" value="{{$detalle->fk_id_clave_cliente_producto}}">--}}
                                                                        {{--<input id="tbveces_surtir" name="_detalle[{{$detalle->producto['id_sku']}}][veces_surtir]" type="hidden" value="{{$detalle->veces_surtidas}}">--}}
                                                                    {{--</td>--}}
                                                                    {{--<td>{{$detalle->cantidad_pedida}}</td>--}}
                                                                    {{--<td>{{$detalle->cantidad_surtida}}</td>--}}
                                                                {{--</tr>--}}
                                                            {{--@endforeach--}}
                                                        {{--@endif--}}
                                                        {{--</tbody>--}}
                                                    {{--</table>--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                        {{--</div><!--/well-->--}}
                                        {{--<div class="row">--}}
                                            {{--<div class="col-sm-12">--}}
                                                {{--<div class="form-group">--}}
                                                    {{--{{ Form::cTextArea('Observaciones adicionales:', 'observaciones', isset($data->observaciones)?$data->observaciones:null) }}--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                        {{--</div><!--/row-->--}}
                                    {{--</div>--}}
                                {{--</div><!--/row-->--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
            {{--</div>--}}
            {{--@endif--}}
            </div>
            </div>

        @endif

        <div class="container-fluid">
            <div class="panel-body">


                <div class="row">


                    <div class="well">



                        @if(Route::currentRouteNamed(currentRouteName('show')))
                            {{--<div class="row">--}}
                            {{--<div class="col-sm-12 text-center">--}}
                            {{--{{ Form::button('<span class="glyphicon glyphicon-flash"></span> Surtir', ['type' =>'button', 'class'=>'btn btn-danger','id'=>'surtir','enabled']) }}--}}
                            {{--<a href="{{companyAction('surtirReceta',['id'=>$data->id_receta])}}" role="button" class="btn btn-danger gotUndisable"><span class="glyphicon glyphicon-arrow-up"></span> Surtir receta</a>--}}
                            {{--<a href="{{companyAction('imprimirReceta',['id'=>$data->id_receta])}}" role="button" class="btn btn-default gotUndisable"><span class="glyphicon glyphicon-print"></span> Imprimir receta</a>--}}
                            {{--</div>--}}
                            {{--</div><!--/row-->--}}
                        @endif
                    </div><!--/panel-body-->
                </div>

                <div class="modal fade" tabindex="-1" role="dialog" id="modal">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title">Medicamento(s) agotado</h4>
                            </div>
                            <div class="modal-body">
                                <p id="medicamento_modal"></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" id="candelar" class="btn btn-default" data-dismiss="modal">No</button>
                                <button type="button" id="aceptar" class="btn btn-danger">SÃ­</button>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->

                @endsection


                @if (Route::currentRouteNamed(currentRouteName('create')))

                @endif
