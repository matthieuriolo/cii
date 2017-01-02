'use strict';
define(['tinyMCE'], function(tinyMCE) {
	return function WysiwygConstructor(node) {
		var options = {};
		var opt = $(node).data('options');
		if(opt) {
			options = opt;
		}

		var defaultLang = $('html').prop('lang');
		if(defaultLang) {
			defaultLang = defaultLang.replace('-', '_');
			if(defaultLang == 'en_US') {
				defaultLang = false;
			}else if(defaultLang.indexOf('_') !== -1) {
				var tks = defaultLang.split('_')
				if(tks[0] == tks[1].toLowerCase()) {
					defaultLang = tks[0]
				}
			}
		}


		var initSubmit = false;
		var type = $(node).data('type');
		if(type == 'small') {
			type = {
				selector: App.getSelector(node),
				relative_urls: true,
				language: defaultLang,
				height : "200px",
				menubar: false,

				theme: 'modern',
				skin: 'lightnostatusbar',
				plugins: 'autolink lists link charmap print preview anchor searchreplace visualblocks code insertdatetime media  paste',
				toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent',
			}
		}else {
			type = {
				selector: App.getSelector(node),
				relative_urls: true,
				language: defaultLang,
				height : "400px",
				
				file_picker_callback: function(callback, value, meta) {
					if(!initSubmit) {
						initSubmit = true;
						$('#' + $(node).data('browser-image') + '_submit').click(function() {
							callback($('#' + $(node).data('browser-image') + '_field').val());
						});

						$('#' + $(node).data('browser-media') + '_submit').click(function() {
							callback($('#' + $(node).data('browser-media') + '_field').val());
						});

						$('#' + $(node).data('browser-file') + '_submit').click(function() {
							callback($('#' + $(node).data('browser-file') + '_field').val());
						})
					}

					if(meta.filetype == 'image') {
						$('#' + $(node).data('browser-image')).modal('show');
					}

					if(meta.filetype == 'media') {
						$('#' + $(node).data('browser-media')).modal('show');
					}

					if(meta.filetype == 'file') {
						$('#' + $(node).data('browser-file')).modal('show');
					}
				},
				theme: 'modern',
				skin: 'lightnostatusbar',
				plugins: 'advlist autolink lists link charmap print preview anchor searchreplace visualblocks code fullscreen insertdatetime media table contextmenu paste image',
				toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | image media file',
				
				file_picker_types: 'file image media',
			}
		}

		tinyMCE.baseURL = tinyMCE.baseURI.source.substr(0, tinyMCE.baseURI.source.length - 11) + 'tinymce/';
		tinyMCE.init($.extend(type, options));
	}
})