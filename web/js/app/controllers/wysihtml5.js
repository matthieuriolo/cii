'use strict';
define(['js/bootstrap-wysihtml5.js'], function() {
	return function WysiwygConstructor(node) {

		var loc = null;//App.locale.code == 'en' ? null : (App.locale.code + '-' + App.locale.code.toUpperCase())
		var deps = []

		if(loc)
			deps.push('js/wysihtml5-locales/' + loc + '.js')


		require(deps, function() {
			$(node).wysihtml5({
				stylesheets: 'css/bootstrap-wysihtml5.css',
				//image: false,
				color: true,
				locale: loc,
			});
		})
	}
})