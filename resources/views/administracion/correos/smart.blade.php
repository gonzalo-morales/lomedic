@section('content-width', 's12')

@section('form-content')
{{ Form::setModel($data) }}
<div class="row">
	<div class="form-group col-md-4 col-xs-12">
		{{ Form::label('correo', 'Correo:') }}
		{{ Form::text('correo', null, ['id'=>'correo','class'=>'form-control']) }}
		{{ $errors->has('correo') ? HTML::tag('span', $errors->first('correo'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
	<div class="form-group col-md-4 col-xs-12">
		{{ Form::label('fk_id_empresa', 'Empresa:') }}
		{{ Form::select('fk_id_empresa', (isset($companies) ? $companies : []), null, ['id'=>'fk_id_empresa','class'=>'form-control select']) }}
		{{ $errors->has('fk_id_empresa') ? HTML::tag('span', $errors->first('fk_id_empresa'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
	<div class="form-group col-md-4 col-xs-12">
		{{ Form::label('fk_id_usuario', 'Usuario:') }}
		{{ Form::select('fk_id_usuario', (isset($users) ? $users : []), null, ['id'=>'fk_id_usuario','class'=>'form-control select']) }}
		{{ $errors->has('fk_id_usuario') ? HTML::tag('span', $errors->first('fk_id_usuario'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
	<div  class="col-md-12 text-center mt-4">
		<div class="alert alert-warning" role="alert">
			Recuerda que al no estar <b>activo</b>, este <b>dato</b> no se mostrará en los modulos correspondientes que se requieran.
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