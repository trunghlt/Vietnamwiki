/**
 * $Id: editor_plugin_src.js 520 2008-01-07 16:30:32Z spocke $
 *
 * @author Cees Rijken | edit by Christian Ladewig
 * @copyright www.connectcase.nl | www.klmedien.de
 *
 * @version 2.0
 */

(function() {
	tinymce.PluginManager.requireLangPack('googlemaps');
	tinymce.create('tinymce.plugins.GooglemapsPlugin', {
		init : function(ed, url) {
			// Register commands
			ed.addCommand('mceGooglemap', function() {
				ed.windowManager.open({
					file : url + '/googlemaps.htm',
					width : 640 + parseInt(ed.getLang('googlemaps.delta_width', 0)),
					height : 480 + parseInt(ed.getLang('googlemaps.delta_height', 0)),
					inline : 1
				}, {
					plugin_url : url
				});
			});

			ed.addCommand('mceGooglemapDelete', function()
      {
      var gdoc = ed.getDoc();
      ed.dom.remove(gdoc.getElementById('spangooglemaps'));
			});

      	ed.addButton('googlemaps', {
				title : 'googlemaps.desc',
				cmd : 'mceGooglemap',
				image : url + '/img/map_add.gif'
			});
				ed.addButton('googlemapsdel', {
				title : 'googlemaps.deldesc',
				cmd : 'mceGooglemapDelete',
				image : url + '/img/map_delete.gif'
			});
			//ed.addButton('googlemaps', {title : 'googlemaps.desc', cmd : 'mceGooglemap'});
			//ed.addButton('googlemapsdel', {title : 'googlemaps.deldesc', cmd : 'mceGooglemapDelete'});

		},

		getInfo : function() {
			return {
				longname : 'Googlemaps',
				author : 'Christian Ladewig (Original by: Cees Rijken <http://www.connectcase.nl>)',
				authorurl : 'http://www.klmedien.de',
				infourl : 'http://www.klmedien.de',
				version : tinymce.majorVersion + "." + tinymce.minorVersion
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('googlemaps', tinymce.plugins.GooglemapsPlugin);
})();