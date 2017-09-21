@extends('layouts.app')

@section('content')
<div class="valign-wrapper" style="height: 100vh;"><br><br><br>
	<div class="container-fluid col-sm-8 col-md-6 col-xl-3">
		<div class="card card-block text-center"> <!-- class="card-panel hoverable row"> -->
        	<h4 class="card-header text-info">Reset Password</h4>
		
			{!! Form::open(['route' => 'password.email', 'id' => 'form-reset', 'class' => 'card-body center']) !!}
			<div class="container-fluid col-sm-12">
				<div class="form-group col-sm-12">
					<object id="front-page-logo" class="Sim" type="image/svg+xml" data="{{asset('img/sim2.svg')}}" name="SIM">Your browser does not support SVG</object>
				</div>
				
				<div class="form-group col-sm-12">
					<div class="input-group">
						<span class="input-group-addon" id="username">
							<i class="material-icons prefix">email</i>
                        	<span class="oi oi-person" title="Usuario o contraseña" aria-hidden="true"></span>
                        </span>
						{{ Form::text('email', null, ['id'=>'email','class'=>'validate col-sm-10','placeholder'=>'* E-Mail Address','autofocus'=>true]) }}
                        {{ $errors->has('email') ? HTML::tag('small', $errors->first('email'), ['class'=>'form-text text-muted']) : '' }}
					</div>
				</div>
				
				<div class="form-group col-sm-12">
					<div class=" container">
					{{ Form::button('Send Password Reset Link', ['type' =>'submit', 'class'=>'btn btn-primary']) }}
					{{ link_to_route('login', 'Regresar', null, ['class'=>'btn']) }}
					</div>
				</div>
			</div>
			{!! Form::close() !!}
				
		</div><!--/card-panel hoverable row-->
	</div><!--/col s12 center-block-->
</div><!--/valign-wrapper aquÃ­ termina el login-->
@endsection
