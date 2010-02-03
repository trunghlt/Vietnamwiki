(function() {

	tinymce.create('tinymce.plugins.PricePlugin', {

		init : function(ed, url) {

			ed.addCommand('mcePrice', function() {
				ed.windowManager.open({
					file : url + '/dialog.htm',
					width : 200 + parseInt(ed.getLang('price.delta_width', 0)), 
					height : 100 + parseInt(ed.getLang('price.delta_height', 0)), 
					inline : 1
				}, {
					plugin_url : url, // Plugin absolute URL
				});
			});

		ed.addButton('price', {
				title : 'price',
				cmd : 'mcePrice_desc',
				image : url + '/img/dollar.png'
			});
		
		ed.onNodeChange.add(function(ed, cm ,n) {
				cm.setActive('price', ed.dom.getAttrib(n,'class').indexOf('price')==-1);
			  	cm.setActive('price', ed.dom.getAttrib(n,'class').indexOf('price')!=-1);
				});

		},

		getInfo : function() {
			return {
				longname : 'Price plugin',
				author : 'Hieu',
				version : "1.0"
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('price', tinymce.plugins.PricePlugin);
})();