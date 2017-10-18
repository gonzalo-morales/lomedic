var a=[];
// Inicializar los datepicker para las fechas necesarias
$('.datepicker').pickadate({
    selectMonths: true, // Creates a dropdown to control month
    selectYears: 2, // Creates a dropdown of 2 years to control year
    min: new Date(2017,0,1),//Primero de enero del 2017
    format: 'yyyy-mm-dd'
});