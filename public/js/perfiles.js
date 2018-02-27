
$(document).on('submit',function(){
    if(!checkingUsers() || !checkingCheckboxes()){
        $.toaster({priority : 'danger',title : '¡Error!',message : 'Antes de Guardar es necesario lo siguiente: <ol><li>Necesitas asignarle mínimo un Usuario</li><li>Agrega mínimo una empresa y su(s) respectivo(s) módulo(s) y permiso(s).</li></ol>',
        settings:{'timeout':6000,'toaster':{'css':{'top':'5em'}}}});
        return false;
    }
});

function checkingUsers(){
    var validator = false;
    var $usuarios = $('#usuariosList input.custom-control-input')
    for(var i = 0; i < $usuarios.length; i++){
        if($($usuarios[i]).is(':checked')){
            validator = true;
        }
    }
    if(validator == true){
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