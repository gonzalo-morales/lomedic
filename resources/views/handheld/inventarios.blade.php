@extends('handheld.layout')

@section('title', 'Hand held')

@section('content')
	<p style="text-align: center;">Selecciona el almacén disponible:</p><br>
	<div id="navigation">
		@foreach ($inventarios as $inventario)
			{{ link_to(companyRoute('handheld.inventarios-inventario', ['id' => $inventario->id_inventario]), $inventario->almacen->almacen, ['class'=>'list-item']) }}
		@endforeach
	</div>
@endsection