define([], function() {
		return function TabbenPanelSelector(node) {
			var nav = $(node).closest('.panel').find('.panel-body > .nav');
			$(node).change(function(evt) {

				nav.find('li:eq(' + $(node).prop('selectedIndex') + ') a').tab('show')
			})
		}
	}
)