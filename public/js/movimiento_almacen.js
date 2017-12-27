$(document).ready(function () {
  //Iniciamos los selects, fechas y tooltips
  initSelect2();
  $('[data-toggle]').tooltip();

  /*---------------FUNCIONES OBTENER SKU Y MOSTRARLO DE MANERA SEXY---------------*/
  $('#fk_id_sucursal').on('change', function() {
    $('#fk_id_almacen').empty();
    $('#fk_id_sku').empty();
    $('#fk_id_upc').empty();
    $('#loadingalmacenes').show();
    var idsucursal = $('#fk_id_sucursal option:selected').val();
    var _url = $('#fk_id_almacen').data('url');
    $.ajax({
      async: true,
      url: _url,
      data: {'param_js':js_almacen,$fk_id_sucursal:idsucursal},
      dataType: 'json',
    }).done(function (data) {
      var options = [];
      /* Si hay resultados... */
      if (data.length > 0) {
        options.push('<option value="0" selected disabled>Selecciona el Almacén que requiera...</option>'); 
        for (var i = 0; i < data.length; i++) {
          options.push('<option data-ubicaciones=\''+ JSON.stringify(data[i]) +'\' value="' + data[i].id_almacen + '">' + data[i].almacen + '</option>');
        };
      //Agregamos todo al select2
      $('#fk_id_almacen').append(options.join(''));
      $('#loadingalmacenes').hide();
      $('#fk_id_almacen').select2({
        placeholder: 'Seleccione un Almacén',
        disabled:false,
      });
      } else {
        $.toaster({priority : 'warning',title : '¡Lo sentimos!',message : 'No hay almacenes registrados en esta sucursal. Intenta con otra Sucursal.',
          settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
        $('#loadingalmacenes').hide();
        $('#fk_id_almacen').select2({
          placeholder: 'No hay almacenes en la sucursal :(...',
          disabled:true,
        });
      }
    })
  }); // <-- Aquí termina el onChange de sucursales

  $('#fk_id_almacen').on('change',function(){
    $('#fk_id_sku').empty();
    $('#fk_id_upc').empty();
    $('#loadingskus').show();
    var idalmacen = $('#fk_id_almacen option:selected').val();
    $.get($('#fk_id_sku').data('url'), {'param_js':js_sku,$fk_id_almacen:idalmacen}, function(data){
      var dataUbicaciones = JSON.parse($('#fk_id_almacen option:selected').data('data').element.dataset.ubicaciones).ubicaciones;
      var options = [];
      /* Si hay resultados... */
      if (data.length > 0) {
        options.push('<option value="0" selected disabled>Selecciona el SKU que requiera...</option>'); 
        for (var i = 0; i < data.length; i++) {
          options.push('<option data-sku=\''+ JSON.stringify(data[i]) +'\' value="' + data[i].id_stock + '">' + data[i].sku.sku + '</option>');
        };
      //Agregamos todo al select2
      $('#fk_id_sku').append(options.join(''));
      $('#loadingskus').hide();
      $('#fk_id_sku').select2({
        escapeMarkup: function (markup) { return markup; },
        placeholder: 'Seleccione el SKU que requiera del inventario',
        templateResult: formatSku,
        templateSelection: formatSkuSelection,
        disabled:false,
      });
      } else {
        $.toaster({priority : 'warning',title : '¡Lo sentimos!',message : 'No hay SKU(s) registrado(s) en este almacen. Le recomendamos intentar con otro Almacén.',
          settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
        $('#loadingskus').hide();
        $('#fk_id_sku').select2({
          placeholder: "No encontramos SKUs :(...",
          disabled:true
        });
      }
      //Mostramos los valores de upc al seleccionar el sku
      $('#fk_id_sku').on('change',function(){
        $('#loadingupcs').show();
        $('#fk_id_upc').empty();
        var options = [];
        /* Si hay resultados */
        if (data.length > 0) {
          for (var i = 0; i < data.length; i++) {
            //Validamos que sea el mismo id del valor seleccionado ;)
            if($(this).val() == data[i].id_stock){
              options.push('<option data-upc-name="'+ data[i].upc.nombre_comercial +'" data-upc-desc="'+ data[i].upc.descripcion +'" value="' + data[i].upc.id_upc + '">' + data[i].upc.upc + '</option>');
            }
          };
        } else {
          $.toaster({priority : 'warning',title : '¡Lo sentimos!',message : 'No hay UPC(s) registrado(s) en este SKU. Le recomendamos intentar con otro SKU.',
            settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
          $('#loadingupcs').hide();
          $('#fk_id_upc').select2({
            placeholder: "No encontramos SKUs :(...",
            disabled:true
          });
        }
        $('#fk_id_upc').append(options.join(''));
        $('#loadingupcs').hide();
        $('#fk_id_upc').select2({
          escapeMarkup: function (markup) { return markup; },
          placeholder: 'Seleccione el SKU que requiera del inventario',
          templateResult: formatUpc,
          templateSelection: formatUpcSelection,
          disabled:false,
          multiple:true,
          allowClear:true,
          tags: true,
          tokenSeparators: [',', ' ']
        });
      });// <-- Aquí termina el onChange de sku
    })
  }) // <-- Aquí termina el onChange de almacen


  // FUNCIONES PARA FORMATO DEL RESULTADO EN EL SELECT2 de SKU
  function formatSku (sku) {
    if (sku.element && sku.element.dataset.sku) {
      //Variable para fecha
      var fechaActual = new Date();
      var dd = fechaActual.getDate();
      var mm = fechaActual.getMonth()+1; //January is 0!
      var yyyy = fechaActual.getFullYear();

      if(dd<10) {
          dd = '0'+dd
      } 

      if(mm<10) {
          mm = '0'+mm
      } 

      fechaActual = mm + '/' + dd + '/' + yyyy;

      // theme here ...
      var data = JSON.parse(sku.element.dataset.sku)
      //Condicionamos la fecha para advertir al usuario de la caducidad
      if(fechaActual > data.fecha_caducidad){
        var claseFecha = 'text-warning';
        var mensaje = "¡Cuidado! verifica la caducidad del producto: " + data.fecha_caducidad;
      } else{
        var claseFecha = 'text-secondary';
        var mensaje = data.fecha_caducidad;
      }
      //Generamos nuestro template
      var markup = "<div class='select2-result-pers clearfix'>" +
        "<div class='select2-result-pers__avatar'><img src='img/sku.png'/></div>" +
        "<div class='select2-result-pers__meta'>" +
        "<div class='select2-result-pers__text'>" + sku.text + "</div>";

      //Condiciones para el caso de que exista el dato
      if (data.sku.descripcion) {
        markup += "<div class='select2-result-pers__descripcion'>" + data.sku.descripcion + "</div>";
      }
      if (!data.stock) {
        markup += "<div class='select2-result-pers__statistics'>" +
        "<div class='select2-result-pers__presentacion text-danger'><i class='material-icons align-middle'>label</i> No hay en stock, seleciona otro SKU</div>" +
        "</div>" +
        "</div></div>";
      } else {
        markup += "<div class='select2-result-pers__statistics'>" +
        "<div class='select2-result-pers__presentacion text-success mr-3'><i class='material-icons align-middle'>shopping_basket</i> " + data.stock + "</div>" +
        "<div data-position='bottom' data-delay='50' data-toggle='"+mensaje+"' title='"+mensaje+"' class='select2-result-pers__presentacion"+" "+claseFecha+" "+"mr-3'><i class='material-icons align-middle'>today</i> " + data.fecha_caducidad + "</div>" +
        "<div class='select2-result-pers__presentacion'><i class='material-icons align-middle'>label</i> " + data.lote + "</div>" +
        "</div>" +
        "</div></div>";

        return markup;
      }
    }
    return sku.text;
  }
  function formatSkuSelection (sku) {
    return sku.text;
  }

  // FUNCIONES PARA FORMATO DEL RESULTADO EN EL SELECT2 de UPC
  function formatUpc (upc) {
    if (upc.element && upc.element.dataset) {
      // theme here ...
      var data = upc.element.dataset
      //Generamos nuestro template
      var markup = "<div class='select2-result-pers clearfix'>" +
        "<div class='select2-result-pers__avatar'><img src='img/upc.png'/></div>" +
        "<div class='select2-result-pers__meta'>" +
        "<div class='select2-result-pers__text'>" + upc.text + "</div>";

      //Condiciones para el caso de que exista el dato
      if (data.upcName) {
        markup += "<div class='select2-result-pers__descripcion'>" + data.upcName + "</div>";
      }
      if (!data.upcDesc) {
        markup += "<div class='select2-result-pers__statistics'>" +
        "<div class='select2-result-pers__presentacion text-danger'><i class='material-icons align-middle'>label</i> ¡Lo siento! al parecer no existe una descripción del producto</div>" +
        "</div>" +
        "</div></div>";
      } else {
        markup += "<div class='select2-result-pers__statistics'>" +
        "<div class='select2-result-pers__presentacion text-success mr-3'><i class='material-icons align-middle'>info</i> " + data.upcDesc + "</div>" +
        "</div>" +
        "</div></div>";

        return markup;
      }
    }
    return upc.text;
  }
  function formatUpcSelection (upc) {
    return upc.text;
  }

  //FUNCIÓN PARA INICIAR LOS SELECTS
  function initSelect2(){
    $('#fk_id_sucursal.select2').select2({
      placeholder: "Seleccione la sucursal",
      disabled: false
    });
    $('#fk_id_sku.select2').select2({
      placeholder: "Para iniciar es necesario indicar la Ubicación",
      disabled: true
    });
    $('#fk_id_upc.select2').select2({
      placeholder: "Para iniciar es necesario indicar la Ubicación",
      disabled: true
    });
    $('#fk_id_almacen.select2').select2({
      placeholder: "Para iniciar es necesario indicar la Ubicación",
      disabled: true
    });
  };
/*
  --- FORMULARIO DE DETALLE PARA MOVIMIENTO ALMACEN ---
*/
  $('#saveTable').click(function() {
    //Prevenimos que genere la acción default
    validateDetail();
    //Confición para validar
    if($('#form-model').valid()){
      // noRepeatUpc()
      agregarFilaDetalle();
    // limpiamos el formulario
      limpiarCampos();
    }else {
        $.toaster({priority : 'danger',title : '¡Error!',message : 'Hay campos que requieren de tu atención',
          settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
    }
  });

  //FUNCIÓN PARA INGRESAR VALORES A LA TABLA
  function agregarFilaDetalle(){
  var tableData = $('table > tbody');
  var i = $('#detalle-form-body > tr').length;
  var row_id = i > 0 ? +$('#detalle-form-body > tr:last').find('#index').val()+1 : 0;
  var sku = $('#fk_id_sku option:selected').text();
  var upc = $('#fk_id_upc option:selected').text();
  tableData.removeClass('no-data')
  //$('#fk_id_sku option:selected').data('sku').sku -> accede a la info del sku
  var $campoSkuData = $('#fk_id_sku option:selected').data('data');
  var skuDataSet = JSON.parse($('#fk_id_sku option:selected').data('data').element.dataset.sku);
  var skuData = $('#fk_id_sku option:selected').data('sku').sku;
  var upcData = $('#fk_id_upc').select2('data');
  var almacenData = $('#fk_id_sku option:selected').data('sku').almacen;
  var ubicacionData = $('#fk_id_sku option:selected').data('sku').ubicacion;

  // console.log($campoSkuData);
  // console.log(skuDataSet);
  // console.log(skuData);
  // console.log(upcData);
  // console.log(almacenData);
  // console.log(ubicacionData);

  //Imprimir cada upc agregado
  if (upcData.length > 0) {
    // console.log(upcData.length)
    for (var i = 0; i < upcData.length; i++) {
      tableData.append(
          //fk_id_sku y id_stock
        '<tr><th>'+ '<img style="max-height:40px" src="img/sku.png" alt="sku"/> ' + sku +'<input type="hidden" id="index" value="'+row_id+'"><input type="hidden" name="relations[has][detalle]['+row_id+'][fk_id_stock]" value="'+skuDataSet.id_stock+'"/><input type="hidden" name="relations[has][detalle]['+row_id+'][fk_id_sku]" value="'+skuDataSet.fk_id_sku+'"</th>' +
        //fk_id_upc(s)
        '<td>'+ '<img style="max-height:40px" src="img/upc.png" alt="upc"/> ' + upcData[i].text + '<input class="upc-unique" type="hidden" name="relations[has][detalle]['+row_id+'][fk_id_sku]" value="'+upcData[i].id+'"/>'+'</td>' +
        //lote
        '<td>'+ '<div class="input-group"><span class="input-group-addon">'+ '<i class="material-icons align-middle">label</i> ' +'</span><input type="text" class="form-control" name="relations[has][detalle]['+row_id+'][lote]" value="'+skuDataSet.lote+'"/></div>' + '</td>' +
        //fecha_caducidad
        '<td class="align-middle">'+ '<i class="material-icons align-middle">today</i> ' +skuDataSet.fecha_caducidad +'<input type="hidden" name="relations[has][detalle]['+row_id+'][fecha_caducidad]" value="'+skuDataSet.fecha_caducidad+'"/>' + '</td>' +
        //fk_id_almacen
        //fk_id_ubicacion
        '<td class="align-middle">'+ almacenData.almacen +' / '+ ubicacionData.ubicacion +'</td>' +
        //cantidad-stock
        '<td class="align-middle">'+ '<i class="material-icons align-middle">shopping_basket</i> ' + skuDataSet.stock +'<input  type="hidden" value="'+skuDataSet.stock+'"/>' + '</td>' +
        //fk_id_almacen
        //fk_id_ubicacion
        '<td>'+ '<select name="relations[has][detalle]['+row_id+'][fk_id_ubicacion]" class="form-control ubicaciones select2 custom-select"></select>' + '</td>' +
        //cantidad-stock
        '<td>'+ '<div class="input-group"><span class="input-group-addon">'+ '<i class="material-icons align-middle">shopping_basket</i> ' +'</span><input type="text" style="max-width: 5em;" class="form-control" name="relations[has][detalle]['+row_id+'][stock]" value="'+skuDataSet.stock+'"/></div>' + '</td>' +
        //acciones
        '<td>'+ '<button data-toggle="Eliminar" data-placement="top" title="Eliminar" data-original-title="Eliminar" type="button" class="text-primary btn btn_tables is-icon eliminar" style="background:none;" data-delay="50" onclick="borrarFila(this)"><i class="material-icons">delete</i></button>'+'</td></tr>'
      );
    };
  }
    $.toaster({priority : 'success',title : '¡Éxito!',message : 'SKU/UPC(s) agregados con éxito, ahora puedes editar la nueva ubicación',
      settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}
    });
  };

  //FUNCIÓN PARA VALIDAR
  function validateDetail() {
    $('#fk_id_sucursal').rules('add',{
      required: true,
      messages:{
        required: 'Para iniciar es necesario ingresar la sucursal'
      }
    });
    $('#fk_id_almacen').rules('add',{
      required: true,
      messages:{
        required: 'Es necesario seleccionar el almacen para posteriormente agregar UPC y SKU'
      }
    });
    $('#fk_id_sku').rules('add',{
      required: true,
      messages:{
        required: 'Es necesario seleccionar el SKU para posteriormente agregar el UPC'
      }
    });
    $('#fk_id_upc').rules('add',{
      required: true,
      messages:{
        required: 'Es necesario seleccionar el UPC para posteriormente agregar a la tabla los datos'
      }
    });
    // $('#stock').rules('add',{
    //     required: true,
    //     number: true,
    //     messages:{
    //         required: 'Para continuar es necesario el stock',
    //         number: 'El campo debe ser un número',
    //         greaterThan: 'El número debe ser mayor a 0',
    //     }
    // });
  };

  //FUNCIÓN PARA LIMPIAR LOS CAMPOS
  function limpiarCampos() {
    //Eliminar reglas de validación detalle
    $('#fk_id_almacen').rules('remove');
    $('#fk_id_sku').rules('remove');
    $('#fk_id_upc').rules('remove');
  };

  // //FUNCIÓN PARA VERIFICAR QUE NO SE REPITA EL UPC
  // function noRepeatUpc(){
  //   if($('table > tbody').hasClass('no-data')){
  //     agregarFilaDetalle();
  //   }else{
  //     $('.upc-unique').each(function(i, el){
  //       if(el.value == $('#fk_id_upc option:selected').val()){
  //         alert('Se parecen no mames')
  //       }else{
  //         agregarFilaDetalle();
  //       }
  //     })
  //   }
  // }

});// <-- Aquí termina el document.ready

//FUNCIÓN PARA LIMPIAR LA FILA
function borrarFila(el) {
  var tr = $(el).closest('tr');
  //Volvemos a calular el total
  var totalProductos = $('#total_productos').val();
  totalProductos -= 1
  tr.fadeOut(400, function(){
    tr.remove().stop();
  })
  $.toaster({priority : 'success',title : '¡Advertencia!',message : 'Se ha eliminado la fila correctamente',
      settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
};
