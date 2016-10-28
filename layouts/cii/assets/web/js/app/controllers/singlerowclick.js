define([], function() {
		return function SingleRowClickConstructor(node) {
			var tr = $(node).find('tr')

			tr.click(function(evt) {

				if($(evt.target).is('a, button, a > span.glyphicon')) {
					return true;
				}

				//ignore it if it happen inside a popover
				if($(evt.target).closest('.popover').length) {
					return true;
				}

				var link = $(this).find('td:last a:first')
				if(link.length) {
					link = link[0]
					if(link.href) {
						$(link).click()
					}
				}
			})
		}
	}
)