@extends('layouts.dashboard')

@section('title', cTrans('titles.create','Nuevo').' '.str_singular(cTrans('titles.'.strtolower(currentEntityBaseName()),ucwords(currentEntityBaseName()))))
@section('content-width', 'col-md-12')

@section('form-header')
	{!! Form::open(['url' => companyRoute('index'), 'id' => 'form-model', 'class' => 'col-md-12', 'enctype' => 'multipart/form-data']) !!}
@endsection

@section('header-bottom')
    {{ HTML::script(asset('js/pickadate/picker.js')) }}
    {{ HTML::script(asset('js/pickadate/picker.date.js')) }}
    {{ HTML::script(asset('js/pickadate/translations/es_Es.js')) }}
	{!! isset($validator) ? $validator : '' !!}
@endsection

@section('form-actions')
	<div class="col-md-12">
		<div class="text-right">
			@yield('left-actions')
			{{ Form::button(cTrans('forms.save','Guardar'), ['type' =>'submit', 'class'=>'btn btn-primary progress-button']) }}
			{{ link_to(companyRoute('index'), cTrans('forms.close','Cerrar'), ['class'=>'btn btn-default progress-button']) }}
			@yield('right-actions')
		</div>
	</div>
@endsection

@section('form-title')
	{{ HTML::tag('h1',cTrans('titles.create','Nuevo').' '.str_singular(cTrans('titles.'.strtolower(currentEntityBaseName()),ucwords(currentEntityBaseName()))),['class' => 'display-4']) }}
@endsection

@section('content')
<div class="container-fluid">
	<div class="@yield('content-width')">
		@yield('form-header')
		@if(config('view.load_data'))
        	<div id="loadingActions" class="w-100 h-100 text-center text-white align-middle loadingData" style="display: none">
        		<h4 style="margin-top:15%">Cargando informacion!</h4>
        		<h5>Espere... <i class="material-icons align-middle loading">cached</i></h5>
        	</div>
    	@endif
    		<div class="row">
    			@yield('form-actions')
				<div class="col-md-12">
					<fieldset @yield('fieldset')>
						{{ Form::hidden(array_keys(is_array($data) ? $data : $data->toarray())[0]) }}
						@yield('form-content')
					</fieldset>
				</div>
    		</div>
		{!! Form::close() !!}
		@yield('form-utils')
	</div>
</div>
@endsection