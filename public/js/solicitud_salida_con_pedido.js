$(document).ready(function () {
    var dataOld = 0;

    $('#fk_id_pedido').on('change',function(e){
        if($('#tableBodySolicitudes > tr').length == 0){

            $('#tableBodySolicitudes > tr').remove();
            var iddocument = $('#fk_id_pedido option:selected').val();
            var _url = $('#fk_id_pedido').data('url');
            $.ajax({
                url: _url,
                data: {'param_js': api_pedido ,$id_documento:iddocument},
                dataType: "json",
                success: function (data) {
                    var $tableBody = $('#tableBodySolicitudes')
                    
                    for (var index = 0; index < data[0].detalle.length; index++) {
                        var i = $('#tableBodySolicitudes > tr').length;
                        var row_id = i > 0 ? +$('#tableBodySolicitudes > tr:last').find('#index').val()+1 : 0;
                        // console.log(data[0].detalle[index]);
                        $tableBody.append(
                            '<tr><th>'+ row_id +
                                '<input type="hidden" id="index" value="'+row_id+'">'+
                                '<input type="hidden" name="relations[has][detalle]['+row_id+'][fk_id_sku]" value="'+ data[0].detalle[index].fk_id_sku +'"/>'+
                                '<input type="hidden" name="relations[has][detalle]['+row_id+'][fk_id_upc]" value="'+ data[0].detalle[index].fk_id_upc +'"/>'+
                                '<input class="falta_surtir" type="hidden" name="relations[has][detalle]['+row_id+'][falta_surtir]" value="0"/>'+
                                '<input type="hidden" name="relations[has][detalle]['+row_id+'][fk_id_pedido]" value="'+ data[0].detalle[index].id_documento_detalle +'"/>'+
                                '<input class="almacen_val" type="hidden" name="relations[has][detalle]['+row_id+'][fk_id_almacen]" value="'+ $('#fk_id_almacen').val() +'"/>'+
                                '</th>' +
                            '<td>'+ data[0].detalle[index].fk_id_sku + '</td>' +
                            '<td>'+ data[0].detalle[index].fk_id_upc + '</td>' +
                            '<td>'+ data[0].detalle[index].fk_id_upc + '</td>' +
                            '<td>'+ data[0].detalle[index].fk_id_upc + '</td>' +
                            '<td>'+ '<input style="min-width:60px" type="number" class="form-control cantidad_total" onkeyup="validateCantidadTotal(this),insertarValorAFalta(this)" onclick="validateCantidadTotal(this),insertarValorAFalta(this)" name="relations[has][detalle]['+row_id+'][cantidad]" value="0"/>' + '</td>' +
                            '<td>'+ $('#almacenistas').html().replace('$row_id',row_id).replace('$row_id',row_id) +'</td>'+
                            '<td>'+ '<input style="min-width:60px" type="number" class="form-control cantidad_solicitada" onkeyup="validateCantidadSolicitada(this)" onclick="validateCantidadSolicitada(this)" name="relations[has][detalle]['+row_id+'][cantidad_solicitada_salida]" value="0"/>' + '</td>' +
                            '<td>'+ '<button data-toggle="Eliminar" data-placement="top" title="Eliminar" data-original-title="Eliminar" type="button" class="text-primary btn btn_tables is-icon eliminar" style="background:none;" data-delay="50" onclick="borrarFila(this)"><i class="material-icons">delete</i></button>'+'</td></tr>'
                        );
                    };
                    
                },
                error: function(){
                    $.toaster({priority : 'danger',title : '¡Lo sentimos!',message : 'Selecciona un <b>pedido diferente</b>, ya que el seleccionado no cuenta con producto activo.',
                    settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
                },
            });
        } else{
            $('#confirmacionpedido').modal('show');
        }
            
    });

    $("#fk_id_pedido").on('select2:selecting',function() {
        dataOld = $('#fk_id_pedido').val();
    });

    //confirmación para modal de pedido
    $('#confirmarpedido').click(function () {
        $('#confirmacionpedido').modal('hide');
        $('#tableBodySolicitudes > tr').remove();
        $.toaster({priority : 'success',title : '¡Advertencia!',message : 'Se han eliminado las filas correctamente',
        settings:{'timeout':2000,'toaster':{'css':{'top':'5em'}}}});
        var iddocument = $('#fk_id_pedido option:selected').val();
        var _url = $('#fk_id_pedido').data('url');

        if(iddocument > 0){
            $.ajax({
                url: _url,
                data: {'param_js': api_pedido ,$id_documento:iddocument},
                dataType: "json",
                success: function (data) {
                    var $tableBody = $('#tableBodySolicitudes')
                    
                    for (var index = 0; index < data[0].detalle.length; index++) {
                        var i = $('#tableBodySolicitudes > tr').length;
                        var row_id = i > 0 ? +$('#tableBodySolicitudes > tr:last').find('#index').val()+1 : 0;
                        // console.log(data[0].detalle[index]);
                        $tableBody.append(
                            '<tr><th>'+ row_id +
                                '<input type="hidden" id="index" value="'+row_id+'">'+
                                '<input type="hidden" name="relations[has][detalle]['+row_id+'][fk_id_sku]" value="'+ data[0].detalle[index].fk_id_sku +'"/>'+
                                '<input type="hidden" name="relations[has][detalle]['+row_id+'][fk_id_upc]" value="'+ data[0].detalle[index].fk_id_upc +'"/>'+
                                '<input class="falta_surtir" type="hidden" name="relations[has][detalle]['+row_id+'][falta_surtir]" value="0"/>'+
                                '<input type="hidden" name="relations[has][detalle]['+row_id+'][fk_id_pedido]" value="'+ data[0].detalle[index].id_documento_detalle +'"/>'+
                                '<input class="almacen_val" type="hidden" name="relations[has][detalle]['+row_id+'][fk_id_almacen]" value="'+ $('#fk_id_almacen').val() +'"/>'+
                                '</th>' +
                            '<td>'+ data[0].detalle[index].fk_id_sku + '</td>' +
                            '<td>'+ data[0].detalle[index].fk_id_upc + '</td>' +
                            '<td>'+ data[0].detalle[index].fk_id_upc + '</td>' +
                            '<td>'+ data[0].detalle[index].fk_id_upc + '</td>' +
                            '<td>'+ '<input style="min-width:60px" type="number" class="form-control cantidad_total" onkeyup="validateCantidadTotal(this),insertarValorAFalta(this)" onclick="validateCantidadTotal(this),insertarValorAFalta(this)"  name="relations[has][detalle]['+row_id+'][cantidad]" value="0"/>' + '</td>' +
                            '<td>'+ $('#almacenistas').html().replace('$row_id',row_id).replace('$row_id',row_id) +'</td>'+
                            '<td>'+ '<input style="min-width:60px" type="number" class="form-control cantidad_solicitada" onkeyup="validateCantidadSolicitada(this)" onclick="validateCantidadSolicitada(this)" name="relations[has][detalle]['+row_id+'][cantidad_solicitada_salida]" value="0"/>' + '</td>' +
                            '<td>'+ '<button data-toggle="Eliminar" data-placement="top" title="Eliminar" data-original-title="Eliminar" type="button" class="text-primary btn btn_tables is-icon eliminar" style="background:none;" data-delay="50" onclick="borrarFila(this)"><i class="material-icons">delete</i></button>'+'</td></tr>'
                        );
                    };
                },
                error: function(){
                    $.toaster({priority : 'danger',title : '¡Lo sentimos!',message : 'Selecciona un <b>pedido diferente</b>, ya que el seleccionado no cuenta con producto activo.',
                    settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
                },
            });
        }

    });

    //En caso de cambiar almacen cambiamos los valores
    $('#fk_id_almacen').on('change',function(){
        var val = $('#fk_id_almacen').val()
        $('#tableBodySolicitudes tr').each(function (index, element) {
            // element == tr
            $(element).find('.almacen_val').val(val)
        });
    })

    //Cancelación modal pedido
    $('#cancelarcambiopedido').click(function () {
        $('#fk_id_pedido').val(dataOld).trigger('change');
    });

    //Validamos en caso de que seleccione otra opción y tenga datos
    $('#pills-tab').on('click',function(e){
        // console.log(e.target)
        $(e.target).on('show.bs.tab', function (event) {
            //Validamos en caso de que tenga valores la tabla...
            if($('#tableBodySolicitudes > tr').length > 0 || $('#tableBodyProductos > tr').length > 0){
                //Detenemos el evento para que el usuario decida...
                event.preventDefault()
                $('#modalTabs').show();
                $('#confirmarTab').click(function () {
                    $('#tableBodySolicitudes > tr').remove();
                    $('#tableBodyProductos > tr').remove();
                    $('#modalTabs').hide();
                    $(event.target).tab('show')
                    $.toaster({priority : 'success',title : '¡Advertencia!',message : 'Se han eliminado las filas correctamente',
                    settings:{'timeout':2000,'toaster':{'css':{'top':'5em'}}}});
                });
                $('#cancelarTab').click(function () {
                    event.preventDefault()
                    $('#modalTabs').hide();
                });
            }
        });
    });
    
}); //document

function insertarValorAFalta(el){
    var thisVal = +$(el).val();
    var $faltaSurtir = $(el).parent().parent().find('.falta_surtir');
    $faltaSurtir.val(thisVal);
}

//FUNCIÓN PARA VALIDAR CANTIDAD SOLICITADA
function validateCantidadSolicitada(el){
    var thisVal = +$(el).val()
    var totalVal = +$(el).parent().parent().find('.cantidad_total').val()
    if(thisVal > totalVal){
        $(el).removeClass('border-success').addClass('border-danger');
    } else{
        $(el).removeClass('border-danger').addClass('border-success');
    }
}

//FUNCIÓN PARA VALIDAR TOTAL
function validateCantidadTotal(el){
    var thisVal = +$(el).val()
    var totalVal = +$(el).parent().parent().find('.cantidad_solicitada').val()
    if(thisVal < totalVal){
        $(el).removeClass('border-success').addClass('border-danger');
    } else{
        $(el).removeClass('border-danger').addClass('border-success');
    }
}

//FUNCIÓN PARA VALIDAR ON SUBMIT LA CANTIDAD
function validateCantidad(){
    var verificar = false;
    $('#tableSolicitudes').each(function (index, row) {
        var newCantidad =  +$(row).find('.cantidad_solicitada').val();
        var totalCantidad =  +$(row).find('.cantidad_total').val();
         if(newCantidad > totalCantidad){
            verificar = true;
            return false;
        }
    });
    if(verificar == true){
        return false;
    } else {
        return true;
    }
}

//FUNCIÓN PARA LIMPIAR LA FILA
function borrarFila(el) {
    var tr = $(el).closest('tr');
    tr.fadeOut(400, function(){
        tr.remove().stop();
    })
    $.toaster({priority : 'success',title : '¡Advertencia!',message : 'Se ha eliminado la fila correctamente',
    settings:{'timeout':2000,'toaster':{'css':{'top':'5em'}}}});
};
