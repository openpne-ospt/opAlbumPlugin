<?php
/* FILE INFO */
/*
 * File: fotonotes.php
 * Author: Greg Elin
 * Updated: 07.1.2006
 * Description: Overall control file communicating with fnclient.js
 *
 */ 

/* INCLUDES 
--------------------------------------------- */
// include separate configuration file
require_once('fn_config.php');
// include function replacement, addition files 
// include default function files
require_once('fnclientlib/library.fnutil.inc.php');
require_once('fnclientlib/library.fn_makeimage.inc.php');
// include class replacement, addition files
// include default class files
require_once('fnclientlib/library.fnsave.inc.php');
require_once('fnclientlib/library.fnretrieve.inc.php');
require_once('fnclientlib/library.fnimage.inc.php');
require_once('fnclientlib/library.fnimageannotation.inc.php');
require_once('fnclientlib/library.fnuser.inc.php');
require_once("fnclientlib/library.fndb.inc.php");


/* CONFIGURATION
--------------------------------------------- */
// Complete URL to this script. Change only if 'autodetect' didn't work!
$LOCALPATH		=	'autodetect';

// This path is prepended to incoming paths that start with a slash!
// Leaving empty will resolve paths starting with / from the server root
$LOCALROOT		=	'';

// SET Version of FotoNotes Data Format
$FNDATAVERSION = "0.2";

GLOBAL $FN_DB, $FN_USER; 
$FN_DB = new FNDatabaseAbstract();
$FN_USER = new FNUser();

// SET image owner's password		(ToDo!)
// This is set in fn_config.php
// build local path to this script if autodetect
if ($LOCALPATH == 'autodetect') {
	$LOCALPATH = "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['SCRIPT_NAME'];
}


/* DEBUG 
--------------------------------------------- */
define("DEBUGLEVEL", 4);


/* PROCEDURE 
--------------------------------------------- */
displayDebug("Starting fotonotes.php ...", 2);

// CREATE a FotoNotes Image object and SET parameters
$fni = new FNImage();
switch($_SERVER['REQUEST_METHOD']) {
	case "POST":
		foreach ($_POST as $key => $value) {
			$fni->setFnImageParam($key, urldecode($value));
		}
	break;
	case "GET":
		foreach ($_GET as $key => $value) {
			echo "\nkey: $key";
			$fni->setFnImageParam($key, urldecode($value));
		}
	break;	
}

$fni->setFnImageParam("timestamp", gmdate("Y-m-d\TH:i:s\Z", time()));
// DETERMINE path to the image
$fni->setFnImageParam("url_parts", parse_url($fni->param['image']));
displayDebug("url_parts", 4);
displayDebugParam($fni->param['url_parts']);
$fni->setFnImageParam("image_path",$_SERVER['DOCUMENT_ROOT'].$fni->param['url_parts']['path']);

displayDebug("image_path: ".$fni->param['image_path'],4);
displayDebug("action: ". $fni->param['action'], 2);


// CLEANUP incoming XML from client, if exists
if ($fni->fnImageParamExists("xml")) {
	$fni->param["xml"] = preg_replace("#<\?xml.*>#Umsi", "", $fni->param["xml"]);
	$fni->param["xml"] = preg_replace("#<feed.*>#Umsi", "", $fni->param["xml"]);
	$fni->param["xml"] = preg_replace("#</feed>#Umsi", "", $fni->param["xml"]);
	$fni->param["xml"] = stripslashes($fni->param["xml"]);

	// Determine username by userid
	preg_match("#<userid>(.*)</userid>#",$fni->param["xml"],$matches);
	$userid = $matches[1];
	$username = $FN_USER->getDisplayNameByUserID($userid);
	$fni->setFnImageParam("username", $username);
	$userpropername = $FN_USER->getProperNameByUserID($userid);
	$fni->setFnImageParam("userpropername", $userpropername);
	$fni->param["xml"] = preg_replace("#<name>.*</name>#Umsi", "<name>$userpropername</name>", $fni->param["xml"]);	
}

// DO the requested action
if ($fni->fnImageParamExists("action")) {
	$fni->doFnAction();
}

displayDebug("Done.", 1);

// DONE with proceedure. Rest of file are functions and classes.

/* ABSTRACT CLASSES */


/*
To create a new UUID, instatiate the class, and call the method using:
		$CreateUUID = new UUID;
		$UUID = $CreateUUID->GenUUID();
*/


/* ADDITIONAL DOCUMENTATION */


?>
