$(document).ready(function () {
	//Estatus en al visualizar ticket
	$('#estatus label').bind('click', function(){
		$('#estatus label').removeClass(" btn-info btn-success btn-danger btn-primary").addClass('btn-secondary');
		$(this).addClass($(this).data('url')).removeClass('btn-secondary');
	});
	
	//Estatus para reabrir un ticket solucionado o cancelado
	$('#id_estatus label').bind('click', function(){
		if (this.classList.contains('active')) {
			$('#id_estatus label').removeClass("btn-success").addClass('btn-secondary');
			$('#id_estatus label span').text("No");
		}
		else {
			$('#id_estatus label').removeClass("btn-secondary").addClass('btn-success');
			$('#id_estatus label span').text("Si");
		}
	});

    var data_empleado = $('#fk_id_empleado_comentario').data('url');
    $.ajax({
        type: 'GET',
        url: data_empleado,
        success: function (response) {
            $('#fk_id_empleado_comentario').val(response);
        }
    });
});