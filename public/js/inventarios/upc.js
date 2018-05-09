$(document).ready(function () {  
    $('#addIndication').on('click', function () {
        addIndication();
    });
    $('#addPresentation').on('click', function () {
        addSalt();
    });
    $('#addMaterial').on('click',function(){
        addMaterial();
    });
    $(document).on('submit', function (e) {
        if(!$('#material_curacion').attr('checked')){
            if( $('#tbodyIndication tr').length == 0 || $('#tbodyPresentation tr').length == 0 )
            {
                e.preventDefault();
                mensajeAlerta("Para guardar el UPC se requiere que mínimo se agregue una Indicación terapéutica y una Presentación", "danger");
            }
        } else{
            if($('#tbodyMaterials tr').length == 0)
            {
                e.preventDefault();
                mensajeAlerta("Para guardar el UPC se requiere que mínimo se agregue una Especificación", "danger");
            }
        }
    });
    $('[data-toggle]').tooltip();
    $('.nav-link').on('click', function (e) {
        e.preventDefault();
        $('#clothing-nav li').each(function () {
            $(this).children().removeClass('active');
        });
        $('.tab-pane').removeClass('active').removeClass('show');
        $(this).addClass('active');
        let tab = $(this).prop('href');
        tab = tab.split('#');
        $('#' + tab[1]).addClass('active').addClass('show');
    });
    $('#material_curacion').on('change',function(){
        $('#tableIndicacion').toggle('slow');
        $('#tableSal').toggle('slow');
        $('#tableMaterial').toggle('slow');
    })
});

function addIndication(){
    let indicationId = $('#indicacion option:selected').val();
    let indicationText = $('#indicacion option:selected').text();
    let $tbody = $('#tbodyIndication');
    let i = $('#tbodyIndication > tr').length;
    let row_id = i > 0 ? +$('#tbodyIndication > tr:last').find('#index').val()+1 : 0;

    $tbody.append(
        '<tr>'+
        '<td>' + '<input type="hidden" id="index" value="'+row_id+'"><input type="hidden" name="relations[has][indicaciones]['+row_id+'][fk_id_indicacion_terapeutica]" value="'+indicationId+'">' + indicationText + '</td>' +
        '<td>' + '<button data-toggle="Eliminar" data-placement="top" title="Eliminar" data-original-title="Eliminar" type="button" class="text-primary btn btn_tables is-icon eliminar bg-white" data-delay="50" onclick="borrarFila(this)"><i class="material-icons">delete</i></button>' + '</td>' +
         +'</tr>'
    );
    mensajeAlerta("Elemento agregado con éxito","success");
    $('[data-toggle]').tooltip();
}

function addSalt(){
    let salId = $('#sal option:selected').val();
    let salText = $('#sal option:selected').text();
    let concentrationId = $('#concentracion option:selected').val();
    let concentrationText = $('#concentracion option:selected').text();
    let $tbody = $('#tbodyPresentation');
    let i = $('#tbodyPresentation > tr').length;
    let row_id = i > 0 ? +$('#tbodyPresentation > tr:last').find('#index').val()+1 : 0;

    $tbody.append(
        '<tr>'+
        '<td>' + '<input type="hidden" id="index" value="'+row_id+'"><input type="hidden" name="relations[has][presentaciones]['+row_id+'][fk_id_presentaciones]" value="'+concentrationId+'"><input type="hidden" name="relations[has][presentaciones]['+row_id+'][fk_id_sal]" value="'+salId+'">' + salText + '</td>' +
        '<td>' + concentrationText + '</td>' +
        '<td>' + '<button data-toggle="Eliminar" data-placement="top" title="Eliminar" data-original-title="Eliminar" type="button" class="text-primary btn btn_tables is-icon eliminar bg-white" data-delay="50" onclick="borrarFila(this)"><i class="material-icons">delete</i></button>' + '</td>' +
         +'</tr>'
    );
    mensajeAlerta("Elemento agregado con éxito","success");
    $('[data-toggle]').tooltip();
}

function addMaterial(){
    let indicationId = $('#indicacion option:selected').val();
    let indicationText = $('#indicacion option:selected').text();
    let $tbody = $('#tbodyMaterials');
    let i = $('#tbodyMaterials > tr').length;
    let row_id = i > 0 ? +$('#tbodyMaterials > tr:last').find('#index').val()+1 : 0;

    $tbody.append(
        '<tr>'+
        '<td>' + '<input type="hidden" id="index" value="'+row_id+'"><input type="hidden" name="especificaciones['+row_id+'][fk_id_especificacion]" value="'+indicationId+'">' + indicationText + '</td>' +
        '<td>' + '<button data-toggle="Eliminar" data-placement="top" title="Eliminar" data-original-title="Eliminar" type="button" class="text-primary btn btn_tables is-icon eliminar bg-white" data-delay="50" onclick="borrarFila(this)"><i class="material-icons">delete</i></button>' + '</td>' +
         +'</tr>'
    );
    mensajeAlerta("Elemento agregado con éxito","success");
    $('[data-toggle]').tooltip();
}

function appendToTable(values,tablebody){
    console.log(tablebody)
    let i = tablebody.child('tr').length;
    let row_id = i > 0 ? + tablebody.child('tr').last().find('#index').val()+1 : 0;
    for (const key in values) {
        if (values.hasOwnProperty(key)) {
            console.log(values[key]);
            // tBody.append(
            //     '<tr>'+
            //     '<td>' + '<input type="hidden" id="index" value="'+row_id+'"><input type="hidden" name="especificaciones['+row_id+'][fk_id_especificacion]" value="'+indicationId+'">' + indicationText + '</td>' +
            //     '<td>' + '<button data-toggle="Eliminar" data-placement="top" title="Eliminar" data-original-title="Eliminar" type="button" class="text-primary btn btn_tables is-icon eliminar bg-white" data-delay="50" onclick="borrarFila(this)"><i class="material-icons">delete</i></button>' + '</td>' +
            //      +'</tr>'
            // );
        }
    }
    mensajeAlerta("Elemento(s) agregado(s) con éxito","success");
    $('[data-toggle]').tooltip();
}

function borrarFila(el) {
    var tr = $(el).closest('tr');
    tr.fadeOut(400, function(){
      tr.remove().stop();
    })
    mensajeAlerta("Se ha eliminado la fila correctamente", "success");
  };

function mensajeAlerta(mensaje,tipo){
    var titulo = '';
    if(tipo == 'danger'){ titulo = '¡Error!'}
    else if(tipo == 'success'){titulo = '¡Correcto!' }
    $.toaster({priority:tipo,
            title: titulo,
            message:mensaje,
            settings:{'timeout':8000,
                'toaster':{'css':{'top':'5em'}}}
        }
    );
}