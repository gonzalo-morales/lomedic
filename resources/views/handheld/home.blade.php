@extends('handheld.layout')

@section('title', 'Hand held')

@section('content')
    <div class="wrapper">
        <button class="square blue">Surtidos <br>de Pedidos</button>
        <button class="square green">Recibo OC</button>
        <button class="square yellow">Cambio <br>Ubicación</button>
        {{ link_to(companyRoute('handheld.index'), 'Inventario', ['class'=>'square blue']) }}

        {!! Form::open(['route' => 'logout', 'before' => 'csrf', 'id' => 'logout-form', 'class' => 'hidden']) !!}
        <input style="width: 99%; height: 44px; margin-top: 20px;" class="square red" type="submit" value="Cerrar sesión">
        {!! Form::close() !!}
    </div>
@endsection
