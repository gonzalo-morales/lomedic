@extends(smart())
@section('content-width', 's12')

@section('form-content')
    {{ Form::setModel($data) }}
    {{-- Campos --}}
    @if(!Route::currentRouteNamed(currentRouteName('show')))
    {{--  Modal para cambiar de tab 1 --}}
    <div id="modalTabs" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cambio de opción</h5>
                </div>
                <div class="modal-body">
                    Al parecer cuentas con datos en tu tabla. Recuerda que al cambiar la <b>opción</b> perderás toda la información y avances que tengas en este momento
                </div>
                <div class="modal-footer">
                    <button id="cancelarTab" type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
                    <button id="confirmarTab" type="button" class="btn btn-danger">Borrar y cambiar de opción</button>
                </div>
            </div>
        </div>
    </div>
    {{--  Modal para cambiar pedido  --}}
    <div id="confirmacionpedido" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cambio de pedido</h5>
                </div>
                <div class="modal-body">
                    Recuerda que al cambiar la <b>pedido</b> perderás toda la información y avances que tengas en este momento
                </div>
                <div class="modal-footer">
                    <button id="cancelarcambiopedido" type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
                    <button id="confirmarpedido" type="button" class="btn btn-danger">Borrar y cambiar pedido</button>
                </div>
            </div>
        </div>
    </div>
    @endif <!--Aquí dejamos de mostrar los modales al ver-->

    <div class="row">
        <div class="col-sm-6 col-lg-3">
            <div class="form-group">
                {{ Form::cSelectWithDisabled('Cliente', 'fk_id_socio_negocio', $clientes ?? [], [
                    'class' => !Route::currentRouteNamed(currentRouteName('show')) ? 'select2' : '',
                ]) }}
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="form-group">
                {{ Form::cSelectWithDisabled('Proyecto', 'fk_id_proyecto', $proyectos ?? [], [
                    'class' => !Route::currentRouteNamed(currentRouteName('show')) ? 'select2' : '',
                ]) }}
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="form-group">
                {{ Form::cSelectWithDisabled('Sucursal de entrega', 'fk_id_direccion_entrega', $sucursales_entrega ?? [], [
                    'class' => !Route::currentRouteNamed(currentRouteName('show')) ? 'select2' : '',
                ]) }}
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="form-group">
                {{ Form::cText('Fecha de entrega', 'fecha_entrega') }}
            </div>
        </div>
        @if (!Route::currentRouteNamed(currentRouteName('show')))
        <div class="col-sm-12">
            <ul class="nav nav-tabs btn-group justify-content-center border-0 mb-3" id="pills-tab" role="tablist" data-tabs="tabs">
                <li>
                    <a id="producto" class="btn m-0 tabs-bs btn-info active" data-toggle="tab" href="#scanner" role="tab" aria-controls="scanner" aria-selected="true">
                        <i class="material-icons align-middle">archive</i> Solicitud de salida por producto
                    </a>
                </li>
                <li>
                    <a id="pedido" class="btn m-0 tabs-bs btn-info" data-toggle="tab" href="#manual" role="tab" aria-controls="manual" aria-selected="false">
                        <i class="material-icons align-middle">keyboard</i> Solicitud de salida mediante pedido
                    </a>
                </li>
            </ul>
        </div>
        @endif <!--Aquí dejamos de mostrar los tabs al ver-->
    </div>

    <div class="row">
        <div class="col-md-12 col-sm-12 mb-3">
        <div class="tab-content">

            @if (!Route::currentRouteNamed(currentRouteName('show')))
            <div class="tab-pane active" id="scanner" role="tabpanel">
                <div id="app" class="card z-depth-1-half" data-api-endpoint="{{ companyRoute('api.index', ['entity' => '#ENTITY#'], false) }}">
                    @if (!Route::currentRouteNamed(currentRouteName('show')))
                    <div class="card-header">
                        <h4>Productos</h4>
                        <p>Agrega los productos que requieras.</p>
                        <div class="row">
                            <div class="col-sm-6 col-lg-3">
                                <div class="form-group" v-cloak>
                                    {{ Form::cSelectWithDisabled('SKU', '', $skus ?? [], [
                                        'v-select2' => 'nuffer.id_sku',
                                        'v-model.number' => 'nuffer.id_sku',
                                        'v-on:change' => 'onChangeSKU',
                                        'v-validate' => '"required|not_in:0"',
                                        'v-bind:class' => '{"is-invalid": errors.has("header.id_sku")}',
                                        'data-vv-as' => 'SKU',
                                        'data-vv-name' => 'id_sku',
                                        'data-vv-scope' => 'header',
                                    ]) }}
                                    <span v-show="errors.has('header.id_sku')" class="help-block help-block-error small">@{{ errors.first('header.id_sku') }}</span>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="form-group" v-cloak>
                                    {{ Form::cSelectWithDisabled('UPC', '', [], [
                                        'ref' => 'id_upc',
                                        'v-select2' => 'nuffer.id_upc',
                                        'v-model.number' => 'nuffer.id_upc',
                                        'v-on:change' => 'onChangeUPC',
                                        'v-validate' => '"required|not_in:0"',
                                        'v-bind:class' => '{"is-invalid": errors.has("header.id_upc")}',
                                        'data-vv-as' => 'UPC',
                                        'data-vv-name' => 'id_upc',
                                        'data-vv-scope' => 'header',
                                    ]) }}
                                    <span v-show="errors.has('header.id_upc')" class="help-block help-block-error small">@{{ errors.first('header.id_upc') }}</span>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="form-group" v-cloak>
                                    {{ Form::cNumber('Cantidad', '', [
                                        'min' => 0,
                                        'v-model.number' => 'nuffer.cantidad',
                                        'v-bind:class' => '{"is-invalid": errors.has("header.cantidad")}',
                                        'v-validate' => '"required|min_value:1"',
                                        'data-vv-as' => 'Cantidad',
                                        'data-vv-name' => 'cantidad',
                                        'data-vv-scope' => 'header',
                                    ]) }}
                                    <span v-show="errors.has('header.cantidad')" class="help-block help-block-error small">@{{ errors.first('header.cantidad') }}</span>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="form-group" v-cloak>
                                    {{ Form::cSelectWithDisabled('Almacén de salida', '', $almacenes ?? [], [
                                        'v-select2' => 'nuffer.id_almacen',
                                        'v-model.number' => 'nuffer.id_almacen',
                                        'v-on:change' => 'onChangeAlmacen',
                                        'v-validate' => '"required|not_in:0"',
                                        'v-bind:class' => '{"is-invalid": errors.has("header.id_almacen")}',
                                        'data-vv-as' => 'Almacén de salida',
                                        'data-vv-name' => 'id_almacen',
                                        'data-vv-scope' => 'header',
                                    ]) }}
                                    <span v-show="errors.has('header.id_almacen')" class="help-block help-block-error small">@{{ errors.first('header.id_almacen') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 text-center my-3">
                        <div class="sep">
                            <div class="sepBtn">
                                <button v-on:click="append" style="width: 4em; height:4em; border-radius:50%;" class="btn btn-primary btn-large" data-position="bottom" data-delay="50" data-toggle="Agregar" title="Agregar" type="button"><i class="material-icons">add</i></button>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="card-body">
                        <table class="table table-hover table-responsive-sm table-striped" style="table-layout: fixed">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>SKU</th>
                                    <th>UPC</th>
                                    <th>Marca</th>
                                    <th>Descripción</th>
                                    <th>Cantidad</th>
                                    <th>Almacén de salida</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tableBodyProductos" v-if="upcs.length" v-cloak>
                                <tr v-for="upc,index in upcs" v-bind:class="{'table-dark':upc.eliminar}" v-bind:data-index.prop.camel="index">
                                    <th scope="row">
                                        <span v-text="index + 1"></span>
                                        <input type="hidden" v-bind:name="'relations[has][detalle]['+index+'][fk_id_sku]'" v-bind:value="upc.id_sku">
                                        <input type="hidden" v-bind:name="'relations[has][detalle]['+index+'][fk_id_upc]'" v-bind:value="upc.id_upc">
                                        <input type="hidden" v-bind:name="'relations[has][detalle]['+index+'][cantidad]'" v-bind:value="upc.cantidad">
                                        <input type="hidden" v-bind:name="'relations[has][detalle]['+index+'][fk_id_almacen]'" v-bind:value="upc.id_almacen">
                                        <input type="hidden" v-bind:name="'relations[has][detalle]['+index+'][eliminar]'" v-bind:value="upc.eliminar">
                                    </th>
                                    <td>
                                        <span v-text="upc.sku"></span>
                                    </td>
                                    <td>
                                        <span v-text="upc.upc"></span>
                                    </td>
                                    <td>
                                        <span v-text="upc.marca"></span>
                                    </td>
                                    <td>
                                        <span v-text="upc.descripcion"></span>
                                    </td>
                                    <td>
                                        <span v-text="upc.cantidad"></span>
                                    </td>
                                    <td>
                                        <span v-text="upc.almacen"></span> <br>
                                        <small><span v-text="upc.sucursal"></span></small>
                                    </td>
        
                                    <td style="width: 1px !important;position: relative;">
                                        @if (!Route::currentRouteNamed(currentRouteName('show')))
                                        <a href="#" v-on:click.prevent="removeOrUndo(index)">
                                            <i class="material-icons align-middle" v-text="upc.eliminar ? 'undo' : 'delete'"></i>
                                            <span v-text="upc.eliminar ? 'Deshacer' : 'Eliminar'"></span>
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                            <tbody v-else>
                                <tr class="text-center">
                                    <td colspan="8">Agrega uno o más Productos.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>{{-- /App --}}
            </div>
            @endif
            {{-- Tap para solicitud de salida mediante surtido --}}

            <div class="tab-pane" id="manual" role="tabpanel">
                <div class="card z-depth-1-half">
                    @if (!Route::currentRouteNamed(currentRouteName('show')))
                    <div class="card-header">
                        <h4>Pedido</h4>
                        <p>Selecciona el pedido para después seleccionar el almacenista que realizará dicho surtido</p>
                        <div class="row justify-content-center">
                            <div class="col-sm-7 col-12">
                                <div class="form-group">
                                    {{ Form::cSelect('* Pedidos','fk_id_pedido', $pedidos ?? [],[
                                        'data-url' => companyAction('HomeController@index').'/ventas.pedidos/api',
                                        'style' => 'width:100%;',
                                        'class' => !Route::currentRouteNamed(currentRouteName('show')) ? 'select2 fk_id_pedido' : 'fk_id_pedido',
                                        Route::currentRouteNamed(currentRouteName('edit')) && $data['id_documento'] > 0 ? 'disabled' : ''
                                      ]) }}
                                </div>
                            </div>
                            <div class="col-sm-5 col-12">
                                    <div class="form-group">
                                        {{ Form::cSelect('* Almacén','fk_id_almacen', $almacenes ?? [],[
                                            'style' => 'width:100%;',
                                            'class' => !Route::currentRouteNamed(currentRouteName('show')) ? 'select2 fk_id_almacen' : 'fk_id_almacen',
                                            Route::currentRouteNamed(currentRouteName('edit')) && $data['id_documento'] > 0 ? 'disabled' : ''
                                          ]) }}
                                    </div>
                                </div>
                            <div style="display:none;" id="almacenistas">
                                {{ Form::cSelectWithDisabled(null,'relations[has][detalle][$row_id][fk_id_empleado]', $empleados ?? [],[
                                  'class'=>'almacenistas',
                                ]) }}
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="card-body">
                        <table id="tableSolicitudes" class="table table-hover table-responsive-sm table-striped" style="table-layout: fixed">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>SKU</th>
                                    <th>UPC</th>
                                    <th>Marca</th>
                                    <th>Descripción</th>
                                    <th>Cantidad total</th>
                                    <th>Almacenista</th>
                                    <th>Cantidad solicitada a surtir</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tableBodySolicitudes">
                                @if(Route::currentRouteNamed(currentRouteName('edit')))
                                    @foreach($data->detalle->where('eliminar',0) as $row => $detalle)
                                        <tr>
                                            <th>
                                                {{ $detalle->sku->sku }}
                                            </th>
                                            <td>
                                                {{$detalle->upc->upc}}
                                            </td>
                                            <td>
                                                {{$detalle->upc->marca}}
                                            </td>
                                            <td>
                                                {{$detalle->upc->descripcion}}
                                            </td>
                                            <td>
                                                {{$detalle->cantidad}}
                                            </td>
                                            <td>
                                                {{$detalle->almacen->almacen}}
                                            </td>
                                            <td>
                                                {{$detalle->pedidos->no_pedido}}
                                            </td>
                                            <td>
                                                {{--  {{dump($detalle->empleados)}}  --}}
                                                {{$detalle->empleados->nombre.' '.$detalle->empleados->apellido_paterno.' '.$detalle->empleados->apellido_materno}}
                                            </td>
                                            <td>
                                                {{$detalle->cantidad_solicitada_salida}}
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>{{-- /tab surtido--}}
            </div><!--/tabs-->
        </div>
        </div>
    </div>

    <!--Vistas para "View"-->
    @if (Route::currentRouteNamed(currentRouteName('show')) && !isset($data->detalle[0]['fk_id_empleado']))
    <div id="app" class="card z-depth-1-half" data-api-endpoint="{{ companyRoute('api.index', ['entity' => '#ENTITY#'], false) }}">
        <div class="card-body">
            <table class="table table-hover table-responsive-sm table-striped" style="table-layout: fixed">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>SKU</th>
                        <th>UPC</th>
                        <th>Marca</th>
                        <th>Descripción</th>
                        <th>Cantidad</th>
                        <th>Almacén de salida</th>
                    </tr>
                </thead>
                <tbody id="tableBodyProductos" v-if="upcs.length" v-cloak>
                    <tr v-for="upc,index in upcs" v-bind:class="{'table-dark':upc.eliminar}" v-bind:data-index.prop.camel="index">
                        <th scope="row">
                            <span v-text="index + 1"></span>
                            <input type="hidden" v-bind:name="'relations[has][detalle]['+index+'][fk_id_sku]'" v-bind:value="upc.id_sku">
                            <input type="hidden" v-bind:name="'relations[has][detalle]['+index+'][fk_id_upc]'" v-bind:value="upc.id_upc">
                            <input type="hidden" v-bind:name="'relations[has][detalle]['+index+'][cantidad]'" v-bind:value="upc.cantidad">
                            <input type="hidden" v-bind:name="'relations[has][detalle]['+index+'][fk_id_almacen]'" v-bind:value="upc.id_almacen">
                            <input type="hidden" v-bind:name="'relations[has][detalle]['+index+'][eliminar]'" v-bind:value="upc.eliminar">
                        </th>
                        <td>
                            <span v-text="upc.sku"></span>
                        </td>
                        <td>
                            <span v-text="upc.upc"></span>
                        </td>
                        <td>
                            <span v-text="upc.marca"></span>
                        </td>
                        <td>
                            <span v-text="upc.descripcion"></span>
                        </td>
                        <td>
                            <span v-text="upc.cantidad"></span>
                        </td>
                        <td>
                            <span v-text="upc.almacen"></span> <br>
                            <small><span v-text="upc.sucursal"></span></small>
                        </td>

                        <td style="width: 1px !important;position: relative;">
                            @if (!Route::currentRouteNamed(currentRouteName('show')))
                            <a href="#" v-on:click.prevent="removeOrUndo(index)">
                                <i class="material-icons align-middle" v-text="upc.eliminar ? 'undo' : 'delete'"></i>
                                <span v-text="upc.eliminar ? 'Deshacer' : 'Eliminar'"></span>
                            </a>
                            @endif
                        </td>
                    </tr>
                </tbody>
                <tbody v-else>
                    <tr class="text-center">
                        <td colspan="8">Agrega uno o más Productos.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div><!--Aquí dejamos de el tab del módulo de Octavio en view-->
    @endif
    @if (Route::currentRouteNamed(currentRouteName('show')) && isset($data->detalle[0]['fk_id_empleado']))
    <div class="card z-depth-1-half">
        <div class="card-body">
            <table id="tableSolicitudes" class="table table-hover table-responsive-sm table-striped" style="table-layout: fixed">
                <thead>
                    <tr>
                        <th>SKU</th>
                        <th>UPC</th>
                        <th>Marca</th>
                        <th>Descripción</th>
                        <th>Cantidad total</th>
                        <th>Almacen</th>
                        <th>Pedido</th>
                        <th>Almacenista</th>
                        <th>Cantidad solicitada a surtir</th>
                        <th>Falta surtir</th>
                    </tr>
                </thead>
                <tbody id="tableBodySolicitudes">

                    @if(Route::currentRouteNamed(currentRouteName('show')))
                        @foreach($data->detalle->where('eliminar',0) as $row => $detalle)
                            <tr>
                                <th>
                                    {{ $detalle->sku->sku }}
                                </th>
                                <td>
                                    {{$detalle->upc->upc}}
                                </td>
                                <td>
                                    {{$detalle->upc->marca}}
                                </td>
                                <td>
                                    {{$detalle->upc->descripcion}}
                                </td>
                                <td>
                                    {{$detalle->cantidad}}
                                </td>
                                <td>
                                    {{$detalle->almacen->almacen}}
                                </td>
                                <td>
                                    {{$detalle->pedidos->no_pedido}}
                                </td>
                                <td>
                                    {{--  {{dump($detalle->empleados)}}  --}}
                                    {{$detalle->empleados->nombre.' '.$detalle->empleados->apellido_paterno.' '.$detalle->empleados->apellido_materno}}
                                </td>
                                <td>
                                    {{$detalle->cantidad_solicitada_salida}}
                                </td>
                                <td>
                                    {{$detalle->falta_surtir}}
                                </td>
                            </tr>
                        @endforeach
                    @endif

                </tbody>
            </table>
        </div>
    </div>{{-- /tab surtido--}}

    @endif

@endsection

@section('header-bottom')
    @parent
    <script src="{{ asset('vendor/vue/vue.js') }}"></script>
    <script src="{{ asset('js/solicitud_salida_con_pedido.js') }}"></script>
    <script type="text/javascript">
        var api_pedido = '{{$api_pedidos_detalle ?? ''}}'
        /*$(document).ready(function(){
            if({{Route::currentRouteNamed(currentRouteName('show'))}}){
                $(".nav-tabs li").click(function (e) {
                    $(this).each(function () {
                        $('.nav-tabs li').children().removeClass('active');
                    });
                    $('.tab-pane').removeClass('active');
                    $(e.currentTarget).children().addClass('active');
                    var tab = $(this).children().prop('href');
                    tab = tab.split('#');
                    $('#' + tab[1]).addClass('active');
                });
            }
        })*/
        $(function(){
        if ($('#app').length) {
        
            function updateSelect2 (el, binding) {
                $(el).off().val(binding.value).select2().on('select2:select', function(e){
                    el.dispatchEvent(new Event('change', { target: e.target }));
                });
            }
            Vue.directive('select2', {inserted: updateSelect2, componentUpdated: updateSelect2});
        
            Vue.use(VeeValidate, {
                locale: 'es',
            });
            window.vapp = new Vue({
                el: '#app',
                data: {
                    buffer: {
                        id_detalle: null,
                        id_sku: 0,
                        sku: '',
                        id_upc: 0,
                        upc: '',
                        cantidad: 0,
                        id_almacen: 0,
                        almacen: '',
                        sucursal: '',
                        marca: '',
                        descripcion: '',
                        eliminar: false,
                    },
                    nuffer: {},
                    upcs: @json($upcs ?? []),
                },
                methods: {
                    /* Limpiamos notificacion de errores */
                    clearErrorScope: function(scope) {
                        this.$nextTick(function() {
                            this.errors.clear(scope)
                        }.bind(this))
                    },
                    onChangeSKU: function(e) {
                        var vm = this;
                        /* Reset */
                        this.nuffer.id_upc = 0;
                        /* */
                        $(this.$refs.id_upc).html('<option value="0" selected disabled>Obteniendo ...</option>')
                        $.get(this.$root.$el.dataset.apiEndpoint.replace('#ENTITY#', 'inventarios.productos'), {
                            param_js: '{{$api_sku ?? ''}}', $id_sku: e.target.value
                            // select: ['id_sku','sku','descripcion'],
                            // conditions: [{'where': ['id_sku', e.target.value]}],
                            // with: ['upcs:id_upc,descripcion,marca,upc'],
                        }, function(response){
                            var options = [], i;
                            $(this).empty();
                            /* Si hay resultados */
                            if (response.length > 0) {
                                vm.nuffer.sku = response[0].sku
                                if (response[0].upcs.length > 0) {
                                    options.push('<option value="0" selected disabled>...</option>');
                                    for (i in response[0].upcs) {
                                        console.log( response[0].upcs[i] )
                                        options.push('<option data-upc="'+response[0].upcs[i].upc+'" data-marca="'+response[0].upcs[i].marca+'" data-descripcion="'+response[0].upcs[i].descripcion+'" value="' + response[0].upcs[i].id_upc + '">' + response[0].upcs[i].upc + ' - ' + response[0].upcs[i].descripcion + '</option>');
                                    }
                                } else {
                                    options.push('<option value="0" selected disabled>Sin resultados ...</option>');
                                }
                            } else {
                                vm.nuffer.sku = '';
                            }
                            $(this).append(options.join(''));
                            vm.$forceUpdate();
                        }.bind(this.$refs.id_upc))
                    },
                    onChangeUPC: function(e) {
                        var data = e.target.options[e.target.selectedIndex].dataset;
                        this.nuffer.marca = data.marca;
                        this.nuffer.descripcion = data.descripcion;
                        this.nuffer.upc = data.upc;
                    },
                    onChangeAlmacen: function(e) {
                        var data = e.target.options[e.target.selectedIndex].innerHTML.split(' - ');
                        this.nuffer.almacen = data[0];
                        this.nuffer.sucursal = data[1];
                    },
                    append: function(e) {
                        /* Validamos campos */
                        this.$validator.validateAll('header').then(function(isValid){
                            if (isValid) {
                                /* upc --enviamos--> upcs */
                                this.upcs.push(JSON.parse(JSON.stringify($.extend(true, {}, this.nuffer))));
                                /* Limpiamos campos */
                                this.nuffer = $.extend(true, {}, this.buffer);
                                this.clearErrorScope('header');
                            }
                        }.bind(this))
                    },
                    removeOrUndo: function(index) {
                        if (!this.upcs[index].id_detalle) {
                            this.upcs.splice(index ,1); return;
                        }
                        this.upcs[index].eliminar = !this.upcs[index].eliminar;
                    }
                },
                beforeMount: function() {
                    var vm = this;
        
                    /* First */
                    this.nuffer = $.extend(true, {}, this.buffer);
        
                    $('#fk_id_socio_negocio').on('change', function(){
        
                        var target = $('#fk_id_proyecto');
                        $(target).parent().prepend('<div class="w-100 h-100 text-center text-white align-middle loadingData">Cargando datos... <i class="material-icons align-middle loading">cached</i></div>');
                        $.get(vm.$root.$el.dataset.apiEndpoint.replace('#ENTITY#', 'proyectos.proyectos'), {
                            param_js: '{{$api_proyectos ?? ''}}', $fk_id_cliente: this.value
                            // conditions: [{'where':['fk_id_cliente', this.value]}],
                            // only: ['id_proyecto', 'proyecto']
                        }, function(response){
                            var options = [], i;
                            $(this).empty();
                            /* Si hay resultados */
                            if (response.length > 0) {
                                options.push('<option value="0" selected disabled>Selecciona ...</option>');
                                for (i in response) {
                                    options.push('<option value="' + response[i].id_proyecto + '">' + response[i].proyecto + '</option>');
                                }
                            } else {
                                options.push('<option value="0" selected disabled>Sin resultados ...</option>');
                            }
                            $(this).append(options.join());
                            $('.loadingData', $(this).parent()).remove();
                        }.bind(target))
        
                        var target = $('#fk_id_direccion_entrega');
                        $(target).parent().prepend('<div class="w-100 h-100 text-center text-white align-middle loadingData">Cargando datos... <i class="material-icons align-middle loading">cached</i></div>');
                        $.get(vm.$root.$el.dataset.apiEndpoint.replace('#ENTITY#', 'sociosnegocio.direccionessociosnegocio'), {
                            param_js: '{{$api_direcciones ?? ''}}', $fk_id_socio_negocio: this.value
                            // conditions: [{'where':['fk_id_socio_negocio', this.value]},{'where':['fk_id_tipo_direccion', 2]}],
                            // only: ['id_direccion', 'direccion_concat']
                        }, function(response){
                            var options = [], i;
                            $(this).empty();
                            /* Si hay resultados */
                            if (response.length > 0) {
                                options.push('<option value="0" selected disabled>Selecciona ...</option>');
                                for (i in response) {
                                    options.push('<option value="' + response[i].id_direccion + '">' + response[i].direccion_concat + '</option>');
                                }
                            } else {
                                options.push(
                                    '<option value="0" selected disabled>Sin resultados ...</option>'
                                );
                            }
                            $(this).append(options.join());
                            $('.loadingData', $(this).parent()).remove();
                        }.bind(target))
                    });
        
                    $('#fecha_entrega').pickadate({
                        selectMonths: true, /* Creates a dropdown to control month */
                        selectYears: 3, /* Creates a dropdown of 3 years to control year */
                        min: true,
                        format: 'yyyy-mm-dd',
                    })
        
                    $('#form-model').on('submit', function(e) {
                        e.preventDefault();
                        var form = this;

                        if($('#producto').hasClass('active')){
                            /* Si detalle y formulario estan validados */
                            if ($(form).validate().form()) {
                                form.submit()
                            } else {
                                console.log('nada aun ...')
                            }
                        } else {
                            //Validamos la otra sección
                            $.validator.addMethod('minStrict', function (value, element, param) {
                                return value > param;
                            },'El numero debe ser mayor a {0}');
                            $.validator.addMethod('cRequerido',$.validator.methods.required,'Este campo es requerido');
                            $.validator.addMethod('cDigits',$.validator.methods.digits,'El campo debe ser entero');
                            $.validator.addClassRules('fk_id_pedido',{
                                cRequerido:true,
                            });
                            $.validator.addClassRules('fk_id_almacen',{
                                cRequerido:true,
                            });
                            $.validator.addClassRules('cantidad_total',{
                                cRequerido:true,
                                cDigits:true,
                                minStrict:0
                            });
                            $.validator.addClassRules('cantidad_solicitada',{
                                cRequerido:true,
                                cDigits:true,
                                minStrict:0
                            });
                            $.validator.addClassRules('almacenistas',{
                                cRequerido:true,
                            });
                        
                            if(!$('#form-model').valid() || !validateCantidad()){
                                e.preventDefault();
                                $('.fk_id_pedido').rules('remove');
                                $('.fk_id_almacen').rules('remove');
                                $('.cantidad_solicitada').rules('remove');
                                $('.cantidad_total').rules('remove');
                                $('.almacenistas').rules('remove');
                                $.toaster({
                                    priority: 'danger', title: '¡Error!', message: 'Hay campos que requieren de tu atencion',settings: {'timeout': 3000, 'toaster': {'css': {'top': '5em'}}}
                                });
                            }else {
                                form.submit();
                            }

                        }
                        
                    });
                },
            }); // aquí termina la App
        }
        });
    </script>
@endsection