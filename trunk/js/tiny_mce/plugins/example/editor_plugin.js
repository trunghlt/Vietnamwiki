(function(){tinymce.create('tinymce.plugins.Img_urlPlugin',{init:function(ed,url){ed.addCommand('mceImg_url',function(){ed.windowManager.open({file:url+'/dialog.php',width:1240,height:900,inline:1},{plugin_url:url,})});ed.addButton('img_url',{title:'img_url.desc',cmd:'mceImg_url',image:url+'/img/img_url.gif'})},getInfo:function(){return{longname:'Img_url plugin',author:'Hieu',version:"1.0"}}});tinymce.PluginManager.add('img_url',tinymce.plugins.Img_urlPlugin)})();