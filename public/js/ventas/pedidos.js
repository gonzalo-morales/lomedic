$(document).ready(function () {
	$('#fk_id_proyecto').on('change', function() {
		let contrato = $('#fk_id_contrato');
		
		if($(this).val() == '') {
			contrato.val('');
		}
		else {
    		$.ajax({
    		    async: true,
    		    url: contrato.data('url'),
    		    data: {'param_js':contratos_js,$id_proyecto:$(this).val()},
    		    dataType: 'json',
                success: function (data) {
                	$("#fk_id_contrato option").remove();
                	contrato.append('<option value="" disabled>Selecciona una Opcion...</option>')
                    $.each(data[0].contratos, function(){
                    	contrato.append('<option value="'+this.id_contrato+'">'+this.num_contrato+'</option>')
                    });
                	contrato.val('');
                	contrato.prop('disabled', (data.length == 0)); 
    		    }
    		});
		}
	});
	
	$('#fk_id_socio_negocio').on('change', function() {
		let proyecto = $('#fk_id_proyecto');

		if($(this).val() == '') {
			proyecto.val('');
		}
		else {
    		$.ajax({
    		    async: true,
    		    url: proyecto.data('url'),
    		    data: {'param_js':proyectos_js,$fk_id_cliente:$(this).val()},
    		    dataType: 'json',
                success: function (data) {
                	$("#fk_id_proyecto").empty();
                	proyecto.append('<option value="" disabled>Selecciona una Opcion...</option>')
                    $.each(data, function(){
                    	proyecto.append('<option value="'+ this.id_proyecto +'">'+ this.proyecto +'</option>')
                    });
                	if(data.length !== 0) {
                		proyecto.val(modeldata.fk_id_proyecto);
                	}
                	proyecto.prop('disabled', (data.length == 0)); 
    		    }
    		});
    		
    		let sucursal = $('#fk_id_sucursal');
    		$.ajax({
    		    async: true,
    		    url: sucursal.data('url'),
    		    data: {'param_js':sucursales_js,$fk_id_cliente:$(this).val()},
    		    dataType: 'json',
                success: function (data) {
                	$("#fk_id_sucursal").empty();
                	sucursal.append('<option value="" disabled>Selecciona una Opcion...</option>')
                    $.each(data, function(){
                    	sucursal.append('<option value="'+this.id_sucursal+'">'+this.sucursal+'</option>')
                    });
                	sucursal.val('');
                	sucursal.prop('disabled', (data.length == 0)); 
    		    }
    		});
    		
    		$('#detalleProductos tbody tr').remove();
    		$('#fk_id_clave_cliente_producto options').empty();
    		
    		$('#fk_id_clave_cliente_producto').select2({
    			ajax: {
    			    url: $('#fk_id_clave_cliente_producto').data('url').replace('?id',$(this).val()),
    			    processResults: function (data) {
    			    	return {
    			    		results: data
    			    	};
    			    }
    			}
    		});
		}
	}).trigger('change');

	
	
	
	
	
	/*
	on('focus', function (e) {
		//alert('is focus');
		let _url = $(this).data('url').replace('?id',$('#fk_id_socio_negocio').val());
        //$(this).empty().prop('disabled',true);
        $('#loadingfk_id_clave_cliente_producto').show();
        $.ajax({
            url: _url,
            dataType:'json',
            success:function (data) {
                let option = $('<option/>');
                option.val(0);
                option.attr('disabled','disabled');
                option.attr('selected','selected');
                if (Object.keys(data).length == 0)
                    option.text('No se encontraron elementos');
                else
                    option.text('...');

                try {
	                $('#fk_id_clave_cliente_producto').prepend(option).select2({
	                    minimumResultsForSearch:'50',
	                    data:data,
	                }).attr('disabled',false);
                }
                catch (e) {
                	console.log(e);
                }
                $('#loadingfk_id_clave_cliente_producto').hide();
            },
        	error:function (e) {
        		$('#loadingfk_id_clave_cliente_producto').hide();
        	}
        });
	});
	*/
	
	//$('#fk_id_clave_cliente_producto').trigger('focus');
	
	$('#activo_upc').on('change',function () {
        if( !this.checked ){
            $( this ).parent().nextAll( "select" ).val(0).trigger('change').prop( "disabled", !this.checked ).empty();
        }else{
            if($('#fk_id_clave_cliente_producto').val()){
                $('#loadingfk_id_upc').show();
                let _url = $('#fk_id_upc').data('url').replace('?id',$('#fk_id_clave_cliente_producto').select2('data')[0].fk_id_sku);
                $('#fk_id_upc').empty();
                $.ajax({
                    url: _url,
                    dataType: 'json',
                    success: function (data) {
                        let option = $('<option/>');
                        option.val(0);
                        option.attr('disabled','disabled');
                        option.attr('selected','selected');
                        if (Object.keys(data).length == 0)
                            option.text('No se encontraron elementos');
                        else
                            option.text('...');
                        $('#fk_id_upc').attr('disabled',false).select2({
                            minimumResultsForSearch: 15,
                            data: data
                        }).prepend(option);
                        $('#loadingfk_id_upc').hide();
                    }
                });
            }else{
                $( this ).prop('checked',false);
                $( this ).parent().nextAll( "select" ).prop( "disabled", !this.checked );
                $.toaster({priority : 'danger',title : 'Â¡Error!',message : 'Selecciona antes una Clave cliente producto',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
            }
        }
    });
	
	$('#agregarProducto').on('click', function() {
    	var i = $('#detalleProductos tr').length;
        var row_id = i > 0 ? +$('#detalleProductos tr:last').find('#index').val()+1 : 0;
		
        /*
		nombre = $('#nombre_archivo').val();
		archivo = $("#archivo").prop('files');
		
		if(nombre == '') {
			$.toaster({priority:'danger',title:'ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¡Error!',message:'Debe introducir el nombre para el documento.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
		}
		else if($("#archivo").length == 0) {
			$.toaster({priority:'danger',title:'ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¡Error!',message:'Selecciona un archivo.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
		}
		else {
			$('#detalleAnexos').append('<tr>'+
				'<td><input class="index" name="relations[has][anexos]['+row_id+'][index]" type="hidden" value="'+row_id+'">'+
					'<input name="relations[has][anexos]['+row_id+'][nombre]" type="hidden" value="'+nombre+'">'+nombre+'</td>'+
				'<td><input name="relations[has][anexos]['+row_id+'][file]" type="file" id="fileAnexo-'+row_id+'" style="display:none">'+archivo[0].name+'</td>'+
				'<td><button class="btn is-icon text-primary bg-white" type="button" data-delay="50" onclick="borrarFila(this)" data-tooltip="Anexo"> <i class="material-icons">delete</i></button></td>'+
			'</tr>');
			$('#fileAnexo-'+row_id).prop('files',archivo);
			$.toaster({priority:'success',title:'Ã‚Â¡Correcto!',message:'El archivo se agrego correctamente.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
		}
		*/
	});
	
	$('#agregarAnexo').on('click', function() {
    	var i = $('#detalleAnexos tr').length;
        var row_id = i > 0 ? +$('#detalleAnexos tr:last').find('#index').val()+1 : 0;
		
		nombre = $('#nombre_archivo').val();
		archivo = $("#archivo").prop('files');
		
		if(nombre == '') {
			$.toaster({priority:'danger',title:'ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¡Error!',message:'Debe introducir el nombre para el documento.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
		}
		else if($("#archivo").length == 0) {
			$.toaster({priority:'danger',title:'ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¡Error!',message:'Selecciona un archivo.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
		}
		else {
			$('#detalleAnexos').append('<tr>'+
				'<td><input class="index" name="relations[has][anexos]['+row_id+'][index]" type="hidden" value="'+row_id+'">'+
					'<input name="relations[has][anexos]['+row_id+'][nombre]" type="hidden" value="'+nombre+'">'+nombre+'</td>'+
				'<td><input name="relations[has][anexos]['+row_id+'][file]" type="file" id="fileAnexo-'+row_id+'" style="display:none">'+archivo[0].name+'</td>'+
				'<td><button class="btn is-icon text-primary bg-white" type="button" data-delay="50" onclick="borrarFila(this)" data-tooltip="Anexo"> <i class="material-icons">delete</i></button></td>'+
			'</tr>');
			$('#fileAnexo-'+row_id).prop('files',archivo);
			$.toaster({priority:'success',title:'Ã‚Â¡Correcto!',message:'El archivo se agrego correctamente.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
		}
	});
	
	$('#fk_id_moneda').on('change', function() {
		$('#tipo_cambio').attr('readonly',$('#fk_id_moneda').val() == 100);
		
		if($('#fk_id_moneda').val() == 100)
			$('#tipo_cambio').val('1.00');
		else
			$('#tipo_cambio').val('');
	});
});

function borrarFila(el) {
    $(el).parent().parent('tr').remove();
    $.toaster({priority:'success',title:'Ã‚Â¡Correcto!',message:'Se ha eliminado correctamente el '+$(el).data('tooltip'),settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
}