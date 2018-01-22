$(document).ready(function () {

    $("#tipo_documento").on('change',function(){
        let token = document.querySelector("meta[name='csrf-token']").getAttribute("content");
        console.log(token);
       var url_getDocumento = $('#fk_id_proveedor').data('url');
        console.log(url_getDocumento);
        $.ajax({
               url:url_getDocumento,
               type:'POST',
               data:{
                   tipoDocumento:$('#tipo_documento').val(),
                   fk_id_proveedor:$('#fk_id_proveedor').val(),
                   _token:token
               },
               success:function (data) {
                   console.log(JSON.parse(data));
                   $("#documentos").html('<option value="-1">Selecciona una opcion...</option>');
                   $.each(JSON.parse(data),function(key,value){
                       console.log(value.id,value.identificador);
                       $("#documentos").append('<option value="'+ value.id +'">'+ value.identificador +'</option>');
                   });
               }
        });

    //    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
    //    var url_getDocumento = $('#fk_id_proveedor').data('url').replace('?id',$('#fk_id_proveedor').val());
    //    $.ajax({
    //        url:url_getDocumento,
    //        type:'POST',
    //        data:{
    //            tipoDocumento:$('#tipo_documento').val()
    //        },
    //        success:function (data) {
    //            console.log(data);
    //         //    if(data.status == 1){
    //         //        $('#autorizacion').modal('toggle');
    //         //        $.toaster({
    //         //            priority: 'success', title: 'Éxito', message: 'Se ha actualizado la información de la autorización',
    //         //            settings: {'timeout': 5000, 'toaster': {'css': {'top': '5em'}}}
    //         //        });
    //         //    }else{
    //         //        $.toaster({
    //         //            priority: 'danger', title: 'Error', message: 'Ha ocurrido un error',
    //         //            settings: {'timeout': 5000, 'toaster': {'css': {'top': '5em'}}}
    //         //        });
    //         //    }
    //        }
       });

});
