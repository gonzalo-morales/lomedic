@extends('handheld.layout')

@section('title', 'Handheld - Solicitud')

@section('content')
{{ session('message') ? HTML::tag('p', session('message'), ['class'=>'success-message']) : '' }}

<form id="form" method="post">
	{{ csrf_field() }}
	<input type="hidden" name="fk_id_pedido" value="{{ $solicitud }}">
	<input type="hidden" name="fk_id_detalle_solicitud" value="{{ $solicitud->id_detalle }}">
	<div>
		<table class="table-columns">
			<tr>
				<td class="column right">
					<label>SKU: <b>{{ $solicitud->sku->sku }}</b></label>
					<input class="form-control readonly display-none" name="fk_id_sku" type="text" value="{{ $solicitud->fk_id_sku ?? '' }}" readonly>
				</td>
				<td class="column left">
					<label>UPC: <b>{{ $solicitud->upc->upc }}</b></label>
					<input class="form-control readonly display-none" name="fk_id_sku" type="text" value="{{ $solicitud->fk_id_upc ?? '' }}" readonly>
				</td>
			</tr>
			<tr>
				<td class="column right">
					<label for="ubicacion">Producto Escaneado</label>
				</td>
				<td class="column">
					<input class="form-control" name="fk_id_upc" type="text" value="">
				</td>
			</tr>
			<tr>
				<td class="column right">
					<label>Cantidad solicitada</label>
				</td>
				<td class="column">
					<input class="form-control readonly total_db" name="cantidad_solicitada_salida" type="text" value="{{ $solicitud->cantidad_solicitada_salida }}" readonly>
				</td>
			</tr>
			<tr>
				<td class="column right">
					<label for="cantidad_escaneada">Cantidad escaneada</label>
				</td>
				<td class="column">
					<input class="form-control" id="cantidad_escaneada" name="cantidad_escaneada" type="text" value="0">
				</td>
			</tr>
			<tr>
				<td class="column right">
					<label>Cantidad Restante</label>
				</td>
				<td class="column">
					<input class="form-control readonly cantidad_res" name="falta_surtir" type="text" value="0" readonly>
				</td>
			</tr>
		</table>

        <div class="margin-top wrapper">
            {{ link_to(companyRoute('handheld.solicitudes'), 'Regresar', ['class'=>'square actionBtn green']) }}
            <button type="submit" class="square actionBtn blue">Guardar</button>
            {{ link_to(route('home'), 'Cancelar', ['class'=>'square actionBtn red']) }}
        </div>

	</div>
</form>

<script type="text/javascript">
	$(window).on('load',function(){
		console.log('asdf')
	});
</script>

@endsection