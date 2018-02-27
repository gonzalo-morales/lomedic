$(document).ready(function () {
	
    $('.datepicker').pickadate({
        selectMonths: true, // Creates a dropdown to control month
        selectYears: 3, // Creates a dropdown of 3 years to control year
        format: 'yyyy-mm-dd'
    });
    
    $('#fk_id_moneda').on('change', function() {
		$('#tipo_cambio').attr('readonly',$('#fk_id_moneda').val() == 100);
		
		if($('#fk_id_moneda').val() == 100)
			$('#tipo_cambio').val('1.00');
		else
			$('#tipo_cambio').val('');
	});

    $('#fk_id_factura,#fk_id_solicitud').change(function () {
        var caso = $(this).attr('id');
        switch(caso) {
            case 'fk_id_factura':
                if($(this).val() > 0){
                    $('#fk_id_solicitud').attr('disabled',true);
                    $('#fk_id_solicitud').val(0);
                }else{
                    $('#fk_id_solicitud').removeAttr('disabled');
                }
                break;
            case 'fk_id_solicitud':
                if($(this).val() > 0){
                    $('#fk_id_factura').attr('disabled',true);
                    $('#fk_id_factura').val(0);
                }else{
                    $('#fk_id_factura').removeAttr('disabled');
                }
                break;
        }
    });

    $('#agregar').click(function () {
        if(+$('#monto').val() != +$('#monto_aplicado').val()){
            $('#loadingpagos').show();
            var i = $('#pagos_realizados tr').length;
            var index = i > 0 ? +$('#pagos_realizados tr:last').find('#index').val() + 1 : 0;
            var descripcion = '';
            var tipo_documento = null;
            var fk_id_documento = null;
            var _url = '';
            var variable_a_enviar = null;
            if($('#fk_id_factura').val() > 0){
                descripcion = 'Factura no.'+$('#fk_id_factura').val();
                tipo_documento = 7;
                fk_id_documento = $('#fk_id_factura').val();
                _url = $('#fk_id_factura').data('url');
                variable_a_enviar = factura_js;
            }else if($('#fk_id_solicitud').val() > 0){
                descripcion = 'Solicitud no.'+$('#fk_id_solicitud').val();
                tipo_documento = 10;
                fk_id_documento = $('#fk_id_solicitud').val();
                _url = $('#fk_id_solicitud').data('url');
                variable_a_enviar = solicitud_js;
            }
            if($('#fk_id_solicitud').val() < 1 && $('#fk_id_factura').val() < 1){
                $.toaster({
                    priority: 'danger', title: 'Error', message: 'Por favor selecciona una factura o una solicitud',
                    settings: {'timeout': 5000, 'toaster': {'css': {'top': '5em'}}}
                });
                $('#loadingpagos').hide();
            }else{
                $.ajax({
                    url:_url,
                    type: 'GET',
                    data: {'param_js': variable_a_enviar, $fk_id_documento:fk_id_documento},
                    dataType: 'json',
                    success: function (data) {
                        var a_pagar = +data[0].total;
                        var pagado = +data[0].total_pagado;
                        validateDetail(a_pagar,pagado);
                        if($('#form-model').valid()){
                            if($('#pagos_realizados').append(
                                    '<tr>' +
                                    '<td>' +
                                    '<input type="hidden" id="index" value="'+index+'">' +
                                    '<input name="relations[has][detalle]['+index+'][fk_id_documento]" value="'+fk_id_documento+'" type="hidden">' +
                                    '<input name="relations[has][detalle]['+index+'][fk_id_tipo_documento]" value="'+tipo_documento+'" type="hidden">' +
                                    descripcion+
                                    '</td>' +
                                    '<td><input type="hidden" class="total_documento" value="'+a_pagar.toFixed(2)+'">$ '+ a_pagar.toFixed(2)+'</td>' +
                                    '<td><input type="hidden" class="total_documento" value="'+pagado.toFixed(2)+'">$ '+ pagado +'</td>' +
                                    '<td><input class="monto" name="relations[has][detalle]['+index+'][monto]" value="'+$('#monto_detalle').val()+'" type="hidden">$ '+$('#monto_detalle').val()+'</td>' +
                                    '<td><button class="btn is-icon text-primary bg-white" type="button" data-delay="50" onclick="borrarFila(this)"> <i class="material-icons">delete</i></button></td>' +
                                    '</tr>'
                                )){
                                calcular_total_pagado();
                                limpiarcampos();
                                $.toaster({
                                    priority: 'success', title: 'Éxito', message: 'Se ha insertado el registro',
                                    settings: {'timeout': 5000, 'toaster': {'css': {'top': '5em'}}}
                                });
                                $('#loadingpagos').hide();
                            }
                        }else{
                            $('#loadingpagos').hide();
                        }
                    },
                    error: function () {
                        $('#loadingpagos').hide();
                        $.toaster({
                            priority: 'danger', title: 'Error', message: 'Error al insertar',
                            settings: {'timeout': 5000, 'toaster': {'css': {'top': '5em'}}}
                        });
                    }
                });
            }
        }else{
            $.toaster({
                priority: 'danger', title: 'Error', message: 'El monto general y el aplicado ya es el mismo',
                settings: {'timeout': 5000, 'toaster': {'css': {'top': '5em'}}}
            });
        }
    });

    $(document).on('submit',function (e) {
       if($('#pagos_realizados tr').length < 1){
           e.preventDefault();
           $.toaster({
               priority: 'danger', title: 'Error', message: 'No se han agregado documentos',
               settings: {'timeout': 5000, 'toaster': {'css': {'top': '5em'}}}
           });
       }

       if($('#comprobante_input')[0].files.length == 0){
           e.preventDefault();
           $.toaster({
               priority: 'danger', title: 'Error', message: 'Favor de anexar un comprobante',
               settings: {'timeout': 5000, 'toaster': {'css': {'top': '5em'}}}
           });
       }
    });
});
function validateDetail(a_pagar,pagado) {
    $.validator.addMethod('precio',function (value,element) {
        return this.optional(element) || /^\d{0,10}(\.\d{0,2})?$/g.test(value);
    },'El precio no debe tener mÃƒÆ’Ã‚Â¡s de dos decimales');
    $.validator.addMethod( "greaterThan", function( value, element, param ) {
        return value > param;
    }, "Please enter a greater value." );
    $.validator.addMethod( "lessThanPago", function( value, element, param ) {
        return value <= param;
    }, "Please enter a greater value." );
    $.validator.addMethod( "lessThanDocumento", function( value, element, param ) {
        return value <= param;
    }, "Please enter a greater value." );
    var monto_general = +$('#monto').val();
    var pagado_total = +$('#monto_aplicado').val();
        $('#monto_detalle').rules('add',{
            number: true,
            precio:true,
            greaterThan:0,
            lessThanPago: +monto_general - +pagado_total,
            lessThanDocumento: +a_pagar - +pagado,
            messages:{
                required: 'Ingresa un precio unitario',
                number: 'El campo debe ser un Número',
                greaterThan: 'El número debe ser mayor a 0',
                lessThanPago:'El monto no puede superar al monto general menos el total pagado',
                lessThanDocumento:'El monto debe ser menor al total documento menos lo pagado ',
                precio: 'El precio no debe tener mas de dos decimales ni mas de 10 enteros y debe ser positivo'
            }
        });
}

function borrarFila(el) {
    $(el).parent().parent('tr').remove();
    $.toaster({priority:'success',title:'Ãƒâ€šÃ‚Â¡Correcto!',message:'Se ha eliminado la orden correctamente',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
    calcular_total_pagado();
}

function calcular_total_pagado() {
    var total = 0;
    $('#pagos_realizados tr').each(function (i,row) {
        var pagado = +$(row).find('.monto').val();
        total += pagado;
    });
    $('#monto_aplicado').val(total.toFixed(2));
}

function limpiarcampos() {
    $('#monto_detalle').val('').rules('remove');
    $('#fk_id_solicitud').val(0).removeAttr('disabled');
    $('#fk_id_factura').val(0).removeAttr('disabled');
}