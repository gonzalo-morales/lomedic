$(document).ready(function(){
  $(".button-collapse").sideNav();// Inicia sideNav para navegación izquierdo
  $('.tooltipped').tooltip({delay: 50}); // Iniciamos tooltips
  $('.chips-initial').material_chip({  //iniciamos chips
    data: [{
      tag: 'Escribe y teclea Enter para crear',
    }],
  });
  $('#atendio.chips-initial').material_chip({  //iniciamos chips para el ID: #atendio (nuevaCompra_nuevo.html)
    data: [{
      tag: 'Escribe y teclea Enter para crear',
    }],
  });
  $('#emails.chips-initial').material_chip({  //iniciamos chips para el ID: #atendio (nuevaCompra_nuevo.html)
    data: [{
      tag: 'Escribe y teclea Enter para crear',
    }],
  });
  $('#phones.chips-initial').material_chip({  //iniciamos chips para el ID: #atendio (nuevaCompra_nuevo.html)
    data: [{
      tag: 'Escribe y teclea Enter para crear',
    }],
  });
  $('#checklistCliente.chips-initial').material_chip({  //iniciamos chips para el ID: #checklistCliente (nuevaCompra_nuevo.html)
    data: [{
      tag: 'Escribe y teclea Enter para crear',
    }],
  });
  $('#datos.chips-initial').material_chip({  //iniciamos chips para el ID: #datos en ayuda (index.html)
    data: [{
      tag: 'Escribe y teclea Enter para crear',
    }],
  });
  $('.chips-placeholder').material_chip({
    placeholder: 'teclea Enter para crear',
    secondaryPlaceholder: '+Enter',
  });
  $('select').material_select(); //iniciamos el select
  $(".help-collapse").sideNav({ // Inicia sideNav para navegación derecho de ayuda
    edge:'right', //para que lo muestre a la derecha
    }
  );
  $('.modal').modal(); //Iniciamos modales
  $('.dropdown-button').dropdown({ //iniciamos el dropdown
    inDuration: 300,
    outDuration: 225,
    //hover: true, // Se activa al hacer hover
    click: true, // se activa al hacer clic
    belowOrigin: true, // Se mostrará hacia abajo del elemento
    alignment: 'right' // Alineado a la derecha
    }
  );
  $('.timepicker').pickatime({
    default: 'now', // Set default time
    fromnow: 0,       // set default time to * milliseconds from now (using with default = 'now')
    twelvehour: false, // Use AM/PM or 24-hour format
    //Lineas para cambiar el idioma
    donetext: 'ACEPTAR', // text for done-button
    cleartext: 'LIMPIAR', // text for clear-button
    canceltext: 'CANCELAR', // Text for cancel-button
    autoclose: false, // automatic close timepicker
    ampmclickable: true, // make AM PM clickable
    aftershow: function(){} //Function for after opening timepicker  
  });
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
    selectYears: 100 // Creates a dropdown of 15 years to control year
  });
  $('input.autocomplete').autocomplete({ //Iniciamos el autocomplete
    data: { //agregamos data de ejemplo, el null es para decir que no llevará una imagen o ícono a un lado del nombre...
      "Apple": null,
      "Microsoft": null,
      "Google": null,
    },
    limit: 5, // The max amount of results that can be shown at once. Default: Infinity.
    onAutocomplete: function(val) {
    // Callback function when value is autcompleted.
    },
    minLength: 1, // The minimum length of the input for the autocomplete to start. Default: 1.
  });
  $('.chips-autocomplete').material_chip({
    autocompleteOptions: {
      data: {
        'Apple': null,
        'Microsoft': null,
        'Google': null
      },
      limit: Infinity,
      minLength: 1
    }
  });
}); //aquí termina el function