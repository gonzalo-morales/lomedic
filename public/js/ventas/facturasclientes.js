$(document).ready(function () {
	$('#fk_id_cliente').on('change', function() {
		let proyecto = $('#fk_id_proyecto');

		if($(this).val() == '') {
			proyecto.val('');
		}
		else {
    		$.ajax({
    		    async: true,
    		    url: proyecto.data('url'),
    		    data: {'param_js':proyectos_js,$fk_id_cliente:$(this).val()},
    		    dataType: 'json',
                success: function (data) {
                	$("#fk_id_proyecto option").remove();
                	proyecto.append('<option value="" disabled>Selecciona una Opcion...</option>')
                    $.each(data, function(){
                    	proyecto.append('<option value="'+ this.id_proyecto +'">'+ this.proyecto +'</option>')
                    });
                	proyecto.val('');
                	proyecto.prop('disabled', (data.length == 0)); 
    		    }
    		});
		}
	});
	
	$('#fk_id_moneda').on('change', function() {
		$('#tipo_cambio').attr('readonly',$('#fk_id_moneda').val() == 100);
		
		if($('#fk_id_moneda').val() == 100)
			$('#tipo_cambio').val('1.00');
	});
});