define([], function() {
		return function ButtonGoogleConstructor(node) {
			
			$(node).click(function() {
				App.openGPModal($(node).data('url'));
			})
		}
	}
)