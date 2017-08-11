@extends('layouts.dashboard')

@section('title', currentEntityBaseName())

@section('header-top')
	<!--dataTable.css-->
	<link rel="stylesheet" href="{{ asset('vendor/vanilla-datatables/vanilla-dataTables.css') }}">
@endsection

@section('header-bottom')
<script src="https://cdnjs.cloudflare.com/ajax/libs/rivets/0.9.6/rivets.bundled.min.js"></script>
<script src="{{ asset('vendor/vanilla-datatables/vanilla-dataTables.js') }}"></script>
<script src="{{ asset('js/smartindex.js') }}"></script>
@if (session('message'))
<script type="text/javascript">
	Materialize.toast('<span><i class="material-icons">priority_high</i>{{session('message.text')}}</span>', 4000, '{{session('message.type')}}' );
</script>
@endif
@endsection

@section('content')
<div class="row">
	<div class="col s12">
		<section id="smart-view" class="row">
			<div class="col s3" hidden>
				<table class="bordered striped highlight">
					<tr><td>isDownloading</td><td rv-text="status.isDownloading"></td></tr>
					<tr><td>isAllChecked</td><td rv-text="status.isAllChecked"></td></tr>
					<tr><td>items</td><td rv-text="collections.items"></td></tr>
					<tr><td>datarows</td><td rv-text="collections.datarows"></td></tr>
				</table>
			</div>
			<div class="col s12 xl8 offset-xl2">
				<div class="row" rv-hide="actions.countItems | call < collections.items">
					<div class="right">
						<a href="{{ companyRoute('create') }}" class="btn orange waves-effect waves-light">Crear</a>
						<button class="btn waves-effect waves-light dropdown-button" data-activates="export-all">
							<span rv-hide="status.isDownloading">
								<i class="material-icons left">file_download</i>Exportar
							</span>
							<div rv-show="status.isDownloading" class="preloader-wrapper small active" style="display: none;">
								<div class="spinner-layer spinner-blue-only">
									<div class="circle-clipper left">
										<div class="circle"></div>
									</div>
									<div class="gap-patch">
										<div class="circle"></div>
									</div>
									<div class="circle-clipper right">
										<div class="circle"></div>
									</div>
								</div>
							</div>
						</button>
						<ul id="export-all" class="dropdown-content">
							<li><a href="#" rv-on-click="actions.itemsExport" data-export-url="{{companyRoute('export', ['type' => 'CSV'])}}">Exportar a Excel</a></li>
							<li><a href="#" rv-on-click="actions.itemsExport" data-export-url="{{companyRoute('export', ['type' => 'PDF'])}}">Exportar a PDF</a></li>
						</ul>
					</div>
				</div>
				<div class="row" rv-show="actions.countItems | call < collections.items" style="display: none;">
					<div class="right">
						<button class="btn waves-effect waves-light" rv-on-click="actions.uncheckAll"><i class="material-icons left">select_all</i>Deseleccionar (<span rv-text="actions.countItems | call < collections.items"></span>)</button>
						@can('delete', currentEntity())
						<button class="btn waves-effect waves-light" rv-on-click="actions.showModalDelete" data-delete-type="multiple" data-delete-url="{{companyRoute('destroyMultiple')}}"><i class="material-icons left">delete</i>Eliminar (<span rv-text="actions.countItems | call < collections.items"></span>)</button>
						@endcan
						<button class="btn waves-effect waves-light dropdown-button" data-activates="export-custom">
							<span rv-hide="status.isDownloading">
								<i class="material-icons left">file_download</i>Exportar (<span rv-text="actions.countItems | call < collections.items"></span>)
							</span>
							<div rv-show="status.isDownloading" class="preloader-wrapper small active">
								<div class="spinner-layer spinner-blue-only">
									<div class="circle-clipper left">
										<div class="circle"></div>
									</div>
									<div class="gap-patch">
										<div class="circle"></div>
									</div>
									<div class="circle-clipper right">
										<div class="circle"></div>
									</div>
								</div>
							</div>
						</button>
						<ul id="export-custom" class="dropdown-content">
							<li><a href="#" rv-on-click="actions.itemsExport" data-export-url="{{companyRoute('export', ['type' => 'CSV'])}}">Exportar a Excel</a></li>
							<li><a href="#" rv-on-click="actions.itemsExport" data-export-url="{{companyRoute('export', ['type' => 'PDF'])}}">Exportar a PDF</a></li>
						</ul>
					</div>
				</div>
			</div>
			<div class="col s12 xl8 offset-xl2">
				<table class="smart-table striped responsive-table highlight">
					<thead>
						<tr>
							<th class="width-auto"><input type="checkbox" id="check-all" rv-on-click="actions.checkAll" rv-checked="status.isAllChecked"><label for="check-all"></label></th>
							@foreach ($fields as $label)
							<th> {{ $label }} </th>
							@endforeach
							<th></th>
						</tr>
					</thead>
					<tbody>
					@foreach ($data as $row)
					<tr>
						<td class="width-auto">
							<input type="checkbox" id="check-{{$row->getKey()}}" class="single-check" data-id="{{$row->getKey()}}" rv-on-click="actions.itemsSync" rv-get-datarow>
							<label for="check-{{$row->getKey()}}"></label>
						</td>
						@foreach ($fields as $field => $label)
						<td>{{ $row->{$field} }}</td>
						@endforeach
						<td class="width-auto smart-actions">
							<a href="{{ companyRoute('show', ['id' => $row->getKey()]) }}" class="waves-effect waves-light btn btn-flat no-padding"><i class="material-icons">visibility</i></a>
							@can('update', currentEntity())
							<a href="{{ companyRoute('edit', ['id' => $row->getKey()]) }}" class="waves-effect waves-light btn btn-flat no-padding"><i class="material-icons">mode_edit</i></a>
							@endcan
							@can('delete', currentEntity())
							<a href="#" class="waves-effect waves-light btn btn-flat no-padding" rv-on-click="actions.showModalDelete" rv-get-datarow data-delete-type="single" data-delete-url="{{companyRoute('destroy', ['id' => $row->getKey()])}}"><i class="material-icons">delete</i></a>
							@endcan
						</td>
					</tr>
					@endforeach
					</tbody>
				</table>
			</div>
			<!-- Modal Structure -->
			<div id="modal-delete" class="modal">
				<div class="modal-content">
					<h5>¿Estas seguro?</h5>
					<p>Una vez eliminado no podrás recuperarlo.</p>
				</div>
				<div class="modal-footer">
					<button class="modal-action modal-close waves-effect waves-light btn orange" rv-on-click="actions.itemsDelete">Aceptar</button>
					<button class="modal-action modal-close waves-effect waves-teal btn-flat teal-text">Cancelar</button>
				</div>
			</div>
		</section>
	</div>
</div>
@endsection