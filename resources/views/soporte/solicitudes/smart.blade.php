
@section('content-width', 's12 m12')

@section('fieldset', '')

@section('header-bottom')
	@parent
	<script type="text/javascript" src="{{ asset('js/seguimiento.js') }}"></script>
	<script type="text/javascript">
		
	</script>
@endsection

@section('form-title')
    {{ HTML::tag('h4', 'Datos de la Solicitud') }}
@endsection

@section('form-header')
@if(Route::currentRouteNamed(currentRouteName('show')))
    {!! Form::open(['method'=>'put', 'url' => companyRoute('update'), 'id' => 'form-model', 'class' => 'col s12 m12']) !!}
@endif
@endsection

@section('form-actions')
@if(Route::currentRouteNamed(currentRouteName('show')))
	<div class="col-12 text-right">
			{{ link_to(companyRoute('index'), 'Cerrar', ['class'=>'btn btn-default']) }}
			@if(!in_array($data->fk_id_estatus_ticket, [3,4]) && $data->fk_id_empleado_tecnico == Auth::id() || $data->fk_id_empleado_tecnico == null)
        		{{ Form::button('Actualizar', ['type' =>'submit', 'class'=>'btn btn-primary']) }}
            @endif
	</div>
	
@endif
@endsection

@section('form-content')
	@if(!Route::currentRouteNamed(currentRouteName('index')) && !Route::currentRouteNamed(currentRouteName('export')))
	{{ Form::setModel($data) }}
	<div class="card row my-2">

        <div class="card-header py-2 text-info">
            <div class="row">
            	<div class="col-md-12 col-lg-6">
            		<h5 >Solicitante:</h5>
            		<h5 class="text-primary">{{ (isset($data->empleado) ? $data->empleado->nombre.' '.$data->empleado->apellido_paterno.' '.$data->empleado->apellido_materno : '') }}</h5>
            	</div>
            	<div class="col-sm-12 col-md-6 col-lg-3">
            		<h5>Prioridad: 
            			<span class="{{isset($data->prioridad->color) ? 'text-'.$data->prioridad->color : ''}}">
                			{{isset($data->prioridad->prioridad) ? $data->prioridad->prioridad : ''}}
                			<i class="material-icons">{{isset($data->prioridad->icono) ? $data->prioridad->icono : ''}}</i>
        				</span>
            		</h5>
            		<h5>Estatus:
            			<span class="{{isset($data->estatusTickets->color) ? 'text-'.$data->estatusTickets->color : ''}}">
            				{{isset($data->estatusTickets->estatus) ? $data->estatusTickets->estatus : '?'}}
        				</span>
        			</h5>
            	</div>
            	<div class="col-sm-12 col-md-6 col-lg-3">
            		<h5 class="grey-text text-darken-2 facturas-line">Solicitud No: <span class="text-primary">{{isset($data->id_solicitud) ? $data->id_solicitud : ''}}</span></h5>
            		<h5 class="text-dark">
            			<i class="tiny material-icons">today</i> {{isset($data->fecha_hora_creacion) ? $data->fecha_hora_creacion : ''}}
            		</h5>
            	</div>
        	</div>
        </div>
        <div class="card-body">
        
            <div class="row px-2">
            	<div class="card col-md-12 col-lg-6 p-0 my-2" style="margin-left: -6px !important; margin-right: 12px !important;">
                	<h5 class="card-header">Detalla de la solicitud.</h5>
                	<div class="card-body">
                	
                		<div class="card">
                			<div class="card-header">
                				<b>Asunto:</b> {{isset($data->asunto) ? $data->asunto : ''}}.
                			</div>
                			<div class="card-body">
                				{{isset($data->descripcion) ? $data->descripcion : ''}}
    						</div>
                		</div>
        			
            			@if(isset($attachments) && count($attachments) > 0)
            			<div class="card p-0">
                            <a class="card-header collapsible-header text-primary" data-toggle="collapse" href="#collapseAdjuntos" aria-expanded="false" aria-controls="collapseExample">
                            	<i class="material-icons">move_to_inbox</i> Archivos Adjuntos <i class="material-icons float-right">arrow_drop_down</i>
                            </a>
                            <div id="collapseAdjuntos" class="collapse">
                                <ul class="list-group">
                                    @foreach($attachments as $archivo_adjunto)
                                    <a class="list-group-item" href="{{companyAction('descargarArchivosAdjuntos', ['id' => $archivo_adjunto->id_archivo_adjunto])}}" title="Descargar Archivo">
                                    	<i class="material-icons">attachment</i> {{$archivo_adjunto->nombre_archivo}} <i class="material-icons float-right">file_download</i>
                                    </a>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
            			@endif
                	</div>
                </div>
            
                @if( $data->fk_id_empleado_tecnico == Auth::id() || $data->fk_id_empleado_tecnico == null)
                <div class="card col-md-12 col-lg-6 p-0 my-2" style="margin-right: -6px !important;">
                	<h5 class="card-header">Informacion de la solicitud.</h5>
                	<div class="card-body">
                    	<div class="row">
                    		<div class="form-group col-md-12 col-lg-6">
                        		{{ Form::label('fk_id_empleado_tecnico', '* Tecnico Asignado') }}
                    			{{ Form::select('fk_id_empleado_tecnico', (isset($employees) ? $employees : []), null, ['id'=>'fk_id_empleado_tecnico','class'=>'validate form-control','disabled'=>in_array($data->fk_id_estatus_ticket, [3,4])]) }}
                        		{{ $errors->has('fk_id_empleado_tecnico') ? HTML::tag('span', $errors->first('fk_id_empleado_tecnico'), ['class'=>'help-block deep-orange-text']) : '' }}
                    		</div>
                    		<div class="form-group col-md-12 col-lg-6">
                        		{{ Form::label('fk_id_urgencia', '* Urgencia') }}
                    			{{ Form::select('fk_id_urgencia', (isset($urgencies) ? $urgencies : []), null, ['id'=>'fk_id_urgencia','class'=>'validate form-control','disabled'=>in_array($data->fk_id_estatus_ticket, [3,4])]) }}
                        		{{ $errors->has('fk_id_urgencia') ? HTML::tag('span', $errors->first('fk_id_urgencia'), ['class'=>'help-block deep-orange-text']) : '' }}
                    		</div>
                    	</div>
                    	
                    	<div class="row">
                    		<div class="form-group col-md-12 col-lg-6">
                        		{{ Form::label('fk_id_impacto', '* Impacto') }}
                    			{{ Form::select('fk_id_impacto', (isset($impacts) ? $impacts : []), null, ['id'=>'fk_id_impacto','class'=>'validate form-control','disabled'=>in_array($data->fk_id_estatus_ticket, [3,4])]) }}
                        		{{ $errors->has('fk_id_impacto') ? HTML::tag('span', $errors->first('fk_id_impacto'), ['class'=>'help-block deep-orange-text']) : '' }}
                    		</div>
                    		<div class="form-group col-md-12 col-lg-6">
                        		{{ Form::label('fk_id_categoria', '* Categoria') }}
                    			{{ Form::select('fk_id_categoria', (isset($categorys) ? $categorys : []), null, ['id'=>'fk_id_categoria','class'=>'validate form-control','disabled'=>in_array($data->fk_id_estatus_ticket, [3,4]),'data-url' => companyAction('Soporte\SolicitudesController@obtenerSubcategorias',['id' => '?id'])]) }}
                        		{{ $errors->has('fk_id_categoria') ? HTML::tag('span', $errors->first('fk_id_categoria'), ['class'=>'help-block deep-orange-text']) : '' }}
                    		</div>
                    	</div>
                    	
                    	<div class="row">
                    		<div class="form-group col-md-12 col-lg-6">
                        		{{ Form::label('fk_id_subcategoria', '* Subcategoria') }}
                    			{{ Form::select('fk_id_subcategoria', (isset($subcategorys) ? $subcategorys : []), null, ['id'=>'fk_id_subcategoria','class'=>'validate form-control','disabled'=>in_array($data->fk_id_estatus_ticket, [3,4]),'data-url' => companyAction('Soporte\SolicitudesController@obtenerAcciones',['id' => '?id'])]) }}
                        		{{ $errors->has('fk_id_subcategoria') ? HTML::tag('span', $errors->first('fk_id_subcategoria'), ['class'=>'help-block deep-orange-text']) : '' }}
                    		</div>
                    		<div class="form-group col-md-12 col-lg-6">
                        		{{ Form::label('fk_id_accion', '* Accion') }}
                    			{{ Form::select('fk_id_accion', (isset($acctions) ? $acctions : []), null, ['id'=>'fk_id_accion','class'=>'validate form-control','disabled'=>in_array($data->fk_id_estatus_ticket, [3,4])]) }}
                        		{{ $errors->has('fk_id_accion') ? HTML::tag('span', $errors->first('fk_id_accion'), ['class'=>'help-block deep-orange-text']) : '' }}
                    		</div>
                    	</div>
                    </div>
                </div>
                @else
                 <div class="card col-md-12 col-lg-6 p-0 my-2" style="margin-right: -6px !important;">
            		{{ HTML::tag('h5', 'Datos adicionales de la solicitud') }}
            		<div class="row">
                		<div class="col-md-12 col-lg-6">
                    		{{ Form::label('fk_id_impacto', '* Impacto') }}
                    		{{ HTML::tag('h6', $data->impacto->impacto) }}
                		</div>
                		<div class="col-md-12 col-lg-6">
                    		{{ Form::label('fk_id_urgencia', '* Urgencia') }}
                			{{ HTML::tag('h6', $data->urgencia->urgencia) }}
                		</div>
                	</div>
            	
            		<div class="row">
                		<div class="col-md-12 col-lg-6">
                    		{{ Form::label('fk_id_empleado_tecnico', '* Tecnico Asignado') }}
                    		{{ HTML::tag('h6', $data->a_tecnico) }}
                		</div>
                		<div class="col-md-12 col-lg-6">
                    		{{ Form::label('fk_id_categoria', '* Categoria') }}
                    		{{ HTML::tag('h6', $data->a_categoria) }}
                		</div>
                	</div>
                	
                	<div class="row">
                		<div class="col-md-12 col-lg-6">
                    		{{ Form::label('fk_id_subcategoria', '* Subcategoria') }}
                    		{{ HTML::tag('h6', $data->subcategoria->subcategoria) }}
                		</div>
                		<div class="col-md-12 col-lg-6">
                    		{{ Form::label('fk_id_accion', '* Accion') }}
                    		{{ HTML::tag('h6', $data->accion->accion) }}
                		</div>
                	</div>
                </div>
                @endif
    		</div>
        </div>
    </div>
	@endif
@endsection

@section('form-utils')
    @if(!Route::currentRouteNamed(currentRouteName('index')) && !Route::currentRouteNamed(currentRouteName('export')))
        @if(Auth::id() == $data->fk_id_tecnico_asignado || Auth::id() == $data->fk_id_empleado_solicitud)
    		<!--Conversacion-->
    		<div class="divider mt-2"></div>
        	<ul class="list-group" style="padding:10px; border:none;">
        		<li class="list-group-item active">
        			<h4 class="float-left py-2"><i class="material-icons">question_answer</i> Conversación.</h4>
        			<a href="#responder" class="waves-effect waves-light btn btn-light float-right" data-toggle="modal" data-target="#responder">Responder</a>
        			<span class="pt-3 float-right text-right">Click en responder para darle seguimiento a la solicitud. -></span>
        		</li>
        		
        		<span style='display:none'>{{ $i = 0 }}</span> 
        		@if(count($conversations) < 1)
        			<li class="list-group-item text-secondary text-center">
        				Sin mensajes...
        			</li>
        		@endif
        		@foreach($conversations as $seguimiento)
        		<span style='display:none'>{{ $i++ }}</span>
        		<li class="list-group-item {{$i % 2 != 0 ? 'bg-light' : ''}}">
        			<div class="title {{$seguimiento->fk_id_empleado_comentario == $data->fk_id_empleado_tecnico ? 'text-success' : 'text-danger text-right'}}">
            			<i class="material-icons circle {{$seguimiento->fk_id_empleado_comentario == $data->fk_id_empleado_tecnico ? 'brown' : ''}}">person</i>
            			<span class="col-sm-12 col-md-7">
            				<b>{{ $seguimiento->empleado->nombre.' '.$seguimiento->empleado->apellido_paterno.' '.$seguimiento->empleado->apellido_materno}}</b>
            			</span>
            		</div>
            		<div class="card">
            			<div class="card-header">
            				<b>{{$seguimiento->asunto}}</b>
            				<span class='float-right'><i class="material-icons tiny">event</i>{{ $seguimiento->fecha_hora}}</span>
            			</div>
            			<div class="card-body">{!! $seguimiento->comentario !!}</div>
    			
            			@if(count($seguimiento->archivo_adjunto) > 0)
            			<div class="card-footer p-0">
                			<div class="card p-0">
                                <a class="card-header collapsible-header text-primary" data-toggle="collapse" href="#collapseAdjuntos{{$i}}" aria-expanded="false" aria-controls="collapseExample">
                                	<i class="material-icons">move_to_inbox</i> Archivos Adjuntos <i class="material-icons float-right">arrow_drop_down</i>
                                </a>
                                <div id="collapseAdjuntos{{$i}}" class="collapse">
                                    <ul class="list-group">
                                        @foreach($seguimiento->archivo_adjunto as $archivo_adjunto)
                                        <a class="list-group-item" href="{{companyAction('descargarArchivosAdjuntos', ['id' => $archivo_adjunto->id_archivo_adjunto])}}" title="Descargar Archivo">
                                        	<i class="material-icons">attachment</i> {{$archivo_adjunto->nombre_archivo}} <i class="material-icons float-right">file_download</i>
                                        </a>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
            			@endif
            		</div>
        		</li>
        		@endforeach
        		<li class="list-group-item active">
        			<a href="#responder" class="waves-effect waves-light btn btn-light" data-toggle="modal" data-target="#responder">Responder</a>
        			<span class="text-right"><- Click en responder para darle seguimiento a la solicitud.</span>
        		</li>
        	</ul>
    		<!--Fin de Conversacion-->
    		
    		<!-- Modal para agregar a conversacion -->
    		<div id="responder" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        		<div class="modal-dialog modal-lg" role="document">
            		<div class="modal-content">
            			{!! Form::open(['url'=>companyAction('Soporte\SeguimientoSolicitudesController@index'),'id'=>'form-model','enctype'=>'multipart/form-data']) !!}
                			<div class="modal-header bg-primary text-white">
                           		<h5 class="modal-title" id="exampleModalLabel">Nuevo comentario.</h5>
                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                          	</div>
                          	<div class="modal-body">
                        		<h5>
                            		<i class="material-icons circle">person</i>
                            		<span class="title"><b>{{$data->empleado->nombre.' '.$data->empleado->apellido_paterno.' '.$data->empleado->apellido_materno}}</b></span>
                        		</h5>
                    		
                    			{{ Form::hidden('fk_id_solicitud', $data->id_solicitud,['id'=>'fk_id_solicitud']) }}
                    			{{ Form::hidden('fk_id_empleado_comentario', Null,['id'=>'fk_id_empleado_comentario','data-url'=>companyAction('RecursosHumanos\EmpleadosController@obtenerEmpleado')]) }}
                        		<div class="form-group col-sm-12">
                            		{{ Form::label('asunto', 'Asunto') }}
                        			{{ Form::text('asunto', '', ['class'=>'validate form-control']) }}
                            		{{ $errors->has('asunto') ? HTML::tag('span', $errors->first('asunto'), ['class'=>'help-block deep-orange-text']) : '' }}
                				</div>
                        		
                        		<div class="form-group col-sm-12">
                            		{{ Form::label('comentario', 'Comentario') }}
                        			{{ Form::textarea('comentario', null, ['class'=>'validate form-control']) }}
                            		{{ $errors->has('comentario') ? HTML::tag('span', $errors->first('comentario'), ['class'=>'help-block deep-orange-text']) : '' }}
                        		</div>
                        		
                        		<div class="form-group col-sm-12">
                        			<label class="custom-file w-100">
                                        <span class="custom-file-control"></span>
                                    	{!! Form::file('archivo[]',['id'=>'archivo','class'=>'form-control-file','multiple'=>'multiple']) !!}
                                    </label>
                        		</div>
                        		@if($data->fk_id_empleado_tecnico == Auth::id() && !in_array($data->fk_id_estatus_ticket, [3,4]))
                        		<div class="form-group col-sm-12">
                            		<span>Estatus: </span>
                                	<div data-toggle="buttons" id="estatus">
                            			<label class="btn btn-primary active" data-url="btn-primary">
                        					<input name="fk_id_estatus_ticket" value="" type="radio" checked>
                        					No cambiar estatus
                                        </label>
                                		@foreach($status as $row)
                                			@if($row->id_estatus_ticket != 1 && $row->id_estatus_ticket != $data->fk_id_estatus_ticket)
                            				<label class="btn btn-secondary" data-url="btn-{{$row->color}}">
                            					{{$row->estatus}}
                            					{{ Form::radio('fk_id_estatus_ticket', $row->id_estatus_ticket,['id'=>$row->id_estatus_ticket,'autocomplete'=>'off']) }}
                                            </label>
                                			@endif
                                		@endforeach
                                    </div>
                                </div>
                        		@endif
                        		
                        		@if(in_array($data->fk_id_estatus_ticket, [3,4]))
								<div class="p-3 mb-2 text-danger text-white">
									<h5>El estatus esta como: {{isset($data->estatusTickets->estatus) ? $data->estatusTickets->estatus : ''}}. Deseas abrirlo de nuevo?</h5>
								</div>
								<div class="form-group btn-group" data-toggle="buttons" id="id_estatus">
                    				<label class="btn btn-secondary" data-url="btn-danger">
                    					<span>No</span>
                    					{{ Form::checkbox('fk_id_estatus_ticket', 1, false) }}
                                    </label>
                                </div>	
                        		@endif
                        	</div>
                        	<div class="modal-footer">
                                {!! Form::button('Enviar',['class'=>'btn btn-success','type'=>'submit']) !!}
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            </div>
                		{!! Form::close() !!}
                	</div>
                </div>
    		</div>
        @endif
    @endif
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