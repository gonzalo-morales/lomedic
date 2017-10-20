$(document).ready(function () {
	var from_picker = $('#activo_desde').pickadate({ selectMonths: true, selectYears: 3, format: 'yyyy-mm-dd' }).pickadate('picker');
	var to_picker = $('#activo_hasta').pickadate({ selectMonths: true, selectYears: 3, format: 'yyyy-mm-dd' }).pickadate('picker');

	if(!$('#activo').prop('checked')) {
		from_picker.clear().stop();
		to_picker.clear().stop();
		$('#activo_desde').prop('readonly', true);
		$('#activo_hasta').prop('readonly', true);
	}

	$('#activo').click(function (e) {
		if(!$(this).prop('checked')) {
			from_picker.clear().stop();
			to_picker.clear().stop();
    		$('#activo_desde').prop('readonly', true);
    		$('#activo_hasta').prop('readonly', true);
		}
		else {
			from_picker.start().clear(); to_picker.trigger('set');
			//to_picker.start().clear();
		}
	});
	
	from_picker.on('set', function(event) {
		if ( 'select' in event ) {
			to_picker.start().clear().set('min', from_picker.get('select'));
	    }

	    if ( 'clear' in event ) {
	    	to_picker.clear().set('min', false).stop();
	    	$('#activo_hasta').prop('readonly', true);
		  }
	});
	
	$('#detallesku a[data-toggle="tab"]').click(function (e) {
		e.preventDefault()
	  	$(this).tab('show')
	});
	
	$('#fk_id_serie_sku').on('change', function() {
		$('#sku').prop('disabled', ($(this).val() != 1));

		if($(this).val() == 1 || $(this).val() == '') {
			$('#sku').val('');
		}
		else {
    		let url = $(this).data('url');
    
    		$.ajax({
    		    async: true,
    		    url: url.replace('?id',$(this).val()),
    		    dataType: 'json',
                success: function (data) {
                    var serie = data.prefijo+'-'+('000000' + data.numero_siguiente).slice(-6);
    				if(data.sufijo != null)
    				{	serie = serie+'-'+data.sufijo; }

    				$('#sku').val(serie);
    		    },
    		    error: function(){
    		    	$('#sku').val('');
    		    	alert('No es posible generar numero de serie. Verifique que la serie no este en su valor maximo.');
    	    	}
    		});
		}
	});
});