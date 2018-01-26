$(document).ready(function () {


    $('input[type=radio][name=fk_id_estatus]').change(function () {
        if($(this).val() == 4){//Si es autorizada
            $('#observaciones').attr('readonly','readonly');
            $('#observaciones').empty();
        }else{
            $('#observaciones').removeAttr('readonly');
        }
    });

    /*console.log($("#id_documento").data('url'));
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
                // console.info(data);
                $(".desviaciones").html('');
                if(data != '')
                {
                    var desviaciones
                    console.log(data);
                    $.each(data, function(index) {
                        console.log($("#tDesviaciones").data('url'));
                        // console.log(index);
                        // console.log(data[index].fecha_captura);
                        // console.log(data[index].id_seguimiento_desviacion);
                        // console.log(data[index].tipo);
                            desviaciones = desviaciones + '<tr>' +
                                '<td>'+data[index].id_seguimiento_desviacion+'</td>' +
                                '<td>'+data[index].serie_factura+'</td>' +
                                '<td>'+data[index].folio_factura+'</td>' +
                                '<td>'+data[index].fecha_captura+'</td>' +
                                '<td>'+data[index].tipo+'</td>' +
                                '<td>'+'<a class="showDetalleDesviacion btn is-icon" data-toggle="tooltip" data-id_seguimiento_desviacion="'+data[index].id_seguimiento_desviacion+'" title="" href="" data-original-title="Ver"><i class="material-icons">visibility</i></a>'+'</td>' +
                                // '<td><input type="text" id="'+tipo_documento+'_'+numero_documento+'_'+id_detalle_documento+'_surtida" name="datos_entradas['+index+'][surtida]"  value="'+cantidad_surtida+'" style="max-width:6em;" disabled></td>' +
                                // '<td><input type="text" id="'+tipo_documento+'_'+numero_documento+'_'+id_detalle_documento+'_precioUnitario" name="datos_orden['+index+'][precio_unitario]"  value="'+precio_unitario+'" disabled style="max-width:6em;"></td>' +
                                // '<input type="hidden" name="datos_entradas['+index+'][id_sku]" value="'+id_sku+'" >' +
                                // '<input type="hidden" name="datos_entradas['+index+'][id_upc]" value="'+id_upc+'" >' +
                                '</tr>';

                    });
                    $(".desviaciones").append(desviaciones);

                }
            }
        });
    });*/

    $('.desviacion').click(function (e) {
        console.log(e);
        console.log($(this).parent().parent().find('td input:first').val());
        console.log($(this).parent().parent().find('td input:first').next('input').val());
        // $('#motivo_autorizacion').val($(this).parent().parent().find('td:first-child').text());
        // $('#fk_id_estatus\\ ').prop('checked',true);
        // $('#id_autorizacion').val($(this).parent().parent().find('td input:first').val());
        $('#observaciones').val($(this).parent().parent().find('td input:first').next('input').val());
        // console.log("------------>"+$(this).parent().parent().find('td input:first').val());

        if($(this).parent().parent().find('td input:first').val() == 3){
            $('#fk_id_estatus\\ 3').prop('checked',true);
            console.log("------------>"+$(this).parent().parent().find('td input:first').val());
        }else if($(this).parent().parent().find('td input:first').val() == 4){
            console.log("------------>"+$(this).parent().parent().find('td input:first').val());
            $('#fk_id_estatus\\ 4').prop('checked',true);
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
            // console.log($('#id_detalle_seguimiento_desviacion').data('url'));
           var autorizar_url = $('#id_detalle_seguimiento_desviacion').data('url');
        //    console.log(autorizar_url);
        //    console.log($('#id_detalle_seguimiento_desviacion').val());
           $.ajax({
               url:autorizar_url,
               type:'POST',
               data:{
                   observaciones:$('#observaciones').val(),
                   fk_id_estatus:$('input[type=radio][name=fk_id_estatus]:checked').val(),
                   id_detalle_seguimiento_desviacion : $('#id_detalle_seguimiento_desviacion').val(),
               },
               dataType: 'json',
               success:function (data) {
                   console.log(data.status);
                   if(data.status == '1'){
                       $('#autorizacion').modal('toggle');
                       $.toaster({
                           priority: 'success', title: 'Éxito', message: 'Se ha actualizado la información de la autorización',
                           settings: {'timeout': 5000, 'toaster': {'css': {'top': '5em'}}}
                       });
                   }else{
                       $.toaster({
                           priority: 'danger', title: 'Error', message: 'Ha ocurrido un error',
                           settings: {'timeout': 5000, 'toaster': {'css': {'top': '5em'}}}
                       });
                   }
                   window.location.reload(true);
               }
           });
       }
    });


    // $('.showDetalleDesviacion').on('click',function(e){
    /*$(document).on('click','.showDetalleDesviacion',function(e){
        e.preventDefault();
        // console.log($(this).data('id_seguimiento_desviacion'));
        $("#showDetalleDesviacion").modal('show');
        // $.ajax({
        //       url:url_getDocumento,
        //       type:'POST',
        //       data:{
        //           tipoDocumento:$('#tipo_documento').val()
        //       },
        //       success:function (data) {
        //           console.log(data);
        //       }
        //   });

    });*/

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

function autorizar(obj){
    console.log($(obj).data('item-id'));
    $("#id_detalle_seguimiento_desviacion").val($(obj).data('item-id'));
    $("#autorizacion").modal('show');
}
