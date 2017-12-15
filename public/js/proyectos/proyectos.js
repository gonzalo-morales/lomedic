$(document).ready(function () {
	$('.datepicker').pickadate({
		selectMonths: true, // Creates a dropdown to control month
		selectYears: 2, // Creates a dropdown of 2 years to control year
		min: new Date(2017,0,1),//Primero de enero del 2017
		format: 'yyyy-mm-dd'
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
			$.toaster({priority:'danger',title:'Ã‚Â¡Error!',message:'Debe introducir el representante legal, No. contrato, fecha inicio, fecha fin y archivo.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
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
			$.toaster({priority:'success',title:'Ã‚Â¡Correcto!',message:'El archivo se agrego correctamente.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
		}
	});
    
    $('#agregarAnexo').on('click', function() {
    	var i = $('#detalleAnexos tr').length;
        var row_id = i > 0 ? +$('#detalleAnexos tr:last').find('#index').val()+1 : 0;
		
		nombre = $('#nombre_archivo').val();
		archivo = $("#archivo").prop('files');
		
		if(nombre == '') {
			$.toaster({priority:'danger',title:'Ã‚Â¡Error!',message:'Debe introducir el nombre para el documento.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
		}
		else if($("#archivo").length == 0) {
			$.toaster({priority:'danger',title:'Ã‚Â¡Error!',message:'Selecciona un archivo.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
		}
		else {
			$('#detalleAnexos').append('<tr>'+
				'<td><input class="index" name="relations[has][anexos]['+row_id+'][index]" type="hidden" value="'+row_id+'">'+
					'<input name="relations[has][anexos]['+row_id+'][nombre]" type="hidden" value="'+nombre+'">'+nombre+'</td>'+
				'<td><input name="relations[has][anexos]['+row_id+'][file]" type="file" id="fileAnexo-'+row_id+'" style="display:none">'+archivo[0].name+'</td>'+
				'<td><button class="btn is-icon text-primary bg-white" type="button" data-delay="50" onclick="borrarFila(this)" data-tooltip="Anexo"> <i class="material-icons">delete</i></button></td>'+
			'</tr>');
			$('#fileAnexo-'+row_id).prop('files',archivo);
			$.toaster({priority:'success',title:'¡Correcto!',message:'El archivo se agrego correctamente.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
		}
	});
});

function borrarFila(el) {
    $(el).parent().parent('tr').remove();
    $.toaster({priority:'success',title:'¡Correcto!',message:'Se ha eliminado correctamente el '+$(el).data('tooltip'),settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
}