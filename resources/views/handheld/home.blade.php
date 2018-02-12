@extends('handheld.layout')

@section('title', 'Hand held')

@section('content')
    <div class="wrapper">
        {{ link_to(companyRoute('handheld.inventarios'), 'Inventario', ['class'=>'square blue','style'=>'line-height:60px;']) }}
    	{{ link_to(companyRoute('handheld.solicitudes'), 'Surtido de pedidos', ['class'=>'square green','style'=>'line-height:60px;']) }}
    	{{ link_to(companyRoute('handheld.ordenes'), 'Recibo OC', ['class'=>'square yellow','style'=>'line-height:60px;']) }}
    	{{ link_to(companyRoute('handheld.sucursales'), 'Cambio de Ubicación', ['class'=>'square teal','style'=>'line-height:60px;']) }}

        {!! Form::open(['route' => 'logout', 'before' => 'csrf', 'id' => 'logout-form', 'class' => 'hidden']) !!}
        	{!! Form::button('Cerrar sesión', ['type' =>'submit', 'class'=>'square red', 'style'=>'width: 98%; height: 44px; margin-top: 20px;']) !!}
        {!! Form::close() !!}
    </div>
@endsection
