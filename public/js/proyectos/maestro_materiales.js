var eliminarProyectoProducto=[];
// Inicializar los datepicker para las fechas necesarias
$(document).ready(function(){
    $('#form-model').attr('enctype',"multipart/form-data");
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
    initSelects();
    
    
    //Inicializar tabla
    /*
    window.proyectoProducto = new DataTable('#productosproyectos', {
        fixedHeight: true,
        fixedColumns: true,
        labels:{
            info: "Mostrando del registro {start} al {end} de {rows}"
        }
    });
    */
    

    $('#fk_id_cliente').on('change',function () {
        proyectoProducto.destroy();
        $('#tbodyproductosproyectos tr').each(function (e) {
            if($(this).closest('tr').attr('id')){
                eliminarProyectoProducto.push($(this).closest('tr').attr('id'));
            }
        });
        $('#tbodyproductosproyectos').empty();
        proyectoProducto.init({
            fixedHeight: true,
            fixedColumns: true,
            labels:{
                info: "Mostrando del registro {start} al {end} de {rows}"
            }
        });
        let _url = $('#fk_id_clave_cliente_producto').data('url').replace('?id',$('#fk_id_cliente').val());
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
    });
    //Por si se selecciona un UPC
    $('#activo_upc').on('change',function () {
        if( !this.checked ){
            $( this ).parent().nextAll( "select" ).val(0).trigger('change').prop( "disabled", !this.checked ).empty();
        }else{
            if($('#fk_id_clave_cliente_producto').val()){
                $('#loadingfk_id_upc').show();
                let _url = $('#fk_id_upc').data('url').replace('?id',
                $('#fk_id_clave_cliente_producto').select2('data')[0].fk_id_sku);
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
                $.toaster({priority : 'danger',title : 'Ãƒâ€šÃ‚Â¡Error!',message : 'Selecciona antes una Clave cliente producto',
                    settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
            }
        }
    }); //Fin UPC

    $('#agregar').on('click',function () {
        agregarProducto();
    });

    $(document).on('submit',function (e) {

        $.validator.addMethod('minStrict', function (value, element, param) {
            return value > param;
        },'El nÃƒÆ’Ã‚Âºmero debe ser mayor a {0}');

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
                $.toaster({priority : 'danger',title : 'Ãƒâ€šÃ‚Â¡Error!',message : 'La tabla se encuentra vacÃƒÆ’Ã‚Â­a.',
                    settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
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
                priority: 'danger', title: 'Ãƒâ€šÃ‚Â¡Error!', message: 'Hay campos que requieren de tu atenciÃƒÆ’Ã‚Â³n',
                settings: {'timeout': 10000, 'toaster': {'css': {'top': '5em'}}}
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

function initSelects() {
    $('#fk_id_cliente').select2({
        minimumResultsForSearch:50,
    });
    $('#fk_id_upc').select2();
}

function agregarProducto() {
    if($('#file_xlsx').val()){
        if($('#form-model').valid()) {
            if ($('#file_xlsx').val().substring($('#file_xlsx').val().lastIndexOf(".")) != '.xlsx') {
                $.toaster({
                    priority: 'danger', title: 'Ãƒâ€šÃ‚Â¡Error!', message: 'Por favor verifica que el archivo sea .xlsx',
                    settings: {'timeout': 10000, 'toaster': {'css': {'top': '5em'}}}
                });
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
                            let row_id = proyectoProducto.activeRows.length;
                            let id_upc = 0;
                            let text_upc = 'UPC no seleccionado';
                            let descripcion_upc = 'Sin descripciÃƒÆ’Ã‚Â³n';
                            if (value['fk_id_upc']) {
                                id_upc = value['fk_id_upc'];
                                text_upc = value['upc'];
                                descripcion_upc = value['descripcion_upc'];
                            }
                            arreglo.push([$('<input type="hidden" name="_productoProyecto[' + row_id + '][fk_id_clave_cliente_producto]" value="' + value['id_clave_cliente_producto'] + '" />')[0].outerHTML + value['clave_cliente_producto'],
                                value['descripcion_clave'],
                                $('<input type="hidden" name="_productoProyecto[' + row_id + '][fk_id_upc]" value="' + id_upc + '" />')[0].outerHTML + text_upc,
                                descripcion_upc,
                                $('<input type="text" class="form-control prioridad" maxlength="2" value="' + value['prioridad'] + '" name="_productoProyecto[' + row_id + '][prioridad]" />')[0].outerHTML,
                                $('<input type="text" class="form-control cantidad" maxlength="4" value="' + value['cantidad'] + '" name="_productoProyecto[' + row_id + '][cantidad]" />')[0].outerHTML,
                                $('<input type="text" class="form-control precio_sugerido" maxlength="13" value="' + value['precio_sugerido'] + '" name="_productoProyecto[' + row_id + '][precio_sugerido]" />')[0].outerHTML,
                                $('<input type="text" class="form-control maximo" maxlength="4" value="' + value['maximo'] + '" name="_productoProyecto[' + row_id + '][maximo]" />')[0].outerHTML,
                                $('<input type="text" class="form-control minimo" maxlength="4" value="' + value['minimo'] + '" name="_productoProyecto[' + row_id + '][minimo]" />')[0].outerHTML,
                                $('<input type="text" class="form-control numero_reorden" maxlength="4" value="' + value['numero_reorden'] + '" name="_productoProyecto[' + row_id + '][numero_reorden]" />')[0].outerHTML,
                                $('<div class="form-check">' +
                                    '<label class="form-check-label custom-control custom-checkbox">' +
                                    '<input type="checkbox" class="form-check-input custom-control-input" checked value="1" name="_productoProyecto[' + row_id + '][activo]" />' +
                                    '<span class="custom-control-indicator"></span>' +
                                    '</label>' +
                                    '</div>')[0].outerHTML,
                                '<button class="btn is-icon text-primary bg-white" type="button" data-delay="50" onclick="borrarFilaProyectoProducto(this)"> <i class="material-icons">delete</i></button>'])
                        });
                        proyectoProducto.insert({
                            data: arreglo
                        });
                        $.toaster({
                            priority: 'success', title: 'Ãƒâ€šÃ‚Â¡ÃƒÆ’Ã¢â‚¬Â°xito!', message: 'Productos importados con ÃƒÆ’Ã‚Â©xito',
                            settings: {'timeout': 10000, 'toaster': {'css': {'top': '5em'}}},
                        });
                        $('.loadingtabla').hide();
                    },
                    error: function () {
                        $.toaster({
                            priority: 'danger', title: 'Ãƒâ€šÃ‚Â¡Error!', message: 'Por favor verifica que el layout sea correcto',
                            settings: {'timeout': 10000, 'toaster': {'css': {'top': '5em'}}}
                        });
                        $('.loadingtabla').hide();
                    }
                });
                $('#file_xlsx').val('');
            }
        }else{
            $.toaster({
                priority: 'danger', title: 'Ãƒâ€šÃ‚Â¡Error!', message: 'Hay campos que requieren de tu atenciÃƒÆ’Ã‚Â³n',
                settings: {'timeout': 10000, 'toaster': {'css': {'top': '5em'}}}
            });
        }
    }else {
        validateDetail();
        if ($('#form-model').valid()) {
            let row_id = proyectoProducto.activeRows.length;

            let id_upc = 0;
            let text_upc = 'UPC no seleccionado';
            let descripcion_upc = 'Sin descripciÃƒÆ’Ã‚Â³n';
            if ($('#fk_id_upc').val()) {
                id_upc = $('#fk_id_upc').select2('data')[0].id;
                text_upc = $('#fk_id_upc').select2('data')[0].text;
                descripcion_upc = $('#fk_id_upc').select2('data')[0].descripcion;
            }

            proyectoProducto.insert({
                data: [
                    $('<input type="hidden" name="_productoProyecto[' + row_id + '][fk_id_clave_cliente_producto]" value="' + $("#fk_id_clave_cliente_producto").select2("data")[0].id + '" />')[0].outerHTML + $('#fk_id_clave_cliente_producto').select2('data')[0].text,
                    $('#fk_id_clave_cliente_producto').select2('data')[0].descripcionClave,
                    $('<input type="hidden" name="_productoProyecto[' + row_id + '][fk_id_upc]" value="' + id_upc + '" />')[0].outerHTML + text_upc,
                    descripcion_upc,
                    $('<input type="text" class="form-control prioridad" maxlength="2" name="_productoProyecto[' + row_id + '][prioridad]" />')[0].outerHTML,
                    $('<input type="text" class="form-control cantidad" maxlength="3" name="_productoProyecto[' + row_id + '][cantidad]" />')[0].outerHTML,
                    $('<input type="text" class="form-control precio_sugerido" maxlength="13" name="_productoProyecto[' + row_id + '][precio_sugerido]" />')[0].outerHTML,
                    $('<input type="text" class="form-control maximo" maxlength="4" name="_productoProyecto[' + row_id + '][maximo]" />')[0].outerHTML,
                    $('<input type="text" class="form-control minimo" maxlength="4" name="_productoProyecto[' + row_id + '][minimo]" />')[0].outerHTML,
                    $('<input type="text" class="form-control numero_reorden" maxlength="4" name="_productoProyecto[' + row_id + '][numero_reorden]" />')[0].outerHTML,
                    $('<div class="form-check">' +
                        '<label class="form-check-label custom-control custom-checkbox">' +
                        '<input type="checkbox" class="form-check-input custom-control-input" checked value="1" name="_productoProyecto[' + row_id + '][activo]" />' +
                        '<span class="custom-control-indicator"></span>' +
                        '</label>' +
                        '</div>')[0].outerHTML,
                    '<button class="btn is-icon text-primary bg-white" type="button" data-delay="50" onclick="borrarFilaProyectoProducto(this)"> <i class="material-icons">delete</i></button>'
                ]
            });
            $.toaster({
                priority: 'success', title: 'Ãƒâ€šÃ‚Â¡ÃƒÆ’Ã¢â‚¬Â°xito!', message: 'Producto agregado con ÃƒÆ’Ã‚Â©xito',
                settings: {'timeout': 10000, 'toaster': {'css': {'top': '5em'}}}
            });
            limpiarCampos();
        } else {
            $.toaster({
                priority: 'danger', title: 'Ãƒâ€šÃ‚Â¡Error!', message: 'Hay campos que requieren de tu atenciÃƒÆ’Ã‚Â³n',
                settings: {'timeout': 10000, 'toaster': {'css': {'top': '5em'}}}
            });
        }
    }
}

function borrarFilaProyectoProducto(element) {
    proyectoProducto.rows().remove([$(element).parents('tr').dataIndex]);
        $.toaster({priority : 'warning',title : 'Ãƒâ€šÃ‚Â¡Advertencia!',message : 'Se ha eliminado la fila',
            settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
}

function limpiarCampos() {
    $('#fk_id_upc').empty().prop('disabled',true);
    $('#activo_upc').prop('checked',false);
    $('#fk_id_clave_cliente_producto').val(0).trigger('change');
    //Eliminar reglas de validaciÃƒÆ’Ã‚Â³n detalle
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

function borrarFilaProyectoProducto_edit(element) {
    eliminarProyectoProducto.push(element.id);
    proyectoProducto.rows().remove([$(element).parents('tr').dataIndex]);
}
