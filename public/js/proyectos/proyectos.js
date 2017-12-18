$(document).ready(function () {
	var from_picker = $('#fecha_inicio').pickadate({ selectMonths: true, selectYears: 3, format: 'yyyy-mm-dd' }).pickadate('picker');
	var to_picker = $('#fecha_terminacion').pickadate({ selectMonths: true, selectYears: 3, format: 'yyyy-mm-dd' }).pickadate('picker');
	
	from_picker.on('set', function(event) {
		if ( 'select' in event ) {
			to_picker.start().clear().set('min', from_picker.get('select'));
	    }

	    if ( 'clear' in event ) {
	    	to_picker.clear().set('min', false).stop();
	    	$('#fecha_terminacion').prop('readonly', true);
		  }
	});
	
	var from_contrato = $('#fecha_inicio_contrato').pickadate({ selectMonths: true, selectYears: 3, format: 'yyyy-mm-dd' }).pickadate('picker');
	var to_contrato = $('#fecha_fin_contrato').pickadate({ selectMonths: true, selectYears: 3, format: 'yyyy-mm-dd' }).pickadate('picker');
	
	from_contrato.on('set', function(event) {
		if ( 'select' in event ) {
			to_contrato.start().clear().set('min', from_contrato.get('select'));
	    }

	    if ( 'clear' in event ) {
	    	to_contrato.clear().set('min', false).stop();
	    	$('#fecha_fin_contrato').prop('readonly', true);
		  }
	});
	
    $(".nav-link").click(function (e) {
        e.preventDefault();
        $('#clothing-nav li').each(function () {
            $(this).children().removeClass('active');
        });
        $('.tab-pane').removeClass('active').removeClass('show');
        $(this).addClass('active');
        var tab = $(this).prop('href');
        tab = tab.split('#');
        $('#' + tab[1]).addClass('active').addClass('show');
    });
    
    $('#agregarContrato').on('click', function() {
    	var i = $('#detalleContratos tr').length;
        var row_id = i > 0 ? +$('#detalleContratos tr:last').find('.index').val()+1 : 0;
		
        representante = $('#representante_legal').val();
        contrato = $('#num_contrato').val();
        fechainicio = $('#fecha_inicio').val();
        fechafin = $('#fecha_fin').val();
		archivo = $("#contrato").prop('files');
		
		if(representante == '' | contrato == '' | fechainicio == '' | fechafin == '' | $("#archivo").length == 0) {
			$.toaster({priority:'danger',title:'Ãƒâ€šÃ‚Â¡Error!',message:'Debe introducir el representante legal, No. contrato, fecha inicio, fecha fin y archivo.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
		}
		else {
			$('#detalleContratos').append('<tr>'+
				'<td><input class="index" name="relations[has][contratos]['+row_id+'][index]" type="hidden" value="'+row_id+'">'+
				    '<input name="relations[has][contratos]['+row_id+'][representante_legal]" type="hidden" value="'+representante+'">'+representante+'</td>'+
				'<td><input name="relations[has][contratos]['+row_id+'][num_contrato]" type="hidden" value="'+contrato+'">'+contrato+'</td>'+
				'<td><input name="relations[has][contratos]['+row_id+'][fecha_inicio]" type="hidden" value="'+fechainicio+'">'+fechainicio+'</td>'+
				'<td><input name="relations[has][contratos]['+row_id+'][fecha_fin]" type="hidden" value="'+fechafin+'">'+fechafin+'</td>'+
				'<td><input name="relations[has][contratos]['+row_id+'][file]" type="file" id="fileContrato-'+row_id+'" style="display:none">'+archivo[0].name+'</td>'+
				'<td><button class="btn is-icon text-primary bg-white" type="button" data-delay="50" onclick="borrarFila(this)" data-tooltip="Anexo"> <i class="material-icons">delete</i></button></td>'+
			'</tr>');
			$('#fileContrato-'+row_id).prop('files',archivo);
			$.toaster({priority:'success',title:'Ãƒâ€šÃ‚Â¡Correcto!',message:'El archivo se agrego correctamente.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
		}
	});
    
    $('#agregarAnexo').on('click', function() {
    	var i = $('#detalleAnexos tr').length;
        var row_id = i > 0 ? +$('#detalleAnexos tr:last').find('#index').val()+1 : 0;
		
		nombre = $('#nombre_archivo').val();
		archivo = $("#archivo").prop('files');
		
		if(nombre == '') {
			$.toaster({priority:'danger',title:'Ãƒâ€šÃ‚Â¡Error!',message:'Debe introducir el nombre para el documento.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
		}
		else if($("#archivo").length == 0) {
			$.toaster({priority:'danger',title:'Ãƒâ€šÃ‚Â¡Error!',message:'Selecciona un archivo.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
		}
		else {
			$('#detalleAnexos').append('<tr>'+
				'<td><input class="index" name="relations[has][anexos]['+row_id+'][index]" type="hidden" value="'+row_id+'">'+
					'<input name="relations[has][anexos]['+row_id+'][nombre]" type="hidden" value="'+nombre+'">'+nombre+'</td>'+
				'<td><input name="relations[has][anexos]['+row_id+'][file]" type="file" id="fileAnexo-'+row_id+'" style="display:none">'+archivo[0].name+'</td>'+
				'<td><button class="btn is-icon text-primary bg-white" type="button" data-delay="50" onclick="borrarFila(this)" data-tooltip="Anexo"> <i class="material-icons">delete</i></button></td>'+
			'</tr>');
			$('#fileAnexo-'+row_id).prop('files',archivo);
			$.toaster({priority:'success',title:'Â¡Correcto!',message:'El archivo se agrego correctamente.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
		}
	});
});

function borrarFila(el) {
    $(el).parent().parent('tr').remove();
    $.toaster({priority:'success',title:'Â¡Correcto!',message:'Se ha eliminado correctamente el '+$(el).data('tooltip'),settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
}