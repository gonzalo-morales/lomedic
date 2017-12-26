var a=[];
var subtotal_original = 0;
// Inicializar los datepicker para las fechas necesarias
$('.datepicker').pickadate({
    selectMonths: true, // Creates a dropdown to control month
    selectYears: 3, // Creates a dropdown of 3 years to control year
    min: true,
    format: 'yyyy/mm/dd'
});
$(document).ready(function(){
    //Inicializar tabla
    window.dataTable = new DataTable('#productos', {
        fixedHeight: true,
        fixedColumns: true,
        searchable: false,
        perPageSelect: false,
        labels:{
            info: "Mostrando del registro {start} al {end} de {rows}"
        },
        footer:true,
    });

    window.dataTableCondiciones = new DataTable('#condicionesAutorizar', {
        fixedHeight: true,
        fixedColumns: true,
        searchable: false,
        perPageSelect: false,
        labels:{
            info: "Mostrando del registro {start} al {end} de {rows}"
        },
        footer:true,
    });

    totalOrden();
    subtotal_original = $('#subtotal_lbl').text();
    // console.log(subtotal_original);
    validateDetail();
    if(window.location.href.toString().indexOf('editar') > -1 || window.location.href.toString().indexOf('crear') > -1 || window.location.href.toString().indexOf('solicitudOrden') > -1)
    {
        initSelects();
        if(window.location.href.toString().indexOf('crear') > -1){
            validateDetail();
        }
        //Por si se selecciona una empresa diferente
        $('#otra_empresa').on('change',function () {
            $( this ).parent().nextAll( "select" ).prop( "disabled", !this.checked );
            if( !this.checked ){
                if(window.location.href.toString().indexOf('crear') > -1)
                    $( this ).parent().nextAll( "select" ).val(0).trigger('change');
            }
        });
        //Por si se selecciona un UPC
        $('#activo_upc').on('change',function () {
            $( this ).parent().nextAll( "select" ).prop( "disabled", !this.checked );
            if( !this.checked ){
                $( this ).parent().nextAll( "select" ).val(0).trigger('change');
            }else{
                if($('#fk_id_sku').val()){
                    _url = $('#fk_id_upc').data('url').replace('?id',$('#fk_id_sku').val());
                    $( this ).parent().nextAll( "select" ).select2({
                        theme: "bootstrap",
                        minimumResultsForSearch: Infinity,
                        ajax:{
                            url: _url,
                            dataType: 'json',
                            data: function (term) {
                                return {term: term};
                            },
                            processResults: function (data) {
                                return {results: data}
                            },
                            cache:true
                        }
                    })
                }else{
                    $( this ).prop('checked',false);
                    $( this ).parent().nextAll( "select" ).prop( "disabled", !this.checked );
                    $.toaster({priority : 'danger',title : '¡Error!',message : 'Selecciona antes un SKU',
                        settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
                }
            }
        })//Fin UPC

        $('#agregar').on('click',function () {
            agregarProducto();
        });

        $('#fk_id_socio_negocio').on('change',function () {
            $('#tiempo_entrega').val($('#fk_id_socio_negocio').select2('data')[0].tiempo_entrega);
            var fecha = new Date();
            fecha.setDate(fecha.getDate()+$('#fk_id_socio_negocio').select2('data')[0].tiempo_entrega);
            let dia = fecha.getDate()+1;
            let mes = fecha.getMonth()+1;
            let anio = fecha.getFullYear();
            $('#fecha_estimada_entrega').val(anio+'/'+mes+'/'+dia);
        });
        $(document).on('submit',function (e) {
            if(dataTable.activeRows.length > 0){
                if(a.length>0) {
                    let url = $('#productos').data('delete');
                    $.delete(url, {ids: a});
                    a = [];
                }
            }else{
                e.preventDefault();
            }
        })
    }
    $('#descuento_porcentaje').on('keyup',function () {
        $('#descuento_general').val(((subtotal_original*$(this).val())/100).toFixed(2));
        totalOrden();
    });
    $('#descuento_general').on('keyup',function () {
        $('#descuento_porcentaje').val((($(this).val()/subtotal_original)*100).toFixed(4));
        totalOrden();
    });
    limpiarCampos();

});

function select2Placeholder(id_select,text,searchable = 1,selected = true, disabled = true,value = 0,select2=true) {
    let option = $('<option/>');
    option.val(value);
    option.attr('disabled',disabled);
    option.attr('selected',selected);
    option.text(text);
    if(select2)
        $('#'+id_select).prepend(option).select2({
            minimumResultsForSearch:searchable,
            theme: "bootstrap",
        });
    else
        $('#'+id_select).prepend(option);
}

function initSelects() {
    $("#fk_id_sku").select2({
        minimumInputLength:3,
        theme: "bootstrap",
        ajax:{
            url: $("#fk_id_sku").data('url'),
            dataType: 'json',
            data: function (params) {
                return {term: params.term};
            },
            processResults: function (data) {
                return {results: data}
            }
        }
    });

    $('#fk_id_solicitud').select2({
        minimumResultsForSearch:50,
        theme:'bootstrap'
    });

    if(window.location.href.toString().indexOf('editar') > -1 || window.location.href.toString().indexOf('solicitudOrden') > -1){
        $('#fk_id_condicion_pago').select2({theme:"bootstrap",minimumResultsForSearch:'Infinity'});
        $('#fk_id_tipo_entrega').select2({theme:"bootstrap",minimumResultsForSearch:'Infinity'});
        $('#fk_id_sucursal_').select2({theme:"bootstrap",minimumResultsForSearch:'Infinity'});
        // $('#fk_id_empresa_').select2({theme:"bootstrap",minimumResultsForSearch:'Infinity'});
        totalOrden();
    }else{
        select2Placeholder('fk_id_empresa_','Selecciona una empresa',0,true,true,0,false);
        select2Placeholder('fk_id_socio_negocio','Selecciona un proveedor',50,true,true,0,false);
        select2Placeholder('fk_id_sucursal_','Selecciona una sucursal',30,true,true);
        select2Placeholder('fk_id_condicion_pago','Selecciona una condición de pago','Infinity',true,true);
        select2Placeholder('fk_id_tipo_entrega','Selecciona una forma de entrega','Infinity',true,true);
    }
    $.ajax({
        url: $('#fk_id_socio_negocio').data('url'),
        dataType:'json',
        success:function (data) {
            $('#fk_id_socio_negocio').select2({
                theme:'bootstrap',
                minimumResultsForSearch:'Infinity',
                data:data,
            });
        }
    });

    select2Placeholder('fk_id_upc','UPC no seleccionado',null,true,true,0,false);
    select2Placeholder('fk_id_cliente','Sin cliente',10,true,false);
    select2Placeholder('fk_id_proyecto','Sin proyecto',10,true,false);
    $('#fk_id_upc').select2();
    $.ajax({
        url: $('#fk_id_impuesto').data('url'),
        dataType:'json',
        success:function (data) {
            $('#fk_id_impuesto').select2({
                minimumResultsForSearch:'Infinity',
                data:data,
            });
        }
    });
}

function agregarProducto() {
    validateDetail();
    if($('#form-model').valid()){
        let row_id = dataTable.activeRows.length;
        let total = totalProducto();

        var data = [];
        data.push([
            $('<input type="hidden" name="_detalles['+row_id+'][fk_id_documento]"/>')[0].outerHTML + 'N/A',
            $('<input type="hidden" name="_detalles['+row_id+'][fk_id_sku]" value="' + $('#fk_id_sku').select2('data')[0].id + '" />')[0].outerHTML + $('#fk_id_sku').select2('data')[0].text,
            $('<input type="hidden" name="_detalles['+row_id+'][fk_id_upc]" value="' + $('#fk_id_upc').select2('data')[0].id + '" />')[0].outerHTML + $('#fk_id_upc').select2('data')[0].text,
            $('#fk_id_sku').select2('data')[0].descripcion_corta,
            $('#fk_id_sku').select2('data')[0].descripcion,
            $('<input type="hidden" name="_detalles['+row_id+'][fk_id_cliente]" value="' + $('#fk_id_cliente').select2('data')[0].id + '" />')[0].outerHTML + $('#fk_id_cliente').select2('data')[0].text,
            $('<input type="hidden" name="_detalles['+row_id+'][fk_id_proyecto]" value="' + $('#fk_id_proyecto').select2('data')[0].id + '" />')[0].outerHTML + $('#fk_id_proyecto').select2('data')[0].text,
            $('<input type="hidden" name="_detalles['+row_id+'][fecha_necesario]" value="' + $('#fecha_necesario').val() + '" />')[0].outerHTML + $('#fecha_necesario').val(),
            $('<input type="hidden" name="_detalles['+row_id+'][cantidad]" class="cantidad_row" value="' + $('#cantidad').val() + '" />')[0].outerHTML + $('#cantidad').val(),
            $('<input type="hidden" name="_detalles['+row_id+'][descuento_detalle]" class="descuento_row" value="' + $('#descuento').val() + '" />')[0].outerHTML + $('#descuento').val(),
            $('<input type="hidden" name="_detalles['+row_id+'][fk_id_impuesto]" value="' + $('#fk_id_impuesto').select2('data')[0].id + '" />')[0].outerHTML + $('#fk_id_impuesto').select2('data')[0].text+
            $('<input type="hidden" class="porcentaje" value="' + $('#fk_id_impuesto').select2('data')[0].porcentaje + '" />')[0].outerHTML,
            $('<input type="hidden" class="precio_unitario_row" name="_detalles['+row_id+'][precio_unitario]" value="' + $('#precio_unitario').val() + '" />')[0].outerHTML + $('#precio_unitario').val(),
            $('<input type="text" value="'+ total +'" name="_detalles['+row_id+'][total]" class="form-control total_row" readonly>')[0].outerHTML,
            '<button class="btn is-icon text-primary bg-white" type="button" data-delay="50" onclick="borrarFila(this)"> <i class="material-icons">delete</i></button>'
        ]);

        dataTable.insert( {
            data:data
        });
        $.toaster({priority : 'success',title : '¡Éxito!',message : 'Producto agregado con éxito',
            settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}
        });
        limpiarCampos();
        totalOrden();
    }else{
        $.toaster({priority : 'danger',title : '¡Error!',message : 'Hay campos que requieren de tu atención',
            settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
    }
}

function totalProducto() {
    let cantidad = $('#cantidad').val();
    let precio = $('#precio_unitario').val();
    let subtotal =cantidad*precio;
    subtotal = subtotal - (subtotal * ($('#descuento').val()/100));
    let impuesto = ($('#fk_id_impuesto').select2('data')[0].porcentaje * subtotal)/100;
    subtotal_original = parseFloat(subtotal_original)+subtotal;
    subtotal_original = subtotal_original.toFixed(2);
    // console.log('Subtotal original: '+subtotal_original);
    console.log('AGREGAR. original: '+subtotal_original+' producto: '+subtotal);
    return (subtotal + impuesto).toFixed(2);
}

function totalOrden() {

    let subtotal = 0;
    let impuesto = 0;
    let descuento_total = 0;
    $.each(window.dataTable.data,function () {
        //Del producto
        let cantidad_row = $(this).find('td .cantidad_row').val();
        let precio_row = $(this).find('td .precio_unitario_row').val();
        let porcentaje_row = $(this).find('td .porcentaje').val()/100;//Decimal
        let descuento_row = $(this).find('td .descuento_row').val();//Decimal
        descuento_row = (descuento_row * precio_row)/100;
        descuento_total += descuento_row;
        let subtotal_row = (precio_row - descuento_row) * cantidad_row;
        // let total_row = (subtotal_row * porcentaje_row) + subtotal_row;
        //Del total
        subtotal += subtotal_row;
        impuesto += subtotal_row * porcentaje_row;
        // console.log('cantidad: '+cantidad_row+' precio: '+precio_row+' porcentaje: '+porcentaje_row+' descuento: '+descuento_row+' impuesto: '+impuesto+' subtotal: '+subtotal_row+' total:'+total_row);
    });
    subtotal = subtotal - $('#descuento_general').val();
    descuento_total += +$('#descuento_general').val();

    // console.log('subtotal: '+subtotal);
    // console.log('impuesto: '+impuesto);
    // console.log('total: '+total);
    let total = (subtotal)+impuesto;
    $('#subtotal_lbl').text(subtotal.toFixed(2));
    $('#subtotal').val(subtotal.toFixed(2));
    $('#impuesto_lbl').text(impuesto.toFixed(2));
    $('#impuesto_total').val(impuesto.toFixed(2));
    $('#total_orden').val(total.toFixed(2));
    $('#descuento_total').val(descuento_total.toFixed(2));
}

function borrarFila(el) {
    let fila = dataTable.data[$(el).parents('tr').index()];
    let cantidad = $(fila).find('td .cantidad_row').val();
    let precio = $(fila).find('td .precio_unitario_row').val();
    let descuento = $(fila).find('td .descuento_row').val();
    descuento = (descuento * precio)/100;
    let subtotal = (precio-descuento)*cantidad;
    subtotal_original -= subtotal;
    subtotal_original = subtotal_original.toFixed(2);
    dataTable.rows().remove([$(el).parents('tr').index()]);
        $.toaster({priority : 'success',title : '¡Advertencia!',message : 'Se ha eliminado la fila correctamente',
            settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});

    console.log('BORRAR. original: '+subtotal_original+' producto: '+subtotal);
    totalOrden();
}

function limpiarCampos() {
    $('#fk_id_sku').val(0).trigger('change');
    $('#fk_id_upc').val(0).trigger('change').prop('disabled',true);
    $('#activo_upc').prop('checked',false);
    $('#fk_id_proyecto').val(0).trigger('change');
    $('#fk_id_cliente').val(0).trigger('change');
    $('#fk_id_impuesto').val('0').trigger('change');
    $('#fecha_necesario').val('');
    $('#cantidad').val('1');
    $('#precio_unitario').val('');
    //Eliminar reglas de validación detalle
    $('#fk_id_sku').rules('remove');
    $('#fk_id_upc').rules('remove');
    $('#fk_id_proyecto').rules('remove');
    $('#fecha_necesario').rules('remove');
    $('#cantidad').rules('remove');
    $('#fk_id_impuesto').rules('remove');
    $('#precio_unitario').rules('remove');
}

function validateDetail() {
    $('#fk_id_sku').rules('add',{
        required: true,
        messages:{
            required: 'Selecciona un SKU'
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
    $('#fk_id_impuesto').rules('add',{
        required: true,
        messages:{
            required: 'Selecciona un tipo de impuesto'
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
    $.validator.addMethod('porcentaje',function (value,element) {
        return this.optional(element) || /^\d{0,2}(\.\d{0,4})?$/g.test(value);
    },'El porcentaje tiene un formato incorrecto');
    $.validator.addMethod( "lessThan", function( value, element, param ) {
        return value <= param;
    }, "Please enter a greater value." );
    $('#descuento_porcentaje').rules('add',{
        porcentaje: true,
        greaterThan: -1,
        lessThan: 100,
        messages:{
            greaterThan: 'El número no debe ser menor a 0',
            lessThan: 'El porcentaje debe ser menor a 100'
        }
    });
    $('#descuento_general').rules('add',{
        precio: true,
        lessThan: subtotal_original,
        messages: {
            lessThan: 'El descuento no debe ser mayor al subtotal'
        }
    });
}

function borrarFila_edit(el) {
    let fila = dataTable.data[$(el).parents('tr').index()];
    let cantidad = $(fila).find('td .cantidad_row').val();
    let precio = $(fila).find('td .precio_unitario_row').val();
    let subtotal = cantidad * precio;
    subtotal = subtotal - (subtotal * ($('#descuento').val()/100));
    subtotal_original = parseFloat(subtotal_original) - parseFloat(subtotal);
    a.push(el.id);
    dataTable.rows().remove([$(el).parents('tr').index()]);

    totalOrden();
}
