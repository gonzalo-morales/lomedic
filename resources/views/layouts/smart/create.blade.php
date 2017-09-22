@extends('layouts.dashboard')

@section('title', currentEntityBaseName() . '@Agregar')

@section('form-header')
	{!! Form::open(['url' => companyRoute('index'), 'id' => 'form-model']) !!}
@endsection

@section('form-actions')
	<div class="col-md-12">
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
			@yield('form-title')
			@yield('form-actions')
			<fieldset @yield('fieldset')>
				@yield('form-content')
			</fieldset>
		{!! Form::close() !!}
		@yield('form-utils')
	</div>
</div>
@endsection
