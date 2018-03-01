@extends('layouts.smart.create')

@section('title', currentEntityBaseName().' '.cTrans('titles.show','Ver'))

@section('fieldset', 'disabled')

@section('form-header')
    {!! Form::open(['url' => '#', 'id' => 'form-model', 'class' => 'col-md-12']) !!}
@endsection

@section('header-bottom')
	@parent
    <script type="text/javascript">
    	$('.select2').select2({disabled: true});
    </script>
@endsection

@section('form-actions')
    <div class="col-md-12 col-xs-12">
        <div class="text-right">
            @yield('left-actions')
            @can('create', currentEntity())
                {{ link_to(companyRoute('create'), cTrans('forms.create','Nuevo'), ['class'=>'btn btn-primary progress-button']) }}
            @endcan
            @can('update', currentEntity())
            {{ link_to(companyRoute('edit'), cTrans('forms.edit','Editar'), ['class'=>'btn btn-info progress-button']) }}
            @endcan
            @yield('extraButtons')
            {{ link_to(companyRoute('index'), cTrans('forms.close','Cerrar'), ['class'=>'btn btn-default progress-button']) }}
            @yield('right-actions')
        </div>
    </div>
@endsection

@section('form-title')
    {{ HTML::tag('h1',cTrans('titles.show','Ver').' '.str_singular(currentEntityBaseName()),['class' => 'display-4']) }}
@endsection