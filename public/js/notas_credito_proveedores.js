$(document).submit(function (e) {
   if($('#productos_facturados tr').length < 1){
       e.preventDefault();
       $.toaster({
           priority: 'danger', title: 'Â¡Error!', message: 'Por favor carga la tabla de facturas',
           settings: {'timeout': 10000, 'toaster': {'css': {'top': '5em'}}}
       });
   }

});

$(document).ready(function () {

    $("#fk_id_socio_negocio").on('select2:selecting',function() {
        var val = $('#fk_id_socio_negocio').val() ? $('#fk_id_socio_negocio').val() : 0;
        $('#fk_id_socio_negocio').attr("data-old",val).trigger('change');
    });

    $('#fk_id_socio_negocio').change(function () {
        $('#confirmar_proveedor').modal('show');
    });

    $('#cancelarcambio').click(function () {
        var val = $('#fk_id_socio_negocio').data('old');
        $('#fk_id_socio_negocio').val(val).trigger('change');
    });

    $('#confirmarcambio').click(function () {
        $('#encabezado_factura').empty();
        $('#productos_facturados').empty();
        $('#relaciones').empty();
        $('#factura').hide();
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
                $('#relaciones').empty();
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

                                $.each(data.relacionados,function (indice,relacion) {
                                    var uuids = [];
                                    $.each(relacion['cfdi:CfdiRelacionado'],function (row,uuid) {
                                        uuids.push(uuid);
                                    });
                                    $.ajax({
                                        url: $('#fk_id_factura_proveedor').data('url'),
                                        data:{
                                            param_js: relacionadas_js,
                                            $uuid: uuids.toString()
                                        },
                                        tipoRelacion:{
                                            relacion:relacion['@TipoRelacion'],
                                            id_relacion:relacion['id_sat_tipo_relacion'],
                                            descripcion:relacion['descripcion']
                                        },
                                        success: function (documentos) {
                                            var self = this;
                                            $.each(documentos,function (linea,documento) {
                                                var i = $('#relaciones tr').length;
                                                var index = i > 0 ? +$('#relaciones tr:last').find('#index').val() + 1 : 0;

                                                $('#relaciones').append(
                                                    '<tr>' +
                                                    '<td>' +
                                                    '<input type="hidden" id="index" value="'+index+'">' +
                                                    '<input type="hidden" name="relations[has][relaciones]['+index+'][fk_id_documento_relacionado]" value="'+documento.id_documento+'">' +
                                                    '<input type="hidden" name="relations[has][relaciones]['+index+'][fk_id_tipo_documento_relacionado]" value="7">' +
                                                    '<input type="hidden" name="relations[has][relaciones]['+index+'][fk_id_tipo_documento]" value="11">' +
                                                    documento.id_documento +
                                                    '</td>' +
                                                    '<td>' +
                                                        documento.serie_factura + ' ' + documento.folio_factura +
                                                    '</td>' +
                                                    '<td>' +
                                                        '<input type="hidden" name="relations[has][relaciones]['+index+'][fk_id_tipo_relacion]" value="'+self.tipoRelacion.id_relacion+'">' +
                                                        "("+self.tipoRelacion.relacion+ ") " + self.tipoRelacion.descripcion +
                                                    '</td>'+
                                                    '</tr>'
                                                )
                                            });
                                        }
                                    });
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
                                $('#fk_id_factura_proveedor').empty();
                                $('#fk_id_tipo_relacion').prop('disabled',true);
                                $('#fk_id_factura_proveedor').prop('disabled',true);
                                $('#agregar_relacion').prop('disabled',true);
                                $.ajax({
                                    url: $('#fk_id_socio_negocio').data('url'),
                                    data: {
                                        param_js: facturas_js,
                                        $fk_id_socio_negocio: $('#fk_id_socio_negocio').val()
                                    },
                                    success: function (values) {
                                        $.each(values,function (index,value) {
                                            var serie = '';
                                            if(value.serie_factura) {
                                                serie = value.serie_factura;
                                            }
                                            var folio = '';
                                            if(value.folio_factura){
                                                folio = value.folio_factura;
                                            }
                                            $('#fk_id_factura_proveedor').append('<option value="'+value.id_documento+'">'+serie+'-'+folio+'</option>');
                                        });
                                        $('#fk_id_factura_proveedor').prepend('<option value="0" selected>Selecciona...</option>');
                                        $('#fk_id_tipo_relacion').removeAttr('disabled');
                                        $('#fk_id_factura_proveedor').removeAttr('disabled');
                                        $('#agregar_relacion').removeAttr('disabled');
                                    }
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

    $('#agregar_relacion').click(function (e) {
        if($('#fk_id_tipo_relacion').val() > 0 && $('#fk_id_factura_proveedor').val() > 0){
            var i = $('#relaciones tr').length;
            var index = i > 0 ? +$('#relaciones tr:last').find('#index').val() + 1 : 0;
            $('#relaciones').append(
                '<tr>' +
                '<td>' +
                    '<input type="hidden" id="index" value="'+index+'">' +
                    '<input type="hidden" name="relations[has][relaciones]['+index+'][fk_id_documento_relacionado]" value="'+$('#fk_id_factura_proveedor').val()+'">'+
                    '<input type="hidden" name="relations[has][relaciones]['+index+'][fk_id_tipo_documento_relacionado]" value="7">' +
                    '<input type="hidden" name="relations[has][relaciones]['+index+'][fk_id_tipo_documento]" value="11">' +
                    $('#fk_id_factura_proveedor').val()+
                '</td>'+
                '<td>'+$('#fk_id_factura_proveedor').select2('data')[0].text+'</td>' +
                '<td><input type="hidden" name="relations[has][relaciones]['+index+'][fk_id_tipo_relacion]" value="'+$('#fk_id_tipo_relacion').val()+'">'+$('#fk_id_tipo_relacion option:selected').text()+'</td>' +
                '<td><button class="btn is-icon text-primary bg-white" type="button" data-delay="50" onclick="borrarFila(this)"> <i class="material-icons">delete</i></button></td>' +
                '</tr>'
            );
            $.toaster({
                priority: 'success', title: 'Â¡Ã‰xito!', message: 'Se ha agregado una factura relacionada',
                settings: {'timeout': 10000, 'toaster': {'css': {'top': '5em'}}}
            });
        }else{
            $.toaster({
                priority: 'danger', title: 'Â¡Error!', message: 'Selecciona un tipo de relaciÃ³n y una factura',
                settings: {'timeout': 10000, 'toaster': {'css': {'top': '5em'}}}
            });
        }
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

function borrarFila(el) {
    $(el).parent().parent('tr').remove();
    $.toaster({priority:'success',title:'Â¡Correcto!',message:'Se ha eliminado la fila correctamente',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
}