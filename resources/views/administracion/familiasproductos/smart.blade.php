
@section('content-width', 's12')

@section('form-content')
{{ Form::setModel($data) }}
{{--{{dd($data)}}--}}
<div class="row">
    <div class="form-group col-md-6 col-xs-12">
        {{ Form::label('descripcion', 'Familia') }}
        {{ Form::text('descripcion', null, ['id'=>'descripcion','class'=>'form-control']) }}
        {{ $errors->has('descripcion') ? HTML::tag('span', $errors->first('descripcion'), ['class'=>'help-block deep-orange-text']) : '' }}
    </div>
    <div class="form-group col-md-6 col-xs-12">
        {{ Form::label('nomenclatura', 'Nomenclatura') }}
        {{ Form::text('nomenclatura', null, ['id'=>'nomenclatura','class'=>'form-control']) }}
        {{ $errors->has('nomenclatura') ? HTML::tag('span', $errors->first('nomenclatura'), ['class'=>'help-block deep-orange-text']) : '' }}
    </div>
    <div class="form-group col-md-5 col-xs-12">
        {{ Form::label('fk_id_tipo_producto', 'Tipo') }}
{{--        {{ Form::text('tipo', null, ['id'=>'tipo','class'=>'form-control']) }}--}}
        {{Form::select('fk_id_tipo_producto',isset($product_types)?$product_types:[],null,['class'=>'form-control'])}}
        {{ $errors->has('tipo') ? HTML::tag('span', $errors->first('tipo'), ['class'=>'help-block deep-orange-text']) : '' }}
    </div>
    <div class="form-group col-md-5 col-xs-12">
        {{ Form::label('tipo_presentacion', 'Presentacion') }}
        {{ Form::select('tipo_presentacion',
        ['1'=>'Cantidad',
        '2'=>'Cantidad y Unidad',
        '3'=>'Ampolletas (Ámpulas)',
        '4'=>'Dosis'],
        null, ['id'=>'tipo_presentacion','class'=>'form-control']) }}
        {{ $errors->has('tipo_presentacion') ? HTML::tag('span', $errors->first('tipo_presentacion'), ['class'=>'help-block deep-orange-text']) : '' }}
    </div>
    <div class="form-check col-md-2 col-xs-12">
        {{ Form::hidden('activo', 0) }}
        {{ Form::checkbox('activo', 1, old('activo'), ['id'=>'activo','class'=>'']) }}
        {{ Form::label('activo', 'Activo') }}
        {{ $errors->has('activo') ?  HTML::tag('span', $errors->first('activo'), ['class'=>'help-block deep-orange-text']) : '' }}
    </div>
</div>
@endsection

{{-- DONT DELETE --}}
@if (Route::currentRouteNamed(currentRouteName('index')))
    @include('layouts.smart.index')
@endif

@if (Route::currentRouteNamed(currentRouteName('create')))
    @include('layouts.smart.create')
@endif

@if (Route::currentRouteNamed(currentRouteName('edit')))
    @include('layouts.smart.edit')
@endif

@if (Route::currentRouteNamed(currentRouteName('show')))
    @include('layouts.smart.show')
@endif

@if (Route::currentRouteNamed(currentRouteName('export')))
    @include('layouts.smart.export')
@endif