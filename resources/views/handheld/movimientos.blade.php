@extends('handheld.layout')

@section('title', 'Handheld - Stocks')
{{ session('message') ? HTML::tag('p', session('message'), ['class'=>'success-message']) : '' }}
@section('content')
		@if($stock == '[]')
			<p style="text-align: center">Al parecer esta sucursal no cuenta con <b>Skus</b> registrados :( prueba con otra</p>
		@else
			<p style="text-align: center;margin-bottom:0;">Selecciona el <b>Sku </b>requerido:</p>
			<div id="navigation">
			@foreach ($stock as $stock)
				{{ link_to(companyRoute('handheld.movimiento', ['id' => $stock->id_stock, 'id_almacen' => $almacen, 'id_sucursal'=>$sucursal]), $stock->sku->sku, ['class'=>'list-item']) }}
				<div class="text-center">
					UPC's: {{$stock->upc->upc}},
					Stock: <b>{{$stock->stock}}</b>
				</div><br>
			@endforeach
			</div><br>
		@endif
	{{ link_to(companyRoute('handheld.sucursales'), 'Seleccionar otra sucursal', ['class'=>'square actionBtn green','style'=>'width:100%;']) }}
	{{ link_to(companyAction('HomeController@index'), 'Cancelar', ['class'=>'square actionBtn red','style'=>'width:100%;']) }}
@endsection