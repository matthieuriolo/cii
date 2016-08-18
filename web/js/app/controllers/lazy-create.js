define([], function() {
		return function LazyCreateConstructor(node) {
			var url = $(node).data('url');
			var tabSystem = $(node).parents('.body-content')
			var tabUl = tabSystem.find('.nav-tabs')
			var tabContent = tabSystem.find('.tab-content')
			var startCount = tabContent.find('>div.tab-pane').length;
			
			if($(node).is("[data-tabs]")) {
				startCount = $(node).data('tabs');
			}

			var cleanAddedTabs = function() {
				var count = tabContent.find('>div.tab-pane').length - 1;
				
				for(var i = count; i >= startCount; i--) {
					var id = '#' + tabUl.prop('id') + '-tab' + i;
					$(id).remove()
					tabUl.find('a[href="'+id+'"]').parent().remove()
				}
			}

			$(node).change(function() {
				var selection = $(this).val();


				App.postJSON(url, {'class': selection}).done(function(data) {
					cleanAddedTabs();
					tabUl.append('<li><a href="#' + tabUl.prop('id') + '-tab' + startCount + '" data-toggle="tab">' + data.label + '</a></li>')
					tabContent.append('<div id="' + tabUl.prop('id') + '-tab' + startCount + '" class="tab-pane">' + data.content + '</div>')
				}).fail(function() {
					cleanAddedTabs()
				})
			})
		}
	}
)