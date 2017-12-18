$('.datepicker').pickadate({
    selectMonths: true, // Creates a dropdown to control month
    selectYears: 3, // Creates a dropdown of 3 years to control year
    format: 'yyyy-mm-dd'
});

$(document).ready(function () {
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
                        if(data.version == "3.3"){
                            $.ajax({
                                url: $('#uuid').data('url'),
                                type: 'POST',
                                data: {uuid:$('#uuid').val()},
                                success: function (factura) {
                                    $.each(factura.resultado,function (indice,valor) {
                                        var id_factura_proveedor = valor.id_factura_proveedor;
                                        var serie = valor.serie_factura != null ? valor.serie_factura : '';
                                        var folio = valor.folio_factura != null ? valor.folio_factura : '';
                                        if($('#relaciones').append(
                                            '<tr>' +
                                                '<td>' +
                                                    '<input type="hidden" name="relations[has][cfdirelacionado]['+indice+'][fk_id_documento_relacionado]" value="'+id_factura_proveedor+'">' +
                                                    '<input type="hidden" name="relations[has][cfdirelacionado]['+indice+'][fk_id_tipo_documento_relacionado]" value="7">' +
                                                    '<input type="hidden" name="relations[has][cfdirelacionado]['+indice+'][fk_id_tipo_documento]" value="11">' +
                                                    id_factura_proveedor +
                                                '</td>'+
                                                '<td>'+serie +' '+folio+'</td>' +
                                                '<td><input type="hidden" name="relations[has][cfdirelacionado]['+indice+'][fk_id_tipo_relacion]"></td>' +
                                                '<td><button class="btn is-icon text-primary bg-white" type="button" data-delay="50" onclick="borrarFila(this)"> <i class="material-icons">delete</i></button></td>' +
                                            '</tr>'
                                        )){

                                        }
                                    });
                                }
                            });
                        }else{
                            $('#fk_id_tipo_relacion').removeAttr('disabled');
                            $('#agregar_relacion').removeAttr('disabled');
                            $('#fk_id_factura_proveedor').removeAttr('disabled');
                            $.ajax({
                                url: $('#fk_id_socio_negocio').data('url'), 
                                type: 'GET',
                                data: {'param_js': facturas_js, $fk_id_socio_negocio:$('#fk_id_socio_negocio').val()},
                                dataType: 'json',
                                success: function (facturas) {
                                    let option = $('<option/>');
                                    option.val(0);
                                    option.attr('disabled','disabled');
                                    option.attr('selected','selected');
                                    option.text('...');
                                    $('#fk_id_factura_proveedor').empty().prepend(option);
                                    $.each(facturas,function (indice,valor) {
                                        let option = $('<option/>');
                                        option.val(valor.id_factura_proveedor);
                                        option.text(valor.serie_factura+valor.folio_factura);
                                        $('#fk_id_factura_proveedor').append(option);
                                    });
                                    $('#fk_id_factura_proveedor').select2();
                                }
                            });
                        }
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
    if($('#ordenes').length){
        if($('#ordenes_detalle tr').length < 1){
            var cantidad_cabecera = $('#ordenes_cabecera th').length;
            $('#ordenes_detalle').append(
                '<tr>' +
                '<td colspan="'+cantidad_cabecera+'" style="text-align: center">Sin órdenes relacionadas</td>' +
                '</tr>'
            );
        }
    }

    if($('#pagos').length){
        if($('#detalle_pagos tr').length < 1){
            var cantidad_cabecera = $('#encabezado_pagos th').length;
            $('#detalle_pagos').append(
                '<tr>' +
                '<td colspan="'+cantidad_cabecera+'" style="text-align: center">Sin pagos relacionados</td>' +
                '</tr>'
            );
        }
    }
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

    $('#agregar_relacion').click(function (e) {
        if($('#fk_id_tipo_relacion').val() > 0 && $('#fk_id_factura_proveedor').val() > 0){
            var i = $('#relaciones tr').length;
            var index = i > 0 ? +$('#relaciones tr:last').find('#index').val() + 1 : 0;
            $('#relaciones').append(
                '<tr>' +
                '<td>' +
                '<input type="hidden" id="index" value="'+index+'">' +
                '<input type="hidden" name="relations[has][cfdirelacionado]['+index+'][fk_id_documento_relacionado]" value="'+$('#fk_id_factura_proveedor').val()+'">'+
                '<input type="hidden" name="relations[has][cfdirelacionado]['+index+'][fk_id_tipo_documento_relacionado]" value="7">' +
                '<input type="hidden" name="relations[has][cfdirelacionado]['+index+'][fk_id_tipo_documento]" value="11">' +
                $('#fk_id_factura_proveedor').val()+
                '</td>'+
                '<td>'+$('#fk_id_factura_proveedor').select2('data')[0].text+'</td>' +
                '<td><input type="hidden" name="relations[has][cfdirelacionado]['+index+'][fk_id_tipo_relacion]" value="'+$('#fk_id_tipo_relacion').val()+'">'+$('#fk_id_tipo_relacion option:selected').text()+'</td>' +
                '<td><button class="btn is-icon text-primary bg-white" type="button" data-delay="50" onclick="borrarFila(this)"> <i class="material-icons">delete</i></button></td>' +
                '</tr>'
            );
            $.toaster({
                priority: 'success', title: '¡Éxito!', message: 'Se ha agregado una factura relacionada',
                settings: {'timeout': 10000, 'toaster': {'css': {'top': '5em'}}}
            });
        }else{
            $.toaster({
                priority: 'danger', title: '¡Error!', message: 'Selecciona un tipo de relación y una factura',
                settings: {'timeout': 10000, 'toaster': {'css': {'top': '5em'}}}
            });
        }
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

function borrarFila(el) {
    $(el).parent().parent('tr').remove();
    $.toaster({priority:'success',title:'¡Correcto!',message:'Se ha eliminado la fila correctamente',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
}