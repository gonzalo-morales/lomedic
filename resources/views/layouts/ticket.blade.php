<!-- Sidebar Holder -->
<nav id="rigth-sidebar" class="bg-white">
    <div class="sidebar-header">
        <img class="background" src="{{asset('img/helpBG2.png')}}">
    	<div class="h-content">
        <span><i class="material-icons medium white-text">live_help</i></span>
        <a class="white-text" href="#"><span class="name">Sección de ayuda</span></a>
        <a href="#ticketHelp" class="white-text dismiss" data-toggle="modal" data-target="#ticketHelp">Crear ticket/solicitud</a>
        </div>
    </div>

    <ul class="list-unstyled components bg-white">
    	<li>
            <a class="collapsed d-flex" href="#tickets" data-toggle="collapse" aria-expanded="false">
            	<i class="material-icons">list</i>Tickets recientes
            	<i class="material-icons float-right grey-text">expand_more</i>
            </a>
            <ul id="tickets" class="list-unstyled collapse" aria-expanded="false">
                @foreach($ultimos_tickets as $ticket)
                <li>
                	<a href="{{ companyAction('Soporte\SolicitudesController@show', ['id' => $ticket->id_solicitud]) }}" class="btn btn-default d-flex"><i class="material-icons">note</i>
                        {{$ticket->asunto}}
                    </a>
                </li>
                @endforeach
            </ul>
        </li>
        <li><a href="{{ companyAction('Soporte\SolicitudesController@index') }}" class="d-flex"><i class="material-icons">dvr</i> Todos mis tickets</a></li>
        <div class="divider"></div>
        <li><a href="#!">Gestion Estrategica (GE)</a></li>
        <li><a href="#!">Gestion de Negociacion (GN)</a></li>
        <li><a href="#!">Gestion de Recursos (GR)</a></li>
        <li><a href="#!">Proveeduria y Suministro (PS)</a></li>
    </ul>
</nav>

<div class="overlay"></div>

<div id="ticketHelp" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
      		{!! Form::model(null,['url'=>companyAction('Soporte\SolicitudesController@store'), 'enctype'=>"multipart/form-data",'method'=>'post']) !!}
                <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="exampleModalLabel">Nuevo Ticket:</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    	<span aria-hidden="true">&times;</span>
                    </button>
              	</div>
              	
              	<div class="modal-body">
          			<div class="form-group">
          				<span>Solicitante: <b>{{Auth::User()->nombre_corto}}</b></span>
          				
          				<div class="input-group input-group-sm">
        					<span class="input-group-addon">
        						<input type="checkbox">
        					</span>
        					<select id="empleado_solicitud" class="select2-single" placeholder="Selecciona si la solicitud es para otra persona" disabled>
        					</select>
          				</div>
					
						<!--
                        {!! Form::text('empleado_solicitud',null,['id'=>'empleado_solicitud','class'=>'form-control','autocomplete'=>'off','data-url'=>companyAction('RecursosHumanos\EmpleadosController@obtenerEmpleados')])!!}
                        {{Form::hidden('nombre_solicitante',null,['id'=>'nombre_solicitante','data-url'=>companyAction('Administracion\SucursalesController@sucursalesEmpleado',['id'=>'?id'])])}} -->
          			</div>
          			<div class="form-group row">
          				<div class="col-md-12 col-lg-6">
              				{{Form::label('fk_id_sucursal','Sucursal')}}
                    		{!! Form::select('fk_id_sucursal',[],null,['id'=>'fk_id_sucursal','class'=>'form-control','disabled' => 'true'])!!}
          				</div>
          				<div class="col-md-12 col-lg-6">
              				{{Form::label('fk_id_categoria','Categoría')}}
                    		{!! Form::select('fk_id_categoria',$categories_tickets,null,['id'=>'fk_id_categoria','class'=>'form-control','data-url' => companyAction('Soporte\SolicitudesController@obtenerSubcategorias',['id' => '?id'])])!!}
          				</div>
          			</div>
          			<div class="form-group row">
          				<div class="col-md-12 col-lg-6">
              				{{Form::label('fk_id_subcategoria','Subategoría')}}
                			{!! Form::select('fk_id_subcategoria',[],null,['id'=>'fk_id_subcategoria','class'=>'form-control','disabled'=>'disabled','data-url' => companyAction('Soporte\SolicitudesController@obtenerAcciones',['id' => '?id'])]) !!}
          				</div>
          				<div class="col-md-12 col-lg-6">
              				{{Form::label('fk_id_accion','Acción')}}
            			{!! Form::select('fk_id_accion',[],null,['id'=>'fk_id_accion','class'=>'form-control','disabled'=>'disabled']) !!}
          				</div>
          			</div>
          			<div class="form-group">
          				<p class="form-check form-check-inline">Prioridad:</p>
                        @foreach($priorities_tickets as $priority_ticket)
                            <p class="form-check form-check-inline">
                                {!! Form::radio('fk_id_prioridad',$priority_ticket->id_prioridad,false,['id'=>'prioridad'.$priority_ticket->id_prioridad]) !!}
                                {{ Form::label('prioridad'.$priority_ticket->id_prioridad,$priority_ticket->prioridad) }}
                            </p>
                        @endforeach
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
    
                <div class="modal-footer">
                    {!! Form::button('Enviar',['class'=>'btn btn-primary','type'=>'submit']) !!}
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
      		{!! Form::close() !!}
        </div>
    </div>
</div><!--/Modal de ayuda-->
