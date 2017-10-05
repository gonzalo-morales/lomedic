$(document).ready(function () {
	// check box para habilitar la seleccion de otro empleado
	$( "#check_solicitante" ).on( "click", function() {
    	$( this ).parent().nextAll( "select" ).prop( "disabled", !this.checked );
    	if( !this.checked )
    		$( this ).parent().nextAll( "select" ).empty().trigger('change');
    });
	
	//cuando se habilita otro empledo carga los empleados disponibles
	var options = new Option('Selecciona un empleado', '', true, false);
    $('#empleado_solicitud').prepend(options);
    $('#empleado_solicitud option:selected').prop('disabled', true);

    $( "#empleado_solicitud" ).select2({
    	theme: "bootstrap",
        placeholder: "Selecciona si la solicitud es para otra persona",
        maximumSelectionSize: 6,
        containerCssClass: ':all:',
        ajax: {
            dataType: 'json',
            url: $('#empleado_solicitud').data('url'),
            delay:250,
            data: function (params) {
                return {q: params.term}
            },
            processResults: function (data) {
                return {results: data}
            }
        },
    });
    
    //Carga las sucursales del empleado que inicio sesion
    var options = new Option('Selecciona una sucursal', '', true, false);
    $('#fk_id_sucursal').prepend(options);
    $('#fk_id_sucursal option:selected').prop('disabled', true);
    id_empleado = $('#id_solicitante').val();
    url_sucursal = $('#fk_id_sucursal').data('url');
    
    $( "#fk_id_sucursal" ).select2({
    	theme: "bootstrap",
        maximumSelectionSize: 6,
        containerCssClass: ':all:',
        ajax: {
            dataType: 'json',
            url: url_sucursal.replace('?id', id_empleado),
            delay:250,
            data: function (params) {
                return {q: params.term}
            },
            processResults: function (data) {
                return {results: data}
            }
        },
    });
    
    //Carga las sucursales para el empleado seleccionado
    $('#empleado_solicitud').bind('change', function(){
    	$("#fk_id_sucursal").empty();
    	var options = new Option('Selecciona una sucursal', '', true, false);
        $('#fk_id_sucursal').prepend(options);
        $('#fk_id_sucursal option:selected').prop('disabled', true);
	    	    
	    let url_sucursal = $('#fk_id_sucursal').data('url');
	    let id_empleado = $('option:selected', this).val();
	    
	    if( !id_empleado ) {
	    	id_empleado = $('#id_solicitante').val();
	    }
	    
	    $( "#fk_id_sucursal" ).select2({
	    	theme: "bootstrap",
	        placeholder: "Sucursal",
	        maximumSelectionSize: 6,
	        containerCssClass: ':all:',
	        ajax: {
	            dataType: 'json',
	            url: url_sucursal.replace('?id', id_empleado),
	            delay:250,
	            data: function (params) {
	                return {q: params.term}
	            },
	            processResults: function (data) {
	                return {results: data}
	            }
	        },
	    });
    });

    //Carga de subcategorias al elegir una categoria
    var options = new Option('Selecciona una categoria', '', true, false);
    $('.fk_id_categoria').prepend(options);
    $('.fk_id_categoria').val('');
    $('.fk_id_categoria option:selected').prop('disabled', true);
    
    $('.fk_id_categoria').on('change', function(){
        let url = $(this).data('url');

        $('.fk_id_subcategoria').empty();
        $('.fk_id_accion').empty();
        $('.fk_id_subcategoria').prop('disabled',!$('option:selected', this).val());
        $('.fk_id_accion').prop('disabled',!$('.fk_id_subcategoria option:selected').val());

        $.ajax({
            url: url.replace('?id', $('option:selected', this).val()),
            dataType: 'json',
            success: function (data) {
            	var options = new Option('Selecciona una subcategoria', '', true, false);
                $('.fk_id_subcategoria').prepend(options);
                $('.fk_id_subcategoria option:selected').prop('disabled', true);
                $.each(data, function (key, data) {
                	var option = new Option(data.subcategoria, data.id_subcategoria, false, false);
                	$('.fk_id_subcategoria').append(option);
                });
            },
            error: function () {
                alert('error');
            }
        });
    });
    
    //Carga acciones segun al elegir una subcategoria
    $('.fk_id_subcategoria').on('change', function(){
        let url = $(this).data('url');

        $('.fk_id_accion').empty();
        $('.fk_id_accion').prop('disabled',!$('option:selected', this).val());

        $.ajax({
            url: url.replace('?id', $('option:selected', this).val()),
            dataType: 'json',
            success: function (data) {
            	var options = new Option('Selecciona una accion', '', true, false);
                $('.fk_id_accion').prepend(options);
                $('.fk_id_accion option:selected').prop('disabled', true);
                $.each(data, function (key, data) {
                	var option = new Option(data.accion, data.id_accion, false, false);
                	$('.fk_id_accion').append(option);
                });
            },
            error: function () {
                alert('error');
            }
        });
    });
    
    //Selecciona Prioridad
    var options = new Option('Selecciona una prioridad', '', true, false);
    $('#fk_id_prioridad').prepend(options);
    $('#fk_id_prioridad').val('');
    $('#fk_id_prioridad option:selected').prop('disabled', true);
    
    //Validaciones antes del submit
    $( "#form-ticket" ).submit(function( event ) {
    	var mensaje = '';
    	
    	if($('#check_solicitante').is(":checked") && !$('#empleado_solicitud').val()) {
    		mensaje = mensaje + '<br> Especifica el solicitante.';
    	}
    	if(!$('#fk_id_sucursal').val()) {
    		mensaje = mensaje + '<br> Especifica una sucursal.';
    	}
    	if(!$('#fk_id_prioridad').val()) {
    		mensaje = mensaje + '<br> Especifica una prioridad.';
    	}
    	
    	if(!$('#asunto').val()) {
    		mensaje = mensaje + '<br> Especifica un Asunto.';
    	}
    	
    	if(!$('#descripcion').val()) {
    		mensaje = mensaje + '<br> Especifica la descripcion del problema.';
    	}
    	
    	if(mensaje.length >= 1) {
    		event.preventDefault();
    		$.toaster({
                priority : 'danger',
                title : 'Verifica los siguiente',
                message : mensaje,
                settings:{
                    'timeout':10000,
                    'toaster':{
                        'css':{
                            'top':'5em'
                        }
                    }
                }
            });
    	}
	});
});
