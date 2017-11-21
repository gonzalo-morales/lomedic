$(document).ready(function () {
	
	window.ContactosTable = new DataTable('#tContactos', {header: true,fixedHeight: true,fixedColumns: false,searchable: false,perPageSelect: false,labels:{info:""} });
	window.DireccionesTable = new DataTable('#tDirecciones', {header: true,fixedHeight: true,fixedColumns: false,searchable: false,perPageSelect: false,labels:{info:""} });
	window.CuentasTable = new DataTable('#tCuentas', {header: true,fixedHeight: true,fixedColumns: false,searchable: false,perPageSelect: false,labels:{info:""} });
	window.AnexosTable = new DataTable('#tAnexos', {header: true,fixedHeight: true,fixedColumns: false,searchable: false,perPageSelect: false,labels:{info:""} });
	window.ProductosTable = new DataTable('#tProductos', {header: true,fixedHeight: true,fixedColumns: false,searchable: false,perPageSelect: false,labels:{info:""} });

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
	
	$('#pais').on('change', function() {
		$('#estado').prop('disabled', ($(this).val() != 1));

		if($(this).val() == '') {
			$('#estado').val('');
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
	
	
	$('#agregar-contacto').on('click', function() {
    	let row_id = ContactosTable.activeRows.length + 1;
		
    	id_tipo   = $('#tipo_contacto option:selected').val();
    	tipo      = $('#tipo_contacto option:selected').text();
    	nombre    = $('#nombre_contacto').val();
    	puesto    = $('#puesto').val();
    	correo    = $('#correo').val();
    	celular   = $('#celular').val();
    	telefono  = $('#telefono_oficina').val();
    	extension = ' - ' + $('#extension_oficina').val();
        
    	if(tipo == '' | nombre == '' | puesto == '' | correo == '') {
    		$.toaster({priority:'danger',title:'¡Error!',message:'Los siguientes campos son necesarios: Tipo contacto, Nombre, Puesto y Correo.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
    	}
        else {
        	ContactosTable.insert( {
        		 data:[
        			 tipo+' <input name="cuentas['+row_id+'][fk_id_tipo_contacto]" type="hidden" value="'+id_tipo+'">',
        			 nombre+' <input name="cuentas['+row_id+'][nombre_contacto]" type="hidden" value="'+nombre+'">',
        			 puesto+' <input name="cuentas['+row_id+'][puesto]" type="hidden" value="'+puesto+'">',
        			 correo+' <input name="cuentas['+row_id+'][correo]" type="hidden" value="'+correo+'">',
        			 celular+' <input name="cuentas['+row_id+'][celular]" type="hidden" value="'+celular+'">',
        			 telefono+extension+' <input name="cuentas['+row_id+'][telefono_oficina]" type="hidden" value="'+telefono+'">'+
        			 '<input name="cuentas['+row_id+'][extension_oficina]" type="hidden" value="'+extension+'">',
                     
                     '<button class="btn is-icon text-primary bg-white" type="button" data-delay="50" onclick="borrarContacto(this)"> <i class="material-icons">delete</i></button>'
        		 ]
        	 }),
        	 $.toaster({priority:'success',title:'¡Correcto!',message:'El contacto se agrego correctamente.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
        }
	});
	
	$('#agregar-direccion').on('click', function() {
    	let row_id = DireccionesTable.activeRows.length + 1;
		
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
        	DireccionesTable.insert( {
        		 data:[
        			 tipo+' <input name="direcciones['+row_id+'][fk_id_tipo_contacto]" type="hidden" value="'+id_tipo+'">',
        			 calle+' '+num_exterior+' '+num_interior+
        			 ' <input name="direcciones['+row_id+'][calle]" type="hidden" value="'+calle+'">'+
        			 ' <input name="direcciones['+row_id+'][num_exterior]" type="hidden" value="'+num_exterior+'">'+
        			 ' <input name="direcciones['+row_id+'][num_interior]" type="hidden" value="'+num_interior+'">',
        			 
        			 cp+' <input name="direcciones['+row_id+'][codigo_postal]" type="hidden" value="'+cp+'">',
        			 colonia+' <input name="direcciones['+row_id+'][colonia]" type="hidden" value="'+colonia+'">',
        			 municipio+' <input name="direcciones['+row_id+'][fk_id_municipio]" type="hidden" value="'+id_municipio+'">',
        			 estado+' <input name="direcciones['+row_id+'][fk_id_estado]" type="hidden" value="'+id_estado+'">',
        			 pais+'<input name="direcciones['+row_id+'][fk_id_pais]" type="hidden" value="'+id_pais+'">',
        			 
                     
                     '<button class="btn is-icon text-primary bg-white" type="button" data-delay="50" onclick="borrarDireccion(this)"> <i class="material-icons">delete</i></button>'
        		 ]
        	 }),
        	 $.toaster({priority:'success',title:'¡Correcto!',message:'La direccion se agrego correctamente.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
        }
	});
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	$('#agrega-cuenta').on('click', function() {
		var cuentas = [];
    	$('.uniquekey').each(function (i) {
    		cuentas.push($('.uniquekey')[i].value); 
		});
    	
    	let row_id = CuentasTable.activeRows.length + 1;
		
    	id_banco  = $('#fk_id_banco option:selected').val();
    	banco  = $('#fk_id_banco option:selected').text();
    	no_cuenta = $('#no_cuenta').val();
    	sucursal = $('#sucursal').val();
    	clave_int = $('#clave_interbancaria').val();
        
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
	         CuentasTable.insert({
				 data:[
					 banco +
					 '<input class="id_cuenta" name="cuentas['+row_id+'][id_cuenta]" type="hidden" value="">'+
					 '<input class="fk_id_banco" name="cuentas['+row_id+'][fk_id_banco]" type="hidden" value="'+id_banco+'">'+
					 '<input class="fk_id_socio_negocio" name="cuentas['+row_id+'][fk_id_socio_negocio]" type="hidden" value="">'+
					 '<input class="uniquekey" name="cuentas['+row_id+'][uniquekey]" type="hidden" value="'+id_banco+'-'+no_cuenta+'">',
					 no_cuenta+' <input name="cuentas['+row_id+'][no_cuenta]" type="hidden" value="'+no_cuenta+'">',
					 sucursal+' <input name="cuentas['+row_id+'][no_sucursal]" type="hidden" value="'+sucursal+'">',
					 clave_int+' <input name="cuentas['+row_id+'][clave_interbancaria]" type="hidden" value="'+clave_int+'">',
					 '<button class="btn is-icon text-primary bg-white" type="button" data-delay="50" onclick="borrarCuenta(this)"> <i class="material-icons">delete</i></button>'
				 ]
			 });
			 $.toaster({priority:'success',title:'¡Correcto!',message:'La cuenta se agrego correctamente.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
        }
	});
});

function borrarCuenta(el) {
    let fila = CuentasTable.data[$(el).parents('tr').index()];
    CuentasTable.rows().remove([$(el).parents('tr').index()]);
    $.toaster({priority:'success',title:'¡Correcto!',message:'Se ha eliminado la cuenta correctamente',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
}

function borrarContacto(el) {
    let fila = CuentasTable.data[$(el).parents('tr').index()];
    ContactosTable.rows().remove([$(el).parents('tr').index()]);
    $.toaster({priority:'success',title:'¡Correcto!',message:'Se ha eliminado el contacto correctamente',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
}

function borrarDireccion(el) {
    let fila = CuentasTable.data[$(el).parents('tr').index()];
    DireccionesTable.rows().remove([$(el).parents('tr').index()]);
    $.toaster({priority:'success',title:'¡Correcto!',message:'Se ha eliminado la diereccion correctamente',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
}

function borrarAnexo(el) {
    let fila = CuentasTable.data[$(el).parents('tr').index()];
    AnexosTable.rows().remove([$(el).parents('tr').index()]);
    $.toaster({priority:'success',title:'¡Correcto!',message:'Se ha eliminado el anexo correctamente',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
}

function borrarProducto(el) {
    let fila = CuentasTable.data[$(el).parents('tr').index()];
    ProductosTable.rows().remove([$(el).parents('tr').index()]);
    $.toaster({priority:'success',title:'¡Correcto!',message:'Se ha eliminado el producto correctamente',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
}