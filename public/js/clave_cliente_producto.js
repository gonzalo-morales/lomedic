$(document).ready(function () {

    $(document).on('submit',function (e) {
       if($('input:checkbox.upc:checked').length < 1){
           $.toaster({priority:'danger',
                   title: '¡Error!',
                   message:"Verifica que tengas al menos un UPC seleccionado",
                   settings:{'timeout':8000,
                       'toaster':{'css':{'top':'5em'}}}
               }
           );
           e.preventDefault();
       }
    });

    $('#fk_id_subgrupo').on('change',function () {
        let table_sal = $('#tableSal');
        let table_especificaciones = $('#tableEspecificaciones');
        $(table_sal).hide('slow');
        $(table_especificaciones).hide('slow');

        const elemento = $(this).select2('data')[0].element;
        if($(elemento).data('sales')){
            $(table_sal).show('slow');
        }
        if($(elemento).data('especificaciones')){
            $(table_especificaciones).show('slow');
        }
    });

    $('#addEspecificacion').on('click',function () {
       addEspecificacion();
    });

    $('#skus a').on('click',function (e) {
        $(this).tab('show');
        e.preventDefault();
    });

    $('#fraccionado').on('change',function () {
        if($(this).is(":checked")) {
            $('#fk_id_presentacion').attr('disabled', true).val('').trigger('change');
            $('#skus,#upcs').empty();
        }
        else{
            if(!$('#material_curacion').is(':checked'))
                $('#fk_id_presentacion').attr('disabled',false);
        }
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
        if($('#concentracion').val())
            addSalt();
        else
            $.toaster({priority:'warning',
                    title: '¡Alerta!',
                    message:"Verifica la concentración de la sal",
                    settings:{'timeout':8000,
                        'toaster':{'css':{'top':'5em'}}}
                }
            );
    });

    $('#fk_id_forma_farmaceutica,#fk_id_presentacion').on('change',function () {
       buscaProductos();
    });

});

function buscaProductos() {
    $('.list-group-item').removeClass('active');
    $('#skus,#upcs').empty();
    //Función AJAX para obtener función de productos
    let id_forma = $('#fk_id_forma_farmaceutica').val() ? $('#fk_id_forma_farmaceutica').val() : 0;
    let id_presentaciones = $('#fk_id_presentacion').val() ? $('#fk_id_presentacion').val() : 0;
    let sales = [];
    $('#tbodySales tr').each(function () {
        let obj = {};
        obj.fk_id_sal = $(this).find('.sal').val();
        obj.fk_id_presentaciones = $(this).find('.concentracion').val();
        sales.push(obj);
    });
    let especificaciones = [];
    $('#tbodyEspecificaciones tr').each(function () {
        let obj = {};
        obj.id_especificacion = $(this).find('.especificacion').val();
        especificaciones.push(obj);
    });
    if(sales.length > 0 || especificaciones.length > 0)
        $.ajax({
            url:$('#productos').data('url'),
            data:{
                fk_id_forma_farmaceutica:id_forma,
                fk_id_presentaciones:id_presentaciones,
                sales:JSON.stringify(sales),
                especificaciones:JSON.stringify(especificaciones)
            },
            success:function (data) {
                $('#skus,#upcs').empty();
                data = JSON.parse(data);
                $(data).each(function (index,sku) {
                    let activo = '';
                    let selected = 'false';
                    if(index == 0){
                        activo = 'active';
                        selected = 'true';
                    }
                    $('#skus').append('<li class="nav-item"><a class="nav-link '+activo+'" id="sku_'+index+'_tab" data-toggle="tab" href="#sku_'+index+'" role="tab" aria-controls="sku_'+index+'" aria-selected="'+selected+'" onclick="cambio(this)">'+sku.sku+'</a></li>');
                    let contenido_tab = '<div class="tab-pane fade '+activo+'" id="sku_'+index+'" aria-labelledby="sku_'+index+'_tab" role="tabpanel">';
                    contenido_tab += '<table class="table table-responsive-sm table-striped table-hover" width="100%">';
                    contenido_tab += '<thead><tr><th></th><th>UPC</th><th>Nombre Comercial</th><th>Marca</th><th>Descripcion</th><th>Laboratorio</th></tr></thead><tbody>';
                    $(sku.upcs).each(function (i,upc) {
                        contenido_tab +=
                            '<tr>' +
                            '<td>' +
                            '<div class="form-check">' +
                            '<input name="productos['+index+'][fk_id_upc]" type="hidden" value="0">' +
                            '<label class="form-check-label custom-control custom-checkbox">' +
                            '<input  class="form-check-input custom-control-input upc" name="productos['+index+'][fk_id_upc]" type="checkbox" value="'+upc.id_upc+'">' +
                            '<span class="custom-control-indicator"></span>' +
                            '</label>' +
                            '</div>' +
                            '</td>' +
                            '<td>'+upc.upc+'</td>' +
                            '<td>'+upc.nombre_comercial+'</td>' +
                            '<td>'+upc.marca+'</td>' +
                            '<td>'+upc.descripcion+'</td>' +
                            '<td>'+upc.laboratorio.laboratorio+'</td>' +
                            '</tr>';
                    });
                    contenido_tab += '</tbody></table></div>';
                    $('#upcs').append(contenido_tab);
                });
            }
        });
    else
        $.toaster({priority:'warning',
                title: '¡Alerta!',
                message:"Verifica la forma farmacéutica, la presentación y las sales",
                settings:{'timeout':8000,
                    'toaster':{'css':{'top':'5em'}}}
            }
        );
}

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
        buscaProductos();
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
    let tr = $(el).closest('tr');
    tr.fadeOut(400, function(){
        tr.remove().stop();
        buscaProductos();
    });
    $.toaster({priority:'success',
            title: '¡Éxito!',
            message:"Se ha eliminado la fila",
            settings:{'timeout':8000,
                'toaster':{'css':{'top':'5em'}}}
        }
    );
}

function cambio(element) {
    $(element).closest('li').tab('show');
}

function addEspecificacion(){
    let especificacionId = $('#especificacion').val();
    let especificacionText = $('#especificacion option:selected').text();
    let $tbody = $('#tbodyEspecificaciones');
    let i = $('#tbodyEspecificaciones > tr').length;
    let row_id = i > 0 ? +$('#tbodyEspecificaciones > tr:last').find('#index').val()+1 : 0;

    $tbody.append(
        '<tr>'+
        '<td>' + '<input type="hidden" id="index" value="'+row_id+'"><input class="especificacion" type="hidden" name="especificaciones['+row_id+'][fk_id_especificacion]" value="'+especificacionId+'">' + especificacionText + '</td>' +
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
    buscaProductos();
    $('[data-toggle]').tooltip();
}