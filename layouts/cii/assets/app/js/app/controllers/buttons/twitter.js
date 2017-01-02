define([], function() {
		return function ButtonTwitterConstructor(node) {
			$(node).click(function() {
				App.openTWModal($(node).data('url'));
			})
		}
	}
)