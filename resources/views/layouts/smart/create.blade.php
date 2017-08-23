@extends('layouts.dashboard')

@section('title', currentEntityBaseName() . '@Agregar')

@section('form-header')
	{!! Form::open(['url' => companyRoute('index'), 'id' => 'form-model', 'class' => 'col s12 m18']) !!}
@endsection

@section('form-actions')
	<div class="row">
		<div class="right">
			{{ Form::button('Guardar', ['type' =>'submit', 'class'=>'waves-effect waves-light btn orange']) }}
			{{ link_to(companyRoute('index'), 'Cerrar', ['class'=>'waves-effect waves-teal btn-flat teal-text']) }}
		</div>
	</div>
@endsection

@section('form-title')
	{{ HTML::tag('h4','Agregar '. str_singular(currentEntityBaseName())) }}
@endsection

@section('content')
<div class="row">
	<div class="col @yield('content-width')">
		@yield('form-header')
    		@yield('form-actions')
    		<div class="row">
    			@yield('form-title')
    			<fieldset @yield('fieldset')>
    				@yield('form-content')
    			</fieldset>
    		</div>
		{!! Form::close() !!}
		@yield('form-utils')
	</div>
</div>
@endsection
