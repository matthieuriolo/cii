define([], function() {
		/*
		special thanks to ktamlyn
		http://stackoverflow.com/questions/6653556/jquery-javascript-function-to-clear-all-the-fields-of-a-form
		*/
		return function ResetFormConstructor(node) {
			var form = $(node).closest('form');
			$(node).click(function(evt) {
				//evt.preventDefault()
				
				form.find(':input').not(':button, :submit, :reset, :hidden, :checkbox, :radio').val('');
    			form.find(':checkbox, :radio').prop('checked', false);
			})
		}
	}
)