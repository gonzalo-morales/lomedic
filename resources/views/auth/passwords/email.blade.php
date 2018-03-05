@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row justify-content-center">
	<div class="col-sm-10 col-md-8 col-lg-5 mt-5">
		<div class="text-right text-info mr-2">
    		@foreach(config('app.locales') as $s=>$lang)
    			<small>{{ HTML::link(url('/lang/'.$s), $s, ['class' => 'text-secondary', 'title' => $lang]) }}</small>
    		@endforeach
    	</div>
		<div class="card card-block text-center" style="background: rgba(0,0,0,0.3);"> <!-- class="card-panel hoverable row"> -->
        	<h4 class="mt-3 text-white">{{cTrans('messages.reset_password')}}</h4>
		
			{!! Form::open(['route' => 'password.email', 'id' => 'form-reset', 'class' => 'card-body center']) !!}
			<div class="container-fluid">
				<div class="form-group">
					<object id="front-page-logo" class="Sim" type="image/svg+xml" data="{{asset('img/sim2.svg')}}" name="SIM">Your browser does not support SVG</object>
				</div>
				<div class="form-group">
					<div class="input-group">
						<span class="input-group-addon" id="username">
							<i class="material-icons prefix">email</i>
                        </span>
                        {{ Form::text('email', null, ['id'=>'email','class'=>'validate form-control','placeholder'=>'* '.cTrans('forms.email','Dirección Correo Electronico'),'autofocus'=>true]) }}
					</div>
                        {{ $errors->has('email') ? HTML::tag('span', $errors->first('email'), ['class'=>'form-text text-white bg-danger p-1 m-0 rounded-bottom']) : '' }}
				</div>
				<div class="form-group mt-5">
					<div class=" container">
					{{ Form::button(cTrans('forms.send','Enviar'), ['type' =>'submit', 'class'=>'btn btn-primary progress-button']) }}
					</div>
				</div>
				<div class="form-group mt-4">
					<small><b>{{ link_to_route('login', cTrans('forms.back','Regresar'), null, ['class'=>'card-link text-white']) }}</b></small>
				</div>
			</div>
			{!! Form::close() !!}
		</div><!--/card-->
	</div>
	</div><!--/row-->
</div><!--/valign-wrapper aquÃ­ termina el login-->
@endsection