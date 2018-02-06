var subtotal_original = 0;
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
                    series.prepend('<option value="0" disabled selected>Seleccione una serie...</option>')
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
		var id_tipo = $('#fk_id_tipo_relacion option:selected').val();
		var tipo_relacion = $('#fk_id_tipo_relacion option:selected').text();
		var tipo_documento = 0;
		var id_documento = 0;
		var documento = '';
		if($('#fk_id_factura_relacion').val()){
			tipo_documento = 4;
            id_documento = $('#fk_id_factura_relacion').val();
            documento = $('#fk_id_factura_relacion option:selected').text();
        }else if($('#fk_id_nota_credito_relacion').val()){
			tipo_documento = 6;
            id_documento = $('#fk_id_nota_credito_relacion').val();
            documento = $('#fk_id_nota_credito_relacion option:selected').text();
        }

		var existe = false;
		$('#detalleRelaciones tbody tr').each(function (index,row) {
			if($(row).find('input:last').val() == id_documento && tipo_documento == $(row).find('.tipo_documento').val()){
				existe = true;
			}
        });
		
		if(!id_tipo || !id_documento) {
			$.toaster({priority:'danger',title:'¡Error!',message:'Debe introducir el tipo de relacion y el documento a relacionar.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
		}else if(existe){
            $.toaster({priority:'danger',title:'¡Error!',message:'Documento ya agregado.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
		}
		else {
			$('#detalleRelaciones').append('<tr>'+
				'<td>' +
					'<input name="relations[has][relaciones]['+row_id+'][index]" class="index" type="hidden" value="'+row_id+'">'+
				    '<input name="relations[has][relaciones]['+row_id+'][fk_id_tipo_relacion]" type="hidden" value="'+id_tipo+'">'+tipo_relacion+
				'</td>'+
				'<td>' +
	                '<input name="relations[has][relaciones]['+row_id+'][fk_id_tipo_documento_relacionado]" class="tipo_documento" type="hidden" value="'+tipo_documento+'">'+documento+'' +
					'<input name="relations[has][relaciones]['+row_id+'][fk_id_documento_relacionado]" type="hidden" value="'+id_documento+'">' +
				'</td>'+
				'<td><button class="btn is-icon text-primary bg-white" type="button" data-delay="50" onclick="borrarFila(this,\'cfdi\')" data-tooltip="Anexo"> <i class="material-icons">delete</i></button></td>'+
			'</tr>');
			$.toaster({priority:'success',title:'¡Correcto!',message:'La relacion se agrego correctamente.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
			cargar_productos();
        }
        $('#loadingfk_id_producto').hide();
    });

	$('#agregar-concepto').click(function () {
		validateDetail();
		if($('#form-model').valid()){
			var existe = false;
			$('#tConceptos tbody tr').each(function (index,row) {
                if($(row).find('.detalle').val() == $('#fk_id_producto').val() && $(row).find('.tipo_documento').val() == $('#fk_id_producto').select2('data')[0].fk_id_tipo_documento){
                	existe = true;
				}
            });
			if(!existe){
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
                total = total.toFixed(2);

                $('#tConceptos tbody').append(
                    '<tr>' +
                    '<td><input type="hidden" class="index" value="'+row_id+'"><input type="hidden" class="detalle" value="'+$('#fk_id_producto').val()+'"><input type="hidden" name="relations[has][detalle]['+row_id+'][fk_id_documento_base]" class="factura" value="'+producto.fk_id_documento+'"><input type="hidden" name="relations[has][detalle]['+row_id+'][fk_id_tipo_documento_base]" class="tipo_documento" value="'+producto.fk_id_tipo_documento+'">'+producto.serie+'-'+producto.folio+'</td>' +
                    '<td><input type="hidden" name="relations[has][detalle]['+row_id+'][fk_id_clave_producto_servicio]" value="'+producto.fk_id_clave_producto_servicio+'">'+producto.clave_producto_servicio+'</td>' +
                    '<td><input type="hidden" name="relations[has][detalle]['+row_id+'][fk_id_clave_cliente]" value="'+producto.fk_id_clave_cliente+'">'+producto.clave_producto_cliente+'</td>' +
                    '<td><input type="hidden" name="relations[has][detalle]['+row_id+'][fk_id_sku]" value="'+producto.fk_id_sku+'"><input type="hidden" name="relations[has][detalle]['+row_id+'][descripcion]" value="'+producto.descripcion+'"><input type="hidden" name="relations[has][detalle]['+row_id+'][fk_id_upc]" value="'+producto.fk_id_upc+'">'+producto.descripcion+'</td>' +
                    '<td><input type="hidden" name="relations[has][detalle]['+row_id+'][fk_id_unidad_medida]" value="'+producto.fk_id_unidad_medida+'">'+producto.unidad_medida+'</td>' +
                    '<td><input type="hidden" name="relations[has][detalle]['+row_id+'][cantidad]" class="cantidad" value="'+cantidad+'">'+cantidad+'</td>' +
                    '<td><input type="hidden" name="relations[has][detalle]['+row_id+'][precio_unitario]" class="precio_unitario" value="'+precio_unitario+'">$'+precio_unitario+'</td>' +
                    '<td><input type="hidden" name="relations[has][detalle]['+row_id+'][descuento]" class="descuento" value="'+descuento+'">'+descuento+'</td>' +
                    '<td><input type="hidden" value="'+data_impuesto.descripcion+'" class="tipo_impuesto"><input type="hidden" class="porcentaje" value="'+data_impuesto.porcentaje+'"><input type="hidden" name="relations[has][detalle]['+row_id+'][fk_id_impuesto]" value="'+$('#fk_id_impuesto').val()+'"><input type="hidden" name="relations[has][detalle]['+row_id+'][impuesto]" class="impuesto" value="'+impuesto_producto.toFixed(2	)+'"><span>'+$('#fk_id_impuesto option:selected').text()+'</span><br><span style="font-size: 11px"><b>$'+impuesto_producto.toFixed(2)+'<b/></span></td>' +
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
                limpiarCampos();
			}
        }
    });

	$('#descuento').on('keypress keyup',function () {
        total_factura();
    });

	$('#impuestos_accordion').on('show.bs.collapse',function () {
		$('.add').text('remove');
    });
    $('#impuestos_accordion').on('hide.bs.collapse',function () {
        $('.add').text('add');
    });
    $(document).on('submit',function (e) {
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
                priority: 'danger', title: 'Â¡Error!', message: 'Hay campos que requieren de tu atencion',settings: {'timeout': 10000, 'toaster': {'css': {'top': '5em'}}}
            });
		}

		if(+$('#descuento').val() > +$('#subtotal').val()){
			e.preventDefault();
            $.toaster({
                priority: 'danger', title: 'Â¡Error!', message: 'El descuento general no puede ser mayor al subtotal',settings: {'timeout': 10000, 'toaster': {'css': {'top': '5em'}}}
            });
		}
    });
    $("#fk_id_factura_relacion").on('select2:selecting',function() {
    	$('#fk_id_nota_cargo_relacion').val(0).trigger('change');
    });
    $("#fk_id_nota_cargo_relacion").on('select2:selecting',function() {
        $('#fk_id_factura_relacion').val(0).trigger('change');
    });
    $('#timbrar').on('click', function(e) {
        $("#form-model").append('<input name="timbrar" type="hidden" value="1">');
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
    })
});

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
        subtotal_factura += cantidad*precio_unitario;
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
	descuento_factura += +$('#descuento').val();//Total Descuentos
	//Subtotal
	$('#subtotal').val(subtotal_factura);
	$('#subtotal_span').text('$'+subtotal_factura);

	//Descuentos
    $('#descuento_span').text('$'+descuento_factura);

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

	total = total - +$('#descuento').val();
	total = total.toFixed(2);

	//Total
	$('#total').val(total);
	$('#total_span').text('$'+total);
}

function cargar_productos() {
    $('#loadingfk_id_producto').show();
    $('#fk_id_producto').empty();
    var facturas = [];
    var notascargo = []
    $('#detalleRelaciones > tbody > tr').each(function (index,row) {
        if($(row).find('.tipo_documento').val() == 4){//Si es factura
            facturas.push(+$(this).find('input:last').val());
        }else if($(row).find('.tipo_documento').val() == 6){//Si es nota de cargo
            notascargo.push(+$(this).find('input:last').val());
        }

    });
    $.ajax({
        async: true,
        url: $('#fk_id_tipo_relacion').data('url'),
        data: {detallesfacturas:JSON.stringify(facturas),detallesnotas:JSON.stringify(notascargo)},
        dataType: 'json',
        success: function (datos) {
            $('#fk_id_producto').select2({
				data:datos,
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
    $('#loadingfk_id_producto').show();
}

function formatProducto (producto) {
    if (producto.element) {
        // theme here ...
        // var data = JSON.parse(producto.element.dataset.producto);
        //Generamos nuestro template
        var markup = "<div class='select2-result-pers clearfix'>" +
            "<div class='select2-result-pers__avatar'><img src='img/sku.png'/></div>" +
            "<div class='select2-result-pers__meta'>" +
            "<div class='select2-result-pers__text'>" + producto.text + "</div>";

        var tipo_documento = '';
        if(producto.fk_id_tipo_documento == 4){
        	tipo_documento = "factura";
		}else if(producto.fk_id_tipo_documento == 6){
        	tipo_documento = "nota de cargo";
		}

        markup += "<div class='select2-result-pers__factura'>Factura: " + producto.serie+'-'+producto.folio + "</div>";
        markup += "<div class='select2-result-pers__sku'>SKU: " + producto.sku+ "</div>";
        markup += "<div class='select2-result-pers__upc'>Clave Cliente: " + producto.clave_producto_cliente+ "</div>";
        markup += "<div class='select2-result-pers__tipodocumento'>Tipo documento: " + tipo_documento+ "</div>";
        markup += "</div></div>";
        return markup;
    }
    return producto.text;
}

function formatProductoSelection (producto) {
    return producto.text;
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

    $('#descuento').rules('add',{
        precio: true,
        greaterThan: -1,
        messages: {
            precio: 'El formato es inválido',
			greaterThan: 'El valor no puede ser negativo'
        }
    });
}

function limpiarCampos() {
    $('#fk_id_producto').rules('remove');
    $('#cantidad').val('').rules('remove');
    $('#fk_id_impuesto').rules('remove');
    $('#precio_unitario').val('').rules('remove');
    $('#descuento_producto').val('').rules('remove');
}