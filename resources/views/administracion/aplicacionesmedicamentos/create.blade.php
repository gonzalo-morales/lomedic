@extends('layouts.dashboard')

@section('title', 'Crear')

@section('header-top')
@endsection

@section('header-bottom')
@endsection

@section('content')
	{!! Form::open(['url' => companyRoute('index'),'class' => 'col s12 x18']) !!}
		{{ csrf_field() }}
		{{ method_field('POST') }}
		{{ Form::token() }}
		
    	<div class="col s12 m7 xl8 offset-xl2">
    		<div class="row">
    			<div class="right">
    				{{ Form::submit('Guardar y salir',['class'=>"waves-effect btn orange"]) }}
    				{{ link_to(url()->previous(),'Cancelar y salir',['class'=>"waves-effect waves-teal btn-flat teal-text"]) }}
    			</div>
    		</div>
    	</div>
    	<div class="col s12 m7 xl8 offset-xl2">
    		{{  HTML::tag('h5','Datos Aplicación de medicamento') }}
    		<div class="row">
    			<div class="input-field col s10">
    				{{ Form::text('aplicacion', old('aplicacion'), ['id'=>'aplicacion','class'=>'validate']) }}
					{{ Form::label('aplicacion', '* Aplicación') }}
					
					@if ($errors->has('aplicacion'))
						{{ HTML::tag('span', $errors->first('aplicacion'), ['class'=>'help-block deep-orange-text']) }}
					@endif
    			</div>
    		</div>
    	</div>
	{!! Form::close() !!}
@endsection
