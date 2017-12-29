$(document).ready(function () {
	$('#fk_id_pais').on('change', function() {
		var estado = $('#fk_id_estado');
		
		if(!$(this).val()) {
			estado.val('');
		}
		else {
			$.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
    		$.ajax({
    		    async: true,
    		    url: estado.data('url'),
    		    data: {'param_js':estados_js,$fk_id_pais:$(this).val()},
    		    dataType: 'json',
                success: function (data) {
                	$("#fk_id_estado option").remove();
                	estado.append('<option value="" disabled>Selecciona una Opcion...</option>')
                    $.each(data, function(){
                    	estado.append('<option value="'+ this.id_estado +'">'+ this.estado +'</option>')
                    });
            		estado.val('');
            		estado.prop('disabled', (data.length == 0)); 
    		    }
    		});
		}
	}).trigger('change');
	
	$('#fk_id_estado').on('change', function() {
		var municipio = $('#fk_id_municipio');

		if(!$(this).val()) {
			municipio.val('');
		}
		else {
			$.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
    		$.ajax({
    		    async: true,
    		    url: municipio.data('url'),
    		    data: {'param_js':municipios_js,$fk_id_estado:$(this).val()},
    		    dataType: 'json',
                success: function (data) {
                	$("#fk_id_municipio option").remove();
                	municipio.append('<option value="" disabled>Selecciona una Opcion...</option>')
                    $.each(data, function(){
                    	municipio.append('<option value="'+ this.id_municipio +'">'+ this.municipio +'</option>')
                    });
                	municipio.val('');
                	municipio.prop('disabled', (data.length == 0)); 
    		    }
    		});
		}
	}).trigger('change');
	
	$('#agregar-certificado').on('click', function() {
		let row_id = $('#tCertificados tr').length;
		
		var no_certificados = [];
    	$('.no_cer').each(function (i) {
    		no_certificados.push($('.no_cer')[i].value); 
		});
		
		url = $(this).data('url');
		key = $("#file_key").prop('files');
		cer = $("#file_certificado").prop('files');
		rfc = $("#rfc").val();
		pass = $("#password").val();
		$.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
		
		if(key.length == 0 | cer.length == 0) {
			$.toaster({priority:'danger',title:'Ã‚Â¡Error!',message:'Selecciona los archivos key y cer solicitados.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
		}
		else {
			var formData = new FormData();
            formData.append('cer',cer[0]);
            formData.append('key',key[0]);
            
            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                	if(data){
                		rfc_cer = data.subject.x500UniqueIdentifier.substr(0,13).trim();
                		cadena_cer = data.cadena_cer;
                		cadena_key = data.cadena_key;
                		no_cer = converSerial(data.serialNumberHex);
                		
                		f = data.validFrom;
                		from = new Date("20"+f.substr(0,2)+'-'+f.substr(2,2)+'-'+f.substr(4,2)+'T'+f.substr(6,2)+':'+f.substr(8,2)+':'+f.substr(10,3)).toJSON().replace('T',' ').replace('.000Z','');
                	
                		t = data.validTo;
                		to = new Date("20"+t.substr(0,2)+'-'+t.substr(2,2)+'-'+t.substr(4,2)+'T'+t.substr(6,2)+':'+t.substr(8,2)+':'+t.substr(10,3)).toJSON().replace('T',' ').replace('.000Z','');
                		
                		
                		if(rfc !== rfc_cer)
                			$.toaster({priority:'danger',title:'Ã‚Â¡Error!',message:'El certificado, no coincide con el rfc de la empresa.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
                		else if(to < Date.now())
                			$.toaster({priority:'danger',title:'Ã‚Â¡Error!',message:'El certificado ya expiro.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
                		else if(no_certificados.indexOf(no_cer) !== -1)
                			$.toaster({priority:'danger',title:'Ã‚Â¡Error!',message:'El certificado seleccionado ya fue agregado anteriormente.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
                		else {
                			$('#tCertificados').append('<tr>'+
            					'<td>' + key[0].name + ' <input id="key-'+row_id+'" class="file-anexos" name="certificados['+row_id+'][key-file]" type="file" style="display:none"></td>'+
            					'<td>' + cer[0].name + ' <input id="certificado-'+row_id+'" class="file-anexos" name="certificados['+row_id+'][cer-file]" type="file" style="display:none"></td>'+
            					'<td>' + no_cer + '<input class="no_cer" name="certificados['+row_id+'][no_certificado]" type="hidden" value="'+no_cer+'"></td>'+
            					'<td>' + from + '<input name="certificados['+row_id+'][fecha_expedicion]" type="hidden" value="'+from+'"></td>'+
            					'<td>' + to+' <input name="certificados['+row_id+'][fecha_vencimiento]" type="hidden" value="'+to+'"></td>'+
            					'<td>'+
            						'<input name="certificados['+row_id+'][password]" type="hidden" value="'+pass+'">'+
	            					'<input name="certificados['+row_id+'][cadena_cer]" type="hidden" value="'+cadena_cer+'">'+
	            					'<input name="certificados['+row_id+'][cadena_key]" type="hidden" value="'+cadena_key+'">'+
	            					'<button class="btn is-icon text-primary bg-white" type="button" data-delay="50" onclick="borrarCertificado(this)"> <i class="material-icons">delete</i></button>'+
	            				'</td>'+
            				'</tr>');
                			$('#key-'+row_id).prop('files',key);
                			$('#certificado-'+row_id).prop('files',cer);
            				$.toaster({priority:'success',title:'Ã‚Â¡Correcto!',message:'El certificado se agrego correctamente.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
                		}
                	}
                },
                error: function (jqXHR, exception) {
                    $.toaster({priority:'danger',title:'Ã‚Â¡Error '+jqXHR.status+'!',message:'Ha ocurrido un error al tratar de cargar el certificado.',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
                }
            });
		}
	});
});

function borrarCertificado(el) {
	$(el).parent().parent('tr').remove();
    $.toaster({priority:'success',title:'Ã‚Â¡Correcto!',message:'Se ha eliminado el certificado correctamente',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
}

function converSerial(serial) {
	serie = '';
	$(serial.split('')).each(function (i,c) {
		if (i % 2 == 1)
			serie = serie + c;
	});
	return serie;
}