/**
 * Created by ihernandezt on 03/08/2017.
 */


function accionesPerfil(profiel)
{

    var profiel_id = profiel.split('_');

    if($("#perfil_check_"+profiel_id[1]).attr('checked') )
    {
        $("#perfil_check_"+profiel_id[1]).attr('checked', false);
    }
    else
    {
        $("#perfil_check_"+profiel_id[1]).attr('checked', true);
    }


    $.each(profiles_permissions,function ()
    {

        if(this.fk_id_perfil == profiel_id[1])
        {
            var id_combo = '#accion_'+this.id_modulo_accion;

            if($(id_combo).prop("checked") == true){
                $(id_combo).prop("checked",false);
            }
            else{
                $(id_combo).prop("checked",true);
            }
        }
    });

}

function agregarCorreo()
{
    var $emailAddress = $('#correo').val();
    if($('#empresa_correo').val() == '' || $('#correo').val()== '' || !validateEmail($emailAddress)){
        $.toaster({priority : 'danger',title : '¡Error!',message : 'Verifique lo siguiente:<lo><li>Seleccione la empresa para el correo empresarial.</li><li>Ingrese el formato correcto para el correo,ejemplo: mimail@empresa.com</li></ol>',
        settings:{'timeout':6000,'toaster':{'css':{'top':'5em'}}}});
        $('#empresa_correo').addClass('border-danger');
        $('#correo').addClass('border-danger');
    } else{
        var correo = $('#correo').val();
        var empresa_correo = $('#empresa_correo option:selected').text();
        var compania_id = $('#empresa_correo option:selected').val();
        $('#empresa_correo').removeClass('border-danger');
        $('#correo').removeClass('border-danger');
        var id_correo = 'correo_'+cont_correo;
        $('#lista_correo').append('<tr id="'+id_correo+'">' +
            '<th scope="row">'+empresa_correo+'</th> ' +
            '<td>'+correo+'</td>' +
            '<td><button data-toggle="Eliminar" data-placement="top" title="Eliminar" data-original-title="Eliminar" type="button" class="text-primary btn btn_tables is-icon eliminar bg-white" data-delay="50" onclick="eliminarFila(\''+id_correo+'\')"><i class="material-icons">delete</i></button></td>   ' +
            '<input type="hidden" value="'+compania_id+'" name="correo_empresa['+cont_correo+'][id_empresa]">' +
            '<input type="hidden" value="'+correo+'" name="correo_empresa['+cont_correo+'][correo]">' +
            '</tr>');
        cont_correo++;
        $('#correo').val('');
    }
}

$('#listProfiles a').on('click', function (e) {
    e.preventDefault()
})

function validateEmail($email) {
    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    return emailReg.test( $email );
}

function eliminarFila(fila)
{
    $('#'+fila).remove();
}

$(document).on('submit',function(){
    if(!checkingProfiles() || $('#lista_correo').length == 0){
        $.toaster({priority : 'danger',title : '¡Error!',message : 'Antes de Guardar es necesario lo siguiente: <ol><li>Es necesario mínimo un correo empresarial</li><li>Necesitas asignarle mínimo un Perfil</li></ol>',
        settings:{'timeout':6000,'toaster':{'css':{'top':'5em'}}}});
        return false;
    }
});

function checkingProfiles(){
    var validator;
    var $perfiles = $('#listProfiles a')
    for(var i = 0; i < $perfiles.length; i++){
        if($($perfiles[i]).hasClass('active')){
            return validator = $($perfiles[i]);
        }
    }
    if(validator){
        return true;
    } else{
        false;
    }
}