
@section('content-width', 's12')

@section('form-content')
{{ Form::setModel($data) }}
<div class="row">
	<div class="input-group col-md-12 col-xs-12">
		{{ Form::label('devolucion_motivo', 'Motivo de devolución') }}
		{{ Form::text('devolucion_motivo', null, ['id'=>'devolucion_motivo','class'=>'form-control']) }}
		<div class="input-group-btn" role="group" aria-label="solicitante_devolucion" data-toggle="buttons">
			<label class="btn btn-check btn-secondary">
				<input type="radio" name="solicitante_devolucion" id="localidad" autocomplete="off" value="0" class="btn btn-secondary">Localidad
			</label>
			<label class="btn btn-check btn-secondary">
				<input type="radio" name="solicitante_devolucion" id="proveedor" autocomplete="off" value="1"  class="btn btn-secondary">Proveedor
			</label>
		</div>
	</div>
	{{ $errors->has('devolucion_motivo') ? HTML::tag('span', $errors->first('devolucion_motivo'), ['class'=>'help-block deep-orange-text']) : '' }}
</div>
@endsection

{{-- DONT DELETE --}}
@if (Route::currentRouteNamed(currentRouteName('index')))
	@include('layouts.smart.index')
@endif

@if (Route::currentRouteNamed(currentRouteName('create')))
	@include('layouts.smart.create')
@endif

@if (Route::currentRouteNamed(currentRouteName('edit')))
	@include('layouts.smart.edit')
@endif

@if (Route::currentRouteNamed(currentRouteName('show')))
	@include('layouts.smart.show')
@endif

@if (Route::currentRouteNamed(currentRouteName('export')))
	@include('layouts.smart.export')
@endif