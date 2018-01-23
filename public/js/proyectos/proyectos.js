$(document).ready(function () {
	var from_picker = $('#fecha_inicio').pickadate({ selectMonths: true, selectYears: 3, format: 'yyyy-mm-dd' }).pickadate('picker');
	var to_picker = $('#fecha_terminacion').pickadate({ selectMonths: true, selectYears: 3, format: 'yyyy-mm-dd' }).pickadate('picker');
	
	from_picker.on('set', function(event) {
		if ( 'select' in event ) {
			to_picker.start().clear().set('min', from_picker.get('select'));
	    }

	    if ( 'clear' in event ) {
	    	to_picker.clear().set('min', false).stop();
	    	$('#fecha_terminacion').prop('readonly', true);
		  }
	});
	
	var from_contrato = $('#fecha_inicio_contrato').pickadate({ selectMonths: true, selectYears: 3, format: 'yyyy-mm-dd' }).pickadate('picker');
	var to_contrato = $('#fecha_fin_contrato').pickadate({ selectMonths: true, selectYears: 3, format: 'yyyy-mm-dd' }).pickadate('picker');
	
	from_contrato.on('set', function(event) {
		if ( 'select' in event ) {
			to_contrato.start().clear().set('min', from_contrato.get('select'));
	    }

	    if ( 'clear' in event ) {
	    	to_contrato.clear().set('min', false).stop();
	    	$('#fecha_fin_contrato').prop('readonly', true);
		  }
	});
	
    $(".nav-link").click(function (e) {
        e.preventDefault();
        $('#clothing-nav li').each(function () {
            $(this).children().removeClass('active');
        });
        $('.tab-pane').removeClass('active').removeClass('show');
        $(this).addClass('active');
        var tab = $(this).prop('href');
        tab = tab.split('#');
        $('#' + tab[1]).addClass('active').addClass('show');
    });
    
    $('#agregarContrato').on('click', function() {
    	var i = $('#detalleContratos tbody tr').length;
        var row_id = i > 0 ? +$('#detalleContratos tr:last').find('.index').val()+1 : 0;
		
        representante = $('#representante_legal').val();
        contrato = $('#num_contrato').val();
        fechainicio = $('#fecha_inicio_contrato').val();
        fechafin = $('#fecha_fin_contrato').val();
		archivo = $("#contrato").prop('files');
		
		if(representante == '' | contrato == '' | fechainicio == '' | fechafin == '' | $("#contrato").length == 0) {
			$.toaster({priority:'danger',title:'¡Error!',message:'Debe introducir el representante legal, No. contrato, fecha inicio, fecha fin y archivo.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
		}
		else {
			$('#detalleContratos').append('<tr>'+
				'<td>' +
					'<input class="index" name="relations[has][contratos]['+row_id+'][index]" type="hidden" value="'+row_id+'">'+
				    '<input name="relations[has][contratos]['+row_id+'][representante_legal]" type="hidden" value="'+representante+'">'+representante+
				'</td>'+
				'<td><input name="relations[has][contratos]['+row_id+'][num_contrato]" type="hidden" value="'+contrato+'">'+contrato+'</td>'+
				'<td><input name="relations[has][contratos]['+row_id+'][fecha_inicio]" type="hidden" value="'+fechainicio+'">'+fechainicio+'</td>'+
				'<td><input name="relations[has][contratos]['+row_id+'][fecha_fin]" type="hidden" value="'+fechafin+'">'+fechafin+'</td>'+
				'<td>' +
                    '<label class="custom-file">' +
                        '<input name="relations[has][contratos]['+row_id+'][file]" class="custom-file-input" onchange="file_name(this)" data-toggle="custom-file" data-target="#fileContrato-'+row_id+'-span" accept=".pdf" type="file" id="fileContrato-'+row_id+'">' +
                        '<span id="fileContrato-'+row_id+'-span" class="custom-file-control custom-file-name archivo" data-content="Selecciona un contrato..."></span>' +
                    '</label>' +
                    // '<input name="relations[has][contratos]['+row_id+'][file]" type="file" class="archivo" id="fileContrato-'+row_id+'" style="display:none">'+archivo[0].name+ +
                '</td>'+
				'<td><button class="btn is-icon text-primary bg-white" type="button" data-delay="50" onclick="borrarFila(this)" data-tooltip="Anexo"> <i class="material-icons">delete</i></button></td>'+
			'</tr>');
			$('#fileContrato-'+row_id).prop('files',archivo);
			$.toaster({priority:'success',title:'Ãƒâ€šÃ‚Â¡Correcto!',message:'El archivo se agrego correctamente.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
		}
	});
    
    $('#agregarAnexo').on('click', function() {
    	var i = $('#detalleAnexos tbody tr').length;
        var row_id = i > 0 ? +$('#detalleAnexos tr:last').find('#index').val()+1 : 0;
		
		nombre = $('#nombre_archivo').val();
		archivo = $("#archivo").prop('files');
		
		if(nombre == '') {
			$.toaster({priority:'danger',title:'¡Error!',message:'Debe introducir el nombre para el documento.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
		}
		else if($("#archivo").length == 0) {
			$.toaster({priority:'danger',title:'¡Error!',message:'Selecciona un archivo.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
		}
		else {
			$('#detalleAnexos').append('<tr>'+
				'<td><input class="index" name="relations[has][anexos]['+row_id+'][index]" type="hidden" value="'+row_id+'">'+
					'<input name="relations[has][anexos]['+row_id+'][nombre]" type="hidden" value="'+nombre+'">'+nombre+'</td>'+
				'<td><input name="relations[has][anexos]['+row_id+'][file]" type="file" class="archivo" id="fileAnexo-'+row_id+'" style="display:none">'+archivo[0].name+'</td>'+
				'<td><button class="btn is-icon text-primary bg-white" type="button" data-delay="50" onclick="borrarFila(this)" data-tooltip="Anexo"> <i class="material-icons">delete</i></button></td>'+
			'</tr>');
			$('#fileAnexo-'+row_id).prop('files',archivo);
			$.toaster({priority:'success',title:'¡Correcto!',message:'El archivo se agrego correctamente.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
		}
	});

    $('#num_evento').on('keyup',function () {
		$('#importar_liciplus').attr('disabled','disabled');
        $('#importar_contratos').attr('disabled','disabled');
        $('#importar_productos').attr('disabled','disabled');
    	if(!$('#num_evento').val()){
    		$('#importar_liciplus').attr('disabled','disabled');
		}else{
            $('#importar_liciplus').removeAttr('disabled');
        }
    });

    $('#importar_liciplus').click(function () {
    	$('#loadinglicitacion').show();
        $.ajax({
			url: $(this).data('url'),
			data: {
				'param_js':licitacion_js,
				$num_evento:$('#num_evento').val()
			},
			dataType:'JSON',
			success: function (data) {
				if(data.length > 0){
					var tipo_evento = data[0].tipo_evento.split('||');
					$('#fk_id_tipo_evento').val(tipo_evento[0]).trigger('change');
					var dependencia = data[0].dependencia.split('||');
					dependencia = dependencia.length == 1 ? [0,dependencia] : dependencia;
					$('#fk_id_dependencia').val(dependencia[0]).trigger('change');
                    var subdependencia = data[0].subdependencia.split('||');
                    subdependencia = subdependencia.length == 1 ? [0,subdependencia] : subdependencia;
                    $('#fk_id_subdependencia').val(subdependencia[0]).trigger('change');
                    var modalidad_entrega = data[0].modalidad_entrega.split('||');
                    modalidad_entrega = modalidad_entrega.length == 1 ? [0,modalidad_entrega] : modalidad_entrega;
                    $('#fk_id_modalidad_entrega').val(modalidad_entrega[0]).trigger('change');
                    var caracter_evento = data[0].caracter_evento.split('||');
                    caracter_evento = caracter_evento.length == 1 ? [0,caracter_evento] : caracter_evento;
                    $('#fk_id_caracter_evento').val(caracter_evento[0]).trigger('change');
                    var forma_adjudicacion = data[0].forma_adjudicacion.split('||');
                    forma_adjudicacion =  forma_adjudicacion.length == 1 ? [0,forma_adjudicacion] : forma_adjudicacion;
                    $('#fk_id_forma_adjudicacion').val(forma_adjudicacion[0]).trigger('change');
                    $('#pena_convencional').val(data[0].pena_convencional);
                    $('#tope_pena_convencional').val(data[0].tope_pena_convencional);
                    $.toaster({priority:'success',title:'LICIPLUS',message:'Licitación encontrada.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
                    $('#importar_contratos').removeAttr('disabled');
                    $('#importar_productos').removeAttr('disabled');
				}else{
                    $.toaster({priority:'info',title:'LICIPLUS',message:'No se encontró ninguna licitación.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
                    $('#importar_contratos').attr('disabled','disabled');
                    $('#importar_productos').attr('disabled','disabled');
                }
                $('#loadinglicitacion').hide();
            }
		});
    });

    $('#importar_contratos').click(function () {
    	$.ajax({
			url: $('#importar_contratos').data('url'),
			data:{
                'param_js':contratos_js,
                $num_contrato:$('#num_evento').val()
			},
			dataType:'JSON',
			success: function (data) {
				if(!data.length){
                    $.toaster({priority:'info',title:'LICIPLUS',message:'No se encontraron contratos relacionados',
                        settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
				}else{
					var repetidos = [];
					$.each(data,function (indice,dato) {
						// alert(dato);
						$('#detalleContratos > tbody > tr').each(function () {
							if( data[indice].no_contrato == $(this).find('td:eq(1)').find('input').val()){
								repetidos.push(data[indice].no_contrato);
							}
                        });
						if(!repetidos.length){
                            var i = $('#detalleContratos tbody tr').length;
                            var row_id = i > 0 ? +$('#detalleContratos tr:last').find('.index').val()+1 : 0;
                            $('#detalleContratos').append('<tr>'+
                                '<td>' +
                                '<input class="index" name="relations[has][contratos]['+row_id+'][index]" type="hidden" value="'+row_id+'">'+
                                '<input name="relations[has][contratos]['+row_id+'][representante_legal]" type="hidden" value="'+dato.representante_legal_cliente+'">'+dato.representante_legal_cliente+
                                '</td>'+
                                '<td><input name="relations[has][contratos]['+row_id+'][num_contrato]" type="hidden" value="'+dato.no_contrato+'">'+dato.no_contrato+'</td>'+
                                '<td><input name="relations[has][contratos]['+row_id+'][fecha_inicio]" type="hidden" value="'+dato.vigencia_fecha_inicio+'">'+dato.vigencia_fecha_inicio+'</td>'+
                                '<td><input name="relations[has][contratos]['+row_id+'][fecha_fin]" type="hidden" value="'+dato.vigencia_fecha_fin+'">'+dato.vigencia_fecha_fin+'</td>'+
                                '<td>' +
									'<label class="custom-file">' +
										'<input name="relations[has][contratos]['+row_id+'][file]" class="custom-file-input archivo" onchange="file_name(this)" data-toggle="custom-file" data-target="#fileContrato-'+row_id+'-span" accept=".pdf" type="file" id="fileContrato-'+row_id+'">' +
										'<span id="fileContrato-'+row_id+'-span" class="custom-file-control custom-file-name" data-content="Selecciona un contrato..."></span>' +
									'</label>' +
								'</td>'+
                                '<td><button class="btn is-icon text-primary bg-white" type="button" data-delay="50" onclick="borrarFila(this)" data-tooltip="Contrato"> <i class="material-icons">delete</i></button></td>'+
                                '</tr>');
						}
                    });

					if(repetidos.length){
                        $.toaster({priority:'danger',title:'¡Error!',message:'Algunos contratos ya se encuentran en la tabla: '+repetidos.toString(),
                            settings:{'donotdismiss':['danger'],'toaster':{'css':{'top':'5em'}}}});
					}else{
                        $.toaster({priority:'success',title:'¡Éxito!',message:'Se han agregado los contratos relacionados de LICIPLUS',
                            settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
					}
				}
            },
			error: function () {
				$.toaster({priority:'danger',title:'¡Error!',message:'Ocurrió un error al obtener los contratos',
					settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
            }
		});
    });

    $('#importar_productos').click(function () {
    	if(!$('#fk_id_cliente').val()){
            $.toaster({priority:'danger',title:'¡Error!',message:'Por favor selecciona un cliente',
                settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
		}else{
			$.ajax({
				url: $('#importar_productos').data('url'),
				data: {
					'param_js':partidas_js,
					$num_contrato:$('#num_evento').val()
				},
				dataType: 'JSON',
				success: function (datos) {
					$.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
					$.ajax({
						url: $('#agregarProducto').data('url'),
						data: {
							productos:datos,
							fk_id_cliente: $('#fk_id_cliente').val()
						},
						dataType: 'JSON',
						type: 'POST',
						success: function (respuesta) {
                            var error = '';
                            $.each(respuesta[1], function (index,clave) {
                                error += clave + ', ';
                            });
                            if (error)
                                $.toaster({
                                    priority: 'danger',
                                    title: 'No se encontraron las siguientes claves de cliente',
                                    message: '<br>' + error,
                                    settings: {'toaster': {'css': {'top': '5em'}}, 'donotdismiss': ['danger'],},
                                });

                            error = '';
                            $.each(respuesta[2], function (index,upc) {
                                error += upc + ', ';
                            });
                            if (error)
                                $.toaster({
                                    priority: 'danger',
                                    title: 'No se encontraron los siguientes UPCs',
                                    message: '<br>' + error,
                                    settings: {'toaster': {'css': {'top': '5em'}}, 'donotdismiss': ['danger'],},
                                });
                            //Importar las filas a la tabla
                            var repetidos = [];
                            $.each(respuesta[0], function (index, value) {
                            	var repetido = false;
                                $('#detalleProductos > tbody > tr').each(function () {
                                    if( value.clave == $(this).find('span').text()){
                                        repetidos.push(value.clave);
                                        repetido = true;
                                    }
                                });
                                if(!repetido){
                                    var i = $('#detalleProductos tbody tr').length;
                                    var row_id = i > 0 ? +$('#detalleProductos tr:last').find('.index').val()+1 : 0;
                                    var id_upc = 0;
                                    var text_upc = 'Sin UPC';
                                    var descripcion_upc = '';
                                    if (value['fk_id_upc']) {
                                        id_upc = value['fk_id_upc'];
                                        text_upc = value['codigo_barras'];
                                        descripcion_upc = value['descripcion'];
                                    }

                                    $('#detalleProductos').append('<tr>'+
                                        '<td><input class="index" name="relations[has][productos]['+row_id+'][index]" type="hidden" value="'+row_id+'">'+
                                        '<input class="index" name="relations[has][productos]['+row_id+'][id_proyecto_producto]" type="hidden" value="">'+
                                        '<input type="hidden" name="relations[has][productos]['+row_id+'][fk_id_clave_cliente_producto]" value="'+value['id_clave_cliente_producto']+'"/><span>'+ value['clave']+'</span></td>'+
                                        '<td>'+value['descripcion_clave']+'</td>'+
                                        '<td>'+$('<input type="hidden" name="relations[has][productos][' + row_id + '][fk_id_upc]" value="' + id_upc + '" />')[0].outerHTML + text_upc+'</td>'+
                                        '<td>'+descripcion_upc+'</td>'+
                                        '<td>'+$('<input class="form-control prioridad" maxlength="2" name="relations[has][productos][' + row_id + '][prioridad]" type="text" value="" />')[0].outerHTML+'</td>'+
                                        '<td>'+$('<input class="form-control cantidad" maxlength="3" name="relations[has][productos][' + row_id + '][cantidad]" type="text" value="" />')[0].outerHTML+'</td>'+
                                        '<td>'+$('<input class="form-control precio_sugerido" maxlength="13" name="relations[has][productos][' + row_id + '][precio_sugerido]" type="text" value="'+value['costo']+'" />')[0].outerHTML+'</td>'+
                                        '<td>'+$('#campo_moneda').html().replace('$row_id',row_id).replace('$row_id',row_id)+'</td>'+
                                        '<td>'+$('<input class="form-control maximo" maxlength="4" name="relations[has][productos][' + row_id + '][maximo]" type="text" value="'+value['cantidad_maxima']+'" />')[0].outerHTML+'</td>'+
                                        '<td>'+$('<input class="form-control minimo" maxlength="4" name="relations[has][productos][' + row_id + '][minimo]" type="text" value="'+value['cantidad_minima']+'" />')[0].outerHTML+'</td>'+
                                        '<td>'+$('<input class="form-control numero_reorden" maxlength="4" name="relations[has][productos][' + row_id + '][numero_reorden]" type="text" value="" />')[0].outerHTML+'</td>'+
                                        '<td>'+$('<div class="form-check">' +
                                            '<label class="form-check-label custom-control custom-checkbox">' +
                                            '<input type="checkbox" class="form-check-input custom-control-input" checked value="1" name="relations[has][productos]['+row_id+'][activo]" />' +
                                            '<span class="custom-control-indicator"></span>' +
                                            '</label>' +
                                            '</div>')[0].outerHTML+
                                        '</td>'+
                                        '<td><button class="btn is-icon text-primary bg-white" type="button" data-delay="50" onclick="borrarFila(this)" data-tooltip="Producto"><i class="material-icons">delete</i></button>'+
                                        '</tr>');

                                }
                            });
                            if(repetidos.length){
                                $.toaster({priority:'info',title:'¡Error!',message:'Algunos productos ya se encuentran en la tabla: '+repetidos.toString(),
                                    settings:{'donotdismiss':['danger'],'toaster':{'css':{'top':'5em'}}}});
                            }else{
                                $.toaster({priority: 'success', title: '!Correcto!', message: 'Productos importados con Exito',settings: {'timeout': 10000, 'toaster': {'css': {'top': '5em'}}}});
							}
                            $('.loadingtabla').hide();
						},
						error: function () {

						}
					});
				}
			})
    	}
    });

    $("#fk_id_cliente").on('select2:selecting',function() {
    	var val = $('#fk_id_cliente').val() ? $('#fk_id_cliente').val() : 0;
        $('#fk_id_cliente').attr("data-old",val).trigger('change');
    });

    $('#fk_id_cliente').change(function () {
        $('#confirmacion').modal('show');
    });

    $('#confirmar').click(function () {//Confirmar cambio de cliente
        $('#confirmacion').modal('hide');
        $('#detalleProductos tbody tr').remove();
		$('#detalleContratos tbody tr').remove();
        $('#detalleAnexos tbody tr').remove();
        $('#num_evento').val('');
        $('#fk_id_tipo_evento').val(0).trigger('change');
        $('#fk_id_dependencia').val(0).trigger('change');
        $('#fk_id_subdependencia').val(0).trigger('change');
        $('#fk_id_caracter_evento').val(0).trigger('change');
        $('#fk_id_forma_adjudicacion').val(0).trigger('change');
        $('#pena_convencional').val('');
        $('#tope_pena_convencional').val('');
        $('#importar_liciplus').attr('disabled','disabled');
        $('#importar_contratos').attr('disabled','disabled');
        $('#importar_productos').attr('disabled','disabled');
        $('#fk_id_localidad').val(0).trigger('change');

        //En productos carga las claves relacionadas con el cliente actual
        var _url = $('#fk_id_clave_cliente_producto').data('url').replace('?id',$('#fk_id_cliente').val());
        $('#fk_id_clave_cliente_producto').empty().prop('disabled',true);
        $('#loadingfk_id_clave_cliente_producto').show();
        $.ajax({
            url: _url,
            dataType:'json',
            success:function (data) {
                var option = $('<option/>');
                option.val(0);
                option.attr('disabled','disabled');
                option.attr('selected','selected');
                if (Object.keys(data).length == 0)
                    option.text('No se encontraron elementos');
                else
                    option.text('...');

                $('#fk_id_clave_cliente_producto').prepend(option).select2({
                    minimumResultsForSearch:'50',
                    data:data,
                }).attr('disabled',false);
                $('#loadingfk_id_clave_cliente_producto').hide();
            }
        });
        cargar_sucursales();
    });

    $('#cancelarcambiocliente').click(function () {
    	var val = $('#fk_id_cliente').data('old');
		$('#fk_id_cliente').val(val).trigger('change');
    });

    $('#fk_id_localidad').on('change',function () {
        cargar_sucursales();
    });

    $(document).on('submit',function (e) {
        $.validator.addMethod('minStrict', function (value, element, param) {
            return value > param;
        },'El numero debe ser mayor a {0}');

        $.validator.addMethod('cRequerido',$.validator.methods.required,'Este campo es requerido');
        $.validator.addMethod('cDigits',$.validator.methods.digits,'El campo debe ser entero');
        $.validator.addClassRules('prioridad',{
            cRequerido: true,
            cDigits: true,
            minStrict: 0
        });
        $.validator.addClassRules('archivo',{
           cRequerido: true
        });
        $.validator.addClassRules('cantidad',{
            cRequerido: true,
            cDigits: true,
            minStrict: 0
        });

        $.validator.addClassRules('maximo',{
            cRequerido:true,
            cDigits:true,
            minStrict:0
        });
        $.validator.addClassRules('minimo',{
            cRequerido:true,
            cDigits:true,
            minStrict:0
        });
        $.validator.addClassRules('numero_reorden',{
            cRequerido:true,
            cDigits:true,
            minStrict:0
        });
        $.validator.addClassRules('fk_id_moneda',{
            cRequerido:true,
        });

        $.validator.addMethod('precio',function (value,element) {
            return this.optional(element) || /^\d{0,10}(\.\d{0,2})?$/g.test(value);
        },'Verifica la cantidad. Ej. 9999999999.00');
        $.validator.addClassRules('precio_sugerido',{
            cRequerido: true,
            precio: true,
            minStrict: 0
        });

        if(!$('#form-model').valid()){
            e.preventDefault();
            $('.prioridad').rules('remove');
            $('.cantidad').rules('remove');
            $('.precio_sugerido').rules('remove');
            $('.maximo').rules('remove');
            $('.minimo').rules('remove');
            $('.numero_reorden').rules('remove');
            $('.archivo').rules('remove');
            $.toaster({
                priority: 'danger', title: 'Â¡Error!', message: 'Hay campos que requieren de tu atencion',settings: {'timeout': 10000, 'toaster': {'css': {'top': '5em'}}}
            });
        }

    });

});

function borrarFila(el) {
    $(el).parent().parent('tr').remove();
    $.toaster({priority:'success',title:'Â¡Correcto!',message:'Se ha eliminado correctamente el '+$(el).data('tooltip'),settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
}

function cargar_sucursales() {
    if($('#fk_id_localidad').val() && $('#fk_id_cliente').val()){
        $('#loadingsucursales').show();
        $.ajax({
            url: $('#fk_id_cliente').data('url'),
            data: {
                'param_js':sucursales_js,
                $fk_id_cliente:$('#fk_id_cliente').val(),
                $fk_id_localidad:$('#fk_id_localidad').val()
            },
            dataType:'JSON',
            success: function (data) {
                var option = $('<option/>');
                option.val(0);
                option.text('...');
                option.attr('disabled','disabled');
                option.attr('selected','selected');
                $('#fk_id_sucursal').empty().removeAttr('disabled').prepend(option).select2({
                    data:data
                });
                $('#loadingsucursales').hide();
            }
        });
    }
}