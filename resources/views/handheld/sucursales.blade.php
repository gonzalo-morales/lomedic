@extends('handheld.layout')

@section('title', 'Handheld - Sucursales')
{{ session('message') ? HTML::tag('p', session('message'), ['class'=>'success-message']) : '' }}
@section('content')
	<p style="text-align: center;margin-bottom:0;">Selecciona la <b>Sucursal:</b></p>
	<div id="navigation">
		@foreach ($sucursales as $sucursal)
		{{-- {{dump($sucursal)}} --}}
			{{ link_to(companyRoute('handheld.almacenes', ['id' => $sucursal->id_sucursal]), $sucursal->sucursal, ['class'=>'list-item']) }}
		@endforeach
	</div><br>
	{{ link_to(companyAction('HomeController@index'), 'Regresar', ['class'=>'square actionBtn red','style'=>'width:100%;']) }}
@endsection