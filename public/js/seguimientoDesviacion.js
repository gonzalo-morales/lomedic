$(document).ready(function () {
    console.log($("#id_documento").data('url'));
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
    $("#id_documento").select2({
        minimumInputLength:2,
        theme: "bootstrap",
        ajax:{
            url: $("#id_documento").data('url'),
            dataType: 'json',
            type: "POST",
            data: function (params) {
                return {
                    term: params.term,
                    fk_id_proveedor: $("#fk_id_proveedor").val(),
                    id_tipo_documento: $("#id_tipo_documento").val()
                };
            },
            processResults: function (data) {
                return {results: data}
            }
        }
    });

    $("#id_documento").on('change',function(e){
        console.log(e);
        // let token = document.querySelector("meta[name='csrf-token']").getAttribute("content");
        $.ajax({
            url: $('#id_documento').data('url-desviaciones'),
            method:'POST',
            data: {
                'id_documento':$(this).val(),
                'id_tipo_documento':$("#id_tipo_documento").val(),
                'fk_id_proveedor':$("#fk_id_proveedor").val(),
                // '_token':token
            },
            dataType: "json",
            success:function(data){
                console.info(data);
                /*if(data != '')
                {
                    var detalle_entrada = '';
                    var estado_producto = '';

                    $.each(data.detalle_documento, function(index) {
                        var sku = data.detalle_entrada[index].sku;
                        var id_sku = data.detalle_entrada[index].id_sku;
                        var sku_descripcion = data.detalle_entrada[index].sku_descripcion;
                        var upc = data.detalle_entrada[index].upc;
                        var id_upc = data.detalle_entrada[index].id_upc;
                        if( cantidad_surtida < cantidad)
                        {

                            detalle_entrada = detalle_entrada + '<tr>' +
                                '<td>'+sku+'</td>' +
                                '<td>'+upc+'</td>' +
                                '<td>'+nombre_proyecto+'</td>' +
                                '<td><input type="text" id="'+tipo_documento+'_'+numero_documento+'_'+id_detalle_documento+'_surtida" name="datos_entradas['+index+'][surtida]"  value="'+cantidad_surtida+'" style="max-width:6em;" disabled></td>' +
                                '<td><input type="text" id="'+tipo_documento+'_'+numero_documento+'_'+id_detalle_documento+'_precioUnitario" name="datos_orden['+index+'][precio_unitario]"  value="'+precio_unitario+'" disabled style="max-width:6em;"></td>' +
                                '<input type="hidden" name="datos_entradas['+index+'][id_sku]" value="'+id_sku+'" >' +
                                '<input type="hidden" name="datos_entradas['+index+'][id_upc]" value="'+id_upc+'" >' +
                                '</tr>';
                        }
                        else
                        {
                            return true;
                        }
                    });

                    $('#detalle_entrada').append('<div role="tabpanel" class="tab-pane fade in" id="'+tipo_documento+'_'+numero_documento+'">' );
                }*/
            }
        });
    });

    //    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
    //    var url_getDocumento = $('#fk_id_proveedor').data('url').replace('?id',$('#fk_id_proveedor').val());
    //    $.ajax({
    //        url:url_getDocumento,
    //        type:'POST',
    //        data:{
    //            tipoDocumento:$('#tipo_documento').val()
    //        },
    //        success:function (data) {
    //            console.log(data);
    //         //    if(data.status == 1){
    //         //        $('#autorizacion').modal('toggle');
    //         //        $.toaster({
    //         //            priority: 'success', title: 'Éxito', message: 'Se ha actualizado la información de la autorización',
    //         //            settings: {'timeout': 5000, 'toaster': {'css': {'top': '5em'}}}
    //         //        });
    //         //    }else{
    //         //        $.toaster({
    //         //            priority: 'danger', title: 'Error', message: 'Ha ocurrido un error',
    //         //            settings: {'timeout': 5000, 'toaster': {'css': {'top': '5em'}}}
    //         //        });
    //         //    }
    //        }
    //    });

});
