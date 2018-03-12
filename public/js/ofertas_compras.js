// Inicializar los datepicker para las fechas necesarias
$(document).ready(function(){

    $('.datepicker').pickadate({
        selectMonths: true, // Creates a dropdown to control month
        selectYears: 3, // Creates a dropdown of 3 years to control year
        min: true,
        format: 'yyyy-mm-dd'
    });

    $('[data-toggle]').tooltip();
    
    initSelects()
    totalOrden();

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

    $('#fk_id_proveedor').on('change', function(){
        $('#loadingskus').show();
        $('#fk_id_upc').empty();
        $('#fk_id_proyecto').empty();
        $('#fk_id_upc').select2({
            placeholder: "Seleccione el SKU...",
            disabled: true,
        });
        $('#fk_id_proyecto').select2({
            placeholder: "Seleccione el SKU...",
            disabled: true,
        });
        var _url = $(this).data('url');
        $.ajax({
            url: _url,
            data: {
                'param_js':skus_js,
                $socio_negocio:$(this).val()
            },
            dataType: "json",
            success: function (response) {
                $('#fk_id_sku').empty();
                var options = [];
                options.push('<option value="0" selected disabled>Seleccione el SKU...</option>'); 
                for (var i = 0; i < response.length; i++) {
                    options.push('<option value="' + response[i].id_sku + '">' + response[i].sku + '</option>');
                }
                $('#fk_id_sku').append(options.join(''));
                $('#loadingskus').hide();
                $('#fk_id_sku').select2({
                    disabled: false,
                });
            },
            error: function(){
                $.toaster({priority : 'warning',title : '¡Lo sentimos!',message : 'No hay productos disponibles en el proveedor. Verifique la información.',
                settings:{'timeout':3000,'toaster':{'css':{'top':'5em'}}}});
                $('#loadingskus').hide();
                $('#fk_id_sku').select2({
                    placeholder: "Cliente sin productos activos",
                    disabled: true,
                });
            }
        });
        $('#agregar').prop('disabled',false);
        $('.progress-button').prop('disabled',false);
    })

    $('#fk_id_sku').on('change', function () {
        $('#loadingskus').show();
        $('#loadingproyectos').show();
        upcs()
        var idsku = $(this).val();
        var _url = $(this).data('url-tiempo_entrega');
        $.ajax({
            url: _url,
            data:{
                'param_js':proyectos_js,
                $id_sku:idsku
            },
            dataType: "json",
            success: function (response) {
                $('#fk_id_proyecto').empty();
                var proyectos = [];
                proyectos.push('<option value="0" selected disabled>Seleccione el Proyecto...</option>');
                for (var i = 0; i < response.length; i++) {
                    proyectos.push('<option value="' + response[i].id + '">' + response[i].text + '</option>');
                }
                $('#fk_id_proyecto').append(proyectos.join(''));
                $('#loadingproyectos').hide();
                $('#loadingskus').hide();
                $('#fk_id_proyecto').select2({
                    disabled: false,
                });
            },
            error: function(){
                $.toaster({priority : 'warning',title : '¡Lo sentimos!',message : 'No hay Proyectos disponibles.',
                settings:{'timeout':3000,'toaster':{'css':{'top':'5em'}}}});
                $('#loadingproyectos').hide();
                $('#loadingskus').hide();
                $('#fk_id_proyecto').select2({
                    placeholder: "SKU sin proyectos",
                    disabled: true,
                });
            }
        });
    });

    $('#descuento_oferta').on('keyup',function () {
       totalOrden();
    });

});//docReady

function upcs(){
    $('#loadingupcs').show();
    var idsku = $('#fk_id_sku').val();
    var _url = $('#fk_id_upc').data('url');
    $.ajax({
        url: _url,
        data:{
            'param_js':upcs_js,
            $id_sku:idsku
        },
        dataType: "json",
        success: function (response) {
            console.log(response)
            $('#fk_id_upc').empty();
            var upcs = [];
            upcs.push('<option value="0" selected disabled>Seleccione el UPC...</option>');
            for (var i = 0; i < response.length; i++) {
                upcs.push('<option data-nc="'+ response[i].nombre_comercial +'" data-descripcion="'+ response[i].descripcion +'" value="' + response[i].id_upc + '">' + response[i].upc + '</option>');
            }
            $('#fk_id_upc').append(upcs.join(''));
            $('#loadingupcs').hide();
            $('#fk_id_upc').select2({
                disabled: false,
            });
        },
        error: function(){
            $.toaster({priority : 'warning',title : '¡Lo sentimos!',message : 'No hay UPCs disponibles.',
            settings:{'timeout':3000,'toaster':{'css':{'top':'5em'}}}});
            $('#loadingupcs').hide();
            $('#fk_id_upc').select2({
                placeholder: "SKU sin UPC(s)",
                disabled: true,
            });
        }
    }); 
}

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
                $.toaster({priority : 'success',title : '¡Éxito!',message : 'Producto agregado con éxito',
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
        $.toaster({priority : 'danger',title : '¡Error!',message : 'Hay campos que requieren de tu atención',
            settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
        }
}

function initSelects() {
    $('#fk_id_proveedor').select2();
    $('#fk_id_cliente').select2();
    $('#fk_id_proyecto').select2({
        disabled: true,
        placeholder: "Seleccione el Cliente...",
    });
    $("#fk_id_sku").select2({
        placeholder: "Seleccione el Proveedor...",
        disabled:true,
    });
    $("#fk_id_upc").select2({
        placeholder: "Seleccione el SKU...",
        disabled:true,
    });
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
    //Eliminar reglas de validación detalle
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
            number: 'El campo debe ser un número',
            range: 'El número debe ser entre 1 y 9999'
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
    },'El precio no tiene un formato válido');
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
            number: 'El campo debe ser un número',
            greaterThan: 'El número debe ser mayor a 0',
        }
    });
    $.validator.addMethod('descuento',function (value,element) {
        return this.optional(element) || /^\d{0,2}(\.\d{0,4})?$/g.test(value);
    },'El descuento no tiene un formato válido');
    $('#descuento_detalle').rules('add',{
        number: true,
        descuento:true,
        greaterThan:-1,
        messages:{
            required: 'Ingresa un precio unitario',
            number: 'El campo debe ser un número',
            greaterThan: 'El número debe ser mayor a {0}',
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
    $.toaster({priority : 'success',title : '¡Advertencia!',message : 'Se ha eliminado la fila correctamente',
        settings:{'timeout':2000,'toaster':{'css':{'top':'5em'}}}});
    totalOrden();
}
// Date.prototype.addDays = function(days) {
//     this.setDate(this.getDate() + days);
//     return this;
// };