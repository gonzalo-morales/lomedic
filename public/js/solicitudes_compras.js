// Inicializar los datepicker para las fechas necesarias
$('.datepicker').pickadate({
    selectMonths: true, // Creates a dropdown to control month
    selectYears: 3, // Creates a dropdown of 3 years to control year
    min: true,
    format: 'yyyy-mm-dd'
});
$(document).ready( function () {

    // function dataUsers($usuarios){
    //     $.map($usuarios, function (obj) {
    //         obj.id = obj.id || obj.text.id_usuario; // replace pk with your identifier
    //         return obj;
    //     });
    //     return $usuarios
    // }
        
    $('#fk_id_solicitante.select2').select2({
        placeholder: "Para iniciar es necesario indicar el Solicitante",
        // data:function($usuarios){
        //     $.map($usuarios, function (obj) {
        //         obj.text = 'obj.text.usuario';
        //         return obj; 
        //     });
        // },
        // data:function($usuarios){
        //     return {
        //         id = $usuarios.id_usuario,
        //         text = $usuarios.usuario,
        //     };
        // },
        // escapeMarkup: function (markup) { return markup; },
        // templateResult:formatUsers,
        // templateSelection:formatUsersSelection,
        disabled: false
    });
    // function formatUsers($usuarios){
    //     console.log($usuarios)
    //     var markup = "<div class='select2-result-pers clearfix'>" +
    //     "<div class='select2-result-pers__avatar'><img src='img/sku.png'/></div>" +
    //     "<div class='select2-result-pers__meta'>" +
    //     "<div class='select2-result-pers__text'>" + $usuarios.text.usuario + "</div>";

    //     // markup += "<div class='select2-result-pers__statistics'>" +
    //     // "<div class='select2-result-pers__presentacion text-success mr-3'><i class='material-icons align-middle'>shopping_basket</i> " + data.stock + "</div>" +
    //     // "<div data-position='bottom' data-delay='50' data-toggle='"+mensaje+"' title='"+mensaje+"' class='select2-result-pers__presentacion"+" "+claseFecha+" "+"mr-3'><i class='material-icons align-middle'>today</i> " + data.fecha_caducidad + "</div>" +
    //     // "<div class='select2-result-pers__presentacion'><i class='material-icons align-middle'>label</i> " + data.lote + "</div>" +
    //     "</div>" +
    //     "</div></div>";

    //         return markup;
    // }
    // function formatUsersSelection ($usuarios) {
    //     return $usuarios.usuario;
    // }

    
    $('#fk_id_sucursal.select2').select2({
        placeholder: "Para iniciar es necesario indicar el Solicitante",
        disabled: true
    });
    $('#fk_id_proveedor.select2').select2({
        placeholder: "Seleccione el SKU",
        disabled: true
    });
    $('#fk_id_upc').select2({
        placeholder: "Seleccione el SKU",
        disabled: true
    });
    $('#fk_id_proyecto.select2').select2({
        placeholder: "Seleccione el Proyecto",
        disabled: false
    });

    if(window.location.href.toString().indexOf('editar') > -1)//Si es editar
    {
        $('#fk_id_sucursal.select2').select2({
            disabled: false
        });
        $('.selectImpuestos').each(function (index, element) {
            // element == this
            
        });
        for (let i = 0; i < $('.selectImpuestos').length; i++) {
            let $this = $($('.selectImpuestos')[i]);
            $('.progress-button').prop('disabled',true);
            let idimpuesto = $this.val();
            let _url = $('#fk_id_impuesto').data('url');
            $.ajax({
                async: true,
                url: _url,
                data: {'param_js':porcentaje_js,$id_impuesto:idimpuesto},
                dataType: 'json',
                    success: function (data) {
                      if(data[0].tasa_o_cuota == null)
                        $this.parent().find('.rowImpuestos').val(0);
                      else
                        $this.parent().find('.rowImpuestos').val(data[0].tasa_o_cuota);
                    $('.progress-button').prop('disabled',false);
                }
            }); 
        }
    }

    $(document).on('submit',function (e) {
        $.validator.addMethod('cRequerido',$.validator.methods.required,'Este campo es requerido');
        $.validator.addClassRules('requerido',{
           cRequerido: true
        });
        if(!$('#form-model').valid()){
            $.toaster({priority : 'danger',title : '¡Error!',message : 'Hay campos que requieren tu atención',
            settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
            e.preventDefault();
        }
    });

    $('#fk_id_solicitante').on('change', function () {
        $('#fk_id_departamento').val('');
        $('#fk_id_sucursal').empty();
        $('#loadingsucursales').show();
        $.ajax({
            url: $('#fk_id_sucursal').data('url'),
            data: {
                'param_js': sucursales_js,
                $usuario: $('#fk_id_solicitante').val()
            },
            dataType: "json",
            success: function (response) {
                var options = [];
                if(response.length > 0){
                    options.push('<option value="0" selected disabled>Selecciona la Sucursal...</option>'); 
                    for (let i = 0; i < response.length; i++) {
                        options.push('<option value="' + response[i].id_sucursal + '">' + response[i].sucursal + '</option>');
                    }
                    $('#fk_id_sucursal').append(options.join(''));
                    $('#fk_id_sucursal').select2({
                        placeholder: "Seleccione la sucursal...",
                        disabled: false,
                    });
                } else{
                    $('#fk_id_sucursal').select2({
                        placeholder: "Seleccione otro Solicitante...",
                        disabled: true,
                    });
                    $.toaster({priority : 'warning',title : '¡Lo sentimos!',message : 'Al parecer el usuario no cuenta con sucursales registrados',
                    settings:{'timeout':3000,'toaster':{'css':{'top':'5em'}}}});
                }
                $('#loadingsucursales').hide();
            },
            error:function(){
                $.toaster({priority : 'danger',title : '¡Lo sentimos!',message : 'Al parecer no recibimos respuesta, trata de nuevo con otra opción.',
                settings:{'timeout':3000,'toaster':{'css':{'top':'5em'}}}});
                $('#loadingsucursales').hide();
                $('#fk_id_sucursal.select2').select2({
                    placeholder: "Seleccione otro Solicitante...",
                    disabled: true,
                });
            }
        }); // ajax-sucursales
        $.ajax({
            url: $('#fk_id_departamento').data('url'),
            data: {
                'param_js': usuarios_js,
                $usuario: $('#fk_id_solicitante').val()
            },
            dataType: "json",
            success: function (response) {
                if(response[0].empleado){
                    $('#fk_id_departamento').val(response[0].empleado.fk_id_departamento)
                } else{
                    $('#fk_id_departamento').val(0)
                }
            }
        }); // ajax-usuarios
    });

    $('#fk_id_sku').on('change',function () {
        $('#whaitplease').show();
        if($('#fk_id_sku').val() ) {
            $('#fk_id_proveedor').empty();
            $('#fk_id_upc').empty();
            $('#loadingUPC').show();
            $('#loadingproveedor').show();
            codigosbarras();//Carga los nuevos datos del producto

            $.ajax({
                 url: $('#fk_id_proveedor').data('url'),
                 data: {
                     'param_js': proveedores_js,
                     $id_sku: $('#fk_id_sku').val()
                 },
                 dataType: 'JSON',

                 success: function (data) {
                    var options = [];
                    /* Si hay resultados */
                    if (data.length > 0) {
                        options.push('<option value="0" selected disabled>Seleccione el Proveedor...</option>'); 
                        for (var i = 0; i < data.length; i++) {
                            options.push('<option value="' + data[i].id + '">' + data[i].text + '</option>');
                        };
                        $('#fk_id_proveedor').select2({
                            disabled: false,
                        });
                        $('#fk_id_proveedor').append(options.join(''));
                    } else{
                        $.toaster({priority : 'warning',title : '¡Lo sentimos!',message : 'Al parecer no hay Proveedores con este SKU, intente con otro',
                        settings:{'timeout':3000,'toaster':{'css':{'top':'5em'}}}});
                        $('#fk_id_proveedor').select2({
                            placeholder: "Proveedor no encontrado",
                            disabled: true,
                        });
                    }
                    $('#loadingproveedor').hide();
                    $('#whaitplease').hide();
                },
                error: function () {
                    $('#loadingproveedor').hide();
                    $('#fk_id_proveedor').select2({
                        placeholder: "Proveedor no encontrado",
                        disabled: true
                    });
                }
             });
         }
    });

    $('.imprimir').on('click',function (e) {
        if($('#productos > tbody') < 1 && $('.imprimir').length){
            e.preventDefault();
            $.toaster({priority : 'danger',title : '¡Espera!',message : 'Al parecer no cuentas con Productos para imprimir, requieres de mínimo un producto',
            settings:{'timeout':3000,'toaster':{'css':{'top':'5em'}}}});
        }
    });

    $(document).on('keypress','.cantidad',function (e) {
        // this.value = (this.value + '').replace(/^\d{1,9}/g, '');
        let valid = /^\d{1,9}$/g.test(this.value+e.key),
            val = this.value;
        if(!valid){
            return false;
        }
    });

    $(document).on('keypress','.precio_unitario',function (e) {
        // this.value = (this.value + '').replace(/^\d{0,6}(\.\d{0,2})?/g , '');
        let valid = /^\d{0,6}(\.\d{0,3})?$/g.test(this.value+e.key),
            val = this.value;
        if(!valid){
            return false;
        }
    });

}); //documentOnReady

function codigosbarras(){
    if($('#fk_id_sku').val() != 0) {
        let data_codigo = $('#fk_id_upc').data('url');
        var _url = data_codigo.replace('?id', $('#fk_id_sku').val());
        $.ajax({
            url: _url,
            dataType: 'json',
            success: function (data) {
                $('#fk_id_proveedor').empty();
                var options = [];
                /* Si hay resultados */
                if (data.length > 0) {
                    options.push('<option value="0" selected disabled>Seleccione el UPC...</option>'); 
                    for (var i = 0; i < data.length; i++) {
                        options.push('<option value="' + data[i].id + '">' + data[i].text + '</option>');
                    };
                    $('#fk_id_upc').select2({
                        disabled: false,
                    });
                    $('#fk_id_upc').append(options.join(''));
                } else{
                    $.toaster({priority : 'warning',title : '¡Lo sentimos!',message : 'Al parecer no hay UPCs en el SKU seleccionado, intente con otro',
                    settings:{'timeout':3000,'toaster':{'css':{'top':'5em'}}}});
                    $('#fk_id_upc').select2({
                        placeholder: "UPC no encontrado",
                        disabled: true,
                    });
                }
                $('#loadingUPC').hide();
            },
            error: function () {
                $('#loadingUPC').hide();
                $('#fk_id_upc').select2({
                    placeholder: "UPC no encontrado",
                    disabled: true
                });
            }
        });
    }
}

$('#fk_id_impuesto').on('change', function() {
    $('#agregar').prop('disabled',true);
    $('#loadingprecio').show();
    var idimpuesto = $('#fk_id_impuesto option:selected').val();
    var _url = $(this).data('url');
    $.ajax({
        async: true,
        url: _url,
        data: {'param_js':porcentaje_js,$id_impuesto:idimpuesto},
        dataType: 'json',
            success: function (data) {
              if(data[0].tasa_o_cuota == null)
                $('#impuesto').val('');
              else
                $('#impuesto').val(data[0].tasa_o_cuota);

            total_producto()
            $('#loadingprecio').hide();
            $('#agregar').prop('disabled',false);
        }
    });
});

function getImpuestos(el){
    $('.progress-button').prop('disabled',true);
    var loading = $(el).parent().parent().find('.loadingData');
    $(loading).show();
    var idimpuesto = $(el).val();
    var _url = $('#fk_id_impuesto').data('url');
    $.ajax({
        async: true,
        url: _url,
        data: {'param_js':porcentaje_js,$id_impuesto:idimpuesto},
        dataType: 'json',
            success: function (data) {
              if(data[0].tasa_o_cuota == null)
                $(el).parent().find('.rowImpuestos').val(0);
              else
                $(el).parent().find('.rowImpuestos').val(data[0].tasa_o_cuota);

            total_producto_row(el)
            $(loading).hide();
            $('.progress-button').prop('disabled',false);
        }
    }); 
}

$('#cantidad').on('keyup', function () {
    total_producto()
});

$('#precio_unitario').on('keyup', function () {
    total_producto()
});

function total_producto() {
    let impuesto = $('#impuesto').val();
    let precio_unitario = $('#precio_unitario').val();
    let cantidad = $('#cantidad').val();
    let subtotal = (cantidad*precio_unitario)*impuesto;
    //Calcular valores
    return (subtotal+cantidad*precio_unitario).toFixed(2);
}

function total_producto_row(el) {
    console.log(el);
    let impuesto = $(el).parent().parent().find('.rowImpuestos').val();
    let precio_unitario = $(el).parent().parent().find('.rowPrecioUnitario').val();
    let cantidad = $(el).parent().parent().find('.rowCantidad').val();
    let total = $(el).parent().parent().find('.rowTotal');
    let subtotal = (cantidad*precio_unitario)*impuesto;
    //Calcular valores
    return $(total).val((subtotal+cantidad*precio_unitario).toFixed(2));
}

function agregarProducto() {
    var tableData = $('#productos > tbody');
    validateDetail();
    if($('#form-model').valid()){
        //Para obtener las opciones del select de proyectos en el detalle de la solicitud
        let proyectos = '';
        $.each($('#fk_id_proyecto option').clone(), function (key, proyecto) {
            //Este if es para verificar la opción seleccionada por defecto
            if(proyecto.value == $('#fk_id_proyecto').val())
                {proyectos += '<option value="'+proyecto.value+'" selected>'+proyecto.innerText+'</option>';}
            else
                {proyectos += proyecto.outerHTML;}
        });
        //Para obtener las opciones del select de impuestos en el detalle de la solicitud
        let impuestos = '';
        $.each($('#fk_id_impuesto option').clone(), function (key, impuesto) {
            //Este if es para verificar la opción seleccionada por defecto
            if(impuesto.value == $('#fk_id_impuesto').val() && impuesto.value != -1)
                {
                    impuestos += '<option value="'+impuesto.value+'" selected>'+impuesto.innerText+'</option>';
                }
            else if(impuesto.value != -1)
                {impuestos += impuesto.outerHTML;}
        });
        let total = total_producto();
        let id_upc = 0;
        let text_upc = 'UPC no seleccionado';
        let proveedor =  $('#fk_id_proveedor').select2('data')[0].text.replace(/,/g ,"");
        if ($('#fk_id_upc').val()) {
            id_upc = $('#fk_id_upc').val();
            text_upc = $('#fk_id_upc').select2('data')[0].text;
        }

        var i = $('#productos > tbody > tr').length;
        var row_id = i > 0 ? +$('#productos > tbody > tr:last').find('#index').val()+1 : 0;
        tableData.append(
        '<tr><th>' + 'N/A' +
            '<input type="hidden" id="index" value="'+row_id+'">'+
            '<input type="hidden" name="relations[has][detalle]['+row_id+'][fk_id_documento_base]" value=""/>'+
            '<input type="hidden" name="relations[has][detalle]['+row_id+'][fk_id_sku]" value="'+ $('#fk_id_sku').val() +'"/>'+
            '<input type="hidden" name="relations[has][detalle]['+row_id+'][fk_id_upc]" value="'+ id_upc +'"/>'+
            '<input type="hidden" name="relations[has][detalle]['+row_id+'][fk_id_unidad_medida]" value="' + $('#fk_id_unidad_medida').val() + '" />'+
            '<input type="hidden" name="relations[has][detalle]['+row_id+'][fk_id_proveedor]" value="'+ $('#fk_id_proveedor').val() +'"/>'+
            '<input type="hidden" name="relations[has][detalle]['+row_id+'][fecha_necesario]" value="'+ $('#fecha_necesario').val() +'"/></th>' +
        '<td>' + '<img style="max-height:40px" src="img/sku.png" alt="sku"/> ' + $('#fk_id_sku').select2('data')[0].text + '</td>' +
        '<td>' + '<img style="max-height:40px" src="img/upc.png" alt="upc"/> ' + text_upc + '</td>' +
        '<td>' + proveedor + '</td>' +
        '<td>' + '<i class="material-icons align-middle">today</i>' + $('#fecha_necesario').val() + '</td>' +
        '<td>' + '<select name="relations[has][detalle]['+row_id+'][fk_id_proyecto]" id="fk_id_proyecto'+row_id+'" style="width: 100%" class="select form-control">'+proyectos+'</select>' + '</td>' +
        '<td>' + '<input type="number" name="relations[has][detalle]['+row_id+'][cantidad]" onkeyup="total_producto_row(this)" value="'+ $('#cantidad').val()+'" class="validate rowCantidad form-control" />' + '</td>' +
        '<td>' + $('#fk_id_unidad_medida option:selected').html() + '</td>' +
        '<td>' + '<select name="relations[has][detalle]['+row_id+'][fk_id_impuesto]" onchange="getImpuestos(this)" style="width: 100%" class="select form-control">'+impuestos+'</select>' +
            '<input type="hidden" class="rowImpuestos" value="'+ $('#impuesto').val() +'"/>' + '</td>' +
        '<td>' + '<input type="number" name="relations[has][detalle]['+row_id+'][precio_unitario]"  onkeyup="total_producto_row(this)" value="'+ $('#precio_unitario').val()+'" class="rowPrecioUnitario form-control"/>' + '</td>' +
        '<td class="position-relative">'+ '<div class="w-100 h-100 text-center text-white align-middle loadingData" style="display: none">Calculando el total... <i class="material-icons align-middle loading">cached</i></div>'+
                '<input type="text" name="relations[has][detalle]['+row_id+'][importe]" class="form-control rowTotal" style="min-width: 100px" readonly value="'+ total +'" />' + '</td>' +
        '<td>'+ '<button data-toggle="Eliminar" data-placement="top" title="Eliminar" data-original-title="Eliminar" type="button" class="text-primary btn btn_tables is-icon eliminar" style="background:none;" data-delay="50" onclick="borrarFila(this)"><i class="material-icons">delete</i></button>'+'</td></tr>'
        );
        $('[data-toggle]').tooltip();
            $.toaster({priority : 'success',title : '¡Éxito!',message : 'Producto agregado con éxito',
            settings:{'timeout':2000,'toaster':{'css':{'top':'5em'}}}
        });
        limpiarFormulario();//Limpiar el formulario de algunos de los valores
    }else {
        $.toaster({priority : 'danger',title : '¡Error!',message : 'Hay campos que requieren de tu atención',
            settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
    }
}

function limpiarFormulario() {
    // $('#fk_id_proveedor').val('');
    $("#fk_id_sku").val(0);
    $("#fk_id_sku").select2();
    $('#fk_id_proveedor').empty();
    $('#fk_id_proveedor').select2({
        placeholder: "Seleccione el SKU",
        disabled: true,
    });
    $('#fk_id_upc').empty();
    $('#fk_id_upc').select2({
        placeholder: "Seleccione el SKU",
        disabled: true,
    });
    $("#fk_id_proyecto").val("");
    $("#fk_id_proyecto").select2();
    $("#fk_id_unidad_medida").val("");
    $("#fk_id_unidad_medida").select2();
    $('#fk_id_impuesto').val("");
    $('#impuesto').val(0);
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
    var tr = $(el).closest('tr');
    tr.fadeOut(400, function(){
        tr.remove().stop();
    })
    $.toaster({priority : 'success',title : '¡Advertencia!',message : 'Se ha eliminado la fila correctamente',
        settings:{'timeout':2000,'toaster':{'css':{'top':'5em'}}}});
}

function validateDetail() {
    $('#fk_id_sku').rules('add',{
        required: true,
        messages:{
            required: 'Selecciona un SKU'
        }
    });
    $('#fk_id_upc').rules('add',{
        required: true,
        messages:{
            required: 'Selecciona un SKU'
        }
    });
    $('#fk_id_proveedor').rules('add',{
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