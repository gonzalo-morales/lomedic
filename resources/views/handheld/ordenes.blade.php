@extends('handheld.layout')

@section('title', 'Handheld - Ordenes')
{{ session('message') ? HTML::tag('p', session('message'), ['class'=>'success-message']) : '' }}
@section('content')
	@if($ordenes == '[]')
		<p style="text-align: center">Al parecer no cuentas con <b>Ordenes de compra activas</b>, verifica la información con tu superior.</p>
	@else
		<p style="text-align: center;margin-bottom:0;">Selecciona el <b>Orden de compra:</b></p>
		<div id="navigation">
			@foreach ($ordenes as $orden)
			{{-- {{dump($orden)}} --}}
				{{ link_to(companyRoute('handheld.orden-compra', ['id' => $orden->id_documento]), $orden->id_documento, ['class'=>'list-item']) }}
			@endforeach
		</div><br>
	@endif
	{{ link_to(companyRoute('.'), 'Regresar', ['class'=>'square actionBtn red','style'=>'width:100%;']) }}
@endsection