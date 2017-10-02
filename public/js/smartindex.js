
/* Extend jQuery with functions for PUT and DELETE requests. */
function _ajax_request(e,t,u,r,a){return jQuery.isFunction(t)&&(u=t,t={}),jQuery.ajax({type:a,url:e,data:t,success:u,dataType:r})}jQuery.extend({put:function(e,t,u,r){return _ajax_request(e,t,u,r,"PUT")},delete:function(e,t,u,r){return _ajax_request(e,t,u,r,"DELETE")}});

var _createElement = function(a) {
	var c = document;
	if (a && "object" == typeof a) {
		var e, i;
		for (e in a) {
			var d = c.createElement(e);
			if (a[e] && "object" == typeof a[e]) {
				for (i in a[e]) {
					if ("html" === i) {
						d.innerHTML = a[e][i];
					} else if ("text" === i) {
						d.appendChild(c.createTextNode(a[e][i]));
					} else if ('childs' == i) {
						for (var x in a[e][i]) {
							d.append(_createElement(a[e][i][x], true))
						}
					} else {
						d.setAttribute(i, a[e][i]);
					}
				}
			}
		}
	}
	return d;
}

	function post_to_url(path, params, method) {
		method = method || 'post';

		var form = document.createElement('form');

	//Move the submit function to another variable
	//so that it doesn't get overwritten.
	form._submit_function_ = form.submit;

	form.setAttribute('method', method);
	form.setAttribute('action', path);

	for(var key in params) {
		var hiddenField = document.createElement('input');
		hiddenField.setAttribute('type', 'hidden');
		hiddenField.setAttribute('name', key);
		hiddenField.setAttribute('value', params[key]);

		form.appendChild(hiddenField);
	}

	document.body.appendChild(form);
	form._submit_function_(); //Call the renamed function.
}

let datatable = new DataTable('.smart-table', {
	perPageSelect: [20,30,50],
	perPage: 20,
	columns: [
		{select: [0], sortable: false },
	],
	// data: data,
	labels: {
		placeholder: 'Buscar datos ...',
		perPage: 'Mostrar {select} datos por pagina',
		noRows: 'No hay datos que mostrar',
		info: 'Mostrando {start} a {end} de {rows} datos (Pagina {page} de {pages})',
		icon:'<i class="material-icons prefix">search</i>',
	},
})

datatable.on('datatable.update', function() {
	view.unbind();
	view = rivets.bind(window['smart-view'], window['smart-model']);
	view.models.actions.itemsSync({}, view.models);
})

datatable.on('datatable.page', function() {
	view.unbind();
	view = rivets.bind(window['smart-view'], window['smart-model']);
	view.models.actions.itemsSync({}, view.models);
})

$('#smart-modal').on('show.bs.modal', function (e) {
	if (e.target.view) {
		if (e.target.view.models.onModalShow) {
			e.target.view.models.onModalShow();
		}
	}
})

$('#smart-modal').on('hidden.bs.modal', function (e) {
	if (e.target.view) {
		if (e.target.view.models.onModalHide) {
			e.target.view.models.onModalHide();
		}
		e.target.view.unbind();
	}
})

//
window['smart-model'] = {
	// Estados de vista
	status: {
		isDownloading: false,
		isAllChecked: function() {
			return window['smart-view'].querySelectorAll('.single-check:not(:checked)').length === 0;
		},
	},
	lastChecked: null,
	// Colecciones de datos
	collections: {
		headerOptions: [
			// Button
			{a: {
				href: '#',
				class: 'btn btn-primary',
				role: 'buttons',
				'rv-get-create-url': '',
				html: '<i class="material-icons left align-middle">add</i>Crear',
			}},
			// Dropdown
			{div: {
				class: 'dropdown d-inline',
				childs: [
					{button: {
						class: 'btn btn-secondary dropdown-toggle',
						type: 'button',
						'data-toggle': 'dropdown',
						'aria-haspopup': 'true',
						'aria-expanded': 'false',
						html: '<i class="material-icons left align-middle">file_download</i>Exportar'
					}},
					{div: {
						class: 'dropdown-menu dropdown-menu-right',
						childs: [
							{a: {text: 'Libro Excel', href:'#', class: 'dropdown-item', 'rv-on-click': 'actions.itemsExport', 'data-export-type': 'XLSX'}},
							{a: {text: 'Archivo Pdf', href:'#', class: 'dropdown-item', 'rv-on-click': 'actions.itemsExport', 'data-export-type': 'PDF'}},
							{div: {class: 'dropdown-divider'}},
							{a: {text: 'Excel 97-2003', href:'#', class: 'dropdown-item', 'rv-on-click': 'actions.itemsExport', 'data-export-type': 'XLS'}},
							{a: {text: 'CSV', href:'#', class: 'dropdown-item', 'rv-on-click': 'actions.itemsExport', 'data-export-type': 'CSV'}},
							{a: {text: 'TXT', href:'#', class: 'dropdown-item', 'rv-on-click': 'actions.itemsExport', 'data-export-type': 'TXT'}},
						]
					}}
				]},
			}
		],
		headerOptionsOnChecks: [
			// Button
			{button: {
				html: '<i class="material-icons left align-middle">select_all</i>Deseleccionar (<span rv-text="collections.items | length"></span>)',
				class: 'btn',
				'rv-on-click': 'actions.uncheckAll'
			}},
			// Dropdown
			{div: {
				class: 'dropdown d-inline',
				childs: [
					{button: {
						class: 'btn btn-secondary dropdown-toggle',
						type: 'button',
						'data-toggle': 'dropdown',
						'aria-haspopup': 'true',
						'aria-expanded': 'false',
						html: '<i class="material-icons left align-middle">file_download</i>Exportar (<span rv-text="collections.items | length"></span>)'
					}},
					{div: {
						class: 'dropdown-menu dropdown-menu-right',
						childs: [
							{a: {text: 'Libro Excel', href:'#', class: 'dropdown-item', 'rv-on-click': 'actions.itemsExport', 'data-export-type': 'XLSX'}},
							{a: {text: 'Archivo Pdf', href:'#', class: 'dropdown-item', 'rv-on-click': 'actions.itemsExport', 'data-export-type': 'PDF'}},
							{div: {class: 'dropdown-divider'}},
							{a: {text: 'Excel 97-2003', href:'#', class: 'dropdown-item', 'rv-on-click': 'actions.itemsExport', 'data-export-type': 'XLS'}},
							{a: {text: 'CSV', href:'#', class: 'dropdown-item', 'rv-on-click': 'actions.itemsExport', 'data-export-type': 'CSV'}},
							{a: {text: 'TXT', href:'#', class: 'dropdown-item', 'rv-on-click': 'actions.itemsExport', 'data-export-type': 'TXT'}},
						]
					}}
				]}
			}
		],
		// {itemId : datatable row }
		items: [],
		// Opciones en item
		itemsOptions: {},
	},
	// Funciones y/o Metodos
	actions: {
		checkAll: function(e, rv) {
			Array.prototype.forEach.call(window['smart-view'].querySelectorAll('.single-check'), function(item, index) {
				item.checked = this.checked;
			}.bind(this))
			rv.actions.itemsSync(e,rv)
		},
		uncheckAll: function(e,rv) {
			window['smart-view'].querySelector('#check-all').checked = false;
			rv.actions.checkAll(e, rv);
		},
		itemCheck: function(e, rv) {
			if (e.shiftKey && rv.lastChecked) {

				var checkboxes = [...window['smart-view'].querySelectorAll('.single-check')],
					checked = this.checked, from, to, start, end;

				for (var i in checkboxes) {
					if ( checkboxes[i] === rv.lastChecked ) from = i;
				};
				for (var i in checkboxes) {
					if ( checkboxes[i] === this ) to = i;
				};

				start = Math.min(from, to);
				end = Math.max(from, to) + 1;

				var range = checkboxes.slice(start, end)
				for (var i in range) {
					range[i].checked = checked;
					range[i].dispatchEvent(new Event('change'))
				}
			}
			rv.lastChecked = this;
		},
		itemsSync: function(e, rv) {
			rv.collections.items = [].slice.call(window['smart-view'].querySelectorAll('.single-check:checked')).reduce(function(acc, item) {
				acc.push({[item.value]: item.parentNode.parentNode.dataIndex}); return acc;
			}, [])
		},
		showModalDelete(e, rv) {
			e.preventDefault();

			let modal = window['smart-modal'];
			modal.view = rivets.bind(modal, {
				title: '¿Estas seguro?',
				content: 'Una vez eliminado(s) no podrás recuperarlo(s).',
				buttons: [
					{button: {
						'text': 'Cancelar',
						'class': 'btn btn-secondary',
						'data-dismiss': 'modal',
					}},
					{button: {
						'html': 'Eliminar',
						'class': 'btn btn-danger',
						'rv-on-click': 'action',
					}}
				],
				action: function(e) {
					window['smart-model'].actions.itemsDelete.call(this, e, rv);
				}.bind(this),
					// Opcionales
					onModalShow: function() {

						let btn = modal.querySelector('[rv-on-click="action"]');

						// Copiamos data a boton de modal
						for (var i in this.dataset) btn.dataset[i] = this.dataset[i];

					}.bind(this),
					// onModalHide: function() {}
				});

			// Abrimos modal
			$(modal).modal('show');
		},
		itemsDelete(e, rv) {
			e.preventDefault();

			let data, tablerows;

			switch (this.dataset.deleteType) {
				case 'multiple':
				data =  {ids: rivets.formatters.keys(rv.collections.items)};
				tablerows = rivets.formatters.values(rv.collections.items);
				break;
				case 'single':
				data =  {};
				tablerows = [this.parentNode.parentNode.dataIndex];
				break;
			}

			$(window['smart-modal']).modal('hide');
			//
			$.delete(this.dataset.deleteUrl, data, function(response) {
				if (response.success) {
					datatable.rows().remove(tablerows)
					$.toaster({
						priority: 'success', title: 'Exito', message: 'Registro(s) eliminado correctamente.',
						settings:{'timeout': 5000, 'toaster':{'css':{'top':'5em'}}}
					})
				}
			});
		},
		itemsExport(e, rv) {
			e.preventDefault();
			rv.status.isDownloading = true;
			post_to_url(window['smart-view'].dataset.itemExportUrl.replace('_ID_', this.dataset.exportType), {'ids' : rivets.formatters.keys(rv.collections.items)});
			rv.status.isDownloading = false;
		},
	},
}

rivets.binders['literal:*'] = function(el, value) {
	el[this.args[0]] = value;
}

rivets.formatters.length = function(value) {
	return value.length;
}

rivets.formatters.keys = function(value) {
	return value.reduce(function(acc, item){
		return acc.concat(Object.keys(item)[0])
	}, [])
}

rivets.formatters.values = function(value) {
	return value.reduce(function(acc, item){
		return acc.concat(item[Object.keys(item)[0]])
	}, [])
}

rivets.binders['append-items'] = {
	bind: function(el) {
		var self = this;
		this.callback = function() {
			currentValue = self.observer.value();
			currentKeys = self.view.formatters.keys(currentValue);
			if (!el.checked && (currentKeys.indexOf(el.value) >= 0)) {
				currentValue.splice(currentKeys.indexOf(el.value), 1)
			}
			if (el.checked && !(currentKeys.indexOf(el.value) >= 0)) {
				currentValue.push({[el.value]: el.parentNode.parentNode.dataIndex})
			}
			self.observer.setValue(currentValue)
		}
		$(el).on('change', this.callback)
		if (el.checked) this.callback()
	},
	unbind: function(el) {
		$(el).off('change', this.callback)
	},
	routine: function(el, value) {
		el.checked = (this.view.formatters.keys(value).indexOf(el.value) >= 0)
		if (el.checked) {
			el.parentNode.parentNode.classList.add('table-active')
		} else {
			el.parentNode.parentNode.classList.remove('table-active')
		}
	}
}

rivets.binders['each-dynamics'] = {
	bind: function(el, value) {
		if (this.marker == null) {
			attr = [this.view.prefix, this.type].join('-').replace('--', '-');
			this.marker = document.createComment(" rivets: " + this.type + " ");
			this.iterated = [];
			this.attr = {k: attr, v: el.getAttribute(attr)};
			el.removeAttribute(attr);
			el.parentNode.insertBefore(this.marker, el);
			el.parentNode.removeChild(el);
		} else {
			if (this.iterated) {
				for (var i in this.iterated) this.iterated[i].bind();
			}
		}
	},
	unbind: function(el) {
		if (this.iterated) {
			for (var i in this.iterated) {
				this.marker.parentNode.removeChild(this.iterated[i].els[0]);
				this.iterated[i].unbind();
			}
		}
		el.setAttribute(this.attr.k, this.attr.v)
		this.marker.parentNode.insertBefore(el, this.marker)
		this.marker.parentNode.removeChild(this.marker);
	},
	routine: function(el, collection) {
		var options = Object.keys(collection).reduce(function(acc, item) {
			return (typeof collection[item] !== 'function') ? acc.concat(collection[item]) : acc;
		}, []);
		for(var key in options) {
			var template = _createElement(options[key], el);
			this.marker.parentNode.append(template);
			this.iterated.push(rivets.bind(template, this.view.models))
		}
	}
};

rivets.binders['get-create-url'] = {
	bind: function(el) {
		el.href = window['smart-view'].dataset.itemCreateUrl;
	},
};

rivets.binders['get-show-url'] = {
	bind: function(el) {
		el.href = window['smart-view'].dataset.itemShowOrDeleteUrl.replace('#ID#', el.dataset.itemId);
	},
};

rivets.binders['get-edit-url'] = {
	bind: function(el) {
		el.href = window['smart-view'].dataset.itemUpdateUrl.replace('#ID#', el.dataset.itemId);
	},
};

rivets.binders['get-delete-url'] = {
	bind: function(el) {
		el.dataset.deleteUrl = window['smart-view'].dataset.itemShowOrDeleteUrl.replace('#ID#', el.dataset.itemId);
	},
};

rivets.binders['append-filters'] = function(el) {

	el.removeAttribute([this.view.prefix, this.type].join('-').replace('--', '-'));
	el.classList.add('form-control')

	var row = _createElement({div: {class: 'row',
		childs: [{div: {class:'col-md-12',
			childs: [{div: {class:'input-group',
				childs: [{div: {class:'input-group-btn',
					childs: [
						{button: {class: 'btn btn-secondary dropdown-toggle', type: 'button', 'data-toggle': 'dropdown', 'aria-haspopup': 'true', 'aria-expanded': 'false'}},
						{div: {class: 'dropdown-menu dropdown-menu-right'}}
					]
				}}]
			}}]
		}}]
	}})
	el.parentNode.append(row)

	var input_group = row.querySelector('.input-group');
		input_group.insertBefore(el, input_group.firstChild)

	//
	for (var i in datatable.headings) {
		var th = datatable.headings[i];
		if (th.innerText || th.textContent) {
			var a = _createElement({'a': {
				class: 'dropdown-item',
				href: "#",
				html: '<input type="checkbox" value='+i+' checked> ' + (th.innerText || th.textContent),
			}})
			row.querySelector('.dropdown-menu').append(a)

			$(a).on('click', function(e) {
				e.preventDefault();
				var selector = this.querySelector('[type="checkbox"]');
				selector.checked = !selector.checked
				if (selector.checked) {
					datatable.columns().show([parseInt(selector.value)]);
				} else {
					datatable.columns().hide([parseInt(selector.value)]);
				}
			})
		}
	}
};

window['smart-view'].querySelector('.dataTable-selector').classList.add('custom-select');
window['smart-view'].querySelector('.dataTable-input').setAttribute('rv-append-filters','')

let view = rivets.bind(window['smart-view'], window['smart-model']);

if (datatable.hasRows) {
	datatable.setMessage('Obteniendo elementos ...');
	getItems(1);
} else {
	datatable.setMessage('Sin elementos.');
}

/* */
function getItems($page) {

	let primary = window['smart-view'].dataset.primaryKey;
	let columns = JSON.parse(window['smart-view'].dataset.columns);

	$.getJSON(window['smart-view'].dataset.itemShowOrDeleteUrl.replace('#ID#', '') + '?page=' + $page, function(response) {
		let collection = [];
		$.each(response.data, function(index, item) {
			let id = item[primary];
			let estatus = item['fk_id_estatus'];
			let collection_item = {};
			collection_item['input'] = '<input type="checkbox" id="check-'+id+'" name="check-'+id+'" class="single-check" rv-on-click="actions.itemCheck" rv-append-items="collections.items" value="'+id+'">';
			$.each(columns, function(index, column) {
				collection_item[column] = (new Function('str', 'return eval("this." + str);')).call(item, column)
			})
			collection_item['actions'] = '<a rv-each-dynamics="collections.itemsOptions" data-item-id="'+id+'"></a>';
			collection.push(collection_item);
		})

		datatable.import({
			type: 'json',
			data: JSON.stringify(collection)
		});

		if (response.next_page_url) {
			datatable.setMessage('Elementos restantes ... ' + (response.total - response.to) );
			getItems(response.current_page + 1)
		}
	})
}