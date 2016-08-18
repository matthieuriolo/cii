define([], function() {
		return function FilterNestedMenuConstructor(node) {
			var menu = $(node).closest('.list-group');
			var searchItem = $(node).closest('.list-group-item')[0];

			$(node).keyup(function(evt) {
				var searchTerm = $(node).val();
				if(evt.which == 13) {
					$(node).val('')
					searchTerm = ''
				}

				var items = $(menu).find('.list-group-item');
				
				
				if(searchTerm.length < 3) {
					items.removeClass('hidden');
					menu.removeClass('searching')
					return;
				}

				menu.addClass('searching')
				items.addClass('hidden');
				$(searchItem).removeClass('hidden');

				var links = $(menu).find('a.list-group-item');
				links.each(function() {
					if(
						searchItem != this
						&&
						!$(this).text().toLocaleLowerCase().includes(searchTerm.toLocaleLowerCase())
					) {
						$(this).addClass('hidden');
					}else {
						$(this).removeClass('matching');
						$(this).removeClass('hidden');
						$(this).parents('.list-group-indented').removeClass('hidden');
						$(this).parents('.list-group-indented').prev().removeClass('hidden');
					}
				})
			})

		}
	}
)