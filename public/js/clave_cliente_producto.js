$(document).ready(function () {
    $("#fk_id_clave_producto_servicio").select2({
        minimumInputLength:3,
        ajax:{
            url: $('#fk_id_clave_producto_servicio').data('url'),
            dataType: 'json',
            data: function (params) {
                return {
                    'param_js':clave_producto_servicio_js,
                    $term: params.term
                };
            },
            processResults: function (data) {
                return {results: data}
            }
        }
    });

    $("#fk_id_clave_unidad").select2({
        minimumInputLength:2,
        ajax:{
            url: $('#fk_id_clave_unidad').data('url'),
            dataType: 'json',
            data: function (params) {
                return {
                    'param_js':clave_unidad_js,
                    $term: params.term
                };
            },
            processResults: function (data) {
                return {results: data}
            }
        }
    });

    $('#fk_id_sku').change(function () {
       $.ajax({
           url:$(this).data('url'),
           data:{
               'param_js':upcs_js,
               $fk_id_sku:$(this).val()
           },
           dataType:'JSON',
           success:function (data) {
               $('#fk_id_upc').empty().prepend('<option selected disabled value="0">...</option>>').select2({
                   data:data
               });
           }
       });
    });

    $('#fk_id_upc').change(function () {
        $.ajax({
            url:$(this).data('url'),
            data:{
                'param_js':cantidad_upc_js,
                $fk_id_upc:$(this).val(),
                $fk_id_sku:$('#fk_id_sku').val()
            },
            dataType:'JSON',
            success:function (data) {
                var sku = $('#fk_id_sku').val();
                $.each(data,function (index,value) {
                    if(+value.fk_id_sku == +sku){
                        $('#disponibilidad').val(value.cantidad);
                        return false;
                    }
                });
            }
        });
    });
});