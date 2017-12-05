$('.datepicker').pickadate({
    selectMonths: true, // Creates a dropdown to control month
    selectYears: 3, // Creates a dropdown of 3 years to control year
    format: 'yyyy-mm-dd'
});

$(document).ready(function () {

    $('#fk_id_socio_negocio').change(function () {
        $('#loadingcomprador').show();
        $.ajax({
            url: $('#comprador').data('url'),
            type: 'GET',
            data: {'param_js':comprador_js,$id_socio_negocio:$(this).val()},
            dataType: 'json',
            success: function (data) {
                if(data){
                    $('#comprador').val(data[0].nombre + ' ' + data[0].apellido_paterno + ' ' + data[0].apellido_materno);
                }else{
                    $('#comprador').val('');
                }
                $('#loadingcomprador').hide();
            },
            error: function () {
                $('#comprador').val('');
                $('#loadingcomprador').hide();
            }
        });
    });

    $('#agregar').click(function () {
        if($('#archivo_xml_input').val() && $('#archivo_pdf_input').val() && $('#fk_id_socio_negocio').val() > 0){
            if($('#archivo_xml_input').val().substring($('#archivo_xml_input').val().lastIndexOf(".")) != '.xml' || $('#archivo_pdf_input').val().substring($('#archivo_pdf_input').val().lastIndexOf(".")) != '.pdf'){
                $.toaster({
                    priority: 'danger', title: '¡Error!', message: 'Por favor verifica la extensión de ambos archivos',
                    settings: {'timeout': 10000, 'toaster': {'css': {'top': '5em'}}}
                });
            }else{
                $('#loadingxml').show();
                $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
                var xml = $('#archivo_xml_input').prop('files')[0];
                var formData = new FormData();
                formData.append('file',xml);
                formData.append('fk_id_socio_negocio',$('#fk_id_socio_negocio').val());
                var _url = $('#archivo_xml_input').data('url');
                $.ajax({
                    url: _url,
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    // dataType: 'script',
                    success: function (data) {
                        // alert(data);
                        $('#loadingxml').hide();
                    },
                    error: function (jqXHR, exception) {
                        $.toaster({
                            priority: 'danger', title: '¡Error '+jqXHR.status+'!', message: 'Ha ocurrido un error al cargar los datos',
                            settings: {'timeout': 10000, 'toaster': {'css': {'top': '5em'}}}
                        });
                        $('#loadingxml').hide();
                    }
                });
            }
        }else{
            $.toaster({
                priority: 'danger', title: '¡Error!', message: 'Por favor sube ambos archivos y selecciona un proveedor',
                settings: {'timeout': 10000, 'toaster': {'css': {'top': '5em'}}}
            });
        }
    });
});
