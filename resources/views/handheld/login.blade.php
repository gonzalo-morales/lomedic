@extends('handheld.layout')

@section('title', 'Iniciar Sesion')

@section('content')
    {!! Form::open(['route' => 'login', 'id' => 'form-login', 'class' => 'card-body center']) !!}
    <div class="content">
        <label for="usuario">Usuario</label>
        {{ Form::text('usuario', null, ['id'=>'usuario','class'=>'form-control', 'placeholder'=>'* Usuario','autofocus'=>true]) }}
        {{ $errors->has('usuario') ? HTML::tag('span', $errors->first('usuario'), ['class'=>'form-text text-white bg-danger p-1 m-0 rounded-bottom']) : '' }}

        <label for="password">Contraseña</label>
        {{ Form::password('password', ['id'=>'password','class'=>'validate form-control','placeholder'=>'* Contraseña']) }}
        {{ $errors->has('password') ? HTML::tag('span', $errors->first('password'), ['class'=>'form-text text-white bg-danger p-1 m-0 rounded-bottom']) : '' }}
    </div><br>
    <div class="wrapper" style="text-align: center;">
        <input type="submit" style="width: 32%; height: 44px;" class="square blue" value="Entrar">
    </div>
    {!! Form::close() !!}
@endsection
