@extends('handheld.layout')

@section('title', 'Hand held')

@section('content')
    <div class="wrapper">
        {{ link_to(companyRoute('handheld.inventarios'), 'Inventario', ['class'=>'square blue','style'=>'line-height:63px;']) }}
    	{{ link_to(companyRoute(''), 'Surtido de pedidos', ['class'=>'square green','style'=>'line-height:63px;']) }}
    	{{ link_to(companyRoute(''), 'Recibo OC', ['class'=>'square yellow','style'=>'line-height:63px;']) }}
    	{{ link_to(companyRoute(''), 'Cambio de Ubicación', ['class'=>'square green','style'=>'line-height:63px;']) }}

        {!! Form::open(['route' => 'logout', 'before' => 'csrf', 'id' => 'logout-form', 'class' => 'hidden']) !!}
        <input style="width: 99%; height: 44px; margin-top: 20px;" class="square red" type="submit" value="Cerrar sesión">
        {!! Form::close() !!}
    </div>
@endsection
