//var eliminarProyectoProducto=[];

// Inicializar los datepicker para las fechas necesarias
$(document).ready(function(){
    $('#form-model').attr('enctype',"multipart/form-data");
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

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
                            // $('#tbodyproductosproyectos').empty();
                            clearCustomFileInputText($('#file_xlsx'));
                            let filas = '<ul>';
                            $.each(data[1], function (index,text) {
                                filas += '<li>'+index + ': ' + text + '</li>';
                            });
                            filas += '</ul>';
                            if (filas != '<ul></ul>')
                                $.toaster({
                                    priority: 'danger',
                                    title: 'Las siguientes filas contienen un error en la clave del cliente',
                                    message: '<br>' + filas,
                                    settings: {'toaster': {'css': {'top': '5em'}}, 'donotdismiss': ['danger'],},
                                });
                            //Importar las filas a la tabla
                            $.each(data[0], function (index, value) {
                            	var i = $('#detalleProductos tbody tr').length;
                                var row_id = i > 0 ? +$('#detalleProductos tr:last').find('.index').val()+1 : 0;
                                
                                $('#detalleProductos').append('<tr>'+
                            		'<td><input class="index" name="relations[has][productos]['+row_id+'][index]" type="hidden" value="'+row_id+'">'+
                            			'<input class="index" name="relations[has][productos]['+row_id+'][id_proyecto_producto]" type="hidden" value="">'+
                            			'<input type="hidden" name="relations[has][productos]['+row_id+'][fk_id_clave_cliente_producto]" value="'+value['id_clave_cliente_producto']+'" /><span>' + value['clave_cliente_producto']+'</span></td>'+
                            		'<td>'+value['descripcion_clave']+'</td>'+
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
                            if($('#tbodyproductosproyectos tr').length)
                                $.toaster({priority: 'success', title: '¡Correcto!', message: 'Productos importados con Exito',settings: {'timeout': 10000, 'toaster': {'css': {'top': '5em'}}}});
                            else
                                $.toaster({priority: 'danger', title: '¡Oooops!', message: 'No se ha cargado ningún producto',settings: {'timeout': 10000, 'toaster': {'css': {'top': '5em'}}}});
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
            	var i = $('#detalleProductos tbody tr').length;
                var row_id = i > 0 ? +$('#detalleProductos tr:last').find('.index').val()+1 : 0;

                var precio = +$('#fk_id_clave_cliente_producto').select2('data')[0].precio;

                $('#detalleProductos').append('<tr>'+
            		'<td><input class="index" name="relations[has][productos]['+row_id+'][index]" type="hidden" value="'+row_id+'">'+
            			'<input class="index" name="relations[has][productos]['+row_id+'][id_proyecto_producto]" type="hidden" value="">'+
            			$('<input type="hidden" name="relations[has][productos]['+row_id+'][fk_id_clave_cliente_producto]" value="'+$("#fk_id_clave_cliente_producto").select2("data")[0].id+'" />')[0].outerHTML + $('#fk_id_clave_cliente_producto').select2('data')[0].text+'</td>'+
            		'<td>'+$('#fk_id_clave_cliente_producto').select2('data')[0].descripcionClave+'</td>'+
            		'<td>'+$('<input class="form-control prioridad" maxlength="2" name="relations[has][productos][' + row_id + '][prioridad]" type="text" value="" />')[0].outerHTML+'</td>'+
            		'<td>'+$('<input class="form-control cantidad" maxlength="3" name="relations[has][productos][' + row_id + '][cantidad]" type="text" value="" />')[0].outerHTML+'</td>'+
            		'<td>'+$('<input class="form-control precio_sugerido" maxlength="13" name="relations[has][productos][' + row_id + '][precio_sugerido]" type="text" value="'+ precio.toFixed(2) +'" />')[0].outerHTML+'</td>'+
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
    $('#fk_id_clave_cliente_producto').val(0).trigger('change');
    //Eliminar reglas de validación detalle
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
        required: function () {
            return !$('#file_xlsx').val()
        },
        messages:{
            required: 'Selecciona una clave cliente producto'
        }
    });
}