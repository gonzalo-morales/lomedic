
@section('fieldset', '')

@section('header-bottom')
	@parent
	<script type="text/javascript" src="{{ asset('js/seguimiento.js') }}"></script>
@endsection

@section('form-title')
	@if(Route::currentRouteNamed(currentRouteName('create')))
		{{ HTML::tag('h4', 'Nueva Solicitud',['class'=>'display-4']) }}
	@else
    	{{ HTML::tag('h4', 'Datos de la Solicitud',['class'=>'display-4']) }}
    @endif
@endsection

@section('form-header')
@if(Route::currentRouteNamed(currentRouteName('show')))
    {!! Form::open(['method'=>'put', 'url' => companyRoute('update'), 'id' => 'form-model', 'class' => 'mb-3']) !!}
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
@else
	@parent
@endif
@endsection

@section('form-content') 
	@if(!Route::currentRouteNamed(currentRouteName('index')) && !Route::currentRouteNamed(currentRouteName('export')) && !Route::currentRouteNamed(currentRouteName('create')))
	{{ Form::setModel($data) }}
	
    <div class="row">
        <div class="col-md-12 text-center">
            <h5><strong>{{ (isset($data->empleado) ? $data->empleado->nombre.' '.$data->empleado->apellido_paterno.' '.$data->empleado->apellido_materno : '') }}</strong></h5>
            <small class="text-muted">
                <i class="material-icons align-middle">today</i> {{isset($data->fecha_hora_creacion) ? $data->fecha_hora_creacion : ''}}
            </small>
            <h5>
            <span class="{{isset($data->estatusTickets->color) ? 'text-'.$data->estatusTickets->color : ''}}">
                {{isset($data->estatusTickets->estatus) ? $data->estatusTickets->estatus : '?'}}
            </span>
            </h5>
        </div>
    </div> 
	<div class="card my-2 z-depth-1-half">
        <div class="card-header">
    		<h5 class="text-center">
    			<span class="{{isset($data->prioridad->color) ? 'text-'.$data->prioridad->color : ''}}">
        			{{isset($data->prioridad->prioridad) ? $data->prioridad->prioridad : ''}}
        			<i class="material-icons align-middle">{{isset($data->prioridad->icono) ? $data->prioridad->icono : ''}}</i>
				</span>
                <span class="float-right badge badge-secondary p-2">No. {{isset($data->id_solicitud) ? $data->id_solicitud : ''}}</span>
    		</h5>
        </div>

        <div class="card-body">
        
            <div class="row">
            	<div class="col-sm-12 col-md-6 col-lg-6">
            	
            			<div class="jumbotron p-3">
            				<h5 class="lead">{{isset($data->asunto) ? $data->asunto : ''}}.</h5>
            				<p>{{isset($data->descripcion) ? $data->descripcion : ''}}</p>
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
            
                @if( $data->fk_id_empleado_tecnico == Auth::id() || $data->fk_id_empleado_tecnico == null)
                <div class="col-sm-12 col-md-6 col-lg-6">
                	<h5>Informacion de la solicitud.</h5>
                	<div class="card-body">
                    	<div class="row">
                            <div class="col-sm-12 col-md-6 col-lg-6">
                        		<div class="form-group">
                            		{{ Form::label('fk_id_empleado_tecnico', '* Tecnico Asignado') }}
                        			{{ Form::select('fk_id_empleado_tecnico', (isset($employees) ? $employees : []), null, ['id'=>'fk_id_empleado_tecnico','class'=>'validate form-control','disabled'=>in_array($data->fk_id_estatus_ticket, [3,4])]) }}
                            		{{ $errors->has('fk_id_empleado_tecnico') ? HTML::tag('span', $errors->first('fk_id_empleado_tecnico'), ['class'=>'help-block deep-orange-text']) : '' }}
                        		</div>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-6">
                        		<div class="form-group">
                            		{{ Form::label('fk_id_urgencia', '* Urgencia') }}
                        			{{ Form::select('fk_id_urgencia', (isset($urgencies) ? $urgencies : []), null, ['id'=>'fk_id_urgencia','class'=>'validate form-control','disabled'=>in_array($data->fk_id_estatus_ticket, [3,4])]) }}
                            		{{ $errors->has('fk_id_urgencia') ? HTML::tag('span', $errors->first('fk_id_urgencia'), ['class'=>'help-block deep-orange-text']) : '' }}
                        		</div>
                            </div>
                    	</div>
                    	
                    	<div class="row">
                            <div class="col-sm-12 col-md-6 col-lg-6">
                        		<div class="form-group">
                            		{{ Form::label('fk_id_impacto', '* Impacto') }}
                        			{{ Form::select('fk_id_impacto', (isset($impacts) ? $impacts : []), null, ['id'=>'fk_id_impacto','class'=>'validate form-control','disabled'=>in_array($data->fk_id_estatus_ticket, [3,4])]) }}
                            		{{ $errors->has('fk_id_impacto') ? HTML::tag('span', $errors->first('fk_id_impacto'), ['class'=>'help-block deep-orange-text']) : '' }}
                        		</div>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-6">
                        		<div class="form-group">
                            		{{ Form::label('fk_id_categoria', '* Categoria') }}
                        			{{ Form::select('fk_id_categoria', (isset($categorys) ? $categorys : []), null, ['id'=>'fk_id_categoria','class'=>'validate form-control','disabled'=>in_array($data->fk_id_estatus_ticket, [3,4]),'data-url' => companyAction('Soporte\SolicitudesController@obtenerSubcategorias',['id' => '?id'])]) }}
                            		{{ $errors->has('fk_id_categoria') ? HTML::tag('span', $errors->first('fk_id_categoria'), ['class'=>'help-block deep-orange-text']) : '' }}
                        		</div>
                            </div>
                    	</div>
                    	
                    	<div class="row">
                            <div class="col-sm-12 col-md-6 col-lg-6">
                        		<div class="form-group">
                            		{{ Form::label('fk_id_subcategoria', '* Subcategoria') }}
                        			{{ Form::select('fk_id_subcategoria', (isset($subcategorys) ? $subcategorys : []), null, ['id'=>'fk_id_subcategoria','class'=>'validate form-control','disabled'=>in_array($data->fk_id_estatus_ticket, [3,4]),'data-url' => companyAction('Soporte\SolicitudesController@obtenerAcciones',['id' => '?id'])]) }}
                            		{{ $errors->has('fk_id_subcategoria') ? HTML::tag('span', $errors->first('fk_id_subcategoria'), ['class'=>'help-block deep-orange-text']) : '' }}
                        		</div>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-6">
                        		<div class="form-group">
                            		{{ Form::label('fk_id_accion', '* Accion') }}
                        			{{ Form::select('fk_id_accion', (isset($acctions) ? $acctions : []), null, ['id'=>'fk_id_accion','class'=>'validate form-control','disabled'=>in_array($data->fk_id_estatus_ticket, [3,4])]) }}
                            		{{ $errors->has('fk_id_accion') ? HTML::tag('span', $errors->first('fk_id_accion'), ['class'=>'help-block deep-orange-text']) : '' }}
                        		</div>
                            </div>
                    	</div>
                    </div>
                </div>
                @else
                 <div class="col-sm-12 col-md-6 col-lg-6">
            		{{ HTML::tag('h5', 'Datos adicionales:') }}
            		<div class="row py-1">
                		<div class="col-12 col-sm-6 col-md-6 col-lg-6">
                    		{{ Form::label('fk_id_impacto', '* Impacto') }}
                    		{{ HTML::tag('h6', $data->impacto->impacto) }}
                		</div>
                		<div class="col-12 col-sm-6 col-md-6 col-lg-6">
                    		{{ Form::label('fk_id_urgencia', '* Urgencia') }}
                			{{ HTML::tag('h6', $data->urgencia->urgencia) }}
                		</div>
                	</div>
            	
            		<div class="row py-1">
                		<div class="col-12 col-sm-6 col-md-6 col-lg-6">
                    		{{ Form::label('fk_id_empleado_tecnico', '* Tecnico Asignado') }}
                    		{{ HTML::tag('h6', $data->a_tecnico) }}
                		</div>
                		<div class="col-12 col-sm-6 col-md-6 col-lg-6">
                    		{{ Form::label('fk_id_categoria', '* Categoria') }}
                    		{{ HTML::tag('h6', $data->a_categoria) }}
                		</div>
                	</div>
                	
                	<div class="row py-1">
                		<div class="col-12 col-sm-6 col-md-6 col-lg-6">
                    		{{ Form::label('fk_id_subcategoria', '* Subcategoria') }}
                    		{{ HTML::tag('h6', $data->subcategoria->subcategoria) }}
                		</div>
                		<div class="col-12 col-sm-6 col-md-6 col-lg-6">
                    		{{ Form::label('fk_id_accion', '* Accion') }}
                    		{{ HTML::tag('h6', $data->accion->accion) }}
                		</div>
                	</div>
                </div>
                @endif
    		</div>
        </div>
    </div>
    @elseif(Route::currentRouteNamed(currentRouteName('create')))
      	<div class="modal-body">
  			<div class="form-group">
  				<div style="padding:6px; width:100%; border-bottom:1px solid #eee;">
  					Solicitante: <span><b>{{Auth::User()->nombre_corto}}</b></span>
					{{Form::hidden('id_solicitante',Auth::User()->fk_id_empleado,['id'=>'id_solicitante'])}}
  				</div>
  				<div class="input-group input-group-sm w-100">
					<span class="input-group-addon">
						<input type="checkbox" id="check_solicitante">
					</span>
					{!! Form::select('empleado_solicitud',[],null,['id'=>'empleado_solicitud','class'=>'form-control select2-single select2-hidden-accessible','disabled'=>'true','style'=>'width: 96% !important;','data-url'=>companyAction('RecursosHumanos\EmpleadosController@obtenerEmpleados')]) !!}
  				</div>
  			</div>
  			<div class="form-group row">
  				<div class="col-md-12">
      				{{Form::label('fk_id_sucursal','Sucursal')}}
      				{!! Form::select('fk_id_sucursal',[],null,['id'=>'fk_id_sucursal','class'=>'form-control select2-single select2-hidden-accessible','style'=>'width: 96% !important;','data-url'=>companyAction('Administracion\SucursalesController@sucursalesEmpleado',['id'=>'?id'])]) !!}
  				</div>
  			</div>
  			<div class="form-group row">
  				<div class="col-md-12 col-lg-6">
      				{{Form::label('fk_id_categoria','Categoría')}}
            		{!! Form::select('fk_id_categoria',$categories_tickets,null,['id'=>'fk_id_categoria','class'=>'form-control fk_id_categoria','data-url' => companyAction('Soporte\SolicitudesController@obtenerSubcategorias',['id' => '?id'])])!!}
  				</div>
  				<div class="col-md-12 col-lg-6">
      				{{Form::label('fk_id_subcategoria','Subategoría')}}
        			{!! Form::select('fk_id_subcategoria',[],null,['id'=>'fk_id_subcategoria','class'=>'form-control fk_id_subcategoria','disabled'=>'disabled','data-url' => companyAction('Soporte\SolicitudesController@obtenerAcciones',['id' => '?id'])]) !!}
  				</div>
  			</div>
  			<div class="form-group row">
  				<div class="col-md-12 col-lg-6">
      				{{Form::label('fk_id_accion','Acción')}}
        			{!! Form::select('fk_id_accion',[],null,['id'=>'fk_id_accion','class'=>'form-control fk_id_accion','disabled'=>'disabled']) !!}
  				</div>
  				<div class="col-md-12 col-lg-6">
      				{{Form::label('fk_id_prioridad','Prioridad')}}
        			{!! Form::select('fk_id_prioridad',$priorities_tickets->pluck('prioridad','id_prioridad'),null,['id'=>'fk_id_prioridad','class'=>'form-control']) !!}
  				</div>
  			</div>
  			<div class="form-group">
        		{{Form::label('asunto','Asunto')}}
  				{!! Form::text('asunto',old('asunto'),['class'=>'form-control validate','id'=>'asunto']) !!}
  			</div>
  			<div class="form-group">
  				{{Form::label('descripción','Descripción')}}
        		{!! Form::textarea('descripcion',old('descripcion'),['id'=>'descripcion','class'=>'form-control']) !!}
  			</div>
  			<div class="form-group">
      			<label class="custom-file w-100">
                    <span class="custom-file-control"></span>
                	{!! Form::file('archivo[]',['id'=>'archivo','class'=>'form-control-file','multiple'=>'multiple']) !!}
                </label>
  			</div>
      	</div>
	@endif
@endsection

@section('form-utils')
    @if(!Route::currentRouteNamed(currentRouteName('index')) && !Route::currentRouteNamed(currentRouteName('export')) && !Route::currentRouteNamed(currentRouteName('create')))
        @if(Auth::id() == $data->fk_id_tecnico_asignado || Auth::id() == $data->fk_id_empleado_solicitud)
    		<!--Conversacion-->
        	<ul class="list-group mb-3">
        		<li class="list-group-item">
        			<h4 class="float-left py-2"><i class="material-icons align-middle">question_answer</i> Conversación.</h4>
        			<a href="#responder" class="btn btn-primary float-right" data-toggle="modal" data-target="#responder">Responder</a>
        			<span class="pt-3 float-right text-right"><i>Clic en responder para darle seguimiento a la solicitud. -></i></span>
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
        			<div class="title {{$seguimiento->fk_id_empleado_comentario == $data->fk_id_empleado_tecnico ? 'text-info' : 'text-dark text-right'}}"><small>
            			<i class="rounded-circle p-2 bg-light material-icons align-middle {{$seguimiento->fk_id_empleado_comentario == $data->fk_id_empleado_tecnico ? 'brown' : ''}}">person</i>
            			<span>
            				<b>{{ $seguimiento->empleado->nombre.' '.$seguimiento->empleado->apellido_paterno.' '.$seguimiento->empleado->apellido_materno}}</b>
            			</span>
            		</small></div>
            			<div>
            				<b>{{$seguimiento->asunto}}</b>
            				<small class='float-right text-muted'><i class="material-icons align-middle">event</i>{{ $seguimiento->fecha_hora}}</small>
            			</div>

            			<div class="p-3 blockquote">{!! $seguimiento->comentario !!}</div>
    			
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
        		</li>
        		@endforeach
        		<li class="list-group-item">
        			<a href="#responder" class="btn btn-primary float-right" data-toggle="modal" data-target="#responder">Responder</a>
        			<span class="pt-3 float-right text-right"><i>Clic en responder para darle seguimiento a la solicitud. -></i></span>
        		</li>
        	</ul>
    		<!--Fin de Conversacion-->
    		
    		<!-- Modal para agregar a conversacion -->
    		<div id="responder" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        		<div class="modal-dialog modal-lg" role="document">
            		<div class="modal-content">
            			{!! Form::open(['url'=>companyAction('Soporte\SeguimientoSolicitudesController@index'),'id'=>'form-model','enctype'=>'multipart/form-data']) !!}
                			<div class="modal-header bg-light">
                           		<h5 class="modal-title" id="exampleModalLabel">Nuevo comentario.</h5>
                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                          	</div>
                          	<div class="modal-body">
                        		<p class="text-center"><small>
                            		<i class="material-icons rounded-circle p-2 bg-light align-middle">person</i>
                            		<span class="title"><b>{{$data->empleado->nombre.' '.$data->empleado->apellido_paterno.' '.$data->empleado->apellido_materno}}</b></span>
                        		</small></p>
                    		
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
								<div class="text-center">
									<h3>{{isset($data->estatusTickets->estatus) ? $data->estatusTickets->estatus : ''}}</h3>
								</div>
								<div class="form-group text-center" data-toggle="buttons" id="id_estatus">
                                    ¿Deseas abrirlo de nuevo?
                    				<label class="btn btn-secondary" data-url="btn-danger">
                    					<span>No</span>
                    					{{ Form::checkbox('fk_id_estatus_ticket', 1, false) }}
                                    </label>
                                </div>	
                        		@endif
                        	</div>
                        	<div class="modal-footer">
                                {!! Form::button('Enviar',['class'=>'btn btn-primary','type'=>'submit']) !!}
                                <button type="button" class="text-primary btn btn-default" data-dismiss="modal">Cerrar</button>
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