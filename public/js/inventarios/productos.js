$(document).ready(function () {
	var from_picker = $('#activo_desde').pickadate({ selectMonths: true, selectYears: 3, format: 'yyyy-mm-dd' }).pickadate('picker');
	var to_picker = $('#activo_hasta').pickadate({ selectMonths: true, selectYears: 3, format: 'yyyy-mm-dd' }).pickadate('picker');

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

	$('#cantidad').on('change', function() {
		var oldvalue = this.old;
		var newvalue = this.value;
		
		// console.log(oldvalue+ ' '+newvalue);
	});

    $('#addPresentation').on('click', function () {
        addSalt();
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
			mensajeAlerta("Para guardar se requiere que mínimo indiques el Impuesto y Subgrupo al producto, puedes agregarlos en la pestaña <b>General</b>","error");
		}
	});

});
    
function borrarFila(el) {
    let fila = dataTable.data[$(el).parents('tr').index()];
    dataTable.rows().remove([$(el).parents('tr').index()]);
        $.toaster({priority:'success',title:'¡Correcto!',message:'Se ha eliminado la fila correctamente',settings:{'timeout':10000,'toaster':{'css':{'top':'5em'}}}});
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

    $tbody.append(
        '<tr>'+
        '<td>' + '<input type="hidden" name="sal" class="id_sal" data-name="'+ salText +'" value="'+salId+'">' + salText + '</td>' +
        '<td>' + '<input type="hidden" name="concentracion" class="id_concentracion" data-name="'+ concentrationText +'" value="'+concentrationId+'">' + concentrationText + '</td>' +
        '<td>' + '<button data-toggle="Eliminar" data-placement="top" title="Eliminar" data-original-title="Eliminar" type="button" class="text-primary btn btn_tables is-icon eliminar bg-white" data-delay="50" onclick="borrarFila(this)"><i class="material-icons">delete</i></button>' + '</td>' +
         +'</tr>'
    );
    mensajeAlerta("Elemento agregado con éxito","success");
    $('[data-toggle]').tooltip();
}

function mensajeAlerta(mensaje,tipo){
    var titulo = '';
    if(tipo == 'danger'){ titulo = '¡Error!'}
	else if(tipo == 'success'){titulo = '¡Correcto!' }
	else if(tipo == 'warning'){titulo = '¡Advertencia!' }
    $.toaster({priority:tipo,
            title: titulo,
            message:mensaje,
            settings:{'timeout':8000,
                'toaster':{'css':{'top':'5em'}}}
        }
    );
}

$('#fk_id_forma_farmaceutica').on('change',function(){
	if( $(this).val() != '' || $('#tbodyPresentation tr').length > 0 ){
		getUpcs();
	}
})

function getUpcs(){
	let idForma = $('#fk_id_forma_farmaceutica').val();
	let	idPresentaciones = $('#fk_id_presentaciones').val();
	let sales = [];
	let presentaciones = [];
	for (const row of $('#tbodyPresentation tr')) {
		let sal =  +$(row).find('.id_sal').val();
		let concentracion =  +$(row).find('.id_concentracion').val();
		sales.push(sal)
		presentaciones.push(concentracion);
	}
	$.ajax({
		url: $('#fk_id_forma_farmaceutica').data('url'),
		data: {
			'id_forma':idForma,
			'id_presentaciones':idPresentaciones,
			'arr_sales':JSON.stringify(sales),
			'arr_presentaciones':JSON.stringify(presentaciones),
		},
		dataType: "json",
		success: function (response) {
			if(response)
			{
				
			}
		}
	});
}