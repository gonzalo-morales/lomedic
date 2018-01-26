/**
 * Created by ihernandezt on 22/1/2018.
 */
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
                           '<td>'+data[key].id+'</td>' +
                           '<td>'+data[key].nombre+'</td>' +
                           '<td>'+data[key].genero+'</td>' +
                           '<td>'+data[key].parentesco+'</td>' +
                           '<td>'+data[key].fecha_nacimiento+'</td>' +
                           '</tr>');
                       // $('#fk_id_proyecto').append('<option value="'+ key +'">'+ value +'</option>');
                   });
                   // $('#fk_id_proyecto').val('');
               }
           });

   }
   else
   {
       $('#id_afiliacion').val('');
   }

});