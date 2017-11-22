/**
 * Created by ihernandezt on 12/10/2017.
 */



$('#entrada_escaner').on('change', function() {
        var no_entrada = $('#entrada_escaner').val();
        $('#entrada_escaner').val('');


        if(no_entrada) {
            $.ajax({
                url: $('#entrada_escaner').data('url'),
                method:'POST',
                data: {'numero_documento':no_entrada,'fk_id_tipo_documento':$('#fk_id_tipo_documento').val()},
                dataType: "json",
                success:function(data){

                    console.info(data);

                    if(data != '')
                    {
                        $('#lista_entradas').append('<li class="nav-item">' +
                            '<a class="nav-link" href="#'+no_entrada+'" role="tab" data-toggle="tab">'+no_entrada+'</a> ' +
                            '</li>');


                        var nombre_sucursal = data.datos_orden.sucursales.sucursal;
                        var nombre_proveedor = data.datos_orden.proveedor.nombre_comercial;
                        var detalle_entrada = '';
                        var cantidad_sutida = 0;
                        var estado_producto = '';

                        $.each(data.detalle_orden, function(index) {


                            var sku = data.detalle_orden[index].detalle_sku.sku;
                            var id_sku = data.detalle_orden[index].detalle_sku.id_sku;
                            var upc = data.detalle_orden[index].detalle_upc.upc;
                            var id_upc = data.detalle_orden[index].detalle_upc.id_upc;
                            var cliente = data.detalle_orden[index].fk_id_cliente;
                            var proyecto = data.detalle_orden[index].fk_id_proyecto;

                            if( data.detalle_orden[index].detalle_sku.sku === null || data.detalle_orden[index].detalle_sku.sku === undefined )
                            {
                                sku = '-';
                            }

                            if(data.detalle_orden[index].detalle_upc.upc === null || data.detalle_orden[index].detalle_upc.upc === undefined )
                            {
                                upc = '-';
                            }

                            if( data.detalle_orden[index].fk_id_cliente === null || data.detalle_orden[index].fk_id_cliente === undefined )
                            {
                                cliente = '-';
                            }

                            if(data.detalle_orden[index].fk_id_proyecto === null || data.detalle_orden[index].fk_id_proyecto === undefined)
                            {
                                proyecto = '-';
                            }

                            if( cantidad_sutida < data.detalle_orden[index].cantidad )
                            {
                                estado_producto = '<input type="text" id="'+index+'_ingresar_'+sku+'_'+upc+'"  name="'+index+'_ingresar_'+sku+'_'+upc+'" value="0" style="max-width:6em;" disabled>';
                            }
                            else
                            {
                                estado_producto = 'Producto ya surtido en su totalidad.';
                            }

                            detalle_entrada = detalle_entrada + '<tr>' +
                                '<td>'+sku+'</td>' +
                                '<td>'+upc+'</td>' +
                                '<td>'+data.detalle_orden[index].detalle_sku.descripcion.substr(0, 150)+'</td>' +
                                '<td>'+cliente+'</td>' +
                                '<td>'+proyecto+'</td>' +
                                '<td><input type="text" id="'+index+'_lote_'+sku+'_'+upc+'" name="datos_entradas['+index+'][lote]" style="max-width:6em;" disabled></td>' +
                                '<td><input type="text" id="'+index+'_caducidad_'+sku+'_'+upc+'" name="datos_entradas['+index+'][caducidad]" style="max-width:6em;"disabled></td>' +
                                '<td><input type="text" id="'+index+'_entrada_'+sku+'" name="datos_entradas['+index+'][entrada]" value="'+data.detalle_orden[index].cantidad+'" style="max-width:6em;" disabled></td>' +
                                '<td><input type="text" id="'+index+'_surtida_'+sku+'" name="datos_entradas['+index+'][surtida]"  value="0" style="max-width:6em;" disabled></td>' +
                                '<td>'+estado_producto+'</td>' +
                                '<td></td>' +
                                '<td></td>' +
                                '<input type="hidden" id="datos_entradas-'+index+'-id_sku" name="datos_entradas['+index+'][id_sku]" value="'+id_sku+'" >' +
                                '<input type="hidden" id="datos_entradas-'+index+'-id_upc" name="datos_entradas['+index+'][id_upc]" value="'+id_upc+'" >' +
                                '</tr>';
                            // detalle_entrada = detalle_entrada.replace(undefined, "-");
                            // detalle_entrada = detalle_entrada.replace(null, "-");
                        });

                        $('#detalle_entrada').append('<div role="tabpanel" class="tab-pane fade in" id="'+no_entrada+'">' +
                            '<div class="row"> ' +
                            '   <div class="col-sm-12"> ' +
                            '       <h3>Entrada: '+ no_entrada +'</h3> <input type="hidden" name="'+no_entrada+'">' +
                            '       <div class="card z-depth-1-half"> ' +
                            '           <div class="card-body"> ' +
                            '               <div class="row">' +
                            '                   <div class="col-md-6 col-sm-6 col-lg-3">' +
                            '                       <div class="form-group">' +
                            '                           <label for="sucursal_'+no_entrada+'">Sucursal</label>' +
                            '                           <input class="form-control" id="sucursal_'+no_entrada+'" name="sucursal_'+no_entrada+'" type="text" value="'+nombre_sucursal+'" disabled>' +
                            '                       </div>' +
                            '                   </div> ' +
                            '                   <div class="col-md-6 col-sm-6 col-lg-3">' +
                            '                       <div class="form-group">' +
                            '                           <label for="negocios_'+no_entrada+'">Proveedor</label>' +
                            '                           <input class="form-control" id="negocios_'+no_entrada+'" name="negocios_'+no_entrada+'" type="text" value="'+nombre_proveedor+'" disabled>' +
                            '                       </div>' +
                            '                   </div> ' +
                            '                   <div class="col-md-6 col-sm-6 col-lg-3">' +
                            '                       <div class="form-group">' +
                            '                           <label for="documento_'+no_entrada+'">Documento</label>' +
                            '                           <input class="form-control" id="documento_'+no_entrada+'" name="documento_'+no_entrada+'" type="text">' +
                            '                       </div>' +
                            '                   </div>' +
                            '                   <div class="text-right d-flex ">' +
                            '                       <button type="button" class="btn btn-primary" id="guardar_entrada" onclick="guardarEntrada('+no_entrada+')" data-url='+data.company_route+'>Guardar</button> ' +
                            '                   </div>' +
                            '                   <div class="col-12"> ' +
                            '                       <h3>Detalle de la entrada</h3> ' +
                            '                       <div class="row justify-content-center">' +
                            '                           <div class="col-md-6 col-sm-6 col-lg-3">' +
                            '                               <div class="form-group">' +
                            '                                   <label for="lote_'+no_entrada+'">Lote</label>' +
                            '                                   <input class="form-control" id="lote_'+no_entrada+'" name="lote_'+no_entrada+'" type="text">' +
                            '                               </div>' +
                            '                           </div>' +
                            '                           <div class="col-md-6 col-sm-6 col-lg-3">' +
                            '                               <div class="form-group">' +
                            '                                   <label for="caducidad_'+no_entrada+'">Fecha de caducidad</label>' +
                            '                                   <input class="datepicker form-control" id="caducidad_'+no_entrada+'" name="caducidad_'+no_entrada+'" type="text" placeholder="Selecciona una fecha">' +
                            '                               </div>' +
                            '                           </div>' +
                            '                           <div class="col-12 col-md-6 col-lg-4">' +
                            '                               <div class="form-group">' +
                            '                                   <label for="codigo_barras">Código del producto</label>' +
                            '                                   <input class="form-control" id="codigo_barras" name="'+no_entrada+'" type="text">' +
                            '                               </div>' +
                            '                           </div>' +
                            '                       </div>' +
                            '                       <form id="form_'+no_entrada+'"><table class="table table-hover table-responsive" name="table2">' +
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
                            '                           <tbody id="detalle_entrada_'+no_entrada+'">'+ detalle_entrada +'</tbody> ' +
                            '                       </table></form> ' +
                            '                   </div> ' +
                            '               </div> ' +
                            '           </div>' +
                            '       </div>' +
                            '   </div>' +
                            '</div></div>');

                        $('#caducidad_52').pickadate({ selectMonths: true, // Creates a dropdown to control month
                            selectYears: 3, // Creates a dropdown of 3 years to control year
                            min: true,
                            format: 'yyyy-mm-dd'});

                    }


                }
            });

        }

        $("#form-model").submit(function(){
            return false;
        });
    });




$(document).on('change','#codigo_barras',function (){

    var codigo_barras = this.value;
    var numero_documento = this.name;
    this.value = '';

    $("#detalle_entrada tr").find("input").each(function(){

        var id_row = this.id;
        if(id_row.includes('ingresar_'))
        {
            var codigos = id_row.split('_');

            if(codigos[2] == codigo_barras)
            {

                if( parseInt($('#'+codigos[0]+'_entrada_'+codigos[2]).val()) >= ( (parseInt($('#'+id_row).val()) + 1 ) + parseInt($('#'+codigos[0]+'_surtida_'+codigos[2]).val()) ))
                {
                    var lote = id_row.replace('_ingresar_','_lote_');
                    var caducidad = id_row.replace('_ingresar_','_caducidad_');
                    $('#'+lote).val($('#lote_'+numero_documento).val());
                    $('#'+caducidad).val($('#caducidad_'+numero_documento).val());
                    $('#'+id_row).val(parseInt($('#'+id_row).val()) + 1);

                    if(parseInt($('#'+codigos[0]+'_entrada_'+codigos[2]).val()) == ( parseInt($('#'+id_row).val()) + parseInt($('#'+codigos[0]+'_surtida_'+codigos[2]).val()) ) )
                    {
                        $('#lote_'+numero_documento).val('');
                        $('#caducidad_'+numero_documento).val('');
                    }

                }
                else
                {
                    alert('Estas mal !!');
                }
            }
            if(codigos[3] == codigo_barras)
            {
                $('#'+id_row).val(parseInt($('#'+id_row).val()) + 1);
            }
        }

    });


});




function guardarEntrada(numero_documento)
{
    var form = $('#form_'+numero_documento);
    $('#detalle_entrada_'+numero_documento+' input[type="text"]').prop('disabled', false);
    var detalle_entrada = $(form).serialize();
    $('#detalle_entrada_'+numero_documento+' input[type="text"]').prop('disabled', true);
    //
    // var myform = $('#form_'+numero_documento);
    // var disabled = myform.find(':input:disabled').removeAttr('disabled');
    // var serialized = disabled.serializeArray();
    // disabled.attr('disabled','disabled');

    $.ajax({
        type: "POST",
        url: $('#guardar_entrada').data('url'),
        data: {'numero_documento':numero_documento,
            'fk_id_tipo_documento':$('#fk_id_tipo_documento').val(),
            'referencia_documento':$('#documento_'+numero_documento).val(),
            'detalle_entrada':detalle_entrada,

        },
        dataType: "json",
        success:function(data){

            console.info(data);

        }
    });
}
