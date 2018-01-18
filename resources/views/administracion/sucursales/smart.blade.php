@extends(smart())
@section('content-width', 's12 m7 xl8 offset-xl2')

@section('form-content')
    {{ Form::setModel($data) }}
    <div class="row">
    	<div class="col-lg-6">
    		<h2 class="font-light">Datos de sucursal</h2>
    		<hr>
    		<div class="row">
    			<div class="form-group col-md-4">
    				{{ Form::cText('Sucursal', 'sucursal') }}
    			</div>
    			<div class="form-group col-md-4">
    				{{ Form::cSelect('Tipo de Sucursal', 'fk_id_tipo', $tipos ?? []) }}
    			</div>
    			<div class="form-group col-md-4">
    				{{ Form::cSelect('Localidad', 'fk_id_localidad', $localidades ?? []) }}
    			</div>
    			<div class="form-group col-md-4">
    				{{ Form::cSelect('Zona', 'fk_id_zona', $zonas ?? []) }}
    			</div>
    			<div class="form-group col-md-4">
    				{{ Form::cSelect('Cliente', 'fk_id_cliente', $clientes ?? []) }}
    			</div>
    			<div class="form-group col-md-4">
    				{{ Form::cText('Responsable', 'responsable') }}
    			</div>
    			<div class="form-group col-md-4">
    				{{ Form::cText('Teléfono', 'telefono_1') }}
    			</div>
    			<div class="form-group col-md-4">
    				{{ Form::cText('Teléfono alternativo', 'telefono_2') }}
    			</div>
    		</div>
    	</div>
    	<div class="col-lg-6">
    		<h2 class="font-light">Ubicación</h2>
    		<hr>
    		<div class="row">
    			<div class="form-group col-md-6">
    				{{ Form::cText('Calle', 'calle') }}
    			</div>
    			<div class="form-group col-md-3">
    				{{ Form::cText('Num. Interior', 'numero_interior') }}
    			</div>
    			<div class="form-group col-md-3">
    				{{ Form::cText('Num. Exterior', 'numero_exterior') }}
    			</div>
    			<div class="form-group col-md-4">
    				{{ Form::cText('Colonia', 'colonia') }}
    			</div>
    			<div class="form-group col-md-4">
    				{{ Form::cText('Codigo Postal', 'codigo_postal') }}
    			</div>
    			<div class="form-group col-md-4">
    				{{ Form::cSelectWithDisabled('Pais', 'fk_id_pais', $paises ?? [], [
    					'class' => 'select2 select-cascade',
    					'data-target-url' => companyRoute('paises.show', ['id' => '#ID#']),
    					'data-target-el' => '[targeted="fk_id_estado"]',
    					'data-target-with' => '["estados:id_estado,fk_id_pais,estado"]',
    					'data-target-value' => 'estados,id_estado,estado'
    				]) }}
    			</div>
    			<div class="form-group col-md-4">
    				{{ Form::cSelect('Estado', 'fk_id_estado', $estados ?? [], [
    					'class' => 'select2 select-cascade',
    					'targeted' => 'fk_id_estado',
    					'data-target-url' => companyRoute('estados.show', ['id' => '#ID#']),
    					'data-target-el' => '[targeted="fk_id_municipio"]',
    					'data-target-with' => '["municipios:id_municipio,fk_id_estado,municipio"]',
    					'data-target-value' => 'municipios,id_municipio,municipio'
    				]) }}
    			</div>
    			<div class="form-group col-md-4">
    				{{ Form::cSelect('Municipio', 'fk_id_municipio', $municipios ?? [], [
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
    	<div class="col">
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
    				{{ Form::cSelectWithDisabled('Jurisdiccion', 'id_jurisdiccion') }}
    			</div>
    		</div>
    	</div>
    	<div  class="col-md-12 text-center mt-4">
    		<div class="alert alert-warning" role="alert">
                Recuerda que al no estar <b>activo</b>, este <b>dato</b> no se mostrara en los modulos correspondientes que se requieran.
            </div>
            {{ Form::cCheckboxBtn('Estatus','Activo','activo', $data['activo'] ?? null, 'Inactivo') }}
    	</div>
    </div>
@endsection