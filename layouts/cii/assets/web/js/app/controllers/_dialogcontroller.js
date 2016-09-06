define([
		App.getTemplName('dialogbase'),
		App.getTemplName('dialogfooter'),
		
	], function() {
	return function DialogController(tmpl, tmplfooter) {
		var self = this;
		
		this.setTitle = function(title) {
			$('.modal .modal-title').html(title)
		}
		
		this.setContent = function(cont) {
			$('.modal .modal-content').html(cont)
		}
		
		this.setFooter = function(footer) {
			$('.modal .modal-content').html(footer)
		}
		
		this.setIsLoading = function(isLoading) {
			var node = $('.modal')
			if(isLoading)
				node.addClass('is_loading')
			else
				node.removeClass('is_loading')
		}
		
		
		/* opens a normal dialog for html content */
		this.open = function() {
			if($('.modal')length == 0)
				$('body > *').first().insertBefore(tmpl)
			
			self.setIsLoading(true)
			self.setTitle('')
			self.setContent('')
			self.setFooter(tmplfooter)
			self.setIsLoading(false)
			
			$('.modal').open()
			//shown.bs.modal
		}
	}
});