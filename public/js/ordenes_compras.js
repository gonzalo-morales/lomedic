var a=[];

$(document).ready(function(){
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

    totalOrden();
    if(window.location.href.toString().indexOf('editar') > -1 || window.location.href.toString().indexOf('crear') > -1 || window.location.href.toString().indexOf('solicitudOrden') > -1)
    {
        limpiarCampos();
        initSelects();
        if(window.location.href.toString().indexOf('crear') > -1){
            validateDetail();
        }
        //Por si se selecciona un UPC
        $('#activo_upc').on('change',function () {
            $( this ).parent().nextAll( "select" ).prop( "disabled", !this.checked );
            if( !this.checked ){
                $( this ).parent().nextAll( "select" ).val(0).trigger('change');
            }else{
                if($('#fk_id_sku').val()){
                    var _url = $('#fk_id_upc').data('url').replace('?id',$('#fk_id_sku').val());
                    $( this ).parent().nextAll( "select" ).select2({
                        minimumResultsForSearch: Infinity,
                        ajax:{
                            url: _url,
                            dataType: 'json',
                            processResults: function (data) {
                                return {results: data}
                            },
                            cache:true
                        }
                    })
                }else{
                    $( this ).prop('checked',false);
                    $( this ).parent().nextAll( "select" ).prop( "disabled", !this.checked );
                    $.toaster({priority : 'danger',title : '¡Error!',message : 'Selecciona antes un SKU',
                        settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
                }
            }
        })//Fin UPC

        $('#agregar').on('click',function () {
            var sku = "NULL";
            if($('#fk_id_sku').val() > 0){
                proveedor = $('#fk_id_sku').val();
            }

            var proveedor = "NULL";
            if($('#fk_id_socio_negocio').val() > 0){
                proveedor = $('#fk_id_socio_negocio').val();
            }
            var upc = "NULL";
            if($('#fk_id_upc').val() > 0){
                upc = $('#fk_id_upc').val();
            }
            $.ajax({
                url: $('#fk_id_upc').data('url-tiempo_entrega'),
                data: {
                    'param_js':tiempo_entrega_js,
                    $fk_id_sku:sku,
                    $fk_id_socio_negocio:proveedor,
                    $fk_id_upc:upc
                },
                dataType:'JSON',
                success: function (tiempo_entrega) {
                    agregarProducto(tiempo_entrega);
                }
            });
        });

        $(document).on('submit',function (e) {
            if(!$('#productos tbody tr').length > 1){
                e.preventDefault();
                $.toaster({
                    priority: 'danger', title: '¡Advertencia!', message: 'La orden de compra debe tener al menos un producto',
                    settings: {'timeout': 5000, 'toaster': {'css': {'top': '5em'}}}
                });
            }
        });
    }
    $('#autorizar').click(function(){
        $('#autorizarCondicion').modal('show');
    });

    //Para las autorizaciones de solicitudes de compras
    $('input[type=radio][name=fk_id_estatus]').change(function () {
        if($(this).val() == 4){//Si es autorizada
            $('#observaciones').attr('readonly','readonly');
            $('#observaciones').val("");
        }else{
            $('#observaciones').removeAttr('readonly');
        }
    });
    $('.condicion').click(function () {
        $('#motivo_autorizacion').val($(this).parent().parent().find('td:nth-child(2)').text());
        $('#id_documento').val($(this).parent().parent().find('td input:first').val());
        $('#observaciones').val($(this).parent().parent().find('td input:first').next('input').val());
        if($(this).parent().parent().find('td input:last').val() == 3){
            $('#fk_id_estatus\\ 3').prop('checked',true);
            $('#observaciones').removeAttr('readonly');
        }else if($(this).parent().parent().find('td input:last').val() == 4){
            $('#fk_id_estatus\\ 4').prop('checked',true);
            $('#observaciones').attr('readonly','readonly');
        }
    });

    $('#guardar_autorizacion').click(function (e) {
        if($('input[type=radio][name=fk_id_estatus]:checked').val() == 3 && !$('#observaciones').val()) {
            $.toaster({
                priority: 'danger', title: 'Error', message: 'Por favor escribe un motivo de rechazo',
                settings: {'timeout': 5000, 'toaster': {'css': {'top': '5em'}}}
            });
        }else if(!$('input[type=radio][name=fk_id_estatus]:checked').val()){
            $.toaster({
                priority: 'danger', title: 'Error', message: 'Por favor selecciona si se autoriza o no',
                settings: {'timeout': 5000, 'toaster': {'css': {'top': '5em'}}}
            });
        }else{
            $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
            var autorizar_url = $('#id_documento').data('url').replace('?id',$('#id_documento').val());//id_autorizacion
            $.ajax({
                url:autorizar_url,
                type:'PUT',
                data:{
                    observaciones:$('#observaciones').val(),
                    fk_id_estatus:$('input[type=radio][name=fk_id_estatus]:checked').val()
                },
                success:function (data) {
                    if(data.status == 1){
                        $('#autorizacion').modal('toggle');
                        $.toaster({
                            priority: 'success', title: 'Éxito', message: 'Se ha actualizado la información de la autorización. Recarga la página para ver los cambios.',
                            settings: {'timeout': 5000, 'toaster': {'css': {'top': '5em'}}}
                        });
                    }else{
                        $.toaster({
                            priority: 'danger', title: 'Error', message: 'Ha ocurrido un error',
                            settings: {'timeout': 5000, 'toaster': {'css': {'top': '5em'}}}
                        });
                    }
                }
            });
        }
    });

    $('#reload').click(function (e) {
        e.preventDefault();
        window.location.reload(true);
    });
    //Aquí termina la parte de las autorizaciones

    $('#fk_id_proyecto').select2({
        minimumResultsForSearch: Infinity,
        ajax:{
            url: $('#fk_id_proyecto').data('url'),
            dataType: 'json',
            data: function(){
                var upc = 'NULL';
                if($('#fk_id_upc').val()){
                    upc = $('#fk_id_upc').val();
                }

                var sku = 'NULL';
                if($('#fk_id_sku').val()){
                    sku = $('#fk_id_sku').val();
                }
                return{
                    'param_js':proyectos_js,
                    $fk_id_upc: upc,
                    $fk_id_sku: sku
                }
            },
            cache:true,
            processResults: function (data) {
                if(data.length > 0){
                    return {results: data}
                }else{
                    $.toaster({priority : 'warning',title : '¡Oooops!',message : 'No se encontraron proyectos. Verifica que el SKU y el UPC coincidan con un proyecto',
                        settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}
                    });
                }
            }
        }
    });
});

function initSelects() {
    $("#fk_id_sku").select2({
        minimumInputLength:3,
        theme: "bootstrap",
        ajax:{
            url: $("#fk_id_sku").data('url'),
            delay:500,
            dataType: 'json',
            data: function (params) {
                return {
                    term: params.term,fk_id_socio_negocio:$('#fk_id_socio_negocio').val()};
            },
            processResults: function (data) {
                if(data.length > 0){
                    return {results: data}
                }else{
                    $.toaster({priority : 'warning',title : '¡Oooops!',message : 'No se encontraron productos de este proveedor. Por favor selecciona otro.',
                        settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}
                    });
                }
            }
        }
    });

    $('#fk_id_solicitud').select2({
        minimumResultsForSearch:50,
        theme:'bootstrap'
    });

    totalOrden();

    //Para obtener los IVAS con sus porcentajes y IDs
    $.ajax({
        url: $('#fk_id_impuesto').data('url'),
        dataType:'json',
        success:function (data) {

            $('.idImpuestoRow').each(function (index,select) {
                var id_default = $(select).data('default');
                var data2 = [];
                $.each(data,function (index,option) {
                    var datadefault = false;
                    if(id_default == option.id)
                        datadefault = true;

                    if(option.id != 0){
                        if(datadefault)
                            option.selected = true;
                        data2.push(option);
                    }
                });
                $(select).select2({
                    minimumResultsForSearch:'Infinity',
                    data:data2
                })
            });

            $('#fk_id_impuesto').select2({
                minimumResultsForSearch:'Infinity',
                data:data,
            });
        }
    });

    $('.cantidad_row, .precio_unitario_row, .descuento_row, .idImpuestoRow').on('change keyup',function (event) {//Para cuando se modifica una fila que le pertenece a una solicitud si se está en el create
        var row = $(event.target).closest('tr');
        var cantidad = +$(row).find('.cantidad_row').val();
        var precio = +$(row).find('.precio_unitario_row').val();
        var descuento = +$(row).find('.descuento_row').val();
        $.validator.addMethod('cRequerido',$.validator.methods.required,'Este campo es requerido');
        $.validator.addMethod('precio',function (value,element) {
            return this.optional(element) || /^\d{0,6}(\.\d{0,2})?$/g.test(value);
        },'Verifica la cantidad. Ej. 999999.00');
        $.validator.addMethod("greaterThan", function( value, element, param ) {
            return value > param;
        }, "El campo debe ser mayor a {0}" );
        $.validator.addMethod( "lessThan", function( value, element, param ) {
            return value < param;
        }, "Ingresa un valor menor a precio por cantidad ({0})" );
        $.validator.addMethod("cDigits",$.validator.methods.digits,'Este campo solo acepta enteros');
        $.validator.addClassRules('descuento_row',{
            precio:true,
            greaterThan:-1,
            lessThan: precio * cantidad
        });
        $.validator.addClassRules('precio_unitario_row',{
            cRequerido:true,
            precio:true,
            greaterThan:0
        });
        $.validator.addClassRules('idImpuestoRow',{
            cRequerido:true
        });
        $.validator.addClassRules('cantidad_row',{
           cRequerido:true,
           cDigits:true,
           greaterThan:0
        });
        if($('#form-model').valid()){
            var subtotal = cantidad*precio;
            subtotal = subtotal - descuento;
            var impuesto = ($(row).find('.idImpuestoRow').select2('data')[0].porcentaje/100) * subtotal;
            $(event.target).closest('tr').find('.total_row').val((subtotal).toFixed(2));
            $(event.target).closest('tr').find('.porcentaje').val($(row).find('.idImpuestoRow').select2('data')[0].porcentaje);
            $(event.target).closest('tr').find('.impuesto_row').val(impuesto);
        }
        totalOrden();
    });
}

function agregarProducto(tiempo_entrega) {
    validateDetail();
    if($('#form-model').valid()){
            var i = $('#productos tbody tr').length;
            var row_id = i > 0 ? +$('#productos tbody tr:last').find('.index').val() + 1 : 0;
            var total = totalProducto();
            var impuesto = impuestoProducto();
            var id_proyecto = '';
            var proyecto = "Sin proyecto";
            if($('#fk_id_proyecto').select2('data').length){
                id_proyecto = $('#fk_id_proyecto').select2('data')[0].id;
                proyecto = $('#fk_id_proyecto').select2('data')[0].text;
            }
            var id_upc = '';
            if($('#fk_id_upc').val() > 0){
                id_upc = $('#fk_id_upc').val();
            }

            $('#productos').append(
                '<tr>' +
                    '<td><input type="hidden" name="relations[has][detalle]['+row_id+'][fk_id_documento]"/>N/A<input type="hidden" value="'+tiempo_entrega[0].tiempo_entrega+'" class="tiempo_entrega"><input type="hidden" class="index" value="'+row_id+'"></td>' +
                    '<td><input type="hidden" name="relations[has][detalle]['+row_id+'][fk_id_sku]" value="' + $('#fk_id_sku').select2('data')[0].id + '" />' + $('#fk_id_sku').select2('data')[0].text + '</td>'+
                    '<td><input type="hidden" name="relations[has][detalle]['+row_id+'][fk_id_upc]" value="' + id_upc + '" />' + $('#fk_id_upc').select2('data')[0].text + '</td>'+
                    '<td>'+$('#fk_id_sku').select2('data')[0].descripcion_corta + '</td>'+
                    '<td style="max-width: 500px">'+$('#fk_id_sku').select2('data')[0].descripcion + '</td>'+
                    '<td><input type="hidden" name="relations[has][detalle]['+row_id+'][fk_id_proyecto]" value="' + id_proyecto + '" />'+ proyecto + '</td>'+
                    '<td><input type="hidden" name="relations[has][detalle]['+row_id+'][fecha_necesario]" value="' + $('#fecha_necesario').val() + '" />'+ $('#fecha_necesario').val()+'</td>'+
                    '<td><input type="hidden" name="relations[has][detalle]['+row_id+'][cantidad]" class="cantidad_row" value="' + +$('#cantidad').val() + '" />' + +$('#cantidad').val() + '</td>'+
                    '<td><input type="hidden" name="relations[has][detalle]['+row_id+'][descuento_detalle]" class="descuento_row" value="' + $('#descuento').val() + '" />' + $('#descuento').val() + '</td>'+
                    '<td><input type="hidden" name="relations[has][detalle]['+row_id+'][total_impuesto]" value="'+impuesto+'"><input type="hidden" name="relations[has][detalle]['+row_id+'][fk_id_impuesto]" value="' + $('#fk_id_impuesto').select2('data')[0].id + '" />' + $('#fk_id_impuesto').select2('data')[0].text + '</td>'+'<input type="hidden" class="porcentaje" value="' + $('#fk_id_impuesto').select2('data')[0].porcentaje + '" />'+
                    '<td><input type="hidden" class="precio_unitario_row" name="relations[has][detalle]['+row_id+'][precio_unitario]" value="' + +$('#precio_unitario').val() + '" />'+ +$('#precio_unitario').val()+'</td>' +
                    '<td><input type="text" value="'+ total +'" name="relations[has][detalle]['+row_id+'][total]" class="form-control total_row" readonly><input type="hidden" value="'+tiempo_entrega[0].tiempo_entrega+'" class="tiempo_entrega"></td>'+
                    '<td><button class="btn is-icon text-primary bg-white" type="button" data-delay="50" onclick="borrarFila(this)"> <i class="material-icons">delete</i></button></td>'+
                '</tr>'
            );

            $.toaster({priority : 'success',title : '¡Éxito!',message : 'Producto agregado con éxito',
                settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}
            });
            limpiarCampos();
            totalOrden();
            tiemposentrega();

    }else{
        $.toaster({priority : 'danger',title : '¡Error!',message : 'Hay campos que requieren de tu atención',
            settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
    }
}

function totalProducto() {
    var cantidad = +$('#cantidad').val();
    var precio = +$('#precio_unitario').val();
    var subtotal =cantidad*precio;
    subtotal = subtotal - +$('#descuento').val();
    return (subtotal).toFixed(2);
}

function impuestoProducto() {
    var cantidad = +$('#cantidad').val();
    var precio = +$('#precio_unitario').val();
    var subtotal =cantidad*precio;
    subtotal = subtotal - +$('#descuento').val();
    var impuesto = ($('#fk_id_impuesto').select2('data')[0].porcentaje)/100 * subtotal;
    return (impuesto).toFixed(2);
}

function totalOrden() {

    var subtotal = 0;
    var impuesto = 0;
    var descuento_total = 0;

    if($('#productos tbody tr').length){
        $('#productos tbody tr').each(function () {
            //Del producto
            var cantidad_row = +$(this).find('.cantidad_row').val();//Decimal
            var precio_row = +$(this).find('.precio_unitario_row').val();//Decimal
            var porcentaje_row = +$(this).find('.porcentaje').val()/100;//Decimal
            var descuento_row = +$(this).find('.descuento_row').val();
            descuento_total += descuento_row;//Decimal
            var subtotal_row = (cantidad_row * precio_row) - descuento_row;
            //Del total
            subtotal += cantidad_row*precio_row;
            impuesto += subtotal_row * porcentaje_row;
        });
        var descuento_porcentaje = ( descuento_total * 100)/ subtotal;

        var total = subtotal + impuesto - descuento_total;
        $('#subtotal_lbl').text((subtotal).toFixed(2));
        $('#subtotal').val(subtotal.toFixed(2));
        $('#impuesto_lbl').text(impuesto.toFixed(2));
        $('#impuesto_total').val(impuesto.toFixed(2));
        $('#total_orden').val(total.toFixed(2));
        $('#descuento_total').val(descuento_total.toFixed(2));
        $('#descuento_porcentaje').val(descuento_porcentaje.toFixed(2));
    }else{
        $('#subtotal_lbl').text(0);
        $('#subtotal').val(0);
        $('#impuesto_lbl').text(0);
        $('#impuesto_total').val(0);
        $('#total_orden').val(0);
        $('#descuento_total').val(0);
        $('#descuento_porcentaje').val(0);
    }
}

function borrarFila(el) {
    var tr = $(el).closest('tr');
    tr.fadeOut(400, function(){
        tr.remove().stop();
    });
    $.toaster({priority : 'success',title : '¡Advertencia!',message : 'Se ha eliminado la fila correctamente',
        settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
    totalOrden();
}

function limpiarCampos() {
    $('#fk_id_sku').val(0).trigger('change').rules('remove');
    $('#fk_id_upc').val(0).trigger('change').prop('disabled',true).rules('remove');
    $('#activo_upc').prop('checked',false);
    $('#fk_id_proyecto').val(0).trigger('change').rules('remove');
    $('#fk_id_impuesto').val('0').trigger('change').rules('remove');
    $('#fecha_necesario').val('').rules('remove');
    $('#cantidad').val('1').rules('remove');
    $('#precio_unitario').val('').rules('remove');
    $('#descuento').val(0).rules('remove');
}

function validateDetail() {
    if ($("#fk_id_sku").val() > 0) {
        $('#fk_id_sku').rules('add',{
            required: true,
            messages:{
                required: 'Selecciona un SKU'
            }
        });
        $('#cantidad').rules('add',{
            required: true,
            number: true,
            range: [1,99999],
            messages:{
                required: 'Ingresa una cantidad',
                number: 'El campo debe ser un número',
                range: 'El número debe ser entre 1 y 99999'
            }
        });
        $('#fk_id_impuesto').rules('add',{
            required: true,
            messages:{
                required: 'Selecciona un tipo de impuesto'
            }
        });
        $.validator.addMethod('precio',function (value,element) {
            return this.optional(element) || /^\d{0,10}(\.\d{0,2})?$/g.test(value);
        },'El precio no debe tener más de dos decimales');
        $.validator.addMethod( "greaterThan", function( value, element, param ) {
            return value > param;
        }, "Please enter a greater value." );
        $('#precio_unitario').rules('add',{
            required: true,
            number: true,
            precio:true,
            greaterThan:0,
            messages:{
                required: 'Ingresa un precio unitario',
                number: 'El campo debe ser un número',
                greaterThan: 'El número debe ser mayor a 0',
                precio: 'El precio no debe tener más de dos decimales'
            }
        });
        $.validator.addMethod( "lessThan", function( value, element, param ) {
            return value <= param;
        }, "Please enter a greater value." );
        $('#descuento').rules('add',{
            number:true,
            precio:true,
            lessThan: +$('#precio_unitario').val(),
            messages:{
                number:'El campo debe ser numérico',
                lessThan:'El descuento debe ser menor al precio',
                precio: 'El descuento no debe tener más de dos decimales'
            }
        });
    }
}


function tiemposentrega() {
    var mayor_tiempo = 0;
    $('#productos tbody tr').each(function (index,row) {
        if($(row).find('.tiempo_entrega').val() != "null")
            mayor_tiempo = $(row).find('.tiempo_entrega').val() > mayor_tiempo ? $(row).find('.tiempo_entrega').val() : mayor_tiempo;
    });
    var fecha = new Date();
    fecha.addDays(mayor_tiempo);
    $('#fecha_estimada_entrega').val(fecha.getFullYear()+'-'+(fecha.getMonth()+1)+'-'+fecha.getDate());
    $('#tiempo_entrega').val(mayor_tiempo);
}
Date.prototype.addDays = function(days) {
    this.setDate(this.getDate() + +days);
    return this;
};
