$(document).ready(function () {
    activar_empleado();
    $('select').material_select();

    var data_empleados = $('#empleado_solicitud').data('url');
    $.ajax({
       type:'GET',
       url: data_empleados,
       success: function (response) {
           $('#empleado_solicitud').autocomplete2({
               data: response
           });

       },
    });
    $('#empleado_solicitud').on('change',function () {
        $('#nombre_solicitante').val($('#empleado_solicitud').val());
    });

    var data_sucursales = $('#sucursal').data('url');
    $.ajax({
        type:'GET',
        url: data_sucursales,
        success: function (response) {
            $('#sucursal').autocomplete2({
                data: response
            })
        }
    });
    $('#sucursal').on('change',function () {
        $('#fk_id_sucursal').val($('#sucursal').data('id'));
    });

    $('#fk_id_categoria').on('change', function(){
    	var data = $(this).data('url');
        
        $('#fk_id_accion option').remove();
        $('#fk_id_subcategoria option').remove();
        $('#fk_id_subcategoria').prop('disabled',true);
        $('#fk_id_accion').prop('disabled',true);
        
        
        
        $.ajax({
        	url: data.replace('?id', $('option:selected', this).val()),
            dataType: 'json',
            success: function (data) {
                $.each(data, function (key, subcategoria) {
                    var option = $('<option/>');
                    option.val(subcategoria.id_subcategoria);
                    option.text(subcategoria.subcategoria);

                    $('#fk_id_subcategoria').append(option);

                });
                var $dis = Object.keys(data).length === 0;
                $('#fk_id_subcategoria').prop('disabled',$dis);
                $('select').material_select();
            },
            error: function () {
                alert('error');
            }
        });

    });

    $('#fk_id_subcategoria').on('change', function(){
    	var data = $(this).data('url');

        $.ajax({
            url: data.replace('?id', $('option:selected', this).val()),
            dataType: 'json',
            success: function (data) {

                $('#fk_id_accion option').remove();

                $.each(data, function (key, accion) {

                    var option = $('<option/>');
                    option.val(accion.id_accion);
                    option.text(accion.accion);

                    $('#fk_id_accion').append(option);
                });
                var $dis = Object.keys(data).length === 0;
                $('#fk_id_accion').prop('disabled',$dis);
                $('select').material_select();
            },
            error: function () {
                alert('error');
            }
        });
    });

});

function activar_empleado(){

    if ($('#otherUser').prop('checked') == true)
        {
            $('#empleado_solicitud').prop('disabled',false);
            // var data_empleado = $('#forMe1').data('url');
            // $.ajax({
            //     type:'GET',
            //     url: data_empleado,
            //     success: function (response) {
            //         $('#nombre_solicitante').val(response);
            //     }
            // });
        }
    else
    {
        $('#empleado_solicitud').prop('disabled',true);
        $('#empleado_solicitud').val('');
        $('#nombre_solicitante').val('');
    }
}

