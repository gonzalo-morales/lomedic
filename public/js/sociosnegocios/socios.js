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
	
	var precio_from = $('#precio_de').pickadate({ selectMonths: true, selectYears: 3, format: 'yyyy-mm-dd' }).pickadate('picker');
	var precio_to = $('#precio_hasta').pickadate({ selectMonths: true, selectYears: 3, format: 'yyyy-mm-dd' }).pickadate('picker');

	precio_from.on('set', function(event) {
		if ( 'select' in event ) {
			precio_to.start().clear().set('min', precio_from.get('select'));
	    }

	    if ( 'clear' in event ) {
	    	precio_to.clear().set('min', false).stop();
	    	$('#precio_hasta').prop('readonly', true);
		  }
	});
	
	$('#pais').on('change', function() {
		let estado = $('#estado');

		if($(this).val() == '') {
			estado.val('');
		}
		else {
    		$.ajax({
    		    async: true,
    		    url: estado.data('url'),
    		    data: {'param_js':estados_js,$fk_id_pais:$(this).val()},
    		    dataType: 'json',
                success: function (data) {
                	$("#estado option").remove();
                	estado.append('<option value="" disabled>Selecciona una Opcion...</option>')
                    $.each(data, function(){
                    	estado.append('<option value="'+ this.id_estado +'">'+ this.estado +'</option>')
                    });
                	estado.val('');
                	estado.prop('disabled', (data.length == 0));
    		    },
    		    error: function (data) {
    		    	estado.val('');
                	estado.prop('disabled', (data.length == 0));
    		    }
    		});
		}
	});
	
	$('#estado').on('change', function() {
		let municipio = $('#municipio');

		if($(this).val() == '') {
			municipio.val('');
		}
		else {
    		$.ajax({
    		    async: true,
    		    url: municipio.data('url'),
    		    data: {'param_js':municipios_js,$fk_id_estado:$(this).val()},
    		    dataType: 'json',
                success: function (data) {
                	$("#municipio option").remove();
                	municipio.append('<option value="" disabled>Selecciona una Opcion...</option>')
                    $.each(data, function(){
                    	municipio.append('<option value="'+ this.id_municipio +'">'+ this.municipio +'</option>')
                    });
                	municipio.val('');
                	municipio.prop('disabled', (data.length == 0));
                	$('#loadingMunicipios').hide();
    		    },
    		    error: function (data) {
    		    	municipio.val('');
                	municipio.prop('disabled', (data.length == 0));
    		    }
    		});
		}
	});
	
	$('#sku').on('change', function() {
		let upc = $('#upc');

		if($(this).val() == '') {
			upc.val('');
		}
		else {
    		$.ajax({
    		    async: true,
    		    url: upc.data('url'),
    		    data: {'param_js':upcs_js,$fk_id_sku:$(this).val()},
    		    dataType: 'json',
                success: function (data) {
                	$("#upc option").remove();
                	upc.append('<option value="" disabled>Selecciona una Opcion...</option>')
                    $.each(data, function(){
                    	upc.append('<option value="'+ this.id_upc +'">'+ this.upc +'</option>')
                    });
                	upc.val('');
                	upc.prop('disabled', (data.length == 0)); 
    		    }
    		});
		}
	});
	
	$('#agregar-contacto').on('click', function() {
		var i = $('#tContactos tbody tr').length;
        var row_id = i > 0 ? +$('#tContactos tr:last').find('.index').val()+1 : 0;
		
    	id_tipo   = $('#tipo_contacto option:selected').val();
    	tipo      = $('#tipo_contacto option:selected').text();
    	nombre    = $('#nombre_contacto').val();
    	puesto    = $('#puesto').val();
    	correo    = $('#correo').val();
    	celular   = $('#celular').val();
    	telefono  = $('#telefono_oficina').val();
    	extension = $('#extension_oficina').val();
    	iextension = ' - ' + extension;
        
    	if(tipo == '' | nombre == '' | puesto == '' | correo == '') {
    		$.toaster({priority:'danger',title:'¡Error!',message:'Los siguientes campos son necesarios: Tipo contacto, Nombre, Puesto y Correo.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
    	}
        else {
        	$('#tContactos').append('<tr>'+
        		'<td><input class="index" name="relations[has][contratos]['+row_id+'][index]" type="hidden" value="'+row_id+'">'+
					'<input name="relations[has][contratos]['+row_id+'][fk_id_tipo_contacto]" type="hidden" value="'+id_tipo+'">'+tipo+'</td>'+
				'<td>'+nombre+' <input name="contactos['+row_id+'][nombre]" type="hidden" value="'+nombre+'"></td>'+
				'<td>'+puesto+' <input name="contactos['+row_id+'][puesto]" type="hidden" value="'+puesto+'"></td>'+
				'<td>'+correo+' <input name="contactos['+row_id+'][correo]" type="hidden" value="'+correo+'"></td>'+
				'<td>'+celular+' <input name="contactos['+row_id+'][celular]" type="hidden" value="'+celular+'"></td>'+
				'<td>'+telefono+iextension+' <input name="contactos['+row_id+'][telefono_oficina]" type="hidden" value="'+telefono+'">'+
				'<input name="contactos['+row_id+'][extension_oficina]" type="hidden" value="'+extension+'"></td>'+
				'<td><button class="btn is-icon text-primary bg-white" type="button" data-delay="50" onclick="borrarFila(this)" data-tooltip="Contrato"><i class="material-icons">delete</i></button></td>'+
			'</tr>');
        	$.toaster({priority:'success',title:'¡Correcto!',message:'El contacto se agrego correctamente.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
        }
	});
	
	$('#agregar-direccion').on('click', function() {
		var i = $('#tDirecciones tbody tr').length;
        var row_id = i > 0 ? +$('#tDirecciones tr:last').find('.index').val()+1 : 0;
		
    	id_tipo      = $('#tipo_direccion option:selected').val();
    	tipo         = $('#tipo_direccion option:selected').text();
    	calle        = $('#calle').val();
    	num_exterior = $('#num_exterior').val();
    	num_interior = $('#num_interior').val();
    	cp           = $('#cp').val();
    	colonia		 = $('#colonia').val();
    	id_municipio = $('#municipio option:selected').val();
    	municipio  	 = $('#municipio option:selected').text();
    	id_estado    = $('#estado option:selected').val();
    	estado   	 = $('#estado option:selected').text();
    	id_pais		 = $('#pais option:selected').val();
    	pais		 = $('#pais option:selected').text();
    	
    	if(tipo == '' | calle == '' | num_exterior == '' | num_interior == '' | cp == '' | id_pais == '' | id_estado == '' | id_municipio == '' | colonia == '') {
    		$.toaster({priority:'danger',title:'¡Error!',message:'Los siguientes campos son necesarios: Tipo contacto, Nombre, Puesto y Correo.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
    	}
        else {
			$('#tDirecciones').append('<tr>'+
				'<td><input class="index" name="relations[has][direcciones]['+row_id+'][index]" type="hidden" value="'+row_id+'">'+
					'<input name="relations[has][direcciones]['+row_id+'][fk_id_tipo_direccion]" type="hidden" value="'+id_tipo+'">'+tipo+'</td>'+
				'<td>'+calle+' '+num_exterior+' '+num_interior+
				' <input name="relations[has][direcciones]['+row_id+'][calle]" type="hidden" value="'+calle+'">'+
				' <input name="relations[has][direcciones]['+row_id+'][num_exterior]" type="hidden" value="'+num_exterior+'">'+
				' <input name="relations[has][direcciones]['+row_id+'][num_interior]" type="hidden" value="'+num_interior+'"></td>'+
				'<td>'+cp+' <input name="relations[has][direcciones]['+row_id+'][codigo_postal]" type="hidden" value="'+cp+'"></td>'+
				'<td>'+colonia+' <input name="relations[has][direcciones]['+row_id+'][colonia]" type="hidden" value="'+colonia+'"></td>'+
				'<td>'+municipio+' <input name="relations[has][direcciones]['+row_id+'][fk_id_municipio]" type="hidden" value="'+id_municipio+'"></td>'+
				'<td>'+estado+' <input name="relations[has][direcciones]['+row_id+'][fk_id_estado]" type="hidden" value="'+id_estado+'"></td>'+
				'<td>'+pais+'<input name="relations[has][direcciones]['+row_id+'][fk_id_pais]" type="hidden" value="'+id_pais+'"></td>'+
				'<td><button class="btn is-icon text-primary bg-white" type="button" data-delay="50" onclick="borrarFila(this)" data-tooltip="Contrato"><i class="material-icons">delete</i></button></td>'+
			'</tr>');
        	$.toaster({priority:'success',title:'¡Correcto!',message:'La direccion se agrego correctamente.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
        }
	});
	
	$('#agregar-cuenta').on('click', function() {
		var cuentas = [];
		$('.uniquekey').each(function (i) {
			cuentas.push($('.uniquekey')[i].value); 
		});
		
		let row_id = $('#tCuentas tr').length;
		
		id_banco  = $('#fk_id_banco option:selected').val();
		banco  = $('#fk_id_banco option:selected').text();
		no_cuenta = $('#no_cuenta').val();
		sucursal = $('#sucursal').val();
		clave_int = $('#clave_interbancaria').val();
		
		if(no_cuenta == '' | Number.isInteger(no_cuenta) != false) {
			$.toaster({priority:'danger',title:'Ãƒâ€šÃ‚Â¡Error!',message:'Debe introducir el numero de cuenta, esta debe ser numero entero.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
		}
		else if(id_banco == '' ){
			$.toaster({priority:'danger',title:'Ãƒâ€šÃ‚Â¡Error!',message:'Debe seleccionar un banco.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
		}
		else if(cuentas.indexOf(id_banco+'-'+no_cuenta) !== -1) {
			$.toaster({priority:'danger',title:'Ãƒâ€šÃ‚Â¡Error!',message:'La cuenta que trata de agregar ya existe.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
		}
		else {
			$('#tCuentas').append('<tr>'+
				'<td>' + banco +
				'<input class="id_cuenta" name="cuentas['+row_id+'][id_cuenta]" type="hidden" value="">'+
				'<input class="fk_id_banco" name="cuentas['+row_id+'][fk_id_banco]" type="hidden" value="'+id_banco+'">'+
				'<input class="uniquekey" name="cuentas['+row_id+'][uniquekey]" type="hidden" value="'+id_banco+'-'+no_cuenta+'"></td>'+
				'<td>' + no_cuenta + ' <input name="cuentas['+row_id+'][no_cuenta]" type="hidden" value="'+no_cuenta+'"></td>'+
				'<td>' + sucursal + ' <input name="cuentas['+row_id+'][no_sucursal]" type="hidden" value="'+sucursal+'"></td>'+
				'<td>' + clave_int + ' <input name="cuentas['+row_id+'][clave_interbancaria]" type="hidden" value="'+clave_int+'"></td>'+
				'<td><button class="btn is-icon text-primary bg-white" type="button" data-delay="50" onclick="borrarCuenta(this)"> <i class="material-icons">delete</i></button></td>'+
			'</tr>');
			$.toaster({priority:'success',title:'Ãƒâ€šÃ‚Â¡Correcto!',message:'La cuenta se agrego correctamente.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
		}
	});

	$('#agregar-anexo').on('click', function() {
		let row_id = $('#tAnexos tr').length;
		
		id_tipo  = $('#tipo_anexo option:selected').val();
		tipo  = $('#tipo_anexo option:selected').text();
		nombre = $('#nombre_archivo').val();
		archivo = $("#archivo").prop('files');
		
		if(id_tipo == '' ){
			$.toaster({priority:'danger',title:'Ãƒâ€šÃ‚Â¡Error!',message:'Debe seleccionar un tipo de documento.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
		}
		else if(nombre == '') {
			$.toaster({priority:'danger',title:'Ãƒâ€šÃ‚Â¡Error!',message:'Debe introducir el nombre para el documento.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
		}
		else if($("#archivo").length == 0) {
			$.toaster({priority:'danger',title:'Ãƒâ€šÃ‚Â¡Error!',message:'Selecciona un archivo.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
		}
		else {
			$('#tAnexos').append('<tr>'+
				'<td>' + tipo + '<input class="id_tipo" name="anexos['+row_id+'][fk_id_tipo_anexo]" type="hidden" value="'+id_tipo+'"></td>'+
				'<td>' + nombre+' <input name="anexos['+row_id+'][nombre]" type="hidden" value="'+nombre+'"></td>'+
				'<td>' + archivo[0].name + ' <input id="anexos-'+row_id+'" class="file-anexos" name="anexos['+row_id+'][archivo]" type="file" style="display:none"></td>'+
				'<td><button class="btn is-icon text-primary bg-white" type="button" data-delay="50" onclick="borrarAnexo(this)"> <i class="material-icons">delete</i></button></td>'+
			'</tr>');
			$('#anexos-'+row_id).prop('files',archivo);
			$.toaster({priority:'success',title:'Ãƒâ€šÃ‚Â¡Correcto!',message:'El archivo se agrego correctamente.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
		}
	});

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
});

function borrarFila(el) {
    $(el).parent().parent('tr').remove();
    $.toaster({priority:'success',title:'¡Correcto!',message:'Se ha eliminado correctamente el '+$(el).data('tooltip'),settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
}