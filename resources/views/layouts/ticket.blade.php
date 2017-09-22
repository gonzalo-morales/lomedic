@section('header-bottom')
<!-- <script type="text/javascript" src="{{ asset('js/ticket.js') }}"></script> -->
<script type="text/javascript">
    $(document).ready(function () {
    	$custom-file-text: (
            placeholder: (
                en: "Choose file...",
                es: "Seleccionar archivo..."
            ),
                button-label: (
                en: "Browse",
                es: "Navegar"
            )
    	);
    });
</script>
@endsection

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
            <a class="collapsed" href="#tickets" data-toggle="collapse" aria-expanded="false">
            	<i class="material-icons">list</i>Tickets recientes
            	<i class="material-icons float-right grey-text">expand_more</i>
            </a>
            <ul id="tickets" class="list-unstyled collapse" aria-expanded="false">
                @foreach($ultimos_tickets as $ticket)
                <li>
                	<a href="{{ companyAction('Soporte\SolicitudesController@show', ['id' => $ticket->id_solicitud]) }}" class="waves-effect waves-light btn btn-flat no-padding"><i class="material-icons">note</i>
                        {{$ticket->asunto}}
                    </a>
                </li>
                @endforeach
            </ul>
        </li>
        <li><a href="{{ companyAction('Soporte\SolicitudesController@index') }}"><i class="material-icons">dvr</i>Todos mis tickets</a></li>
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
            <div class="modal-header bg-primary text-white">
            <h5 class="modal-title" id="exampleModalLabel">Nuevo Ticket:</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                	<span aria-hidden="true">&times;</span>
                </button>
          	</div>
          	
          	<div class="modal-body">
          		{!! Form::model(null,['url'=>companyAction('Soporte\SolicitudesController@store'), 'class'=>"col s12", 'enctype'=>"multipart/form-data",'method'=>'post']) !!}
          			<div class="form-group">
          				<div class="form-check form-check-inline">
              				{!! Form::radio('groupWho','',true,['id'=>'forMe1','class'=>'form-control validate', 'onclick'=>'activar_empleado()', 'data-url'=>companyAction('RecursosHumanos\EmpleadosController@obtenerEmpleado')]) !!}
              				{{ Form::label('forMe1', 'El ticket es para mí') }}
						</div>
                        <div class="form-check form-check-inline">
                        	{{Form::radio('groupWho','',true,['id'=>'otherUser','class'=>'form-control validate', 'onchange'=>'activar_empleado()'])}}
          					{{ Form::label('otherUser', 'El ticket es para otra persona') }}
                        </div>
          			</div>
          			<div class="form-group">
          				{{Form::label('empleado_solicitud','Empleado que realizó la solicitud')}}
                        {!! Form::text('empleado_solicitud',null,['id'=>'empleado_solicitud','class'=>'form-control','autocomplete'=>'off','data-url'=>companyAction('RecursosHumanos\EmpleadosController@obtenerEmpleados')])!!}
                        {{Form::hidden('nombre_solicitante',null,['id'=>'nombre_solicitante','data-url'=>companyAction('Administracion\SucursalesController@sucursalesEmpleado',['id'=>'?id'])])}}
          			</div>
          			<div class="form-group">
          				{{Form::label('fk_id_sucursal','Sucursal')}}
                		{!! Form::select('fk_id_sucursal',[],null,['id'=>'fk_id_sucursal','class'=>'form-control','disabled' => 'true'])!!}
          			</div>
          			<div class="form-group">
          				{{Form::label('fk_id_categoria','Categoría')}}
                		{!! Form::select('fk_id_categoria',$categories_tickets,null,['id'=>'fk_id_categoria','class'=>'form-control','data-url' => companyAction('Soporte\SolicitudesController@obtenerSubcategorias',['id' => '?id'])])!!}
          			</div>
          			<div class="form-group">
          				{{Form::label('fk_id_subcategoria','Subategoría')}}
                		{!! Form::select('fk_id_subcategoria',[],null,['id'=>'fk_id_subcategoria','class'=>'form-control','disabled'=>'disabled','data-url' => companyAction('Soporte\SolicitudesController@obtenerAcciones',['id' => '?id'])]) !!}
          			</div>
          			<div class="form-group">
          				{{Form::label('fk_id_accion','Acción')}}
            			{!! Form::select('fk_id_accion',[],null,['id'=>'fk_id_accion','class'=>'form-control','disabled'=>'disabled']) !!}
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
                        <label class="custom-file">
                            {!! Form::file('archivo[]',['id'=>'archivo','class'=>'custom-file-input','multiple'=>'multiple']) !!}
                            <span class="custom-file-control"></span>
                        </label>
          			</div>
          			<div class="form-group">
          				<p>Prioridad:</p>
                        @foreach($priorities_tickets as $priority_ticket)
                            <p class="form-check form-check-inline">
                                {!! Form::radio('fk_id_prioridad',$priority_ticket->id_prioridad,false,['id'=>'prioridad'.$priority_ticket->id_prioridad]) !!}
                                {{ Form::label('prioridad'.$priority_ticket->id_prioridad,$priority_ticket->prioridad) }}
                            </p>
                        @endforeach
          			</div>
          			<div class="form-group">
          				<label class="custom-file">
                          <input type="file" id="file2" class="custom-file-input">
                          <span class="custom-file-control"></span>
                        </label>
          			</div>
          		{!! Form::close() !!}
          	</div>
        </div>

        <div class="modal-footer">
            {!! Form::button('Enviar',['class'=>'modal-action waves-effect waves-light btn orange','type'=>'submit']) !!}
        </div>
    </div>
</div><!--/Modal de ayuda-->

