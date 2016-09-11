define([], function() {
		return function SubmitOnChangeConstructor(node) {
			$(node).change(function(evt) {
				var form = $(node).closest('form');
				form.submit();
			})
		}
	}
)