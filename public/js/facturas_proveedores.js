$('.datepicker').pickadate({
    selectMonths: true, // Creates a dropdown to control month
    selectYears: 3, // Creates a dropdown of 3 years to control year
    format: 'yyyy-mm-dd'
});

$(document).ready(function () {
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
    $('#fk_id_socio_negocio').change(function () {
        $('#loadingcomprador').show();
        $.ajax({
            url: $('#comprador').data('url'),
            type: 'GET',
            data: {'param_js':comprador_js,$id_socio_negocio:$(this).val()},
            dataType: 'json',
            success: function (data) {
                if(data){
                    $('#comprador').val(data[0].nombre + ' ' + data[0].apellido_paterno + ' ' + data[0].apellido_materno);
                }else{
                    $('#comprador').val('');
                }
                $('#loadingcomprador').hide();
            },
            error: function () {
                $('#comprador').val('');
                $('#loadingcomprador').hide();
            }
        });
    });

    $('#cargar').click(function () {
        if($('#archivo_xml_input').val() && $('#archivo_pdf_input').val() && $('#fk_id_socio_negocio').val() > 0){
            if($('#archivo_xml_input').val().substring($('#archivo_xml_input').val().lastIndexOf(".")) != '.xml' || $('#archivo_pdf_input').val().substring($('#archivo_pdf_input').val().lastIndexOf(".")) != '.pdf'){
                $.toaster({
                    priority: 'danger', title: '¡Error!', message: 'Por favor verifica la extensión de ambos archivos',
                    settings: {'timeout': 10000, 'toaster': {'css': {'top': '5em'}}}
                });
            }else{
                //Para leer el XML
                $('#encabezado_factura').empty();
                $('#productos_facturados').empty();
                $('#factura').hide();
                $('#loadingxml').show();
                $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
                var xml = $('#archivo_xml_input').prop('files')[0];
                var formData = new FormData();
                formData.append('file',xml);
                formData.append('fk_id_socio_negocio',$('#fk_id_socio_negocio').val());
                var _url = $('#archivo_xml_input').data('url');
                $.ajax({
                    url: _url,
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        console.log(data.estatus);
                        if(data.estatus == -1){
                            $.toaster({
                                priority: 'danger', title: '¡Error -1 !', message: 'Ha ocurrido un error al cargar los datos',
                                settings: {'timeout': 10000, 'toaster': {'css': {'top': '5em'}}}
                            });
                        }else if(data.estatus == -2 || data.estatus == -3){
                            $.toaster({
                                priority: 'danger', title: '¡Error'+data.estatus+' !', message: data.resultado,
                                settings: {'timeout': 10000, 'toaster': {'css': {'top': '5em'}}}
                            });
                        }else{
                            if(data.version == "3.3"){
                                $('#encabezado_factura').append(
                                  '<tr>' +
                                  '<th>Clave Producto Servicio</th>' +
                                  '<th>Clave Unidad</th>' +
                                  '<th>Descripcion</th>' +
                                  '<th>Cantidad</th>' +
                                  '<th>Precio Unitario</th>' +
                                  '<th>Descuento</th>' +
                                  '<th>Impuesto</th>' +
                                  '<th>Importe</th>' +
                                  '<th>Orden de Compra</th>' +
                                  '</tr>'
                                );
                                $.each(data.resultado,function (index,value) {
                                    value.Descuento = value.Descuento != null ? value.Descuento : "0.00";
                                    $("#productos_facturados").append(
                                        '<tr>' +
                                        '<td>'+value.ClaveProdServ+'<input name="productos['+index+'][fk_id_clave_producto_servicio]" type="hidden" value="'+value.IdClaveProdServ+'"></td>' +
                                        '<td>'+value.ClaveUnidad+'<input name="productos['+index+'][fk_id_clave_unidad]" type="hidden" value="'+value.IdClaveUnidad+'"></td>' +
                                        '<td>'+value.Descripcion+'<input name="productos['+index+'][descripcion]" type="hidden" value="'+value.Descripcion+'"></td>' +
                                        '<td>'+value.Cantidad+'<input name="productos['+index+'][cantidad]" type="hidden" value="'+value.Cantidad+'"></td>' +
                                        '<td>'+value.ValorUnitario+'<input name="productos['+index+'][precio_unitario]" type="hidden" value="'+value.ValorUnitario+'"></td>' +
                                        '<td>'+value.Descuento+'<input name="productos['+index+'][descuento]" type="hidden" value="'+value.Descuento+'"></td>' +
                                        '<td>'+value.Importe_impuesto+'<input name="productos['+index+'][fk_id_impuesto]" type="hidden" value="'+value.IdImpuesto+'"></td>' +
                                        '<td>'+value.Importe+'<input name="productos['+index+'][importe]" type="hidden" value="'+value.Importe+'"></td>' +
                                        '<td><input name="productos['+index+'][fk_id_orden_compra]" class="form-control integer" value=""></td>' +
                                        '</tr>');
                                });
                            }else if(data.version == "3.2"){
                                $('#encabezado_factura').append(
                                  '<tr>' +
                                  '<th>Descripcion</th>' +
                                  '<th>Unidad</th>' +
                                  '<th>Cantidad</th>' +
                                  '<th>Valor Unitario</th>' +
                                  '<th>Importe</th>' +
                                  '<th>Orden de Compra</th>' +
                                  '</tr>'
                                );
                                $.each(data.resultado,function (index,value) {
                                   $("#productos_facturados").append(
                                       '<tr>' +
                                       '<td>'+value.Descripcion+'<input name="productos['+index+'][descripcion]" type="hidden" value="'+value.Descripcion+'"></td>' +
                                       '<td>'+value.Unidad+'<input name="productos['+index+'][unidad]" type="hidden" value="'+value.Unidad+'"></td>' +
                                       '<td>'+value.Cantidad+'<input name="productos['+index+'][cantidad]" type="hidden" value="'+value.Cantidad+'"></td>' +
                                       '<td>'+value.ValorUnitario+'<input name="productos['+index+'][precio_unitario]" type="hidden" value="'+value.ValorUnitario+'"></td>' +
                                       '<td>'+value.Importe+'<input name="productos['+index+'][importe]" type="hidden" value="'+value.Importe+'"></td>' +
                                       '<td><input name="productos['+index+'][fk_id_orden_compra]" class="form-control integer" value=""></td>' +
                                       '</tr>');
                                });
                            }
                            $.toaster({
                                priority: 'success', title: '¡Éxito!', message: 'Se han importado los datos correctamente',
                                settings: {'timeout': 10000, 'toaster': {'css': {'top': '5em'}}}
                            });
                            $('#factura').show();
                        }
                        $('#loadingxml').hide();
                        $('#archivo_xml_hidden').prop('files',$('#archivo_xml_input').prop('files'));
                        $('#uuid').val(data.uuid);
                        $('#version_sat').val(data.version);
                    },
                    error: function (jqXHR) {
                        $.toaster({
                            priority: 'danger', title: '¡Error '+jqXHR.status+'!', message: 'Ha ocurrido un error al cargar los datos',
                            settings: {'timeout': 10000, 'toaster': {'css': {'top': '5em'}}}
                        });
                        $('#loadingxml').hide();
                    }
                });
                $('#loadingpdf').show();
                //Para mostrar el pdf
                var pdf = $('#archivo_pdf_input').prop('files')[0];
                getBase64(pdf);
                $('#archivo_pdf_hidden').prop('files',$('#archivo_pdf_input').prop('files'));
            }
        }else{
            $.toaster({
                priority: 'danger', title: '¡Error!', message: 'Por favor sube ambos archivos y selecciona un proveedor',
                settings: {'timeout': 10000, 'toaster': {'css': {'top': '5em'}}}
            });
        }
    });

    $(document).on('submit',function (e) {

        $.validator.addMethod('minStrict', function (value, element, param) {
            return (value > param);
        },'El valor debe ser mayor a {0} y numérico');
        $.validator.addMethod('cDigits',$.validator.methods.digits,'El campo debe ser entero');
        $.validator.addClassRules('integer',{
            minStrict:-1,
            cDigits:true
        });

        if(!$('#form-model').valid()){
            e.preventDefault();
        }
    })

    //Si existe la tabla de órdenes
    // if($('#ordenes').length){
    //     if($('#ordenes_detalle tr').length < 1){
    //         var cantidad_cabecera = $('#ordenes_cabecera th').length;
    //         $('#ordenes_detalle').append(
    //             '<tr>' +
    //             '<td colspan="'+cantidad_cabecera+'" style="text-align: center">Sin órdenes relacionadas</td>' +
    //             '</tr>'
    //         );
    //     }
    // }

    // if($('#pagos').length){
    //     if($('#detalle_pagos tr').length < 1){
    //         var cantidad_cabecera = $('#encabezado_pagos th').length;
    //         $('#detalle_pagos').append(
    //             '<tr>' +
    //             '<td colspan="'+cantidad_cabecera+'" style="text-align: center">Sin pagos relacionados</td>' +
    //             '</tr>'
    //         );
    //     }
    // }
    $(".nav-link").click(function (e) {
        e.preventDefault();
        $('#clothing-nav li').each(function () {
            $(this).children().removeClass('active');
        });
        $('.tab-pane').removeClass('active').removeClass('show');
        $(this).addClass('active');
        var tab = $(this).prop('href');
        tab = tab.split('#');
        $('#' + tab[1]).addClass('active').addClass('show');
    });

    $('#reloadpagos').click(function (e) {
        e.preventDefault();
        window.location.reload(true);
    });

    if($('#total_pagado').length){
        var total = +$('#total_pagado').val();
        $('#total_pagado').val(total.toFixed(2));
    }

    //Para eliminar un pago
    $('.eliminar_pago').click(function (e) {
        e.preventDefault();
        $('#eliminar_pago_button').attr('data-id',$(this).attr('href'));
        $('#confirmar_eliminar_pago').modal('show');
    });

    $('#eliminar_pago_button').click(function (e) {
        e.preventDefault();
        $.delete($('#eliminar_pago_button').attr('data-id'))
        $('#eliminar_pago_button').removeAttr('data-id');
    });

    $('#cancelar_deliminar_pago').click(function (e) {
        e.preventDefault();
        $('#eliminar_pago_button').removeAttr('data-id');
    });
});

function getBase64(file) {
    var reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = function () {
         $('#pdf').prop('data',reader.result);
         $('#loadingpdf').hide();
        $.toaster({
            priority: 'success', title: '¡Éxito!', message: 'Se ha importado el PDF correctamente',
            settings: {'timeout': 10000, 'toaster': {'css': {'top': '5em'}}}
        });
    };
    reader.onerror = function (error) {
        console.log('Error: ', error);
        $('#loadingpdf').hide();
    };
}
