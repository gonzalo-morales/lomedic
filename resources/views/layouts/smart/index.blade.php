@extends('layouts.dashboard')

@section('title', currentEntityBaseName())

@section('form-title', str_singular(currentEntityBaseName()))


@section('header-top')
    <!--dataTable.css-->
    <link rel="stylesheet" href="{{ asset('vendor/vanilla-datatables/vanilla-dataTables.css') }}">
@endsection

@section('smart-js')
    <script type="text/javascript">
    	window['smart-model'].collections.itemsOptions.view = {a: {
    		'html': '<i class="material-icons">visibility</i>',
    		'class': 'btn is-icon',
    		'rv-get-show-url': '',
    		'data-toggle':'tooltip',
    		'title':'@lang("forms.show")'
    	}};
    	@can('update', currentEntity())
    	window['smart-model'].collections.itemsOptions.edit = {a: {
    		'html': '<i class="material-icons">mode_edit</i>',
    		'class': 'btn is-icon',
    		'rv-get-edit-url': '',
            'data-toggle':'tooltip',
            'title':'@lang("forms.edit")'
    	}};
    	@endcan
    	@can('delete', currentEntity())
    	window['smart-model'].collections.itemsOptions.delete = {a: {
    		'html': '<i class="material-icons">delete</i>',
    		'href' : '#',
    		'class': 'btn is-icon',
    		'rv-on-click': 'actions.showModalDelete',
    		'rv-get-delete-url': '',
    		'data-delete-type': 'single',
            'data-toggle':'tooltip',
            'title':'@lang("forms.delete")'
    	}};
    	@endcan
    	
    	window['smart-model'].collections.headerOptions.splice(1, 0,
    			@can('create', currentEntity())
    	    {a: {
				href: '#',
				class: 'btn btn-primary progress-button',
				role: 'buttons',
				'rv-get-create-url': '',
				html: '<i class="material-icons left align-middle">add</i>@lang("forms.create")',
			}},
			@endcan
			@can('export', currentEntity())
			{div: {
				class: 'dropdown d-inline',
				childs: [
					{button: {
						class: 'btn btn-info dropdown-toggle',
						type: 'button',
						'data-toggle': 'dropdown',
						'aria-haspopup': 'true',
						'aria-expanded': 'false',
						html: '<i class="material-icons left align-middle">file_download</i>@lang("forms.export")'
					}},
					{div: {
						class: 'dropdown-menu dropdown-menu-right',
						childs: [
							{a: {html:'<i class="material-icons text-info m-1">insert_link</i> ', text: '@lang("forms.file") Excel', style:'vertical-align: text-bottom', href:'#', class: 'dropdown-item', 'rv-on-click': 'actions.itemsExport', 'data-export-type': 'XLSX'}},
							{a: {html:'<i class="material-icons text-info m-1">insert_link</i> ', text: '@lang("forms.file") Pdf', href:'#', class: 'dropdown-item', 'rv-on-click': 'actions.itemsExport', 'data-export-type': 'PDF'}},
							{div: {class: 'dropdown-divider'}},
							{a: {text: 'Excel 97-2003', href:'#', class: 'dropdown-item', 'rv-on-click': 'actions.itemsExport', 'data-export-type': 'XLS'}},
							{a: {text: '@lang("forms.file") CSV', href:'#', class: 'dropdown-item', 'rv-on-click': 'actions.itemsExport', 'data-export-type': 'CSV'}},
							{a: {text: '@lang("forms.file") TXT', href:'#', class: 'dropdown-item', 'rv-on-click': 'actions.itemsExport', 'data-export-type': 'TXT'}},
						]
					}}
				]
			}}
			@endcan
		);

    	window['smart-model'].collections.headerOptionsOnChecks.splice(1, 0,
        	{button: {
        		html: '<i class="material-icons left align-middle">select_all</i>@lang("forms.deselected") (<span rv-text="collections.items | length"></span>)',
        		class: 'btn btn-default text-primary',
        		'rv-on-click': 'actions.uncheckAll'
        	}},
        	@can('delete', currentEntity())
        	{button: {
        		'class': 'btn btn-danger',
        		'rv-on-click': 'actions.showModalDelete',
        		'data-delete-type': 'multiple',
        		'data-delete-url': '{{companyRoute('destroyMultiple')}}',
        		'html': '<i class="material-icons left align-middle">delete</i>@lang("forms.delete") (<span rv-text="collections.items | length"></span>)'
        	}},
        	@endcan
        	@can('export', currentEntity())
        	{div: {
    			class: 'dropdown d-inline',
    			childs: [
    				{button: {
    					class: 'btn btn-info dropdown-toggle',
    					type: 'button',
    					'data-toggle': 'dropdown',
    					'aria-haspopup': 'true',
    					'aria-expanded': 'false',
    					html: '<i class="material-icons left align-middle">file_download</i>@lang("forms.export") (<span rv-text="collections.items | length"></span>)'
    				}},
    				{div: {
    					class: 'dropdown-menu dropdown-menu-right',
    					childs: [
    						{a: {html:'<i class="material-icons text-info m-1">insert_link</i> ', text: '@lang("forms.file") Excel', href:'#', class: 'dropdown-item', 'rv-on-click': 'actions.itemsExport', 'data-export-type': 'XLSX'}},
    						{a: {html:'<i class="material-icons text-info m-1">insert_link</i> ', text: '@lang("forms.file") Pdf', href:'#', class: 'dropdown-item', 'rv-on-click': 'actions.itemsExport', 'data-export-type': 'PDF'}},
    						{div: {class: 'dropdown-divider'}},
    						{a: {text: 'Excel 97-2003', href:'#', class: 'dropdown-item', 'rv-on-click': 'actions.itemsExport', 'data-export-type': 'XLS'}},
    						{a: {text: '@lang("forms.file") CSV', href:'#', class: 'dropdown-item', 'rv-on-click': 'actions.itemsExport', 'data-export-type': 'CSV'}},
    						{a: {text: '@lang("forms.file") TXT', href:'#', class: 'dropdown-item', 'rv-on-click': 'actions.itemsExport', 'data-export-type': 'TXT'}},
    					]
    				}}
    			]
    		}},
    		@endcan
    	);
    </script>
@endsection

@section('header-bottom')
    <script src="{{ asset('vendor/rivets/rivets.js') }}"></script>
    <script src="{{ asset('vendor/vanilla-datatables/vanilla-dataTables.js') }}"></script>
    <script src="{{ asset('js/smartindex.js') }}"></script>
    @if (session('message'))
        <script type="text/javascript">
        	$.toaster({
        		priority: 'success', title: 'Exito', message: '{{session('message.text')}}',
        		settings:{'timeout': 5000, 'toaster':{'css':{'top':'5em'}}}
        	});
        </script>
    @endif
    @yield('smart-js')
@endsection

@section('content')
    <div class="container-fluid">
    	<div class="row">
    		<div class="col-sm-12">
    			<section id="smart-view" class="row" data-primary-key="{{ currentEntity()->getKeyName() }}" data-columns="{{ json_encode(array_keys($fields)) }}" data-item-create-url="{{ companyRoute('create') }}" data-item-show-or-delete-url="{{ companyRoute('show', ['id' => '#ID#']) }}" data-item-update-url="{{ companyRoute('edit', ['id' => '#ID#']) }}" data-item-export-url="{{companyRoute('export', ['type' => '_ID_'])}}">
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
    						<a rv-each-dynamics="collections.headerOptions"></a>
    					</div>
    					<div class="float-right" rv-show="collections.items | length" style="display: none;">
    						<a rv-each-dynamics="collections.headerOptionsOnChecks"></a>
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
    									<input type="checkbox" id="check-{{$row->getKey()}}" name="check-{{$row->getKey()}}" class="single-check" rv-on-click="actions.itemCheck" rv-append-items="collections.items" value="{{$row->getKey()}}">
    								</td>
    								@foreach ($fields as $field => $label)
    								<td>{{ str_limit(object_get($row, $field),90) }}</td>
    								@endforeach
    								<td class="width-auto not-wrap">
    									<a rv-each-dynamics="collections.itemsOptions" data-item-id="{{$row->getKey()}}" {!! isset($row['data-atributes']) ? $row['data-atributes'] : '' !!} ></a>
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