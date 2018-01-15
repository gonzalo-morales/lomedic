$(document).ready(function () {
	$.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
	
	$('#fk_id_empresa').on('change', function() {
		let cliente = $('#fk_id_socio_negocio');
		let series = $('#fk_id_serie');
		let val = $('#fk_id_empresa option:selected').val();

		if(!val) {
			$("#fk_id_socio_negocio option").remove();
			cliente.prop('disabled',true);
			$("#fk_id_serie option").remove();
			series.prop('disabled',true);
			
			$("#rfc").val('');
			$("#fk_id_regimen_fiscal").val();
			$("#calle").val('');
        	$("#no_exterior").val();
        	$("#no_interior").val();
        	$("#codigo_postal").val();
        	$("#colonia").val();
        	$("#fk_id_municipio").val();
			$("#fk_id_estado").val();
			$("#fk_id_pais").val();
		}
		else {
    		$.ajax({
    		    async: true,
    		    url: cliente.data('url'),
    		    data: {'param_js':clientes_js,$id_empresa:val},
    		    dataType: 'json',
                success: function (data) {
                	$("#fk_id_socio_negocio option").remove();
                	cliente.append('<option value="" disabled>Selecciona una Opcion...</option>')
                    $.each(data, function(){
                    	cliente.append('<option value="'+this.id_socio_negocio+'">'+this.razon_social+'</option>')
                    });
                	cliente.val('');
                	cliente.prop('disabled', (data.length == 0)); 
    		    }
    		});
    		
    		$.ajax({
    		    async: true,
    		    url: series.data('url'),
    		    data: {'param_js':series_js,$id_empresa:val},
    		    dataType: 'json',
                success: function (data) {
                	$("#fk_id_serie option").remove();
                    $.each(data, function(){
                    	series.append('<option value="'+this.id_serie+'">'+this.prefijo+(this.sufijo ? ' - '+this.sufijo :'')+'</option>')
                    });
                	series.prop('disabled', (data.length == 0)); 
    		    }
    		});
    		
    		$.ajax({
			    url: $(this).data('url'),
			    data: {'param_js':empresa_js,$id_empresa:$(this).val()},
	            success: function (data) {
	            	$("#rfc").val(data[0].rfc);
	            	$("#fk_id_regimen_fiscal").val(data[0].fk_id_regimen_fiscal);
	            	$("#calle").val(data[0].calle);
	            	$("#no_exterior").val(data[0].no_exterior);
	            	$("#no_interior").val(data[0].no_interior);
	            	$("#codigo_postal").val(data[0].codigo_postal);
	            	$("#colonia").val(data[0].colonia);
	            	$("#fk_id_municipio").val(data[0].fk_id_municipio);
	            	$("#fk_id_estado").val(data[0].fk_id_estado);
	            	$("#fk_id_pais").val(data[0].fk_id_pais);
			    }
			});
		}

		$('#fk_id_socio_negocio').trigger('change');
	}).trigger('change');
	
	$('#fk_id_socio_negocio').on('change', function() {
		let proyecto = $('#fk_id_proyecto');
		let sucursal = $('#fk_id_sucursal');
		let val = $('#fk_id_socio_negocio option:selected').val()

		if(!val) {
			$("#fk_id_proyecto option").remove();
			proyecto.prop('disabled',true);
			$("#fk_id_sucursal option").remove();
        	sucursal.prop('disabled',true); 
        	$("#rfc_cliente").val('');
		}
		else {
			$.ajax({
			    url: $(this).data('url'),
			    data: {'param_js':cliente_js,$id_socio_negocio:$(this).val()},
	            success: function (data) {
	            	$("#rfc_cliente").val(data[0].rfc);
	            	/*$("#fk_id_regimen_fiscal").val(data[0].fk_id_regimen_fiscal);
	            	$("#calle").val(data[0].calle);
	            	$("#no_exterior").val(data[0].no_exterior);
	            	$("#no_interior").val(data[0].no_interior);
	            	$("#codigo_postal").val(data[0].codigo_postal);
	            	$("#colonia").val(data[0].colonia);
	            	$("#fk_id_municipio").val(data[0].fk_id_municipio);
	            	$("#fk_id_estado").val(data[0].fk_id_estado);
	            	$("#fk_id_pais").val(data[0].fk_id_pais);*/
			    }
			});
			
    		$.ajax({
    		    async: true,
    		    url: proyecto.data('url'),
    		    data: {'param_js':proyectos_js,$fk_id_cliente:val},
    		    dataType: 'json',
                success: function (data) {
                	$("#fk_id_proyecto option").remove();
                	proyecto.append('<option value="" disabled>Selecciona una Opcion...</option>')
                    $.each(data, function(){
                    	proyecto.append('<option value="'+ this.id_proyecto +'">'+ this.proyecto +'</option>')
                    });
                	proyecto.val('');
                	proyecto.prop('disabled', (data.length == 0));
    		    }
    		});
    		
    		$.ajax({
    		    async: true,
    		    url: sucursal.data('url'),
    		    data: {'param_js':sucursales_js,$fk_id_cliente:val},
    		    dataType: 'json',
                success: function (data) {
                	$("#fk_id_sucursal option").remove();
                	sucursal.append('<option value="" disabled>Selecciona una Opcion...</option>')
                    $.each(data, function(){
                    	sucursal.append('<option value="'+this.id_sucursal+'">'+this.sucursal+'</option>')
                    });
                	sucursal.val('');
                	sucursal.prop('disabled', (data.length == 0)); 
    		    }
    		});
		}
	}).trigger('change');
	
	$('#fk_id_moneda').on('change', function() {
		$('#tipo_cambio').attr('readonly',$('#fk_id_moneda').val() == 100);
		
		if($('#fk_id_moneda').val() == 100)
			$('#tipo_cambio').val('1.00');
		else
			$('#tipo_cambio').val('');
	}).trigger('change');
	
	
	$('#agregarRelacion').on('click', function() {
		var i = $('#detalleRelaciones tr').length;
        var row_id = i > 0 ? +$('#detalleRelaciones tr:last').find('.index').val()+1 : 0;
		id_tipo = $('#fk_id_tipo_relacion option:selected').val();
		tipo_relacion = $('#fk_id_tipo_relacion option:selected').text();
		id_factura = $('#fk_id_factura_relacion option:selected').val();
		factura = $('#fk_id_factura_relacion option:selected').text();
		
		if(id_tipo == '' | id_factura == '') {
			$.toaster({priority:'danger',title:'Â¡Error!',message:'Debe introducir el tipo de relacion y la factura a relacionar.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
		}
		else {
			$('#detalleRelaciones').append('<tr>'+
				'<td>' +
					'<input name="relations[has][relaciones]['+row_id+'][index]" class="index" type="hidden" value="'+row_id+'">'+
				    '<input name="relations[has][relaciones]['+row_id+'][fk_id_tipo_relacion]" type="hidden" value="'+id_tipo+'">'+tipo_relacion+
				'</td>'+
				'<td><input name="relations[has][relaciones]['+row_id+'][fk_id_factura]" type="hidden" value="'+id_factura+'">'+factura+'</td>'+
				'<td><button class="btn is-icon text-primary bg-white" type="button" data-delay="50" onclick="borrarFila(this)" data-tooltip="Anexo"> <i class="material-icons">delete</i></button></td>'+
			'</tr>');
			$.toaster({priority:'success',title:'Â¡Correcto!',message:'La relacion se agrego correctamente.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
		}
		
	});
	
});

function borrarFila(el) {
    $(el).parent().parent('tr').remove();
    $.toaster({priority:'success',title:'¡Correcto!',message:'Se ha eliminado correctamente el '+$(el).data('tooltip'),settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
}