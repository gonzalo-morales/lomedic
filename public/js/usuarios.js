/**
 * Created by ihernandezt on 03/08/2017.
 */
$('.fk_id_sucursales').select2({
    multiple: true,
    disabled:true,
    placeholder: 'Seleccione la empresa...'
});
$('#fk_id_empresa_default').on('change', function () {
    $(".fk_id_sucursales").html("")
    $('#loadingsucursales').show();
    var idempresa = $('#fk_id_empresa_default option:selected').val();
    var _url = $(this).data('url');
    $.ajax({
        url: _url,
        data: {'param_js': api_sucursales ,$fk_id_empresa:idempresa},
        dataType: "json",
        success: function (data) {
            if(data.length > 0){
                var options = [];
                for (var i = 0; i < data.length; i++) {
                    options.push('<option value="' + data[i].id_sucursal + '">' + data[i].sucursal + '</option>');
                };
                $('.fk_id_sucursales').append(options.join(''));
                $('.fk_id_sucursales').select2({
                    multiple: true,
                    disabled:false,
                });
                $('#loadingsucursales').hide();
                $('.fk_id_sucursales').select2();
            } else {
                $.toaster({priority : 'danger',title : '¡Lo sentimos!',message : 'Selecciona otra <b>Sucursal</b>, ya que el seleccionado no cuenta con Empresas relacionadas.',
                settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
                $('.fk_id_sucursales').select2({
                    multiple: true,
                    disabled:true,
                    placeholder: 'Seleccione otra empresa...'
                });
                $('#loadingsucursales').hide();
            }
        },
        error: function(){
            $('.fk_id_sucursales').select2({
                multiple: true,
                disabled:true,
            });
            $('#loadingsucursales').hide();
            $.toaster({priority : 'danger',title : '¡Lo sentimos!',message : 'Selecciona otra <b>Sucursal</b>, ya que el seleccionado no cuenta con Empresas relacionadas.',
            settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
        },
    });
});

function accionesPerfil(profiel)
{   
    
    var profiel_id = profiel.split('_');
    if($("#perfil_"+profiel_id[1]).prop('checked') == true)
    {
        $("#perfil_"+profiel_id[1]).prop('checked', false);
    }
    else
    {
        $("#perfil_"+profiel_id[1]).prop('checked', true);
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
        $.toaster({priority : 'success',title : '¡Éxito!',message : 'Correo agregado con éxito',
        settings:{'timeout':3000,'toaster':{'css':{'top':'5em'}}}});
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

$('#usuario').on('keyup', function (value) {
    if($(this).val() == $('#nombre_corto').val()){
        $(this).removeClass('border-success').addClass('border-danger');
        $.toaster({priority : 'danger',title : '¡Error!',message : 'El Usuario tiene que ser diferente a su nombre actual.',
        settings:{'timeout':3000,'toaster':{'css':{'top':'5em'}}}});
    } else {
        $(this).removeClass('border-danger').addClass('border-success')
    }
});

function validateEmail($email) {
    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    return emailReg.test( $email );
}

function eliminarFila(fila)
{
    $('#'+fila).remove();
}

$(document).on('submit',function(){
    if(!checkingProfiles() || $('#lista_correo').length == 0 || $('#usuario').hasClass('border-danger') || !checkingCheckboxes()){
        $.toaster({priority : 'danger',title : '¡Error!',message : 'Antes de Guardar es necesario lo siguiente: <ol><li>Es necesario mínimo un correo empresarial</li><li>Necesitas asignarle mínimo un Perfil</li><li>Verifica que el Nombre corto y el usuario no sean <b>iguales</b></li><li>Agrega mínimo una sucursal</li></ol>',
        settings:{'timeout':6000,'toaster':{'css':{'top':'5em'}}}});
        return false;
    }
});

function checkingProfiles(){
    var validator;
    var $perfiles = $('#listProfiles input')
    for(var i = 0; i < $perfiles.length; i++){
        if($($perfiles[i]).hasClass('active')){
            validator = $($perfiles[i]);
        }
    }
    if(validator){
        return true;
    } else{
        return false;
    }
}
function checkingCheckboxes(){
    var validator = false;
    var $modulos = $('#modulos input')
    for(var i = 0; i < $modulos.length; i++){
        if($($modulos[i]).is(':checked')){
            validator = true;
        }
    }
    if(validator == true){
        return true;
    } else{
        return false;
    }
}