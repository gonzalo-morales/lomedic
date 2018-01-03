$(document).ready(function () {
	$.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
	$.ajax({
        async: true,
        url:$('#fk_id_impuesto').data('url'),
        data: {'param_js':impuestos_js},
        dataType: 'json',
        success: function (data) {
            $('#fk_id_impuesto').append('<option value="" disabled>Selecciona un tipo de impuesto...</option>')
            $.each(data, function(index,row){
                $('#fk_id_impuesto').append('<option value="'+this.id_impuesto+'" data-impuesto=\''+JSON.stringify(row)+'\'>'+this.impuesto+'</option>')
            });
        }
	});
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
        $('#loadingfk_id_producto').show();
        var i = $('#detalleRelaciones tbody tr').length;
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
				'<td><button class="btn is-icon text-primary bg-white" type="button" data-delay="50" onclick="borrarFila(this,\'cfdi\')" data-tooltip="Anexo"> <i class="material-icons">delete</i></button></td>'+
			'</tr>');
			$.toaster({priority:'success',title:'Â¡Correcto!',message:'La relacion se agrego correctamente.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
			cargar_productos();
        }
	});

	$('#agregar-concepto').click(function () {
        var i = $('#tConceptos tbody tr').length;
        var row_id = i > 0 ? +$('#tConceptos tr:last').find('.index').val()+1 : 0;
        var producto = $('#fk_id_producto').select2().find(":selected").data("producto");

        var total = 0;
        var cantidad = +$('#cantidad').val();
        var precio_unitario = +$('#precio_unitario').val();
        var descuento = +$('#descuento').val();
        var impuesto = +$('#fk_id_impuesto').select2().find(':selected').data('impuesto').tasa_o_cuota;

        var subtotal = (cantidad*precio_unitario)-descuento;
        var impuesto_producto = (subtotal*impuesto);
        total = subtotal + impuesto_producto;
        total = total.toFixed(2);

		$('#tConceptos tbody').append(
			'<tr>' +
				'<td><input type="hidden" class="index" value="'+row_id+'"><input type="hidden" name="relations[has][detalle]['+row_id+'][fk_id_factura]" value="'+producto.fk_id_factura+'">'+producto.serie+'-'+producto.folio+'</td>' +
				'<td><input type="hidden" name="relations[has][detalle]['+row_id+'][fk_id_clave_producto_servicio]" value="'+producto.fk_id_clave_producto_servicio+'">'+producto.clave_producto_servicio+'</td>' +
				'<td><input type="hidden" name="relations[has][detalle]['+row_id+'][fk_id_clave_cliente]" value="'+producto.id_clave_cliente_producto+'">'+producto.clave_producto_cliente+'</td>' +
				'<td><input type="hidden" name="relations[has][detalle]['+row_id+'][descripcion]" value="'+producto.descripcion+'"><input type="hidden" name="relations[has][detalle]['+row_id+'][fk_id_upc]" value="'+producto.id_upc+'">'+producto.descripcion+'</td>' +
				'<td><input type="hidden" name="relations[has][detalle]['+row_id+'][fk_id_unidad_medida]" value="'+producto.fk_id_unidad_medida+'">'+producto.unidad_medida+'</td>' +
				'<td><input type="hidden" name="relations[has][detalle]['+row_id+'][cantidad]" value="'+cantidad+'">'+cantidad+'</td>' +
            	'<td><input type="hidden" name="relations[has][detalle]['+row_id+'][precio_unitario]" value="'+precio_unitario+'">$'+precio_unitario+'</td>' +
            	'<td><input type="hidden" name="relations[has][detalle]['+row_id+'][descuento]" value="'+descuento+'">'+descuento+'</td>' +
            	'<td><input type="hidden" name="relations[has][detalle]['+row_id+'][fk_id_impuesto]" value="'+$('#fk_id_impuesto').val()+'"><input type="hidden" name="relations[has][detalle]['+row_id+'][impuesto]" value="'+impuesto_producto+'"><span>'+$('#fk_id_impuesto option:selected').text()+'</span><br><span style="font-size: 11px"><b>$'+impuesto_producto+'<b/></span></td>' +
            	'<td><input type="text" name="relations[has][detalle]['+row_id+'][pedimento]" class="form-control"></td>' +
            	'<td><input type="text" name="relations[has][detalle]['+row_id+'][cuenta_predial]" class="form-control"></td>' +
				'<td><input type="hidden" name="relations[has][detalle]['+row_id+'][total]" value="'+total+'">$'+total+'</td>' +
				'<td><button class="btn is-icon text-primary bg-white" type="button" data-delay="50" onclick="borrarFila(this,\'total\')" data-tooltip="Producto"> <i class="material-icons">delete</i></button></td>' +
            '</tr>'
		);
		total_factura();
    });

});

function borrarFila(el,tipo = null) {
    $(el).parent().parent('tr').remove();
    switch(tipo){
		case 'cfdi':
			cargar_productos();
			break;
		case 'total':
			total_factura();
			break;
		default:
			break;
	}
    $.toaster({priority:'success',title:'¡Correcto!',message:'Se ha eliminado correctamente el '+$(el).data('tooltip'),settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
}

function formatProducto (producto) {
    if (producto.element && producto.element.dataset.producto) {
        // theme here ...
        var data = JSON.parse(producto.element.dataset.producto);
        //Generamos nuestro template
        var markup = "<div class='select2-result-pers clearfix'>" +
            "<div class='select2-result-pers__avatar'><img src='img/sku.png'/></div>" +
            "<div class='select2-result-pers__meta'>" +
            "<div class='select2-result-pers__text'>" + producto.text + "</div>";

		markup += "<div class='select2-result-pers__factura'>Factura: " + data.serie+'-'+data.folio + "</div>";
		markup += "<div class='select2-result-pers__sku'>SKU: " + data.sku+ "</div>";
		markup += "<div class='select2-result-pers__upc'>Descripcion: " + data.descripcion+ "</div>";
		markup += "</div></div>";
		return markup;
    }
    return producto.text;
}
function formatProductoSelection (producto) {
    return producto.text;
}

function total_factura() {
	
}

function cargar_productos() {
    $('#loadingfk_id_producto').show();
    $('#fk_id_producto').empty();
    var facturas = [];
    $('#detalleRelaciones > tbody > tr').each(function (index,row) {
        facturas.push(+$(this).children('td').slice(1).find('input').val());
    });
    $.ajax({
        async: true,
        url: $('#fk_id_producto').data('url'),
        data: {'param_js':productos_facturas_js,$fk_id_factura:JSON.stringify(facturas)},
        dataType: 'json',
        success: function (datos) {
            $.each(datos,function (index,row) {
                $('#fk_id_producto').append('<option data-producto=\''+JSON.stringify(row)+'\' value="'+row.fk_id_factura+'">'+row.clave_producto_cliente+'</option>');
            });
            $('#fk_id_producto').select2({
                escapeMarkup: function (markup) {
                    return markup;
                },
                placeholder: 'Seleccione un producto',
                templateResult: formatProducto,
                templateSelection: formatProductoSelection,
            });
            $('#loadingfk_id_producto').hide();
        },
        error: function () {
            $('#loadingfk_id_producto').hide();
        }
    });
}