README - FNCLIENT 0.6.0

======================================================
FNClient Image Annotation Software
http://www.fotonotes.net

(c) FotoNotes, LLC. All Rights Reserved.
FNServer, FotoNoter, FotoNotes, and fotonotesplugin.php are 
open source and available under the Open Software License
v 2.1 (http://www.opensource.org/licenses/osl-2.1.php). 
FotoNotes is patent-pending.

You may implement the FotoNotes specification in your
own code an applications royalty-free in exchange for 
attribution under the Creative Commons by Attribution 
License. See http://fotonotes.net/spec/index.cgi?AttributionFormats 
for more information, including commercial licensing options.

README v0.2.6 (07.01.2006)
======================================================

~TABLE OF CONTENT~
======================================================
1. GETTING HELP
2. LIMITATIONS
3. WHAT'S NEW
4. WHAT'S WHAT
5. INSTALLATION
6. CONFIGURATION AND PERMISSIONS
7. EXAMPLES


1. ~GETTING HELP~
======================================================
As of 2006, please direct all general support to
the FotoNotes Hacker List at Yahoo Groups. Please join
the list/group, post your question to the email list. (You can
unsubscribe after receiving assistance, if you want.)

URL: http://groups.yahoo.com/group/fotonotes/
Subscribe: send email to fotonotes-subscribe@yahoogroups.com

If you need special help, you can contact me directly, but using
the email list is preferable. Email: greg@fotonotes.net


2. ~LIMITATIONS~
======================================================
Limitations of this release: 
- Images must be on same server as fotonotes.php script.
- Annotations can only be saved in JPEG header. Stub code for saving 
	annotation in other locations exists but is not working.
- Documentation is limited.

Known bugs of this release:
- New annotations can not be deleted until the page is refreshed.
	Page must be reloaded before deleting a new annotation because 
	client script does not currently receive new annotation ID back 
	from server script.
	

3. ~WHAT'S NEW~
======================================================
fnclient-0.6.0
- All test pages have been moved inside new "docs" directory.
- fnclient.js has preference setting to make all imags of a certain size 
	in a web page annotate-able.
- Linked images (e.g., <a href="..."><img src="..." class="fn-image"></a> 
	can now be annotated. The href link is moved below the image.
- A configuration setting has been added to fnclient.js to allow the image file path to
	be set by the "id" attribute of the <img> tag instead of the "src" attribute.
	This is useful in cases with the src is pointed to a script that generates a 
	scaled version of the image. Most likely, you will need to alter the page
	generation scripts to include the proper path to the image in the "id" attribute.

fnclient-0.5.0
- All classes have been located in separate files in the fnclientlib folder. 
	Previously, all classes were defined in fotonotes.php script making it rather long.
- The control buttons have been relocated to the top of the image. New graphics for buttons.
- An fn-canvas style has been define that wraps around fn-container.

As of 11.29.2005 the FotoNotes displays and adds annotations for multiple images on a page
and creates annotations in images that do not have annotations.

4. ~WHAT'S WHAT~
======================================================
This distribution of FotoNotes is an early adopters version. The below information
is provided to help the brave find their way around the code.

docs/ - Documents directory.
	
fn_config.php - Configuration settings.
fotonotes.php - The server side script that communicates with fnclient/js/fnclient.js 
				and reads/edits/stores/deletes

fnclientlib/ - Class, js, style files.
fnclientlib/library.<class_name>.inc.php - Separate files for each class.
fnclientlib/library.fnuser.inc.php  - Class file for user identification originally from FNServer software. 
									It is included for compatibility and may be re-integrated for session 
									management and user identification.

fnclientlib/js/fnclient.js - Communicates with the server and create/edit/delete iteractivity

fnclientlib/styles/fnclient.css - Styles for fotonotes; integral part of the application.
	

5. ~INSTALLATION~
======================================================
Just drop the folder into a web directory of your choosing. Everything should work.

Examine "http://yourserver.com/pathto/fnclient/docs/test_fnclient.html". 
Open this page in your browser via your web server and everything should work. 
If you have a problem adding annotations, first check the image files in the docs/test_images folder
are writeable by the web server. This is a common problem.


6. ~CONFIGURATION AND PERMISSIONS~
======================================================
Most configuration is done in the fn_config.php script. Most of the settings should
be self-explanatory.

A few settings are inside the fnclientlib/js/fnclient.js script near the top, below the comment:

// *** FNCLIENT CONFIGURATION, VARIABLES AND SETUP ***

Notable Javascript variables:
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


7. ~EXAMPLES~
======================================================
The following files are included to help explain how FotoNotes work. 

docs/test_dog.html - A static html page showing the annotation divs the FNClient reads. 

docs/test_fnclient.html - A static html page that includes an image classed as "fn-image". This page
demonstrates how fnclient.js automatically communicates with the server and modifies the document in 
real time to display the image's annotations. You can do multiple images on the same page.



(end)