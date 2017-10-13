var a=[];
// Inicializar los datepicker para las fechas necesarias
$('.datepicker').pickadate({
    selectMonths: true, // Creates a dropdown to control month
    selectYears: 2, // Creates a dropdown of 2 years to control year
    min: new Date(2017,0,1),//Primero de enero del 2017
    format: 'yyyy-mm-dd'
});
$(document).ready(function(){
    //Inicializar tabla
    window.dataTable = new DataTable('#productos', {
        fixedHeight: true,
        fixedColumns: true,
        searchable: false,
        perPageSelect: false
    });
    // if(window.location.href.toString().indexOf('editar') > -1 || window.location.href.toString().indexOf('crear') > -1 || window.location.href.toString().indexOf('solicitudOrden') > -1)
    // {
    //     initSelects();
    //     if(window.location.href.toString().indexOf('crear') > -1){
    //         validateDetail();
    //     }
    //     //Por si se selecciona una empresa diferente
    //     $('#otra_empresa').on('change',function () {
    //         $( this ).parent().nextAll( "select" ).prop( "disabled", !this.checked );
    //         if( !this.checked ){
    //             if(window.location.href.toString().indexOf('crear') > -1)
    //                 $( this ).parent().nextAll( "select" ).val(0).trigger('change');
    //         }
    //     });
    //     //Por si se selecciona un UPC
    //     $('#activo_upc').on('change',function () {
    //         $( this ).parent().nextAll( "select" ).prop( "disabled", !this.checked );
    //         if( !this.checked ){
    //             $( this ).parent().nextAll( "select" ).val(0).trigger('change');
    //         }else{
    //             if($('#fk_id_sku').val()){
    //                 _url = $('#fk_id_upc').data('url').replace('?id',$('#fk_id_sku').val());
    //                 $( this ).parent().nextAll( "select" ).select2({
    //                     theme: "bootstrap",
    //                     minimumResultsForSearch: Infinity,
    //                     ajax:{
    //                         url: _url,
    //                         dataType: 'json',
    //                         data: function (term) {
    //                             return {term: term};
    //                         },
    //                         processResults: function (data) {
    //                             return {results: data}
    //                         },
    //                         cache:true
    //                     }
    //                 })
    //             }else{
    //                 $( this ).prop('checked',false);
    //                 $( this ).parent().nextAll( "select" ).prop( "disabled", !this.checked );
    //                 $.toaster({priority : 'danger',title : '¡Error!',message : 'Selecciona antes un SKU',
    //                     settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
    //             }
    //         }
    //     })//Fin UPC
    //
    //     $('#agregar').on('click',function () {
    //         agregarProducto();
    //     });
    //
    //     $('#fk_id_socio_negocio').on('change',function () {
    //         $('#tiempo_entrega').val($('#fk_id_socio_negocio').select2('data')[0].tiempo_entrega);
    //         var fecha = new Date();
    //         fecha.setDate(fecha.getDate()+$('#fk_id_socio_negocio').select2('data')[0].tiempo_entrega);
    //         $('#fecha_estimada_entrega').val(fecha.getFullYear()+'-'+fecha.getMonth()+'-'+fecha.getDate());
    //     });
    //     $(document).on('submit',function (e) {
    //         if(a.length>0) {
    //             e.preventDefault();
    //             let url = $('#productos').data('delete');
    //             $.delete(url, {ids: a});
    //             a = [];
    //         }
    //     })
    // }else{
    //     totalOrden();
    // }
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

        dataTable.insert( {
            data:[
            $('<input type="hidden" name="_detalles['+row_id+'][fk_id_solicitud]"/>')[0].outerHTML + 'N/A',
            $('<input type="hidden" name="_detalles['+row_id+'][fk_id_sku]" value="' + $('#fk_id_sku').select2('data')[0].id + '" />')[0].outerHTML + $('#fk_id_sku').select2('data')[0].text,
            $('<input type="hidden" name="_detalles['+row_id+'][fk_id_upc]" value="' + $('#fk_id_upc').select2('data')[0].id + '" />')[0].outerHTML + $('#fk_id_upc').select2('data')[0].text,
            $('#fk_id_sku').select2('data')[0].nombre_comercial,
            $('#fk_id_sku').select2('data')[0].descripcion,
            $('<input type="hidden" name="_detalles['+row_id+'][fk_id_cliente]" value="' + $('#fk_id_cliente').select2('data')[0].id + '" />')[0].outerHTML + $('#fk_id_cliente').select2('data')[0].text,
            $('<input type="hidden" name="_detalles['+row_id+'][fk_id_proyecto]" value="' + $('#fk_id_proyecto').select2('data')[0].id + '" />')[0].outerHTML + $('#fk_id_proyecto').select2('data')[0].text,
            $('<input type="hidden" name="_detalles['+row_id+'][fecha_necesario]" value="' + $('#fecha_necesario').val() + '" />')[0].outerHTML + $('#fecha_necesario').val(),
            $('<input type="hidden" name="_detalles['+row_id+'][cantidad]" value="' + $('#cantidad').val() + '" />')[0].outerHTML + $('#cantidad').val(),
            $('<input type="hidden" name="_detalles['+row_id+'][fk_id_impuesto]" value="' + $('#fk_id_impuesto').select2('data')[0].id + '" />')[0].outerHTML + $('#fk_id_impuesto').select2('data')[0].text,
            $('<input type="hidden" name="_detalles['+row_id+'][precio_unitario]" value="' + $('#precio_unitario').val() + '" />')[0].outerHTML + $('#precio_unitario').val(),
            $('<input type="text" value="'+ total +'" name="_detalles['+row_id+'][total]" class="form-control total" readonly>')[0].outerHTML,
            '<button class="btn is-icon text-primary bg-white" type="button" data-delay="50" onclick="borrarFila(this)"> <i class="material-icons">delete</i></button>'
            ]
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
    let impuesto = ($('#fk_id_impuesto').select2('data')[0].porcentaje * subtotal)/100;

    return (subtotal + impuesto).toFixed(2);
}

function totalOrden() {

    let total = 0;
    $(".total").each(function () {
        total +=  parseFloat($(this).val());
    });
    $('#total_orden').val(total.toFixed(2));
}

function borrarFila(el) {
    dataTable.rows().remove([$(el).parents('tr').dataIndex]);
        $.toaster({priority : 'success',title : '¡Advertencia!',message : 'Se ha eliminado la fila correctamente',
            settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
    if(dataTable.rows.length<1)
        validateDetail();

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

        if ( this.settings.onfocusout ) {
            $(element).addClass( "validate-greaterThan-blur" ).on( "blur.validate-greaterThan", function() {
                $( element ).valid();
            } );
        }

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
}

function borrarFila_edit(el) {
    a.push(el.id);
    dataTable.rows().remove([$(el).parents('tr').dataIndex]);
    if(dataTable.activeRows.length<1)
        validateDetail();

    totalOrden();
}
