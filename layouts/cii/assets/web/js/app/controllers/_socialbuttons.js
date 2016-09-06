define([], function() {
		return function SocialButtonsConstructor(node) {
			$(node).find('i.fa-google-plus-square').click(function() {
				App.openGPModal();
			})
			$(node).find('i.fa-facebook-square').click(function() {
				App.openFBModal();
			})
			$(node).find('i.fa-twitter-square').click(function() {
				App.openTWModal();
			})
		}
	}
)