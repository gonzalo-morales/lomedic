$('.datepicker').pickadate({
    selectMonths: true, // Creates a dropdown to control month
    selectYears: 3, // Creates a dropdown of 3 years to control year
    format: 'yyyy-mm-dd'
});

$(document).ready(function () {
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
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
    $('#fk_id_orden_compra').select2({
        minimumResultsForSearch: 15
    });

    $('#fk_id_orden_compra').change(function () {
        var existe = false;
        $('#detalle_solicitud_pago tr').each (function (index,row) {
            if($(this).find('.orden').val() == $('#fk_id_orden_compra').val()){
                $.toaster({
                    priority: 'danger', title: 'Error', message: 'Ya se ha agregado esta orden',
                    settings: {'timeout': 5000, 'toaster': {'css': {'top': '5em'}}}
                });
                existe = true;
            }
        });
        if(!existe && $('#fk_id_orden_compra').val() > 0) {
            $('#loadingtabla').show();
            $.ajax({
                url: $('#fk_id_orden_compra').data('url'),
                type: 'GET',
                data: {'param_js': orden_js, $fk_id_orden: $('#fk_id_orden_compra').val()},
                dataType: 'json',
                success: function (data) {
                    var i = $('#detalle_solicitud_pago tr').length;
                    var index = i > 0 ? +$('#detalle_solicitud_pago tr:last').find('#index').val()+1 : 0;
                    var impuesto = +data[0].impuesto;
                    var subtotal = +data[0].subtotal;
                    var total = +data[0].total_orden;
                    $('#detalle_solicitud_pago').append(
                        '<tr>' +
                        '<td><input class="orden" type="hidden" name="relations[has][detalle][' + index + '][fk_id_orden_compra]" value="' + data[0].id_orden + '">' + data[0].id_orden + '<input type="hidden" id="index" value="'+index+'"></td>' +
                        '<td><input type="hidden" name="relations[has][detalle][' + index + '][descripcion]" value="Orden de compra ' + data[0].id_orden + '">Orden de compra ' + data[0].id_orden + '</td>' +
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
                '<td><input type="hidden" id="index" value="'+index+'"><input type="hidden" name="relations[has][detalle][' + index + '][fk_id_orden_compra]" value="">N/A</td>' +
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
});

function borrarFila(el) {
    $(el).parent().parent('tr').remove();
    $.toaster({priority:'success',title:'¡Correcto!',message:'Se ha eliminado la orden correctamente',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
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
            number: 'El campo debe ser un número',
            range: 'El número debe ser entre 1 y 9999'
        }
    });
    $.validator.addMethod('precio',function (value,element) {
        return this.optional(element) || /^\d{0,10}(\.\d{0,2})?$/g.test(value);
    },'El precio no debe tener más de dos decimales');
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
            number: 'El campo debe ser un número',
            greaterThan: 'El número debe ser mayor a 0',
            precio: 'El precio no debe tener más de dos decimales ni más de 10 enteros y debe ser positivo'
        }
    });
    var impuesto_maximo = $('#precio_unitario').val()*$('#cantidad').val();
    $('#impuesto').rules('add',{
        required:false,
        number: true,
        precio:true,
        lessThan:impuesto_maximo,
        messages:{
            number: 'El campo debe ser un número',
            lessThan: 'El número debe ser menor a precio por cantidad',
            precio: 'El impuesto no debe tener más de dos decimales ni más de 10 enteros y y debe ser positivo'
        }
    });
}