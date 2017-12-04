/**
 * Created by ihernandezt on 12/10/2017.
 */

$('#entrada_escaner').on('change', function() {

    var tipo_documento = $('#fk_id_tipo_documento').val();
    var numero_documento = $('#entrada_escaner').val();
    $('#entrada_escaner').val('');

        if(numero_documento) {
            let token = document.querySelector("meta[name='csrf-token']").getAttribute("content");
            $.ajax({
                url: $('#entrada_escaner').data('url'),
                method:'POST',
                data: {'numero_documento':numero_documento,'fk_id_tipo_documento':tipo_documento,'_token':token},
                dataType: "json",
                success:function(data){
                    console.info(data);
                    if(data != '')
                    {
                        $('#lista_entradas').append('<li class="nav-item">' +
                            '<a class="nav-link" href="#'+tipo_documento+'_'+numero_documento+'" role="tab" data-toggle="tab">'+numero_documento+'</a> ' +
                            '</li>');

                        var detalle_entrada = '';
                        var estado_producto = '';

                        $.each(data.detalle_documento, function(index) {


                            var sku = data.detalle_entrada[index].sku;
                            var id_sku = data.detalle_entrada[index].id_sku;
                            var sku_descripcion = data.detalle_entrada[index].sku_descripcion;
                            var upc = data.detalle_entrada[index].upc;
                            var id_upc = data.detalle_entrada[index].id_upc;
                            var nombre_cliente = data.detalle_entrada[index].fk_id_cliente;
                            var nombre_proyecto = data.detalle_entrada[index].nombre_proyecto;
                            var cantidad_surtida = data.detalle_entrada[index].cantidad_surtida;
                            var cantidad = data.detalle_entrada[index].cantidad;
                            var lote = data.detalle_entrada[index].lote;
                            var fecha_caducidad = data.detalle_entrada[index].fecha_caducidad;
                            var id_detalle_documento = data.detalle_entrada[index].id_detalle;

                            if(sku === undefined || sku === null ){sku='-';}
                            if(sku_descripcion === undefined || sku_descripcion === null){sku_descripcion='-';}
                            if(upc === undefined || upc === null ){upc='-';}
                            if(nombre_cliente === undefined || nombre_cliente === null ){nombre_cliente='-';}
                            if(nombre_proyecto === undefined || nombre_proyecto === null){nombre_proyecto='-';}
                            if(lote === undefined || lote === null){lote = '';}
                            if(fecha_caducidad === undefined || fecha_caducidad === null){fecha_caducidad = '';}
                            if(cantidad_surtida === undefined || cantidad_surtida  === null){cantidad_surtida  = 0;}

                            if( cantidad_surtida < cantidad)
                            {
                                estado_producto = '<input type="text" id="'+tipo_documento+'_'+numero_documento+'_'+id_detalle_documento+'_ingresar"  name="datos_entradas['+index+'][ingresar]" value="0" style="max-width:6em;" disabled>';

                                detalle_entrada = detalle_entrada + '<tr>' +
                                    '<td>'+sku+'</td>' +
                                    '<td>'+upc+'</td>' +
                                    '<td>'+sku_descripcion .substr(0, 150)+'</td>' +
                                    '<td>'+nombre_cliente +'</td>' +
                                    '<td>'+nombre_proyecto+'</td>' +
                                    '<td><input type="text" id="'+tipo_documento+'_'+numero_documento+'_'+id_detalle_documento+'_lote" name="datos_entradas['+index+'][lote]" value="'+lote+'" style="max-width:6em;" disabled></td>' +
                                    '<td><input type="text" id="'+tipo_documento+'_'+numero_documento+'_'+id_detalle_documento+'_caducidad" name="datos_entradas['+index+'][caducidad]" value="'+fecha_caducidad+'" style="max-width:6em;"disabled></td>' +
                                    '<td><input type="text" id="'+tipo_documento+'_'+numero_documento+'_'+id_detalle_documento+'_entrada" value="'+cantidad+'" style="max-width:6em;" disabled></td>' +
                                    '<td><input type="text" id="'+tipo_documento+'_'+numero_documento+'_'+id_detalle_documento+'_surtida" name="datos_entradas['+index+'][surtida]"  value="'+cantidad_surtida+'" style="max-width:6em;" disabled></td>' +
                                    '<td>'+estado_producto+'</td>' +
                                    '<input type="hidden" name="datos_entradas['+index+'][id_sku]" value="'+id_sku+'" >' +
                                    '<input type="hidden" name="datos_entradas['+index+'][id_upc]" value="'+id_upc+'" >' +
                                    '<input type="hidden" name="datos_entradas['+index+'][id_detalle_documento]" value="'+id_detalle_documento+'" >' +
                                    '</tr>';
                            }
                            else
                            {
                                return true;
                            }
                        });

                        $('#detalle_entrada').append('<div role="tabpanel" class="tab-pane fade in" id="'+tipo_documento+'_'+numero_documento+'">' +
                            '<div class="row"> ' +
                            '   <div class="col-sm-12"> ' +
                            '       <h3>Entrada: '+ numero_documento +'</h3> <input type="hidden" name="'+numero_documento+'">' +
                            '       <div class="card z-depth-1-half"> ' +
                            '           <div class="card-body"> ' +
                            '               <div class="row">' +
                            '                   <div class="col-md-6 col-sm-6 col-lg-3">' +
                            '                       <div class="form-group">' +
                            '                           <label for="sucursal_'+numero_documento+'">Sucursal</label>' +
                            '                           <input class="form-control" id="sucursal_'+tipo_documento+'_'+numero_documento+'" name="sucursal_'+tipo_documento+'_'+numero_documento+'" type="text" value="'+data.datos_documento.sucursales.sucursal+'" disabled>' +
                            '                       </div>' +
                            '                   </div> ' +
                            '                   <div class="col-md-6 col-sm-6 col-lg-3">' +
                            '                       <div class="form-group">' +
                            '                           <label for="negocios_'+tipo_documento+'_'+numero_documento+'">Proveedor</label>' +
                            '                           <input class="form-control" id="negocios_'+tipo_documento+'_'+numero_documento+'" name="negocios_'+tipo_documento+'_'+numero_documento+'" type="text" value="'+data.datos_documento.proveedor.nombre_comercial+'" disabled>' +
                            '                       </div>' +
                            '                   </div> ' +
                            '                   <div class="col-md-6 col-sm-6 col-lg-3">' +
                            '                       <div class="form-group">' +
                            '                           <label for="documento_'+tipo_documento+'_'+numero_documento+'">Documento</label>' +
                            '                           <input class="form-control" id="documento_'+tipo_documento+'_'+numero_documento+'" name="documento_'+tipo_documento+'_'+numero_documento+'" type="text">' +
                            '                       </div>' +
                            '                   </div>' +
                            '                   <div class="text-right d-flex ">' +
                            '                       <button type="button" class="btn btn-primary" id="guardar_entrada" onclick="guardarEntrada('+tipo_documento+','+numero_documento+')" data-url='+data.company_route+'>Guardar</button> ' +
                            '                   </div>' +
                            '                   <div class="col-12"> ' +
                            '                       <h3>Detalle de la entrada</h3> ' +
                            '                       <div class="row justify-content-center">' +
                            '                           <div class="col-md-6 col-sm-6 col-lg-3">' +
                            '                               <div class="form-group">' +
                            '                                   <label for="lote_'+tipo_documento+'_'+numero_documento+'">Lote</label>' +
                            '                                   <input class="form-control" id="lote_'+tipo_documento+'_'+numero_documento+'" name="lote_'+tipo_documento+'_'+numero_documento+'" type="text">' +
                            '                               </div>' +
                            '                           </div>' +
                            '                           <div class="col-md-6 col-sm-6 col-lg-3">' +
                            '                               <div class="form-group">' +
                            '                                   <label for="caducidad_'+tipo_documento+'_'+numero_documento+'">Fecha de caducidad</label>' +
                            '                                   <input class="datepicker form-control" id="caducidad_'+tipo_documento+'_'+numero_documento+'" name="caducidad_'+tipo_documento+'_'+numero_documento+'" type="text" placeholder="Selecciona una fecha">' +
                            '                               </div>' +
                            '                           </div>' +
                            '                           <div class="col-12 col-md-6 col-lg-4">' +
                            '                               <div class="form-group">' +
                            '                                   <label for="codigo_barras">Código del producto</label>' +
                            '                                   <input class="form-control" id="codigo_barras" name="codigo_barras_'+tipo_documento+'_'+numero_documento+'" type="text">' +
                            '                               </div>' +
                            '                           </div>' +
                            '                       </div>' +
                            '                       <form id="form_'+tipo_documento+'_'+numero_documento+'"><table class="table table-hover table-responsive" name="table2">' +
                            '                           <thead>' +
                            '                               <tr>' +
                            '                                   <th>Sku</th> ' +
                            '                                   <th>Upc</th> ' +
                            '                                   <th>Descripción</th> ' +
                            '                                   <th>Cliente</th>' +
                            '                                   <th>Proyecto</th>' +
                            '                                   <th>Lote</th>' +
                            '                                   <th>F. de caducidad</th>' +
                            '                                   <th>C. Entrada</th>' +
                            '                                   <th>C. Surtida</th>' +
                            '                                   <th>C. ingresar</th>' +
                            '                                   <th></th>' +
                            '                               </tr> ' +
                            '                           </thead> ' +
                            '                           <tbody class="detalle_entrada_'+tipo_documento+'_'+numero_documento+'">'+ detalle_entrada +'</tbody> ' +
                            '                       </table></form> ' +
                            '                   </div> ' +
                            '               </div> ' +
                            '           </div>' +
                            '       </div>' +
                            '   </div>' +
                            '</div></div>');

                        $('#caducidad_'+tipo_documento+'_'+numero_documento).pickadate({ selectMonths: true, // Creates a dropdown to control month
                            selectYears: 3, // Creates a dropdown of 3 years to control year
                            min: true,
                            format: 'yyyy-mm-dd'
                        });
                    }
                }
            });
        }
    });


$("#form-model").submit(function(){
    return false;
});

$(document).on('change','#codigo_barras',function (){

    var datos_documento = this.name;
    var codigo_barras = this.value;

    var detalle_entrada = datos_documento.replace('codigo_barras','detalle_entrada');
    var caducidad = datos_documento.replace('codigo_barras','caducidad');
    var lote = datos_documento.replace('codigo_barras','lote');

    $('.'+detalle_entrada).find('tr').each(function(index)
    {
        var surtida = $('.'+detalle_entrada).find('tr').eq(index).children('td').eq(9).children('input').val();
        if(surtida != undefined)
        {
            var sku = $('.'+detalle_entrada).find('tr').eq(index).children('td').eq(0).text();
            if( sku == codigo_barras )
            {
                var numero_lote = $('.'+detalle_entrada).find('tr').eq(index).children('td').eq(5).children('input').val();
                var entrada = parseInt($('.'+detalle_entrada).find('tr').eq(index).children('td').eq(7).children('input').val());
                var surtida = parseInt($('.'+detalle_entrada).find('tr').eq(index).children('td').eq(8).children('input').val());
                var escanda = parseInt($('.'+detalle_entrada).find('tr').eq(index).children('td').eq(9).children('input').val());

                if(entrada >= ((escanda + 1)  + surtida ))
                {
                    if( $('#'+lote).val() != '' && $('#'+caducidad).val() != '' && numero_lote == '')
                    {
                        $('.'+detalle_entrada).find('tr').eq(index).children('td').eq(5).children('input').val( $('#'+lote).val() );
                        $('.'+detalle_entrada).find('tr').eq(index).children('td').eq(6).children('input').val( $('#'+caducidad).val() );
                        $('.'+detalle_entrada).find('tr').eq(index).children('td').eq(9).children('input').val( escanda + 1 );
                        $('#codigo_barras').val('');
                        $('#'+lote).val('');
                        $('#'+caducidad).val('');
                        return false;
                    }
                    else if( $('#'+lote).val() == '' && $('#'+caducidad).val() == '' && numero_lote  != ''  )
                    {
                        $('.'+detalle_entrada).find('tr').eq(index).children('td').eq(9).children('input').val( escanda + 1 );
                        $('#codigo_barras').val('');
                        return false;
                    }
                    else
                    {
                        mensajeAlerta('Favor de ingresar un lote y la fecha de caducidad','danger');
                        $('#codigo_barras').val('');
                        return false;
                    }

                }
                else
                {
                    $('#codigo_barras').val('');
                }

            }
        }

    });

});

function guardarEntrada(tipo_documento,numero_documento)
{
    var form = $('#form_'+tipo_documento+'_'+numero_documento);
    $('.detalle_entrada_'+tipo_documento+'_'+numero_documento+' input[type="text"]').prop('disabled', false);
    var detalle_entrada = $(form).serialize();
    $('.detalle_entrada_'+tipo_documento+'_'+numero_documento+' input[type="text"]').prop('disabled', true);

    if($('#documento_'+tipo_documento+'_'+numero_documento).val() != '' ){
        if(validarForm(tipo_documento,numero_documento) != 0)
        {
            let token = document.querySelector("meta[name='csrf-token']").getAttribute("content");
            $.ajax({
                type: "POST",
                url: $('#guardar_entrada').data('url'),
                data: {'numero_documento':numero_documento,
                    'fk_id_tipo_documento':$('#fk_id_tipo_documento').val(),
                    'referencia_documento':$('#documento_'+tipo_documento+'_'+numero_documento).val(),
                    'detalle_entrada':detalle_entrada,
                    '_token':token,
                },
                dataType: "json",
                success:function(data){

                    mensajeAlerta('Registro guardado correctamente.','success');
                    window.setTimeout(function(){
                        window.location.replace($('#guardar_entrada').data('url'));
                        },10100);

                }
            });
        }
        else
        {
            mensajeAlerta('Debes ingresar algun producto antes de guardar.','danger');
        }
    }
    else
    {
        mensajeAlerta('Debes ingresar algun numero de referencial al documento antes de poder guardar.','danger');
    }


}

function validarForm(tipo_documento,numero_documento) {

    var detalle_entrada = '.detalle_entrada_'+tipo_documento+'_'+numero_documento;
    var cont_surtida = 0;
    $(detalle_entrada).find('tr').each(function(index)
    {
        var surtida = $(detalle_entrada).find('tr').eq(index).children('td').eq(9).children('input').val();

        if(surtida != undefined)
        {
            cont_surtida = cont_surtida + parseInt(surtida);
        }

    });

    return cont_surtida;
}

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