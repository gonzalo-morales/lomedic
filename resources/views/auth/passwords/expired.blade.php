@extends('layouts.dashboard')

@section('content')
    {!! Form::open(['url' => companyRoute('reset'), 'id'=>'form-reset', 'class' => 'row', 'enctype' => 'multipart/form-data']) !!}
    	<div class="col-md-2 col-lg-3 mt-3"></div>
    	<div class="card col-md-8 col-lg-6 row mt-3">
    		<div class="card-header row">
    			<h5 class="col-md-12 text-center">Debe cambiar su contrase&ntilde;a.</h5>
    			<div class="col-md-12 text-center mt-4">Su contrase&ntilde;a tiene mas de {{ $dias }} dias desde su ultimo cambio.</div>
        	</div>
        	<div class="card-body row">
        		<div class="col-md-12">
        			{{Form::cPassword('* Contrase&ntilde;a actual','actual')}}
    			</div>
    			<div class="col-md-12 col-lg-6">
    				{{Form::cPassword('* Nueva contrase&ntilde;a','password')}}
    			</div>
    			<div class="col-md-12 col-lg-6">
    				{{Form::cPassword('* Confirmar contrase&ntilde;a','confirmar')}}
    			</div>
        	</div>
        	<div class="card-footer row">
    			<div class="form-group text-center col-sm-12">
    				{{ Form::button('Cambiar', ['type' =>'submit', 'class'=>'btn btn-primary progress-button']) }}
    			</div>
        	</div>
    	</div>
    {!! Form::close() !!}
@endsection

@section('header-bottom')
	{!! isset($validator) ? $validator : '' !!}
@endsection