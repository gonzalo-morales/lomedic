
@section('content-width', 's12')

@section('form-content')
{{ Form::setModel($data) }}
    <div class="row">
        <div class="input-group col-md-12 col-xs-12">
{{--                {{ Form::label('aplicacion', '',['class'=>'col-form-label']) }}--}}
                {{ Form::text('aplicacion', '', ['id'=>'aplicacion','class'=>'form-control','placeholder'=>'Aplicacion']) }}
                <span class="input-group-addon">
                    {{ Form::hidden('activo', 0) }}
                    {{ Form::label('activo', 'Activo',['class'=>'col-form-label']) }}
                    {{ Form::checkbox('activo', 1, old('activo'), ['id'=>'activo','class'=>'']) }}
                </span>
            {{ $errors->has('aplicacion') ? HTML::tag('span', $errors->first('aplicacion'), ['class'=>'help-block deep-orange-text']) : '' }}
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