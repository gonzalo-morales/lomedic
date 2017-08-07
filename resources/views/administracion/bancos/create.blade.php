@extends('layouts.dashboard')

@section('title', 'Nuevo')

@section('header-top')
@endsection

@section('header-bottom')
@endsection

@section('content')
	{!! Form::model($data, ['url' => companyRoute('index'), 'class' => 'col s12 x18']) !!}
    	<div class="col s12 m7 xl8 offset-xl2">
    		<div class="row">
    			<div class="right">
        			{{ Form::submit('Guardar',['class'=>"waves-effect waves-light btn orange"]) }}
        			{{ link_to(companyRoute("index",['company'=> $company]),'Cerrar',['class'=>"waves-effect waves-teal btn-flat teal-text"]) }}
    			</div>
    		</div>
    	</div>
    	<div class="col s12 m7 xl8 offset-xl2">
    		{{  HTML::tag('div','',['class'=>'divider']) }}
    		{{  HTML::tag('h4','Nuevo '.trans_choice('messages.'.$entity, 0)) }}
    		<div class="row">
				<div class="input-field col s12">
					{{ Form::text('razon_social', null, ['id'=>'razon_social','class'=>'validate']) }}
					{{ Form::label('razon_social', '* Razón Social') }}
					{{ $errors->has('razon_social') ? HTML::tag('span', $errors->first('razon_social'), ['class'=>'help-block deep-orange-text']) : '' }}
				</div>
			</div>
			<div class="row">
				<div class="input-field col s6">
					{{ Form::text('banco', null, ['id'=>'banco','class'=>'validate']) }}
					{{ Form::label('banco', '* Banco') }}
					{{ $errors->has('banco') ? HTML::tag('span', $errors->first('banco'), ['class'=>'help-block deep-orange-text']) : '' }}
				</div>
				<div class="input-field col s6">
					<a href="#modal-1" class="prefix"><i class="material-icons">info</i></a>
					{{ Form::text('rfc', null, ['id'=>'rfc','class'=>'validate']) }}
					{{ Form::label('rfc', 'Rfc') }}
					{{ $errors->has('rfc') ? HTML::tag('span', $errors->first('rfc'), ['class'=>'help-block deep-orange-text']) : '' }}
				</div>
			</div>
			<div class="row">
				<div class="input-field col s12">
					{{ Form::hidden('nacional', 0) }}
					{{ Form::checkbox('nacional', 0, old('nacional'),['id'=>'nacional']) }}
					{{ Form::label('nacional', '¿Banco nacional?') }}
					{{ $errors->has('nacional') ?  HTML::tag('span', $errors->first('nacional'), ['class'=>'help-block deep-orange-text']) : '' }}
				</div>
			</div>
		</div>
	{!! Form::close() !!}
	
	<div id="modal-1" class="modal">
    <div class="modal-content">
      <h4>Informacion RFC:</h4>
      <p>Publico General: XAXX010101000.</p>
      <p>Extranjero: XEXX010101000.</p>
    </div>
    <div class="modal-footer">
      <a href="#!" class="modal-action modal-close waves-effect waves-teal btn-flat teal-text">Cerrar</a>
    </div>
  </div>
@endsection
