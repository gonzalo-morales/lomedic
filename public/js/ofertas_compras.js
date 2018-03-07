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
    
    if(window.location.href.toString().indexOf('crear') > -1){
        if($('#fk_id_empresa').val() > 0){
            $('#loadingsucursales').show();
            $('#fk_id_sucursal').empty();
            let idempresa = $('#fk_id_empresa').val();
            let _url = $('#fk_id_sucursal').data('url');
            $.ajax({
                url: _url,
                data: {'param_js':sucursales_js,$fk_id_empresa:idempresa},
                dataType: "json",
                success: function (response) {
                    var options = [];
                    options.push('<option value="0" selected disabled>Seleccione la Sucursal...</option>'); 
                    for (let i = 0; i < response.length; i++) {
                        options.push('<option value="' + response[i].id_sucursal + '">' + response[i].sucursal + '</option>');
                    }
                    $('#fk_id_sucursal').append(options.join(''));
                    $('#loadingsucursales').hide();
                    $('#fk_id_sucursal').select2({
                        disabled: false,
                    });
                },
                error: function(){
                    $.toaster({priority : 'danger',title : '¡Lo sentimos!',message : 'No hay sucursales en la empresa, verifica la información e intenta de nuevo.',
                    settings:{'timeout':3000,'toaster':{'css':{'top':'5em'}}}});
                    $('#loadingsucursales').hide();
                    $('#fk_id_sucursal').select2({
                        placeholder: "Empresa sin sucursales :(",
                        disabled: true,
                    });
                }
            });
        } else{
            $('#fk_id_sucursal').select2({
                placeholder: "Seleccione la empresa...",
                disabled:true,
            })
        }
    }

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

    $('#fk_id_empresa').on('change',function () {
        $.ajax({
            url: $('#fk_id_proveedor').data('url'),
            dataType:'json',
            data:{fk_id_empresa:$("#fk_id_empresa").val()},
            success: function (data) {

                $('#fk_id_proveedor').empty().select2({
                    minimumResultsForSearch: 15,
                    data:data,
                });
            }
        });
    });

    $('#fk_id_cliente').on('change',function () {
        $('#loadingproyectos').show();
        $('#fk_id_proyecto').empty();
        $.ajax({
            url: $('#fk_id_proyecto').data('url'),
            data: {
                'param_js':proyectos_js,
                $fk_id_cliente:$('#fk_id_cliente').val()
            },
            dataType:'JSON',
            success: function (response) {
                var options = [];
                options.push('<option value="0" selected disabled>Seleccione el proyecto...</option>'); 
                for (let i = 0; i < response.length; i++) {
                    options.push('<option value="' + response[i].id + '">' + response[i].text + '</option>');
                }
                $('#fk_id_proyecto').append(options.join(''));
                $('#loadingproyectos').hide();
                $('#fk_id_proyecto').select2({
                    disabled: false,
                });
            },
            error: function(){
                $.toaster({priority : 'warning',title : '¡Lo sentimos!',message : 'No hay proyectos activos en este cliente, te recomendamos seleccionar otro.',
                settings:{'timeout':3000,'toaster':{'css':{'top':'5em'}}}});
                $('#loadingproyectos').hide();
                $('#fk_id_proyecto').select2({
                    placeholder: "Cliente sin proyectos activos",
                    disabled: true,
                });
            }
        });
    });

    $('#fk_id_sku').on('change', function () {
        $('#loadingupcs').show();
        $('#fk_id_upc').empty();
        let idsku = $(this).val();
        let _url = $('#fk_id_upc').data('url');
        $.ajax({
            url: _url,
            data:{
                'param_js':upcs_js,$id_sku:idsku
            },
            dataType: "json",
            success: function (response) {
                var options = [];
                options.push('<option value="0" selected disabled>Seleccione el UPC...</option>'); 
                for (let i = 0; i < response.length; i++) {
                    options.push('<option data-nc="'+ response[i].nombre_comercial +'" data-descripcion="'+ response[i].descripcion +'" value="' + response[i].id_upc + '">' + response[i].upc + '</option>');
                }
                $('#fk_id_upc').append(options.join(''));
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
    });

    $('#descuento_oferta').on('keyup',function () {
       totalOrden();
    });

});//docReady

function agregarProducto() {
    validateDetail();
    if($('#form-model').valid()){
        var tableData = $('#productos > tbody');
        var sku = $('#fk_id_sku').val() ? $('#fk_id_sku').val() : "null";
        var cliente = $('#fk_id_proveedor').val() && $('#fk_id_proveedor').val() != null ? $('#fk_id_proveedor').val() : "null";
        var upc = $('#fk_id_upc').val() != null ? $('#fk_id_upc').val() : "null";
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

        // $.ajax({
        //     url: $('#fk_id_sku').data('url-tiempo_entrega'),
        //     data: {
        //         'param_js':tiempo_entrega_js,
        //         $fk_id_sku:sku,
        //         $fk_id_socio_negocio:cliente,
        //         $fk_id_upc:upc
        //     },
        //     dataType:'JSON',
        //     success: function (tiempo_entrega) {
        //         var row_id = dataTable.activeRows.length;
        //         var total = totalProducto();

        //         var text_upc = 'UPC no seleccionado';
        //         var id_upc = 0;
        //         if($('#fk_id_upc').val()){
        //             text_upc = $('#fk_id_upc').select2('data')[0].text;
        //             id_upc = $('#fk_id_upc').val();
        //         }
        //         var i = $('#productos > tbody > tr').length;
        //         var row_id = i > 0 ? +$('#productos > tbody > tr:last').find('#index').val()+1 : 0;
        //         tableData.append(
        //             '<tr><th>' + 'N/A' +
        //                 '<input type="hidden" id="index" value="'+row_id+'">'+
        //                 '<input type="hidden" name="relations[has][detalle]['+row_id+'][fk_id_documento_base]" value=""/>'+
        //                 '<input type="hidden" name="relations[has][detalle]['+row_id+'][fk_id_sku]" value="'+ $('#fk_id_sku').val() +'"/>'+
        //                 '<input type="hidden" name="relations[has][detalle]['+row_id+'][cantidad]" value="'+ $('#cantidad').val() +'"/>'+
        //                 '<input type="hidden" name="relations[has][detalle]['+row_id+'][fk_id_upc]" value="'+ id_upc +'"/>'+
        //                 '<input type="hidden" name="relations[has][detalle]['+row_id+'][precio_unitario]" value="'+ $('#precio_unitario').val() +'"/>'+
        //                 '<input type="hidden" name="relations[has][detalle]['+row_id+'][descuento_detalle]" value="'+ $('#descuento_detalle').val() +'"/>'+
        //                 '<input type="hidden" name="relations[has][detalle]['+row_id+'][fk_id_impuesto]" value="'+ $('#fk_id_impuesto').val() +'"/>'+
        //                 '<input type="hidden" name="relations[has][detalle]['+row_id+'][fk_id_proyecto]" value="' + $('#fk_id_proyecto').val() + '" />'+
        //                 '<input type="hidden" name="relations[has][detalle]['+row_id+'][fk_id_cliente]" value="' + $('#fk_id_cliente').val() + '" />'+
        //                 '<input type="hidden" name="relations[has][detalle]['+row_id+'][fk_id_unidad_medida]" value="' + $('#fk_id_unidad_medida').val() + '" />'+
        //                 '<input type="hidden" name="relations[has][detalle]['+row_id+'][fk_id_proveedor]" value="'+ $('#fk_id_proveedor').val() +'"/>'+
        //                 '<input type="hidden" name="relations[has][detalle]['+row_id+'][fecha_necesario]" value="'+ $('#fecha_necesario').val() +'"/></th>' +
        //             '<td>' + '<img style="max-height:40px" src="img/sku.png" alt="sku"/> ' + $('#fk_id_sku').select2('data')[0].text + '</td>' +
        //             '<td>' + '<img style="max-height:40px" src="img/upc.png" alt="upc"/> ' + text_upc + '</td>' +
        //             '<td>' + $('#fk_id_sku').select2('data')[0].descripcion_corta + '</td>' +
        //             '<td>' + $('#fk_id_sku').select2('data')[0].descripcion + '</td>' +
        //             '<td>' + $('#fk_id_cliente option:selected').html() + '</td>' +
        //             '<td>' + $('#fk_id_proyecto option:selected').html() + '</td>' +
        //             '<td>' + $('#fk_id_unidad_medida option:selected').html() + '</td>' +
        //             '<td>' + $('#cantidad').val() + '</td>' +
        //             '<td>' + $('#fk_id_proyecto option:selected').html() + '</td>' +
        //             '<td>' + $('#precio_unitario').val() + '</td>' +
        //             '<td>' + $('#descuento_detalle').val() + '</td>' +
        //             '<td>' + total + '</td>' +
        //             '<td class="position-relative">'+ '<div class="w-100 h-100 text-center text-white align-middle loadingData" style="display: none">Calculando el total... <i class="material-icons align-middle loading">cached</i></div>'+
        //                 total + '</td>' +
        //             '<td>'+ '<button data-toggle="Eliminar" data-placement="top" title="Eliminar" data-original-title="Eliminar" type="button" class="text-primary btn btn_tables is-icon eliminar" style="background:none;" data-delay="50" onclick="borrarFila(this)"><i class="material-icons">delete</i></button>'+'</td></tr>'
        //             );
        //         $.toaster({priority : 'success',title : '¡Éxito!',message : 'Producto agregado con éxito',
        //             settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}
        //         });
        //         limpiarCampos();
        //         totalOrden();
        //         tiemposentrega();
        //     },
        //     error: function () {
        //     }
        // });
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
    $("#fk_id_sku").select2();
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
    let $totalRows = $('#productos > tbody > tr').find('.totalRow');
    let subTotal = 0;
    for (let i = 0; i < $totalRows.length; i++) {
       subTotal += +$($totalRows[i]).val();
    }
    let descuento_porcentaje = $('#descuento_oferta').val()/100;
    let descuento = descuento_porcentaje*subTotal;
    let total = subTotal-descuento;
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