<?php
/* FILE INFO */
/*
 * File: library.fnannotationfeedsave.inc.php
 * Author: Greg Elin
 * Version: 0.1
 * Updated: 04.14.2005
 * Description: FNAnnotationFeedSave Class has abstract and concrete classes for saving feeds.
 *
 */ 

/* CLASSES */

//  FNAnnotationFeedSave - abstract class to storing annotations
class FNAnnotationFeedSave {

	function FNAnnotationFeedSave() { }

	function saveFNAnnotationFeed() { }
}

// FNAnnotationFeedSaveXmlFile - concrete class for storing annotation feeds on file system
class FNAnnotationFeedSaveXmlFileV03 {

	function FNAnnotationFeedSaveXmlFileV03() {
	
	}

	function saveFNAnnotationFeed($fn_image, $fn_annotation) {
		GLOBAL $FN_FEED_STYLESHEET_PATH;
		
		// BUILD *feed xml*
		// SET *style sheet path*     
        	$feed_stylesheet = $FN_FEED_STYLESHEET_PATH;
		displayDebug("feed_stylesheet: $feed_stylesheet", 3);

		// SET *feed file path* from annotation param
		$feed_file_path = $fn_annotation->param["feed_file_path"];

		// SET Other params
		$feed_uuid = $fn_annotation->param["feed_uuid"];
		$created = $fn_image->param["timestamp"];

		// SET *xml head*    	
		$xml_prefix = <<<EOD
<?xml version="1.0" encoding="UTF-8"?>
<?xml-stylesheet href='$feed_stylesheet' type='text/css'?>
<feed version="0.3"
     xmlns="http://purl.org/atom/ns#"
     xmlns:ps="http://pubsub.com/xmlns"
     xmlns:dc="http://purl.org/dc/elements/1.1/" xml:lang="en"
     xmlns:fn="http://fotonotes.net/protocol/fotonotes_0.2"
>\n
EOD;
		displayDebug("feed_file_path(2): ".$feed_file_path, 3);
		// SET additional prefix information 
		// NEED to improve link href that specifes $feed_file_path
$xml_prefix_additional  = <<<EOD
<title mode='escaped' type='text/html'>FotoNotes(tm) Feed</title>
<link href='{$_SERVER[path]}/$feed_file_path' rel='service.post' 
title='FotoNotes(tm) Feed' type='application/atom+xml'/>
<id>$feed_uuid</id>
<issued>$created</issued>
<created>$created</created> 
<modified>$created</modified>
<generator url='fotonotes.php' version='0.70'>FotoNotes(tm) Annotation Script</generator>
<author><name>FotoNotes(tm) Annotatator</name></author>
<info mode='xml' type='text/html'>This is a FotoNotes Image Annotation Feed</info>
EOD;

		// SET *xml* suffix
		$xml_suffix = "\n</feed>";
		
		// assemble full feed
		$newfeed_xml = $xml_prefix . $xml_prefix_additional . $fn_annotation->param["xml_entryinfo"] . $xml_suffix;
		
		// WRITE *feed xml* to filesystem
		$handle = fopen($feed_file_path,'w');
		fwrite($handle, $newfeed_xml);
		fclose($handle);
	}

}

?>