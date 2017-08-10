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
            <a href="{{ companyRoute('Soporte\SolicitudesController@index') }}" class="waves-effect waves-light btn-flat white-text">Ver mis tickets/solicitudes</a>
        </div>
    </li>
    <li><a href="#!">Proceso NAUS1234</a></li>
    <li><a href="#!">Proceso NAUS1234</a></li>
    <li><a href="#!">Proceso NAUS1234</a></li>
    <li><a href="#!">Proceso NAUS1234</a></li>
</ul>

<form action="{{ companyRoute('Soporte\SolicitudesController@store') }}" method="post" class="row" enctype="multipart/form-data">
    {{ csrf_field() }}
    {{ method_field('POST') }}
    <div id="ticketHelp" class="modal modal-fixed-footer">
        <div class="modal-content">
            <h4>Nuevo Ticket:</h4>
                <div class="input-field col s12">
                    <input type="text" id="asunto" name="asunto" class="validate" value="{{old('asunto')}}">
                    <label for="asunto">Asunto</label>
                </div>
            <div class="row">
                <div class="input-field col s12">
                    <p class="col s6">
                        <input name="groupWho" type="radio" id="forMe1" onclick="activar_empleado()" checked
                               data-url="{{companyRoute('RecursosHumanos\EmpleadosController@obtenerEmpleado')}}"/>
                        <label for="forMe1">El ticket es para mí</label>
                    </p>
                    <p class="col s6">
                        <input name="groupWho" type="radio" id="otherUser" onchange="activar_empleado()"/>
                        <label for="otherUser">Para otra persona</label>
                    </p>
                </div>
                <div class="input-field col s12">
                    <input type="text" id="empleado_solicitud" class="autocomplete_empleado"
                           data-url="{{companyRoute('RecursosHumanos\EmpleadosController@obtenerEmpleados')}}" autocomplete="off">
                    <label for="empleado_solicitud">Usuario</label>
                    <input type="hidden" id="nombre_solicitante" name="nombre_solicitante" value="">
                </div>
                <div class="input-field col s12">
                    <input type="text" id="sucursal" class="autocomplete_sucursal"
                           data-url="{{companyRoute('Administracion\SucursalesController@obtenerSucursales')}}" autocomplete="off">
                    <label for="sucursal">Sucursal</label>
                    <input type="hidden" id="fk_id_sucursal" name="fk_id_sucursal" value="">
                </div>
                <div class="input-field col s4">
                    <select name="fk_id_categoria" id="fk_id_categoria">
                        <option selected disabled>Selecciona una categoría</option>
                        @foreach($categories_tickets as $category_ticket)
                            <option value="{{$category_ticket->id_categoria}}"
                                    data-url="{{companyRoute('Soporte\SolicitudesController@obtenerSubcategorias'
                                    ,['id' => $category_ticket->id_categoria])}}">
                                {{$category_ticket->categoria}}
                            </option>
                        @endforeach
                    </select>
                    <label for="fk_id_categoria">Categoría</label>
                </div>
                <div class="input-field col s4">
                    <select name="fk_id_subcategoria" id="fk_id_subcategoria" disabled>
                    </select>
                    <label>Subcategoría</label>
                </div>
                <div class="input-field col s4">
                    <select name="fk_id_accion" id="fk_id_accion" disabled>
                    </select>
                    <label>Acción</label>
                </div>
                <div class="input-field col s12">
                    <textarea id="descripcion" name="descripcion" class="materialize-textarea"></textarea>
                    <label for="descripcion">Descripción</label>
                </div>
                <div class="file-field input-field col s12">
                    <div class="btn">
                        <span><i class="material-icons">file_upload</i>Anexar pruebas</span>
                        <input type="file" name="archivo[]" id="archivo" multiple>
                    </div>
                    <div class="file-path-wrapper">
                        <input class="file-path validate" type="text" placeholder="Upload one or more files">
                    </div>
                </div>
                <div class="input-field col s12">
                    <p>Prioridad:</p>
                    @foreach($priorities_tickets as $priority_ticket)
                        <p class="col s4">
                            <input name="fk_id_prioridad" type="radio" value="{{$priority_ticket->id_prioridad}}"
                                   id="prioridad{{$priority_ticket->id_prioridad}}"/>
                            <label for="prioridad{{$priority_ticket->id_prioridad}}">{{$priority_ticket->prioridad}}</label>
                        </p>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="modal-action waves-effect waves-light btn orange">Aceptar</button>
            {{--<button class="modal-action modal-close waves-effect teal-text btn-flat">Cancelar</button>--}}
        </div>
    </div><!--/Modal de ayuda-->
</form>
