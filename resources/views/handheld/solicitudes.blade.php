@extends('handheld.layout')

@section('title', 'Handheld - Solicitudes')

@section('content')
	<p style="text-align: center;margin-bottom:0;">Selecciona el pedido disponible <b>asignado:</b></p>
	<div id="navigation">
		@foreach ($solicitudes as $solicitud)
		{{-- {{dump($solicitud)}} --}}
			{{ link_to(companyRoute('handheld.solicitudes-solicitud', ['id' => $solicitud->id_detalle]), $solicitud->pedidos->no_pedido, ['class'=>'list-item']) }}
		@endforeach
	</div><br>
	{{ link_to(route('home'), 'Regresar', ['class'=>'square actionBtn red','style'=>'width:100%;']) }}
@endsection