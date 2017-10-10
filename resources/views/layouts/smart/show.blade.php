@extends('layouts.smart.create')

@section('title', currentEntityBaseName() . '@Ver')

@section('fieldset', 'disabled')

@section('form-header')
    {!! Form::open(['url' => '#', 'id' => 'form-model', 'class' => 'col-md-12']) !!}
@endsection

@section('form-actions')
    <div class="col-md-12 col-xs-12">
        <div class="text-right">
            {{ link_to(companyRoute('index'), 'Cerrar', ['class'=>'btn btn-default']) }}
            @can('update', currentEntity())
            {{ link_to(companyRoute('edit'), 'Editar', ['class'=>'btn btn-info']) }}
            @endcan
            @can('create', currentEntity())
            {{ link_to(companyRoute('create'), 'Nuevo', ['class'=>'btn btn-primary']) }}
            @endcan
            @yield('extraButtons')
        </div>
    </div>
@endsection

@section('form-title')
    {{ HTML::tag('h1', 'Datos del '. str_singular(currentEntityBaseName())) }}
@endsection
