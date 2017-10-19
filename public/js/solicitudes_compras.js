var a=[];
// Inicializar los datepicker para las fechas necesarias
$('.datepicker').pickadate({
    selectMonths: true, // Creates a dropdown to control month
    selectYears: 3, // Creates a dropdown of 3 years to control year
    min: true,
    format: 'yyyy/mm/dd'
});
$(document).ready( function () {
    $.ajax({
        async: false,
        url: $('#fk_id_impuesto').data('url'),
        dataType:'json',
        success:function (data) {
            $('#fk_id_impuesto').select2({
                placeholder:'Selecciona un tipo de impuesto',
                data:data
            });
        }
    });
    select2Placeholder('fk_id_sku','Selecciona un SKU');
    select2Placeholder('fk_id_proveedor','Proveedor no seleccionado',50,true,false);

    $('#id_solicitante').val(getIdempleado());
    if(window.location.href.toString().indexOf('editar') > -1)//Si es editar
    {
        total_producto();//Obtiene el porcentaje del valor por defecto
        $('#fk_id_solicitante').select2();
    }else if(window.location.href.toString().indexOf('crear') > -1){
        total_producto();//Obtiene el porcentaje del valor por defecto
        select2Placeholder('fk_id_solicitante',
            'Yo solicito la compra',
            10,
            true,
            false,
            $('#id_solicitante').val());
    }else{
        $('select').prop('disabled',true);
    }
    $(':submit').attr('onclick','eliminarDetalle()');

    if(window.location.href.toString().indexOf('crear')>-1 || window.location.href.toString().indexOf('editar') >-1)
    {
        $('#fk_id_sucursal_').prop('disabled',true);
        sucursal();//Cargar las sucursales del usuario
    }

    // $('#fk_id_solicitante').select2();
    $('#fk_id_solicitante').change(function () {
        $('#id_solicitante').val('');
        sucursal();//Caga los nuevos datos de la sucursal
    });

    //Inicializar tabla
    window.dataTable = new DataTable('#productos', {
        footer : false,
        fixedHeight: true,
        fixedColumns: true,
        searchable: false,
        perPageSelect: false,
        labels:{
            info: "Mostrando del registro {start} al {end} de {rows}"
        }
    });

    select2Placeholder('fk_id_upc','Selecciona UPC',5);
    $('#fk_id_sku').on('change',function () {
        if($('#fk_id_sku').select2('data')[0].id){
            $('#loadingUPC').show();
            codigosbarras();//Carga los nuevos datos del producto
        }
    });

    $('.imprimir').on('click',function (e) {
        if(dataTable.rows.length < 1 && $('.imprimir').length){
            e.preventDefault();
            // Materialize.toast('<span><i class="material-icons">priority_high</i>Te recomendamos editar la solicitud ya que no cuenta con SKUs<br/></span>', 10000,'red rounded');
        }
    });

    select2Placeholder('fk_id_unidad_medida','Selecciona una unidad de medida','Infinity');
    select2Placeholder('fk_id_proyecto','Proyecto no seleccionado',50,true,false);

    $(document).on('keyup','.cantidad',function (e) {
            let valid = /[^0-9]/g.test(this.value),
                val = this.value;
            if(valid)
            {
                this.value = val.substring(0, val.length - 1);
            }
            // else
            // {
            //     total_producto_row(this.id);
            // }
    });

    $(document).on('keyup','.precio_unitario',function (e) {
        let valid = /^\d{0,10}(\.\d{0,2})?$/g.test(this.value),
            val = this.value;
        if(!valid)
        {
            this.value = val.substring(0, val.length - 1);
        }
    });
});

function getIdempleado()
{
    var data_empleado = $('#fk_id_solicitante').data('url');

    var objempleado = $.ajax({
        url: data_empleado,
        dataType: 'json',
        type: 'get',
        contentType: 'application/json',
        processData: false,
        success: function( data){
            return data;
        },
        error: function(){
            return '0';
        }
    });

    return JSON.stringify(objempleado.readyState);
}

function sucursal()
{
    let data_empleado = $('#id_solicitante').data('url');
    $('#fk_id_sucursal_').prop('disabled',true);//Deshabilitar

    if(!$('#id_solicitante').val())
    {
            var _url = data_empleado.replace('?id', $('#fk_id_solicitante').select2('data')[0].id);
            $('#id_solicitante').val($('#fk_id_solicitante').select2('data')[0].id);
    }
    else
        {var _url = data_empleado.replace('?id', $('#id_solicitante').val());}

    $.ajax({
        async:false,
        url: _url,
        dataType: 'json',
        success: function (data) {
            // $('#fk_id_sucursal_').material_select('destroy');
            $('#fk_id_sucursal_').empty();
            let option = $('<option/>');
            option.val(0);
            option.attr('disabled','disabled');
            option.attr('selected','selected');
            option.text('Selecciona una sucursal');
            $('#fk_id_sucursal_').append(option);
            $.each(data, function (key, sucursal) {
                let option = $('<option/>');
                option.val(key);
                option.text(sucursal);
                if(window.location.href.toString().indexOf('editar') > -1)
                {
                    if($('#sucursal_defecto').val() == key)
                    {
                        option.prop('selected',true);
                    }
                }
                $('#fk_id_sucursal_').append(option);
            });
            if(Object.keys(data).length ==0)
            {$('#fk_id_sucursal_').prop('disabled',true)}
            else{$('#fk_id_sucursal_').prop('disabled',false)}

            $('#fk_id_sucursal_').select2({
                minimumResultsForSearch:'Infinity'
            });
        },
    });
}

function codigosbarras()
{
    if($('#fk_id_sku').select2('data')[0].id != null) {
        let data_codigo = $('#fk_id_upc').data('url');
        $('#fk_id_upc').prop('disabled', true);//Deshabilitar

        var _url = data_codigo.replace('?id', $('#fk_id_sku').select2('data')[0].id);

        $.ajax({
            url: _url,
            dataType: 'json',
            success: function (data) {
                // removerOpciones('fk_id_upc');
                $('#fk_id_upc').empty();
                $.each(data, function (key, codigo) {
                    let option = $('<option/>');
                    option.val(codigo.id);
                    option.text(codigo.text);
                    $('#fk_id_upc').append(option);
                });
                if (Object.keys(data).length == 0) {
                    let option = $('<option/>');
                    option.val(null);
                    option.attr('disabled', 'disabled');
                    option.attr('selected', 'selected');
                    option.text('UPC no encontrado');
                    $('#fk_id_upc').prepend(option);
                    $('#fk_id_upc').prop('disabled', true)
                }
                else {
                    let option = $('<option/>');
                    option.val(null);
                    option.attr('disabled', 'disabled');
                    option.attr('selected', 'selected');
                    option.text('Selecciona un UPC');
                    $('#fk_id_upc').prepend(option);
                    $('#fk_id_upc').prop('disabled', false)
                }
                $('#loadingUPC').hide();

                $('#fk_id_upc').select2();
            },
        });
    }
}

function total_producto() {
    let impuesto = $('#fk_id_impuesto').select2('data')[0].porcentaje;
            let precio_unitario = $('#precio_unitario').val();
            let cantidad = $('#cantidad').val();

            //Calcular valores
            let subtotal = cantidad*precio_unitario;
            let impuesto_producto = (subtotal*impuesto)/100;
            return (subtotal + impuesto_producto).toFixed(2);
}
function total_producto_row(id_detalle,tipo) {

    let url = $('#productos').data('porcentaje');
    let id_impuesto;
    if(tipo == 'old')
        {id_impuesto = $('#fk_id_impuesto'+id_detalle).val();}
    else
        {id_impuesto = $('#_fk_id_impuesto'+id_detalle).val();}
    $.ajax({
        type: 'GET',
        url: url.replace('?id', id_impuesto),
        success: function (impuesto) {
            let precio_unitario;
            let cantidad;
            if(tipo == 'old')
            {
                precio_unitario = $('#precio_unitario'+id_detalle).val();
                cantidad = $('#cantidad'+id_detalle).val();
            }else
            {
                precio_unitario = $('#_precio_unitario'+id_detalle).val();
                cantidad = $('#_cantidad'+id_detalle).val();
            }
            //Calcular valores
            let subtotal = cantidad*precio_unitario;
            let impuesto_producto = (subtotal*impuesto)/100;

            if(tipo == 'old')
                {$('#total'+id_detalle).val(subtotal + impuesto_producto);}
            else
                {$('#_total'+id_detalle).val(subtotal + impuesto_producto);}

        }
    });
}

function agregarProducto() {
    // e.preventDefault();
    // alert($('').validate());
    validateDetail();
    if($('#form-model').valid()){

        var row_id = dataTable.rows.length;
        //Para obtener las opciones del select de proyectos en el detalle de la solicitud
        let proyectos = '';
                $.each($('#fk_id_proyecto option').clone(), function (key, proyecto) {
                    //Este if es para verificar la opción seleccionada por defecto
                    if(proyecto.value == $('#fk_id_proyecto').select2('data')[0].id)
                        {proyectos += '<option value="'+proyecto.value+'" selected>'+proyecto.innerText+'</option>';}
                    else
                        {proyectos += proyecto.outerHTML;}
                });
    //Para obtener las opciones del select de impuestos en el detalle de la solicitud
        let impuestos = '';
        $.each($('#fk_id_impuesto option').clone(), function (key, impuesto) {
                    //Este if es para verificar la opción seleccionada por defecto
                    if(impuesto.value == $('#fk_id_impuesto').select2('data')[0].id && impuesto.value != -1)
                        {
                            impuestos += '<option value="'+impuesto.value+'" selected>'+impuesto.innerText+'</option>';
                        }
                    else if(impuesto.value != -1)
                        {impuestos += impuesto.outerHTML;}
                });

        let total = total_producto();
        let id_upc = 0;
        let text_upc = 'UPC no seleccionado';
        if ($('#fk_id_upc').val()) {
            id_upc = $('#fk_id_upc').select2('data')[0].id;
            text_upc = $('#fk_id_upc').select2('data')[0].text;
        }

        dataTable.import({
            type: "csv",
            data:
            $('<input type="hidden" name="_detalles['+row_id+'][fk_id_sku]" value="' + $('#fk_id_sku').select2('data')[0].id + '" />')[0].outerHTML + $('#fk_id_sku').select2('data')[0].text + ","+
            $('<input type="hidden" name="_detalles['+row_id+'][fk_id_upc]" value="' + id_upc + '" />')[0].outerHTML + text_upc + ","+
            $('<input type="hidden" name="_detalles['+row_id+'][fk_id_proveedor]" value="'+$('#fk_id_proveedor').val()+'"/>')[0].outerHTML+$('#fk_id_proveedor').select2('data')[0].text+ "," +
            $('<input type="hidden" name="_detalles['+row_id+'][fecha_necesario]" value="'+ $('#fecha_necesario').val()+'"/>')[0].outerHTML +  $('#fecha_necesario').val() + "," +
            $('<select name="_detalles['+row_id+'][fk_id_proyecto]" id="fk_id_proyecto'+row_id+'" style="width: 100%" class="select">'+proyectos+'</select>')[0].outerHTML + ","+
            $('<input type="text" name="_detalles['+row_id+'][cantidad]" onchange="total_producto_row('+row_id+')" id="_cantidad'+row_id+'" value="'+ $('#cantidad').val()+'" class="validate cantidad form-control" />')[0].outerHTML + "," +
            $('<input type="hidden" name="_detalles['+row_id+'][fk_id_unidad_medida]" value="' + $('#fk_id_unidad_medida').val() + '" />')[0].outerHTML + $('#fk_id_unidad_medida option:selected').html() + ","+
            $('<select name="_detalles['+row_id+'][fk_id_impuesto]" onchange="total_producto_row('+row_id+')" id="_fk_id_impuesto'+row_id+'" style="width: 100%" class="select">'+impuestos+'</select>')[0].outerHTML + ","+
            $('<input type="text" name="_detalles['+row_id+'][precio_unitario]"  onchange="total_producto_row('+row_id+')" id="_precio_unitario'+row_id+'" value="'+ $('#precio_unitario').val()+'" class="precio_unitario form-control"/>')[0].outerHTML + "," +
            $('<input type="text" name="_detalles['+row_id+'][total]" id="_total'+row_id+'" class="form-control" style="min-width: 100px" readonly value="'+ total+'" />')[0].outerHTML +"," +
            '<button class="btn is-icon text-primary bg-white" ' +
            'type="button" data-delay="50" onclick="borrarFila(this)">' +
            '<i class="material-icons">delete</i></button>'
        });
        $('.select').select2({
            minimumResultsForSearch:'Infinity'
        });
        $.toaster({
            priority : 'success',
            title : '¡Éxito!',
            message : 'Producto agregado con éxito',
            settings:{
                'timeout':10000,
                'toaster':{
                    'css':{
                        'top':'5em'
                    }
                }
            }
        });
        limpiarFormulario();//Limpiar el formulario de algunos de los valores
    }else {
        $.toaster({
            priority : 'danger',
            title : '¡Error!',
            message : 'Hay campos que requieren de tu atención',
            settings:{
                'timeout':10000,
                'toaster':{
                    'css':{
                        'top':'5em'
                    }
                }
            }
        });
    }
}

function limpiarFormulario() {
    // $('#fk_id_proveedor').val('');
    $('#fk_id_proveedor').val(null).trigger('change');
    $('#fk_id_sku').val(null).trigger('change');
    $('#fk_id_upc').val(null).trigger('change');
    $('#fk_id_proyecto').val(null).trigger('change');
    $('#fk_id_unidad_medida').val(null).trigger('change');
    $('#fk_id_impuesto').val('-1').trigger('change');
    $('#fecha_necesario').val('');
    $('#cantidad').val('1');
    $('#precio_unitario').val('0');
    //Eliminar reglas de validación detalle
    $('#fk_id_sku').rules('remove');
    $('#fk_id_upc').rules('remove');
    $('#fk_id_proyecto').rules('remove');
    $('#fecha_necesario').rules('remove');
    $('#cantidad').rules('remove');
    $('#fk_id_unidad_medida').rules('remove');
    $('#fk_id_impuesto').rules('remove');
    $('#precio_unitario').rules('remove');
}

function borrarFila(el) {
    // console.log( $(el).parents('tr').data('datarow') );
    dataTable.rows().remove([$(el).parents('tr').index()]);
    if(dataTable.rows.length<1)
        validateDetail();
}
function borrarFila_edit(el) {
    a.push(el.id);
    dataTable.rows().remove([$(el).parents('tr').index()]);
    if(dataTable.rows.length<1)
        validateDetail();
}

function eliminarDetalle() {
    if (a.length>0){
        let url = $('#productos').data('delete');
        $.delete(url, {ids: a});
    }
}

function select2Placeholder(id_select,text,searchable = 1,selected = true, disabled = true,value = null) {
    let option = $('<option/>');
    option.val(value);
    option.attr('disabled',disabled);
    option.attr('selected',selected);
    option.text(text);
    $('#'+id_select).prepend(option).select2({
        minimumResultsForSearch:searchable
    });
}

function validateDetail() {
    $('#fk_id_sku').rules('add',{
        required: true,
        messages:{
            required: 'Selecciona un SKU'
        }
    });
    $('#fecha_necesario').rules('add',{
        required: true,
        messages:{
            required:'Selecciona para cuando se necesita el producto'
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
    $('#fk_id_unidad_medida').rules('add',{
        required: true,
        messages:{
            required: 'Selecciona una unidad de medida'
        }
    });
    $('#fk_id_impuesto').rules('add',{
        required: true,
        messages:{
            required: 'Selecciona un tipo de impuesto'
        }
    });
    $.validator.addMethod('precio',function (value,element) {
        return this.optional(element) || /^\d{0,10}(\.\d{0,2})?$/g.test(value);
    },'El precio no debe tener más de dos decimales');
    $('#precio_unitario').rules('add',{
        required: true,
        number: true,
        precio:true,
        messages:{
            required: 'Ingresa un precio unitario',
            number: 'El campo debe ser un número',
            greaterThan: 'El número debe ser mayor a 0',
            precio: 'El precio no debe tener más de dos decimales'
        }
    });
}