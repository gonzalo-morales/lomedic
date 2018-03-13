$(document).on('change','.orden-compra',function () {
        var _url = $('#factura').data('urlorden');
        var obj = $(this);
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
        if ($(this).val() !== ''){
            $('#loadinglinea').show();
            $.ajax({
                url: _url,
                type: 'POST',
                data: {'id_orden':$(this).val()},
                dataType: 'json',
                success: function (data) {
                    $(obj).parent().siblings().find('select').children('option').remove();
                    var detSelect = $(obj).parent().siblings().find('select').attr('disabled',false);
                    detSelect.append('<option value="" disabled>Seleccione...</option>');

                    $.each(data,function(id,value){
                        var option = new Option(value.value, value.id);
                        detSelect.append(option);
                    });
                        detSelect.prop('disabled', (data.length == 0));
                    $('#loadinglinea').hide();
                },
                error: function (e) {
                    $.toaster({
                        priority: 'danger', title: 'Â¡Error!', message: "Error: "+e,
                        settings: {'timeout': 10000, 'toaster': {'css': {'top': '5em'}}}
                    });
                    $('#loadinglinea').hide();
                }
            });
        }else {
            $(obj).parent().siblings().find('select').children('option').remove();
            var detSelect = $(obj).parent().siblings().find('select').attr('disabled',true);
            detSelect.append('<option value="" selected>Seleccione...</option>');

        }
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
                if(data.length > 0){
                    $('#comprador').val(data[0].nombre + ' ' + data[0].apellido_paterno + ' ' + data[0].apellido_materno);
                }else{
                    $('#comprador').val('');
                    $.toaster({
                        priority: 'warning', title: 'Â¡Oooops!', message: 'No se encontrÃ³ un nombre de comprador',
                        settings: {'timeout': 10000, 'toaster': {'css': {'top': '5em'}}}
                    });
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
                    priority: 'danger', title: 'Â¡Error!', message: 'Por favor verifica la extensiÃ³n de ambos archivos',
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
                                priority: 'danger', title: 'Â¡Error -1 !', message: 'Ha ocurrido un error al cargar los datos',
                                settings: {'timeout': 10000, 'toaster': {'css': {'top': '5em'}}}
                            });
                        }else if(data.estatus == -2 || data.estatus == -3){
                            $.toaster({
                                priority: 'danger', title: 'Â¡Error'+data.estatus+' !', message: data.resultado,
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
                                  '<th>Detalle O.C.</th>' +
                                  '</tr>'
                                );
                                $.each(data.resultado,function (index,value) {
                                    value.Descuento = value.Descuento != null ? value.Descuento : "0.00";
                                    $("#productos_facturados").append(
                                        '<tr>' +
                                        '<td>'+value.ClaveProdServ+'<input name="relations[has][detalle]['+index+'][fk_id_clave_producto_servicio]" type="hidden" value="'+value.IdClaveProdServ+'"></td>' +
                                        '<td>'+value.ClaveUnidad+'<input name="relations[has][detalle]['+index+'][fk_id_clave_unidad]" type="hidden" value="'+value.IdClaveUnidad+'"></td>' +
                                        '<td>'+value.Descripcion+'<input name="relations[has][detalle]['+index+'][descripcion]" type="hidden" value="'+value.Descripcion+'"></td>' +
                                        '<td>'+value.Cantidad+'<input name="relations[has][detalle]['+index+'][cantidad]" type="hidden" value="'+value.Cantidad+'"></td>' +
                                        '<td>'+value.ValorUnitario+'<input name="relations[has][detalle]['+index+'][precio_unitario]" type="hidden" value="'+value.ValorUnitario+'"></td>' +
                                        '<td>'+value.Descuento+'<input name="relations[has][detalle]['+index+'][descuento]" type="hidden" value="'+value.Descuento+'"></td>' +
                                        '<td>'+value.Importe_impuesto+'<input name="relations[has][detalle]['+index+'][fk_id_impuesto]" type="hidden" value="'+value.IdImpuesto+'"></td>' +
                                        '<td>'+value.Importe+'<input name="relations[has][detalle]['+index+'][importe]" type="hidden" value="'+value.Importe+'"></td>' +
                                        '<td><input name="relations[has][detalle]['+index+'][fk_id_documento_base]" class="form-control integer orden-compra" value=""></td>' +
                                        '<td>' +
                                            '<div id="loadinglinea" class="w-100 h-100 text-center text-white align-middle loadingData" style="display: none">Cargando... <i class="material-icons align-middle loading">cached</i></div>' +
                                            '<select name="relations[has][detalle]['+index+'][fk_id_linea]" class="form-control custom-select" disabled>'+
                                            '<option value="0" disabled="" selected="">Seleccione...</option>'+
                                        '</select></td>' +
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
                                  '<th>Detalle O.C.</th>' +
                                  '</tr>'
                                );
                                $.each(data.resultado,function (index,value) {
                                   $("#productos_facturados").append(
                                       '<tr>' +
                                       '<td>'+value.Descripcion+'<input name="relations[has][detalle]['+index+'][descripcion]" type="hidden" value="'+value.Descripcion+'"></td>' +
                                       '<td>'+value.Unidad+'<input name="relations[has][detalle]['+index+'][unidad]" type="hidden" value="'+value.Unidad+'"></td>' +
                                       '<td>'+value.Cantidad+'<input name="relations[has][detalle]['+index+'][cantidad]" type="hidden" value="'+value.Cantidad+'"></td>' +
                                       '<td>'+value.ValorUnitario+'<input name="relations[has][detalle]['+index+'][precio_unitario]" type="hidden" value="'+value.ValorUnitario+'"></td>' +
                                       '<td>'+value.Importe+'<input name="relations[has][detalle]['+index+'][importe]" type="hidden" value="'+value.Importe+'"></td>' +
                                       '<td><input name="relations[has][detalle]['+index+'][fk_id_documento_base]" class="form-control integer orden-compra" value=""></td>' +
                                       '<td>' +
                                            '<div id="loadinglinea" class="w-100 h-100 text-center text-white align-middle loadingData" style="display: none">Cargando... <i class="material-icons align-middle loading">cached</i></div>' +
                                            '<select name="relations[has][detalle]['+index+'][fk_id_linea]" class="form-control custom-select" disabled>'+
                                           '<option value="0" disabled="" selected="">Seleccione...</option>'+
                                       '</select></td>' +
                                       '</tr>');
                                });
                            }
                            $.toaster({
                                priority: 'success', title: 'Â¡Ã‰xito!', message: 'Se han importado los datos correctamente',
                                settings: {'timeout': 10000, 'toaster': {'css': {'top': '5em'}}}
                            });
                            $('#factura').show();
                        }
                        $('#loadingxml').hide();
                        var archivo = $('#archivo_xml_input').prop('files');
                        $('#archivo_xml_hidden').prop('files',archivo);
                        $('#uuid').val(data.uuid);
                        $('#version_sat').val(data.version);
                    },
                    error: function (jqXHR) {
                        $.toaster({
                            priority: 'danger', title: 'Â¡Error '+jqXHR.status+'!', message: 'Ha ocurrido un error al cargar los datos',
                            settings: {'timeout': 10000, 'toaster': {'css': {'top': '5em'}}}
                        });
                        $('#loadingxml').hide();
                    }
                });
                $('#loadingpdf').show();
                //Para mostrar el pdf
                var pdf = $('#archivo_pdf_input').prop('files')[0];
                getBase64(pdf);
                var archivo = $('#archivo_pdf_input').prop('files');
                $('#archivo_pdf_hidden').prop('files',archivo);
            }
        }else{
            $.toaster({
                priority: 'danger', title: 'Â¡Error!', message: 'Por favor sube ambos archivos y selecciona un proveedor',
                settings: {'timeout': 10000, 'toaster': {'css': {'top': '5em'}}}
            });
        }
    });

    $(document).on('submit',function (e) {

        $.validator.addMethod('minStrict', function (value, element, param) {
            return (value > param);
        },'El valor debe ser mayor a {0} y numÃ©rico');
        $.validator.addMethod('cDigits',$.validator.methods.digits,'El campo debe ser entero');
        $.validator.addClassRules('integer',{
            minStrict:-1,
            cDigits:true
        });

        if(!$('#form-model').valid()){
            e.preventDefault();
        }

        if($('#productos_facturados tr').length < 1){
            e.preventDefault();
            $.toaster({
                priority: 'danger', title: 'Â¡Error!', message: 'Por favor carga la tabla de facturas',
                settings: {'timeout': 10000, 'toaster': {'css': {'top': '5em'}}}
            });
        }
    });

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
        if(reader.result != "data:"){
            $('#pdf').prop('data',reader.result);
            $('#loadingpdf').hide();
            $.toaster({
                priority: 'success', title: 'Â¡Ã‰xito!', message: 'Se ha importado el PDF correctamente',
                settings: {'timeout': 10000, 'toaster': {'css': {'top': '5em'}}}
            });
        }else{
            $.toaster({
                priority: 'info', title: 'Â¡Advertencia!', message: 'El PDF no contiene informaciÃ³n',
                settings: {'timeout': 10000, 'toaster': {'css': {'top': '5em'}}}
            });
            $('#loadingpdf').hide();
        }

    };
    reader.onerror = function (error) {
        console.log('Error: ', error);
        $('#loadingpdf').hide();
    };
}
