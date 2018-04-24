@extends(smart())

@section('form-content')
    {{ Form::setModel($data) }}
    <div class="row">
    	<div class="form-group col-md-3 col-xs-12">
    		{{ Form::cText('* Razón Social','razon_social') }}
    	</div>
    	<div class="form-group col-md-3 col-xs-12">
    		{{ Form::cText('* Banco','banco') }}
    	</div>
    	<div class="form-group col-md-3 col-xs-12">
    		{{ Form::cText('Rfc','rfc') }}
    	</div>
    	<div class="form-check col-md-3 col-xs-12 d-flex align-items-center">
            {{ Form::cCheckbox('Banco Nacional','nacional') }}
    	</div>
    </div>
@endsection