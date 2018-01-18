
    $(document).ready(function () {


        $('#fk_id_sucursal').select2();
        // $('#id_receta').select2();
        let token = document.querySelector("meta[name='csrf-token']").getAttribute("content");
        $('#fk_id_sucursal').on('change', function() {
            $.ajax({
                type: "POST",
                url: $(this).data('url'),
                data: {'fk_id_sucursal':$(this).val(),'_token':token},
                dataType: "json",
                success:function(data) {
                    $('#fk_id_requisicion_hospitalaria').empty();
                    $.each(data, function(key, value) {
                        $('#fk_id_requisicion_hospitalaria').append('<option value="'+ key +'">'+ value +'</option>');
                    });
                    $('#fk_id_requisicion_hospitalaria').val('');
                }
            });
        });
        $('#fk_id_requisicion_hospitalaria').on('change', function() {
            if (!$(this).is(":empty")) {
                $('#detalle tbody tr').remove();
                $.ajax({
                    type: "POST",
                    url: $('#fk_id_requisicion_hospitalaria').data('url'),
                    data: {'fk_id_requiscion':$(this).val(),'_token':token},
                    dataType: "json",
                    success:function(data) {
                        // console.info(data);
                        $.each(data, function(key,values) {
                            $('#detalle').append(
                                '<tr>' +
                                    '<td>'+values.clave_cliente_producto+'</td>'+
                                    '<td>'+values.descripcion+'</td>'+
                                    '<td>'+values.cantidad_solicitada+'</td>'+
                                    '<td class="cantidad_surtida">'+values.cantidad_surtida+'</td>'+
                                    '<td>'+values.cantidad_disponible+'</td>'+
                                    '<td><input type="number" onchange="calculatotal(this)" name="relations[has][detalles][' + key + '][cantidad_surtida]" min="0" max="'+(values.cantidad_solicitada - values.cantidad_surtida)+'" class="form-control cantidad"></td>'+
                                    '<td>$ '+parseFloat(values.precio_unitario, 10).toFixed(2)+'</td>'+
                                    '<td class="text-right total">$ '+parseFloat(0, 10).toFixed(2)+'</td>' +
                                    '<input type="hidden" name="relations[has][detalles][' + key + '][id_surtido_requisicion]"  value=""/> ' +
                                    '<input type="hidden" name="relations[has][detalles][' + key + '][fk_id_surtido_requisicion]"  value=""/> ' +
                                    '<input type="hidden" name="relations[has][detalles][' + key + '][fk_id_clave_cliente_producto]"  value="'+ values.fk_id_clave_cliente_producto +'"/> ' +
                                    '<input type="hidden" name="relations[has][detalles][' + key + '][cantidad_solicitada]"  value="'+ values.cantidad_solicitada +'"/> ' +
                                    '<input type="hidden" name="relations[has][detalles]['+ key +'][precio_unitario]" class="precio" value="'+ values.precio_unitario +'">'+
                                    '<input type="hidden" name="relations[has][detalles]['+ key +'][importe]" class="importe" value="'+ values.precio_unitario +'">'+

                                '</tr>');
                        })
                    }
                });
            }
        });


        // $('#fk_id_sucursal').trigger("change");
    });
function calculatotal(el) {
    var cantidad = $(el).val();
    var precio = $(el).parent().parent().find('.precio').val();
    var cantidad_surtida = $(el).parent().parent().find('.cantidad_surtida').html();
    var cantidad_total = parseInt(cantidad_surtida)+parseInt(cantidad);

    // cantidad_total =
    console.info(cantidad_total*precio);
    $(el).parent().parent().find('.importe').val(cantidad_total*precio);
    $(el).parent().parent().find('.total').html('$ '+parseFloat((cantidad_total*precio), 10).toFixed(2));

    var total =  0;
    $('.importe').each(function (i) {
        console.info(total);
        total += cantidad_total*precio;
    });

    $('#total').html('$ '+parseFloat(total, 10).toFixed(2));
};
