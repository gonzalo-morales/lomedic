/**
 * Created by ihernandezt on 22/1/2018.
 */

var from_contrato = $('#fecha_nacimiento').pickadate({ selectMonths: true, selectYears: 3, format: 'yyyy-mm-dd' }).pickadate('picker');

$('#fk_id_afiliacion').on('change', function() {

   if($('#fk_id_afiliacion').val() != '' )
   {
       $('#id_afiliacion').val($('#fk_id_afiliacion').val());

       let token = document.querySelector("meta[name='csrf-token']").getAttribute("content");

           $.ajax({
               type: "POST",
               url: $(this).data('url'),
               data: {'fk_id_afiliacion':$(this).val(),'_token':token},
               dataType: "json",
               success:function(data) {
                   $('.dependientes').empty();
                   $.each(data, function(key, value) {
                        $('.dependientes').append('<tr>' +
                           '<td>'+value.id_afiliacion+'</td>' +
                           '<td>'+value.nombre+'</td>' +
                           '<td>'+value.genero+'</td>' +
                           '<td>'+value.parentesco+'</td>' +
                           '<td>'+value.fecha_nacimiento+'</td>' +
                           '</tr>');
                   });
               }
           });

   }
   else
   {
       $('#id_afiliacion').val('');
   }

});