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
				title : 'link_url',
				cmd : 'mceLink_url',
				image : url + '/img/plus.gif'
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
	tinymce.PluginManager.add('link_url', tinymce.plugins.link_urlPlugin);
})();