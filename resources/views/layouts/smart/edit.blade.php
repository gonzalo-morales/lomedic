@extends('layouts.smart.create')

@section('title', currentEntityBaseName() . '@Editar')

@section('form-header')
    {!! Form::open(['method'=>'put', 'url' => companyRoute('update'), 'id' => 'form-model', 'class' => 'col-md-12']) !!}
@endsection

@section('form-actions')
    <div class="col-md-12">
        <div class="text-right">
            {{ Form::button('Guardar', ['type' =>'submit', 'class'=>'btn btn-primary']) }}
            {{ link_to(companyRoute('index'), 'Cerrar', ['class'=>'btn btn-default']) }}
        </div>
    </div>
@endsection

@section('form-title')
    {{ HTML::tag('h1','Editar '. str_singular(currentEntityBaseName())) }}
@endsection
