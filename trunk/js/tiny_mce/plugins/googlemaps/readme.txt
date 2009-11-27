Hi, this is a modified version of Cees Rijken "Googlemaps"-Plugin for Tinymce 3.x
I changed the complete interface. Also i added a few more options and a map with draggable marker. 

So what you need?

1. a google maps API-Key (http://code.google.com/apis/maps/signup.html)
2. tinymce 3.x
3. five minutes of your time



INSTALLATION
------------
1. First upload the complete content of the archive to your plugin-dir.
   folder need to be "googlemaps".



2. After you get your API-KEY. Open the "googlemaps.htm"-File and jump to line 8.

   Enter your API-KEY here.
   <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=false&amp;key=API-KEY" type="text/javascript"></script>
   
   Example:
   <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=false&amp;key=ABQIAAAAN34d6VtFch77JMvVwadLpRRL25fVr4Jq-FCya_V05n17fLm0ShRZMd5pFiaB02M4m6FPEd4cVJ3YtA" type="text/javascript"></script>



3. Same file on line 184:

   Enter your API-KEY as value:
   <div style="padding:4px"><input id="akey" name="akey" type="text" value="API-KEY" class="text" style="width:570px" /></div>

   Example:
  <div style="padding:4px"><input id="akey" name="akey" type="text" value="ABQIAAAAN34d6VtFch77JMvVwadLpRRL25fVr4Jq-FCya_V05n17fLm0ShRZMd5pFiaB02M4m6FPEd4cVJ3YtA" class="text" style="width:570px" /></div>



4. Save the files and overwrite it on your server.



5. Find your tinyMce init statement and add "googlemaps" to the "plugins" line and add "googlemaps, googlemapsdel" to one of your button lines (like "theme_advanced_buttons3").





USING THE PLUGIN
----------------
General:	- MAP
		  This is NOT a preview. You can choose here your location

		- Location -> Coordinates
		  The coordinates of the choosen location

		- Location -> Address
		  Here you can search for an location


Advanced:	- Map-Size -> Width
		  Width of the map (Pixel)
		
		- Map-Size -> Height
		  Height of the map (Pixel)

		- Map-Options -> Zoom-Level
		  Zoom of the map. The Zoom-Level is the same you choose on the map.
		  Here is a little bug in the script. The zoom-level refresh only after dragging
		  the marker on the map. Sorry of that (Just had two hours time for that plugin)

		- Map-Options -> Map-Style
		  The Style of the map. Standard is Normal Map. This option will not refresh the map on the 
		  general tab.

		- Map-Options -> Map-Controls
		  What controls do you want. Simple controls are the white square buttons, 3D the rounded buttons
		  with a little 3d-effect and no controls, well, no means no ;)

		- DIV ID -> ID
		  The id of the div-layer where your map is saved.

		- API-KEY
		  Your API-KEY. Well if you do all the installation steps correctly, you can see your API-KEY here.



TODO/ BUGS
----------
		- Ability to edit a created map
		- automaticaly refresh of zoom-level if changed on map without dragging the marker after
		- Adding routes, overlays and custom marker (with upload)
		- using a language-file, translation to other languages
		- moon and mars overlays and coordinates
		- adding more markers then one
		- adding html-markers where you can enter text or something else.



Remember this is just a modified version of Cees Rijken "Googlemaps"-Plugin. I just modified the plugin for a client 
and thought perhaps someone can use it too. So feel free to hate me if it not working :P
This is just a pre-version. You take all the risk if there is any damage on your documents, server, computer or anything else.

The Addon was test ONLY in Firefox3. If you use IE6 or another version of IE, sorry, my client hate IE so i haven't test it.



















OLD VERSION README.TXT:
_______________________


First of all, I am not much of a JS expert. But since JS syntax ressembles PHP's, I have managed
to whip up a very simple Google Maps plugin.

Second: this is a VERY VERY crude, elementary first draft. It works and was tested in IE7 and FF2.

Requirements
------------
What you need for Google maps to run on your website, is your very own API key. You can get one here:
http://code.google.com/apis/maps/signup.html 

Installing the plugin
---------------------
Download the plugin from here: http://www.connectcase.nl/bl.google.maps.plugin.tinymce.html
Make a plugins-folder called 'googlemaps'
Unzip all the files into that folder.

Find your tinyMce init statement and add "googlemaps" to the "plugins" line and
add "googlemaps, googlemapsdel" to one of your button lines (like "theme_advanced_buttons3").

This should create 2 buttons: one for adding the Google Map and one for deleting it.

Basics
------
The dialog for this plugin requires 5 variables:

1. API key (get one at http://code.google.com/apis/maps/signup.html)
2. coordinates for your own location (you can get them at http://www.connectcase.nl/bl.google.maps.plugin.tinymce.html)
3. width: how wide do you want the Google div to be? (if left blank --> 100px)
4. height: how high do you want the Google div to be? (if left blank --> 100px)
5. div ID: to refrain from applying a div ID that you already used in your page, you can enter your own here
(if left blank, the div is called "map")

The dialog then creates the necessary Javascript (2 <script> tags) and the div in which the maps is actually shown. 
This is all put in a span called "spangooglemaps".
Forth comes the "googlemapsdel"-button. If clicked, it removes this particular span from the DOM.

Removing the span "spangooglemaps" from the DOM is not the most brilliant solution, I know, but I couldn't figure out how to simply
select the span and then press the delete-button on your keyboard (suggestions are welcome!!)

All the Google Maps examples at Google.com have the Javascript run at the "body onLoad"-statement. Since I assume that most of you use
tinyMce in a CMS environment, in which you cannot access the body-tag, I added some extra Javascript that avoids the necessary js-methods 
to run via the body-tag. (Can't take credit for this nifty piece of code, I just nicked it somewhere on the Internet)

Some day, if this plugin ever loses some of its crudeness, but either development from my side or input from others, I will post it in the Sourceforge repository.
I know that's where it is supposed to go, but this first version is not a "professional" plugin yet, so let's wait a bit longer....

For now, you can download it here: http://www.connectcase.nl/bl.google.maps.plugin.tinymce.html
