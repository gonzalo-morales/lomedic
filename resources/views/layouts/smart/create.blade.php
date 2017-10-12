@extends('layouts.dashboard')

@section('title', currentEntityBaseName() . '@Agregar')

@section('form-header')
	{!! Form::open(['url' => companyRoute('index'), 'id' => 'form-model', 'class' => 'col-md-12 col-xs-12']) !!}
@endsection

@section('header-bottom')
    {{ HTML::script(asset('js/pickadate/picker.js')) }}
    {{ HTML::script(asset('js/pickadate/picker.date.js')) }}
    {{ HTML::script(asset('js/pickadate/translations/es_Es.js')) }}
	{!! isset($validator) ? $validator : '' !!}
@endsection

@section('form-actions')
	<div class="col-md-12 col-xs-12">
		<div class="text-right">
			{{ Form::button('Guardar', ['type' =>'submit', 'class'=>'btn btn-primary']) }}
			{{ link_to(companyRoute('index'), 'Cerrar', ['class'=>'btn btn-default']) }}
		</div>
	</div>
@endsection

@section('form-title')
	{{ HTML::tag('h1','Agregar '. str_singular(currentEntityBaseName())) }}
@endsection

@section('content')
<div class="container-fluid">
	<div class="@yield('content-width')">
		@yield('form-header')
    		<div class="row">
    			@yield('form-title')
    			@yield('form-actions')
				<div class="col-md-12 col-xs-12">
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
