
@section('content-width', 's12')

@section('form-content')
{{ Form::setModel($data) }}
<div class="row">
    <div class="form-group col-lg-4 col-md-6 col-xs-12">
        {{ Form::label('tipo_producto', 'Tipo Producto') }}
        {{ Form::text('tipo_producto', null, ['id'=>'tipo_producto','class'=>'form-control']) }}
        {{ $errors->has('tipo_producto') ? HTML::tag('span', $errors->first('tipo_producto'), ['class'=>'help-block deep-orange-text']) : '' }}
    </div>
    <div class="form-group col-lg-4 col-md-4 col-xs-12">
        {{ Form::label('nomenclatura', 'Nomenclatura') }}
        {{ Form::text('nomenclatura', null, ['id'=>'nomenclatura','class'=>'form-control']) }}
        {{ $errors->has('nomenclatura') ? HTML::tag('span', $errors->first('nomenclatura'), ['class'=>'help-block deep-orange-text']) : '' }}
    </div>
    <div class="form-group col-lg-4 col-md-2 col-xs-12">
        {{ Form::label('prioridad', 'Prioridad') }}
        {{Form::number('prioridad', null, ['id'=>'prioridad','class'=>'form-control'])}}
        {{ $errors->has('prioridad') ? HTML::tag('span', $errors->first('prioridad'), ['class'=>'help-block deep-orange-text']) : '' }}
    </div>
    <div  class="col-md-12 text-center mt-2">
        <div class="alert alert-warning" role="alert">
            Recuerda que al no estar <b>activo</b>, este <b>dato</b> no se mostrara en los modulos correspondientes que se requieran.
        </div>
        <div data-toggle="buttons">
            <label class="btn btn-secondary form-check-label {{ !empty($data->activo) || old('activo') ? 'active':''}}">
                {{Form::checkbox('activo',true,old('activo'),['id'=>'activo',Route::currentRouteNamed(currentRouteName('show'))?'disabled':''])}}
                Activo
            </label>
        </div>
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