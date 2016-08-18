define([], function() {
		return function StrengthCheckConstructor(node) {
			var $e = $(node)
			var tests = [{
				message: 'At least 8 characters',
				check: function(input) {
					return input.length >= 8
				}
			},{
				message: 'Small letter',
				check: function(input) {
					return /[a-z]/.test(input)
				}
			},{
				message: 'Capital letter',
				check: function(input) {
					return /[A-Z]/.test(input)
				}
			},{
				message: 'Digit',
				check: function(input) {
					return  /[0-9]/.test(input)
				}
			},{
				message: 'Non alphanumeric',
				check: function(input) {
					return  /[^A-Za-z0-9]/.test(input)
				}
			},]
			
			var rebuildContent = function() {
				var ret = ''
				var allValid = true
				tests.forEach(function(test) {
					ret += '<div class="row">'
					ret += '<div class="col-md-2">'
					
					if(test.check($e.val())) {
						ret += '<span class="glyphicon glyphicon-ok text-success"></span>'
					}else {
						allValid = false
						ret += '<span class="glyphicon glyphicon-remove text-danger"></span>'
					}
					ret += '</div>'
					
					ret += '<div class="col-md-10">'
					ret += test.message
					ret += '</div>'
					ret += '</div>'
				})

				if(allValid) {
					ret += '<hr><div class="row"><div class="col-md-12 text-center" style="font-size: 200%;"><span class="glyphicon glyphicon-thumbs-up text-success"></span></div></div>'
				}else {
					ret += '<hr><div class="row"><div class="col-md-12 text-center" style="font-size: 200%;"><span class="glyphicon glyphicon-thumbs-down text-danger"></span></div></div>'
				}
				return ret
			}
			
			var pos = $e.data('position') ? $e.data('position') : 'top';
			
			$e.focus(function() {
				$e.popover({
					title: 'Password strength',
					content: rebuildContent(),
					placement: pos,
					html: true,
					trigger: 'manual',
				}).popover('show')
				
			}).blur(function() {
				$e.popover('destroy')
			}).keyup(function() {
				$e.data('bs.popover').tip().find(".popover-content").html(rebuildContent())
			})
		}
	}
)