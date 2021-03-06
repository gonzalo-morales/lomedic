@extends(smart())

@section('header-bottom')
	@parent
	@editar
		{{HTML::script(asset('js/autorizaciones.js'))}}
	@endif
@endsection

@section('form-content')
	{{ Form::setModel($data) }}
	@inroute(['show','edit'])
    	<div class="row">
    		<div class="col-md-12 text-center text-success">
    			<h3>Autorización de la {{$data->fk_id_tipo_documento == 10 ? 'solicitud de pago' : 'orden de compra'}} No. {{$data->fk_id_documento}}</h3>
    		</div>
    	</div>
        <div class="row">
        	<div class="form-group col-md-4 col-sm-6">
        		{{Form::cText('* Tipo de autorizacion','',['disabled'],$data->condicion->nombre)}}
        	</div>
        	<div class="form-group col-md-8 col-sm-6">
				{{Form::cRadio('* ¿Autorizado?','fk_id_estatus',[17=>'Autorizado',16=>'No Autorizado'])}}
        	</div>
        	<div class="form-group col-md-12 col-sm-12">
        		{{Form::cTextArea('Motivo','observaciones',['readonly','style'=>'resize:none;'])}}
        	</div>
        </div>
	@endif
@endsection