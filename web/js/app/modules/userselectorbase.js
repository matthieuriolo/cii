define([
	App.getModuleName('PaginatedCollection'),
	App.getTranslationName('Browser'),
	], function(PaginatedCollection, transl) {
		return function UserSelectorBaseConstructor(callback) {
			var self = this
			var coll = new PaginatedCollection()
			coll.singleSelection = true
			
			
			coll.url = App.getApiUrl('user/selectorlist')
			coll.method = 'POST'
			coll.columns = [
				{
					name: App.t(transl, 'label.username'),
					sortname: 'name',
					format: function(a) { return a.item.name; }
				},
				
				{
					name: App.t(transl, 'label.email'),
					sortname: 'email',
					format: function(a) { return a.item.email || '-'; }
				}
			]


			this.excludeUser = null
			this.onlyGroupAdmins = null
			this.excludeGroupMembers = null
			this.excludeCompanyMembers = null
			

			var searchText = ''

			
			var oldLoad = coll.load
			coll.load = function() {
				if(self.excludeUser) {
					coll.parameters.excludeUser = self.excludeUser
				}else {
					delete coll.parameters.excludeUser
				}

				if(self.onlyGroupAdmins) {
					coll.parameters.onlyGroupAdmins = self.onlyGroupAdmins
				}else {
					delete coll.parameters.onlyGroupAdmins;
				}

				if(self.excludeGroupMembers) {
					coll.parameters.excludeGroupMembers = self.excludeGroupMembers
				}else {
					delete coll.parameters.excludeGroupMembers
				}

				if(self.excludeCompanyMembers) {
					coll.parameters.excludeCompanyMembers = self.excludeCompanyMembers
				}else {
					delete coll.parameters.excludeCompanyMembers
				}

				if(searchText) {
					coll.parameters.search = searchText
				}else {
					delete coll.parameters.search;
				}

				oldLoad.call(this)
			}

			var _searchFormHandler = function(evt) {
				var field = $(evt.target).closest('form').find('#searchfield')
				
				var searchTxt = jQuery.trim(field.val())
				var errors = false
				if(searchTxt.length < 3) {
					errors = true
					field.closest('.input-group-xsm').addClass('error-nomsg')
				}else {
					field.closest('.input-group-xsm').removeClass('error-nomsg')
				}

				if(!errors) {
					searchText = searchTxt
					coll.currentPage = 1
					coll.load()
				}
			}

			this.openModal = function() {
				coll.load()
				App.confirmModal({
					title: App.t(transl, 'title.select_user'),
					collection: coll,
					okText: App.t(transl, 'label.chose'),
					searchText: searchText,
					okHandler: function() {
						var item = coll.getSelectedItem()
						
						callback(item, self)
					},

					clearFormHandler: function(evt) {
						$(evt.target).closest('form').clearForm()
						var field = $(evt.target).closest('form').find('#searchfield')
						field.closest('.input-group-xsm').removeClass('error-nomsg')
						searchText = ''
						coll.currentPage = 1
						coll.load()
					},

					searchFormHandler: _searchFormHandler
				}, 'selectormodal')
			}
		}
	}
)