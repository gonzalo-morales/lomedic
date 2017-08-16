function post_to_url(path, params, method) {
    method = method || "post";

    var form = document.createElement("form");

    //Move the submit function to another variable
    //so that it doesn't get overwritten.
    form._submit_function_ = form.submit;

    form.setAttribute("method", method);
    form.setAttribute("action", path);

    for(var key in params) {
        var hiddenField = document.createElement("input");
        hiddenField.setAttribute("type", "hidden");
        hiddenField.setAttribute("name", key);
        hiddenField.setAttribute("value", params[key]);

        form.appendChild(hiddenField);
    }

    document.body.appendChild(form);
    form._submit_function_(); //Call the renamed function.
}
    
let datatable = new DataTable(".smart-table", {
	perPageSelect: [20,30,50],
	perPage: 20,
	columns: [
		{ select: [0], sortable: false },
	],
	// data: data,
	labels: {
		placeholder: "Buscar datos ...",
		perPage: "Mostrar {select} datos por pagina",
		noRows: "No hay datos que mostrar",
		info: "Mostrando {start} a {end} de {rows} datos (Pagina {page} de {pages})",
	},
});

datatable.on("datatable.update", function() {
	view.unbind();
	view = rivets.bind(smartView, model);
	view.models.actions.itemsSync({}, view.models);
});

datatable.on("datatable.page", function() {
	view.unbind();
	view = rivets.bind(smartView, model);
	view.models.actions.itemsSync({}, view.models);
});

// FIX Datatable-Materialize
document.addEventListener("DOMContentLoaded", function(event) {
	Array.prototype.forEach.call(document.querySelectorAll('.dataTable-selector .select-dropdown li'), function(item, index){
		item.addEventListener('click', function(e){
			e.preventDefault()
			var evt = document.createEvent("HTMLEvents"); evt.initEvent('change', false, true);
			document.querySelector('select.dataTable-selector').dispatchEvent(evt);
		}, false)
	})
}, false);

//
document.querySelector('select').setAttribute('rv-on-change', 'actions.itemsSync')
//
let smartView = document.querySelector('#smart-view');
//
let model = {
	// Estados de vista
	status: {
		isDownloading: false,
		isAllChecked: false,
	},
	//
	collections: {
		// Entity item
		items: [],
		// Datatable items
		datarows: []
	},
	//
	actions: {
		countItems: function(e, items) {
			return items.length;
		},
		isAllChecked: function() {
			return smartView.querySelectorAll('.single-check:not(:checked)').length == 0;
		},
		checkAll: function(e,rv){
			Array.prototype.forEach.call(smartView.querySelectorAll('.single-check'), function(item, index) {
				item.checked = this.checked;
			}.bind(this))
			rv.actions.itemsSync(e,rv)
		},
		uncheckAll: function(e,rv){
			smartView.querySelector('#check-all').checked = false;
			rv.actions.checkAll(e,rv);
		},
		itemsSync: function(e, rv) {
			rv.collections.datarows = [];
			rv.collections.items = [].slice.call(smartView.querySelectorAll('.single-check:checked')).reduce(function(acc, item){
				rv.collections.datarows.push(item.dataset.datarow);
				acc.push(item.dataset.id); return acc;
			}, [])
			rv.status.isAllChecked = rv.actions.isAllChecked();
		},
		showModalDelete(e, rv) {
			e.preventDefault();

			// Abrimos modal
			$('#modal-delete').modal('open');

			let btn = smartView.querySelector('[rv-on-click="actions.itemsDelete"]');

			// Limpiamos data del elemento
			Object.keys(btn.dataset).forEach(function(key) {
				delete btn.dataset[key]
			})

			// Copiamos data a boton de modal
			Object.keys(this.dataset).forEach(function(key) {
				btn.dataset[key] = this.dataset[key];
			}.bind(this))
		},
		itemsDelete(e, rv) {
			e.preventDefault();

			let data, datarows;

			switch (this.dataset.deleteType) {
				case 'multiple':
					data =  {ids: rv.collections.items};
					datarows = rv.collections.datarows;
					break;
				case 'single':
					data =  {};
					datarows = [this.dataset.datarow];
					break;
			}

			//
			$.delete(this.dataset.deleteUrl, data, function(response){
				if (response.success) {
					datatable.removeRows(datarows)
				}
			});

		},
		itemsExport(e, rv) {
			e.preventDefault();
			//
			rv.status.isDownloading = true;
			post_to_url(this.dataset.exportUrl, {'ids' : rv.collections.items});
			rv.status.isDownloading = false;
		},
	},
}

rivets.binders['get-datarow'] = {
	bind: function(el) {
		el.dataset['datarow'] = el.parentNode.parentNode.dataset.datarow;
	},
	unbind: function(el) {
		delete el.dataset['datarow'];
	},
};

let view = rivets.bind(smartView, model);