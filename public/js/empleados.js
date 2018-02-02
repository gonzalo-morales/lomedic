$('#fecha_nacimiento').pickadate({
    selectMonths: true, // Creates a dropdown to control month
    selectYears: 150, // Creates a dropdown of 3 years to control year
    format: 'yyyy-mm-dd',
    max:new Date()
  });
function activar_infonavit() {

    var on = $('#infonavit_activo:checked').val();
    if ($('#infonavit_activo:checked').val() == 'on')
    {$('#numero_infonavit').prop('disabled',false);}
    else
    {$('#numero_infonavit').prop('disabled',true);}
}