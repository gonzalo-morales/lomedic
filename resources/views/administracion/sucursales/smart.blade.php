@extends(smart())

@section('form-content')
    {{ Form::setModel($data) }}
    <div class="row">
    	<div class="col-md-8 col-sm-12">
    		<h2 class="font-light">Datos de sucursal</h2>
    		<hr>
    		<div class="row">
    			<div class="form-group col-md-6">
    				{{ Form::cText('* Sucursal', 'sucursal') }}
    			</div>
				<div class="form-group col-md-6">
					{{ Form::cText('Responsable', 'responsable') }}
				</div>
    			<div class="form-group col-md-4">
    				{{ Form::cSelect('* Tipo de Sucursal', 'fk_id_tipo', $tipos ?? []) }}
    			</div>
    			<div class="form-group col-md-4">
    				{{ Form::cSelect('* Localidad', 'fk_id_localidad', $localidades ?? []) }}
    			</div>
    			<div class="form-group col-md-4">
    				{{ Form::cSelect('* Zona', 'fk_id_zona', $zonas ?? []) }}
    			</div>
    			<div class="form-group col-md-4">
    				{{ Form::cSelect('* Cliente', 'fk_id_cliente', $clientes ?? []) }}
    			</div>
    			<div class="form-group col-md-4">
    				{{ Form::cText('* Teléfono', 'telefono_1') }}
    			</div>
    			<div class="form-group col-md-4">
    				{{ Form::cText('Teléfono alternativo', 'telefono_2') }}
    			</div>
    		</div>
		</div>
		<!-- Empresas -->
		@if(isset($empresas))
		<div class="col-sm-12 col-md-4">
			<div class="card z-depth-1-half" style="max-height: 310px;">
				<div class="card-header">
					<h5>Empresas</h5>
				</div>
				<div class="card-body" style="overflow: auto;">
					<ul class="list-group">
						<?php 
						$empresa_socio = [];
						if(isset($data->empresas))
						{
    						foreach ($data->empresas as $empresa)
    						    array_push($empresa_socio, $empresa->pivot->fk_id_empresa);
						}
						?>
						
                        @foreach ($empresas as $row)
                        <li class="list-group-item form-group row">
							{{-- {{ Form::cCheckbox($row->nombre_comercial, 'empresas['.$row->id_empresa.']',['class'=>'socio-empresa'], (in_array($row->id_empresa, $empresa_socio)?1:0) ) }} --}}
							<div class="form-check">
								<input name="empresas_[{{$row->id_empresa}}][fk_id_empresa]" type="hidden" value="0">
								<label class="form-check-label custom-control custom-checkbox">
									{{ $row->nombre_comercial }}
									<input class="form-check-input custom-control-input" name="empresas_[{{$row->id_empresa}}][fk_id_empresa]" type="checkbox" value="{{$row->id_empresa}}" {{in_array($row->id_empresa,$empresa_socio) ? 'checked' : ''}}>
									<span class="custom-control-indicator"></span>
								</label>
							</div>
                        </li>
                        @endforeach
                    </ul>
				</div>
			</div>
		</div>
		@endif <!-- Fin Empresas -->
    </div>
	<div class="row mb-3 mt-3">
		<div class="col-md-12">
			<div class="card z-depth-1-half">
				<div class="card-header">
					<ul class="nav nav-pills card-header-pills nav-fill" id="pills-tab" role="tablist" data-tabs="tabs">
						<li class="nav-item">
							<a id="producto" class="nav-link active" data-toggle="tab" href="#ubicacion" role="tab" aria-controls="ubicacion" aria-selected="true">
								<i class="material-icons align-middle">pin_drop</i> Ubicación
							</a>
						</li>
						<li class="nav-item">
							<a id="pedido" class="nav-link" data-toggle="tab" href="#otros" role="tab" aria-controls="otros" aria-selected="false">
								<i class="material-icons align-middle">more_horiz</i> Otros
							</a>
						</li>
					</ul>
				</div>
				<div class="card-body">
					<div class="tab-content">
						<div class="tab-pane active" id="ubicacion" role="tabpanel">
							<div class="col-12">
								<h2 class="font-light">Ubicación</h2>
								<hr>
								<div class="row">
									<div class="form-group col-md-6">
										{{ Form::cText('* Calle', 'calle') }}
									</div>
									<div class="form-group col-md-3">
										{{ Form::cText('* Num. Exterior', 'numero_exterior') }}
									</div>
									<div class="form-group col-md-3">
										{{ Form::cText('Num. Interior', 'numero_interior') }}
									</div>
									<div class="form-group col-md-4">
										{{ Form::cText('* Colonia', 'colonia') }}
									</div>
									<div class="form-group col-md-4">
										{{ Form::cText('*Codigo Postal', 'codigo_postal') }}
									</div>
									<div class="form-group col-md-4">
										{{ Form::cSelectWithDisabled('* Pais', 'fk_id_pais', $paises ?? [], [
											'class' => 'select2 select-cascade',
											'data-target-url' => companyRoute('paises.show', ['id' => '#ID#']),
											'data-target-el' => '[targeted="fk_id_estado"]',
											'data-target-with' => '["estados:id_estado,fk_id_pais,estado"]',
											'data-target-value' => 'estados,id_estado,estado'
										]) }}
									</div>
									<div class="form-group col-md-4">
										{{ Form::cSelect('* Estado', 'fk_id_estado', $estados ?? [], [
											'class' => 'select2 select-cascade',
											'targeted' => 'fk_id_estado',
											'data-target-url' => companyRoute('estados.show', ['id' => '#ID#']),
											'data-target-el' => '[targeted="fk_id_municipio"]',
											'data-target-with' => '["municipios:id_municipio,fk_id_estado,municipio"]',
											'data-target-value' => 'municipios,id_municipio,municipio'
										]) }}
									</div>
									<div class="form-group col-md-4">
										{{ Form::cSelect('* Municipio', 'fk_id_municipio', $municipios ?? [], [
											'class' => 'select2',
											'targeted' => 'fk_id_municipio',
										]) }}
									</div>
									<div class="form-group col-md-4">
										{{ Form::cText('Latitud', 'latitud') }}
									</div>
									<div class="form-group col-md-4">
										{{ Form::cText('Longitud', 'longitud') }}
									</div>
								</div>
							</div>
						</div>{{-- /modules --}}
						<div class="tab-pane" id="otros" role="tabpanel">
							<div class="col-12">
								<h2 class="font-light">Otros</h2>
								<hr>
								<div class="row">
									<div class="form-group col-md-4 col-12">
										{{ Form::cText('Registro sanitario', 'registro_sanitario') }}
									</div>
									<div class="form-group col-md-4">
										{{ Form::cCheckboxYesOrNo('¿Tiene inventario?', 'inventario') }}
									</div>
									<div class="form-group col-md-4">
										{{ Form::cCheckboxYesOrNo('¿Tiene enbarque?', 'enbarque') }}
									</div>
									<div class="form-group col-md-4">
										{{ Form::cText('Tipo batallón', 'tipo_batallon') }}
									</div>
									<div class="form-group col-md-4">
										{{ Form::cText('Region', 'region') }}
									</div>
									<div class="form-group col-md-4">
										{{ Form::cText('Zona militar', 'zona_militar') }}
									</div>
									<div class="form-group col-md-4">
										{{ Form::cText('Clave presupuestal', 'clave_presupuestal') }}
									</div>
									<div class="form-group col-md-4">
										{{ Form::cSelectWithDisabled('Sucursal proveedora', 'id_sucursal_proveedor', $sucursales ?? []) }}
									</div>
									<div class="form-group col-md-4">
										{{ Form::cSelectWithDisabled('Jurisdiccion', 'fk_id_jurisdiccion',$jurisdicciones ?? []) }}
									</div>
								</div>
							</div>
						</div>{{-- /sucursales --}}
					</div>{{-- /tabcontent container for all tabs --}}
				</div>{{-- /cardbody --}}
			</div>
		</div>
	</div>
	<div  class="col-md-12 text-center mt-4">
		<div class="alert alert-warning" role="alert">
			Recuerda que al no estar <b>activo</b>, este <b>dato</b> no se mostrara en los modulos correspondientes que se requieran.
		</div>
		{{ Form::cCheckboxBtn('Estatus','Activo','activo', $data['activo'] ?? null, 'Inactivo') }}
	</div>
@endsection