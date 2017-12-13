$(document).ready(function () {

  //Iniciamos los selects, fechas y tooltips
  initSelect2();
  initDates();
  $('[data-toggle]').tooltip();

  //Funciones para ingresar valores de acuerdeo al empleado seleccionado (puesto, departamento, sucursal)
  $('#fk_id_empleado').on('change', function() {
    var idempleado = $('#fk_id_empleado option:selected').val();
    var _url = $(this).data('url');


    $.ajax({
        async: true,
        url: _url,
        data: {'param_js':js_departamento,$id_empleado:idempleado},
        dataType: 'json',
            success: function (data) {
              if(data[0].departamento == null)
                $('#departamento').val('');
              else
                $('#departamento').val(data[0].departamento.descripcion);
        }
    });

    $.ajax({
        async: true,
        url: _url,
        data: {'param_js':js_puesto,$id_empleado:idempleado},
        dataType: 'json',
            success: function (data) {
              if(data[0].puesto == null)
                $('#puesto').val('');
              else
                $('#puesto').val(data[0].puesto.descripcion);
        }
    });

    $.ajax({
        async: true,
        url: _url,
        data: {'param_js':js_sucursal,$id_empleado:idempleado},
        dataType: 'json',
            success: function (data) {
              if(data[0].sucursales == null)
                $('#sucursal').val('');
              else
                $('#sucursal').val(data[0].sucursales.sucursal);
        }
    });
    
  });

  //Funciones para ingresar valores de acuerdo al impuesto seleccionado
  $('#fk_id_impuesto').on('change', function() {
    var idimpuesto = $('#fk_id_impuesto option:selected').val();
    var _url = $(this).data('url');


    $.ajax({
        async: true,
        url: _url,
        data: {'param_js':js_impuesto,$id_impuesto:idimpuesto},
        dataType: 'json',
            success: function (data) {
              if(data[0].tasa_o_cuota == null)
                $('#impuesto').val('');
              else
                $('#impuesto').val(data[0].tasa_o_cuota);

              calcular();
        }
    });
  });

  //Realizamos función de cálculo en caso de que el usuario cambie el subtotal
  $('#subtotal_fac').on('keyup',function(){
    calcular();
  });

  //FUNCIÓN PARA INICIAR LOS SELECTS
  function initSelect2(){
    $('#fk_id_tipo').select2({
      placeholder: "Seleccione el concepto de la nota",
      allowClear: true
    });
    $('#fk_id_empleado.select2').select2({
      placeholder: "Seleccione el empleado",
      allowClear: true
    });
  };

  // FUNCIÓN PARA INICIAR LAS FECHAS Y SUS RESPECTIVAS FUNCIONES
  function initDates(){
    $('#fecha').pickadate({
      selectMonths: true, // Creates a dropdown to control month
      selectYears: 3, // Creates a dropdown of 3 years to control year
      min: true,
      format: 'yyyy-mm-dd'
    });

    //Función para calcular días
    $("#periodo_inicio").pickadate({
      selectMonths: true, // Creates a dropdown to control month
      selectYears: 3, // Creates a dropdown of 3 years to control year
      min: false,
      format: 'yyyy-mm-dd',
      //Función para indicar que la segunda fecha tome el valor de la primera
      onSet: function () {
        $('#periodo_fin').pickadate('picker').set('min', $('#periodo_inicio').pickadate('picker').get('select'));
      },
    });

    $("#periodo_fin").pickadate({
      selectMonths: true, // Creates a dropdown to control month
      selectYears: 3, // Creates a dropdown of 3 years to control year
      min: false,
      format: 'yyyy-mm-dd',
      onSet: function () {
        //Corremos la función para ingresar los días
        ingresarDias();
      }
    });

    //Función para ingresar y calcular los días de acuerdo a las dos fechas seleccionadas
    function ingresarDias(){
      var fecha1 = $('#periodo_inicio').pickadate('picker');
      var fecha2 = $('#periodo_fin').pickadate('picker');
      var datoFinal = $('#total_dias');
      var fTotal = "";

      var start= fecha1.get('select','d');
      var end= fecha2.get('select','d');
      var fTotal = (end - start);

      //Función para indicar que la segunda fecha tome el valor de la primera
      fecha1.on('set', function(event) {
        if ( 'select' in event ) {
          fecha2.start().clear().set('min', fecha1.get('select'));
        }
        if ( 'clear' in event ) {
          fecha2.clear().set('min', false).stop();
          $('#periodo_fin').prop('readonly', true);
        }
      });
      //Condición para ingresar el total
      if(fTotal >= 0)
        datoFinal.val(fTotal);
      else
        datoFinal.val("N/A");
    };
  };

/*
  --- FORMULARIO DE DETALLE PARA FACTURAS ---
*/
  $('#saveTable').click(function() {
    //Prevenimos que genere la acción default
    validateDetail()

    //Confición para validar
    if($('#form-model').valid())
    {
      var formulario = $('#detalle-form');
      var oveData = formulario.serializeArray();
      //hacemos un for each para obtener cada valor por individual
      $.each(oveData, function(i, val){
      // Convertimos el array en objeto JSON
        returnArray = {};
        for (var i = 0; i < oveData.length; i++){
            returnArray[oveData[i]['name']] = oveData[i]['value'];
          }
        return returnArray;
      });

      agregarFilaDetalle();
      limpiarCampos();
    } else {
        $.toaster({priority : 'danger',title : '¡Error!',message : 'Hay campos que requieren de tu atención',
          settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
    }
    // limpiamos el formulario
    sumarTodosLosTotales();
  });

  //FUNCIÓN PARA INGRESAR VALORES A LA TABLA
  function agregarFilaDetalle(){
  var tableData = $('table > tbody');
  var i = $('#detalle-form-body > tr').length;
  var row_id = i > 0 ? +$('#detalle-form-body > tr:last').find('#index').val()+1 : 0;
  var tipo = $('#fk_id_tipo option:selected').text();
  var impuesto = $('#fk_id_impuesto option:selected').text();
  //Ingresamos estos datos en la tabla
  tableData.append(
    '<tr><td>'+ returnArray.folio_fac + '<input type="hidden" id="index" value="'+row_id+'"></input><input type="hidden" name="relations[has][detalle]['+row_id+'][folio]" value="'+returnArray.folio_fac+'"/>'+'</td>'
    + '<td>'+ tipo + '<input type="hidden" name="relations[has][detalle]['+row_id+'][fk_id_tipo]" value="'+returnArray.fk_id_tipo+'"/>'+'</td>'
    + '<td>'+ '$' +returnArray.subtotal_fac + '<input class="subtotal" type="hidden" name="relations[has][detalle]['+row_id+'][subtotal]" value="'+returnArray.subtotal_fac+'"/>'+'</td>'
    + '<td>'+ impuesto + '<input type="hidden" name="relations[has][detalle]['+row_id+'][fk_id_impuesto]" value="'+returnArray.fk_id_impuesto+'"/>'+'</td>'
    + '<td>'+ '$' +returnArray.total_fac + '<input class="total" type="hidden" name="relations[has][detalle]['+row_id+'][total]" value="'+returnArray.total_fac+'"/>'+'</td>'
    + '<td>'+ '<button data-toggle="Eliminar" data-placement="top" title="Eliminar" data-original-title="Eliminar" type="button" class="text-primary btn btn_tables is-icon eliminar bg-white" data-delay="50" onclick="borrarFila(this)"><i class="material-icons">delete</i></button>'+'</td></tr>'
    );
    $.toaster({priority : 'success',title : '¡Éxito!',message : 'Factura/Nota agregada con éxito',
      settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}
    });
  };

  //FUNCIÓN PARA VALIDAR
  function validateDetail() {
    $('#folio_fac').rules('add',{
      required: true,
      messages:{
        required: 'Ingresa el número de folio o número de la nota'
      }
    });
    $('#fk_id_tipo').rules('add',{
      required: true,
      messages:{
        required: 'Es necesario seleccionar el concepto'
      }
    });
    $('#fk_id_impuesto').rules('add',{
      required: true,
      messages:{
        required: 'Es necesario seleccionar el tipo de impuesto'
      }
    });
    $.validator.addMethod('subtotal',function (value,element) {
        return this.optional(element) || /^\d{0,6}(\.\d{0,2})?$/g.test(value);
    },'El subtotal no tiene un formato´válido');
    $.validator.addMethod( "greaterThan", function( value, element, param ) {

      if ( this.settings.onfocusout ) {
          $(element).addClass( "validate-greaterThan-blur" ).on( "blur.validate-greaterThan", function() {
              $( element ).valid();
          } );
      }

        return value > param;
    }, "Ingresa un valor mayor a 0" );
    $('#subtotal_fac').rules('add',{
        required: true,
        number: true,
        subtotal:true,
        greaterThan:0,
        messages:{
            required: 'Para continuar es necesario un subtotal',
            number: 'El campo debe ser un número',
            greaterThan: 'El número debe ser mayor a 0',
        }
    });
  };

  //FUNCIÓN PARA LIMPIAR LOS CAMPOS
  function limpiarCampos() {
    $('#fk_id_tipo').val(0).select2({
      placeholder: "Seleccione el concepto de la nota",
      allowClear: true
    });
    $('#fk_id_impuesto').val(0)
    $('#folio_fac').val('');
    $('#subtotal_fac').val('');
    $('#total_fac').val('');
    $('#impuesto').val(0);
    //Eliminar reglas de validación detalle
    $('#fk_id_tipo').rules('remove');
    $('#fk_id_impuesto').rules('remove');
    $('#folio_fac').rules('remove');
    $('#fk_id_impuesto').rules('remove');
    $('#subtotal_fac').rules('remove');
  };

});

//FUNCIÓN PARA LIMPIAR LA FILA
function borrarFila(el) {
  var tr = $(el).closest('tr');
  //Volvemos a calular el total y subtotal general de todos los totales y subtotales en el detalle
  var trTotal = $(el).parent().parent().find('input.total').val();
  var trSubtotal = $(el).parent().parent().find('input.subtotal').val();
  var hiddenTotal = $('#total_detalles').val();
  var hiddenSubtotal = $('#subtotal_detalles').val();
  var overallTotal = hiddenTotal - trTotal;
  var overallSubtotal = hiddenSubtotal - trSubtotal;
  $('#total_detalles').val(overallTotal.toFixed(2));
  $('#subtotal_detalles').val(overallSubtotal.toFixed(2));

  tr.fadeOut(400, function(){
    tr.remove().stop();
  })
  $.toaster({priority : 'success',title : '¡Advertencia!',message : 'Se ha eliminado la fila correctamente',
      settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
};


//FUNCIÓN PARA CALULAR Y SUMAR TODO (TOTALES DE DETALLE)
function sumarTodosLosTotales(){
  var tdTotal = $('#detalle-form-body > tr > td > input.total');
  var tdSubtotal = $('#detalle-form-body > tr > td > input.subtotal');

  //Usamos la función de array.reduce.call para ir sumando los valores
  var total = [].reduce.call(tdTotal, function(a, b) {
      return a + parseFloat($(b).val());
  }, 0);
  var subtotal = [].reduce.call(tdSubtotal, function(a, b) {
      return a + parseFloat($(b).val());
  }, 0);

  $('#total_detalles').val(total.toFixed(2));
  $('#subtotal_detalles').val(subtotal.toFixed(2));
}

//FUNCIÓN PARA CALCULAR EL TOTAL
function calcular(){
  var subtotal = +$('#subtotal_fac').val();
  var impuesto = +$('#impuesto').val();
  var totalImpuesto = subtotal * impuesto;
  var total = subtotal + totalImpuesto
  $('#total_fac').val(total.toFixed(2));
}