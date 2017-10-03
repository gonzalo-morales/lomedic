/**
 * Created by ihernandezt on 03/08/2017.
 */


function accionesPerfil(profiel)
{
    var profiel_id = profiel.split('_');

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


// $('.profiles_select').change(function(event) {
//
//     var profiles_no_select = [];
//
//     $.each(this,function(index,value)
//     {
//         profiles_no_select.push($(value).val());
//     });
//     $.each($( this ).val(),function(index,profiel_select)
//     {
//         $.each(profiles_permissions,function()
//         {
//             profiles_no_select = profiles_no_select.filter(function(item)
//             {
//                 return item !== profiel_select
//             });
//             if(this.fk_id_perfil == profiel_select)
//             {
//                 $('#accion_' + this.id_modulo_accion).prop('checked',true);
//             }
//         });
//
//     });
//
//     $.each(profiles_no_select,function(index,profiel_no_select)
//     {
//         $.each(profiles_permissions,function()
//         {
//             if(this.fk_id_perfil == profiel_no_select)
//             {
//                 $('#accion_' + this.id_modulo_accion).prop('checked',false);
//             }
//         });
//     });
// });

// var cont_correo = 0;

function agregarCorreo()
{
    var correo = $('#correo').val();
    var empresa_correo = $('#empresa_correo option:selected').text();
    var compania_id = $('#empresa_correo option:selected').val();

    var id_correo = 'correo_'+cont_correo;
    $('#lista_correo').append('<tr id="'+id_correo+'">' +
        '<th scope="row">'+empresa_correo+'</th> ' +
        '<td>'+correo+'</td>' +
        '<td><a href="#" class="waves-effect " onclick="eliminarFila(\''+id_correo+'\')"><i class="material-icons">delete</i></a></td>   ' +
        '<input type="hidden" value="'+compania_id+'" name="correo_empresa['+cont_correo+'][id_empresa]">' +
        '<input type="hidden" value="'+correo+'" name="correo_empresa['+cont_correo+'][correo]">' +
        '</tr>');
    cont_correo++;
    $('#correo').val('');

}

function eliminarFila(fila)
{
    $('#'+fila).remove();
}
