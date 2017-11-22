@extends('handheld.layout')

@section('title', 'Handheld - Inventario')

@section('content')
{{ session('message') ? HTML::tag('p', session('message'), ['class'=>'success-message']) : '' }}

<form id="form" action="{{ companyRoute('handheld.inventarios-inventario-detalle', ['inventario' => $inventario->getKey()]) }}" method="post">
	{{ csrf_field() }}
	<input type="hidden" name="fk_id_inventario" value="{{ $inventario->getKey() }}">
	<input type="hidden" name="fk_id_almacen" value="{{ $inventario->fk_id_almacen }}">
	<input type="hidden" id="fk_id_ubicacion" name="fk_id_ubicacion" value="{{ $ubicacion->id_ubicacion ?? '' }}">
	<div>
		<table class="table-columns">
			<tr>
				<td class="column right">
					<label>Almacen</label>
				</td>
				<td class="column">
					<input class="form-control readonly" type="text" value="{{ $inventario->almacen->almacen }}" readonly>
				</td>
			</tr>
			<tr>
				<td class="column right">
					<label for="ubicacion">Ubicación</label>
				</td>
				<td class="column">
					<input class="form-control" id="ubicacion" type="text" value="{{ $ubicacion->codigo_barras ?? '' }}">
				</td>
			</tr>
			<tr>
				<td class="column right">
					<label>Nomenclatura</label>
				</td>
				<td class="column">
					<input class="form-control readonly nomenclatura" name="nomenclatura" type="text" value="{{ $ubicacion->nomenclatura ?? '' }}" readonly>
				</td>
			</tr>
			<tr>
				<td class="column right">
					<label for="codigo_barras">Código</label>
				</td>
				<td class="column">
					<input class="form-control" id="codigo_barras" name="codigo_barras" type="text" value="{{ $upc->upc ?? '' }}">
				</td>
			</tr>
			<tr>
				<td class="column right">
					<label>Descripción</label>
				</td>
				<td class="column">
					<textarea class="form-control readonly" id="descripcion" readonly>{{ $upc->descripcion ?? '' }}</textarea>
				</td>
			</tr>
		</table>

        <div class="margin-top wrapper">
            {{ link_to(companyRoute('handheld.inventarios'), 'Regresar', ['class'=>'square actionBtn green']) }}
            <button type="submit" class="square actionBtn blue">Siguiente</button>
            {{ link_to(companyRoute('handheld.inventarios'), 'Cancelar', ['class'=>'square actionBtn red']) }}
        </div>

	</div>
</form>

<script type="text/javascript">

	$(function() {
		// alert(2)

		$(document).on("keypress", "#form", function(event) {
		    return event.keyCode != 13;
		});

		$('#ubicacion').on('keydown', function(e){
			// Si enter
			if (e.keyCode == 13) {
				$.get('{{ companyRoute('api.index', ['entity' => 'inventarios.ubicaciones'], false) }}', {
					'param_js': '{{$ubicacion_js ?? ''}}',
					'$codigo_barras': e.target.value,
					'$fk_id_almacen': {{ $inventario->fk_id_almacen}}
					// conditions: [{'where': ['codigo_barras', e.target.value]}, {'where': ['fk_id_almacen', {{ $inventario->fk_id_almacen}}]}, {'where': ['activo','true']}],
					// only: ['id_ubicacion','nomenclatura']
				}, function(response){
					if (response.length > 0) {
						$('#fk_id_ubicacion').val(response[0].id_ubicacion);
						$('.nomenclatura').val(response[0].nomenclatura);
						$('#codigo_barras').focus();
					} else {
						$('.nomenclatura').val('invalido');
						$('#ubicacion').val('').focus();
					}
				})
			}
		})

		$('#codigo_barras').on('keydown', function(e){
			// Si enter
			if (e.keyCode == 13) {
				$.get('{{ companyRoute('api.index', ['entity' => 'inventarios.upcs'], false) }}', {
					'param_js': '{{$codigo_barras_js ?? ''}}',
					'$upc': e.target.value
					// conditions: [{'where': ['upc', e.target.value]}],
					// only: ['descripcion']
				}, function(response){
					if (response.length > 0) {
						$('#descripcion').val(response[0].descripcion);
						$('.codigo').val( e.target.value)
						$('#codigo_barras').blur();
					} else {
						$('#descripcion').val('invalido');
						$('#codigo_barras').val('').focus();
					}
				})
			}
		})

        $('#form').on('submit', function(e){
            e.preventDefault();

			if (($('.nomenclatura').val() != '' && $('#descripcion').val() != '') && ($('.nomenclatura').val() != 'invalido' && $('#descripcion').val() != 'invalido')) {
				// alert('pasa')
			} else {

				if (!($('.nomenclatura').val() != '' && $('.nomenclatura').val() != 'invalido')) {
					alert('Ubicación requerida')
					$('#ubicacion').focus()
					return false;
				}

				if (!($('#descripcion').val() != '' && $('#descripcion').val() != 'invalido')) {
					alert('Código requerido')
					$('#codigo_barras').val('').focus();
					return false;
				}

				// No pasa
				return false;
			}

            // Enviamos formulario
            this.submit();
        });

		// init
		$('#ubicacion').focus()
	});
</script>

@endsection