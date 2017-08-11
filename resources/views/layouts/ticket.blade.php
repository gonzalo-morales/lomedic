
@section('header-bottom')
<script type="text/javascript" src="{{ asset('js/jquery.ui.autocomplete2.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/ticket.js') }}"></script>
@endsection

<ul id="slide-help" class="side-nav">
    <li>
        <div class="user-view center">
            <span><i class="material-icons medium white-text">live_help</i></span>
            <div class="background">
                <img src="{{asset('img/helpBG2.png')}}">
            </div>
            <a class="white-text" href="#"><span class="name">Sección de ayuda</span></a>
            <a href="#ticketHelp" class="waves-effect waves-light btn-flat white-text">Crear ticket/solicitud</a>
            <a href="{{ companyAction('Soporte\SolicitudesController@index') }}" class="waves-effect waves-light btn-flat white-text">Ver mis tickets/solicitudes</a>
        </div>
    </li>
    <li><a href="#!">Proceso NAUS1234</a></li>
    <li><a href="#!">Proceso NAUS1234</a></li>
    <li><a href="#!">Proceso NAUS1234</a></li>
    <li><a href="#!">Proceso NAUS1234</a></li>
</ul>

<form action="{{ companyAction('Soporte\SolicitudesController@store') }}" method="post" class="col s12" enctype="multipart/form-data">
    {{ csrf_field() }}
    {{ method_field('POST') }}
    <div id="ticketHelp" class="modal modal-fixed-footer">
        {!! Form::model(null,['url'=>companyAction('Soporte\SolicitudesController@store'), 'class'=>"col s12", 'enctype'=>"multipart/form-data"]) !!}
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
                    {!! Form::select('fk_id_categoria',[0 => 'Selecciona una categoria',$categories_tickets],null,['id'=>'fk_id_categoria', 'data-url' => companyAction('Soporte\SolicitudesController@obtenerSubcategorias',['id' => '?id'])])!!}
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
            {!! Form::button('Enviar',['class'=>'modal-action waves-effect waves-light btn orange']) !!}
        </div>
        {!! Form::close() !!}
    </div><!--/Modal de ayuda-->
</form>
