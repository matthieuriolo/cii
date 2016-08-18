'use strict';

define([App.getModuleName('AjaxCollection')], function(AjaxCollection) {
	return function PaginatedCollectionConstructor(data) {
		var self = this;
		
		AjaxCollection.call(this)
		
		this.isLoading = true
		this.countTotal = 0
		this.countPage = 0
		this.currentPage = 1
		this.sizePage = 5
		
		this.canSelectPrevious = false
		this.canSelectNext = false

		this.pages = []


		this.selectNext = function() {
			if(self.currentPage+1<self.countPage) {
				self.currentPage++;
				self.load()
			}
		}

		this.selectPrevious = function() {
			if(self.currentPage > 1) {
				self.currentPage--;
				self.load()
			}
		}

		this.selectPageNum = function(num) {
			if(num>=1 && num<=self.countPage) {
				self.currentPage = num
				self.load()
			}
		}

		this.load = function() {
			self.isLoading = true
			var func = (self.method == 'POST' ? App.postJSON : App.getJSON)
			var params = self.parameters
			
			if(self.sortedBy) {
				params.sortedBy = self.sortedBy
				params.sortedOrder = self.sortedOrder
			}

			params.currentPage = self.currentPage
			params.sizePage = self.sizePage
			
			func(self.url, params).done(function(data) {
				self.selectedItems = {}
				
				self.countTotal = data.countTotal
				self.countPage = Math.ceil(data.countTotal / self.sizePage) + 1
				self.items = data.items.map(function(d) { return new self.CollectionItemConstructor(d) })

				self.pages = []
				for(var i = Math.max(self.currentPage - 2, 1); i < Math.min(self.currentPage + 5, self.countPage); i++) {
					self.pages.push({pageNum: i})
				}
				
				self.canSelectPrevious = self.currentPage > 1 ? true : false
				self.canSelectNext = self.currentPage + 1 < self.countPage ? true : false
				self.selection = undefined
				self.isLoading = false
			})
		}
	}
});