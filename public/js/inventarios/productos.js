$(document).ready(function () {
	//Inicializar tabla
    window.dataTable = new DataTable('#upcs', {
    	header: true,
        fixedHeight: true,
        fixedColumns: false,
        searchable: false,
        perPageSelect: false,
        labels:{info:""},
    });
    
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
	
	$('#cantidad').on('change', function() {
		var oldvalue = this.old;
		var newvalue = this.value;
		
		console.log(oldvalue+ ' '+newvalue);
	});

    $('#agrega-detalle').on('click', function() {
    	var upcs_ids = [];
    	$('.id_upc').each(function (i) {
    		upcs_ids.push($('.id_upc')[i].value); 
		});
    	
    	let row_id = dataTable.activeRows.length + 1;
		
    	id_upc = $('#fk_id_upc option:selected').val();
    	cantidad = $('#cantidad').val();
        text_upc = $('#fk_id_upc option:selected').text();
        
        
        if(id_upc == '' ){
        	$.toaster({priority:'danger',title:'¡Error!',message:'Debe seleccionar un upc.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
        }
        else if(cantidad == '' | Number.isInteger(cantidad) != false) {
        	$.toaster({priority:'danger',title:'¡Error!',message:'Debe seleccionar la cantidad, esta debe ser numero entero.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
        }
        else if(upcs_ids.indexOf(id_upc) !== -1) {
        	$.toaster({priority:'danger',title:'¡Error!',message:'El upc seleccionado ya fue agregado.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
        }
        else {
	        var url = $('#fk_id_upc').data('url');
	        var data_upc = null;
	        $.ajax({
	            type: "GET",
	            url: url,
	            data: {param_js,$id_upc:id_upc},
	            dataType: "json",
	            success: function (data) {
	            	 dataTable.insert( {
	            		 data:[
	            			 '<input type="hidden" class="id_upc" name="detalles['+row_id+'][fk_id_upc]" value="' + id_upc + '" /> '+text_upc,
	                         data[0].nombre_comercial,
	                         data[0].descripcion,
	                         data[0].laboratorio.laboratorio,
	                         '<input type="hidden" name="detalles['+row_id+'][cantidad]" value="' + cantidad + '" /> '+cantidad,
	                         '<button class="btn is-icon text-primary bg-white" type="button" data-delay="50" onclick="borrarFila(this)"> <i class="material-icons">delete</i></button>'
	            		 ]
	            	 })
	            }
	        });
        }
	});
});
    
function borrarFila(el) {
    let fila = dataTable.data[$(el).parents('tr').index()];
    dataTable.rows().remove([$(el).parents('tr').index()]);
        $.toaster({priority:'success',title:'¡Advertencia!',message:'Se ha eliminado la fila correctamente',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
}