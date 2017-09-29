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
	
	$('#fk_id_categoria').on('change', function(){
        let url = $(this).data('url');

        $('#fk_id_subcategoria').empty();
        $('#fk_id_accion').empty();
        $('#fk_id_subcategoria').prop('disabled',!$('option:selected', this).val());
        $('#fk_id_accion').prop('disabled',!$('#fk_id_subcategoria option:selected').val());

        $.ajax({
            url: url.replace('?id', $('option:selected', this).val()),
            dataType: 'json',
            success: function (data) {
            	var options = new Option('Selecciona una subcategoria', '', true, false);
                $('#fk_id_subcategoria').prepend(options);
                $('#fk_id_subcategoria option:selected').prop('disabled', true);
                $.each(data, function (key, data) {
                	var option = new Option(data.subcategoria, data.id_subcategoria, false, false);
                	$('#fk_id_subcategoria').append(option);
                });
            },
            error: function () {
                alert('error');
            }
        });
    });
    
    //Carga acciones segun al elegir una subcategoria
    $('#fk_id_subcategoria').on('change', function(){
        let url = $(this).data('url');

        $('#fk_id_accion').empty();
        $('#fk_id_accion').prop('disabled',!$('option:selected', this).val());

        $.ajax({
            url: url.replace('?id', $('option:selected', this).val()),
            dataType: 'json',
            success: function (data) {
            	var options = new Option('Selecciona una accion', '', true, false);
                $('#fk_id_accion').prepend(options);
                $('#fk_id_accion option:selected').prop('disabled', true);
                $.each(data, function (key, data) {
                	var option = new Option(data.accion, data.id_accion, false, false);
                	$('#fk_id_accion').append(option);
                });
            },
            error: function () {
                alert('error');
            }
        });
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