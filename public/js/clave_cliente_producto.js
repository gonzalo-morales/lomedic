$(document).ready(function () {

    $('#skus a').on('click', function (e) {
        e.preventDefault();
        $(this).tab('show')
    });

    $('[data-toggle]').tooltip();

    $("#fk_id_clave_producto_servicio").select2({
        minimumInputLength:3,
        ajax:{
            url: $('#fk_id_clave_producto_servicio').data('url'),
            dataType: 'json',
            data: function (params) {
                return {
                    'param_js':clave_producto_servicio_js,
                    $term: params.term
                };
            },
            processResults: function (data) {
                return {results: data}
            }
        }
    });

    $("#fk_id_clave_unidad").select2({
        minimumInputLength:2,
        ajax:{
            url: $('#fk_id_clave_unidad').data('url'),
            dataType: 'json',
            data: function (params) {
                return {
                    'param_js':clave_unidad_js,
                    $term: params.term
                };
            },
            processResults: function (data) {
                return {results: data}
            }
        }
    });

    $('.nav-link').on('click', function (e) {
        e.preventDefault();
        $('#clothing-nav li').each(function () {
            $(this).children().removeClass('active');
        });
        $('.tab-pane').removeClass('active').removeClass('show');
        $(this).addClass('active');
        var tab = $(this).prop('href');
        tab = tab.split('#');
        $('#' + tab[1]).addClass('active').addClass('show');
    });
    $('#addSal').on('click',function () {
        $('.modal').modal('show');
        $('.sales_modal').addClass('active');
    });

    $('#fk_id_forma_farmaceutica').on('select2:selecting',function () {
        var val = $(this).val() ? $(this).val() : 0;
        $(this).attr('data-old',val);
        $('.forma_farmaceutica_modal').addClass('active');
    });
    $('#fk_id_presentacion').on('select2:selecting',function (e) {
        var val = $(this).val() ? $(this).val() : 0;
        $(this).attr('data-old',val);
        $('.presentacion_modal').addClass('active');
    });

    $('.cancelar_cambio').on('click',function () {
        $('.modal').modal('hide');
        if($('.forma_farmaceutica_modal').hasClass('active'))
            $('#fk_id_forma_farmaceutica').val($('#fk_id_forma_farmaceutica').data('old')).trigger('change');
        else if($('.presentacion_modal').hasClass('active'))
            $('#fk_id_presentacion').val($('#fk_id_presentacion').data('old')).trigger('change');
        $('.list-group-item').removeClass('active');
    });

    $('#fk_id_forma_farmaceutica,#fk_id_presentacion').on('change',function () {
        $('.modal').modal({backdrop:'static',keyboard:false});
    });

    $('#aceptar_cambio').on('click',function () {
        $('.modal').modal('hide');
        if($('.sales_modal').hasClass('active'))
            addSalt();
        //Función AJAX para obtener función de productos
    });
});

function addSalt(){
    let salId = $('#sal').val();
    let salText = $('#sal option:selected').text();
    let concentrationId = $('#concentracion').val();
    let concentrationText = $('#concentracion option:selected').text();
    let $tbody = $('#tbodySales');
    let i = $('#tbodySales > tr').length;
    let row_id = i > 0 ? +$('#tbodySales > tr:last').find('#index').val()+1 : 0;
    let existe = false;
    $('#tbodySales tr').each(function () {
       let sal = $(this).find('.sal').val();
       let concentracion = $(this).find('.concentracion').val();
       if(sal === salId && concentracion === concentrationId)
           existe = true;
    });

    if(!existe){
        $tbody.append(
            '<tr>'+
            '<td>' + '<input type="hidden" id="index" value="'+row_id+'"><input type="hidden" name="relations[has][concentraciones]['+row_id+'][fk_id_concentracion]" value="'+concentrationId+'" class="concentracion"><input type="hidden" name="relations[has][concentraciones]['+row_id+'][fk_id_sal]" value="'+salId+'" class="sal">' + salText + '</td>' +
            '<td>' + concentrationText + '</td>' +
            '<td>' + '<button data-toggle="Eliminar" data-placement="top" title="Eliminar" data-original-title="Eliminar" type="button" class="text-primary btn btn_tables is-icon eliminar bg-white" data-delay="50" onclick="borrarFila(this)"><i class="material-icons">delete</i></button>' + '</td>' +
            +'</tr>'
        );
        $.toaster({priority:'success',
                title: '¡Éxito!',
                message:"Elemento agregado con éxito",
                settings:{'timeout':8000,
                    'toaster':{'css':{'top':'5em'}}}
            }
        );
        $('[data-toggle]').tooltip();
    }else{
        $.toaster({priority:'danger',
                title: '¡Error!',
                message:"Elemento ya agregado",
                settings:{'timeout':8000,
                    'toaster':{'css':{'top':'5em'}}}
            }
        );
    }

}

function borrarFila(el) {
    var tr = $(el).closest('tr');
    tr.fadeOut(400, function(){
        tr.remove().stop();
    })
    $.toaster({priority:'success',
            title: '¡Éxito!',
            message:"Se ha eliminado la fila",
            settings:{'timeout':8000,
                'toaster':{'css':{'top':'5em'}}}
        }
    );
};