@extends('handheld.layout')

@section('title', 'Handheld - Solicitudes')
{{ session('message') ? HTML::tag('p', session('message'), ['class'=>'success-message']) : '' }}
@section('content')
{{--  {{dd($solicitudes)}}  --}}
	<p style="text-align: center;margin-bottom:0;">Selecciona el pedido disponible <b>asignado:</b></p>
	<div id="navigation">
		@foreach ($solicitudes as $solicitud)
		{{--  {{dd($solicitud)}}  --}}
			{{ link_to(companyRoute('handheld.solicitudes-solicitud', ['id' => $solicitud->id_detalle]), $solicitud->pedidos->no_pedido, ['class'=>'list-item']) }}
			<p class="text-center" style="margin-top:0;margin-bottom:10px;">Falta surtir: <strong>{{$solicitud->falta_surtir}}</strong></p>
		@endforeach
	</div><br>
	{{ link_to(companyAction('HomeController@index'), 'Regresar', ['class'=>'square actionBtn red','style'=>'width:100%;']) }}
@endsection