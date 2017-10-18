@extends('layouts.dashboard')

@section('title', currentEntityBaseName())

@section('header-top')
	<!--dataTable.css-->
	<link rel="stylesheet" href="{{ asset('vendor/vanilla-datatables/vanilla-dataTables.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">
@endsection

@section('header-bottom')
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/rivets/0.9.6/rivets.bundled.min.js"></script> --}}
    <script src="{{ asset('vendor/vanilla-datatables/vanilla-dataTables.js') }}"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
    <script type="text/javascript" src="{{ asset('js/sociosnegocios/index-socios.js') }}"></script>
    {{-- <script src="{{ asset('js/smartindex.js') }}"></script> --}}
    {{-- @if (session('message'))
    <script type="text/javascript">
    	Materialize.toast('<span><i class="material-icons">priority_high</i>{{session('message.text')}}</span>', 4000, '{{session('message.type')}}' );
    </script>
    @endif --}}
@endsection

@section('content')
<div class="row">
	<div class="col-sm-12">
		<section id="smart-view" class="row" >
			<div class="col s3">
				<table class="bordered striped highlight" hidden>
					<tr><td>isDownloading</td><td ></td></tr>
					<tr><td>isAllChecked</td><td ></td></tr>
					<tr><td>items</td><td ></td></tr>
					<tr><td>datarows</td><td ></td></tr>
				</table>
			</div>
			<div class="col-sm-12">
				<div class="row" rv-hide="actions.countItems | call < collections.items">
					<div class="right">
						<a href="{{ companyRoute('create') }}" class="btn btn-primary">Crear</a>
						<button class="btn btn-info dropdown-button" data-activates="export-all">
							<span rv-hide="status.isDownloading">
								<i class="material-icons left">file_download</i>Exportar
							</span>
						</button>
						<ul id="export-all" class="dropdown-content">
							{{-- <li><a href="#" class="teal-text" rv-on-click="actions.itemsExport" data-export-url="{{companyRoute('export', ['type' => 'XLSX'])}}">Libro Excel</a></li> --}}
							{{-- <li><a href="#" class="teal-text" rv-on-click="actions.itemsExport" data-export-url="{{companyRoute('export', ['type' => 'PDF'])}}">Archivo Pdf</a></li> --}}
							{{-- <li class="divider"></li> --}}
							{{-- <li><a href="#" class="blue-grey-text" rv-on-click="actions.itemsExport" data-export-url="{{companyRoute('export', ['type' => 'XLS'])}}">Excel 97-2003</a></li> --}}
							{{-- <li><a href="#" class="blue-grey-text" rv-on-click="actions.itemsExport" data-export-url="{{companyRoute('export', ['type' => 'CSV'])}}">CSV</a></li> --}}
							<li><a href="#" class="blue-grey-text export" data-type="json"  data-export-url="{{companyRoute('export', ['type' => 'TXT'])}}">TXT</a></li>
						</ul>
					</div>
				</div>
			</div>
			<div class="col-sm-12">
				<table class="dataTable table smart-table striped responsive-table highlight">
					<thead>
						<tr>
							{{-- <th class="width-auto"><input type="checkbox" id="check-all" rv-on-click="actions.checkAll" rv-checked="status.isAllChecked"><label for="check-all"></label></th> --}}
							{{-- @foreach ($data as $label)
							<th> {{ $label->nombre_corto }} </th>
							@endforeach --}}
							<th>Razón Social</th>
							<th>RFC</th>
							<th>Nombre</th>
							<th>Ramo</th>
							<th>Tipo de Socio</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
    					@foreach ($data as $row)
                            {{-- {{dd($data)}} --}}
    					<tr>
    						{{-- <td class="width-auto">
    							<input type="checkbox" id="check-{{$row->getKey()}}" class="single-check" data-item-id="{{$row->getKey()}}" rv-on-click="actions.itemsSync" rv-get-datarow  name="check-{{$row->getKey()}}">
    							<label for="check-{{$row->getKey()}}"></label>
    						</td> --}}
                            <td>{{ $row->razon_social }}</td>
                            <td>{{ $row->rfc }}</td>
                            <td>{{ $row->nombre_corto }}</td>
                            <td>{{ $row->ramo->ramo }}</td>
                            {{-- <td>{{ $row->tipoSocio }}</td> --}}
                            {{-- @if ($row->id_socio_negocio === 30)
                                <td>{{ dd($row->tipoSocio) }}</td>
                            @endif --}}
                            <td>
                                {{-- <div class="col-sm-6 col-md-3"> --}}
                                    <select id="tipo_socio" name="tipo_socio" multiple="multiple" class="form-control itipo_socios">

                                        @foreach($row->tipoSocio as $tipo)
                                            <option value="{{$tipo->id_tipo_socio}}"  disabled="true" data-select='unselected'>{{$tipo->tipo_socio}}</option>
                                        @endforeach
                                    </select>
                                {{-- </div> --}}
                            </td>
                            <td>
                                <a class="btn btn-flat no-padding" data-item-id="{{ companyRoute('show', ['id' => $row->id_socio_negocio]) }}"><i class="material-icons">visibility</i></a>
                                <a class="btn btn-flat no-padding" data-item-id="{{ companyRoute('edit', ['id' => $row->id_socio_negocio]) }}"><i class="material-icons">edit</i></a>
                            </td>

                            {{-- @foreach ($row as $label)
    						<td>{{ object_get($row , $label) }}</td>
    						@endforeach
    						<td class="width-auto">
    							<span data-item-id="{{$row->getKey()}}" data-item-estatus="{{$row->estatus}}"></span>
    						</td> --}}
    						<td class="width-auto"></td>
    					</tr>
    					@endforeach
					</tbody>
				</table>
			</div>

		</section>
		{{-- <div class="smart-actions" hidden>
			<a class="waves-effect waves-light btn btn-flat no-padding" data-item-id="#ID#" ><i class="material-icons">visibility</i></a>
			@can('update', currentEntity())
			<a class="waves-effect waves-light btn btn-flat no-padding" data-item-id="#ID#" rv-get-edit-url rv-hide-update data-item-estatus="#ESTATUS#"><i class="material-icons">mode_edit</i></a>
			@endcan
			@can('delete', currentEntity())
			@endcan
			<a href="#" class="waves-effect waves-light btn btn-flat no-padding" data-item-estatus="#ESTATUS#"  ><i class="material-icons">delete</i></a>
		</div> --}}
	</div>
</div>
@endsection
