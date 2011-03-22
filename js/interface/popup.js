// <script>

// Copyright (C) 2005 Ilya S. Lyubinskiy. All rights reserved.
// Technical support: http://www.php-development.ru/
//
// YOU MAY NOT
// (1) Remove or modify this copyright notice.
// (2) Distribute this code, any part or any modified version of it.
//     Instead, you can link to the homepage of this code:
//     http://www.php-development.ru/javascripts/dropdown.php.
//
// YOU MAY
// (1) Use this code on your website.
// (2) Use this code as a part of another product provided that
//     this code will be a small part of this product.
//
// NO WARRANTY
// This code is provided "as is" without warranty of any kind, either
// expressed or implied, including, but not limited to, the implied warranties
// of merchantability and fitness for a particular purpose. You expressly
// acknowledge and agree that use of this code is at your own risk.


// USAGE
//
// function popup_show(id, drag_id, exit_id, position, x, y, position_id)
//
// id          - id of a popup window;
// drag_id     - id of an element within popup window intended for dragging it
// exit_id     - id of an element within popup window intended for hiding it
// position    - positioning type:
//               "screen-corner", "screen-center"
//               "mouse-corner" , "mouse-center"
//               "element-right", "element-bottom"
// x, y        - offset
// position_id - for the last two types of positioning popup window will be
//               positioned relative to this element


// ----- Variables -------------------------------------------------------------

var popup_dragging = false;
var popup_target;
var popup_mouseX;
var popup_mouseY;
var popup_mouseposX;
var popup_mouseposY;
var popup_oldfunction;

function popup_display(x)
{
  var win = window.open();
  for (var i in x) win.document.write(i+' = '+x[i]+'<br>');
}

// ----- popup_mousedown -------------------------------------------------------

function popup_mousedown(e)
{
  var ie = navigator.appName == "Microsoft Internet Explorer";

  if ( ie && window.event.button != 1) return;
  if (!ie && e.button            != 0) return;

  popup_dragging = true;
  popup_target   = this['target'];
  popup_mouseX   = ie ? window.event.clientX : e.clientX;
  popup_mouseY   = ie ? window.event.clientY : e.clientY;

  if (ie)
       popup_oldfunction      = document.onselectstart;
  else popup_oldfunction      = document.onmousedown;

  if (ie)
       document.onselectstart = new Function("return false;");
  else document.onmousedown   = new Function("return false;");
}

// ----- popup_mousemove -------------------------------------------------------

function popup_mousemove(e)
{
  if (!popup_dragging) return;

  var ie      = navigator.appName == "Microsoft Internet Explorer";
  var element = document.getElementById(popup_target);

  var mouseX = ie ? window.event.clientX : e.clientX;
  var mouseY = ie ? window.event.clientY : e.clientY;

  element.style.left = (element.offsetLeft+mouseX-popup_mouseX)+'px';
  element.style.top  = (element.offsetTop +mouseY-popup_mouseY)+'px';

  popup_mouseX = ie ? window.event.clientX : e.clientX;
  popup_mouseY = ie ? window.event.clientY : e.clientY;
}

// ----- popup_mouseup ---------------------------------------------------------

function popup_mouseup(e)
{
  if (!popup_dragging) return;
  popup_dragging = false;

  var ie      = navigator.appName == "Microsoft Internet Explorer";
  var element = document.getElementById(popup_target);
  
  if (ie)
       document.onselectstart = popup_oldfunction;
  else document.onmousedown   = popup_oldfunction;
}

// ----- popup_exit ------------------------------------------------------------

function popup_exit(e)
{
  var ie      = navigator.appName == "Microsoft Internet Explorer";
  var element = document.getElementById(popup_target);

  popup_mouseup(e);
  element.style.visibility = 'hidden';
  element.style.display    = 'none';
  x_set_uneditting();
  
  //set uneditting if popup is compose_popup

}

function popup_exit_click() {
}


// ----- popup_show ------------------------------------------------------------

function popup_show(id, drag_id, exit_id, position, x, y, position_id)
{
  element      = document.getElementById(id);
  drag_element = document.getElementById(drag_id);
  exit_element = document.getElementById(exit_id);

  element.style.position   = "absolute";
  element.style.visibility = "visible";
  element.style.display    = "block";
 
   if (position == "long-form")
  {
    element.style.left = '200px';
    element.style.top  = '50px';
  }
  
  if (position == "top-center")
  {
	element.style.left = (document.body.clientWidth -element.clientWidth )/2 + 'px';
	element.style.top = '200px';
  }


  if (position == "screen-corner")
  {
    element.style.left = (document.documentElement.scrollLeft+x)+'px';
    element.style.top  = (document.documentElement.scrollTop +y)+'px';
  }

  if (position == "screen-center")
  {
    element.style.left = (document.documentElement.scrollLeft+(document.body.clientWidth -element.clientWidth )/2+x)+'px';
    element.style.top  = (document.documentElement.scrollTop +(document.body.clientHeight-element.clientHeight)/2+y)+'px';
  }

  if (position == "mouse-corner")
  {
    element.style.left = (document.documentElement.scrollLeft+popup_mouseposX+x)+'px';
    element.style.top  = (document.documentElement.scrollTop +popup_mouseposY+y)+'px';
  }

  if (position == "mouse-center")
  {
    element.style.left = (document.documentElement.scrollLeft+popup_mouseposX-element.clientWidth /2+x)+'px';
    element.style.top  = (document.documentElement.scrollTop +popup_mouseposY-element.clientHeight/2+y)+'px';
  }

  if (position == "element-right" || position == "element-bottom" || position == "element-left")
  {
    var position_element = document.getElementById(position_id);

    for (var p = position_element; p; p = p.offsetParent)
      if (p.style.position != 'absolute')
      {
        x += p.offsetLeft;
        y += p.offsetTop ;
      }

    if (position == "element-right" ) x += position_element.clientWidth;
    if (position == "element-bottom") y += position_element.clientHeight;
	if (position == "element-left"){
		x = x - element.clientWidth;
		y = y - element.clientHeight;
	}
      
    if (x < 10) x = 10;
    if (y < 10) y = 10;
    element.style.left = x+'px';
    element.style.top  = y+'px';
  }

  drag_element['target']   = id;
  drag_element.onmousedown = popup_mousedown;

  exit_element.onclick     = popup_exit;
}

function popup_appear(id) {
element      = document.getElementById(id);
  element.style.position   = "absolute";
  element.style.visibility = "visible";
  element.style.display    = "block";
  element.style.left = (document.documentElement.scrollLeft+(document.body.clientWidth -element.clientWidth )/2+10)+'px';
  element.style.top  = (document.documentElement.scrollTop +(document.body.clientHeight-element.clientHeight)/2+10)+'px';
 }
  

function popup_disappear(id) {
  element      = document.getElementById(id);
  element.style.position   = "absolute";
  element.style.visibility = "hidden";
  element.style.display    = "none";

}

// ----- popup_mousepos --------------------------------------------------------

function popup_mousepos(e)
{
  var ie = navigator.appName == "Microsoft Internet Explorer";

  popup_mouseposX = ie ? window.event.clientX : e.clientX;
  popup_mouseposY = ie ? window.event.clientY : e.clientY;
}

// ----- Attach Events ---------------------------------------------------------
if (navigator.appName == "Microsoft Internet Explorer")
     document.attachEvent('onmousedown', popup_mousepos);
else document.addEventListener('mousedown', popup_mousepos, false);

if (navigator.appName == "Microsoft Internet Explorer")
     document.attachEvent('onmousemove', popup_mousemove);
else document.addEventListener('mousemove', popup_mousemove, false);

if (navigator.appName == "Microsoft Internet Explorer")
     document.attachEvent('onmouseup', popup_mouseup);
else document.addEventListener('mouseup', popup_mouseup, false);
