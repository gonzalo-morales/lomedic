@extends('handheld.layout')

@section('title', 'Handheld - Ordenes')
{{ session('message') ? HTML::tag('p', session('message'), ['class'=>'success-message']) : '' }}
@section('content')
	@if($ordenes == '[]')
		<p style="text-align: center">Al parecer no cuentas con <b>Ordenes de compra activas</b>, verifica la información con tu superior.</p>
	@else
		<p style="text-align: center;margin-bottom:0;">Selecciona el <b>Orden de compra:</b></p><br>
		<div id="navigation">
			<table class="table-columns">
				<tr>
					<td class="column right">
							<label for="filter">Buscar la orden de compra:</label>
					</td>
					<td>
						<input id="filter" class="form-control" type="text">
					</td>
				</tr>
			</table>
			@foreach ($ordenes as $orden)
			{{-- {{dump($orden)}} --}}
				{{ link_to(companyRoute('handheld.orden-compra', ['id' => $orden->id_documento]), $orden->id_documento, ['class'=>'list-item']) }}
			@endforeach
		</div><br>
	@endif
	{{ link_to(companyAction('HomeController@index'), 'Regresar', ['class'=>'square actionBtn red','style'=>'width:100%;']) }}

	<script text="text/javascript">
		$(function () {
			$('#filter').on('keyup', function () {
				var filter = $(this).val();
				console.log(filter)
				$(".list-item").each(function(){
					if ($(this).text().search(new RegExp(filter))) {
						$(this).fadeOut();
					} else {
						$(this).show();
					}
				});
			});
		});
	</script>
@endsection