
@section('content-width', 's12')

@section('form-content')
{{ Form::setModel($data) }}
<div class="row">
	<div class="form-group col-md-3 col-xs-12">
		{{ Form::label('razon_social', '* Razón Social') }}
		{{ Form::text('razon_social', null, ['id'=>'razon_social','class'=>'form-control']) }}
		{{ $errors->has('razon_social') ? HTML::tag('span', $errors->first('razon_social'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
	<div class="form-group col-md-3 col-xs-12">
		{{ Form::label('banco', '* Banco') }}
		{{ Form::text('banco', null, ['id'=>'banco','class'=>'form-control']) }}
		{{ $errors->has('banco') ? HTML::tag('span', $errors->first('banco'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
	<div class="form-group col-md-3 col-xs-12">
		{{--<a href="#modal-1" class="prefix"><i class="material-icons">info</i></a>--}}
		{{ Form::label('rfc', 'Rfc') }}
		{{ Form::text('rfc', null, ['id'=>'rfc','class'=>'form-control']) }}
		{{ $errors->has('rfc') ? HTML::tag('span', $errors->first('rfc'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
	<div class="form-check col-md-3 col-xs-12 d-flex align-items-center">
		<input type="hidden" name="nacional" value="0" />
		{{ Form::checkbox('nacional', 1, old('nacional'), ['id'=>'nacional']) }}
		<label for="nacional">¿Banco Nacional?</label>
		{{ $errors->has('nacional') ?  HTML::tag('span', $errors->first('nacional'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
</div>
@endsection

{{--@section('form-utils')--}}
	{{--<div id="modal-1" class="modal bottom-sheet">--}}
		{{--<div class="modal-content">--}}
			{{--<h5 class="teal-text"><i class="material-icons">announcement</i> RFC:</h5>--}}
			{{--<ul class="collection">--}}
            	{{--<li class="collection-item">--}}
                	{{--<i class="material-icons teal-text">info</i>--}}
                	{{--<span class="title">Publico General: XAXX010101000.</span>--}}
                {{--</li>--}}
                {{--<li class="collection-item">--}}
                	{{--<i class="material-icons teal-text">info</i>--}}
                  	{{--<span class="title">Extranjero: XEXX010101000.</span>--}}
                {{--</li>--}}
            {{--</ul>--}}
			{{--<br>--}}
		{{--</div>--}}
		{{----}}
	{{--</div>--}}
{{--@stop--}}

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