$(document).ready(function () {
    $('#addDetalle').click(function (e) {

        var iddocument = $('#fk_id_pedido option:selected').val();
        var _url = $('#fk_id_pedido').data('url');
        $.ajax({
            url: _url,
            data: {'param_js': api_pedido ,$id_documento:iddocument},
            dataType: "json",
            success: function (data) {
                var $tableBody = $('#tableBodySolicitudes')
                $('#fk_id_pedido').val('');
                $('#fk_id_pedido').trigger('change');
                var i = $('#detalle-form-body > tr').length;
                var row_id = i > 0 ? +$('#tableBodySolicitudes > tr:last').find('#index').val()+1 : 0;

                for (var i = 0; i < data[0].detalle.length; i++) {
                    console.log(data[0].detalle[i]);
                    $tableBody.append(
                        '<tr><th>'+ row_id +
                            '<input type="hidden" id="index" value="'+row_id+'">'+
                            '<input type="hidden" name="relations[has][detalle]['+row_id+'][fk_id_sku]" value="'+ data[0].detalle[i].fk_id_sku +'"/>'+
                            '<input type="hidden" name="relations[has][detalle]['+row_id+'][fk_id_upc]" value="'+ data[0].detalle[i].fk_id_upc +'"/>'+
                            '</th>' +
                        '<td>'+ '<img style="max-height:40px" src="img/upc.png" alt="upc"/> ' + upcData[i].text + '<input class="upc-unique" type="hidden" name="relations[has][detalle]['+row_id+'][fk_id_upc]" value="'+upcData[i].id+'"/>'+'</td>' +
                        '<td class="align-middle">'+ '<i class="material-icons align-middle">today</i> ' +skuDataSet.fecha_caducidad +'<input type="hidden" name="relations[has][detalle]['+row_id+'][fecha_caducidad]" value="'+skuDataSet.fecha_caducidad+'"/>' + '</td>' +
                        '<td class="align-middle">'+ '<i data-toggle="Lote" data-placement="top" title="Lote" data-original-title="Lote" class="material-icons align-middle">label</i> ' + skuDataSet.lote + '<br>' + almacenData.almacen +' / '+ ubicacionData.ubicacion +'<br>' + '<i data-toggle="Stock actual" data-placement="top" title="Stock actual" data-original-title="Stock actual" class="material-icons align-middle">shopping_basket</i> ' + skuDataSet.stock +'<input  type="hidden" value="'+skuDataSet.stock+'"/>' + '</td>' +
                        '<td>'+ $('#campo_ubicacion').html().replace('$row_id',row_id).replace('$row_id',row_id) +'</td>'+
                        '<td>'+ '<div class="input-group"><span class="input-group-addon">'+ '<i class="material-icons align-middle">label</i> ' +'</span><input style="min-width:60px" type="text" class="form-control nuevo_lote" name="relations[has][detalle]['+row_id+'][lote]" value="'+skuDataSet.lote+'"/></div>' + '</td>' +
                        '<td>'+ '<div class="input-group"><span class="input-group-addon">'+ '<i class="material-icons align-middle">shopping_basket</i> ' +'</span><input type="number" style="width: 80px" class="form-control cantidad_stock" name="relations[has][detalle]['+row_id+'][stock]" value="'+skuDataSet.stock+'"/></div>' + '</td>' +
                        '<td>'+ '<button data-toggle="Eliminar" data-placement="top" title="Eliminar" data-original-title="Eliminar" type="button" class="text-primary btn btn_tables is-icon eliminar" style="background:none;" data-delay="50" onclick="borrarFila(this)"><i class="material-icons">delete</i></button>'+'</td></tr>'
                    );
                };

            },
            error: function(){
                $('#fk_id_pedido').val('');
                $('#fk_id_pedido').trigger('change');
                $.toaster({priority : 'danger',title : '¡Lo sentimos!',message : 'Selecciona un <b>pedido diferente</b>, ya que el seleccionado no cuenta con producto activo.',
                settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
            },
        });
        
    });
});