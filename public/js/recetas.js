
$(document).ready(function () {

    medicamento();
    afiliafos();

    var token = document.querySelector("meta[name='csrf-token']").getAttribute("content");
    $('#fk_id_sucursal').on('change', function() {
        $.ajax({
            type: "POST",
            url: $(this).data('url'),
            data: {'fk_id_sucursal':$(this).val(),'_token':token},
            dataType: "json",
            success:function(data) {
                $('#fk_id_proyecto').empty();
                $.each(data, function(key, value) {
                    $('#fk_id_proyecto').append('<option value="'+ key +'">'+ value +'</option>');
                });
                $('#fk_id_proyecto').val('');
            }
        });
    });

    var  token = document.querySelector("meta[name='csrf-token']").getAttribute("content");
    $("#fk_id_diagnostico").select2({
        placeholder: 'Escriba el diagnóstico del paciente',
        ajax: {
            type: 'POST',
            url: $("#fk_id_diagnostico").data('url'),
            dataType: 'json',
            data: function(params) {
                return {

                    '_token':token,
                    diagnostico: $.trim(params.term) // search term
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
        }



    });

    $('#cargar_receta').click(function () {
        $.ajax({
            method: "POST",
            url: 'https://pensiones.jalisco.gob.mx/Farmacia/wsFarmacia.asmx/consulta_folio',
            data: { Usr: "ABISALUD",Pwd:'TB-x23-G3',farmacia:'UNIMEF JAVIER MINA',folio:'205490',sufijo:'A'},
            dataType: 'json',
            success:function (data) {
                console.info(data);
            }
        });
    });

    $('#tipo_servicio').on('change', function() {

        if( $('#tipo_servicio').parent().find('span').html() == 'Externo')
        {
            $('#nombre_paciente_no_afiliado').prop('disabled',false);
            $('#fk_id_afiliado').empty().append('<option value="0"> </option>').val('0').trigger('change');
            $('#fk_id_afiliado').prop('disabled',true);
        }
        else if( $('#tipo_servicio').parent().find('span').html() == 'Afiliado' )
        {
            $('#nombre_paciente_no_afiliado').prop('disabled',true);
            $('#nombre_paciente_no_afiliado').val('');
            $('#fk_id_afiliado').prop('disabled',false);
        }
    });


    $("#form-model").submit(function(){

        var fila = $('.medicine_detail tr').length;

        if( fila <= 0 )
        {
            mensajeAlerta('Favor de ingresar por lo menos un medicamento.','danger');
            return false;
        }
        else
        {
            if( $('#nombre_paciente_no_afiliado').prop('disabled') == false )
            {
                if($('#nombre_paciente_no_afiliado').val() == ''){
                    mensajeAlerta('Favor de ingresar el nombre del paciente no afiliado.','danger');
                    return false;
                }
                else
                {
                    $('#fk_id_afiliado').prop('disabled',false);
                    return true;
                }
            }else{
                $('#fk_id_afiliado').prop('disabled',false);
                return true;
            }
            // $('#form-model').prop("disabled", true);

        }
    });

});


function resurtir()
{
    if($('#surtido_numero').prop('disabled') == true)
    {
        $('#surtido_numero').prop('disabled',false);
        $('#surtido_tiempo').prop('disabled',false);
    }
    else if($('#surtido_numero').prop('disabled') == false)
    {
        $('#surtido_numero').prop('disabled',true);
        $('#surtido_tiempo').prop('disabled',true);
    }
}



function agregar_medicamento() {

    // $('#agregar').click(function () {

    var medicamento = $('#medicamento').select2('data');
    var campos = '';
    if ($('#medicamento').select2('data').length == 0) {
        campos += '<br><br>Medicamento: ¿Seleccionaste un medicamento?';
    }
    if (!parseInt($('#dosis').val()) > 0)
        campos += '<br><br>Necesito que me indiques la <b>dosis</b> del medicamento';
    if (!parseInt($('#cada').val()) > 0)
        campos += '<br><br>Necesito que me indiques <b>cada</b> cuando tomar el medicamento';
    if (!parseInt($('#por').val()) > 0)
        campos += '<br><br>Necesito que me indiques <b>la duracion</b> del medicamento';
    if (parseInt($('#cada').val()) * parseInt($('#_cada option:selected').val()) > parseInt($('#por').val()) * parseInt($('#_por option:selected').val()))
        campos += '<br><br>Verifica que el campo<b> cada </b> no sea mayor al campo <b>por</b>';

    if (campos != '') {

        mensajeAlerta('Verifica los siguientes campos', 'danger');
        return
    }
    var filas = $('.medicine_detail tr').length;
    var dosis_text = '<b>';
    var dosis_hidden = parseInt($('#dosis').val());
    dosis_text += $('#dosis').val() + ' ';
    if ($('#dosis14').prop('checked') == true) {
        dosis_text += '1/4 ';
        dosis_hidden += 0.25;
    } else if ($('#dosis12').prop('checked') == true) {
        dosis_text += '1/2 ';
        dosis_hidden += 0.5;
    }

    var cantidad_final = 1;
    var recurrencia_text = '';
    var recurrencia_hidden = 0;
    var veces_surtir = 0;

    if ($('#surtido_recurrente').prop('checked') == true) {
        var cantidad_medicamento_necesaria = (($('#surtido_tiempo option:selected').val() * $('#surtido_numero').val()) / ($('#_cada option:selected').val() * $('#cada').val())) * dosis_hidden;
        if (medicamento[0].cantidad_presentacion > 1) {
            while (medicamento[0].cantidad_presentacion * cantidad_final < cantidad_medicamento_necesaria) {
                cantidad_final++;
            }
            if (cantidad_final > medicamento[0].tope_receta) {
                mensajeAlerta('Asegúrate que la cantidad entregable no sea mayor al tope de entrega', 'danger');
                return
            }
        } else {
            mensajeAlerta('Este medicamento no cuenta con la información necesaria. Te recomendamos seleccionar otro.', 'danger');
            return
        }

        var _duracion = '<b>' + $('#por').val();
        _duracion += ' ' + $('#_por option:selected').text() + '</b>';

        recurrencia_text += 'Recoger ' + cantidad_final + '<b> caja(s)</b> cada <b>' + $('#surtido_numero').val() + ' ' + $('#surtido_tiempo option:selected').text() + '</b> durante ' + _duracion;
        recurrencia_hidden += $('#surtido_numero').val() * $('#surtido_tiempo option:selected').val();
        if (($('#surtido_tiempo option:selected').val() * $('#surtido_numero').val()) >= $('#_por option:selected').val() * $('#por').val() || !($('#surtido_numero').val() > 0)) {
            mensajeAlerta('Verifica el tiempo de recurrencia y el de la duración del tratamiento', 'danger');
            return
        }
        veces_surtir = parseInt($('#por').val()) * (parseInt($('#_por option:selected').val()) / 24);
        veces_surtir = veces_surtir / (recurrencia_hidden / 24);

    } else {//Si no es recurrente
        var cantidad_medicamento_necesaria = (($('#_por option:selected').val() * $('#por').val()) / ($('#_cada option:selected').val() * ($('#cada').val()))) * dosis_hidden;
        if (medicamento[0].cantidad_presentacion > 1) {
            while (medicamento[0].cantidad_presentacion * cantidad_final < cantidad_medicamento_necesaria) {
                cantidad_final++;
            }
            if (cantidad_final > medicamento[0].tope_receta) {
                mensajeAlerta('Asegúrate que la cantidad entregable no sea mayor al tope de entrega', 'danger');
                return
            }
        } else {
            mensajeAlerta('Este medicamento no cuenta con la informaciÃ³n necesaria. Te recomendamos seleccionar otro.', 'danger');
            return
        }
    }

    if (veces_surtir < 1 || veces_surtir == null)
        veces_surtir = 1;
    dosis_hidden += ' ' + medicamento[0].familia;
    dosis_text += medicamento[0].familia + '</b>';

    var tiempo_text = '<b>' + $('#cada').val();
    tiempo_text += ' ' + $('#_cada option:selected').text() + '</b>';
    var tiempo_hidden = $('#cada').val() + ' ' + $('#_cada option:selected').text();

    var duracion_text = '<b>' + $('#por').val();
    duracion_text += ' ' + $('#_por option:selected').text() + '</b>';
    var duracion_hidden = $('#por').val() + ' ' + $('#_por option:selected').text();

    var nota_medicamento = $('#nota_medicamento').val();

    var agregar = true;
    if ($('#detalle tbody tr').length > 0) {
        $('#detalle tbody tr').each(function (index) {
            if (this.id == medicamento[0].id) {
                agregar = false;
                mensajeAlerta('<br>Medicamento agregado anteriormente', 'danger');
            }
        })
    }

    if (agregar) {
        $('.medicine_detail').append('' +
            '<tr id="' + medicamento[0].id + '">' +
            '<th scope="row">' + medicamento[0].sku + '</th>' +
            '<td>' +
            '<p><input name="relations[has][detalles][' + filas + '][id_receta_detalle]" type="hidden" value=""/></p>' +
            '<p><input id="clave_cliente' + medicamento[0].id + '" name="relations[has][detalles][' + filas + '][fk_id_clave_cliente_producto]" type="hidden" value="' + medicamento[0].fk_id_clave_cliente_producto + '"/>' + medicamento[0].text + '</p>' +
            '<p><input id="tbdosis' + medicamento[0].id + '" name="relations[has][detalles][' + filas + '][dosis]" type="hidden" value="' + dosis_hidden + ' cada ' + tiempo_hidden + ' por ' + duracion_hidden + '" />' + dosis_text + ' cada ' + tiempo_text + ' por ' + duracion_text + '</p>' +
            '<p><input id="tbcantidad_pedida' + medicamento[0].id + '" name="relations[has][detalles][' + filas + '][cantidad_pedida]" type="hidden" value="' + cantidad_final + '" />Recoger hoy: ' + cantidad_final + '</p>' +
            '<p><input id="tben_caso_presentar' + medicamento[0].id + '" name="relations[has][detalles][' + filas + '][en_caso_presentar]" type="hidden" value="' + nota_medicamento + '" />' + nota_medicamento + '</p>' +
            '<p><input id="tbpor[' + medicamento[0].id + ']" name="relations[has][detalles][' + filas + '][por]" type="hidden" value="' + $('#por').val() * $('#_por option:selected').val() + '"/>' +
            '<input id="_detalle[' + medicamento[0].id + '][recurrente]" name="relations[has][detalles][' + filas + '][recurrente]" type="hidden" value="' + recurrencia_hidden + '"/>' + recurrencia_text + '</p>' +
            // '<input id="_detalle[' + medicamento[0].id + '][fk_id_cuadro]" name="relations[has][detalles][' + filas + '][fk_id_cuadro]" type="hidden" value="' + medicamento[0].fk_id_cuadro + '"/>' +
            '<input id="tbveces_surtir' + medicamento[0].id + '" name="relations[has][detalles][' + filas + '][veces_surtir]" type="hidden" value="' + Math.ceil(veces_surtir) + '"/>' +
            '<input id="tbcatidad_surtida' + medicamento[0].id + '" name="relations[has][detalles][' + filas + '][cantidad_surtida]" type="hidden" value="0"/>' +
            // '<input id="tbrecurrente' + medicamento[0].id + '" name="relations[has][detalles][' + filas + '][recurrente]" type="hidden" value="0"/>' +
            '</td>' +
            '<td>' +
            '<a onclick="eliminarFila(this)" data-delete-type="single"  data-toggle="tooltip" data-placement="top" title="Borrar"  id="' + filas + '" aria-describedby="tooltip687783"><i class="material-icons text-primary">delete</i></a> ' +
            '</td>' +
            '</tr>');
        $('#guardar').prop('disabled', filas = 0);
        limpiarCampos();
        mensajeAlerta('Medicamento agregado exitosamente', 'success');

    }
}

function afiliafos() {

    let token = document.querySelector("meta[name='csrf-token']").getAttribute("content");
    $("#fk_id_afiliado").select2({
        placeholder: 'Escriba el número de afiliación o el nombre del paciente',
        ajax: {
            type: 'POST',
            url: $("#fk_id_afiliado").data('url'),
            dataType: 'json',
            data:function(params) {
                return {
                    '_token':token,
                    membership: $.trim(params.term) // search term

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
        minimumInputLength: 1,
        language: {
            "noResults": function() {
                return "No se encontraron resultados";
            }
        },
        escapeMarkup: function(markup) {
            return markup;
        }
    });
}

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
                    localidad: $('.unidad').val()
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
    $('#dosis').val('');
    $('#cada').val('');
    $('#por').val('');
    $('#surtido_numero').val('');
    $('#nota_medicamento').val('');
    $('#medicamento').select2('destroy');
    $('#medicamento').empty();
    medicamento();
    $('#dosis12').prop('checked',false);
    $('#dosis12').parent().removeClass('active');
    $('#dosis14').prop('checked',false);
    $('#dosis14').parent().removeClass('active');
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



