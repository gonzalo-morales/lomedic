@section('header-bottom')
	@parent
	@if (!Route::currentRouteNamed(currentRouteName('index')))
		{{HTML::script(asset('js/autorizaciones.js'))}}
	@endif
@endsection

@section('content-width', 's12')

@section('form-content')
{{ Form::setModel($data) }}
{{--{{dd($data)}}--}}
@if (Route::currentRouteNamed(currentRouteName('show')) || Route::currentRouteNamed(currentRouteName('edit')))
	<div class="row">
		<div class="col-md-12 text-center text-success">
			<h3>Autorización de la {{$data->fk_id_tipo_documento == 10 ? 'solicitud de pago' : 'orden de compra'}} No. {{$data->fk_id_documento}}</h3>
		</div>
	</div>
@endif
<div class="row">
	<div class="form-group col-md-4 col-sm-6">
		{{Form::label('','Tipo de autorizacion')}}
		{{Form::text('',isset($data) && !empty($data->condicion) ? $data->condicion->nombre : '',['class'=>'form-control','disabled'])}}
	</div>
	<div class="form-group col-md-8 col-sm-6">
		{{Form::cRadio('¿Autorizado?','fk_id_estatus',[4=>'Autorizado',3=>'No Autorizado'])}}
	</div>
	<div class="form-group col-md-12 col-sm-12">
		{{Form::cTextArea('Motivo','observaciones',['readonly','style'=>'resize:none;'])}}
	</div>
</div>
@endsection

{{-- DONT DELETE --}}
@if (Route::currentRouteNamed(currentRouteName('index')))
	@section('form-title')
		<h1 class="display-4">Autorizaciones</h1>
	@endsection
	@include('layouts.smart.index')
@endif

@if (Route::currentRouteNamed(currentRouteName('create')))
	@section('form-title')
		<h1 class="display-4">Agregar Autorizacion</h1>
	@endsection
	@include('layouts.smart.create')
@endif

@if (Route::currentRouteNamed(currentRouteName('edit')))
	@section('form-title')
		<h1 class="display-4">Autorizar</h1>
	@endsection
	@include('layouts.smart.edit')
@endif

@if (Route::currentRouteNamed(currentRouteName('show')))
	@section('form-title')
		<h1 class="display-4">Autorizacion</h1>
	@endsection
	@include('layouts.smart.show')
@endif
