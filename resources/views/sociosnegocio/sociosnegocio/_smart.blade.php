
@section('content-width', 's12 m7 xl8 offset-xl2')

@section('form-content')
	{{-- {!! Form::open(['action'=>'ProveedoresController@store']) !!} --}}
	{{ Form::setModel($data) }}
<div class="row">
	<div class="input-field col s12">
		{{ Form::text('razon_social', null, ['id'=>'razon_social','class'=>'validate']) }}
		{{ Form::label('razon_social', '* Razón Social') }}
		{{ $errors->has('razon_social') ? HTML::tag('span', $errors->first('razon_social'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
</div>
<div class="row">
	<div class="input-field col s6">
		{{ Form::text('nombre', null, ['id'=>'nombre','class'=>'validate']) }}
		{{ Form::label('nombre', '* Nombre') }}
		{{ $errors->has('nombre') ? HTML::tag('span', $errors->first('nombre'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
	<div class="input-field col s6">
		{{ Form::text('rfc', null, ['id'=>'rfc','class'=>'validate']) }}
		{{ Form::label('rfc', 'Rfc') }}
		{{ $errors->has('rfc') ? HTML::tag('span', $errors->first('rfc'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
</div>
<div class="col s6">
	{{ Form::label('avisoFuncionamiento', 'Aviso Funcionamiento') }}
	<div class="input-field file-field">
		<div class="btn">
			<i class="material-icons">attach_file</i>
			{{ Form::file('avisoFuncionamiento', null, ['id'=>'avisoFuncionamiento','class'=>'validate']) }}
		</div>
		<div class="file-path-wrapper">
			<input class="file-path validate" type="text">
		</div>
	</div>
		{{ $errors->has('avisoFuncionamiento') ? HTML::tag('span', $errors->first('avisoFuncionamiento'), ['class'=>'help-block deep-orange-text']) : '' }}
</div>

{{-- {!! Form::close() !!} --}}

@endsection

{{-- DONT DELETE --}}
@if (Route::currentRouteNamed(currentRouteName('index')))
	@include('layouts.smart.index')
@endif

@if (Route::currentRouteNamed(currentRouteName('create')))
	@section('form-header')
		{!! Form::model($data, ['url' => companyRoute('index'), 'id' => 'form-model', 'class' => 'col s12 x18', 'method'=>'post','enctype'=>'multipart/form-data']) !!}
	@endsection
	@include('layouts.smart.create')
@endif

@if (Route::currentRouteNamed(currentRouteName('edit')))
	@include('layouts.smart.edit')
@endif

@if (Route::currentRouteNamed(currentRouteName('show')))
	@include('layouts.smart.show')
@endif
