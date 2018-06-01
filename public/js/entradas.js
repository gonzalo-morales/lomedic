
$("#fk_id_tipo_documento").val('').change();

$('#fk_id_tipo_documento').on('change', function() {

    let token = document.querySelector("meta[name='csrf-token']").getAttribute("content");
    $.ajax({
        url: $('#fk_id_tipo_documento').data('url'),
        method:'POST',
        data: {'fk_id_tipo_documento':$('#fk_id_tipo_documento').val(),'_token':token},
        dataType: "json",
        success:function(data){

            $('#numero_documento').empty();
            $('#numero_documento').append('<option value="">Seleccionar una opcion</option>');
            $.each(data, function(index,value) {
                $('#numero_documento').append('<option value="'+ value +'">'+ value +'</option>');
            });
            $('#numero_documento').val('');

        }
    });

});

$('#numero_documento').on('change', function() {

    let token = document.querySelector("meta[name='csrf-token']").getAttribute("content");
    $.ajax({
        url: $('#numero_documento').data('url'),
        method:'POST',
        data: {'fk_id_tipo_documento':$('#fk_id_tipo_documento').val(),
            '_token':token,
            'numero_documento':$('#numero_documento').val(),
        },
        dataType: "json",
        success:function(data){

            $.each(data.upcs, function(index,value) {

                $('#upcs').append('<option value="'+ value +'">'+ value +'</option>');

            });

            $.each(data.detalle, function(index,value) {

                $('#nombre_sucursal').val(data.sucursal);
                $('#nombre_proveedor').val(data.proveedor);

                if( value.cantidad > value.cantidad_surtida)
                {
                    $('#table_detalle').append(
                        '<tr class="upc_'+ value.upc +'" bgcolor="#fff9c4">' +
                        '<td class="codigo_upc">'+ value.upc +'</td>' +
                        '<td>'+ value.descripcion +'</td>' +
                        '<td>'+ value.cliente +'</td>' +
                        '<td>'+ value.proyecto +'</td>' +
                        '<td class="cantidad_entrada">'+ value.cantidad +'</td>' +
                        '<td class="cantidad_surtida">'+ value.cantidad_surtida +'</td>' +
                        '<td>'+ value.precio_unitario +'</td>' +
                        '<td>'+ value.total +'</td>' +
                        '<td><button type="button" class="btn btn-primary  material-icons " onclick="mostrar_detalle(\''+value.upc+'\');" style="padding-bottom: 0rem;padding-top: 0px;">zoom_in</button></td>' +
                        '<input type="hidden" class="id_upc" value="'+value.fk_id_upc+'">'+
                        '<input type="hidden" class="fk_id_proyecto" value="'+value.fk_id_proyecto+'" >'+
                        '<input type="hidden" class="costo_unitario" value="'+value.precio_unitario+'" >'+
                        '<input type="hidden" class="fk_id_linea" value="'+value.fk_id_linea+'" >'+
                        '</tr>'+
                        '<tr class="detalle_'+ value.upc +'" style="display: none">'+
                        '<td colspan="9" >'+
                        '<div class="row px-5">' +
                        '   <table class="col-md-12"> ' +
                        '       <thead>' +
                        '           <tr> ' +
                        '               <th>Lote</th> ' +
                        '               <th>F. Caducidad</th> ' +
                        '               <th>Capturado</th> ' +
                        '               <th></th> ' +
                        '           </tr> ' +
                        '       </thead> ' +
                        '       <tbody class="detalle_upc_'+ value.upc +'"> </tbody> ' +
                        '   </table>' +
                        '</div>'+
                        '</td>'+
                        '</tr>'

                    );
                }

            });
        }
    });

});

function mostrar_detalle(upc){

    $('#upcs option').each(function () {
        $('.detalle_'+ $(this).val()).hide();
    });
    $('.detalle_'+upc).show();
}

function activar_boton_agregar(activo){

    if(activo){
        $('#agrgar_upc').prop("disabled", false);
    }else{
        $('#agrgar_upc').prop("disabled", true);
    }

}

var cont_row = 0;
function agregar_info_upc(tipo_captura){

    let lote = '';
    let fecha_caducidad = '';
    let upcs = '';
    let cantidad = 0;
    let fk_id_tipo_documento = $('#fk_id_tipo_documento').val();
    let numero_documento = $('#numero_documento').val();

    if( tipo_captura == 1 )
    {
        lote = $('#lote_cb').val();
        fecha_caducidad = $('#fecha_caducidad_cb').val();
        upcs = $('#upcs_cb').val();
        cantidad = 1;
        $('#upcs_cb').val('');

    }
    else if( tipo_captura == 2 )
    {
        lote = $('#lote').val();
        fecha_caducidad = $('#fecha_caducidad').val();
        upcs = $('#upcs').val();
        cantidad = parseInt($('#cantidad').val());
    }
    let cantidad_entrada = parseInt($( '.upc_'+upcs ).find('.cantidad_entrada').html());
    let id_upc = $( '.upc_'+upcs ).find('.id_upc').val();
    let fk_id_proyecto = $( '.upc_'+upcs ).find('.fk_id_proyecto').val();
    let costo_unitario = $( '.upc_'+upcs ).find('.costo_unitario').val();
    let fk_id_linea = $( '.upc_'+upcs ).find('.fk_id_linea').val();
    if(validar_cantidad(upcs,tipo_captura)){

        if($( '.detalle_upc_'+upcs+' tr' ).length <= 0)
        {

            $('.detalle_upc_'+upcs).append(
                '<tr class="detalle">'+
                    '<td class="lote" >'+ lote +'</td>' +
                    '<td class="fecha_caducidad" >'+ fecha_caducidad +'</td>' +
                    '<td class="cantidad">'+ cantidad +'</td>'+
                    '<td><a class="material-icons" onclick="eliminar_detalle_upc(this,\''+upcs +'\')">mode_clear</a></td>'+
                    '<input type="hidden" name="relations[has][detalles]['+cont_row+'][id_documento_detalle]"  value=""/> ' +
                    '<input type="hidden" name="relations[has][detalles]['+cont_row+'][fk_id_docuemto]"  value="'+numero_documento+'"/> ' +
                    '<input type="hidden" name="relations[has][detalles]['+cont_row+'][fk_id_upc]"  value="'+id_upc+'"/> ' +
                    '<input type="hidden" name="relations[has][detalles]['+cont_row+'][cantidad_surtida]" class="detalle_cantidad" value="'+cantidad+'">'+
                    '<input type="hidden" name="relations[has][detalles]['+cont_row+'][fecha_caducidad]" value="'+fecha_caducidad+'">'+
                    '<input type="hidden" name="relations[has][detalles]['+cont_row+'][lote]" value="'+lote+'">'+
                    '<input type="hidden" name="relations[has][detalles]['+cont_row+'][fk_id_linea]" value="'+fk_id_linea+'">'+
                    '<input type="hidden" name="relations[has][detalles]['+cont_row+'][fk_id_tipo_documento_base]" value="'+fk_id_tipo_documento+'">'+
                    '<input type="hidden" name="relations[has][detalles]['+cont_row+'][fk_id_tipo_documento]" value="21">'+
                    '<input type="hidden" name="relations[has][detalles]['+cont_row+'][costo_unitario]" value="'+costo_unitario+'">'+
                    '<input type="hidden" name="relations[has][detalles]['+cont_row+'][fk_id_proyecto]" value="'+fk_id_proyecto+'">'+
                '</tr>'
            );
            cont_row++;
        }
        else
        {
            let lote_existente = true;

            $.each( $( '.detalle_upc_'+upcs+' tr' ),function(){
                if( lote == $(this).find('.lote').html() )
                {
                    let cantidad_ingresada = parseInt($(this).find('.cantidad').html());
                    $(this).find('.cantidad').html( cantidad_ingresada + cantidad );
                    $(this).find('.detalle_cantidad').val( cantidad_ingresada + cantidad );
                    lote_existente = false;
                    return false;
                }
            });

            if(lote_existente)
            {
                $('.detalle_upc_'+upcs).append(
                    '<tr class="detalle">'+
                    '<td class="lote" >'+ lote +'</td>' +
                    '<td class="fecha_caducidad" >'+ fecha_caducidad +'</td>' +
                    '<td class="cantidad">'+ cantidad +'</td>'+
                    '<td><a class="material-icons" onclick="eliminar_detalle_upc(this,\''+upcs +'\')">mode_clear</a></td>'+
                    '<input type="hidden" name="relations[has][detalles]['+cont_row+'][id_documento_detalle]"  value=""/> ' +
                    '<input type="hidden" name="relations[has][detalles]['+cont_row+'][fk_id_docuemto]"  value="'+numero_documento+'"/> ' +
                    '<input type="hidden" name="relations[has][detalles]['+cont_row+'][fk_id_upc]"  value="'+id_upc+'"/> ' +
                    '<input type="hidden" name="relations[has][detalles]['+cont_row+'][cantidad_surtida]" class="detalle_cantidad" value="'+cantidad+'">'+
                    '<input type="hidden" name="relations[has][detalles]['+cont_row+'][fecha_caducidad]" value="'+fecha_caducidad+'">'+
                    '<input type="hidden" name="relations[has][detalles]['+cont_row+'][lote]" value="'+lote+'">'+
                    '<input type="hidden" name="relations[has][detalles]['+cont_row+'][fk_id_linea]" value="'+fk_id_linea+'">'+
                    '<input type="hidden" name="relations[has][detalles]['+cont_row+'][fk_id_tipo_documento_base]" value="'+fk_id_tipo_documento+'">'+
                    '<input type="hidden" name="relations[has][detalles]['+cont_row+'][fk_id_tipo_documento]" value="21">'+
                    '<input type="hidden" name="relations[has][detalles]['+cont_row+'][costo_unitario]" value="'+costo_unitario+'">'+
                    '<input type="hidden" name="relations[has][detalles]['+cont_row+'][fk_id_proyecto]" value="'+fk_id_proyecto+'">'+
                    '</tr>'
                );
                cont_row++;
            }

        }
        mostrar_detalle(upcs);
        estatus_upcs(upcs);
    }
}

function estatus_upcs(upcs){

    let cantidad_entrada = parseInt($( '.upc_'+upcs ).find('.cantidad_entrada').html());
    let cantidad_surtida = parseInt($( '.upc_'+upcs ).find('.cantidad_surtida').html());
    let cantidad_lote = 0;


    if($( '.detalle_upc_'+upcs+' tr' ).length > 0)
    {
        $.each( $( '.detalle_upc_'+upcs+' tr'),function(){
            cantidad_lote = cantidad_lote + parseInt($(this).find('.cantidad').html());
        });
    }

    if( (cantidad_lote + cantidad_surtida ) < 0 )
    {
        $('.upc_' + upcs).attr('bgcolor', '#ffccbc');
    }
    if( (cantidad_lote + cantidad_surtida ) == 0 )
    {
        $('.upc_' + upcs).attr('bgcolor', '#fff9c4');
    }
    if( (cantidad_lote + cantidad_surtida ) > cantidad_surtida )
    {
        $('.upc_' + upcs).attr('bgcolor', '#fff9c4');
    }
    if((cantidad_lote + cantidad_surtida ) == cantidad_entrada)
    {
        $('.upc_' + upcs).attr('bgcolor', '#b3e5fc');
    }

}

function validar_cantidad(upcs,tipo_captura){

    let correcto = true;

    let cantidad_entrada = parseInt($( '.upc_'+upcs ).find('.cantidad_entrada').html());
    let cantidad_surtida = parseInt($( '.upc_'+upcs ).find('.cantidad_surtida').html());
    let cantidad_lote = 0;

    if( tipo_captura == 1 )
    {
        cantidad = 1;
    }
    else if( tipo_captura == 2 )
    {
        cantidad = parseInt($('#cantidad').val());
    }



    if($( '.detalle_upc_'+upcs+' tr' ).length <= 0)
    {
        if( (cantidad + cantidad_surtida) > cantidad_entrada )
        {
            correcto = false;
        }
    }
    else
    {

        if( cantidad > (cantidad_entrada + cantidad_surtida) )
        {
            correcto = false;
        }
        else
        {
            $.each( $( '.detalle_upc_'+upcs+' tr'),function(){
                cantidad_lote = cantidad_lote + parseInt($(this).find('.cantidad').html());
            });
            cantidad_lote = cantidad_lote + cantidad;

            if( (cantidad_lote + cantidad_surtida) > cantidad_entrada )
            {
                correcto = false;
            }
        }
    }
    return correcto;

}

function eliminar_detalle_upc(row_upc,upcs){
    $(row_upc).parent().parent().remove();
    estatus_upcs(upcs);
}

$("#guardar").click(function(evt){

    if( $('#fk_id_tipo_documento').val != '')
    {
        if($('#numero_documento').val() != '')
        {
            if($('.detalle').length > 0)
            {
                $("#form-model").submit();
            }
            else
            {
                mensajeAlerta('Favor de ingresar un producto antes de guardar la entrada.','danger');
            }
        }
        else
        {
            mensajeAlerta('Favor de seleccionar un numero de documento.','danger');
        }
    }
    else
    {
        mensajeAlerta('Favor de seleccionar un tipo de documento.','danger');
    }


});


function mensajeAlerta(mensaje,tipo){

    var titulo = '';

    if(tipo == 'danger'){ titulo = '¡Error!'}
    else if(tipo == 'success'){titulo = '¡Correcto!' }
    $.toaster({priority:tipo,
            title: titulo,
            message:mensaje,
            settings:{'timeout':10000,
                    'toaster':{'css':{'top':'5em'}}}
            }
    );
}
