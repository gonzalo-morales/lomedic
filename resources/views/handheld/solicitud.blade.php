@extends('handheld.layout')

@section('title', 'Handheld - Solicitud')

@section('content')
{!! Form::open(['url' => companyRoute('handheld.solicitudes-solicitud-detalle-store'),'method'=>'post','enctype' => 'multipart/form-data','id'=>'form']) !!}
	<input type="hidden" name="fk_id_pedido" value="{{ $solicitud->fk_id_pedido }}">
	<input type="hidden" name="id_detalle" value="{{ $solicitud->id_detalle }}">
	<input type="hidden" name="fk_id_solicitud" value="{{ $solicitud->fk_id_solicitud }}">
	<input type="hidden" name="fk_id_almacen" value="{{ $solicitud->fk_id_almacen }}">
	<input type="hidden" name="fk_id_empleado" value="{{ $solicitud->fk_id_empleado }}">
	<input type="hidden" name="cantidad" value="{{ $solicitud->cantidad }}">
	<div>
		<table class="table-columns">
			<tr>
				<td class="column right">
					<label>SKU: <b>{{ $solicitud->sku->sku }}</b></label>
					<input class="form-control readonly display-none" name="fk_id_sku" type="text" value="{{ $solicitud->fk_id_sku }}" readonly>
				</td>
				<td class="column left">
					<label>UPC: <b><span id="upc">{{ $solicitud->upc->upc }}</span></b></label>
					<input class="form-control readonly display-none" name="fk_id_upc" type="text" value="{{ $solicitud->fk_id_upc }}" readonly>
				</td>
			</tr>
			<tr>
				<td class="column right">
					<label for="ubicacion">Producto Escaneado</label>
				</td>
				<td class="column left" style="position:relative;">
				<div id="loading" class="h-100 text-center text-white align-middle loadingData display-none" style="height: 30px">
              		Validando...
            	</div>
					<input id="scan" class="form-control" type="text" value="">
				</td>
			</tr>
			<tr>
				<td class="column right">
					<label>Cantidad solicitada</label>
				</td>
				<td class="column left">
					<input id="total_db" class="form-control readonly total_db" name="cantidad_solicitada_salida" type="text" value="{{ $solicitud->cantidad_solicitada_salida }}" readonly>
				</td>
			</tr>
			<tr>
				<td class="column right">
					<label for="cantidad_escaneada">Cantidad escaneada</label>
				</td>
				<td class="column left">
					<input class="form-control readonly" style="font-weight: 600;" id="cantidad_escaneada" name="cantidad_escaneada" type="text" value="{{ $solicitud->cantidad_escaneada ?? 0 }}" readonly>
				</td>
			</tr>
			<tr>
				<td class="column right">
					<label>Cantidad Restante</label>
				</td>
				<td id="cantidadRestante" class="column left">
					<input id="cantidad_restante" class="form-control readonly cantidad_res" name="falta_surtir" type="text" value="{{ $solicitud->falta_surtir }}" readonly>
					<span id="validator" class="text-red display-none">Has sobrepasado la cantidad</span>
				</td>
			</tr>
		</table>

        <div class="margin-top wrapper">
            {{ link_to(companyRoute('handheld.solicitudes'), 'Regresar', ['class'=>'square actionBtn green']) }}
            <button id="submit" type="submit" class="square actionBtn blue">Guardar</button>
            {{ link_to(companyAction('HomeController@index'), 'Cancelar', ['class'=>'square actionBtn red']) }}
        </div>

	</div>
{!! Form::close() !!}

<script type="text/javascript">
	$(document).ready(function () {	
        $('#scan').focus();
        $('#submit').attr('disabled',true);
		//Con esto evitamos que haga submit
		$(document).on("keypress", "#form", function(event) {
		    return event.keyCode != 13;
		});
		if($('#cantidad_restante').val() == 0){
			$('#cantidad_restante').val($('.total_db').val());
		}
		// Damos de alta las variables
		var valorOriginal = $('.total_db').val();
		var valorRestante = parseInt($('#cantidad_restante').val());
		var num = parseInt($('#cantidad_escaneada').val());
		//Evento para el campo del scan
		$("#scan").on("keydown", function(event){

		    if (event.keyCode == 13) {
		    	$('#loading').show();
		    	//Hacemos una petición para validar el UPC
				$.get('{{ companyRoute('api.index', ['entity' => 'inventarios.upcs'], false) }}', {
					'param_js': '{{$codigo_barras_js ?? ''}}',
					'$upc': event.target.value
					// conditions: [{'where': ['upc', e.target.value]}],
					// only: ['descripcion']
				}, function(response){
					if (response.length > 0) {
						$('#upc').attr('style','color:green');
						$('#scan').attr('disabled', true);
						// Si tiene datos...
						if(valorOriginal <= num){
							alert('Al parecer ya cuentas con todos los productos escaneados que requerías');
							event.preventDefault();
							$('#scan').val('').attr('disabled', true);
						} else{
							$('#scan').val('');
							$('#scan').focus();
							//access `event.code` - barcode data
							num = num + 1;
							valorRestante = valorRestante - 1;
							$('#cantidad_escaneada').val(num);
							$('#cantidad_restante').val(valorRestante);
							$('#upc').attr('style','color:black');
							// alert('ya acabe')
							$('#loading').hide();
							$('#submit').attr('disabled',false);
							$('#scan').val('').attr('disabled', false).focus();
						}
					} else {
						alert('El UPC que tratas de escanear no es válido, verifica que sea el mismo y tenga existencias');
						$('#loading').hide();
						$('#scan').val('').attr('disabled', false).focus();
					}
				})
		    }

		});
	});
</script>
@endsection