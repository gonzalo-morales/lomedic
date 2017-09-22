@section('header-bottom')
<script type="text/javascript" src="{{ asset('js/ticket.js') }}"></script>
@endsection

<!-- Sidebar Holder -->
<nav id="rigth-sidebar" class="bg-white">
    <div class="sidebar-header">
        <img class="background" src="{{asset('img/helpBG2.png')}}">
    	<div class="h-content">
        <span><i class="material-icons medium white-text">live_help</i></span>
        <a class="white-text" href="#"><span class="name">Sección de ayuda</span></a>
        <a href="#ticketHelp" class="waves-effect waves-light btn-flat white-text">Crear ticket/solicitud</a>
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


<div id="ticketHelp" class="modal modal-fixed-footer">
    {!! Form::model(null,['url'=>companyAction('Soporte\SolicitudesController@store'), 'class'=>"col s12", 'enctype'=>"multipart/form-data",'method'=>'post']) !!}
    <div class="modal-content">
        <h4>Nuevo Ticket:</h4>
            <div class="input-field col s12">
                {!! Form::text('asunto',old('asunto'),['class'=>'validate','id'=>'asunto']) !!}
                {{Form::label('asunto','Asunto')}}
            </div>
        <div class="row">
            <div class="input-field col s12">
                <p class="col s6">
                    {!! Form::radio('groupWho','',true,['id'=>'forMe1','class'=>'validate', 'onclick'=>'activar_empleado()', 'data-url'=>companyAction('RecursosHumanos\EmpleadosController@obtenerEmpleado')]) !!}
                    {{ Form::label('forMe1', 'El ticket es para mí') }}
                </p>
                <p class="col s6">
                    {{Form::radio('groupWho','',true,['id'=>'otherUser','class'=>'validate', 'onchange'=>'activar_empleado()'])}}
                    {{ Form::label('otherUser', 'El ticket es para otra persona') }}
                </p>
            </div>
            <div class="input-field col s12">
                {!! Form::text('empleado_solicitud',null,['id'=>'empleado_solicitud','','autocomplete'=>'off','data-url'=>companyAction('RecursosHumanos\EmpleadosController@obtenerEmpleados')])!!}
                {{Form::label('empleado_solicitud','Empleado que realizó la solicitud')}}
                {{Form::hidden('nombre_solicitante',null,['id'=>'nombre_solicitante','data-url'=>companyAction('Administracion\SucursalesController@sucursalesEmpleado',['id'=>'?id'])])}}
            </div>
            <div class="input-field col s12">
                {!! Form::select('fk_id_sucursal',[],null,['id'=>'fk_id_sucursal', 'disabled' => 'true'])!!}
                {{Form::label('fk_id_sucursal','Sucursal')}}
            </div>
            <div class="input-field col s4">
                {!! Form::select('fk_id_categoria',$categories_tickets,null,['id'=>'fk_id_categoria', 'data-url' => companyAction('Soporte\SolicitudesController@obtenerSubcategorias',['id' => '?id'])])!!}
                {{Form::label('fk_id_categoria','Categoría')}}
            </div>
            <div class="input-field col s4">
                {!! Form::select('fk_id_subcategoria',[],null,['id'=>'fk_id_subcategoria', 'disabled'=>'disabled','data-url' => companyAction('Soporte\SolicitudesController@obtenerAcciones',['id' => '?id'])]) !!}
                {{Form::label('fk_id_subcategoria','Subategoría')}}
            </div>
            <div class="input-field col s4">
                {!! Form::select('fk_id_accion',[],null,['id'=>'fk_id_accion','disabled'=>'disabled']) !!}
                {{Form::label('fk_id_accion','Acción')}}
            </div>
            <div class="input-field col s12">
                {!! Form::textarea('descripcion',old('descripcion'),['id'=>'descripcion','class'=>'materialize-textarea']) !!}
                {{Form::label('descripción','Descripción')}}
            </div>
            <div class="file-field input-field col s12">
                <div class="btn">
                    <span><i class="material-icons">file_upload</i>Anexar pruebas</span>
                    {!! Form::file('archivo[]',['id'=>'archivo','multiple'=>'multiple']) !!}
                </div>
                <div class="file-path-wrapper">
                    {!! Form::text('',null,['class'=>'file-path validate','placeholder'=>'Selecciona uno o más archivos']) !!}
                </div>
            </div>
            <div class="input-field col s12">
                <p>Prioridad:</p>
                @foreach($priorities_tickets as $priority_ticket)
                    <p class="col s4">
                        {!! Form::radio('fk_id_prioridad',$priority_ticket->id_prioridad,false,['id'=>'prioridad'.$priority_ticket->id_prioridad]) !!}
                        {{ Form::label('prioridad'.$priority_ticket->id_prioridad,$priority_ticket->prioridad) }}
                    </p>
                @endforeach
            </div>
        </div>
    </div>
    <div class="modal-footer">
        {!! Form::button('Enviar',['class'=>'modal-action waves-effect waves-light btn orange','type'=>'submit']) !!}
    </div>
    {!! Form::close() !!}
</div><!--/Modal de ayuda-->

