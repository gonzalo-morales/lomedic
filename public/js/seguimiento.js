$(document).ready(function () {
    var data_empleado = $('#fk_id_empleado_comentario').data('url');
    $.ajax({
        type: 'GET',
        url: data_empleado,
        success: function (response) {
            $('#fk_id_empleado_comentario').val(response);
        }
    });
});

