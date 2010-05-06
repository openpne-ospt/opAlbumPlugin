<?php

	/* PROJECT Specific */
	$FN_FEED_PATH = "fndate/feed/";
	$FN_FEED_STYLESHEET_PATH = "fnfeed.css";
	$FN_FOTONOTES_DISPLAY_SCRIPT = "http://fotonotes.net/index.php?fuseaction=my.fn&page=fotonotes"; // script for displaying fotonotes
	$FN_FOTONOTES_DATA_PATH_PREFIX = $_SERVER['SERVER_NAME']."";
	$FEEDMANAGER_HOST = $_SERVER['SERVER_NAME'].":8080";
	$FEEDMANAGER_PATH = "/pubsub/pubsub";
	$ANNOTATIONS_ORIGINALS_FOLDER =	'fndata/imagedata/annotations';
	$ANNOTATIONS_THUMBNAILS_FOLDER = 'fndata/imagedata/thumbnails';
	
	
	/* /PROJECT Specific */
	
	$SERVER_ADDRESS = "";

	$ORIGINALS_FOLDER		=		'imagedata/originals';
	$THUMBNAILS_FOLDER		=		'imagedata/thumbnails';
	$THUMBNAIL_SIZE			=		'200';
	
	$THUMBNAIL_MAXWIDTH		=		'170';
	$THUMBNAIL_MAXHEIGHT	=		'120';
	$ENTRIES_PER_PAGE		=	12;
	$ENTRIES_PER_PAGE_DAILY	=		60;
	
	$DHTML_MAXWIDTH	= 675;
	$DHTML_MAXHEIGHT =	675;
	
	$DATE_FORMAT			=		'm/d/Y H:i';
	$DB_TYPE				=		'MYSQL';		// 'MYSQL', 'SQLITE' or 'NONE'
	$FILETYPES = "jpg|png|gif|avi|mpg|mov";
	
/*
	Set $ADD, $MODIFY and $DELETE to 
 	'prompt' to have the FotoNotes viewer ask for the password before the user can proceed with an action
 	'allow' to allow access to an action to everyone
 	'deny' to disable an action completely
*/
	$PERMISSIONS = array();
	$PERMISSIONS['ADD'] = 'allow';
	$PERMISSIONS['MODIFY'] = 'allow';
	$PERMISSIONS['DELETE'] = 'allow';
	$PERMISSIONS['PASSWORD'] = '';
	
	
	$MYSQL_SERVER			=		'localhost';
	$MYSQL_USERNAME			=		'root';
	$MYSQL_PASSWORD			=		'';
	$MYSQL_DBNAME			=		'album_image_tag';
		
	// INDICATE where data & annotations should be saved
	$FNSAVESTRATEGY["FNSaveDatabaseRows"] = false;
	$FNSAVESTRATEGY["FNSaveDatabaseXMLBlock"] = false;
	$FNSAVESTRATEGY["FNSaveJPEGHeader"] = true;
	$FNSAVESTRATEGY["FNSaveTextFile"] = false;
	// INDICATE strategy for saving Feed of annotation, if at all	
	$FNANNOTATIONFEEDSAVESTRATEGY["FNAnnotationFeedSaveXmlFile"] = false;

	// INDICATE from where annotations should be retrieved (choose one)
	$FNRETRIEVESTRATEGY["FNRetrieveDatabaseRows"] = false;
	$FNRETRIEVESTRATEGY["FNRetrieveJPEGHeader"] = true;

/* 
	Do Not Edit Below Here
*/

	// Make sure error notices do not show up
	error_reporting(E_ALL & ~E_NOTICE);
	
	// set version number
	DEFINE(FN_VERSION,'0.6.0');	
?>