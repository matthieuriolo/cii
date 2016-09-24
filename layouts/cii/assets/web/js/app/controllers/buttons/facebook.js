define([], function() {
		return function ButtonFacebookConstructor(node) {
			$(node).click(function() {
				App.openFBModal($(node).data('url'));
			})
		}
	}
)