$(document).ready(function(){

    $('#marca').on('change', function(){
          var data = $('option:selected', this).data('url');

      $.ajax({
          url: data,
          dataType: 'json',
          success: function (data) {
              console.log(data);
              $('#modelos').find('option:gt(0)').remove();
              $.each(data, function (key, accion) {
                  var option = $('<option/>');
                  option.val(accion.id_modelo);
                  option.text(accion.modelo);

                  console.log(option);
                  $('#modelos').append(option);
              });
              $('#modelos ').material_select();
          },
          error: function () {
                Materialize.toast('<span><i class="material-icons">priority_high</i> No se completó el proceso</span>', 3000,'m_error');
          }
      });
  });
});
