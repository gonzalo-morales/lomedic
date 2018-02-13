@extends('handheld.layout')

@section('title', 'Hand held')

@section('content')
	<p style="text-align: center;margin-bottom:0;">Selecciona el almacén <b>disponible:</b></p>
	<div id="navigation">
		@foreach ($inventarios as $inventario)
			{{ link_to(companyRoute('handheld.inventarios-inventario', ['id' => $inventario->id_inventario]), $inventario->almacen->almacen, ['class'=>'list-item']) }}
		@endforeach
	</div><br>
	{{ link_to(companyAction('HomeController@index'), 'Regresar', ['class'=>'square actionBtn red','style'=>'width:100%;']) }}
@endsection