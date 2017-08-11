@extends('layouts.dashboard')

@section('title', 'Ver')

@section('header-top')
@endsection

@section('header-bottom')
@endsection

@section('content')
	{!! Form::model($data, ['url' => '#', 'class' => 'col s12 x18']) !!}
    	<div class="col s12 m7 xl8 offset-xl2">
    		<div class="row">
    			<div class="right">
        			{{ link_to(companyRoute("index",['company'=> $company]),'Cerrar',['class'=>"waves-effect waves-light btn orange"]) }}
        			@can('update', App\Http\Models\Administracion\Bancos::class)
        				{{ link_to(companyRoute("edit", ['company'=> $company, 'id' => $data->id_banco]),'Editar',['class'=>"waves-effect waves-teal btn-flat teal-text"]) }}
        			@endcan
    			</div>
    		</div>
    	</div>
    	<div class="col s12 m7 xl8 offset-xl2">
    		{{  HTML::tag('div','',['class'=>'divider']) }}
    		{{  HTML::tag('h4','Datos del '.trans_choice('messages.'.$entity, 0)) }}
    		<div class="row">
				<div class="input-field col s12">
					{{ Form::text('razon_social', null, ['id'=>'razon_social','class'=>'validate', 'readonly' => true]) }}
					{{ Form::label('razon_social', '* Razón Social') }}
				</div>
			</div>
			<div class="row">
				<div class="input-field col s6">
					{{ Form::text('banco', null, ['id'=>'banco','class'=>'validate', 'readonly' => true]) }}
					{{ Form::label('banco', '* Banco') }}
				</div>
				<div class="input-field col s6">
					{{ Form::text('rfc', null, ['id'=>'rfc','class'=>'validate', 'readonly' => true]) }}
					{{ Form::label('rfc', 'Rfc') }}
				</div>
			</div>
			<div class="row">
				<div class="input-field col s12">
					{{ Form::hidden('nacional', 0, ['readonly' => true]) }}
					{{ Form::checkbox('nacional', null, old('nacional'),['id'=>'nacional', 'disabled' => true]) }}
					{{ Form::label('nacional', '¿Banco nacional?') }}
				</div>
			</div>
		</div>
	{!! Form::close() !!}
@endsection
