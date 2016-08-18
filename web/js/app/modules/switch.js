'use strict';

/*
A simplified collection. It only allows single selection and calls a callback everytime the items changes

*/

define([
	App.getModuleName('collection')
	], function(Collection) {
	
	/*
	function callback(switch, newValue) {
		switch.selectedItems // oldValue
		
		return true // allow selection to change
	}
	*/
	return function SwitchConstructor(states, callback) {
		//$.extend(this, new Collection())
		Collection.call(this)
		var self = this;
		
		
		this.selectInsertions = false;
		this.singleSelection  = true;
		
		var oldSelectItem = this.selectItem
		this.selectItem = function(item) {
			if(callback(self, item)) {
				return oldSelectItem.call(self, item)
			}
		}
		
		states.forEach(function(item) { self.addItem(item); })
		this.selectItem(this.items[0])
	};
});