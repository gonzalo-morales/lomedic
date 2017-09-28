@extends('layouts.dashboard')

@section('title', currentEntityBaseName())

@section('header-top')
<!--dataTable.css-->
<link rel="stylesheet" href="{{ asset('vendor/vanilla-datatables/vanilla-dataTables.css') }}">
@endsection

@push('smart-js')
<script type="text/javascript">
	window['smart-model'].collections.itemsOptions = {
		view: {
			props: {
				'innerHTML': '<i class="material-icons">visibility</i>',
			},
			attrs: {
				'class': 'btn is-icon',
				'rv-get-show-url': '',
			}
		},
		@can('update', currentEntity())
		edit: {
			props: {
				'innerHTML': '<i class="material-icons">mode_edit</i>',
			},
			attrs: {
				'class': 'btn is-icon',
				'rv-get-edit-url': '',
			}
		},
		@endcan
		@can('delete', currentEntity())
		delete: {
			props: {
				'innerHTML': '<i class="material-icons">delete</i>',
			},
			attrs: {
				'href' : '#',
				'class': 'btn is-icon',
				'rv-on-click': 'actions.showModalDelete',
				'rv-get-delete-url': '',
				'data-delete-type': 'single',
			}
		},
		@endcan
		dummyopt: {
			props: {
				'innerHTML': '<i class="material-icons">visibility</i>',
			},
			attrs: {
				'href' : '#',
				'class': 'btn is-icon',
				'rv-on-click': 'actions.showModalDummy',
			}
		},
	};

	window['smart-model'].actions.showModalDummy = function(e, rv) {
		e.preventDefault();

		let modal = window['smart-modal'];
		modal.view = rivets.bind(modal, {
			title: 'Dummy title',
			content: '<form  id="dummy-form">' +
			'<div class="form-group">' +
			'<label for="recipient-name" class="form-control-label">Recipient:</label>' +
			'<input type="text" class="form-control" id="recipient-name" name="recipient">' +
			'</div>' +
			'<div class="form-group">' +
			'<label for="message-text" class="form-control-label">Message:</label>' +
			'<textarea class="form-control" id="message-text" name="message"></textarea>' +
			'</div>' +
			'</form>',
			buttons: [{
				props: {
					'innerHTML': 'Cancelar',
				},
				attrs: {
					'class': 'btn btn-secondary',
					'data-dismiss': 'modal',
				}
			},{
				props: {
					'innerHTML': 'Aceptar',
				},
				attrs: {
					'class': 'btn btn-primary',
					'rv-on-click': 'action',
				}
			}],
			action: function(e, rv) {

				var formData = new FormData(document.querySelector('#dummy-form')), convertedJSON = {}, it = formData.entries(), n;

				while(n = it.next()) {
					if(!n || n.done) break;
					convertedJSON[n.value[0]] = n.value[1];
				}

				console.log(convertedJSON)
			},
		})

	// Abrimos modal
	$(modal).modal('show');

};

// showModalMotivoCancelacion(e, rv) {
// 	e.preventDefault();

// 	// Abrimos modal
// 	$('#smart-modal').modal('open');

// 	let btn = smartView.querySelector('[rv-on-click="actions.itemsCancelacion"]');

// 	// Limpiamos data del elemento
// 	Object.keys(btn.dataset).forEach(function(key) {
// 		delete btn.dataset[key]
// 	});

// 	// Copiamos data a boton de modal
// 	Object.keys(this.dataset).forEach(function(key) {
// 		btn.dataset[key] = this.dataset[key];
// 	}.bind(this))
// },


// itemsCancelacion(e, rv) {
// 	e.preventDefault();

// 	let data, tablerows, motivo;
// 	motivo = $('#motivo_cancelacion').val();
// 	switch (this.dataset.deleteType) {
// 		case 'multiple':
// 		data =  {ids: rv.collections.items, motivo_cancelacion: motivo};
// 		tablerows = rv.collections.tablerows;
// 		break;
// 		case 'single':
// 		data =  {motivo_cancelacion: motivo};
// 		tablerows = [this.parentNode.parentNode.dataIndex];
// 		break;
// 	}

// 	//
// 	$.delete(this.dataset.deleteUrl, data, function(response) {
// 		if (response.success) {
// 			location.reload();
// 		}
// 	});

// },

// rivets.binders['get-item-id-and-estatus'] = {
// 	bind: function(el) {
// 		if (el.innerHTML == '') {
// 			el.outerHTML = document.querySelector('.smart-actions').innerHTML.replace(/#ID#/g, el.dataset.itemId).replace(/#ESTATUS#/g, el.dataset.itemEstatus);
// 		}
// 	},
// };
// rivets.binders['hide-delete'] = {
// 	bind: function (el) {
// 		if(el.dataset.itemEstatus != 1)
// 		{
// 			$(el).hide();
// 		}
// 	}
// };
// rivets.binders['hide-update'] = {
// 	bind: function (el) {
// 		if(el.dataset.itemEstatus != 1)
// 		{
// 			$(el).hide();
// 		}
// 	}
// };

</script>
@endpush

@section('header-bottom')
<script src="{{ asset('vendor/rivets/rivets.js') }}"></script>
<script src="{{ asset('vendor/vanilla-datatables/vanilla-dataTables.js') }}"></script>
<script src="{{ asset('js/smartindex.js') }}"></script>
@if (session('message'))
<script type="text/javascript">
	Materialize.toast('<span><i class="material-icons">priority_high</i>{{session('message.text')}}</span>', 4000, '{{session('message.type')}}' );
</script>
@endif
@stack('smart-js')
@endsection

@section('content')
<div class="container-fluid">
	{{ HTML::tag('h4', currentEntityBaseName(),['class'=>'col-md-12']) }}
	<div class="row">
		<div class="col">
			<section id="smart-view" class="row" data-primary-key="{{ currentEntity()->getKeyName() }}" data-columns="{{ json_encode(array_keys($fields)) }}" data-item-show-or-delete-url="{{ companyRoute('show', ['id' => '#ID#']) }}" data-item-update-url="{{ companyRoute('edit', ['id' => '#ID#']) }}">
				<div class="col-sm-6">
					<table class=table bordered striped highlight" hidden>
						<tr><td>items checked</td><td rv-text="collections.items | length"></td></tr>
						<tr><td>isDownloading</td><td rv-text="status.isDownloading"></td></tr>
						<tr><td>isAllChecked</td><td rv-text="status.isAllChecked | call < collections.items"></td></tr>
						<tr><td>items (keys)</td><td rv-text="collections.items | keys"></td></tr>
						<tr><td>datarows (values)</td><td rv-text="collections.items | values"></td></tr>
					</table>
				</div>
				<div class="col-md-12">
					<div class="float-right" rv-hide="collections.items | length">
						<a href="{{ companyRoute('create') }}" class="btn btn-primary" role="button">Crear</a>
						<button class="btn btn-secondary dropdown-toggle" type="button" id="export-all" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<i class="material-icons left">file_download</i>Exportar
						</button>
						<div class="dropdown-menu" aria-labelledby="export-all">
							<a href="#" class="dropdown-item" rv-on-click="actions.itemsExport" data-export-url="{{companyRoute('export', ['type' => 'XLSX'])}}">Libro Excel</a>
							<a href="#" class="dropdown-item" rv-on-click="actions.itemsExport" data-export-url="{{companyRoute('export', ['type' => 'PDF'])}}">Archivo Pdf</a>
							<div class="dropdown-divider"></div>
							<a href="#" class="dropdown-item" rv-on-click="actions.itemsExport" data-export-url="{{companyRoute('export', ['type' => 'XLS'])}}">Excel 97-2003</a>
							<a href="#" class="dropdown-item" rv-on-click="actions.itemsExport" data-export-url="{{companyRoute('export', ['type' => 'CSV'])}}">CSV</a>
							<a href="#" class="dropdown-item" rv-on-click="actions.itemsExport" data-export-url="{{companyRoute('export', ['type' => 'TXT'])}}">TXT</a>
						</div>
					</div>
					<div class="float-right" rv-show="collections.items | length" style="display: none;">
						<button class="btn" rv-on-click="actions.uncheckAll"><i class="material-icons left">select_all</i>Deseleccionar (<span rv-text="collections.items | length"></span>)</button>
						@can('delete', currentEntity())
						<button class="btn" rv-on-click="actions.showModalDelete" data-delete-type="multiple" data-delete-url="{{companyRoute('destroyMultiple')}}"><i class="material-icons left">delete</i>Eliminar (<span rv-text="collections.items | length"></span>)</button>
						@endcan
						<button class="btn btn-secondary dropdown-toggle" type="button" id="export-custom" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<i class="material-icons left">file_download</i>Exportar (<span rv-text="collections.items | length"></span>)
						</button>
						<div class="dropdown-menu" aria-labelledby="export-custom">
							<a href="#" class="dropdown-item" rv-on-click="actions.itemsExport" data-export-url="{{companyRoute('export', ['type' => 'XLSX'])}}">Libro Excel</a>
							<a href="#" class="dropdown-item" rv-on-click="actions.itemsExport" data-export-url="{{companyRoute('export', ['type' => 'PDF'])}}">Archivo Pdf</a>
							<div class="dropdown-divider"></div>
							<a href="#" class="dropdown-item" rv-on-click="actions.itemsExport" data-export-url="{{companyRoute('export', ['type' => 'XLS'])}}">Excel 97-2003</a>
							<a href="#" class="dropdown-item" rv-on-click="actions.itemsExport" data-export-url="{{companyRoute('export', ['type' => 'CSV'])}}">CSV</a>
							<a href="#" class="dropdown-item" rv-on-click="actions.itemsExport" data-export-url="{{companyRoute('export', ['type' => 'TXT'])}}">TXT</a>
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<table id="smart-table" class="smart-table table table-hover table-responsive">
						<thead>
							<tr>
								<th class="width-auto"><input type="checkbox" id="check-all" rv-on-click="actions.checkAll" rv-literal:checked="status.isAllChecked | call < collections.items"></th>
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
									<input type="checkbox" id="check-{{$row->getKey()}}" class="single-check" rv-on-click="actions.itemCheck" rv-append-items="collections.items" value="{{$row->getKey()}}">
								</td>
								@foreach ($fields as $field => $label)
								<td>{{ object_get($row, $field) }}</td>
								@endforeach
								<td class="width-auto not-wrap">
									<a rv-each-dynamics="collections.itemsOptions" data-item-id="{{$row->getKey()}}"></a>
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</section>
			<!-- Modal Structure -->
			<div id="smart-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="smart-modal-label" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="smart-modal-label" rv-text="title">Titulo</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body" rv-html="content">
							...
						</div>
						<div class="modal-footer">
							<button type="button" rv-each-dynamics="buttons"></button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection