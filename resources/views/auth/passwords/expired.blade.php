@extends('layouts.dashboard')

@section('content')
    {!! Form::open(['url' => companyRoute('reset'), 'id'=>'form-reset', 'class' => 'form', 'enctype' => 'multipart/form-data']) !!}
    	<div class="row">
        	<div class="col-md-2 col-lg-3 mt-3"></div>
        	<div class="card col-md-8 col-lg-6 row mt-3">
        		<div class="card-header row">
        			<h5 class="col-md-12 text-center">
        				{{ cTrans('messages.change_password','Restablecer contraseña') }}
        				<span title="Por cuestiones de seguridad debe realizar el cambio de contrase&ntilde;a periodicamente." data-toggle="tooltip" data-placement="bottom">
                    		<i class="text-primary material-icons">help_outline</i>
                    	</span>
        			</h5>
        			<div class="col-md-12 text-center mt-4">Su contrase&ntilde;a tiene mas de {{ $dias }} dias desde su ultimo cambio.</div>
            	</div>
            	<div class="card-body row">
            		<div class="col-md-12">
            			{{Form::cPassword('* '.cTrans('forms.cpassword','Contraseña actual'),'actual')}}
        			</div>
        			<div class="col-md-12 col-lg-6">
        				{{Form::cPassword('* '.cTrans('forms.npassword','Nueva contraseña'),'password')}}
        			</div>
        			<div class="col-md-12 col-lg-6">
        				{{Form::cPassword('* '.cTrans('forms.opassword','Confirmar contraseña'),'confirmar')}}
        			</div>
                	<div class="mt-4 text-center col-sm-12 alert alert-warning">
                		Si no realiza el cambio de la contrase&ntilde;a, no podra acceder a ningun modulo.
                	</div>
            	</div>
            	<div class="card-footer row">
        			<div class="form-group text-center col-sm-12">
        				{{ Form::button(cTrans('forms.change','Cambiar'), ['type' =>'submit', 'class'=>'btn btn-primary progress-button']) }}
        			</div>
            	</div>
        	</div>
    	</div>
    {!! Form::close() !!}
@endsection

@section('header-bottom')
	{!! isset($validator) ? $validator : '' !!}
@endsection