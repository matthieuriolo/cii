/** App specific functions and settings **/
var App = {
	baseUrl: '',//we better do it relative
	/*
	locale: {
		code: globalLanguage,
		timezone: globalTimezoneSeconds
	},*/
	
	pollingDuration: 10 * 1000,

	getContrName: function getContrName(name) { return 'controllers/' + name.toLowerCase(); },
	/*getTemplName: function getTemplName(name) {
		return 'text!jstemplate/' + App.getYiiUrl('site/jstemplates', {'view': name.toLowerCase()})
	},*/

	getModuleName: function getModuleName(name) { return 'modules/' + name.toLowerCase(); },
	/*
	getTranslationName: function(name) {
		return App.getYiiUrl('site/jstemplates', {'view': 'i18n/' + App.locale.code + '/' + name.toLowerCase()})
	},*/
	

	loadControllersFromDOM: function(node) {
		if(!node)
			node = $('html')
		
		node.find('[data-controller]').each(function() {
			var node = this;
			var names = $(node).data('controller').split(' ')
			names.forEach(function(name) {
				require([App.getContrName(name)], function(contr) {
					new contr(node);
				})
			})
		})
		/*
		node.find('[data-toggle="popover"]').popover({
			trigger: 'click hover focus'
		})*/
		
		//node.find('[data-toggle="tooltip"]').tooltip()
		
		//node.find('.selectpicker').selectpicker();
		/*
		node.find('.datetimepicker').each(function() {
			var n = $(this)
			var hid = n.find('input[type="hidden"]')
			var mom = moment(hid.val(), 'YYYY-MM-DD HH:mm:ss')
			
			n.datetimepicker({
				language: App.locale.code,
				defaultDate: mom,
				useCurrent: false,
			}).on('dp.change', function(changed) {
				$(this).find('input[type="hidden"]').val(changed.date.format('YYYY-MM-DD HH:mm:ss'))
			})
		})*/
	},

	getAbsoluteUrl: function() {
		if(!$('base').length) {
			document.head.appendChild(document.createElement('base'))
		}
		var href = $('base')[0].href
		var idx = href.indexOf('/index.php?')
		if(idx!==-1) {
			return href.substr(0, idx) + '/'
		}

		return href
	},

	getYiiUrl: function(path, conf, absoluteURL) {
		params = conf || {}
		params['r'] = path;
		var ret = 'index.php?' + $.param(params);

		if(!absoluteURL) {
			return ret;
		}

		return App.getAbsoluteUrl() + ret;
	},

	getApiUrl: function(action, params) {
		return App.getYiiUrl('api/' + action, params);
	},
	
	/* deprecated - see getApiUrl */
	getBrowserUrl: function(action, params) {
		return App.getApiUrl('browser/' + action, params)
	},

	
	
	postJSON: function(url, data) {
		data = data || {}
		data[yii.getCsrfParam()] = yii.getCsrfToken()

		var t = $.post(url, data, null, 'json')
		return t;
		/*
		var ret = t.then(App.validateJSONResponse, function(xhr, type, resp) {
			try {
				return App.validateJSONResponse(JSON.parse(xhr.responseText))
			} catch(e) {
				return App.validateJSONResponse(resp)
			}
		});
		ret.origPromise = t
		*/
		return ret
	},
	
	getJSON: function(url, data) {
		data = data || {}
		return $.get(url, data, null, 'json').then(App.validateJSONResponse);
	},
	
	
	validateJSONResponse: function(resp) {
		if(resp && resp.code_error !== undefined && resp.code_error == 0) {
			return $.Deferred().resolve(resp.data).promise();
		}
		
		//its invalid - show flash
		if(resp.code_error !== undefined) {
			if(resp.code_error <= 10) {
				var code = resp.code_error
				require([App.getTranslationName('browser')], function(transl) {
					var errorMsg = App.t(transl, 'error.json.' + code);
					App.addFlash($('#main-flashes'), errorMsg, 'danger')
				})
			}
		}

		return $.Deferred().reject(resp.error_code).promise();
	},



	getSelector: function(node) {
		/*
		thanks to
		http://stackoverflow.com/questions/3620116/get-css-path-from-dom-element
		Gumbo
		*/
		var el = node;
		if (!(el instanceof Element)) 
            return;
        var path = [];
        while (el.nodeType === Node.ELEMENT_NODE) {
            var selector = el.nodeName.toLowerCase();
            if (el.id) {
                selector += '#' + el.id;
                path.unshift(selector);
                break;
            } else {
                var sib = el, nth = 1;
                while (sib = sib.previousElementSibling) {
                    if (sib.nodeName.toLowerCase() == selector)
                       nth++;
                }
                if (nth != 1)
                    selector += ":nth-of-type("+nth+")";
            }
            path.unshift(selector);
            el = el.parentNode;
        }
        return path.join(" > ");
	},

	
	openGPModal: function(url) {
		if(!url)
			url = App.getAbsoluteUrl();
		window.open('https://plus.google.com/share?url=' + encodeURIComponent(url), 'googlepluswindow', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,width=950,height=850');
	},

	openFBModal: function(url) {
		if(!url)
			url = App.getAbsoluteUrl();
		window.open('https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(url), 'facebookwindow', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,width=500,height=350');
	},

	openTWModal: function(url) {
		if(!url)
			url = App.getAbsoluteUrl();
		window.open('https://twitter.com/share?url='  + encodeURIComponent(url), 'twitterwindow', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,width=500,height=350');
	},

	/*
	_openModals: [],
	
	openModal: function(bindings, template) {
		var q = jQuery.Deferred()
		if(!template)
			template = 'defaultmodal'
		
		require([App.getTemplName(template)], function(html) {
			html = $(html)
			
			$('body').append(html)
			html.modal({
				show: true,
			})
			
			html.css({zIndex: 1050 + App._openModals.length * 2 + 1})
			$('.modal-backdrop:last').css({zIndex: 1050 + App._openModals.length * 2})
			
			html.on('hidden.bs.modal', function() {
				App._openModals.pop()
				html.remove()
			})
			
			App._openModals.push(html)
			rivets.bind(html, bindings)
			q.resolve(html)
		});

		return q
	},
	
	closeLastModal: function() {
		//may the modal has been closed by bootstrap
		var elem = App._openModals.pop()
		$(elem).modal('hide')
		return elem
	},
	
	closeAllModals: function() {
		App._openModals.forEach(function(elem) {
			$(elem).modal('hide')
		})
	},
	
	confirmModal: function(opts, template) {
		var q = $.Deferred()
		
		require([App.getTranslationName('browser')], function(transl) {
			if(!template)
				template = 'defaultconfirmmodal'
			
			opts = $.extend({
				okHandler: function() {
					q.resolve()
				},
				
				cancelHandler: function() {
					q.reject()
				},
				
				title: 'A confirm title',
				message: 'A confirm message',
				okText: App.t(transl, 'label.ok'),
				cancelText: App.t(transl, 'label.cancel'),
			}, opts)
			
			App.openModal(opts, template)
		})
		
		return q.promise()
	},*/
	
	defaultUnitPrefixes: ['B', 'KB', 'MB', 'GB', 'TB'],
	
	bytesFormatter: function(number, prefixes) {
		prefixes = prefixes || App.defaultUnitPrefixes;
		
		var pre = prefixes[0]
		prefixes.forEach(function(elem, index) {
			if(index == 0) return;
			
			var c = number / 1024;
			if(c >= 0.5) {
				pre = elem
				number = c
			}
		})
		
		return App.numberFormatter(number, 2) + pre;
	},
	
	/* deprecated - see stdlib dateFormatter */
	numberFormatter: function(number, decs) {
		if(!$.isNumeric(number))
			number = 0;
		
		if(decs !== undefined && number != 0) {
			number = number.toFixed(decs)
		}
		
		return number;
	},
	
	/* deprecated - see stdlib dateFormatter */
	mysqlDatetimeToJSDatetime: function(date) {
		/* will only work for gmt datetimes */
		var tks = date.split(/[- :]/)
		if(tks.length < 6)
			return null;
		return new Date(tks[0], tks[1] - 1, tks[2], tks[3], tks[4], tks[5])
	},
	
	/* for i18n */
	/* deprecated - see stdlib dateFormatter */
	dateFormatter: function(date) {
		return date;
	},
	
	addFlash: function($elem, msg, type) {
		if(!type)
			type = 'success'
		$elem.append('<div class="alert alert-' + type + '">' + msg + '</div>');
	},


	/*t: function(cat, token, dict) {
		dict = dict || {}
		if(cat[token]) {
			var t = cat[token]
			for(var repl in dict) {
				var v = dict[repl]
				t = t.replace(new RegExp(repl, 'g'), v)
			}
			return t
		}else {
			console.log('App.t: The token ' + token + ' does not exists')
		}
		
		return token
	}*/
}

/* JS fixes */
if(!Object.keys) {
	Object.keys = function(obj) {
		var ret = []
		for(var i in obj)
			ret.push(i)
		return ret
	}
}

if(!Object.values) {
	Object.values = function(obj) {
		return Object.keys(obj).map(function(key) { return obj[key] })
	}
}



/* extend formatters */
rivets.formatters.bytesFormatter = App.bytesFormatter;



/* setup require according the config of the App */
var defaultConfig = {
	//baseUrl: './js/app',
	//urlArgs: "v=" +  App.commitID,
	paths: {
		jstemplate: '../..',
		tinyMCE: '../app/tinymce/tinymce',
	},

    shim: {
        tinyMCE: {
            exports: 'tinyMCE',
            init: function () {
                this.tinyMCE.DOM.events.domLoaded = true;
                return this.tinyMCE;
            }
        }
    }
}

if(true || App.isDevelopment) {
	//prevent caching
	defaultConfig.urlArgs = "v=" +  (new Date()).getTime()
}else {
	//activate caching
	defaultConfig.urlArgs = "v=" +  App.commitID
}

require.config(defaultConfig);

/* load controllers */
require(['rivets-std'], function() {
	//add locale settings to rivets-std
	/*rivets.stdlib.defaultDateFormat = globalDateFormat;
	rivets.stdlib.defaultTimeFormat = globalTimeFormat;
	rivets.stdlib.defaultDatetimeFormat = globalDateTimeFormat;
	rivets.stdlib.defaultTimezoneSeconds = globalTimezoneSeconds;
	*/
	App.loadControllersFromDOM();
});