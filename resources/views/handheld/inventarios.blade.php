@extends('handheld.layout')

@section('title', 'Hand held')

@section('content')
	<p style="text-align: center;">Selecciona el almacén disponible:</p><br>
	<div id="navigation">
		@foreach ($almacenes as $id_almacen => $almacen)
			{{ link_to(companyRoute('handheld.almacen', ['id' => $id_almacen]), $almacen, ['class'=>'list-item']) }}
		@endforeach
	</div>
@endsection