
@section('content-width', 's12')

@section('form-content')
{{ Form::setModel($data) }}
<div class="row">
    <div class="form-group col-md-6 col-xs-12">
        {{ Form::label('forma_farmaceutica', 'Forma Farmaceutica') }}
        {{ Form::text('forma_farmaceutica', null, ['id'=>'forma_farmaceutica','class'=>'form-control']) }}
        {{ $errors->has('forma_farmaceutica') ? HTML::tag('span', $errors->first('forma_farmaceutica'), ['class'=>'help-block deep-orange-text']) : '' }}
    </div>
    <div class="form-group col-md-6 col-xs-12">
        {{ Form::label('descripcion', 'Descripcion') }}
        {{ Form::text('descripcion', null, ['id'=>'descripcion','class'=>'form-control']) }}
        {{ $errors->has('descripcion') ? HTML::tag('span', $errors->first('descripcion'), ['class'=>'help-block deep-orange-text']) : '' }}
    </div>
    <div  class="col-md-12 text-center mt-2">
        <div class="alert alert-warning" role="alert">
            Recuerda que al no estar <b>activo</b>, este <b>dato</b> no se mostrarÃ¡ en los modulos correspondientes que se requieran.
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