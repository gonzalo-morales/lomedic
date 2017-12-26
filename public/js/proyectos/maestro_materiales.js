//var eliminarProyectoProducto=[];

// Inicializar los datepicker para las fechas necesarias
$(document).ready(function(){
    $('#form-model').attr('enctype',"multipart/form-data");
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
    
    $('#fk_id_cliente').on('change',function () {
        $('#detalleProductos tbody tr').remove();

        let _url = $('#fk_id_clave_cliente_producto').data('url').replace('?id',$(this).val());
        $('#fk_id_clave_cliente_producto').empty().prop('disabled',true);
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

                $('#fk_id_clave_cliente_producto').prepend(option).select2({
                    minimumResultsForSearch:'50',
                    data:data,
                }).attr('disabled',false);
                $('#loadingfk_id_clave_cliente_producto').hide();
            }
        });
        $('#loadingsucursales').show();
        $.ajax({
            url: $('#fk_id_cliente').data('url'),
            data: {
                'param_js':sucursales_js,
                $fk_id_cliente:$('#fk_id_cliente').val()
            },
            dataType:'JSON',
            success: function (data) {
                let option = $('<option/>');
                option.val(0);
                option.text('...');
                option.attr('disabled','disabled');
                option.attr('selected','selected');
                $('#fk_id_sucursal').empty().prepend(option).select2({
                    data:data
                });
                $('#loadingsucursales').hide();
            }
        });
    });
    
    //Por si se selecciona un UPC
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
                        $('#fk_id_upc').attr('disabled',false).prepend(option).select2({
                            minimumResultsForSearch: 15,
                            data: data
                        });
                        $('#loadingfk_id_upc').hide();
                    }
                });
            }else{
                $( this ).prop('checked',false);
                $( this ).parent().nextAll( "select" ).prop( "disabled", !this.checked );
                $.toaster({priority : 'danger',title : '¡Error!',message : 'Selecciona antes una Clave cliente producto',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
            }
        }
    });

    $('#agregarProducto').on('click',function () {
    	if($('#file_xlsx').val()){
            if($('#form-model').valid()) {
                if ($('#file_xlsx').val().substring($('#file_xlsx').val().lastIndexOf(".")) != '.xlsx') {
                    $.toaster({priority: 'danger', title: 'Â¡Error!', message: 'Por favor verifica que el archivo sea .xlsx',settings: {'timeout': 10000, 'toaster': {'css': {'top': '5em'}}}});
                    $('#file_xlsx').val('');
                } else {
                    $('.loadingtabla').show();
                    // $('.loadingtabla').attr('style','display:block');
                    var xlsx = $('#file_xlsx').prop('files')[0];
                    var formData = new FormData();
                    formData.append('file', xlsx);
                    formData.append('fk_id_cliente', $('#fk_id_cliente').val());
                    var _url = $('#file_xlsx').data('url');
                    $.ajax({
                        url: _url,
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        cache: false,
                        processData: false,
                        dataType: 'json',
                        success: function (data) {
                            let filas = '';
                            $.each(data[1], function (index) {
                                filas += index + ', ';
                            });
                            if (filas)
                                $.toaster({
                                    priority: 'danger',
                                    title: 'Las siguientes filas contienen un error en la clave del cliente',
                                    message: '<br>' + filas,
                                    settings: {'toaster': {'css': {'top': '5em'}}, 'donotdismiss': ['danger'],},
                                });

                            filas = '';
                            $.each(data[2], function (index) {
                                filas += index + ', ';
                            });
                            if (filas)
                                $.toaster({
                                    priority: 'danger',
                                    title: 'Las siguientes filas contienen un error en los UPC',
                                    message: '<br>' + filas,
                                    settings: {'toaster': {'css': {'top': '5em'}}, 'donotdismiss': ['danger'],},
                                });
                            //Importar las filas a la tabla
                            let arreglo = [];
                            $.each(data[0], function (index, value) {
                            	var i = $('#detalleProductos tr').length;
                                var row_id = i > 0 ? +$('#detalleProductos tr:last').find('.index').val()+1 : 0;
                                let id_upc = 0;
                                let text_upc = 'Sin UPC';
                                let descripcion_upc = '';
                                if (value['fk_id_upc']) {
                                    id_upc = value['fk_id_upc'];
                                    text_upc = value['upc'];
                                    descripcion_upc = value['descripcion_upc'];
                                }
                                
                                $('#detalleProductos').append('<tr>'+
                            		'<td><input class="index" name="relations[has][productos]['+row_id+'][index]" type="hidden" value="'+row_id+'">'+
                            			'<input class="index" name="relations[has][productos]['+row_id+'][id_proyecto_producto]" type="hidden" value="">'+
                            			$('<input type="hidden" name="relations[has][productos]['+row_id+'][fk_id_clave_cliente_producto]" value="'+value['id_clave_cliente_producto']+'" />')[0].outerHTML + value['clave_cliente_producto']+'</td>'+
                            		'<td>'+value['descripcion_clave']+'</td>'+
                            		'<td>'+$('<input type="hidden" name="relations[has][productos][' + row_id + '][fk_id_upc]" value="' + id_upc + '" />')[0].outerHTML + text_upc+'</td>'+
                            		'<td>'+descripcion_upc+'</td>'+
                            		'<td>'+$('<input class="form-control prioridad" maxlength="2" name="relations[has][productos][' + row_id + '][prioridad]" type="text" value="'+value['prioridad']+'" />')[0].outerHTML+'</td>'+
                            		'<td>'+$('<input class="form-control cantidad" maxlength="3" name="relations[has][productos][' + row_id + '][cantidad]" type="text" value="'+value['cantidad']+'" />')[0].outerHTML+'</td>'+
                            		'<td>'+$('<input class="form-control precio_sugerido" maxlength="13" name="relations[has][productos][' + row_id + '][precio_sugerido]" type="text" value="'+value['precio_sugerido']+'" />')[0].outerHTML+'</td>'+
                            		
                            		'<td>'+$('#campo_moneda').html().replace('$row_id',row_id).replace('$row_id',row_id)+'</td>'+
                            		
                            		'<td>'+$('<input class="form-control maximo" maxlength="4" name="relations[has][productos][' + row_id + '][maximo]" type="text" value="'+value['maximo']+'" />')[0].outerHTML+'</td>'+
                            		'<td>'+$('<input class="form-control minimo" maxlength="4" name="relations[has][productos][' + row_id + '][minimo]" type="text" value="'+value['minimo']+'" />')[0].outerHTML+'</td>'+
                            		'<td>'+$('<input class="form-control numero_reorden" maxlength="4" name="relations[has][productos][' + row_id + '][numero_reorden]" type="text" value="'+value['numero_reorden']+'" />')[0].outerHTML+'</td>'+
                            		'<td>'+$('<div class="form-check">' +
                                        '<label class="form-check-label custom-control custom-checkbox">' +
                                        '<input type="checkbox" class="form-check-input custom-control-input" checked value="1" name="relations[has][productos]['+row_id+'][activo]" />' +
                                        '<span class="custom-control-indicator"></span>' +
                                        '</label>' +
                                        '</div>')[0].outerHTML+'</td>'+
                                    '<td><button class="btn is-icon text-primary bg-white" type="button" data-delay="50" onclick="borrarFila(this)" data-tooltip="Producto"><i class="material-icons">delete</i></button>'+
                                '</tr>');
                            });
                            $.toaster({priority: 'success', title: '!Correcto!', message: 'Productos importados con Exito',settings: {'timeout': 10000, 'toaster': {'css': {'top': '5em'}}}});
                            $('.loadingtabla').hide();
                        },
                        error: function () {
                            $.toaster({priority: 'danger', title: '¡Error!', message: 'Por favor verifica que el layout sea correcto',settings: {'timeout': 10000, 'toaster': {'css': {'top': '5em'}}}});
                            $('.loadingtabla').hide();
                        }
                    });
                    $('#file_xlsx').val('');
                }
            }else{
                $.toaster({priority: 'danger', title: '¡Error!', message: 'Hay campos que requieren de tu atencion',settings: {'timeout': 10000, 'toaster': {'css': {'top': '5em'}}}});
            }
        }else {
            validateDetail();
            if ($('#form-model').valid()) {
            	var i = $('#detalleProductos tr').length;
                var row_id = i > 0 ? +$('#detalleProductos tr:last').find('.index').val()+1 : 0;
            	
                let id_upc = 0;
                let text_upc = 'Sin UPC';
                let descripcion_upc = '';
                if ($('#fk_id_upc').val()) {
                    id_upc = $('#fk_id_upc').select2('data')[0].id;
                    text_upc = $('#fk_id_upc').select2('data')[0].text;
                    descripcion_upc = $('#fk_id_upc').select2('data')[0].descripcion;
                }
                
                $('#detalleProductos').append('<tr>'+
            		'<td><input class="index" name="relations[has][productos]['+row_id+'][index]" type="hidden" value="'+row_id+'">'+
            			'<input class="index" name="relations[has][productos]['+row_id+'][id_proyecto_producto]" type="hidden" value="">'+
            			$('<input type="hidden" name="relations[has][productos]['+row_id+'][fk_id_clave_cliente_producto]" value="'+$("#fk_id_clave_cliente_producto").select2("data")[0].id+'" />')[0].outerHTML + $('#fk_id_clave_cliente_producto').select2('data')[0].text+'</td>'+
            		'<td>'+$('#fk_id_clave_cliente_producto').select2('data')[0].descripcionClave+'</td>'+
            		'<td>'+$('<input type="hidden" name="relations[has][productos][' + row_id + '][fk_id_upc]" value="' + id_upc + '" />')[0].outerHTML + text_upc+'</td>'+
            		'<td>'+descripcion_upc+'</td>'+
            		'<td>'+$('<input class="form-control prioridad" maxlength="2" name="relations[has][productos][' + row_id + '][prioridad]" type="text" value="" />')[0].outerHTML+'</td>'+
            		'<td>'+$('<input class="form-control cantidad" maxlength="3" name="relations[has][productos][' + row_id + '][cantidad]" type="text" value="" />')[0].outerHTML+'</td>'+
            		'<td>'+$('<input class="form-control precio_sugerido" maxlength="13" name="relations[has][productos][' + row_id + '][precio_sugerido]" type="text" value="" />')[0].outerHTML+'</td>'+
            		
            		'<td>'+$('#campo_moneda').html().replace('$row_id',row_id).replace('$row_id',row_id)+'</td>'+
            		
            		'<td>'+$('<input class="form-control maximo" maxlength="4" name="relations[has][productos][' + row_id + '][maximo]" type="text" value="" />')[0].outerHTML+'</td>'+
            		'<td>'+$('<input class="form-control minimo" maxlength="4" name="relations[has][productos][' + row_id + '][minimo]" type="text" value="" />')[0].outerHTML+'</td>'+
            		'<td>'+$('<input class="form-control numero_reorden" maxlength="4" name="relations[has][productos][' + row_id + '][numero_reorden]" type="text" value="" />')[0].outerHTML+'</td>'+
            		'<td>'+$('<div class="form-check">' +
                        '<label class="form-check-label custom-control custom-checkbox">' +
                        '<input type="checkbox" class="form-check-input custom-control-input" checked value="1" name="relations[has][productos][' + row_id + '][activo]" />' +
                        '<span class="custom-control-indicator"></span>' +
                        '</label>' +
                        '</div>')[0].outerHTML+'</td>'+
                    '<td><button class="btn is-icon text-primary bg-white" type="button" data-delay="50" onclick="borrarFila(this)" data-tooltip="Producto"><i class="material-icons">delete</i></button>'+
                '</tr>');

                $.toaster({priority: 'success', title: '¡Correcto!', message: 'Producto agregado con Exito',settings: {'timeout': 10000, 'toaster': {'css': {'top': '5em'}}}});
                limpiarCampos();
            } else {
                $.toaster({priority: 'danger', title: '¡Error!', message: 'Hay campos que requieren de tu atencion',settings: {'timeout': 10000, 'toaster': {'css': {'top': '5em'}}}});
            }
        }
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

        if($('#form-model').valid()){
            if(proyectoProducto.activeRows.length>0){
                if(eliminarProyectoProducto.length>0) {
                    let url = $('#productosproyectos').data('delete');
                    $.delete(url, {ids: eliminarProyectoProducto});
                }
            }else{
                e.preventDefault();
                $.toaster({priority : 'danger',title : 'Â¡Error!',message : 'La tabla se encuentra vacia.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
            }
        }else{
            e.preventDefault();
            $('.prioridad').rules('remove');
            $('.cantidad').rules('remove');
            $('.precio_sugerido').rules('remove');
            $('.maximo').rules('remove');
            $('.minimo').rules('remove');
            $('.numero_reorden').rules('remove');
            $.toaster({
                priority: 'danger', title: 'Â¡Error!', message: 'Hay campos que requieren de tu atencion',settings: {'timeout': 10000, 'toaster': {'css': {'top': '5em'}}}
            });
        }
    });

    if($('#fk_id_cliente').val()){
        if($('#detalle-form').length > 0) {
            let _url = $('#fk_id_clave_cliente_producto').data('url').replace('?id', $('#fk_id_cliente').val());
            $('#fk_id_clave_cliente_producto').empty();
            $.ajax({
                url: _url,
                dataType: 'json',
                success: function (data) {
                    let option = $('<option/>');
                    option.val(0);
                    option.attr('disabled', 'disabled');
                    option.attr('selected', 'selected');
                    option.text('...');
                    $('#fk_id_clave_cliente_producto').prepend(option).select2({
                        minimumResultsForSearch: '50',
                        data: data,
                    }).attr('disabled', false);
                }
            });
        }
    }else{
        let option = $('<option/>');
        option.val(0);
        option.attr('disabled','disabled');
        option.attr('selected','selected');
        option.text('Cliente no seleccionado');
        $('#fk_id_clave_cliente_producto').empty().prop('disabled',true).prepend(option);
    }
});

function limpiarCampos() {
    $('#fk_id_upc').empty().prop('disabled',true);
    $('#activo_upc').prop('checked',false);
    $('#fk_id_clave_cliente_producto').val(0).trigger('change');
    //Eliminar reglas de validaciÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â³n detalle
    $('#fk_id_upc').rules('remove');
    $('#fk_id_clave_cliente_producto').rules('remove');
}

function validateDetail() {
    $('#fk_id_cliente').rules('add',{
        required: true,
        messages:{
            required: 'Selecciona un cliente'
        }
    });
    $('#fk_id_clave_cliente_producto').rules('add',{
        required: true,
        messages:{
            required: 'Selecciona una clave cliente producto'
        }
    });

    if($('#activo_upc').is(':checked')){
        $('#fk_id_upc').rules('add',{
            required: true,
            messages:{
                required: 'Selecciona un UPC'
            }
        });
    }else{
        $('#fk_id_upc').rules('remove');
    }
}