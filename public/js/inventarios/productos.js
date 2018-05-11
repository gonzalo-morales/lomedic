$(document).ready(function () {
	var from_picker = $('#activo_desde').pickadate({ selectMonths: true, selectYears: 3, format: 'yyyy-mm-dd' }).pickadate('picker');
	var to_picker = $('#activo_hasta').pickadate({ selectMonths: true, selectYears: 3, format: 'yyyy-mm-dd' }).pickadate('picker');
	var campoMaterial = document.getElementById('material_curacion');

	if(campoMaterial.hasAttribute('checked')){
		$('#tableSal').toggle();
		$('#tableMaterial').toggle();
	}

	if(!$('#activo').prop('checked')) {
		from_picker.clear().stop();
		to_picker.clear().stop();
		$('#activo_desde').prop('readonly', true);
		$('#activo_hasta').prop('readonly', true);
	}

	$('#activo').click(function (e) {
		if(!$(this).prop('checked')) {
			from_picker.clear().stop();
			to_picker.clear().stop();
    		$('#activo_desde').prop('readonly', true);
    		$('#activo_hasta').prop('readonly', true);
		}
		else {
			from_picker.start().clear();
			to_picker.start().clear();
			to_picker.trigger('set');
		}
	});
	
	from_picker.on('set', function(event) {
		if ( 'select' in event ) {
			to_picker.start().clear().set('min', from_picker.get('select'));
	    }

	    if ( 'clear' in event ) {
	    	to_picker.clear().set('min', false).stop();
	    	$('#activo_hasta').prop('readonly', true);
		  }
	});
	
	$('#fk_id_serie_sku').on('change', function() {
		if($(this).val() == 1 || $(this).val() == '') {
			$('#sku').val('');
			$('#sku').attr('readonly',false)
		}
		else {
			$('#sku').attr('readonly',true)
    		let url = $(this).data('url');
    
    		$.ajax({
    		    async: true,
    		    url: url.replace('?id',$(this).val()),
    		    dataType: 'json',
                success: function (data) {
                    var serie = data.prefijo+'-'+('000000' + data.numero_siguiente).slice(-6);
    				if(data.sufijo != null)
    				{	serie = serie+'-'+data.sufijo; }

					$('#sku').val(serie);
					
    		    },
    		    error: function(){
    		    	$('#sku').val('');
    		    	alert('No es posible generar numero de serie. Verifique que la serie no este en su valor maximo.');
    	    	}
    		});
		}
	});

    $('#material_curacion').on('change',function(){
		$('#tableSal').toggle();
		$('#tbodyMaterials').empty();
		$('#tableMaterial').toggle();
		$('#tbodyPresentation').empty();
    });

	$('#cantidad').on('change', function() {
		var oldvalue = this.old;
		var newvalue = this.value;
		
		// console.log(oldvalue+ ' '+newvalue);
	});

	$('[data-toggle]').tooltip();
	$('.nav-link').on('click', function (e) {
		e.preventDefault();
		$('#clothing-nav li').each(function () {
			$(this).children().removeClass('active');
		});
		$('.tab-pane').removeClass('active').removeClass('show');
		$(this).addClass('active');
		let tab = $(this).prop('href');
		tab = tab.split('#');
		$('#' + tab[1]).addClass('active').addClass('show');
	});

    $('#addPresentation').on('click', function () {
        return addSalt();
	});
    $('#addMaterial').on('click',function(){
        return addMaterial();
	});

	$('#fk_id_forma_farmaceutica').on('change',function(){
		return validateFieldsforUPC();
	})

	$('#fk_id_presentaciones').on('change',function(){
		return validateFieldsforUPC();
	})
	if($('#fk_id_forma_farmaceutica').val() > 0 && $('#fk_id_presentaciones').val() > 0 && $('#tbodyPresentation tr').length > 0){
		getUpcs();
	}
	if($('#fk_id_forma_farmaceutica').val() > 0 && $('#fk_id_presentaciones').val() > 0 && $('#tbodyMaterials tr').length > 0){
		getUpcs();
	}
	
	$(document).on('submit',function (e) {
		if($('#upcs tbody tr').length == 0)
		{
			e.preventDefault();
			mensajeAlerta("Para guardar se requiere que mínimo agregues un UPC, puedes agregarlos en la pestaña <b>Upcs</b>","danger");
		}
		validateDetail();
		if(!$('#form-model').valid())
		{
			e.preventDefault();
			mensajeAlerta("Para guardar se requiere que mínimo indiques el Impuesto y Subgrupo al producto, puedes agregarlos en la pestaña <b>General</b>","danger");
		}
	});
});

function validateFieldsforUPC(){
	let formaFarma = $('#fk_id_forma_farmaceutica').val();
	let material = document.getElementById('material_curacion');
	if(material.hasAttribute('checked')){
		let $matTable = $('#tbodyMaterials tr').length;
		if( $matTable > 0 && formaFarma != '' ){
			return getUpcs();
		} else{
			return mensajeAlerta("Necesito de mínimo: <ul><li>Una especificación del material</li><li>Presentación</li><li>Forma farmacéutica</li></ul> Para poder mostrar los UPCs relacionados.","warning",10000);
		}
	} else {
		let $preTable = $('#tbodyPresentation tr').length;
		if( $preTable > 0 && formaFarma != '' ){
			return getUpcs();
		} else{
			return mensajeAlerta("Necesito de mínimo: <ul><li>Una sal</li><li>Concentración</li><li>Presentación y forma farmacéutica</li></ul> Para poder mostrar los UPCs relacionados.","warning",10000);
		}
	}
}

function borrarFila(el) {
	let tr = $(el).closest('tr');
    tr.fadeOut(400, function(){
		tr.remove();
		$('#upcs > tbody').empty();
		$('#current_upcs').text(0);
		mensajeAlerta("Fila eliminada con éxito","success");
		validateFieldsforUPC();
	})
}

function validateDetail() {
    $('#fk_id_impuesto').rules('add',{
		required:true,
		messages:{
			required: 'Seleccione un impuesto'
		}
	});
	$('#fk_id_subgrupo').rules('add',{
		required:true,
		messages:{
			required: 'Seleccione un subgrupo'
		}
	})
}

function addSalt(){
    let salId = $('#sal option:selected').val();
    let salText = $('#sal option:selected').text();
    let concentrationId = $('#concentracion option:selected').val();
    let concentrationText = $('#concentracion option:selected').text();
	let $tbody = $('#tbodyPresentation');
    let i = $('#tbodyPresentation > tr').length;
    let row_id = i > 0 ? +$('#tbodyPresentation > tr:last').find('#index').val()+1 : 0;
	let existe = false;
	
    $('#tbodyPresentation tr').each(function () {
       let sal = $(this).find('.id_sal').val();
       let concentracion = $(this).find('.id_concentracion').val();
       if(sal === salId && concentracion === concentrationId)
           existe = true;
    });

    if(!existe){
		$tbody.append(
			'<tr>'+
			'<td>' + '<input type="hidden" id="index" value="'+row_id+'"><input type="hidden" name="relations[has][presentaciones]['+row_id+'][fk_id_sal]" class="id_sal" data-name="'+ salText +'" value="'+salId+'">' + salText + '</td>' +
			'<td>' + '<input type="hidden" name="relations[has][presentaciones]['+row_id+'][fk_id_presentaciones]" class="id_concentracion" data-name="'+ concentrationText +'" value="'+concentrationId+'">' + concentrationText + '</td>' +
			'<td>' + '<button data-toggle="Eliminar" data-placement="top" title="Eliminar" data-original-title="Eliminar" type="button" class="text-primary btn btn_tables is-icon eliminar bg-white" data-delay="50" onclick="borrarFila(this)"><i class="material-icons">delete</i></button>' + '</td>' +
			+'</tr>'
		);
		mensajeAlerta("Elemento agregado con éxito","success");
		$('[data-toggle]').tooltip();
		validateFieldsforUPC();
	} else {
		mensajeAlerta(`La sal: <b>${salText}</b> y la cantidad: <b>${concentrationText}</b> ya existe, prueba con otra concentración.`,"warning");
	}
}

function addMaterial(){
    let materialId = $('#especificacion option:selected').val();
    let materialText = $('#especificacion option:selected').text();
    let $tbody = $('#tbodyMaterials');
    let i = $('#tbodyMaterials > tr').length;
    let row_id = i > 0 ? +$('#tbodyMaterials > tr:last').find('#index').val()+1 : 0;
    let existe = false;

    if(i > 0){
        $tbody.children().each(function(){
            let matRow = $(this).find('.id_mat').val()
            if(matRow == materialId){
                existe = true;
            }
        })
    }

    if(!existe){
        $tbody.append(
            '<tr>'+
            '<td>' + '<input type="hidden" id="index" value="'+row_id+'"><input class="id_mat" type="hidden" name="especificaciones['+row_id+'][fk_id_especificacion]" value="'+materialId+'">' + materialText + '</td>' +
            '<td>' + '<button data-toggle="Eliminar" data-placement="top" title="Eliminar" data-original-title="Eliminar" type="button" class="text-primary btn btn_tables is-icon eliminar bg-white" data-delay="50" onclick="borrarFila(this)"><i class="material-icons">delete</i></button>' + '</td>' +
             +'</tr>'
        );
        mensajeAlerta("Elemento agregado con éxito","success");
		$('[data-toggle]').tooltip();
		validateFieldsforUPC();
    } else {
		mensajeAlerta(`El material: <b>${materialText}</b> ya existe, prueba con otro material.`,"warning");
	}
}

function mensajeAlerta(mensaje,tipo,tiempo){
    var titulo = '';
    if(tipo == 'danger'){ titulo = '¡Error!'}
	else if(tipo == 'success'){titulo = '¡Correcto!' }
	else if(tipo == 'warning'){titulo = '¡Advertencia!' }
    $.toaster({priority:tipo,
            title: titulo,
            message:mensaje,
            settings:{'timeout':(tiempo) ? tiempo : 8000,
                'toaster':{'css':{'top':'5em'}}}
        }
    );
}

function getUpcs(){
	let idForma = $('#fk_id_forma_farmaceutica').val();
	let	idPresentaciones = $('#fk_id_presentaciones').val();
	let material = document.getElementById('material_curacion');
	if(material.hasAttribute('checked')){
		let especificaciones = [];
		for (const row of $('#tbodyMaterials tr')) {
			let mat =  +$(row).find('.id_mat').val();
			especificaciones.push(mat);
		}
		$.ajax({
			url: $('#fk_id_forma_farmaceutica').data('url'),
			data: {
				'id_forma':idForma,
				'id_presentaciones':idPresentaciones,
				'arr_especificaciones':JSON.stringify(especificaciones),
			},
			dataType: "json",
			success: function (response) {
				let type = typeof(response);
				if(response.length > 0 || (type == "object" && Object.keys(response).length > 0)){
					(type == "object") ? $('#current_upcs').text(Object.keys(response).length) : $('#current_upcs').text(response.length);
					addUpcs(response);
				}else{
					mensajeAlerta("Al parecer no hay UPC's con las características indicadas, verifica que la presentación, forma farmacéutica y la especificación sean correctas.","danger");
				}
			}
		});
	} else{
		let sales = [];
		let especificaciones = [];
		for (const row of $('#tbodyPresentation tr')) {
			let sal =  +$(row).find('.id_sal').val();
			let concentracion =  +$(row).find('.id_concentracion').val();
			let obj = {};
			obj.id_sal = sal;
			obj.id_concentracion = concentracion;
			sales.push(obj);
		}
		$.ajax({
			url: $('#fk_id_forma_farmaceutica').data('url'),
			data: {
				'id_forma':idForma,
				'id_presentaciones':idPresentaciones,
				'arr_sales':JSON.stringify(sales),
				'arr_especificaciones':JSON.stringify(especificaciones),
			},
			dataType: "json",
			success: function (response) {
				if(response.length > 0){
					$('#current_upcs').text(response.length)
					addUpcs(response);
				}else{
					mensajeAlerta("Al parecer no hay UPC's con las características indicadas, verifica que la presentación, forma farmacéutica y sales sean los adecuados.","danger");
				}
			}
		});
	}
}

function addUpcs(response){
	let $tbody = $('#upcs > tbody');
	$tbody.empty();
	for (const key in response) {
		if (response.hasOwnProperty(key)) {
			$tbody.append(
				'<tr>'+
				'<td>' + response[key].upc + '</td>' +
				'<td>' + response[key].nombre_comercial + '</td>' +
				'<td>' + response[key].descripcion + '</td>' +
				'<td>' + response[key].laboratorio.laboratorio + '</td>' +
				'<td>' + '$' + response[key].costo_base + '</td>' +
				+'</tr>'
			);
		}
	}
    mensajeAlerta("UPC(s) obtenidos con éxito.","success");
    $('[data-toggle]').tooltip();
}