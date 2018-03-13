// Inicializar los datepicker para las fechas necesarias
$(document).ready(function(){

    $('[data-toggle]').tooltip();
    
    initSelects()
    totalOrden();

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
                        data: function (term) {
                            return {term: term};
                        },
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

    $('#fk_id_impuesto').on('change', function() {
        $('#agregar').prop('disabled',true);
        $('#loadingprecio').show();
        var idimpuesto = $('#fk_id_impuesto option:selected').val();
        var _url = $(this).data('url');
        $.ajax({
            async: true,
            url: _url,
            data: {'param_js':porcentaje_js,$id_impuesto:idimpuesto},
            dataType: 'json',
                success: function (data) {
                  if(data[0].tasa_o_cuota == null)
                    $('#impuesto').val('');
                  else
                    $('#impuesto').val(data[0].tasa_o_cuota);
    
                totalProducto()
                $('#loadingprecio').hide();
                $('#agregar').prop('disabled',false);
            }
        });
    });

    $('#fk_id_proveedor').on('change',function(){
        if($(this).val() > 0){
            $("#fk_id_sku").select2({
                disabled:false,
                minimumInputLength:3,
                ajax:{
                    delay:500,
                    url: $("#fk_id_proveedor").data('url'),
                    dataType: 'json',
                    data: function (params) {
                        return {
                            term: params.term,fk_id_socio_negocio:$('#fk_id_proveedor').val()};
                    },
                    processResults: function (data) {
                        console.log(data)
                        return {results: data}
                    },
                    error:function(){
                        $.toaster({priority : 'danger',title : '¡Error!',message : 'Al parecer no ingresaste un SKU válido, verifica que el SKU sea el correcto',
                        settings:{'timeout':3000,'toaster':{'css':{'top':'5em'}}}}); 
                    }
                }
            });
        } else {
            $('#fk_id_sku').val('');
            $('#fk_id_sku').select2({
                disabled:true,
            });
            $('#fk_id_proyecto').val('');
            $('#fk_id_proyecto').select2({
                disabled:true,
                placeholder: "Seleccione primero el proveedor..."
            });
            $('#fk_id_upc').val('');
            $('#activo_upc').prop('checked',false);
            $('#fk_id_upc').select2({
                disabled:true,
                placeholder: "Seleccione primero el proveedor..."
            });
        }
    });

    $('#fk_id_sku').on('change',function(){
        $('#fk_id_proyecto').select2({
            disabled: false,
            minimumResultsForSearch: Infinity,
            ajax:{
                url: $('#fk_id_proyecto').data('url'),
                dataType: 'json',
                data: function(){
                    var upc = 'NULL'
                    if($('#fk_id_upc').val()){
                        upc = $('#fk_id_upc').val();
                    }
    
                    var sku = 'NULL'
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
                        return {
                            results: $.map(data, function (value) {
                                return {
                                    id: value.id,
                                    text: value.text
                                }
                            })
                        }
                    }else{
                        $.toaster({priority : 'warning',title : '¡Oooops!',message : 'No se encontraron proyectos. Verifica que el SKU y el UPC coincidan con un proyecto',
                            settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}
                        });
                        return{
                            results:{
                                id:0,
                                text: 'Sin proyecto'
                            }
                        }
                    }
                }
            }
        });
    });

    $('#descuento_oferta').on('keyup',function () {
       totalOrden();
    });

});//docReady

function agregarProducto() {
    validateDetail();
    if($('#form-model').valid()){
        var sku = $('#fk_id_sku').val() ? $('#fk_id_sku').val() : "null";
        var cliente = $('#fk_id_proveedor').val() && $('#fk_id_proveedor').val() != null ? $('#fk_id_proveedor').val() : "null";
        var upc = $('#fk_id_upc').val() != null ? $('#fk_id_upc').val() : "null";
        $.ajax({
            url: $('#fk_id_sku').data('url-tiempo_entrega'),
            data: {
                'param_js':tiempo_entrega_js,
                $fk_id_sku:sku,
                $fk_id_socio_negocio:cliente,
                $fk_id_upc:upc
            },
            dataType:'JSON',
            success: function (tiempo_entrega) {
            var tableData = $('#productos > tbody');
            var total = totalProducto();
            var i = $('#productos > tbody > tr').length;
            var row_id = i > 0 ? +$('#productos > tbody > tr:last').find('#index').val()+1 : 0;
            tableData.append(
                '<tr><th>' + 'N/A' +
                    '<input type="hidden" id="index" value="'+row_id+'">'+
                    '<input type="hidden" name="relations[has][detalle]['+row_id+'][fk_id_documento_base]" value=""/>'+
                    '<input type="hidden" name="relations[has][detalle]['+row_id+'][fk_id_sku]" value="'+ $('#fk_id_sku').val() +'"/>'+
                    '<input type="hidden" name="relations[has][detalle]['+row_id+'][cantidad]" value="'+ $('#cantidad').val() +'"/>'+
                    '<input type="hidden" name="relations[has][detalle]['+row_id+'][fk_id_upc]" value="'+ $('#fk_id_upc').val() +'"/>'+
                    '<input type="hidden" name="relations[has][detalle]['+row_id+'][precio_unitario]" value="'+ $('#precio_unitario').val() +'"/>'+
                    '<input type="hidden" name="relations[has][detalle]['+row_id+'][descuento_detalle]" value="'+ $('#descuento_detalle').val() +'"/>'+
                    '<input type="hidden" name="relations[has][detalle]['+row_id+'][fk_id_impuesto]" value="'+ $('#fk_id_impuesto').val() +'"/>'+
                    '<input type="hidden" name="relations[has][detalle]['+row_id+'][fk_id_proyecto]" value="' + $('#fk_id_proyecto').val() + '" />'+
                    '<input type="hidden" name="relations[has][detalle]['+row_id+'][fk_id_cliente]" value="' + $('#fk_id_cliente').val() + '" />'+
                    '<input type="hidden" name="relations[has][detalle]['+row_id+'][fk_id_unidad_medida]" value="' + $('#fk_id_unidad_medida').val() + '" />'+
                    '<input type="hidden" name="relations[has][detalle]['+row_id+'][fk_id_proveedor]" value="'+ $('#fk_id_proveedor').val() +'"/>'+
                    '<input type="hidden" name="relations[has][detalle]['+row_id+'][total_producto]" class="totalRow" value="'+ total +'"/>'+
                    '<input type="hidden" value="'+ tiempo_entrega[0].tiempo_entrega +'" class="tiempo_entrega">' +
                '<td>' + '<img style="max-height:40px" src="img/sku.png" alt="sku"/> ' + $('#fk_id_sku option:selected').text() + '</td>' +
                '<td>' + '<img style="max-height:40px" src="img/upc.png" alt="upc"/> ' + $('#fk_id_upc option:selected').text() + '</td>' +
                '<td>' + $('#fk_id_upc option:selected').data('nc') + '</td>' +
                '<td>' + $('#fk_id_upc option:selected').data('descripcion') + '</td>' +
                '<td>' + $('#fk_id_cliente option:selected').html() + '</td>' +
                '<td>' + $('#fk_id_proyecto option:selected').html() + '</td>' +
                '<td>' + $('#fk_id_unidad_medida option:selected').html() + '</td>' +
                '<td>' + $('#cantidad').val() + '</td>' +
                '<td>' + $('#fk_id_impuesto option:selected').html() + '</td>' +
                '<td>' + $('#precio_unitario').val() + '</td>' +
                '<td>' + $('#descuento_detalle').val() + '</td>' +
                '<td class="position-relative">'+ '<div class="w-100 h-100 text-center text-white align-middle loadingData" style="display: none">Calculando el total... <i class="material-icons align-middle loading">cached</i></div>'+
                    total + '</td>' +
                '<td>'+ '<button data-toggle="Eliminar" data-placement="top" title="Eliminar" data-original-title="Eliminar" type="button" class="text-primary btn btn_tables is-icon eliminar" style="background:none;" data-delay="50" onclick="borrarFila(this)"><i class="material-icons">delete</i></button>'+'</td></tr>'
                );
                $.toaster({priority : 'success',title : 'Â¡Ã‰xito!',message : 'Producto agregado con Ã©xito',
                    settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}
                });
                limpiarCampos();
                totalOrden();
                tiemposentrega();
            },
            error: function () {
            }
        });
        $('[data-toggle]').tooltip();
    }else{
        $.toaster({priority : 'danger',title : 'Â¡Error!',message : 'Hay campos que requieren de tu atenciÃ³n',
            settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
        }
}

function initSelects() {
    $('#fk_id_proveedor').select2();
    $('#fk_id_proyecto').select2({
        disabled: true,
        placeholder: "Seleccione primero el proveedor y el SKU..."
    });
    $('#fk_id_sku').select2({
        disabled:true,
        placeholder: "Seleccione primero el proveedor..."
    })
}

function totalProducto() {
    var cantidad = $('#cantidad').val();
    var precio = $('#precio_unitario').val();
    var descuento_porcentaje = $('#descuento_detalle').val()/100;
    var descuento = precio*descuento_porcentaje;
    precio = precio-descuento;
    var subtotal = cantidad*precio;
    var impuesto = $('#impuesto').val()*subtotal;

    return (subtotal + impuesto).toFixed(2);
}

function totalOrden() {
    var $totalRows = $('#productos > tbody > tr').find('.totalRow');
    var subTotal = 0;
    for (var i = 0; i < $totalRows.length; i++) {
       subTotal += +$($totalRows[i]).val();
    }
    var descuento_porcentaje = $('#descuento_oferta').val()/100;
    var descuento = descuento_porcentaje*subTotal;
    var total = subTotal-descuento;
    return $('#total_oferta').val(total.toFixed(2));
}

function limpiarCampos() {
    $('#fk_id_cliente').val('');
    $('#fk_id_cliente').select2();
    $('#fk_id_proyecto').empty();
    $('#fk_id_proyecto').select2({
        placeholder: "Seleccione el Cliente...",
        disabled:true,
    });
    $('#fk_id_sku').val('');
    $('#fk_id_sku').select2();
    $('#fk_id_upc').empty();
    $('#fk_id_upc').select2({
        placeholder: "Seleccione el SKU...",
        disabled:true,
    });
    $('#fk_id_unidad_medida').val('');
    $('#fk_id_unidad_medida').select2();
    $('#cantidad').val('');
    $('#fk_id_impuesto').val('');
    $('#precio_unitario').val(0);
    $('#descuento_detalle').val(0);
    //Eliminar reglas de validaciÃ³n detalle
    $('#fk_id_sku').rules('remove');
    $('#fk_id_upc').rules('remove');
    $('#cantidad').rules('remove');
    $('#fk_id_impuesto').rules('remove');
    $('#precio_unitario').rules('remove');
    $('#fk_id_unidad_medida').rules('remove');
}

function validateDetail() {
    $('#fk_id_cliente').rules('add',{
        required: true,
        messages:{
            required: 'Selecciona un SKU'
        }
    });
    $('#fk_id_sku').rules('add',{
        required: true,
        messages:{
            required: 'Selecciona un SKU'
        }
    });
    $('#cantidad').rules('add',{
        required: true,
        number: true,
        range: [1,9999],
        messages:{
            required: 'Ingresa una cantidad',
            number: 'El campo debe ser un nÃºmero',
            range: 'El nÃºmero debe ser entre 1 y 9999'
        }
    });
    $('#fk_id_impuesto').rules('add',{
        required: true,
        messages:{
            required: 'Selecciona un tipo de impuesto'
        }
    });
    $.validator.addMethod('precio',function (value,element) {
        return this.optional(element) || /^\d{0,6}(\.\d{0,2})?$/g.test(value);
    },'El precio no tiene un formato vÃ¡lido');
    $.validator.addMethod( "greaterThan", function( value, element, param ) {

        if ( this.settings.onfocusout ) {
            $(element).addClass( "validate-greaterThan-blur" ).on( "blur.validate-greaterThan", function() {
                $( element ).valid();
            } );
        }

        return value > param;
    }, "Please enter a greater value." );
    $('#precio_unitario').rules('add',{
        required: true,
        number: true,
        precio:true,
        greaterThan:0,
        messages:{
            required: 'Ingresa un precio unitario',
            number: 'El campo debe ser un nÃºmero',
            greaterThan: 'El nÃºmero debe ser mayor a 0',
        }
    });
    $.validator.addMethod('descuento',function (value,element) {
        return this.optional(element) || /^\d{0,2}(\.\d{0,4})?$/g.test(value);
    },'El descuento no tiene un formato vÃ¡lido');
    $('#descuento_detalle').rules('add',{
        number: true,
        descuento:true,
        greaterThan:-1,
        messages:{
            required: 'Ingresa un precio unitario',
            number: 'El campo debe ser un nÃºmero',
            greaterThan: 'El nÃºmero debe ser mayor a {0}',
        }
    });

    $('#fk_id_unidad_medida').rules('add',{
        required:true,
        messages:{
            required: 'Selecciona una unidad de medida'
        }
    });
}

function tiemposentrega() {
    var mayor_tiempo = 0;
    $('#productos tbody tr').each(function (index,row) {
        if($(row).find('.tiempo_entrega').val() != "null")
            mayor_tiempo = $(row).find('.tiempo_entrega').val() > mayor_tiempo ? $(row).find('.tiempo_entrega').val() : mayor_tiempo;
    });
    var fecha = new Date();
    fecha.setDate(fecha.getDate() + +mayor_tiempo);
    // fecha.addDays(mayor_tiempo);
    $('#fecha_estimada_entrega').val(fecha.getFullYear()+'-'+(fecha.getMonth()+1)+'-'+fecha.getDate());
    $('#tiempo_entrega').val(mayor_tiempo);
}

function borrarFila(el) {
    var tr = $(el).closest('tr');
    tr.remove().stop();
    $.toaster({priority : 'success',title : 'Â¡Advertencia!',message : 'Se ha eliminado la fila correctamente',
        settings:{'timeout':2000,'toaster':{'css':{'top':'5em'}}}});
    totalOrden();
}
// Date.prototype.addDays = function(days) {
//     this.setDate(this.getDate() + days);
//     return this;
// };