@extends('handheld.layout')

@section('title', 'Handheld - Orden-Compra')
@section('content')
	{!! Form::open(['url' => companyRoute('handheld.entrada-detalle-store'),'method'=>'post','enctype' => 'multipart/form-data','id'=>'form']) !!}
		<input type="hidden" name="fk_id_tipo_documento" value="{{ $orden->fk_id_tipo_documento }}">
		<input type="hidden" name="numero_documento" value="{{ $orden->id_documento }}">
		<input type="hidden" name="fk_id_estatus_entrada" value="{{ $orden->fk_id_estatus_autorizacion }}">
		<input type="hidden" name="fecha_entrada" value="{{ $fecha_entrada }}">
		<div>
			<p class="text-center thead" style="padding:0;margin:0;">Empieza por indicar el Documento de Entrada</p>
			<table class="table-columns">
				<tr>
					<td class="column right">
						<label for="fk_id_almacen">* Almacen</label>
					</td>
					<td class="column left">
							{{ Form::cSelect('','fk_id_almacen', $almacenes ?? []) }}
					</td>
				</tr>
				<tr>
					<td class="column right">
						<label for="ubicacion">* Ubicación</label>
					</td>
					<td class="column left" style="position:relative;">
							<div id="loadingUbicaciones" class="h-100 text-center text-white align-middle loadingData display-none" style="height: 30px;">
								Buscando ubicaciones...
							</div>
							{{ Form::cSelect('','fk_id_ubicacion', $ubicaciones ?? []) }}
					</td>
				</tr>
				<tr>
					<td class="column right">
						<label for="referencia_documento">* Documento</label>
					</td>
					<td class="column left">
						<input id="referencia_documento" name="referencia_documento" class="form-control" type="text" value="">
					</td>
				</tr>
			</table>
			<hr>
			<p class="text-center thead" style="padding:0;margin:0;">Ingresa los siguientes valores para indicar la Entrada de productos</p>
			<table class="table-columns">
				<tr>
					<td class="column right">
						<label for="skus">* SKU</label>
					</td>
					<td class="column left">
							{{ Form::cSelect('','skus', $skus ?? []) }}
					</td>
				</tr>
				<tr>
					<td class="column right">
						<label for="fecha_caducidad">* Fecha de caducidad</label>
					</td>
					<td class="column left">
						<input id="fecha_caducidad" placeholder="AAAA-MM-DD" class="form-control" type="text" value="">
					</td>
				</tr>
				<tr>
					<td class="column right">
						<label for="lote">* Lote</label>
					</td>
					<td class="column left">
						<input id="lote" class="form-control" type="text" value="">
					</td>
				</tr>
				<tr>
					<td class="column right">
						<label for="scan">* Producto Escaneado</label>
					</td>
					<td class="column left" style="position:relative;">
					<div id="loadingupc" class="h-100 text-center text-white align-middle loadingData display-none" style="height: 30px; ">
							Validando...
					</div>
						<input id="scan" class="form-control" type="text" value="">
						<span id="upc_verificado" class="display-none" style="color:green;">Verificado</span>
					</td>
				</tr>
			</table>

			<table id="skuTable" class="table-columns display-none margin-top">
				<tbody id="tbody"></tbody>
			</table>

			<div class="margin-top wrapper">
				<button id="submit" type="submit" class="square actionBtn blue" style="width:45.5%">Guardar</button>
				{{ link_to(companyAction('HomeController@index'), 'Cancelar', ['class'=>'square actionBtn red','style'=>'width:45.5%','id'=>'cancelar']) }}
			</div>
	
		</div>
	{!! Form::close() !!}
	
	<script type="text/javascript">
		$(function(){
			// Init
			$('#scan').focus();
			
			//Con esto evitamos que haga submit
			$(document).on("keypress", "#form", function(event) {
				return event.keyCode != 13;
			});

			function GetDescriptionFor(e) {
				var result, code, key;
				if (e.charCode && e.keyCode == 0) {
					result = "charCode: " + e.charCode;
					code = e.charCode;
				} else {
					result = "keyCode: " + e.keyCode;
					code = e.keyCode;
				}
		
				if (code == 8) key = "BKSP";
				else if (code == 9) key = "TAB";
				else if (code == 46) key = "DEL";
				else if (code >= 41 && code <= 126) key = String.fromCharCode(code);
		
				// if (e.shiftKey) result += " shift";
				// if (e.ctrlKey) result += " ctrl";
				// if (e.altKey) result += " alt";
		
				return {keycode: code, key: key};
			}

			function datemask(v) {
				if (v.match(/^\d{4}$/) !== null) {
					return v = v + '-';
				} else if (v.match(/^\d{4}\-\d{2}$/) !== null) {
					return v = v + '-';
				}
				return v;
			}

			// Placeholder jquery
			$("input").each(function(){""===$(this).val()&&void 0!==$(this).attr("placeholder")&&""!==$(this).attr("placeholder")&&($(this).val($(this).attr("placeholder")),$(this).addClass("placeholder")),void 0!==$(this).attr("placeholder")&&""!==$(this).attr("placeholder")&&($(this).focus(function(){$(this).val()===$(this).attr("placeholder")&&$(this).removeClass("placeholder").val("")}),$(this).blur(function(){""===$(this).val()&&($(this).val($(this).attr("placeholder")),$(this).addClass("placeholder"))}),$(this).change(function(){""!==$(this).val()&&$(this).removeClass("placeholder")}))}),$("textarea").each(function(){""===$(this).val()&&void 0!==$(this).attr("placeholder")&&""!==$(this).attr("placeholder")&&($(this).val($(this).attr("placeholder")),$(this).addClass("placeholder")),void 0!==$(this).attr("placeholder")&&""!==$(this).attr("placeholder")&&($(this).focus(function(){$(this).val()===$(this).attr("placeholder")&&$(this).removeClass("placeholder").val("")}),$(this).blur(function(){""===$(this).val()&&($(this).val($(this).attr("placeholder")),$(this).addClass("placeholder"))}),$(this).change(function(){""!==$(this).val()&&$(this).removeClass("placeholder")}))}),$("form").submit(function(){$("input").each(function(){$(this).val()===$(this).attr("placeholder")&&$(this).val("")}),$("textarea").each(function(){$(this).val()===$(this).attr("placeholder")&&$(this).val("")})});

			// Validar fecha
			$('#fecha_caducidad').on({
				keydown: function(e) {
					if (!new RegExp('^[0-9]|BKSP|TAB+$').test(GetDescriptionFor(e).key) || (!new RegExp('^BKSP|TAB$').test(GetDescriptionFor(e).key) && this.value.match(/^\d{4}\-\d{2}\-\d{2}$/) !== null)) {
						e.preventDefault();
					}
				},
				keyup: function(e) {
					this.value = datemask(this.value)
				},
				blur: function(e) {
					if (this.value != '' && this.value != $(this).attr('placeholder') && !new RegExp('^([0-9]{4}[-](0[1-9]|1[0-2])[-]([0-2]{1}[0-9]{1}|3[0-1]{1})|([0-2]{1}[0-9]{1}|3[0-1]{1})[-](0[1-9]|1[0-2])[-][0-9]{4})$').test(this.value)) {
						alert('Fecha invalida')
						this.value = '';
						this.focus();
					}
				}
			});

			$('#fk_id_almacen').on('change',function(){
				$('#loadingUbicaciones').show();
				$('#fk_id_ubicacion').html('');
				$.get('{{ companyRoute('api.index', ['entity' => 'inventarios.ubicaciones'], false) }}', {
					'param_js': '{{$ubicaciones_js ?? ''}}',
					'$almacen': $(this).val()
					// conditions: [{'where': ['upc', e.target.value]}],
					// only: ['descripcion']
				}, function(response){
					var options = [];
					if (response.length > 0) {
						options.push('<option value="0" selected disabled>Selecciona la Ubicación...</option>'); 
						for(var i = 0; i < response.length; i++){
							options.push('<option value="' + response[i].id_ubicacion + '">' + response[i].ubicacion + '</option>');
						}
						$('#fk_id_ubicacion').append(options.join(''));
						$('#loadingUbicaciones').hide();
					} else {
						alert('Seleccione otro Almacén que cuente con Ubicaciones disponibles');
						$('#loadingUbicaciones').hide();
					}
				})
			})

			$("#scan").on("keydown", function(event){
				if (event.keyCode == 13) {

					if($('#fecha_caducidad').val() != "AAAA-MM-DD" && $('#lote').val() != ""){
						$('#loadingupc').show();
						$.get('{{ companyRoute('api.index', ['entity' => 'compras.detalleOrdenes'], false) }}', {
							'param_js': '{{$codigo_barras_js ?? ''}}',
							'$upc': event.target.value,
							'$orden':'{{ $orden->id_documento }}',
							'$id_sku':$('#skus').val()
							// conditions: [{'where': ['upc', e.target.value]}],
							// only: ['descripcion']
						}, function(response){
							if (response.length > 0) {
								if($('#tbody tr').length > 0){
									findSameRows(response)
									$('#scan').val('');
									$('#lote').val('');
									$('#fecha_caducidad').val("AAAA-MM-DD");
								} else {
									addNewRegInTable(response);
									$('#scan').val('');
									$('#lote').val('');
									$('#fecha_caducidad').val("AAAA-MM-DD");
								}
							} else {
								alert('El UPC que tratas de escanear no es válido debido a lo siguiente: \n-Verifica que sea el mismo UPC registrado en el Orden de Compra.\n-Se registró la cantidad máxima para la orden de compra en este UPC.');
								$('#loadingupc').hide();
								$('#scan').val('');
								$('#upc_verificado').hide();
							}
						})
					} else {
						alert('Para ingresar el producto escaneado es necesario que ingreses el Lote y su fecha de caducidad');
					}

				}
			});
	
			$('#form').on('submit', function(e){
					// Enviamos formulario
				if($('#fk_id_ubicacion').val() == ''){
					alert('Para finalizar es necesario indicar la Ubicación');
					return false;
				}
				if($('#referencia_documento').val() == ''){
					alert('Indica el Documento en la Orden de Compra para finalizar');
					return false;
				}
				if($('#fk_id_almacen').val() == '0'){
					alert('Para finalizar es necesario indicar el Almacén');
					return false;
				}
				if($('#tbody tr').length == 0){
					alert('Para guardar la Orden es necesario que mínimo indiques los documentos y un SKU/UPC');
					return false;
				}

			});
	
		});

		//FUNCIÓN PARA LIMPIAR LA FILA
		function borrarFila(el) {
			var tr = $(el).closest('tr');
			tr.fadeOut(400, function(){
			tr.remove().stop();
			})
		};

		function findSameRows(response){
			var tableRows = $('#tbody tr');
			var rowFounded;
			for(var i = 0; i < tableRows.length; i++){
				var skuRow =  +$(tableRows[i]).find('.skuRow').val();
				var upcRow =  $(tableRows[i]).find('.upcRow').text();
				var idOrdenDetalle =  +$(tableRows[i]).find('.id_orden_detalle').val();
				var fechaCaducidadRow =  $(tableRows[i]).find('.fechaCaducidadRow').val();
				var loteRow =  $(tableRows[i]).find('.loteRow').val();
				if( $('#scan').val() == upcRow && $('#skus').val() == skuRow ){
					rowFounded = tableRows[i];
				}
			}
			if( rowFounded ){
				var cantidadSurtida = +$(rowFounded).find('.cantidad_surtida').val();
				var nuevaCantidadSurtir = cantidadSurtida + 1;
				if(nuevaCantidadSurtir > (response[0].cantidad - response[0].cantidad_recibida)){
					$(rowFounded).find('.cantidad_surtida').addClass('border-done');
					$(rowFounded).find('div.thead').addClass('text-done');
					$(rowFounded).find('div.stopUser').removeClass('display-none');
				} else {
					$(rowFounded).find('.cantidad_surtida').val(nuevaCantidadSurtir);
				}
			} else {
				addNewRegInTable(response);
			}
			$('#loadingupc').hide();
		}

		function addNewRegInTable(response){
			$('#skuTable').removeClass('display-none');
			var $tbody = $('#tbody');
			var loteVal = $('#lote').val();
			var fecha_caducidadVal = $('#fecha_caducidad').val();
			$('#loadingupc').hide();
			$('#upc_verificado').show();
			var i = $('#tbody > tr').length;
			var row_id = i > 0 ? +$('#tbody > tr:last').find('#index').val()+1 : 0;
			$tbody.append(
				'<tr><th class="thead" style="text-align:left;">'+ 
					'<div> SKU: ' + $('#skus option:selected').text() + '</div>' +
					'<div> UPC: ' + '<span class="upcRow">' + $('#scan').val() + '</span>' + '</div>' +
					'<input type="hidden" id="index" value="'+row_id+'"></input>'+
					'<input type="hidden" class="skuRow" name="datos_entradas['+row_id+'][fk_id_sku]" value="'+ response[0].fk_id_sku +'"/>'+
					'<input type="hidden" name="datos_entradas['+row_id+'][precio_unitario]" value="'+ response[0].precio_unitario +'"/>'+
					'<input type="hidden" name="datos_entradas['+row_id+'][fk_id_upc]" value="'+ response[0].fk_id_upc +'"/>'+
					'<input type="hidden" class="id_orden_detalle" name="datos_entradas['+row_id+'][fk_id_linea]" value="'+ response[0].id_orden_detalle +'"/>'+
					'<input type="hidden" name="datos_entradas['+row_id+'][fk_id_proyecto]" value="'+ response[0].fk_id_proyecto +'"/>'+
					'<input type="hidden" class="fechaCaducidadRow" name="datos_entradas['+row_id+'][fecha_caducidad]" value="'+ fecha_caducidadVal +'"/>'+
					'<input type="hidden" class="loteRow" name="datos_entradas['+row_id+'][lote]" value="'+ loteVal +'"/>'+
					'<input type="hidden" name="datos_entradas['+row_id+'][fk_id_tipo_documento_base]" value="'+ response[0].fk_id_tipo_documento +'"/>'+
					'<input type="hidden" name="datos_entradas['+row_id+'][fk_id_proyecto]" value="'+ response[0].fk_id_proyecto +'"/>'+
					'</th>'
				+ '<td class="thead">'+ fecha_caducidadVal + ' / '+ loteVal +'</td>'
				+ '<td style="text-align:left;">'+ '<div class="thead">Total solicitadas: ' + '<b>' + (response[0].cantidad - response[0].cantidad_recibida) + '</b>' + '</div>' + '<span class="thead">Escaneadas: </span>' + '<input readonly class="cantidad_surtida" name="datos_entradas['+row_id+'][cantidad_surtida]" value="1" type="number" style="width:30px">' + '<div class="thead text-done display-none stopUser">Ya agregaste la cantidad máxima solicitadas</div>' + '</td>'
				+ '<td>'+ '<button title="Eliminar" type="button" class="red is-icon text-white" data-delay="50" onclick="borrarFila(this)">X</button>'
				+'</td></tr>'		
			);
		};
	</script>
@endsection