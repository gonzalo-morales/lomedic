$(document).ready(function () {

    $('input[type=radio][name=fk_id_estatus]').change(function () {
        if($(this).val() == 4){//Si es autorizada
            $('#observaciones').attr('readonly','readonly');
            $('#observaciones').val('');
        }else{
            $('#observaciones').removeAttr('readonly');
        }
    });

});
