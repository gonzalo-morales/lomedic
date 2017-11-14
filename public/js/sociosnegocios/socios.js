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
			from_picker.start().clear();
			to_picker.start().clear();
			to_picker.trigger('set');
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
});