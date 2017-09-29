
/* Extend jQuery with functions for PUT and DELETE requests. */
function _ajax_request(e,t,u,r,a){return jQuery.isFunction(t)&&(u=t,t={}),jQuery.ajax({type:a,url:e,data:t,success:u,dataType:r})}jQuery.extend({put:function(e,t,u,r){return _ajax_request(e,t,u,r,"PUT")},delete:function(e,t,u,r){return _ajax_request(e,t,u,r,"DELETE")}});

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
		//{ select: [2], case_sensitive: true },
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
						'innerHTML': 'Eliminar',
					},
					attrs: {
						'class': 'btn btn-danger',
						'rv-on-click': 'action',
					}
				}],
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

			//
			$.delete(this.dataset.deleteUrl, data, function(response) {
				if (response.success) {
					datatable.rows().remove(tablerows)
					$(window['smart-modal']).modal('hide');
				}
			});
		},
		itemsExport(e, rv) {
			e.preventDefault();
			rv.status.isDownloading = true;
			post_to_url(this.dataset.exportUrl, {'ids' : rivets.formatters.keys(rv.collections.items)});
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
			var template = el.cloneNode(true);
			if (options[key].props) {
				let props = options[key].props;
				for (let i in props) {
					template[i] = (!!(props[i] && props[i].call && props[i].apply) ? props[i].bind(this.view.models) : props[i]);
				}
			}
			if (options[key].attrs) {
				let attrs = options[key].attrs;
				for (let i in attrs) {
					template.setAttribute(i, attrs[i])
				}
			}
			this.marker.parentNode.append(template)
			this.iterated.push(rivets.bind(template, this.view.models))
		}
	}
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
			collection_item['input'] = '<input type="checkbox" id="check-'+id+'" class="single-check" rv-on-click="actions.itemCheck" rv-append-items="collections.items" value="'+id+'">';
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