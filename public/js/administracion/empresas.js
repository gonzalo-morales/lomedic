$(document).ready(function () {
	var from_picker = $('#fecha_expedicion').pickadate({ selectMonths: true, selectYears: 3, format: 'yyyy-mm-dd' }).pickadate('picker');
	var to_picker = $('#fecha_vencimiento').pickadate({ selectMonths: true, selectYears: 3, format: 'yyyy-mm-dd' }).pickadate('picker');

	from_picker.on('set', function(event) {
		if ( 'select' in event ) {
			to_picker.start().clear().set('min', from_picker.get('select'));
	    }

	    if ( 'clear' in event ) {
	    	to_picker.clear().set('min', false).stop();
	    	$('#fecha_vencimiento').prop('readonly', true);
		  }
	});
	
	$('#fk_id_pais').on('change', function() {
		var estado = $('#fk_id_estado');
		
		if(!$(this).val()) {
			estado.val('');
		}
		else {
    		$.ajax({
    		    async: true,
    		    url: estado.data('url'),
    		    data: {'param_js':estados_js,$fk_id_pais:$(this).val()},
    		    dataType: 'json',
                success: function (data) {
                	$("#fk_id_estado option").remove();
                	estado.append('<option value="" disabled>Selecciona una Opcion...</option>')
                    $.each(data, function(){
                    	estado.append('<option value="'+ this.id_estado +'">'+ this.estado +'</option>')
                    });
            		estado.val('');
            		estado.prop('disabled', (data.length == 0)); 
    		    }
    		});
		}
	}).trigger('change');
	
	$('#fk_id_estado').on('change', function() {
		var municipio = $('#fk_id_municipio');

		if(!$(this).val()) {
			municipio.val('');
		}
		else {
    		$.ajax({
    		    async: true,
    		    url: municipio.data('url'),
    		    data: {'param_js':municipios_js,$fk_id_estado:$(this).val()},
    		    dataType: 'json',
                success: function (data) {
                	$("#fk_id_municipio option").remove();
                	municipio.append('<option value="" disabled>Selecciona una Opcion...</option>')
                    $.each(data, function(){
                    	municipio.append('<option value="'+ this.id_municipio +'">'+ this.municipio +'</option>')
                    });
                	municipio.val('');
                	municipio.prop('disabled', (data.length == 0)); 
    		    }
    		});
		}
	}).trigger('change');
	
	
	$('#agregar-certificado').on('click', function() {
		let row_id = $('#tCertificados tr').length;
		
		key         = $("#file_key").prop('files');
		certificado = $("#file_certificado").prop('files');
		expedicion  = $('#fecha_expedicion').val();
		vencimiento = $('#fecha_vencimiento').val();
		
		if($("#certificado").length == 0 | expedicion == '' | vencimiento == '') {
			$.toaster({priority:'danger',title:'Ãƒâ€šÃ‚Â¡Error!',message:'Selecciona un archivo. Fecha de expedicion y de vencimiento no deben estar vacias',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
		}
		else {
			$('#tCertificados').append('<tr>'+
				'<td>' + key[0].name + ' <input id="key-'+row_id+'" class="file-anexos" name="certificados['+row_id+'][key-file]" type="file" style="display:none"></td>'+
				'<td>' + certificado[0].name + ' <input id="certificado-'+row_id+'" class="file-anexos" name="certificados['+row_id+'][cer-file]" type="file" style="display:none"></td>'+
				'<td>' + expedicion + '<input name="certificados['+row_id+'][fecha_expedicion]" type="hidden" value="'+expedicion+'"></td>'+
				'<td>' + vencimiento+' <input name="certificados['+row_id+'][fecha_vencimiento]" type="hidden" value="'+vencimiento+'"></td>'+
				'<td><button class="btn is-icon text-primary bg-white" type="button" data-delay="50" onclick="borrarAnexo(this)"> <i class="material-icons">delete</i></button></td>'+
			'</tr>');
			$.toaster({priority:'success',title:'Ãƒâ€šÃ‚Â¡Correcto!',message:'El certificado se agrego correctamente.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
			$('#certificado-'+row_id).prop('files',certificado);
		}
	});

	/*
	$('#fk_id_tipo_socio_compra').on('change', function() {
		console.log($(this).val());
		$('#fieldProductos').prop('disabled',$(this).val() == '');
	});
	
	$('#agregar-producto').on('click', function() {
		let row_id = $('#tProductos tr').length;
		
		var skus_ids = [];
    	$('.id_sku').each(function (i) {
    		skus_ids.push($('.id_sku')[i].value + $('.id_sku').parent().find('.id_upc').value ); 
		});
		
		id_sku  = $('#sku option:selected').val();
		sku  = $('#sku option:selected').text();
		id_upc  = $('#upc option:selected').val();
		upc  = id_upc == '' ? '' : $('#upc option:selected').text();
		tiempo_entrega = $('#tiempo_entrega').val();
		precio = $("#precio").val();
		precio_de = $("#precio_de").val();
		precio_hasta = $("#precio_hasta").val();
		
		if(id_sku == '' | tiempo_entrega == '' | precio == '' | precio_de == '' | precio_hasta == ''){
			$.toaster({priority:'danger',title:'Ãƒâ€šÃ‚Â¡Error!',message:'Los campos, Sku, Tiempo Entrega, Precio, Precio Valido De y Precio Valido Hasta son requeridos.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
		}
		else if(skus_ids.indexOf(id_sku + id_upc) !== -1) {
        	$.toaster({priority:'danger',title:'Ãƒâ€šÃ‚Â¡Error!',message:'El producto seleccionado ya fue agregado.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
        }
		else {
			if(id_upc != '') {
				var url_ajax = $('#upc').data('url');
				var param_js = upc_js;
				var params = {'param_js':param_js,'$id_upc':id_upc}
			}
			else {
				var url_ajax = $('#sku').data('url');
				var param_js = sku_js;
				var params = {'param_js':param_js,'$id_sku':id_sku}
			}
			console.log(params);
			
			$.ajax({
    		    async: true,
    		    url: url_ajax,
    		    data: params,
    		    dataType: 'json',
                success: function (data) {
                	$('#tProductos').append('<tr>'+
        				'<td>' + sku + '<input class="id_sku" name="productos['+row_id+'][fk_id_sku]" type="hidden" value="'+id_sku+'"></td>'+
        				'<td>' + upc + '<input class="id_upc" name="productos['+row_id+'][fk_id_upc]" type="hidden" value="'+id_upc+'"></td>'+
        				'<td>'+data[0].descripcion+'</td>'+
        				'<td>' + tiempo_entrega + ' <input name="productos['+row_id+'][tiempo_entrega]" type="hidden" value="'+tiempo_entrega+'"></td>'+
        				'<td>' + precio + ' <input name="productos['+row_id+'][precio]" type="hidden" value="'+precio+'"></td>'+
        				'<td>' + precio_de + ' <input name="productos['+row_id+'][precio_de]" type="hidden" value="'+precio_de+'"></td>'+
        				'<td>' + precio_hasta + ' <input name="productos['+row_id+'][precio_hasta]" type="hidden" value="'+precio_hasta+'"></td>'+
        				'<td><button class="btn is-icon text-primary bg-white" type="button" data-delay="50" onclick="borrarProducto(this)"> <i class="material-icons">delete</i></button></td>'+
        			'</tr>');
        			$.toaster({priority:'success',title:'Ãƒâ€šÃ‚Â¡Correcto!',message:'El producto se agrego correctamente.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
    		    }
    		});
		}
	});
	*/
});


function borrarCertificado(el) {
	$(el).parent().parent('tr').remove();
    $.toaster({priority:'success',title:'Ãƒâ€šÃ‚Â¡Correcto!',message:'Se ha eliminado la cuenta correctamente',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
}
/*
function borrarContacto(el) {
	$(el).parent().parent('tr').remove();
    $.toaster({priority:'success',title:'Ãƒâ€šÃ‚Â¡Correcto!',message:'Se ha eliminado el contacto correctamente',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
}

function borrarDireccion(el) {
	$(el).parent().parent('tr').remove();
    $.toaster({priority:'success',title:'Ãƒâ€šÃ‚Â¡Correcto!',message:'Se ha eliminado la diereccion correctamente',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
}

function borrarAnexo(el) {
    $(el).parent().parent('tr').remove();
    $.toaster({priority:'success',title:'Ãƒâ€šÃ‚Â¡Correcto!',message:'Se ha eliminado el anexo correctamente',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
}

function borrarProducto(el) {
	$(el).parent().parent('tr').remove();
    $.toaster({priority:'success',title:'Ãƒâ€šÃ‚Â¡Correcto!',message:'Se ha eliminado el producto correctamente',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
}
*/