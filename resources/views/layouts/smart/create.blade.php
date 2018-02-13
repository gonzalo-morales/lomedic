@extends('layouts.dashboard')

@section('title', currentEntityBaseName().' '.trans('titles.create'))

@section('form-header')
	{!! Form::open(['url' => companyRoute('index'), 'id' => 'form-model', 'class' => 'col-md-12 col-xs-12', 'enctype' => 'multipart/form-data']) !!}
@endsection

@section('header-bottom')
    {{ HTML::script(asset('js/pickadate/picker.js')) }}
    {{ HTML::script(asset('js/pickadate/picker.date.js')) }}
    {{ HTML::script(asset('js/pickadate/translations/es_Es.js')) }}
	{!! isset($validator) ? $validator : '' !!}
@endsection

@section('form-actions')
	<div class="col-12">
		<div class="text-right">
			@yield('left-actions')
			{{ Form::button(trans('forms.save'), ['type' =>'submit', 'class'=>'btn btn-primary progress-button']) }}
			{{ link_to(companyRoute('index'), trans('forms.close'), ['class'=>'btn btn-default progress-button']) }}
			@yield('right-actions')
		</div>
	</div>
@endsection

@section('form-title')
	{{ HTML::tag('h1',trans('titles.create').' '.str_singular(currentEntityBaseName()),['class' => 'display-4']) }}
@endsection

@section('content')
<div class="container-fluid">
	<div class="@yield('content-width')">
		@yield('form-header')
    		<div class="row">
    			@yield('form-actions')
				<div class="col-12">
					<fieldset @yield('fieldset')>
						@yield('form-content')
					</fieldset>
				</div>
    		</div>
		{!! Form::close() !!}
		@yield('form-utils')
	</div>
</div>
@endsection