var eliminarProyectoProducto=[];
// Inicializar los datepicker para las fechas necesarias
$(document).ready(function(){
    //Inicializar tabla
    window.proyectoProducto = new DataTable('#productosproyectos', {
        fixedHeight: true,
        fixedColumns: true,
        searchable: false,
        perPageSelect: false,
    });
    initSelects();

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
            searchable: false,
            perPageSelect: false,
        });
        let _url = $('#fk_id_clave_cliente_producto').data('url').replace('?id',$('#fk_id_cliente').val());
        $('#fk_id_clave_cliente_producto').empty().prop('disabled',true);
        $.ajax({
            url: _url,
            dataType:'json',
            success:function (data) {
                let option = $('<option/>');
                option.val(0);
                option.attr('disabled','disabled');
                option.attr('selected','selected');
                option.text('...');
                $('#fk_id_clave_cliente_producto').select2({
                    minimumResultsForSearch:'50',
                    data:data,
                }).attr('disabled',false).prepend(option);
            }
        });
    });
    //Por si se selecciona un UPC
    $('#activo_upc').on('change',function () {
        if( !this.checked ){
            $( this ).parent().nextAll( "select" ).val(0).trigger('change').prop( "disabled", !this.checked ).empty();
        }else{
            if($('#fk_id_clave_cliente_producto').val()){
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
                        option.text('...');
                        $('#fk_id_upc').attr('disabled',false).select2({
                            minimumResultsForSearch: 15,
                            data: data
                        }).prepend(option);
                    }
                });
            }else{
                $( this ).prop('checked',false);
                $( this ).parent().nextAll( "select" ).prop( "disabled", !this.checked );
                $.toaster({priority : 'danger',title : '¡Error!',message : 'Selecciona antes una Clave cliente producto',
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
        },'El número debe ser mayor a 0');

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
        $.validator.addMethod('precio',function (value,element) {
            return this.optional(element) || /^\d{0,10}(\.\d{0,2})?$/g.test(value);
        },'El precio no debe tener más de dos decimales');
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
                $.toaster({priority : 'danger',title : '¡Error!',message : 'La tabla se encuentra vacía.',
                    settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
            }
        }else{
            e.preventDefault();
            $('.prioridad').rules('remove');
            $('.cantidad').rules('remove');
            $('.precio_sugerido').rules('remove');
        }
    });

    if($('#fk_id_cliente').val()){
        let _url = $('#fk_id_clave_cliente_producto').data('url').replace('?id',$('#fk_id_cliente').val());
        $('#fk_id_clave_cliente_producto').empty().prop('disabled',true);
        $.ajax({
            url: _url,
            dataType:'json',
            success:function (data) {
                let option = $('<option/>');
                option.val(0);
                option.attr('disabled','disabled');
                option.attr('selected','selected');
                option.text('...');
                $('#fk_id_clave_cliente_producto').select2({
                    minimumResultsForSearch:'50',
                    data:data,
                }).attr('disabled',false).prepend(option);
            }
        });
    }
});

function initSelects() {
    $('#fk_id_cliente').select2({
        minimumResultsForSearch:50,
    });
    $('#fk_id_upc').select2();
}

function agregarProducto() {
    if($('#file_csv').val()){
        if($('#file_csv').val().substring($('#file_csv').val().lastIndexOf(".")) != '.csv'){
            $.toaster({
                priority: 'danger', title: '¡Error!', message: 'Por favor verifica que el archivo sea .csv',
                settings: {'timeout': 10000, 'toaster': {'css': {'top': '5em'}}}
            });
            $('#file_csv').val('');
        }else{
            var csv = $('#file_csv');
            var _url = csv.data('url');
            $.ajax({
                url: _url,
                type: 'POST',
                data: new FormData($('#form-model')[0]),
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    
                }
            })
        }
    }else {
        validateDetail();
        if ($('#form-model').valid()) {
            let row_id = proyectoProducto.activeRows.length;

            let id_upc = 0;
            let text_upc = 'UPC no seleccionado';
            let descripcion_upc = 'Sin descripción';
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
                    $('<input type="text" class="form-control prioridad" name="_productoProyecto[' + row_id + '][prioridad]" />')[0].outerHTML,
                    $('<input type="text" class="form-control cantidad" name="_productoProyecto[' + row_id + '][cantidad]" />')[0].outerHTML,
                    $('<input type="text" class="form-control precio_sugerido" name="_productoProyecto[' + row_id + '][precio_sugerido]" />')[0].outerHTML,
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
                priority: 'success', title: '¡Éxito!', message: 'Producto agregado con éxito',
                settings: {'timeout': 10000, 'toaster': {'css': {'top': '5em'}}}
            });
            limpiarCampos();
        } else {
            $.toaster({
                priority: 'danger', title: '¡Error!', message: 'Hay campos que requieren de tu atención',
                settings: {'timeout': 10000, 'toaster': {'css': {'top': '5em'}}}
            });
        }
    }
}

function borrarFilaProyectoProducto(element) {
    proyectoProducto.rows().remove([$(element).parents('tr').dataIndex]);
        $.toaster({priority : 'warning',title : '¡Advertencia!',message : 'Se ha eliminado la fila',
            settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
}

function limpiarCampos() {
    $('#fk_id_upc').empty().prop('disabled',true);
    $('#activo_upc').prop('checked',false);
    $('#fk_id_clave_cliente_producto').val(0).trigger('change');
    //Eliminar reglas de validación detalle
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
