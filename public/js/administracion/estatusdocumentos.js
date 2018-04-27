$(document).ready(function () {
   $('#todos').on('click',function () {
       var arreglo = [];
       if($(this).is(':checked')){
           $('#tiposdocumentos option').each(function () {
               arreglo.push(this.value);
           });
           $('#tiposdocumentos').val(arreglo).trigger('change');
       }else{
           $('#tiposdocumentos').val([]).trigger('change');
       }
   });
   if(cantidad == $('#tiposdocumentos option').length){
       $('#todos').prop('checked',true);
   }

    $('#tiposdocumentos').on('change',function () {
        var uldiv = $(this).siblings('span.select2').find('ul');
        var count = uldiv.find('li').length - 1;//Para contar los seleccionados
        if(count == $('#tiposdocumentos option').length)
            $('#todos').prop('checked',true);
        else
            $('#todos').prop('checked',false);
    });

});