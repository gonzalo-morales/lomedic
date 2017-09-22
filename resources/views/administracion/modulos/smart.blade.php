
@section('content-width', 's12 m7 xl8 offset-xl2')

@section('form-content')
{{ Form::setModel($data) }}
<div class="row">
	<div class="input-field col s4">
		{{ Form::text('nombre', null, ['id'=>'nombre','class'=>'validate']) }}
		{{ Form::label('nombre', '* Nombre') }}
		{{ $errors->has('nombre') ? HTML::tag('span', $errors->first('nombre'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
	<div class="input-field col s4">
		{{ Form::text('url', null, ['id'=>'url','class'=>'validate']) }}
		{{ Form::label('url', '* Url') }}
		{{ $errors->has('url') ? HTML::tag('span', $errors->first('url'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
	<div class="input-field col s4">
		{{ Form::text('icono', null, ['id'=>'icono','class'=>'validate']) }}
		{{ Form::label('icono', '* Icono') }}
		{{ $errors->has('icono') ? HTML::tag('span', $errors->first('icono'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
</div>
<div class="row">
	<div class="input-field col s12">
		{{ Form::textarea('descripcion', null, ['id'=>'descripcion','class'=>'validate materialize-textarea']) }}
		{{ Form::label('descripcion', '* Descripcion') }}
		{{ $errors->has('descripcion') ? HTML::tag('span', $errors->first('descripcion'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
</div>
<div class="row">
	<div class="input-field col s2">
		{{ Form::hidden('accion_menu', 0) }}
		{{ Form::checkbox('accion_menu', null, old('accion_menu'), ['id'=>'accion_menu']) }}
		{{ Form::label('accion_menu', '¿Accion Menu?') }}
	</div>
	<div class="input-field col s2">
		{{ Form::hidden('accion_barra', 0) }}
		{{ Form::checkbox('accion_barra', null, old('accion_barra'), ['id'=>'accion_barra']) }}
		{{ Form::label('accion_barra', '¿Accion Menu?') }}
	</div>
	<div class="input-field col s2">
		{{ Form::hidden('accion_tabla', 0) }}
		{{ Form::checkbox('accion_tabla', null, old('accion_tabla'), ['id'=>'accion_tabla']) }}
		{{ Form::label('accion_tabla', '¿Accion Menu?') }}
	</div>
	<div class="input-field col s2">
		{{ Form::hidden('activo', 0) }}
		{{ Form::checkbox('activo', null, old('activo'), ['id'=>'activo']) }}
		{{ Form::label('activo', '¿Activo?') }}
	</div>
</div>
@endsection

@section('form-utils')
	<div id="modal-1" class="modal bottom-sheet">
		<div class="modal-content">
			<h5 class="teal-text"><i class="material-icons">announcement</i> RFC:</h5>
			<ul class="collection">
            	<li class="collection-item">
                	<i class="material-icons teal-text">info</i>
                	<span class="title">Publico General: XAXX010101000.</span>
                </li>
                <li class="collection-item">
                	<i class="material-icons teal-text">info</i>
                  	<span class="title">Extranjero: XEXX010101000.</span>
                </li>
            </ul>
			<br>
		</div>
		
	</div>
@stop

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