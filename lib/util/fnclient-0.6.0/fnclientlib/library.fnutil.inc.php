<?php
/* FILE INFO */
/*
 * File: library.fnutil.inc.php
 * Author: Greg Elin
 * Version: 0.1
 * Updated: 04.14.2005
 * Description: Various utility functions used by fotonotes.php.
 * Notes: Should be required by fotonotes.php, but should be loaded after any function overrides are.
 *
 */ 
 
 /* FUNCTIONS */
if (!function_exists('displayDebug')) {
	function displayDebug($content, $debuglevel = 1) {
		if (DEBUGLEVEL >= $debuglevel) {
			echo "\n<div>".$content."</div>\n";
		}
		return true;
	}
}

if (!function_exists('displayDebugParam')) {
	function displayDebugParam($param, $debuglevel = 1) {
		if (DEBUGLEVEL >= $debuglevel) {
			echo "\n<div><textarea rows=10 cols=80>";
			print_r($param);
			echo "</textarea></div>\n";
		}
		return true;
	}
}

if (!function_exists('getNewUUID')) {
	function getNewUUID() {
			$CreateUUID = new UUID;
			return $CreateUUID->GenUUID();
	}	
}

class UUID {
	var $args;
	var $number;
	
	function GenUUID(){
		return md5(getmypid().uniqid(rand()).$_SERVER['SERVER_NAME']);
	}
}

if (!function_exists('createPath')) {
	function createPath($storagepath) {
		$parts = explode("/", $storagepath);
		foreach ($parts as $part) {
			$dir .= "$part/";
			if (! file_exists($dir)) {
				@mkdir($dir);
			}
		}
	}
}

if (!function_exists('unEscapeHTMLLinks')) {
	function unEscapeHTMLLinks ($text) {
		// convert &gt; and &lt; back to < and > for links
		$lt = "&lt;";
		$gt = "&gt;";
	
	}	
}

if (!function_exists('pingFeedManager')) {
	function pingFeedManager($newfeed_url=null) {
		GLOBAL $FEEDMANAGER_HOST, $FEEDMANAGER_PATH;		
		displayDebug("pinging FeedManager: "."http://".$FEEDMANAGER_HOST.$FEEDMANAGER_PATH."?url="."http://".$newfeed_url, 3)	;
		$response = file("http://".$FEEDMANAGER_HOST.$FEEDMANAGER_PATH."?url="."http://".$newfeed_url);	
		return $response;
	}
}
?>