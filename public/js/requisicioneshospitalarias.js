
$(document).ready(function () {


    $('.programa').select2();
    $('.area').select2();

    medicamento();

    $('#agregar').click(function () {

        var medicamento = $('#medicamento').select2('data');
        var campos = '';
        if($('#medicamento').select2('data').length ==0){
            campos += '<br><br>Medicamento: ¿Seleccionaste un medicamento?';
        }
        if(campos!=''){

            mensajeAlerta('Verifica los siguientes campos '+campos,'danger');
            // $.toaster({
            //     priority : 'danger',
            //     title : 'Verifica los siguientes campos',
            //     message : campos,
            //     settings:{
            //         'timeout':10000,
            //         'toaster':{
            //             'css':{
            //                 'top':'5em'
            //             }
            //         }
            //     }
            // });
            return
        }


        $('#fk_id_sucursal').select2();
        // $('#id_receta').select2();
        let token = document.querySelector("meta[name='csrf-token']").getAttribute("content");
        $('#fk_id_sucursal').on('change', function() {
            $.ajax({
                type: "POST",
                url: $(this).data('url'),
                data: {'fk_id_sucursal':$(this).val(),'_token':token},
                dataType: "json",
                success:function(data) {
                    $('#fk_id_requisiciones_hospitalarias').empty();
                    $.each(data, function(key, value) {
                        $('#fk_id_requisiciones_hospitalarias').append('<option value="'+ key +'">'+ value +'</option>');
                    });
                    $('#fk_id_requisiciones_hospitalarias').val('');
                }
            });
        });


        var filas = $('.medicine_detail tr').length;
        var agregar = true;

        if(agregar) {

            var area = $('#fk_id_area').select2('data');
            var medicamento = $('#medicamento').select2('data');
            var cantidad = $('#cantidad');
            $('.medicine_detail').append('' +
                '<tr id="'+ filas +'">' +
                    '<td>' + area[0]['text'] + '</td>' +
                    '<td>' + medicamento[0].clave_cliente_producto + '</td>' +
                    '<td>' + medicamento[0]['text'] + '</td>' +
                    '<td>' + cantidad.val() + '</td>' +
                    '<td>' +
                        '<a onclick="eliminarFila(this)" data-delete-type="single"  data-toggle="tooltip" data-placement="top" title="Borrar"  id="' + filas + '" aria-describedby="tooltip687783"><i class="material-icons text-primary">delete</i></a> ' +
                    '</td> ' +
                    // '<input type="hidden" name="relations[has][detalles][' + filas + '][id_detalle_requisicion]"  value=""/> ' +
                    '<input type="hidden" name="relations[has][detalles]['+filas+'][fk_id_area]" value="'+area[0]['id']+'"> ' +
                    '<input type="hidden" name="relations[has][detalles]['+filas+'][fk_id_clave_cliente_producto]" value="'+medicamento[0].fk_id_clave_cliente_producto+'"> ' +
                    '<input type="hidden" name="relations[has][detalles]['+filas+'][cantidad_solicitada]" value="'+cantidad.val()+'"> ' +
                '</tr>');
            $('#guardar').prop('disabled', filas = 0);
            limpiarCampos();

            mensajeAlerta('Medicamento agregado exitosamente','success');
            // $.toaster({
            //     priority: 'success',
            //     title: 'Éxito!',
            //     message: '<br>Medicamento agregado exitosamente',
            //     settings: {
            //         'toaster': {
            //             'css': {
            //                 'top': '5em'
            //             }
            //         }
            //     }
            // });
        }
    });

    $("#form-model").submit(function(){

        var filas = $('.medicine_detail tr').length;


        if( filas <= 0 )
        {
            mensajeAlerta('Favor de ingresar por lo menos un producto.','danger');
            return false;
        }
        else
        {
            return true;
        }
    });


});


function formatMedicine(medicine) {
    if(!medicine.id){return medicine.text;}
    return $('<span>'+medicine.text+'</span><br>Presentación: <b>'+medicine.familia+'</b> Cantidad en la presentación: <b>'+medicine.cantidad_presentacion+'</b>' +
        '<br>Disponibilidad: <b>'+medicine.disponible+'</b> Máximo para recetar: <b>'+medicine.tope_receta+'</b>');
}

function eliminarFila(el)
{

    $(el).parent().parent('tr').remove();
    $.toaster({priority:'success',title:'¡Correcto!',message:'Se ha eliminado correctamente el '+$(el).data('tooltip'),settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});

}

function medicamento() {
    let token = document.querySelector("meta[name='csrf-token']").getAttribute("content");
    $("#medicamento").select2({
        placeholder: 'Escriba el medicamento',
        ajax: {
            type: 'POST',
            url: $("#medicamento").data('url'),
            dataType: 'json',
            data: function(params) {
                return {
                    '_token':token,
                    medicamento: $.trim(params.term), // search term
                    // localidad: $('.unidad').val()
                };
            },
            processResults: function(data) {
                return {
                    results: data
                };
            },
            cache: true
        },
        escapeMarkup: function(markup) {
            return markup;
        },
        minimumInputLength: 3,
        language: {
            "noResults": function() {
                return "No se encontraron resultados";
            }
        },
        escapeMarkup: function(markup) {
            return markup;
        },
        templateResult: formatMedicine,
    });
}

function limpiarCampos() {

    $("#fk_id_area").val('').trigger('change');
    $("#medicamento").val('').trigger('change');
    $('#cantidad').val('');

}

function escaparID(myid){
    return "#" + myid.replace( /(:|\.|\[|\]|,|=|@)/g, "\\$1" );
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