var a=[];
// Inicializar los datepicker para las fechas necesarias
$('.datepicker').pickadate({
    selectMonths: true, // Creates a dropdown to control month
    selectYears: 2, // Creates a dropdown of 2 years to control year
    min: new Date(2017,0,1),//Primero de enero del 2017
    format: 'yyyy-mm-dd'
});

$(document).ready(function () {
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
    
    $('#agregar-anexo').on('click', function() {
		let row_id = $('#tAnexos tr').length;
		
		nombre = $('#nombre_archivo').val();
		archivo = $("#archivo").prop('files');
		
		if(nombre == '') {
			$.toaster({priority:'danger',title:'Ãƒâ€šÃ‚Â¡Error!',message:'Debe introducir el nombre para el documento.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
		}
		else if($("#archivo").length == 0) {
			$.toaster({priority:'danger',title:'Ãƒâ€šÃ‚Â¡Error!',message:'Selecciona un archivo.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
		}
		else {
			$('#tAnexos').append('<tr>'+
				'<td>' + nombre+' <input name="anexos['+row_id+'][nombre]" type="hidden" value="'+nombre+'"></td>'+
				'<td>' + archivo[0].name + ' <input id="anexos-'+row_id+'" class="file-anexos" name="anexos['+row_id+'][archivo]" type="file" style="display:none"></td>'+
				'<td><button class="btn is-icon text-primary bg-white" type="button" data-delay="50" onclick="borrarAnexo(this)"> <i class="material-icons">delete</i></button></td>'+
			'</tr>');
			$('#anexos-'+row_id).prop('files',archivo);
			$.toaster({priority:'success',title:'Ãƒâ€šÃ‚Â¡Correcto!',message:'El archivo se agrego correctamente.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
		}
	});
});

function borrarAnexo(el) {
    $(el).parent().parent('tr').remove();
    $.toaster({priority:'success',title:'Ãƒâ€šÃ‚Â¡Correcto!',message:'Se ha eliminado el anexo correctamente',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
}