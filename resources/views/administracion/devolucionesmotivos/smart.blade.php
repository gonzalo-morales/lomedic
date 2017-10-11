
@section('content-width', 's12')

@section('form-content')
{{ Form::setModel($data) }}
<div class="row">
	<div class="form-group col-md-12 col-xs-12">
		{{ Form::label('devolucion_motivo', 'Motivo de devolución') }}
		{{ Form::text('devolucion_motivo', null, ['id'=>'devolucion_motivo','class'=>'form-control']) }}
		{{ $errors->has('devolucion_motivo') ? HTML::tag('span', $errors->first('devolucion_motivo'), ['class'=>'help-block deep-orange-text']) : '' }}
	</div>
	<div  class="col-md-12 text-center">
		@if(!Route::currentRouteNamed(currentRouteName('show'))?'disabled aria-disabled=true':'')
		<div class="btn-group" data-toggle="buttons">
			<label class="btn btn-check btn-secondary {{ old('solicitante_devolucion') === "0" || (isset($data->solicitante_devolucion) && $data->solicitante_devolucion === false)  ? 'active':''}}">
				<input type="radio" name="solicitante_devolucion" id="localidad" autocomplete="off" value="0"
						{{(old('solicitante_devolucion') === "0" || (!empty($data->solicitante_devolucion) && $data->solicitante_devolucion == 0) ? 'checked':'')}}>Solicitante
			</label>
			<label class="btn btn-check btn-secondary {{ !empty($data->solicitante_devolucion) || old('solicitante_devolucion') == 1 ? 'active':''}}">
				<input type="radio" name="solicitante_devolucion" id="proveedor" autocomplete="off" value="1"
						{{!empty($data->solicitante_devolucion) || old('solicitante_devolucion') == 1 ? 'checked':''}}>Proveedor
			</label>
		</div>
		@else
			<div class="btn-group">
				<label class="btn btn-check btn-secondary {{ old('solicitante_devolucion') === "0" || $data->solicitante_devolucion == 0 ? 'active':''}}">
					Solicitante
				</label>
				<label class="btn btn-check btn-secondary {{ !empty($data->solicitante_devolucion) || old('solicitante_devolucion') == 1 ? 'active':''}}">
					Proveedor
				</label>
			</div>
		@endif
	</div>
	<div class="col-md-12 text-center mt-2">{{ $errors->has('solicitante_devolucion') ? HTML::tag('span', $errors->first('solicitante_devolucion'), ['class'=>'help-block deep-orange-text']) : '' }}</div>
	<div  class="col-md-12 text-center mt-2">
		<div class="alert alert-warning" role="alert">
            Recuerda que al no estar <b>activo</b>, este <b>dato</b> no se mostrara en los modulos correspondientes que se requieran.
        </div>
        {{ Form::cCheckboxBtn('Estatus','Activo','activo', $data['activo'] ?? null, 'Inactivo') }}
	</div>
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