'use strict';
/*
Very similiar to NSArrayController of cocoa

A controller for a sorted list of objects. Selection and sorting will be handled through the controller
*/


define([App.getModuleName('Sorting')], function(Sorting) {
	return function CollectionConstructor(data) {
		data = $.isArray(data) ? data : []
		
		var self = this;
		
		this.items = []
		this.selectedItems = {}
		this.selection = undefined

		//this.selectedIdentifiers = []
		//this.selectedItem = null
		
		this.sortedOrder = true;
		this.sortedBy =  null;
		
		this.sortingFunction = {/*name: Sorting.natural,*/}
		
		this.preserveSelection = true
		this.selectInsertions = true
		this.singleSelection = false
		
		this.sortBy = function(sortby, order) {
			if(sortby == self.sortedBy) {
				if(order === undefined) {
					self.sortedOrder = !self.sortedOrder
				}
			}else {
				self.sortedBy = sortby
				if(order === undefined) {
					self.sortedOrder = true
				}
			}
			
			if(order !== undefined) {
				self.sortedOrder = order
			}
			
			self.resort()
		}
		
		this.resort = function() {
			if(self.sortedBy) {
				var sortfunc = self.sortingFunction[self.sortedBy]
				self.items.sort(Sorting.object('item', Sorting.object(self.sortedBy, sortfunc)))
			}
			
			if(!self.sortedOrder) self.items.reverse()
		}
		
		this.toggleSortedOrder = function() {
			self.sortedOrder = !self.sortedOrder;
			self.items.reverse();
		}
		
		
		
		var internCounter = 0
		this.CollectionItemConstructor = function(obj) {
			this.id = internCounter++;
			this.item = obj
			this.selected = false
		}
		
		this.removeItem = function(item) {
			self.deselectItem(item)
			var index = self.items.indexOf(item)
			if(index > -1) {
				self.items.splice(index,1)
				self.resort()
			}
		}
		
		this.addItem = function(item) {
			item = new self.CollectionItemConstructor(item)
			
			if(self.selectInsertions) {
				if(!self.preserveSelection) {
					self.deselectAll()
				}
				
				self.selectItem(item)
			}
			
			self.items.push(item)
			self.resort()
			return item
		}
		
		this.selectItem = function(item) {
			if(self.singleSelection)
				self.deselectAll()
			item.selected = true
			self.selectedItems[item.id] = item
			self.selection = item
		}
		
		this.deselectItem = function(item) {
			if(self.selectedItems.hasOwnProperty(item.id)) {
				self.selection = undefined

				item.selected = false
				delete self.selectedItems[item.id]
				//self.selectedIdentifiers = Object.keys(self.selectedItems)
			}
		}
		
		this.getSelectedItems = function() {
			return Object.values(self.selectedItems).map(function(item) { return item.item })
		}
		
		this.getSelectedItem = function() {
			var list = self.getSelectedItems()
			
			if(list.length) {
				return list[0];
			}
			
			return null;
		}
		
		this.deselectAll = function() {
			self.selection = undefined
			self.items.forEach(function(item) {self.deselectItem(item); })
		}

		this.selectAll = function() {
			self.items.forEach(function(item) {self.selectItem(item); })
			self.selection = self.items[self.items.length - 1]
		}
		
		
		this.setItems = function(items) {
			//items = items || []
			self.deselectAll()
			self.items = []
			items.forEach(function(item) { self.addItem(item) })
			self.resort()
		}
		
		this.setItems(data)
		//data.forEach(function(d) { self.addItem(d); })
	}
});