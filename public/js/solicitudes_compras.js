//Inicializar los datepicker para las fechas necesarias
$('.datepicker').pickadate({
    //Cambiamos idiomas a español
    labelMonthNext: 'Siguiente mes',
    labelMonthPrev: 'Regresar mes',
    labelMonthSelect: 'Selecciona el mes',
    labelYearSelect: 'Selecciona el año',
    monthsFull: [ 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre' ],
    monthsShort: [ 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic' ],
    weekdaysFull: [ 'Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado' ],
    weekdaysShort: [ 'Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab' ],
    weekdaysLetter: [ 'D', 'L', 'M', 'M', 'J', 'V', 'S' ],
    today: 'Hoy',
    clear: 'Limpiar',
    close: 'Aceptar',
    selectMonths: true, // Creates a dropdown to control month
    selectYears: 3, // Creates a dropdown of 15 years to control year
    min: true,
    format: 'yyyy/mm/dd'
});
$(document).ready(function () {

    var data_empleado = $('#solicitante').data('url2');
    $.ajax({//Cargar los datos del empleado autenticado
        type:'GET',
        url: data_empleado,
        success: function (response) {
            $('#fk_id_solicitante').val(response);
        },
    });
    sucursal();//Cargar las sucursales del usuario

    let data_empleados = $('#solicitante').data('url');
    $.ajax({//Carga todos los empleados
       type:'GET',
       url: data_empleados,
       success: function (response) {
           $('#solicitante').autocomplete2({
               data: response,
           });
       },
    });
    $('#solicitante').change(function () {
        $('#fk_id_solicitante').val('');
        sucursal();//Caga los nuevos datos de la sucursal
    });

    $(':submit').attr('onclick','obtenerProductos()');
    //Inicializar tabla
    window.dataTable = new DataTable('#productos',{
        columns:[
            {
                select: [0,2,4,7,9,13],
                hidden: true,
                type: 'number'
            },
        ],
        fixedHeight: true,
        searchable: false,
    });
    dataTable.removeRows([0]);

    //Para obtener todos los skus
    let data_sku =$('#fk_id_sku').data('url');
    $.ajax({
       type:'GET',
        url: data_sku,
        success: function (response) {
            $('#fk_id_sku').autocomplete2({
               data: response,
            });
        }
    });
    $('#fk_id_sku').change(function () {
        codigosbarras();//Caga los nuevos datos del producto
    });

    let data_proyectos = $('#fk_id_proyecto').data('url');
    $.ajax({//Carga todos los proyectos
        type:'GET',
        url: data_proyectos,
        success: function (response) {
            $('#fk_id_proyecto').autocomplete2({
                data: response,
            });
        },
    });

    porcentaje();//Obtiene el porcentaje del valor por defecto
    $('#fk_id_impuesto').change(function () {//Cuando cambia el valor por defecto
        porcentaje();
        total_producto();
    });

    $('#precio_unitario').keyup(function(event) {
        var regex = /[^0-9\.]/g;
        this.value = this.value.replace(regex, '');
        total_producto();
    });

    $('#cantidad').keyup(function(event) {
        var regex = /[^0-9]/g;
        this.value = this.value.replace(regex, '');
        total_producto();
    });

});

function sucursal()
{
    $('#fk_id_sucursal').prop('disabled',true);//Deshabilitar
        let data_empleado = $('#fk_id_solicitante').data('url');
        // $('#fk_id_sucursal option').remove();//Limpiar
        $('#fk_id_sucursal').prop('disabled',true);//Deshabilitar

        if($('#fk_id_solicitante').val()=='')
            {var _url = data_empleado.replace('?id', $('#solicitante').data('id'))}
        else
            {var _url = data_empleado.replace('?id', $('#fk_id_solicitante').val())}

        $.ajax({
            url: _url,
            dataType: 'json',
            success: function (data) {
                removerOpciones('fk_id_sucursal');
                let option = $('<option/>');
                option.val(null);
                option.attr('disabled','disabled');
                option.attr('selected','selected');
                option.text('Selecciona una sucursal');
                $('#fk_id_sucursal').append(option);
                $.each(data, function (key, sucursal) {
                    let option = $('<option/>');
                    option.val(sucursal.id_sucursal);
                    option.text(sucursal.nombre_sucursal);
                    $('#fk_id_sucursal').append(option);
                });
                if(Object.keys(data).length ==0)
                {$('#fk_id_sucursal').prop('disabled',true)}
                else{$('#fk_id_sucursal').prop('disabled',false)}

                $('select').material_select();
            },
        });
}

function codigosbarras()
{
    let data_codigo = $('#fk_id_codigo_barras').data('url');
    $('#fk_id_codigo_barras').prop('disabled',true);//Deshabilitar

    var _url = data_codigo.replace('?id', $('#fk_id_sku').data('id'))

    $.ajax({
        url: _url,
        dataType: 'json',
        success: function (data) {
            removerOpciones('fk_id_codigo_barras');
            let option = $('<option/>');
            option.val(null);
            option.attr('disabled','disabled');
            option.attr('selected','selected');
            option.text('Selecciona un código de barras');
            $('#fk_id_codigo_barras').append(option);
            $.each(data, function (key, codigo) {
                let option = $('<option/>');
                option.val(codigo.id_codigo_barras);
                option.text(codigo.descripcion);
                $('#fk_id_codigo_barras').append(option);
            });
            if(Object.keys(data).length ==0)
            {$('#fk_id_codigo_barras').prop('disabled',true)}
            else{$('#fk_id_codigo_barras').prop('disabled',false)}

            $('select').material_select();
        }
    });
}

function porcentaje() {
    let data_impuesto = $('#fk_id_impuesto').data('url');
    $.ajax({
       type:'GET',
        url:data_impuesto.replace('?id',$('#fk_id_impuesto').val()),
        success: function (data) {
            $('#impuesto').val(data);
        }
    });
}

function total_producto() {
    //Obtener valores base para calcular el total
    let impuesto = $('#impuesto').val();
    let precio_unitario = $('#precio_unitario').val();
    let cantidad = $('#cantidad').val();

    //Calcular valores
    let subtotal = cantidad*precio_unitario;
    let impuesto_producto = (subtotal*impuesto)/100;

    $('#total').val(subtotal + impuesto_producto);
}

function agregarProducto() {

    var mensaje = '';

    if($('#fk_id_sku').data('id') == null || $('#fk_id_sku').data('id') < 1)
        mensaje = mensaje+"SKU\n";
    if($('#fk_id_codigo_barras option:selected').html()==null || $('#fk_id_codigo_barras option:selected').html()=='')
        mensaje = mensaje+"Código de barras\n";
    if($('#cantidad').val() == null || $('#cantidad').val() == '' || isNaN($('#cantidad').val()))
        mensaje = mensaje + "Cantidad\n";
    if(isNaN($('#precio_unitario').val()) | !($('#precio_unitario').val() > 0))
        mensaje = mensaje+"Precio unitario\n";
    if(isNaN($('#total').val()) || !($('#total').val() > 0) )
        mensaje = mensaje+"Total\n";
    if($('#fk_id_proyecto').data('id') == null || $('#fk_id_proyecto').data('id') < 1)
        mensaje = mensaje+"Proyecto\n";
    if($('#fecha_necesario').val() == '' || $('#fecha_necesario').val() == null)
        mensaje = mensaje+"Fecha";

    if(mensaje == '' || mensaje == null) {
        dataTable.import({
            type: "csv",
            data: $('#fk_id_sku').data('id') + "," + $('#fk_id_sku').val() + "," +
            $('#fk_id_codigo_barras').val() + "," + $('#fk_id_codigo_barras option:selected').html() + "," +
            1+","+$('#fk_id_proveedor').val() + "," +
            $('#cantidad').val() + "," +
            $('#fk_id_unidad_medida').val() + "," + $('#fk_id_unidad_medida option:selected').html() + "," +
            $('#fk_id_impuesto').val() + "," + $('#fk_id_impuesto option:selected').html() + "," +
            $('#precio_unitario').val() + "," +
            $('#total').val() + "," +
            $('#fk_id_proyecto').data('id') + "," + $('#fk_id_proyecto').val() + "," +
            $('#fecha_necesario').val() + "," +
            '<button class="tooltipped btn-flat teal lighten-5 halfway-fab waves-effect waves-light" ' +
            'type="button" data-tooltip="Eliminar" data-delay="50" onclick="borrarFila(this)">' +
            '<i class="material-icons">delete</i></button>'
        });
        limpiarFormulario();
    }else {
        alert("Verifica los siguientes campos:\n"+mensaje);
        return false;
    }
}

function limpiarFormulario() {
    $('#fk_id_sku').val('');
    removerOpciones('fk_id_codigo_barras');
    $('#fk_id_proveedor').val('');
    $('#cantidad').val('1');
    $('#precio_unitario').val('0');
    $('#total').val('0');
    $('#fk_id_proyecto').val('');
}

function borrarFila(el) {
    console.log( $(el).parents('tr').data('datarow') );
    dataTable.removeRows([$(el).parents('tr').data('datarow')]);
}

function obtenerProductos() {

    var columns = dataTable.columns([0, 2, 4, 7, 9, 13]);
    columns.show();
    var prueba = dataTable.export({
        type: 'json',
        download: false,
        skipColumn: [1, 3, 5, 8, 10, 14, 15]//Se salta las columnas que no se necesitan en la base de datos
    });
    //Las vuelve a esconder para que el usuario no las vea si regresa a la página
    dataTable.columns([0, 2, 4, 7, 9, 13]).hide();

    $.ajax({
        type:'POST',
        url: $('#productos').data('url'),
        dataType: 'json',
        data: prueba
    })
}


function removerOpciones(id) {
    document.getElementById(id).options.length = 0;
}
