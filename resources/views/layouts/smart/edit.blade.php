@extends('layouts.smart.create')

@section('title', currentEntityBaseName().' '.cTrans('titles.edit','Editar'))

@section('form-header')
    {!! Form::open(['method'=>'put', 'url' => companyRoute('update'), 'id' => 'form-model', 'class' => 'col-md-12', 'enctype' => 'multipart/form-data']) !!}
@endsection

@section('form-actions')
    <div class="col-md-12 col-xs-12">
        <div class="text-right">
        	@yield('left-actions')
            {{ Form::button(cTrans('forms.save',Guardar), ['type' =>'submit', 'class'=>'btn btn-primary progress-button']) }}
            {{ link_to(companyRoute('index'), cTrans('forms.close','Cerrar'), ['class'=>'btn btn-default progress-button']) }}
            @yield('right-actions')
        </div>
    </div>
@endsection

@section('form-title')
    {{ HTML::tag('h1',cTrans('titles.edit','Editar').' '.str_singular(currentEntityBaseName()),['class' => 'display-4']) }}
@endsection