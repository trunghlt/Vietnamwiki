      
/***********************************************
* Ultimate Fade-In Slideshow (v1.51): © Dynamic Drive (http://www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit http://www.dynamicdrive.com/ for this script and 100s more.
***********************************************/
 
var fadeimages1=new Array()
//SET IMAGE PATHS. Extend or contract array as needed
fadeimages1[0]=["http://www.vietnamwiki.net/images/upload/medium/sapa_in_mist.jpg", "http://www.vietnamwiki.net/Sapa-Overview-I100", ""] //plain image syntax
fadeimages1[1]=["http://www.vietnamwiki.net/images/upload/medium/reisterrassen_sapa_vietnam.jpg", "http://www.vietnamwiki.net/Sapa-Overview-I100", ""] //image with link syntax
fadeimages1[2]=["http://www.vietnamwiki.net/images/upload/medium/viet_sapa_girls.jpg", "http://www.vietnamwiki.net/Sapa-Overview-I100", ""] //image with link and target syntax
 
var fadeimages2=new Array() //2nd array set example. Remove or add more sets as needed.
//SET IMAGE PATHS. Extend or contract array as needed
fadeimages2[0]=["http://www.vietnamwiki.net/images/upload/medium/HTV_9131.jpg", "index2.php?id=10", "http://www.vietnamwiki.net/Ha_Noi-Overview-I72"] //plain image syntax
fadeimages2[1]=["http://www.vietnamwiki.net/images/upload/medium/Hanoi_Temple_de_la_Litterature_3_1.jpg", "http://www.vietnamwiki.net/Ha_Noi-Overview-I72", ""] //image with link syntax
fadeimages2[2]=["http://www.vietnamwiki.net/images/upload/medium/681x454_2.jpg", "http://www.vietnamwiki.net/Ha_Noi-Overview-I72", ""] //image with link and target syntax

var fadeimages3=new Array() //2nd array set example. Remove or add more sets as needed.
//SET IMAGE PATHS. Extend or contract array as needed
fadeimages3[0]=["http://www.vietnamwiki.net/images/upload/medium/halong_bay.jpg", "http://www.vietnamwiki.net/Ha_Long-Overview-I62", ""] //plain image syntax
fadeimages3[1]=["http://www.vietnamwiki.net/images/upload/medium/cave4.jpg", "http://www.vietnamwiki.net/Ha_Long-Overview-I62", ""] //image with link syntax
fadeimages3[2]=["http://www.vietnamwiki.net/images/upload/medium/Hang_Trong.jpg", "http://www.vietnamwiki.net/Ha_Long-Overview-I62", ""] //image with link and target syntax

var fadeimages4=new Array() //2nd array set example. Remove or add more sets as needed.
//SET IMAGE PATHS. Extend or contract array as needed
fadeimages4[0]=["http://www.vietnamwiki.net/images/upload/medium/H8.jpg", "http://www.vietnamwiki.net/Hue-Overview-I97", ""] //plain image syntax
fadeimages4[1]=["http://www.vietnamwiki.net/images/upload/medium/Hue_2.jpg", "http://www.vietnamwiki.net/Hue-Overview-I97", ""] //image with link syntax
fadeimages4[2]=["http://www.vietnamwiki.net/images/upload/medium/Hue_6.jpg", "http://www.vietnamwiki.net/Hue-Overview-I97", ""] //image with link and target syntax

var fadeimages5=new Array() //2nd array set example. Remove or add more sets as needed.
//SET IMAGE PATHS. Extend or contract array as needed
fadeimages5[0]=["http://www.vietnamwiki.net/images/upload/medium/193345074_b0dd1d0796.jpg", "http://www.vietnamwiki.net/Hoi_An__My_Son-Overview-I56", ""] //plain image syntax
fadeimages5[1]=["http://www.vietnamwiki.net/images/upload/medium/8.jpg", "http://www.vietnamwiki.net/Hoi_An__My_Son-Overview-I56", ""] //image with link syntax
fadeimages5[2]=["http://www.vietnamwiki.net/images/upload/medium/HoiAnBynight2.jpg", "http://www.vietnamwiki.net/Hoi_An__My_Son-Overview-I56", ""] //image with link and target syntax
                
var fadeimages6=new Array() //2nd array set example. Remove or add more sets as needed.
//SET IMAGE PATHS. Extend or contract array as needed
fadeimages6[0]=["http://www.vietnamwiki.net/images/upload/medium/3_2.jpg", "http://www.vietnamwiki.net/Nha_Trang-Overview-I91", ""] //plain image syntax
fadeimages6[1]=["http://www.vietnamwiki.net/images/upload/medium/6_1.jpg", "http://www.vietnamwiki.net/Nha_Trang-Overview-I91", ""] //image with link syntax
fadeimages6[2]=["http://www.vietnamwiki.net/images/upload/medium/1_1_2.jpg", "http://www.vietnamwiki.net/Nha_Trang-Overview-I91", ""] //image with link and target syntax
              
var fadeimages7=new Array() //2nd array set example. Remove or add more sets as needed.
//SET IMAGE PATHS. Extend or contract array as needed
fadeimages7[0]=["http://www.vietnamwiki.net/images/upload/medium/7B05_mekong.jpg", "http://www.vietnamwiki.net/Mekong_delta-Overview-I117", ""] //plain image syntax
fadeimages7[1]=["http://www.vietnamwiki.net/images/upload/medium/a_1.jpg", "http://www.vietnamwiki.net/Mekong_delta-Overview-I117", ""] //image with link syntax
fadeimages7[2]=["http://www.vietnamwiki.net/images/upload/medium/23820664transportinMekongDelta.jpg", "http://www.vietnamwiki.net/Mekong_delta-Overview-I117", ""] //image with link and target syntax
            
                
var fadebgcolor="white"

////NO need to edit beyond here/////////////
 
var fadearray=new Array() //array to cache fadeshow instances
var fadeclear=new Array() //array to cache corresponding clearinterval pointers
 
var dom=(document.getElementById) //modern dom browsers
var iebrowser=document.all
 
function fadeshow(theimages, fadewidth, fadeheight, borderwidth, delay, pause, displayorder){
this.imagewidth = fadewidth
this.imageheight = fadeheight
this.pausecheck=pause
this.mouseovercheck=0
this.delay=delay
this.degree=10 //initial opacity degree (10%)
this.curimageindex=0
this.nextimageindex=1
fadearray[fadearray.length]=this
this.slideshowid=fadearray.length-1
this.canvasbase="canvas"+this.slideshowid
this.curcanvas=this.canvasbase+"_0"
if (typeof displayorder!="undefined")
theimages.sort(function() {return 0.5 - Math.random();}) //thanks to Mike (aka Mwinter) :)
this.theimages=theimages
this.imageborder=parseInt(borderwidth)
this.postimages=new Array() //preload images
for (p=0;p<theimages.length;p++){
this.postimages[p]=new Image()
this.postimages[p].src=theimages[p][0]
}
 
var fadewidth=fadewidth+this.imageborder*2
var fadeheight=fadeheight+this.imageborder*2
 
if (iebrowser&&dom||dom) //if IE5+ or modern browsers (ie: Firefox)
document.write('<div id="master'+this.slideshowid+'" style="position:relative;width:'+fadewidth+'px;height:'+fadeheight+'px;overflow:hidden;z-index:+1;"><div id="'+this.canvasbase+'_0" style="position:absolute;width:'+fadewidth+'px;height:'+fadeheight+'px;top:0;left:0;filter:progid:DXImageTransform.Microsoft.alpha(opacity=10);opacity:0.1;-moz-opacity:0.1;-khtml-opacity:0.1;background-color:'+fadebgcolor+'"></div><div id="'+this.canvasbase+'_1" style="position:absolute;width:'+fadewidth+'px;height:'+fadeheight+'px;top:0;left:0;filter:progid:DXImageTransform.Microsoft.alpha(opacity=10);opacity:0.1;-moz-opacity:0.1;-khtml-opacity:0.1;background-color:'+fadebgcolor+'"></div></div>')
else
document.write('<div><img class="ss_img" name="defaultslide'+this.slideshowid+'" src="'+this.postimages[0].src+'"></div>')
 
if (iebrowser&&dom||dom) //if IE5+ or modern browsers such as Firefox
this.startit()
else{
this.curimageindex++
setInterval("fadearray["+this.slideshowid+"].rotateimage()", this.delay)
}
}

function fadepic(obj){
if (obj.degree<100){
obj.degree+=10
if (obj.tempobj.filters&&obj.tempobj.filters[0]){
if (typeof obj.tempobj.filters[0].opacity=="number") //if IE6+
obj.tempobj.filters[0].opacity=obj.degree
else //else if IE5.5-
obj.tempobj.style.filter="alpha(opacity="+obj.degree+")"
}
else if (obj.tempobj.style.MozOpacity)
obj.tempobj.style.MozOpacity=obj.degree/101
else if (obj.tempobj.style.KhtmlOpacity)
obj.tempobj.style.KhtmlOpacity=obj.degree/100
else if (obj.tempobj.style.opacity&&!obj.tempobj.filters)
obj.tempobj.style.opacity=obj.degree/101
}
else{
clearInterval(fadeclear[obj.slideshowid])
obj.nextcanvas=(obj.curcanvas==obj.canvasbase+"_0")? obj.canvasbase+"_0" : obj.canvasbase+"_1"
obj.tempobj=iebrowser? iebrowser[obj.nextcanvas] : document.getElementById(obj.nextcanvas)
obj.populateslide(obj.tempobj, obj.nextimageindex)
obj.nextimageindex=(obj.nextimageindex<obj.postimages.length-1)? obj.nextimageindex+1 : 0
setTimeout("fadearray["+obj.slideshowid+"].rotateimage()", obj.delay)
}
}
 
fadeshow.prototype.populateslide=function(picobj, picindex){
var slideHTML=""
if (this.theimages[picindex][1]!="") //if associated link exists for image
slideHTML='<a href="'+this.theimages[picindex][1]+'" target="'+this.theimages[picindex][2]+'">'
slideHTML+='<img class="ss_img" width="'+this.imagewidth+'px" height="'+this.imageheight+'px" src="'+this.postimages[picindex].src+'" border="'+this.imageborder+'px">'
if (this.theimages[picindex][1]!="") //if associated link exists for image
slideHTML+='</a>'
picobj.innerHTML=slideHTML
}
 
 
fadeshow.prototype.rotateimage=function(){
if (this.pausecheck==1) //if pause onMouseover enabled, cache object
var cacheobj=this
if (this.mouseovercheck==1)
setTimeout(function(){cacheobj.rotateimage()}, 100)
else if (iebrowser&&dom||dom){
this.resetit()
var crossobj=this.tempobj=iebrowser? iebrowser[this.curcanvas] : document.getElementById(this.curcanvas)
crossobj.style.zIndex++
fadeclear[this.slideshowid]=setInterval("fadepic(fadearray["+this.slideshowid+"])",50)
this.curcanvas=(this.curcanvas==this.canvasbase+"_0")? this.canvasbase+"_1" : this.canvasbase+"_0"
}
else{
var ns4imgobj=document.images['defaultslide'+this.slideshowid]
ns4imgobj.src=this.postimages[this.curimageindex].src
}
this.curimageindex=(this.curimageindex<this.postimages.length-1)? this.curimageindex+1 : 0
}
 
fadeshow.prototype.resetit=function(){
this.degree=10
var crossobj=iebrowser? iebrowser[this.curcanvas] : document.getElementById(this.curcanvas)
if (crossobj.filters&&crossobj.filters[0]){
if (typeof crossobj.filters[0].opacity=="number") //if IE6+
crossobj.filters(0).opacity=this.degree
else //else if IE5.5-
crossobj.style.filter="alpha(opacity="+this.degree+")"
}
else if (crossobj.style.MozOpacity)
crossobj.style.MozOpacity=this.degree/101
else if (crossobj.style.KhtmlOpacity)
crossobj.style.KhtmlOpacity=this.degree/100
else if (crossobj.style.opacity&&!crossobj.filters)
crossobj.style.opacity=this.degree/101
}
 
 
fadeshow.prototype.startit=function(){
var crossobj=iebrowser? iebrowser[this.curcanvas] : document.getElementById(this.curcanvas)
this.populateslide(crossobj, this.curimageindex)
if (this.pausecheck==1){ //IF SLIDESHOW SHOULD PAUSE ONMOUSEOVER
var cacheobj=this
var crossobjcontainer=iebrowser? iebrowser["master"+this.slideshowid] : document.getElementById("master"+this.slideshowid)
crossobjcontainer.onmouseover=function(){cacheobj.mouseovercheck=1}
crossobjcontainer.onmouseout=function(){cacheobj.mouseovercheck=0}
}
this.rotateimage()
}

