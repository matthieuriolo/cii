'use strict';

define([App.getModuleName('Collection')], function(Collection) {
	return function AjaxCollectionConstructor(data) {
		var self = this;
		Collection.call(this, data);
		
		this.url = '';
		this.parameters = {};
		this.method = 'GET'
		
		this.load = function() {
			var func = (self.method == 'POST' ? App.postJSON : App.getJSON)
			var params = self.parameters
			
			func(self.url, params).done(function(data) {
				self.items = data.map(function(d) { return new self.CollectionItemConstructor(d) })
				//if(!self.sortedOrder) self.items.reverse()
			})
		}
		
		this.resort = function() {
			self.load()
		}
	}
});