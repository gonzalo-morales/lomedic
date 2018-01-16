@extends('handheld.layout')

@section('title', 'Handheld - Almacenes')
@section('content')
		@if($almacenes == '[]')
			<p style="text-align: center">Al parecer esta sucursal no cuenta con <b>Almacenes</b> registrados :( prueba con otra</p>
		@else
			<p style="text-align: center;margin-bottom:0;">Selecciona el <b>Almacén:</b></p>
			<div id="navigation">
			@foreach ($almacenes as $almacen)
			{{-- {{dump($sucursal)}} --}}
				{{ link_to(companyRoute('handheld.movimientos', ['id' => $almacen->id_almacen, 'id_sucursal'=>$sucursal->id]), $almacen->almacen, ['class'=>'list-item']) }}
			@endforeach
			</div><br>
		@endif
	{{ link_to(companyRoute('handheld.sucursales'), 'Regresar a las sucursales', ['class'=>'square actionBtn green','style'=>'width:100%;']) }}
	{{ link_to(route('home'), 'Cancelar', ['class'=>'square actionBtn red','style'=>'width:100%;']) }}
@endsection