var a=[];
// Inicializar los datepicker para las fechas necesarias
$(document).ready(function(){
    //Inicializar tabla
    window.dataTable = new DataTable('#productos', {
        fixedHeight: true,
        fixedColumns: true,
        searchable: false,
        perPageSelect: false,
    });
    initSelects();

    $('#fk_id_cliente').on('change',function () {
        dataTable.destroy();
        $('#tbody').empty();
        dataTable.init({
            fixedHeight: true,
            fixedColumns: true,
            searchable: false,
            perPageSelect: false,
        });
        let _url = $('#fk_id_proyecto').data('url').replace('?id',$('#fk_id_cliente').val());
        $('#fk_id_proyecto').empty();
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
                $('#fk_id_proyecto').select2({
                    minimumResultsForSearch:'50',
                    data:data,
                }).attr('disabled',false).prepend(option);
            }
        });
    });

    $('#fk_id_proyecto').on('change',function () {
        let _url = $('#fk_id_clave_cliente_producto').data('url').replace('?id',$('#fk_id_proyecto').val());
        $('#fk_id_clave_cliente_producto').empty();
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
                    minimumResultsForSearch:'15',
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

        $.validator.addMethod('cRequerido',$.validator.methods.required,'Escrible la prioridad');
        $.validator.addMethod('cNumber',$.validator.methods.number,'El campo debe ser numérico');
        $.validator.addClassRules('prioridad',{
            cRequerido: true,
            cNumber: true,
            minStrict: 0
        });

        $.validator.addClassRules('cantidad',{
            cRequerido: true,
            cNumber: true,
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
            if(dataTable.activeRows.length>0){
                if(a.length>0) {
                    let url = $('#productos').data('delete');
                    $.delete(url, {ids: a});
                    a = [];
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
    })
});

function initSelects() {

    let option = $('<option/>');
    option.val(0);
    option.attr('disabled','disabled');
    option.attr('selected','selected');
    option.text('...');
    $('#fk_id_cliente').select2({
        minimumResultsForSearch:50,
    }).prepend(option);
    $('#fk_id_upc').select2();
}

function agregarProducto() {
    validateDetail();
    if($('#form-model').valid()){
        let row_id = dataTable.activeRows.length;

        let id_upc = 0;
        let text_upc = 'UPC no seleccionado';
        let descripcion_upc = '';
        if($('#fk_id_upc').val()){
            id_upc = $('#fk_id_upc').select2('data')[0].id;
            text_upc = $('#fk_id_upc').select2('data')[0].text;
            descripcion_upc = $('#fk_id_upc').select2('data')[0].descripcion;
        }

        dataTable.insert( {
            data:[
            $('<input type="hidden" name="_detalles['+row_id+'][fk_id_clave_cliente_producto]" value="'+$("#fk_id_clave_cliente_producto").select2("data")[0].id+'" />')[0].outerHTML + $('#fk_id_clave_cliente_producto').select2('data')[0].text,
            $('#fk_id_clave_cliente_producto').select2('data')[0].descripcionClave,
            $('<input type="hidden" name="_detalles['+row_id+'][fk_id_upc]" value="' + id_upc + '" />')[0].outerHTML + text_upc,
            descripcion_upc,
            $('<input type="text" class="form-control prioridad" name="_detalles['+row_id+'][prioridad]" />')[0].outerHTML,
            $('<input type="text" class="form-control cantidad" name="_detalles['+row_id+'][cantidad]" />')[0].outerHTML,
            $('<input type="text" class="form-control precio_sugerido" name="_detalles['+row_id+'][precio_sugerido]" />')[0].outerHTML,
            $('<input type="checkbox" class="form-control" checked value="1" name="_detalles['+row_id+'][activo]" />')[0].outerHTML,
            '<button class="btn is-icon text-primary bg-white" type="button" data-delay="50" onclick="borrarFila(this)"> <i class="material-icons">delete</i></button>'
            ]
        });
        $.toaster({priority : 'success',title : '¡Éxito!',message : 'Producto agregado con éxito',
            settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}
        });
        limpiarCampos();
    }else{
        $.toaster({priority : 'danger',title : '¡Error!',message : 'Hay campos que requieren de tu atención',
            settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
    }
}

function borrarFila(element) {
    dataTable.rows().remove([$(element).parents('tr').dataIndex]);
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
    $('#fk_id_proyecto').rules('add',{
        required: true,
        messages:{
            required: 'Selecciona un proyecto'
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

function borrarFila_edit(element) {
    a.push(element.id);
    dataTable.rows().remove([$(element).parents('tr').dataIndex]);
}
