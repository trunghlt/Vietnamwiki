<<<<<<< .mine
(function() {

	tinymce.create('tinymce.plugins.Link_urlPlugin', {

		init : function(ed, url) {

			ed.addCommand('mceLink_url', function() {
				ed.windowManager.open({
					file : url + '/dialog.php',
					width : 420, 
					height : 400, 
					inline : 1
				}, {
					plugin_url : url, // Plugin absolute URL
				});
			});

		ed.addButton('link_url', {
				title : 'link_url.desc',
				cmd : 'mceLink_url',
				image : url + '/img/link_url.gif'
			});
		},

		getInfo : function() {
			return {
				longname : 'Link_url plugin',
				author : 'Hieu',
				version : "1.0"
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('link_url', tinymce.plugins.Link_urlPlugin);
=======
(function() {

	tinymce.create('tinymce.plugins.Img_urlPlugin', {

		init : function(ed, url) {

			ed.addCommand('mceImg_url', function() {
				ed.windowManager.open({
					file : url + '/dialog.php',
					width : 1240, 
					height : 900, 
					inline : 1
				}, {
					plugin_url : url, // Plugin absolute URL
				});
			});

		ed.addButton('img_url', {
				title : 'img_url.desc',
				cmd : 'mceImg_url',
				image : url + '/img/img_url.gif'
			});
		},

		getInfo : function() {
			return {
				longname : 'Img_url plugin',
				author : 'Hieu',
				version : "1.0"
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('img_url', tinymce.plugins.Img_urlPlugin);
>>>>>>> .r106
})();