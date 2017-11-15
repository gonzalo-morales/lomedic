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
	
	window.dataTable = new DataTable('#Cuentas', {
    	header: true,
        fixedHeight: true,
        fixedColumns: false,
        searchable: false,
        perPageSelect: false,
        labels:{info:""},
    });
	
	$('#agrega-cuenta').on('click', function() {
    	
		var cuentas = [];
    	$('.uniquekey').each(function (i) {
    		cuentas.push($('.uniquekey')[i].value); 
		});
    	
    	let row_id = dataTable.activeRows.length + 1;
		
    	id_banco  = $('#fk_id_banco option:selected').val();
    	no_cuenta = $('#no_cuenta').val();
        
    	if(no_cuenta == '' | Number.isInteger(no_cuenta) != false) {
    		$.toaster({priority:'danger',title:'¡Error!',message:'Debe introducir el numero de cuenta, esta debe ser numero entero.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
    	}
    	else if(id_banco == '' ){
        	$.toaster({priority:'danger',title:'¡Error!',message:'Debe seleccionar un banco.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
        }
        else if(cuentas.indexOf(id_banco+'-'+no_cuenta) !== -1) {
        	$.toaster({priority:'danger',title:'¡Error!',message:'La cuenta que trata de agregar ya existe.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
        }
        else {
	        var url = $('#fk_id_banco').data('url');
	        $.ajax({
	            type: "GET",
	            url: url,
	            data: {param_js,$id_banco:id_banco},
	            dataType: "json",
	            success: function (data) {
	            	 dataTable.insert( {
	            		 data:[
	            			 no_cuenta+' <input name="cuentas['+row_id+'][no_cuenta]" type="hidden" value="'+no_cuenta+'">',
	            			 
	            			 data[0].banco +
	            			 '<input class="id_cuenta" name="cuentas['+row_id+'][id_cuenta]" type="hidden" value="">'+
	            			 '<input class="fk_id_banco" name="cuentas['+row_id+'][fk_id_banco]" type="hidden" value="'+id_banco+'">'+
	            			 '<input class="fk_id_socio_negocio" name="cuentas['+row_id+'][fk_id_socio_negocio]" type="hidden" value="">'+
	            			 '<input class="uniquekey" name="cuentas['+row_id+'][uniquekey]" type="hidden" value="'+id_banco+'-'+no_cuenta+'">',
	                         
	                         '<button class="btn is-icon text-primary bg-white" type="button" data-delay="50" onclick="borrarFila(this)"> <i class="material-icons">delete</i></button>'
	            		 ]
	            	 }),
	            	 $.toaster({priority:'success',title:'¡Correcto!',message:'La cuenta se agrego correctamente.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
	            },
	            error: function () {
	            	$.toaster({priority:'danger',title:'¡Error!',message:'Algo salio mal :( Revisa que los datos sean correctos.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
	            }
	        });
        }
	});
});

function borrarFila(el) {
    let fila = dataTable.data[$(el).parents('tr').index()];
    dataTable.rows().remove([$(el).parents('tr').index()]);
        $.toaster({priority:'success',title:'¡Correcto!',message:'Se ha eliminado la fila correctamente',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
}