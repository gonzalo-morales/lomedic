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
        let certificado = $('#fk_id_certificado');
        let val = $('#fk_id_empresa').val();

		if(val <= 0) {
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
                	cliente.val(id_socio);
                	cliente.prop('disabled', (data.length == 0 | ver)); 
    		    }
    		});
    		
    		$.ajax({
    		    async: true,
    		    url: series.data('url'),
    		    data: {'param_js':series_js,$id_empresa:val},
    		    dataType: 'json',
                success: function (data) {
                	$("#fk_id_serie option").remove();
                		series.append('<option value="">...</option>')
                    $.each(data, function(){
                    	series.append('<option value="'+this.id_serie+'">'+this.prefijo+(this.sufijo ? ' - '+this.sufijo :'')+'</option>')
                    });
            		series.val(id_serie);
                	series.prop('disabled', (data.length == 0 | ver)); 
    		    }
    		});

            $.ajax({
                async: true,
                url: certificado.data('url'),
                data: {'param_js': certificados_js, $id_empresa: val},
                dataType: 'json',
                success: function (data) {
                    if(data[0].certificados.length > 0) {
                        $("#fk_id_certificado").empty().attr('disabled', false);
                        certificado.append('<option value="" disabled>Selecciona una Opción...</option>')
                        $.each(data[0].certificados, function (index, value) {
                            certificado.append('<option value="' + value.id_certificado + '">' + value.no_certificado + '</option>')
                        });
                        certificado.val('');
                        certificado.prop('disabled', (data.length == 0));
                    }else{
                        $.toaster({priority:'warning',title:'¡Oooops!',message:'No se encontraron certificados.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
                    }
                }
            });

    		$.ajax({
    			async: true,
			    url: $(this).data('url'),
			    data: {'param_js':empresa_js,$id_empresa:$(this).val()},
			    dataType: 'json',
	            success: function (data) {
	            	$("#rfc").val(data[0].rfc);
	            	$("#fk_id_regimen_fiscal").val(data[0].fk_id_regimen_fiscal).change();
	            	$("#calle").val(data[0].calle);
	            	$("#no_exterior").val(data[0].no_exterior);
	            	$("#no_interior").val(data[0].no_interior);
	            	$("#codigo_postal").val(data[0].codigo_postal);
	            	$("#colonia").val(data[0].colonia);
	            	$("#fk_id_municipio").val(data[0].fk_id_municipio).change();
	            	$("#fk_id_estado").val(data[0].fk_id_estado).change();
	            	$("#fk_id_pais").val(data[0].fk_id_pais).change();
			    }
			});
		}

		$('#fk_id_socio_negocio').trigger('change');
	}).trigger('change');

    $(document).on('submit',function (e) {
        limpiarCampos();
        $.validator.addMethod('cuenta_predial',function (value,element) {
            return this.optional(element) || /^\d{1,150}$/g.test(value);
        },'Verifica el formato de la cuenta predial (dígitos entre 1 y 150 caracteres)');
        $.validator.addMethod('pedimento',function (value,element) {
            return this.optional(element) || /^(\d{2}  \d{2}  \d{4}  \d{7})$/g.test(value);
        },'Verifica el formato del pedimento (XX  XX  XXXX  XXXXXXX). Recuerda que son dos espacios');

        $.validator.addClassRules('cuenta_predial',{
            cuenta_predial:true
        });
        $.validator.addClassRules('pedimento',{
            pedimento:true
        });
        if(!$('#form-model').valid()){
            e.preventDefault();
            $('.pedimento').rules('remove');
            $('.cuenta_predial').rules('remove');
            $.toaster({
                priority: 'danger', title: '¡Error!', message: 'Hay campos que requieren de tu atencion',settings: {'timeout': 10000, 'toaster': {'css': {'top': '5em'}}}
            });
        }

        if($('#tConceptos tbody tr').length < 1){
            e.preventDefault();
            $.toaster({
                priority: 'danger', title: '¡Error!', message: 'Por favor agrega al menos un producto',settings: {'timeout': 10000, 'toaster': {'css': {'top': '5em'}}}
            });
        }

    });

	$('#timbrar').on('click', function(e) {
		$("#form-model").append('<input name="timbrar" type="hidden" value="1">');
	});
	
	$('#fk_id_socio_negocio').on('change', function() {
		let proyecto = $('#fk_id_proyecto');
		let sucursal = $('#fk_id_sucursal');
		let val = $('#fk_id_socio_negocio option:selected').val();

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
                	proyecto.val(id_proyecto);
                	proyecto.prop('disabled', (data.length == 0 | ver));
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
                	sucursal.val(id_sucursal);
                	sucursal.prop('disabled', (data.length == 0 | ver)); 
    		    }
    		});
		}
	}).trigger('change');
	
	$('#fk_id_proyecto').on('change', function() {
		let val = $('#fk_id_proyecto').val();
		let contrato = $('#fk_id_contrato');
        let cliente = $('#fk_id_socio_negocio');
        let producto = $('#fk_id_producto');

		if(!val) {
            $("#fk_id_contrato option").remove();
			contrato.prop('disabled',true);
		}
		else {
    		$.ajax({
    		    async: true,
    		    url: contrato.data('url'),
    		    data: {'param_js':contratos_js,$id_proyecto:val},
    		    dataType: 'json',
                success: function (data) {
                	$("#fk_id_contrato option").remove();
                	contrato.append('<option value="" disabled>Selecciona una Opcion...</option>')
                    $.each(data[0].contratos, function(){ 
                    	contrato.append('<option value="'+this.id_contrato+'">'+this.num_contrato+'</option>')
                    });
                	contrato.val(id_contrato);
                	contrato.prop('disabled', (data.length == 0 | ver)); 
    		    }
    		});
            $.ajax({
                async: true,
                url: producto.data('url'),
                data: {'param_js':productos_js,$id_proyecto:val},
                dataType: 'json',
                success: function (data) {
                    producto.empty();
                    if(data.length > 0) {
                        producto.select2({
							data:data,
                            escapeMarkup: function (markup) {
                                return markup;
                            },
                            placeholder: 'Seleccione un producto',
                            templateResult: formatProducto,
                            templateSelection: formatProductoSelection,
						}).trigger('change');
                        var precio = producto.select2('data')[0].precio
                        var impuesto = producto.select2('data')[0].fk_id_impuesto;
						$('#precio_unitario').val(+precio.toString());
						$('#fk_id_impuesto').val(impuesto).trigger('change');
                        // $.each(data, function () {
                        //     producto.append('<option value="' + this.id_clave_cliente_producto + '">' +  this.clave_producto_cliente + '</option>')
                        // });
                        producto.prop('disabled', (data.length == 0 | ver));
                    }
                }
            });
		}
	});
	
	$('#fk_id_moneda').on('change', function() {
		$('#tipo_cambio').attr('readonly',$('#fk_id_moneda').val() == 100);
		
		if($('#fk_id_moneda').val() == 100)
			$('#tipo_cambio').val('1.00');
		else
			$('#tipo_cambio').val('');
	}).trigger('change');
	
	
	$('#agregarRelacion').on('click', function() {
		var i = $('#detalleRelaciones tbody tr').length;
        var row_id = i > 0 ? +$('#detalleRelaciones tbody tr:last').find('.index').val()+1 : 0;
		id_tipo = $('#fk_id_tipo_relacion option:selected').val();
		tipo_relacion = $('#fk_id_tipo_relacion option:selected').text();
		id_documento_relacionado = $('#fk_id_factura_relacion option:selected').val();
		factura = $('#fk_id_factura_relacion option:selected').text();
		id_tipo_documento_relacionado = 4;
		
		var agregar = true;
        if($('#detalleRelaciones tbody tr').length >0){
            $('.fk_id_documento_relacionado').each(function (i) {
                var relacionado = $('.fk_id_documento_relacionado')[i].value+'-'+$('.fk_id_tipo_documento_relacionado')[i].value;
                if(relacionado == id_documento_relacionado+'-'+id_tipo_documento_relacionado) {
                    agregar = false;
                }
            });
        }
		
		if(agregar == false)
			$.toaster({priority:'danger',title:'¡Error!',message:'<br>El documento seleccionado ya esta relacionado.',settings:{'timeout':10000,'toaster':{'css':{'top':'3em'}}}});
		else if(id_tipo == '' | id_documento_relacionado == '') {
			$.toaster({priority:'danger',title:'¡Error!',message:'Debe introducir el tipo de relacion y la factura a relacionar.',settings:{'timeout':10000,'toaster':{'css':{'top':'3em'}}}});
		}
		else {
			$('#detalleRelaciones').append('<tr>'+
				'<td>' +
					'<input name="relations[has][relaciones]['+row_id+'][index]" class="index" type="hidden" value="'+row_id+'">'+
					'<input name="relations[has][relaciones]['+row_id+'][fk_id_tipo_documento]" type="hidden" value="4">'+
				    '<input name="relations[has][relaciones]['+row_id+'][fk_id_tipo_relacion]" type="hidden" value="'+id_tipo+'">'+tipo_relacion+
				'</td>'+
				'<td>'+
					'<input name="relations[has][relaciones]['+row_id+'][fk_id_documento_relacionado]" type="hidden" value="'+id_documento_relacionado+'" class="fk_id_documento_relacionado">'+factura+
					'<input name="relations[has][relaciones]['+row_id+'][fk_id_tipo_documento_relacionado]" type="hidden" value="'+id_tipo_documento_relacionado+'" class="fk_id_tipo_documento_relacionado">'+
				'</td>'+
				'<td><button class="btn is-icon text-primary bg-white" type="button" data-delay="50" onclick="borrarFila(this)" data-tooltip="documento relacionado"> <i class="material-icons">delete</i></button></td>'+
			'</tr>');
			$.toaster({priority:'success',title:'¡Correcto!',message:'La relacion se agrego correctamente.',settings:{'timeout':10000,'toaster':{'css':{'top':'3em'}}}});
		}
		
	});
	
	$('#fk_id_serie').on('change',function () {
        let url = $(this).data('url');
        $.ajax({
            async: true,
            url: url,
            data: {'param_js':serie_js,$id_serie:$(this).val()},
            dataType: 'json',
            success: function (data) {
                if(data[0].sufijo){
                    $('#serie').val(data[0].prefijo+'-'+data[0].sufijo);
                }else{
                    $('#serie').val(data[0].prefijo);
                }
                var folio = !data[0].siguiente_numero ? 1 : +data[0].siguiente_numero;
                $('#folio').val(folio);
            }
        });
    });

    $('#fk_id_producto').on('change',function () {
		let url = $('#descripcion').data('url');
		let cliente = $('#fk_id_socio_negocio').val();
		let clave_cliente = $('#fk_id_producto').val();
		let descripcion = $('#descripcion');
		$.ajax({
			async:true,
			url:url,
			type:'POST',
			data:{fk_id_cliente:cliente,id_clave_cliente_producto:clave_cliente},
			dataType:'json',
			success: function (data) {
				$(descripcion).empty().append('<option value="" disabled selected>Selecciona una Opcion...</option>');
                $.each(data,function () {
                    descripcion.append('<option value="' + this.descripcion + '">' +  this.descripcion + '</option>');
                });
                descripcion.prop('disabled', (data.length == 0 | ver));
            }
		});
		var producto = $(this);
        var precio = producto.select2('data')[0].precio;
        var impuesto = producto.select2('data')[0].fk_id_impuesto;
        $('#precio_unitario').val(+precio.toString());
        $('#fk_id_impuesto').val(impuesto).trigger('change');

    });

    $('#agregar-concepto').on('click',function () {
        validateDetail();
        if($('#form-model').valid()){
            // var existe = false;
            // $('#tConceptos tbody tr').each(function (index,row) {
            //     if($(row).find('.detalle').val() == $('#fk_id_producto').val() && $(row).find('.tipo_documento').val() == $('#fk_id_producto').select2('data')[0].fk_id_tipo_documento){
            //         existe = true;
            //     }
            // });
            // if(!existe){
                var i = $('#tConceptos tbody tr').length;
                var row_id = i > 0 ? +$('#tConceptos tr:last').find('.index').val()+1 : 0;
                var producto = $('#fk_id_producto').select2('data')[0];

                var cantidad = +$('#cantidad').val();
                var precio_unitario = +$('#precio_unitario').val();
                var descuento = +$('#descuento_producto').val();
                var data_impuesto = $('#fk_id_impuesto').select2().find(':selected').data('impuesto');
                var impuesto = +data_impuesto.tasa_o_cuota;

                var subtotal = (cantidad*precio_unitario)-descuento;
                var impuesto_producto = (subtotal*impuesto);
                var total = subtotal;
                total = total;

                var id_moneda = null;
                var moneda = "";

                $('#tConceptos tbody').append(
                    '<tr>' +
                    '<td>' +
    	                '<input type="hidden" class="index" value="'+row_id+'">' +
	                    '<input type="hidden" name="relations[has][detalle]['+row_id+'][fk_id_clave_producto_servicio]" value="'+producto.fk_id_clave_producto_servicio+'">'+producto.claveproductoservicio.clave_producto_servicio +
                    	// '<input type="hidden" class="detalle" value="'+$('#fk_id_producto').val()+'">' +
                    '</td>' +
                    '<td><input type="hidden" name="relations[has][detalle]['+row_id+'][fk_id_clave_cliente]" value="'+producto.id+'">'+producto.text+'</td>' +
                    '<td><input type="hidden" name="relations[has][detalle]['+row_id+'][fk_id_sku]" value="'+producto.fk_id_sku+'"><input type="hidden" name="relations[has][detalle]['+row_id+'][descripcion]" value="'+$("#descripcion").val()+'"><input type="hidden" name="relations[has][detalle]['+row_id+'][fk_id_upc]" value="'+producto.fk_id_upc+'">'+$("#descripcion").val()+'</td>' +
                    '<td><input type="hidden" name="relations[has][detalle]['+row_id+'][fk_id_unidad_medida]" value="'+producto.fk_id_clave_unidad+'">'+producto.claveunidad.clave_unidad+'</td>' +
                    '<td><input type="hidden" name="relations[has][detalle]['+row_id+'][fk_id_moneda]"></td>' +
                    '<td><input type="hidden" name="relations[has][detalle]['+row_id+'][cantidad]" class="cantidad" value="'+cantidad+'">'+cantidad+'</td>' +
                    '<td><input type="hidden" name="relations[has][detalle]['+row_id+'][precio_unitario]" class="precio_unitario" value="'+precio_unitario+'">$'+precio_unitario+'</td>' +
                    '<td><input type="hidden" name="relations[has][detalle]['+row_id+'][descuento]" class="descuento" value="'+descuento+'">$'+descuento+'</td>' +
                    '<td>' +
                        '<input type="hidden" value="'+data_impuesto.descripcion+'" class="tipo_impuesto"><input type="hidden" class="porcentaje" value="'+data_impuesto.porcentaje+'"><input type="hidden" name="relations[has][detalle]['+row_id+'][fk_id_impuesto]" value="'+data_impuesto.id_impuesto+'"><input type="hidden" name="relations[has][detalle]['+row_id+'][impuesto]" class="impuesto" value="'+impuesto_producto+'"><span>'+$('#fk_id_impuesto option:selected').text()+'</span><br><span style="font-size: 11px"><b>$'+impuesto_producto+'<b/></span>' +
                        '<input type="hidden" name="relations[has][detalle]['+row_id+'][fk_id_moneda]" value="'+producto.proyectoproducto.fk_id_moneda+'">' +
                    '</td>' +
                    '<td><input type="text" name="relations[has][detalle]['+row_id+'][pedimento]" class="form-control pedimento"></td>' +
                    '<td><input type="text" name="relations[has][detalle]['+row_id+'][cuenta_predial]" class="form-control cuenta_predial"></td>' +
                    '<td><input type="hidden" name="relations[has][detalle]['+row_id+'][importe]" class="total" value="'+total+'">$'+total+'</td>' +
                    '<td><button class="btn is-icon text-primary bg-white" type="button" data-delay="50" onclick="borrarFila(this,\'total\')" data-tooltip="Producto"> <i class="material-icons">delete</i></button></td>' +
                    '</tr>'
                );
                total_factura();
                limpiarCampos();
            }else{
                $.toaster({priority:'danger',title:'¡Error!',message:'Producto ya agregado.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
                // limpiarCampos();
            }
        // }
    });

    $('#impuestos_accordion').on('show.bs.collapse',function () {
        $('.add').text('remove');
    });
    $('#impuestos_accordion').on('hide.bs.collapse',function () {
        $('.add').text('add');
    });
});

function formatProducto (producto) {
    if (producto.element) {
        // theme here ...
        // var data = JSON.parse(producto.element.dataset.producto);
        //Generamos nuestro template
		var precio = +producto.precio;
        var markup = "<div class='select2-result-pers clearfix'>" +
            "<div class='select2-result-pers__avatar'><img src='img/sku.png'/></div>" +
            "<div class='select2-result-pers__meta'>" +
            "<div class='select2-result-pers__text'>" + producto.text + "</div>";
        markup += "<div class='select2-result-pers__precio'><b>Precio:</b> $" + precio + "</div>";
        markup += "<div class='select2-result-pers__impuesto'><b>Impuesto:</b> " + producto.impuesto.impuesto +"</div>";
        markup += "</div></div>";
        return markup;
    }
    return producto.text;
}

function formatProductoSelection (producto) {
    return producto.text;
}

function borrarFila(el,tipo = null) {
	$(el).parent().parent('tr').remove();
	switch(tipo){
		case 'cfdi':
			cargar_productos();
			var factura_eliminada = $(el).parent().parent('tr').find('input:last').val();
			var tipo_factura_eliminada = $(el).parent().parent('tr').find('.tipo_documento').val();
			$('#tConceptos tbody tr').each(function (index,row) {
				if($(row).find('.factura').val() == factura_eliminada && $(row).find('.tipo_documento').val() == tipo_factura_eliminada){
					$(row).remove();
				}
			});
			break;
		case 'total':
			total_factura();
			break;
		default:
			break;
	}
	$.toaster({priority:'success',title:'¡Correcto!',message:'Se ha eliminado correctamente el '+$(el).data('tooltip'),settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
}

function validateDetail() {
    $('#fk_id_producto').rules('add',{
        required:true,
        messages:{
            required:"Selecciona un producto"
        }
    });
    $('#cantidad').rules('add',{
        required: true,
        number: true,
        range: [1,9999],
        messages:{
            required: 'Ingresa una cantidad',
            number: 'El campo debe ser un número',
            range: 'El número debe ser entre 1 y 9999'
        }
    });
    $.validator.addMethod('precio',function (value,element) {
        return this.optional(element) || /^\d{0,10}(\.\d{0,2})?$/g.test(value);
    },'El precio no debe tener más de dos decimales');
    $.validator.addMethod( "greaterThan", function( value, element, param ) {
        return value > param;
    }, "Please enter a greater value." );
    $('#precio_unitario').rules('add',{
        required: true,
        number: true,
        precio:true,
        greaterThan:0,
        messages:{
            required: 'Ingresa un precio unitario',
            number: 'El campo debe ser un número',
            greaterThan: 'El número debe ser mayor a 0',
            precio: 'El precio no debe tener más de dos decimales'
        }
    });
    $.validator.addMethod( "lessThan", function( value, element, param ) {
        return value <= param;
    }, "Please enter a greater value." );
    var menorque = $('#precio_unitario').val() * $('#cantidad').val();
    $('#descuento_producto').rules('add',{
        greaterThan: -1,
        precio: true,
        lessThan: menorque,
        messages:{
            greaterThan: 'El número no debe ser menor a 0',
            lessThan: 'El descuento debe ser menor al precio por la cantidad',
            precio: 'El precio no debe tener más de dos decimales y diez enteros'
        }
    });

    $('#fk_id_impuesto').rules('add',{
        required:true,
        messages:{
            required: 'El campo de impuesto es requerido'
        }
    });

    $('#descripcion').rules('add',{
        required:true,
        messages:{
            required: 'El campo de descripcion es requerido'
        }
    });
}

function limpiarCampos() {
    $('#fk_id_producto').rules('remove');
    $('#cantidad').val('').rules('remove');
    $('#fk_id_impuesto').rules('remove');
    $('#precio_unitario').val('').rules('remove');
    $('#descuento_producto').val('').rules('remove');
    $('#descripcion').val('').trigger('change').rules('remove');
}

function total_factura() {

    $('#impuestos_descripcion tbody tr').remove();
    var impuestos = [];
    var subtotal_factura = 0;
    var descuento_factura = 0;

    var total = 0;
    $('#tConceptos tbody tr').each(function (i,row) {
        var cantidad = +$(row).find('.cantidad').val();
        var precio_unitario = +$(row).find('.precio_unitario').val();
        var descuento = +$(row).find('.descuento').val();
        var tipo_impuesto = $(row).find('.tipo_impuesto').val();
        var impuesto = +$(row).find('.impuesto').val();
        var porcentaje = +$(row).find('.porcentaje').val();
        var impuesto_temporal = [];
        total += +$(row).find('.total').val();
        subtotal_factura += (cantidad*precio_unitario)-descuento;
        descuento_factura += descuento;

        //Para impuestos dependiendo del tipo
        impuesto_temporal.tipo_impuesto = tipo_impuesto;
        impuesto_temporal.porcentaje = porcentaje;
        var existe = $.grep(impuestos,function (n) {
            return (n.tipo_impuesto == tipo_impuesto && n.porcentaje == porcentaje);
        });
        if(!existe.length){
            impuesto_temporal.impuesto = impuesto;
            impuestos.push(impuesto_temporal);
        }else{
            $.each(impuestos,function (i,v) {
                var boolean = true;
                if(v.tipo_impuesto == tipo_impuesto && v.porcentaje == porcentaje){
                    impuestos[i].impuesto += impuesto;
                    boolean = false;
                }
                return boolean;
            })
        }
    });
    //Subtotal
    $('#subtotal').val(subtotal_factura);
    $('#subtotal_span').text('$'+subtotal_factura);

    //Descuentos
    $('#descuento_span').text('$'+descuento_factura);
    $('#descuento').val(descuento_factura);
    var impuestos_html = '';
    var impuesto = 0;
    //Impuestos
    $.each(impuestos,function (index,impuesto_row) {
        impuesto += impuesto_row.impuesto;
        impuestos_html +=
            '<tr>' +
            '<th class="pl-4" style="font-size: 14px">'+impuesto_row.tipo_impuesto+' '+impuesto_row.porcentaje+'%</th>' +
            '<td class="text-right">$'+impuesto_row.impuesto+'</td>' +
            '<td></td>' +
            '</tr>'
    });
    $('#impuestos_descripcion').append(impuestos_html);
    $('#impuestos').val(impuesto);
    $('#impuesto_label').text('$'+impuesto);

    // total = total - +$('#descuento').val();
    // total = total;

    //Total
    $('#total').val((+total + +impuesto));
    $('#total_span').text('$'+(+total + +impuesto));
}