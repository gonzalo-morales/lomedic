/**
 * Created by ihernandezt on 12/10/2017.
 */


$('select[name="id_sucursal"]').on('change', function() {
    var id_sucursal = $(this).val();
    if(id_sucursal) {
        $.ajax({
            type: "POST",
            url: $("#id_sucursal").data('url'),
            data: 'id_sucursal='+id_sucursal,
            dataType: "json",
            success:function(data) {
                $('select[name="id_orden"]').empty();
                $.each(data, function(index,id_orden) {
                    $('select[name="id_orden"]').append('<option value="'+ id_orden +'">'+ id_orden +'</option>');
                });
            }
        });

    }
    else
    {
        $('select[name="id_orden"]').empty();
    }
});

$('select[name="id_orden"]').on('change', function() {
    var id_orden = $(this).val();
    var id_sucursal = $('#id_sucursal').val();
    alert($("#id_orden").data('url'));

    if(id_orden) {
        $.ajax({
            type: "POST",
            url: $("#id_orden").data('url'),
            data: {'id_orden':id_orden,
                'fk_id_sucursal':id_sucursal},
            dataType: "json",
            success:function(data) {
                console.info(data);
                // $.each(data, function(index,id_orden) {
                //     $('select[name="id_orden"]').append('<option value="'+ id_orden +'">'+ id_orden +'</option>');
                // });
            }
        });

    }
});