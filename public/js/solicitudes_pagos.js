$(document).on('submit',function (e) {
    if($('#detalle_solicitud_pago tr').length < 1)
    {
        e.preventDefault();
        $.toaster({
            priority: 'danger', title: 'Error', message: 'Por favor agrega al menos un detalle',
            settings: {'timeout': 5000, 'toaster': {'css': {'top': '5em'}}}
        });
    }
});

$(document).ready(function () {
    //Para las autorizaciones de solicitudes de compras
    $('input[type=radio][name=fk_id_estatus]').change(function () {
        if($(this).val() == 4){//Si es autorizada
            $('#observaciones').attr('readonly','readonly');
            $('#observaciones').val("");
        }else{
            $('#observaciones').removeAttr('readonly');
        }
    });
    $('.condicion').click(function () {
        $('#motivo_autorizacion').val($(this).parent().parent().find('td:nth-child(2)').text());
        // $('#fk_id_estatus\\ ').prop('checked',true);
        $('#id_documento').val($(this).parent().parent().find('td input:first').val());
        $('#observaciones').val($(this).parent().parent().find('td input:first').next('input').val());
        if($(this).parent().parent().find('td input:last').val() == 3){
            $('#fk_id_estatus\\ 3').prop('checked',true);
            $('#observaciones').removeAttr('readonly');
        }else if($(this).parent().parent().find('td input:last').val() == 4){
            $('#fk_id_estatus\\ 4').prop('checked',true);
            $('#observaciones').attr('readonly','readonly');
        }
    });

    $('#guardar_autorizacion').click(function (e) {
       if($('input[type=radio][name=fk_id_estatus]:checked').val() == 3 && !$('#observaciones').val()) {
           $.toaster({
               priority: 'danger', title: 'Error', message: 'Por favor escribe un motivo de rechazo',
               settings: {'timeout': 5000, 'toaster': {'css': {'top': '5em'}}}
           });
       }else if(!$('input[type=radio][name=fk_id_estatus]:checked').val()){
           $.toaster({
               priority: 'danger', title: 'Error', message: 'Por favor selecciona si se autoriza o no',
               settings: {'timeout': 5000, 'toaster': {'css': {'top': '5em'}}}
           });
        }else{
           $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
           var autorizar_url = $('#id_documento').data('url').replace('?id',$('#id_documento').val());//id_autorizacion
           $.ajax({
               url:autorizar_url,
               type:'PUT',
               data:{
                   observaciones:$('#observaciones').val(),
                   fk_id_estatus:$('input[type=radio][name=fk_id_estatus]:checked').val()
               },
               success:function (data) {
                   if(data.status == 1){
                       $('#autorizacion').modal('toggle');
                       $.toaster({
                           priority: 'success', title: 'Ã‰xito', message: 'Se ha actualizado la informaciÃ³n de la autorizaciÃ³n. Recarga la pÃ¡gina para ver los cambios.',
                           settings: {'timeout': 5000, 'toaster': {'css': {'top': '5em'}}}
                       });
                   }else{
                       $.toaster({
                           priority: 'danger', title: 'Error', message: 'Ha ocurrido un error',
                           settings: {'timeout': 5000, 'toaster': {'css': {'top': '5em'}}}
                       });
                   }
               }
           });
       }
    });
    //AquÃ­ termina la parte de las autorizaciones

    $('#fk_id_solicitante').change(function () {
        $('#fk_id_sucursal').empty();
        $('#loadingsucursales').show();
        $.ajax({
            url: $('#fk_id_sucursal').data('url'),
            type: 'GET',
            data: {'param_js':sucursales_js,$fk_id_empleado:$(this).val()},
            dataType: 'json',
            success: function (data) {
                $('#fk_id_sucursal').select2({
                    data:data
                });
                $('#loadingsucursales').hide();
            },
            error: function () {
                $('#loadingsucursales').hide();
            }
        });
    });
    $('.select2').select2({
        minimumResultsForSearch: 15
    });

    $('#fk_id_linea').change(function () {
        var existe = false;
        $('#detalle_solicitud_pago tr').each (function (index,row) {
            if($(this).find('.orden').val() == $('#fk_id_linea').val()){
                $.toaster({
                    priority: 'danger', title: 'Error', message: 'Ya se ha agregado esta orden',
                    settings: {'timeout': 5000, 'toaster': {'css': {'top': '5em'}}}
                });
                existe = true;
            }
        });
        if(!existe && $('#fk_id_linea').val() > 0) {
            $('#loadingtabla').show();
            $.ajax({
                url: $('#fk_id_linea').data('url'),
                type: 'GET',
                data: {'param_js': orden_js, $fk_id_orden: $('#fk_id_linea').val()},
                dataType: 'json',
                success: function (data) {
                    var i = $('#detalle_solicitud_pago tr').length;
                    var index = i > 0 ? +$('#detalle_solicitud_pago tr:last').find('#index').val()+1 : 0;
                    var impuesto = +data[0].impuesto;
                    var subtotal = +data[0].subtotal;
                    var total = +data[0].total_orden;
                    $('#detalle_solicitud_pago').append(
                        '<tr>' +
                        '<td><input class="orden" type="hidden" name="relations[has][detalle][' + index + '][fk_id_linea]" value="' + data[0].id_documento + '">' + data[0].id_documento + '<input type="hidden" id="index" value="'+index+'"><input type="hidden" name="relations[has][detalle][' + index + '][fk_id_tipo_documento_base]" value="'+data[0].fk_id_tipo_documento+'"></td>' +
                        '<td><input type="hidden" name="relations[has][detalle][' + index + '][descripcion]" value="Orden de compra ' + data[0].id_documento + '">Orden de compra ' + data[0].id_documento + '</td>' +
                        '<td><input type="hidden" name="relations[has][detalle][' + index + '][cantidad]" value="1">1</td>' +
                        '<td><input type="hidden" name="relations[has][detalle][' + index + '][impuesto]" value="' + impuesto.toFixed(2) + '">' + impuesto.toFixed(2) + '</td>' +
                        '<td><input type="hidden" name="relations[has][detalle][' + index + '][precio_unitario]" value="' + subtotal.toFixed(2) + '">' + subtotal.toFixed(2) + '</td>' +
                        '<td><input class="importe" type="hidden" name="relations[has][detalle][' + index + '][importe]" value="' + total.toFixed(2) + '">' + total.toFixed(2) + '</td>' +
                        '<td><button class="btn is-icon text-primary bg-white" type="button" data-delay="50" onclick="borrarFila(this)"> <i class="material-icons">delete</i></button></td>' +
                        '</tr>'
                    );
                    $('#loadingtabla').hide();
                    $.toaster({
                        priority: 'success', title: 'Exito', message: 'Orden agregada',
                        settings: {'timeout': 5000, 'toaster': {'css': {'top': '5em'}}}
                    });
                    total_solicitud()
                },
                error: function () {
                    $('#loadingtabla').hide();
                    $.toaster({
                        priority: 'danger', title: 'Error', message: 'Ha ocurrido un error al cargar los datos',
                        settings: {'timeout': 5000, 'toaster': {'css': {'top': '5em'}}}
                    });
                }
            });
        }
    });

    $("#agregar").click(function () {
        validateDetail();
        if($('#form-model').valid()) {
            var i = $('#detalle_solicitud_pago tr').length;
            var index = i > 0 ? +$('#detalle_solicitud_pago tr:last').find('#index').val()+1 : 0;

            var impuesto = +$('#impuesto').val();
            var subtotal = +$('#precio_unitario').val();
            var cantidad = +$('#cantidad').val();
            var total = impuesto + ( subtotal * cantidad);
            $('#detalle_solicitud_pago').append(
                '<tr>' +
                '<td><input type="hidden" id="index" value="'+index+'"><input type="hidden" name="relations[has][detalle][' + index + '][fk_id_linea]" value="">N/A</td>' +
                '<td><input type="hidden" name="relations[has][detalle][' + index + '][descripcion]" value="' + $('#descripcion').val() + '">' + $('#descripcion').val() + '</td>' +
                '<td><input type="hidden" name="relations[has][detalle][' + index + '][cantidad]" value="' + $('#cantidad').val() + '">' + $('#cantidad').val() + '</td>' +
                '<td><input type="hidden" name="relations[has][detalle][' + index + '][impuesto]" value="' + $('#impuesto').val() + '">' + $('#impuesto').val() + '</td>' +
                '<td><input type="hidden" name="relations[has][detalle][' + index + '][precio_unitario]" value="' + $('#precio_unitario').val() + '">' + $('#precio_unitario').val() + '</td>' +
                '<td><input class="importe" type="hidden" name="relations[has][detalle][' + index + '][importe]" value="' + total.toFixed(2) + '">' + total.toFixed(2) + '</td>' +
                '<td><button class="btn is-icon text-primary bg-white" type="button" data-delay="50" onclick="borrarFila(this)"> <i class="material-icons">delete</i></button></td>' +
                '</tr>'
            );
            $.toaster({
                priority: 'success', title: 'Exito', message: 'Concepto agregado',
                settings: {'timeout': 5000, 'toaster': {'css': {'top': '5em'}}}
            });
            limpiarCampos();
            total_solicitud();
        }
    });

    $('.calculo').keyup(function () {
        var impuesto = +$('#impuesto').val();
        var subtotal = +$('#precio_unitario').val();
        var cantidad = +$('#cantidad').val();
        var total = impuesto + ( subtotal* cantidad);
        $('#importe').val(total.toFixed(2));
    });

    $('#reload').click(function (e) {
        e.preventDefault();
        window.location.reload(true);
    })
});

function borrarFila(el) {
    $(el).parent().parent('tr').remove();
    $.toaster({priority:'success',title:'Â¡Correcto!',message:'Se ha eliminado la orden correctamente',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
    total_solicitud();
}
function total_solicitud() {
    var total = 0;
    $('#detalle_solicitud_pago tr').each(function (i,row) {
        var importe = +$(row).find('.importe').val();
        // var importe = +$(row).find('name=["relations[has][detalle][' + index + '][importe]"]').val();
        total += importe;
    });
    $('#total').val(total.toFixed(2));
}
function limpiarCampos() {
    $('#descripcion').val('').rules('remove');
    $('#cantidad').val('').rules('remove');
    $('#impuesto').val('').rules('remove');
    $('#precio_unitario').val('').rules('remove');
    $('#importe').val('').rules('remove');
}

function validateDetail() {
    $('#descripcion').rules('add',{
        required: true,
        maxlength: 255,
        messages: {
            required: 'Este campo es requerido',
            maxlength: 'No debe exceder los 255 caracteres'
        }
    });
    $('#cantidad').rules('add',{
        required: true,
        number: true,
        range: [1,9999],
        messages:{
            required: 'Ingresa una cantidad',
            number: 'El campo debe ser un nÃºmero',
            range: 'El nÃºmero debe ser entre 1 y 9999'
        }
    });
    $.validator.addMethod('precio',function (value,element) {
        return this.optional(element) || /^\d{0,10}(\.\d{0,2})?$/g.test(value);
    },'El precio no debe tener mÃ¡s de dos decimales');
    $.validator.addMethod( "greaterThan", function( value, element, param ) {
        return value > param;
    }, "Please enter a greater value." );
    $.validator.addMethod( "lessThan", function( value, element, param ) {
        return value <= param;
    }, "Please enter a greater value." );
    $('#precio_unitario').rules('add',{
        required: true,
        number: true,
        precio:true,
        greaterThan:0,
        messages:{
            required: 'Ingresa un precio unitario',
            number: 'El campo debe ser un nÃºmero',
            greaterThan: 'El nÃºmero debe ser mayor a 0',
            precio: 'El precio no debe tener mÃ¡s de dos decimales ni mÃ¡s de 10 enteros y debe ser positivo'
        }
    });
    var impuesto_maximo = $('#precio_unitario').val()*$('#cantidad').val();
    $('#impuesto').rules('add',{
        required:false,
        number: true,
        precio:true,
        lessThan:impuesto_maximo,
        messages:{
            number: 'El campo debe ser un nÃºmero',
            lessThan: 'El nÃºmero debe ser menor a precio por cantidad',
            precio: 'El impuesto no debe tener mÃ¡s de dos decimales ni mÃ¡s de 10 enteros y y debe ser positivo'
        }
    });
}