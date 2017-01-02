define([], function() {
		return function DataSelectConstructor(node) {
			var tr = $(node).find('tr')
			var id = $(node).closest('.modal').prop('id');
			
			tr.click(function(evt) {
				$("#" + id + "_submit").removeClass('disabled');
				$("#" + id + "_field").val($(this).data('value'));
				if(data = $(this).data('name')) {
					$("#" + id + "_name").val(data);
				}

				if(data = $(this).data('url')) {
					$("#" + id + "_url").val(data);
				}


				$(this).closest('table').find('tr').removeClass('selected');
				$(this).addClass('selected');
			})
		}
	}
)