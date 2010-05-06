// Fotonotes DHTML Client (c) 2004-2005 Angus Turnbull http://www.twinhelix.com
// Developed under license to FotoNotes LLC
// Released under the Open Source License v2.1 or later.

// Modification 2005.11.17 - add loading scripts - Greg

// See the bottom of this file for configuration.


// *** FNCLIENT CONFIGURATION, VARIABLES AND SETUP ***


// Address of fotonoter.php on the server (this auto-detect should work):
var fnServerPath = "../";
var fnServerFotonotesScript = "fotonotes.php";
var fnServer = fnServerPath + fnServerFotonotesScript;

// XMLHTTPRequest object to communicate with server.
var fnXMLHTTP = null;
if (window.ActiveXObject)
{
 try
 {
  fnXMLHTTP = new ActiveXObject('Microsoft.XMLHTTP');
 }
 catch (e) { }
}
else if (window.XMLHttpRequest)
{
 fnXMLHTTP = new XMLHttpRequest();
}

// Permissions (respect previous settings):
// Allowed values are 'allow', 'prompt', 'deny'.
if (!window.FN_ADD)    var FN_ADD = 'allow';
if (!window.FN_MODIFY) var FN_MODIFY = 'allow';
if (!window.FN_DELETE) var FN_DELETE = 'allow';

// Internationalisation:
var FN_CREDITS = 'Fotonotes DHTML Viewer\n\n' +
 '(c) 2004-2005 Angus Turnbull, http://www.twinhelix.com\n\n' +
 'Provided under license to Fotonotes LLC';
var FN_DISALLOWED = 'Sorry, that action is not permitted.\n\n' +
 'Please login under a different account.';
var FN_POST_UNSUPPORTED = 'Sorry, your browser does not support editing notes.';
var FN_DELETE_CONFIRM = 'Are you sure you want to delete this note?';
var FN_SAVE_WAIT = 'Loading Fotonotes...';
var FN_SAVE_FAIL = 'An error occurred, and your changes could not be saved.';
var FN_SAVE_FAIL_JPEG_NOT_WRITABLE = "JPEG file is not writable. Please check file permissions on server.";
var FN_SAVE_SUCCESS = 'Changes saved!';

// Other global variables:
var fnDebugMode = false;    // Set to true to show XML sent/received.
var fnHideTimer = null;     // Hide notes after timeout.
var fnActiveNote = null;    // Currently visible note.
var fnActionVerb = '';      // Control bar's current action.
var fnActionTrigger = null; // Control bar's lit item.
var fnEditingData = null;   // Data store during note editing process.
var fnAnnotateAll =  false;	// Indicate annotation should be applied to all images
var fnMinImgWidth = 200;	// MinWidth to make to apply to fn-image
var fnMinImgHeight = 150;	// MinHeight to make to apply to fn-image
var imageFileSrc = "src";	// Use 'id' for findImage() to use imgObj.id; use "src" (default) for findImage to use imgOb.src


// *** Common API Code ***

var aeOL = [];
function addEvent(o, n, f, l)
{
 var a = 'addEventListener', h = 'on'+n, b = '', s = '';
 if (o[a] && !l) return o[a](n, f, false);
 o._c |= 0;
 if (o[h])
 {
  b = '_f' + o._c++;
  o[b] = o[h];
 }
 s = '_f' + o._c++;
 o[s] = f;
 o[h] = function(e)
 {
  e = e || window.event;
  var r = true;
  if (b) r = o[b](e) != false && r;
  r = o[s](e) != false && r;
  return r;
 };
 aeOL[aeOL.length] = { o: o, h: h };
};
addEvent(window, 'unload', function() {
 for (var i = 0; i < aeOL.length; i++) with (aeOL[i])
 {
  o[h] = null;
  for (var c = 0; o['_f' + c]; c++) o['_f' + c] = null;
 }
});

function cancelEvent(e, c)
{
 e.returnValue = false;
 if (e.preventDefault) e.preventDefault();
 if (c)
 {
  e.cancelBubble = true;
  if (e.stopPropagation) e.stopPropagation();
 }
};


// *** FNCLIENT LOAD DIVS ***
// The following functions run after page loaded and retrieve Fotonotes data into the document to show annotations.

addLoadEvent(findImage);

function addLoadEvent(func) {
  var oldonload = window.onload;
  if (typeof window.onload != 'function') {
    window.onload = func;
  } else {
    window.onload = function() {
      oldonload();
      func();
    }
  }
}



function findImage() {
	for (i=0;i < document.images.length; i++) {
		if (fnDebugMode) alert('img '+document.images[i].className);
		if ( (document.images[i].className == "fn-image") || ( (fnAnnotateAll) && (document.images[i].width >= fnMinImgWidth) && (document.images[i].height >= fnMinImgHeight)) ) {	
			var imgObj = document.images[i];
			
			// get path to image.
			if (fnDebugMode) alert("imgObj.src: "+imgObj.src);
			if (imageFileSrc == "id") {
				var imageFile = imgObj.id;
			} else {
				var imageFile = imgObj.src;
			}
			if (fnDebugMode) alert('revised imageFile: \n\n' + imageFile);
			
			if (imgObj.parentNode.tagName == "A") {
				
				var currentLinkNode = imgObj.parentNode;
				var newNode = document.createElement('div');
				//newNode.innerHTML = "replacement newNode";
				imgObj.parentNode.parentNode.replaceChild(newNode, imgObj.parentNode);
				newNode.appendChild(imgObj);
				
				newLinkNode = document.createElement('div');
				newLinkNode.className = "fn-view-image-link";
				currentLinkNode.innerHTML = "View image";
				var pathToImage = unescape(currentLinkNode.pathname);
				var temp = pathToImage.split('blank');
				if (window.ActiveXObject) {
					currentLinkNode.href = temp[1]; //IE quirk
				} else {
					currentLinkNode.href = temp[0];
				}					
				newLinkNode.appendChild(currentLinkNode);
				newNode.appendChild(newLinkNode);
			
				/*
				ImgElement.parentNode.parentNode.replaceChild(newNode, ImgElement.parentNode);
				Y = document.createElement('div');
				*/
			}
			
			createFNImage(imgObj, imageFile);
		}
	}	
}	

function createFNImage(imgObj, imageFile) {
	getFNDiv(imgObj, imageFile);
}

function getFNDiv(imgObj, imageFile) {
	// Sends some XML off to the server to get FNClient for image calls fnGetClientComplete on completion.
	//imageFile = imgObj.src; // deprecated assigment
	if (fnDebugMode) alert('Final imageFile: \n\n' + imageFile);
 	if (!imageFile) return alert(FN_SAVE_FAIL);
 	// Compose our post content and send it.
 	var postContent = 'image=' + escape(imageFile) + '&action=' + 'display' + '&width=' + imgObj.width + '&height=' + imgObj.height + '&alt=' + imgObj.alt + '&style=';// + imgObj.style;
 	if (fnDebugMode) alert('TARGET SERVER URL: \n\n' + fnServer);
 	if (fnDebugMode) alert('SENDING TO tlnServer:\n\n' + postContent);
/*	fnXMLHTTP.open('POST', fnServer, false); // use "false" to stop script from proceeding until data received.
	
	fnXMLHTTP.setRequestHeader('Content-type', 'application/x-www-form-urlencoded; charset=utf-8');
	fnXMLHTTP.setRequestHeader('Content-length', postContent.length);
	var cookies = document.cookie.split(';');
	
	if (cookies != null) {
	 for (var c = 0; c < cookies.length; c++)
	 {
	 if (cookies[c].length > 0) fnXMLHTTP.setRequestHeader('Cookie', cookies[c]);
	 }
	}
*/

// XMLHTTPRequest object to communicate with server.
var fnObjXMLHTTP = null;
if (window.ActiveXObject)
{
 try
 {
  fnObjXMLHTTP = new ActiveXObject('Microsoft.XMLHTTP');
  //fnObjXMLHTTP = new ActiveXObject('Msxml2.XMLHTTP.4.0'); // Angus recommend this call, but 'Microsoft.XMLHTTP' seems to be working better.
 }
 catch (e) { }
}
else if (window.XMLHttpRequest)
{
 fnObjXMLHTTP = new XMLHttpRequest();
}

  fnObjXMLHTTP.open('POST', fnServer, true);
	fnObjXMLHTTP.setRequestHeader('Content-type', 'application/x-www-form-urlencoded; charset=utf-8');
	fnObjXMLHTTP.setRequestHeader('Content-length', postContent.length);
	var cookies2 = document.cookie.split(';');
	/* Commented out in December 2005 b/c of problems in some instances of IE
	for (var c = 0; c < cookies2.length; c++)
	{
	fnObjXMLHTTP.setRequestHeader('Cookie', cookies2[c]);
	}
	*/
	fnObjXMLHTTP.onreadystatechange = function()
	{
		if (fnObjXMLHTTP.readyState == 4) fnGetClientComplete(true,imgObj,fnObjXMLHTTP.responseText);
	};

	fnObjXMLHTTP.send(postContent);

}

function fnGetClientComplete(ok2,imgObj,responseText)
{
  // All successful actions: let the user know it's OK, and reset the control bar,
  // and clear the editing data store.
  fnModalDialog(FN_SAVE_WAIT);
 
  setTimeout('fnModalDialog("")', 500);
  if (fnDebugMode) alert('RECEIVED FROM FNSERVER:\n\n' + responseText);
// Called once the server responds post-Save operation. 'ok' indicates success.

	// EXTRACT returned HTML text from reply to update document
	re = /displayHTML##([\w\W\n\r]*)##/;
	//alert ('re' + re);
	//alert ('test? ' + re.test(fnXMLHTTP.responseText));
	matches = re.exec(responseText);
	//fnDiv = fnXMLHTTP.responseText;
	fnDiv = matches[1];  // first matche pattern
	fnDivElement = document.createElement('div');
	fnDivElement.innerHTML = fnDiv;
	imgObj.parentNode.insertBefore(fnDivElement,imgObj);
	imgObj.parentNode.removeChild(imgObj);
}


// *** Drag and Resize Library Code ***
// (c) 2005 Angus Turnbull http://www.twinhelix.come


function DragResize(myName, config)
{
 var props = {
  myName: myName,                  // Name of the object.
  enabled: true,                   // Global toggle of drag/resize.
  handles: ['tl', 'tm', 'tr',
   'ml', 'mr', 'bl', 'bm', 'br'], // Array of drag handles: top/mid/.
  isElement: null,                 // Function ref to test for an element.
  isHandle: null,                  // Function ref to test for move handle.
  element: null,                   // The currently selected element.
  dragging: null,                  // Active handle reference of the element.
  minWidth: 10, minHeight: 10,     // Minimum pixel size of elements.
  minLeft: 0, maxRight: 9999,      // Bounding box area.
  minTop: 0, maxBottom: 9999,
  zIndex: 1,                       // The highest Z-Index yet allocated.
  mouseX: 0, mouseY: 0,            // Current mouse position, recorded live.
  lastMouseX: 0, lastMouseY: 0,    // Last processed mouse positions.
  mOffX: 0, mOffY: 0,              // A known offset between position & mouse.
  elmX: 0, elmY: 0,                // Element position.
  elmW: 0, elmH: 0,                // Element size.
  allowBlur: true,                 // Whether to allow automatic blur onclick.
  ondragfocus: null,               // Event handler functions.
  ondragstart: null,
  ondragmove: null,
  ondragend: null,
  ondragblur: null
 };

 for (var p in props)
 {
  this[p] = (typeof config[p] == 'undefined') ? props[p] : config[p];
 }
};


DragResize.prototype.apply = function(node)
{
 // Adds object event handlers to the specified DOM node.

 var obj = this;
 addEvent(node, 'mousedown', function(e) { obj.mouseDown(e) } );
 addEvent(node, 'mousemove', function(e) { obj.mouseMove(e) } );
 addEvent(node, 'mouseup', function(e) { obj.mouseUp(e) } );
};


DragResize.prototype.handleSet = function(elm, show) { with (this)
{
 // Either creates, shows or hides the resize handles within an element.

 // If we're showing them, and no handles have been created, create 4 new ones.
 if (!elm._handle_tr)
 {
  for (var h = 0; h < handles.length; h++)
  {
   // Create 4 news divs, assign each a generic + specific class.
   var hDiv = document.createElement('div');
   hDiv.className = myName + ' ' +  myName + '-' + handles[h];
   elm['_handle_' + handles[h]] = elm.appendChild(hDiv);
  }
 }

 // We now have handles. Find them all and show/hide.
 for (var h = 0; h < handles.length; h++)
 {
  elm['_handle_' + handles[h]].style.visibility = show ? 'inherit' : 'hidden';
 }
}};


DragResize.prototype.select = function(newElement) { with (this)
{
 // Selects an element for dragging.

 if (!document.getElementById || !enabled) return;

 // Activate and record our new dragging element.
 if (newElement && (newElement != element) && enabled)
 {
  element = newElement;
  // Elevate it and give it resize handles.
  element.style.zIndex = ++zIndex;
  handleSet(element, true);
  // Record element attributes for mouseMove().
  elmX = parseInt(element.style.left);
  elmY = parseInt(element.style.top);
  elmW = element.offsetWidth;
  elmH = element.offsetHeight;
  if (ondragfocus) this.ondragfocus();
 }
}};


DragResize.prototype.deselect = function(keepHandles) { with (this)
{
 // Immediately stops dragging an element. If 'keepHandles' is false, this
 // remove the handles from the element and clears the element flag,
 // completely resetting the .

 if (!document.getElementById || !enabled) return;

 if (!keepHandles)
 {
  if (ondragblur) this.ondragblur();
  handleSet(element, false);
  element = null;
 }

 dragging = null;
 mOffX = 0;
 mOffY = 0;
}};


DragResize.prototype.mouseDown = function(e) { with (this)
{
 // Suitable elements are selected for drag/resize on mousedown.
 // We also initialise the resize boxes, and drag parameters like mouse position etc.
 if (!document.getElementById || !enabled) return true;

 var elm = e.target || e.srcElement,
  newElement = null,
  newHandle = null,
  hRE = new RegExp(myName + '-([trmbl]{2})', '');

 while (elm)
 {
  // Loop up the DOM looking for matching elements. Remember one if found.
  if (elm.className)
  {
   if (!newHandle && (hRE.test(elm.className) || isHandle(elm))) newHandle = elm;
   if (isElement(elm)) { newElement = elm; break }
  }
  elm = elm.parentNode;
 }

 // If this isn't on the last dragged element, call deselect(false),
 // which will hide its handles and clear element.
 if (element && (element != newElement) && allowBlur) deselect(false);

 // If we have a new matching element, call select().
 if (newElement && (!element || (newElement == element)))
 {
  // Stop mouse selections.
  cancelEvent(e);
  select(newElement, newHandle);
  dragging = newHandle;
  if (dragging && ondragstart) this.ondragstart();
 }
}};


DragResize.prototype.mouseMove = function(e) { with (this)
{
 // This continually offsets the dragged element by the difference between the
 // last recorded mouse position (mouseX/Y) and the current mouse position.
 if (!document.getElementById || !enabled) return true;

 // We always record the current mouse position.
 mouseX = e.pageX || e.clientX + document.documentElement.scrollLeft;
 mouseY = e.pageY || e.clientY + document.documentElement.scrollTop;
 // Record the relative mouse movement, in case we're dragging.
 // Add any previously stored&ignored offset to the calculations.
 var diffX = mouseX - lastMouseX + mOffX;
 var diffY = mouseY - lastMouseY + mOffY;
 mOffX = mOffY = 0;
 // Update last processed mouse positions.
 lastMouseX = mouseX;
 lastMouseY = mouseY;

 // That's all we do if we're not dragging anything.
 if (!dragging) return true;

 // Establish which handle is being dragged -- retrieve handle name from className.
 var hClass = dragging && dragging.className &&
  dragging.className.match(new RegExp(myName + '-([tmblr]{2})')) ? RegExp.$1 : '';

 // If the hClass is one of the resize handles, resize one or two dimensions.
 // Bounds checking is the hard bit -- basically for each edge, check that the
 // element doesn't go under minimum size, and doesn't go beyond its boundary.
 var rs = 0, dY = diffY, dX = diffX;
 if (hClass.indexOf('t') >= 0)
 {
  rs = 1;
  if (elmH - dY < minHeight) mOffY = (dY - (diffY = elmH - minHeight));
  else if (elmY + dY < minTop) mOffY = (dY - (diffY = minTop - elmY));
  elmY += diffY;
  elmH -= diffY;
 }
 if (hClass.indexOf('b') >= 0)
 {
  rs = 1;
  if (elmH + dY < minHeight) mOffY = (dY - (diffY = minHeight - elmH));
  else if (elmY + elmH + dY > maxBottom) mOffY = (dY - (diffY = maxBottom - elmY - elmH));
  elmH += diffY;
 }
 if (hClass.indexOf('l') >= 0)
 {
  rs = 1;
  if (elmW - dX < minWidth) mOffX = (dX - (diffX = elmW - minWidth));
  else if (elmX + dX < minLeft) mOffX = (dX - (diffX = minLeft - elmX));
  elmX += diffX;
  elmW -= diffX;
 }
 if (hClass.indexOf('r') >= 0)
 {
  rs = 1;
  if (elmW + dX < minWidth) mOffX = (dX - (diffX = minWidth - elmW));
  else if (elmX + elmW + dX > maxRight) mOffX = (dX - (diffX = maxRight - elmX - elmW));
  elmW += diffX;
 }
 // If 'rs' isn't set, we must be dragging the whole element, so move that.
 if (dragging && !rs)
 {
  // Bounds check left-right...
  if (elmX + dX < minLeft) mOffX = (dX - (diffX = minLeft - elmX));
  else if (elmX + elmW + dX > maxRight) mOffX = (dX - (diffX = maxRight - elmX - elmW));
  // ...and up-down.
  if (elmY + dY < minTop) mOffY = (dY - (diffY = minTop - elmY));
  else if (elmY + elmH + dY > maxBottom) mOffY = (dY - (diffY = maxBottom - elmY - elmH));
  elmX += diffX;
  elmY += diffY;
 }

 // Assign new info back to the element, with minimum dimensions.
 with (element.style)
 {
  left =   elmX + 'px';
  width =  elmW + 'px';
  top =    elmY + 'px';
  height = elmH + 'px';
 }

 // Evil, dirty, hackish Opera select-as-you-drag fix.
 if (window.opera && document.documentElement)
 {
  var oDF = document.getElementById('op-drag-fix');
  if (!oDF)
  {
   var oDF = document.createElement('input');
   oDF.id = 'op-drag-fix';
   oDF.style.display = 'none';
   document.body.appendChild(oDF);
  }
  oDF.focus();
 }

 if (ondragmove) this.ondragmove();

 // Stop a normal drag event.
 cancelEvent(e);
}};


DragResize.prototype.mouseUp = function(e) { with (this)
{
 // On mouseup, stop dragging, but don't reset handler visibility.
 if (!document.getElementById || !enabled) return;

 if (ondragend) this.ondragend();
 deselect(true);
}};





// *** FNCLIENT CORE CODE ***

var _f_idcount = 1;
function fnElementFade(elm, show)
{
 // Fader function that shows/hides an element.
 var speed = show ? 20 : 10;
 elm._f_count |= 0;
 elm._f_timer |= null;
 clearTimeout(elm._f_timer);

 if (show && !elm._f_count) elm.style.visibility = 'inherit';

 elm._f_count = Math.max(0, Math.min(100, elm._f_count + speed*(show?1:-1)));

 var f = elm.filters, done = (elm._f_count==100);
 if (f)
 {
  if (!done && elm.style.filter.indexOf("alpha") == -1)
   elm.style.filter += ' alpha(opacity=' + elm._f_count + ')';
  else if (f.length && f.alpha) with (f.alpha)
  {
   if (done) enabled = false;
   else { opacity = elm._f_count; enabled=true }
  }
 }
 else elm.style.opacity = elm.style.MozOpacity = elm._f_count/100.1;

 if (!show && !elm._f_count) elm.style.visibility = 'hidden';

 if (elm._f_count % 100)
  elm._f_timer = setTimeout(function() { fnElementFade(elm,show) }, 50);
};




function fnClassSet(elm, active)
{
 // Utility function that toggles the "-active" and "-inactive" classnames.

 elm.className = elm.className.replace((active ? (/-inactive/) : (/-active/)),
  (active ? '-active' : '-inactive'));
};




function fnGetContainer(node)
{
 // When passed a DOM node, returns its parent "fn-container".

 var container = node;
 while (container)
 {
  if ((/fn-container/).test(container.className)) break;
  container = container.parentNode;
 }
 return container;
};



function fnGetControlBar(container)
{
 // When passed a container, returns the control bar within that container.

 var controlBar = null;
 for (var i = 0; i < container.childNodes.length; i++)
 {
  if ((/fn-controlbar/).test(container.childNodes.item(i).className))
  {
   controlBar = container.childNodes.item(i);
   break;
  }
 }
 return controlBar;
};




function fnContainerSet(container, active)
{
 // Sets the "activated" status of a note container area, and changes
 // the appropriate "toggle" item in its control bar.

 var controlBar = fnGetControlBar(container);
 for (var i = 0; i < controlBar.childNodes.length; i++)
 {
  if ((/fn-controlbar-toggle/).test(controlBar.childNodes.item(i).className))
  {
   fnClassSet(controlBar.childNodes.item(i), !active);
   break;
  }
 }

 fnClassSet(container, active);
};




function fnAction(action, trigger)
{
 // Called on click of control buttons to highlight/dim them.

 // Control the state of the trigger buttons, and set the global fnActionVerb variable.
 if (fnActionVerb != action)
 {
  // Set a new action, dim the old button.
  if (fnActionTrigger && fnActionVerb) fnClassSet(fnActionTrigger, false);
  fnActionVerb = action;
  fnActionTrigger = trigger;
  if (trigger) fnClassSet(trigger, true);
 }
 else
 {
  // Deactivate a trigger that is clicked twice.
  fnActionVerb = '';
  if (trigger) fnClassSet(trigger, false);
 }
};




function fnMouseOverOutHandler(evt, isOver)
{
 // Called on document.onmouseover & onmouseout, manages tip visibility.

 var node = evt.target || evt.srcElement;
 if (node.nodeType != 1) node = node.parentNode;

 while (node && !((node.className||'').indexOf('fn-container') > -1))
 {
  // If the node has an CLASS of "fotonote-area", process it.
  // No mouseovers if fnActionVerb is set (i.e. editing/deleting/adding/etc).
  if (node && ((node.className||'').indexOf('fn-area') > -1) && !fnActionVerb)
  {
   var area = node;
   // Find the first child element, which will be the note in question.
   var note = area.firstChild;
   while (note && note.nodeType != 1) note = note.nextSibling;
   if (!note) return;

   // Clear any hide timeout, and either show the note, or set a timeout for its hide.
   // We record the currently active note for the hide timer to work, and also elevate
   // its parent area above any previously active area (which is lowered).
   clearTimeout(fnHideTimer);
   if (isOver)
   {
    if (fnActiveNote && (note != fnActiveNote)) fnElementFade(fnActiveNote, false);
    fnElementFade(note, true);
	if (fnActiveNote) fnActiveNote.parentNode.style.zIndex = 1;
	note.parentNode.style.zIndex = 2;
    fnActiveNote = note;
   }
   else
   {
    fnHideTimer = setTimeout('if (fnActiveNote) { ' +
     'fnElementFade(fnActiveNote, false); fnActiveNote = null }', 200);
   }
  }

  // Loop up the DOM.
  node = node.parentNode;
 }
};




function fnClickHandler(evt)
{
 // Processes clicks on the document, performs the correct action.
 var node = evt.target || evt.srcElement;
 if (node.nodeType != 1) node = node.parentNode;
 while (node && !((node.className||'').indexOf('fn-container') > -1))
 {

  // Check buttons within the Edit bar.
  if ((/fn-editbar-ok/).test(node.className)) return fnEditButtonHandler(true);
  if ((/fn-editbar-cancel/).test(node.className)) return fnEditButtonHandler(false);

  // Perform no other if we're currently editing a note.
  if (fnEditingData) return;

  // If an existing area with a CLASS of the form "fn-area"
  // has been clicked, check if we're editing/deleting it.
  if ((/fn-area/).test(node.className))
  {
   var area = node;
   if (fnActionVerb == 'del') fnDelNote(area);
   if (fnActionVerb == 'edit')
   {
    var note = area.firstChild;
    while (note && note.nodeType != 1) note = note.nextSibling;
    if (note) fnEditNote(note);
   }
   return;
  }

  // Buttons on/within the Control bar.
  if ((/fn-controlbar-logo/).test(node.className))
  {
   // Logo click toggles control bar, if we're not editing a note.
   var isActive = ((/fn-controlbar-active/).test(node.parentNode.className));
   fnClassSet(node.parentNode, !isActive);
   return;
  }
  if ((/fn-controlbar-credits/).test(node.className))
  {
   alert(FN_CREDITS);
   return;
  }
  if ((/fn-controlbar-del/).test(node.className))
  {
   if (!fnXMLHTTP) return alert(FN_POST_UNSUPPORTED);
   if (FN_DELETE == 'deny') return alert(FN_DISALLOWED);
   return fnAction('del', node);
  }
  if ((/fn-controlbar-edit/).test(node.className))
  {
   if (!fnXMLHTTP) return alert(FN_POST_UNSUPPORTED);
   if (FN_MODIFY == 'deny') return alert(FN_DISALLOWED);
   return fnAction('edit', node);
  }
  if ((/fn-controlbar-add/).test(node.className))
  {
   if (!fnXMLHTTP) return alert(FN_POST_UNSUPPORTED);
   if (FN_ADD == 'deny') return alert(FN_DISALLOWED);
   return fnAddNote(node);
  }
  if ((/fn-controlbar-toggle/).test(node.className))
  {
   // Find the parent container, and toggle its classname to show/hide notes.
   var container = fnGetContainer(node);
   if (container)
   {
    var isActive = ((/fn-container-active/).test(container.className));
    fnContainerSet(container, !isActive);
   }
  }

  // Otherwise, loop up the hierarchy.
  node = node.parentNode;
 }
};




function fnEditUISet(show)
{
 // Either shows or hides the editing UI.

 if (!fnEditingData) return;
 with (fnEditingData)
 {
  // Start or stop dragging the selected area.
  if (show) dragresize.select(area, area);
  else dragresize.deselect();
  // Set area className so its remains visible if editing, or reset it back otherwise.
  area.className = show ? 'fn-area-editing' : 'fn-area';
  // Fade the editing UI in/out, and toggle its classname so it stays that way.
  fnElementFade(form, show);
  fnClassSet(form, show);
  // Toggle the container class and control bar (for other notes' visibility)
  fnContainerSet(container, !show);
  fnClassSet(fnGetControlBar(container), !show);  
 }
};




function fnAddNote(node)
{
 // Adds a new note when the specified button is clicked.

 // Find the parent container of this node.
 var container = fnGetContainer(node);
 if (!container) return;

 // Highlight the "Add" button.
 fnAction('add', node);

 // Create a new area in which the note will reside.
 var newArea = document.createElement('div');
 newArea.className = 'fn-area';
 newArea.style.left = (container.offsetWidth/2 - 25) + 'px';
 newArea.style.top  = (container.offsetHeight/2 - 25) + 'px';
 newArea.style.width = '50px';
 newArea.style.height = '50px';
 newArea.id = 'fn-area-new';

 var newNote = document.createElement('div');
 newNote.className = 'fn-note';
 newArea.appendChild(newNote);

 // Create note elements.
 var newTitle = document.createElement('span');
 newTitle.className = 'fn-note-title';
 newNote.appendChild(newTitle);

 var newContent = document.createElement('span');
 newContent.className = 'fn-note-content';
 newNote.appendChild(newContent);

 var newAuthor = document.createElement('span');
 newAuthor.className = 'fn-note-author';
 newNote.appendChild(newAuthor);

 var newUserid = document.createElement('span');
 newUserid.className = 'fn-note-userid';
 newNote.appendChild(newUserid);

 var newID = document.createElement('span');
 newID.className = 'fn-note-id';
 newID.title = '';
 newArea.appendChild(newID);
 
 // add in innerborders
 var newInnerBorder = document.createElement('div');
 newInnerBorder.className = 'fn-area-innerborder-right';
 newArea.appendChild(newInnerBorder);
 
 var newInnerBorder = document.createElement('div');
 newInnerBorder.className = 'fn-area-innerborder-left';
 newArea.appendChild(newInnerBorder);
 
 var newInnerBorder = document.createElement('div');
 newInnerBorder.className = 'fn-area-innerborder-top';
 newArea.appendChild(newInnerBorder);
 
 var newInnerBorder = document.createElement('div');
 newInnerBorder.className = 'fn-area-innerborder-bottom';
 newArea.appendChild(newInnerBorder);

 // Add newArea to document
 container.appendChild(newArea);

 // Record this note as editing, and set the "add" action flag.
 fnEditingData = {
  area: newArea,
  note: newNote
 };

 // Hand over to the editing function.
 fnEditNote();
};




function fnEditNote(note)
{
 // Edits a passed note reference.

 var area = null;
 if (note)
 {
  // If we're editing an existing note, setup the data store.
  area = note.parentNode;
  fnEditingData = {
   area: area,
   note: note
  };
 }
 else
 {
  // New notes: pull the note and area out of the stored data.
  area = fnEditingData.area;
  note = fnEditingData.note;
 }

 // Find our container and form references.
 var container = fnGetContainer(area);
 if (!container) return;
 var form = container.getElementsByTagName('form');
 if (!form) return;
 form = form.item(0);
  
 // Pick up existing values for content from the note.
 var oldTitle = '', oldAuthor = '', oldContent = '', noteID = '';
 var fields = area.getElementsByTagName('span');
 for (var n = 0; n < fields.length; n++)
 {
  var field = fields.item(n);
  if (field.className == 'fn-note-id') noteID = field.getAttribute('title');
  if (field.className == 'fn-note-title') oldTitle = field.innerHTML;
  if (field.className == 'fn-note-author') oldAuthor = field.innerHTML;
  if (field.className == 'fn-note-content') oldContent = field.innerHTML;
 }

 // Backup the original content, refs and position in our datastore.
 // It already has the .note and .area properties.
 // And yes, I know innerHTML isn't standard, but it's SO MUCH EASIER here!
 fnEditingData.container = container;
 fnEditingData.form = form;
 fnEditingData.noteID = noteID;
 fnEditingData.oldTitle = oldTitle;
 fnEditingData.oldAuthor = oldAuthor;
 fnEditingData.oldContent = oldContent;
 fnEditingData.oldLeft = parseInt(area.style.left);
 fnEditingData.oldTop = parseInt(area.style.top);
 fnEditingData.oldWidth = area.offsetWidth;
 fnEditingData.oldHeight = area.offsetHeight;
 // Some values for the post-editing callback handler to populate.
 fnEditingData.newTitle = fnEditingData.newAuthor = fnEditingData.newContent = '';
 fnEditingData.newLeft = fnEditingData.newTop = 0;
 fnEditingData.newWidth = fnEditingData.newHeight = 0;

 // Populate the editing UI with its current content.
 var inputs = form.getElementsByTagName('input');
 for (var i = 0; i < inputs.length; i++)
 {
  if ((/title/).test(inputs[i].className)) inputs[i].value = oldTitle;
  if ((/author/).test(inputs[i].className)) inputs[i].value = oldAuthor;
 }
 var textarea = form.getElementsByTagName('textarea');
 if (textarea && (/content/).test(textarea.item(0).className))
  textarea.item(0).value = oldContent;

 // Finally, show the editing UI for the recorded area.
 fnEditUISet(true);
};




function fnEscapeHTML(html)
{
 // Returns a properly escaped HTML string.

 return html.replace('&', '&amp;').replace('<', '&lt;').replace('>', '&gt;');
};




function fnEditButtonHandler(ok)
{
 // Button click handler from the editing UI.
 // Pass a boolean value indicating if the OK button was clicked (so save should proceed).

 if (!fnEditingData) return;
 with (fnEditingData)
 {
  if (ok)
  {
   // Populate fnEditingData.new* from the edit form fields and area attributes.
   // SET default value for all params.
   newTitle = newAuthor = newUserid = newEntryid = newContent = newBorderColor = '';
    var inputs = form.getElementsByTagName('input');
   for (var i = 0; i < inputs.length; i++)
   {
    if ((/title/).test(inputs[i].className)) {newTitle = inputs[i].value;} //else {newTitle = '';}
    if ((/author/).test(inputs[i].className)) {newAuthor = inputs[i].value;} //else {newAuthor = '';}
    if ((/userid/).test(inputs[i].className)) {newUserid = inputs[i].value;} //else {newUserid = '';}
    if ((/entry_id/).test(inputs[i].className)) {newEntryid = inputs[i].value;} //else {newEntryid = '';}
    if ((/border_color/).test(inputs[i].className)) {newBorderColor = inputs[i].value;} //else {newEntryid = '';}
   }
   var textarea = form.getElementsByTagName('textarea');
   if (textarea && (/content/).test(textarea.item(0).className)) {newContent = textarea.item(0).value};
   newLeft = parseInt(area.style.left);
   newTop = parseInt(area.style.top);
   newWidth = area.offsetWidth;
   newHeight = area.offsetHeight;
   	
	if (fnDebugMode) alert('Begin server save operation ' + 'newBorderColor: ' + newBorderColor);
  
  // Get the scalefactor from a hidden SPAN in the container.
   var sFact = 1;
   for (var n = 0; n < container.childNodes.length; n++)
   {
    if ((/fn-scalefactor/).test(container.childNodes.item(n).className))
	 sFact = parseFloat(container.childNodes.item(n).getAttribute('title'));
   }
  
   // Begin server save operation.
   /* Bordercolor UI elements have been removed fn div elements. See fnclient-0.4.0.bordercolor for elements.*/
   fnPostXML(
    '<?xml version="1.0" encoding="UTF-8"?>' +
    '<feed><entry>' +
     (fnActionVerb == 'edit' ? '<id>' + noteID + '</id>' : '') +
     '<fn:selection><fn:boundingBox>' +
	  parseInt(newLeft/sFact) + ',' + parseInt(newTop/sFact) + ',' +
      parseInt((newLeft+newWidth)/sFact) + ',' + parseInt((newTop+newHeight)/sFact) +
     '</fn:boundingBox></fn:selection>' +
     '<title>' + fnEscapeHTML(newTitle) + '</title>' +
     '<author><name>' + fnEscapeHTML(newAuthor) + '</name><userid>' + fnEscapeHTML(newUserid) + '</userid></author>' +
     '<content>' + fnEscapeHTML(newContent) + '</content>' + '<entry_id>' + fnEscapeHTML(newEntryid) + '</entry_id>' +
    '</entry></feed>'
   );

  }
  else
  {
   // For "cancel" clicks:

   if (fnActionVerb == 'add')
   {
    // Just delete new notes.
	area.parentNode.removeChild(area);
   }
   else
   {
    // Restore original note area position/size for edited notes.
    area.style.left = oldLeft + 'px';
    area.style.top = oldTop + 'px';
    area.style.width = oldWidth + 'px';
    area.style.height = oldHeight + 'px';
   }

   // Hide the editing UI, reset the control bar, clear the data store.
   fnEditUISet(false);
   fnAction('', null);
   fnEditingData = null;
  }
 }

};




function fnDelNote(area)
{
 // Deletes a note area -- passed a whole area reference.

 // Find the ID of this note.
 var noteID = '', fields = area.getElementsByTagName('span');
 for (var n = 0; n < fields.length; n++)
  if (fields.item(n).className == 'fn-note-id')
   noteID = fields.item(n).getAttribute('title');
 if (!noteID) alert(FN_SAVE_FAIL);

 if (noteID && confirm(FN_DELETE_CONFIRM))
 {
  // Set up our data store to delete this area, and post to the server.
  fnEditingData = {
   area: area,
   note: null,
   container: fnGetContainer(area)
  };
  fnPostXML(
   '<?xml version="1.0" encoding="UTF-8"?>' +
   '<feed><entry>' +
    '<id>' + noteID + '</id>' +
   '</entry></feed>'
  );
 }
 else
 {
  // Reset control bar if cancelled.
  fnAction('', null);
 }

};




function fnModalDialog(message)
{
 // Shows or hides the browser-wide modal dialog.
 // Pass a message to show, or an empty string to hide the dialog.

 var dialog = document.getElementById('fn-modaldialog');
 if (!dialog)
 {
  dialog = document.createElement('div');
  dialog.setAttribute('id', 'fn-modaldialog');
  document.body.appendChild(dialog);
 }

 /*
 // Different approach for IE/Windows, since it doesn't support position: fixed.
 dialog.style.position = (window.ActiveXObject ? 'absolute' : 'fixed');
 dialog.style.zIndex = '100000';
 dialog.style.top = (window.activeXObject ?
  document.documentElement.scrollTop+(document.documentElement.clientHeight/2) + 'px' : '0');
 dialog.style.left = '0';
 dialog.style.width = '100%';
 dialog.style.height = (window.ActiveXObject ?
  document.documentElement.scrollHeight : '100%');
 dialog._setupDone = true;
*/
 dialog.innerHTML = '<span>' + message + '</span>';
 dialog.style.visibility = message ? 'visible' : 'hidden';
};




function fnPostXML(xml)
{
 // Sends some XML off to the server and calls fnEditComplete on completion.

 // Hopefully my auto-detect-fu powers are strong. I'll use the Crouching Regex Style.
 var image = fnEditingData.container.getElementsByTagName('img').item(0);
 var imageFile = image.getAttribute('src');
 if (!imageFile) return alert(FN_SAVE_FAIL);

 // Figure out if we need to prompt the user for a password.
 var password = '', password_req = false;
 switch (fnActionVerb)
 {
  case 'add':  { if (FN_ADD == 'prompt')    password_req = true; break };
  case 'edit': { if (FN_MODIFY == 'prompt') password_req = true; break };
  case 'del':  { if (FN_DELETE == 'prompt') password_req = true; break };
 }
 if (password_req)
 {
  // TODO: Secure input here.
  password = prompt('Please enter your password', '');
 }

 // Compose our post content and send it.
 var actVerbs = { add: 'add', edit: 'modify', del: 'delete' };
 var postContent = 'image=' + escape(imageFile) + '&action=' + actVerbs[fnActionVerb] +
  (password ? '&password=' + escape(password) : '') +
  '&xml=' + escape(xml);

 if (fnDebugMode) alert('SENDING TO FNSERVER:\n\n' + postContent);

 fnXMLHTTP.open('POST', fnServer, true);
 fnXMLHTTP.setRequestHeader('Content-type', 'application/x-www-form-urlencoded; charset=utf-8');
 fnXMLHTTP.setRequestHeader('Content-length', postContent.length);
 var cookies = document.cookie.split(';');
 /*
 for (var c = 0; c < cookies.length; c++)
 {
  fnXMLHTTP.setRequestHeader('Cookie', cookies[c]);
 }
 */
 fnXMLHTTP.onreadystatechange = function()
 {
  if (fnXMLHTTP.readyState == 4) fnEditComplete(true);
 };

 // Show "please wait" modal dialog, which prevents document clicks, and send.
 fnModalDialog(FN_SAVE_WAIT);
 fnXMLHTTP.send(postContent);
};




function fnEditComplete(ok)
{
 // Called once the server responds post-Save operation. 'ok' indicates success.

 if (fnDebugMode) alert('RECEIVED FROM FNSERVER:\n\n' + fnXMLHTTP.responseText);

 if (!ok || !fnXMLHTTP.responseText.match('success=ok'))
 {
  // In the case of a communication error, hide the modal dialog and alert the user.
  fnModalDialog('');
  if (fnXMLHTTP.responseText.match('success=501')) {
  	// File is not writable
  	alert(FN_SAVE_FAIL_JPEG_NOT_WRITABLE);
  } else {
  	// Some other error occurred.
  	alert(FN_SAVE_FAIL);
  }
  // Failed deletes: reset the control bar and clear the data store.
  // (Failed edits/adds: UI and data persist).
  if (fnActionVerb == 'del')
  {
   fnEditingData = null;
   fnAction('', null);
  }
 }
 else with (fnEditingData)
 {
  // Depending on our action, commit the changes to the document.
  if (fnActionVerb == 'add' || fnActionVerb == 'edit')
  {
   // Place new values in the note. It's already in the right position.
   for (var n = 0; n < note.childNodes.length; n++)
   {
    var field = note.childNodes.item(n);
    if (field.className == 'fn-note-title') field.innerHTML = newTitle;
    if (field.className == 'fn-note-author') field.innerHTML = newAuthor;
    if (field.className == 'fn-note-content') field.innerHTML = newContent;
   }
   // Hide the editing UI.
   fnEditUISet(false);
  }
  else
  {
   // Deleting notes? Just remove the area from the document.
   area.parentNode.removeChild(area);
  }

  // All successful actions: let the user know it's OK, and reset the control bar,
  // and clear the editing data store.
  fnModalDialog(FN_SAVE_SUCCESS);
  setTimeout('fnModalDialog("")', 500);
  fnAction('', null);
  fnEditingData = null;
 }
 // Reload the page - Added temporarily
 // window.location.reload();
};






// INITIALISATION CODE:
if (document.getElementById)
{
 // Create a new DragResize() object, and set it up.
 // We apply to the whole document to interoperate with blinds.
 var dragresize = new DragResize('dragresize', { allowBlur: false });
 dragresize.isElement = function(elm)
 {
  if (!(/(add|edit)/).test(fnActionVerb)) return false;
  if ((/fn-area-editing/).test(elm.className))
  {
   var container = fnGetContainer(elm);
   this.maxRight = container.offsetWidth - 2;
   this.maxBottom = container.offsetHeight - 2;
   return true;
  }
 };
 dragresize.isHandle = function(elm)
 {
  if (!(/(add|edit)/).test(fnActionVerb)) return false;
  if ((/fn-area-editing/).test(elm.className)) return true;
 };
 dragresize.ondragfocus = function()
 {
  this.element.style.cursor = 'move';
 };
 dragresize.ondragblur = function()
 {
  this.element.style.cursor = 'default';
 };
 dragresize.apply(document);


 // *** Global event handler setup ***
 // These are global, rather than assigned to individual notes, to work with the "blind" code.

 // Note show/hide events.
 addEvent(document, 'mouseover', new Function('e', 'fnMouseOverOutHandler(e, 1)'));
 addEvent(document, 'mouseout', new Function('e', 'fnMouseOverOutHandler(e, 0)'));
 // Creation/editing/deletion events.
 if (document.createElement && document.documentElement)
 {
  //addEvent(document, 'mousedown', fnMouseDownHandler);
  //addEvent(document, 'mouseup', fnMouseUpHandler);
  addEvent(document, 'click', fnClickHandler);
 }
}




