<!-- Sidebar Holder -->
<nav id="rigth-sidebar" class="bg-white">
    <div class="sidebar-header">
        <img class="background" src="{{asset('img/helpBG2.png')}}">
    	<div class="h-content">
        <span><i class="material-icons medium white-text">live_help</i></span>
        <a class="white-text" href="#"><span class="name">Sección de ayuda</span></a>
        <a href="#ticketHelp" class="waves-effect waves-light btn-flat white-text dismiss" data-toggle="modal" data-target="#ticketHelp">Crear ticket/solicitud</a>
        </div>
    </div>

    <ul class="list-unstyled components bg-white">
    	<li>
            <a class="collapsed d-flex align-items-center" href="#tickets" data-toggle="collapse" aria-expanded="false">
            	<i class="material-icons">list</i>Tickets recientes
            	<i class="material-icons float-right grey-text">expand_more</i>
            </a>
            <ul id="tickets" class="list-unstyled collapse" aria-expanded="false">
                @foreach($ultimos_tickets as $ticket)
                <li>
                	<a href="{{ companyAction('Soporte\SolicitudesController@show', ['id' => $ticket->id_solicitud]) }}" class="btn d-flex align-items-center"><i class="material-icons">note</i>
                        {{$ticket->asunto}}
                    </a>
                </li>
                @endforeach
            </ul>
        </li>
        <li><a class="d-flex align-items-center" href="{{ companyAction('Soporte\SolicitudesController@index') }}"><i class="material-icons">dvr</i>Todos mis tickets</a></li>
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
      		{!! Form::model(null,['url'=>companyAction('Soporte\SolicitudesController@store'), 'enctype'=>"multipart/form-data",'method'=>'post','id'=>'form-ticket']) !!}
                <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="exampleModalLabel">Nuevo Ticket:</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    	<span aria-hidden="true">&times;</span>
                    </button>
              	</div>
              	
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
                    		{!! Form::select('fk_id_categoria',$categories_tickets,null,['id'=>'fk_id_categoria','class'=>'form-control','data-url' => companyAction('Soporte\SolicitudesController@obtenerSubcategorias',['id' => '?id'])])!!}
          				</div>
          				<div class="col-md-12 col-lg-6">
              				{{Form::label('fk_id_subcategoria','Subategoría')}}
                			{!! Form::select('fk_id_subcategoria',[],null,['id'=>'fk_id_subcategoria','class'=>'form-control','disabled'=>'disabled','data-url' => companyAction('Soporte\SolicitudesController@obtenerAcciones',['id' => '?id'])]) !!}
          				</div>
          			</div>
          			<div class="form-group row">
          				<div class="col-md-12 col-lg-6">
              				{{Form::label('fk_id_accion','Acción')}}
	            			{!! Form::select('fk_id_accion',[],null,['id'=>'fk_id_accion','class'=>'form-control','disabled'=>'disabled']) !!}
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
    
                <div class="modal-footer">
                    {!! Form::button('Enviar',['class'=>'btn btn-primary','type'=>'submit']) !!}
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
      		{!! Form::close() !!}
        </div>
    </div>
</div><!--/Modal de ayuda-->