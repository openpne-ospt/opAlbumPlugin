<?php
/* FILE INFO */
/*
 * File: library.fnimageannotation.inc.php
 * Author: Greg Elin
 * Version: 0.1
 * Updated: 04.14.2005
 * Description: FNImageAnnotation Class consisting for creating and reading image annotations
 *
 */ 
 
 /* CLASSES */
 
 class FNImageAnnotation {

	function FNImageAnnotation() {
		displayDebug("Creating FNImageAnnotation...", 3);
		$this->param = array();
		
	}
	
	function setFnImageAnnotationParam($param, $value) {
		$this->param[$param] = $value;
	}
	
	function fnImageAnnotationParamExists($param) {
		$exists = false;
		if ($this->param[$param]) {
			$exists = true;
		}
		return $exists;
	}
	

	function parseEntryXML($entry_xml) {
		GLOBAL $FNDATAVERSION;

		if ($FNDATAVERSION  = "0.2") {
			return $this->parseEntryXMLV02($this->param["src_xml"]);
		} else if ($FNDATAVERSION == "0.3") {
			return $this->parseEntryXMLV03($this->param["src_xml"]);
		}
	
	}
	
	function parseEntryXMLV02($entry_xml) {
    		$entry_array = array();
    
    		//add xml details for parsing by simplexml
    		$xml_prefix = <<<EOD
 <feed version="0.2" 
     xmlns="http://purl.org/atom/ns#" 
     xmlns:dc="http://purl.org/dc/elements/1.1/" xml:lang="en" 
     xmlns:fn="http://fotonotes.net/protocol/fotonotes_0.2"
 >\n
EOD;
		$xml_suffix = "\n</feed>";
		$entry_xml = $xml_prefix . $entry_xml . $xml_suffix;
		displayDebugParam($entry_xml, 3);
 
 		// if version of simplexml functions exist, use them, else parse manually or other means.
 		if (function_exists('simplexml_load_string')) {
			$entry_object = simplexml_load_string($entry_xml);
			
			$entry_array['file'] = (string) $entry_object->entry->id;
			$entry_array['id'] = $this->extractUuidFromFileUrl((string) $entry_object->entry->id);
			$entry_array['fn:id'] = $entry_object->entry->id;
			//$entry_array['parent_id'] = $this->extractParentUuidFromFileUrl((string) $entry_object->entry->id);
			$entry_array['parent_id'] = (string) $entry_object->entry->entry_id;
			$entry_array['link_href'] = (string) $entry_object->entry->link['href'];
			$entry_array['issued'] = (string) $entry_object->entry->issued;
			$entry_array['created'] = (string) $entry_object->entry->created;
			$entry_array['modified'] = (string) $entry_object->entry->modified;
			foreach ($entry_object->entry->children('http://fotonotes.net/protocol/fotonotes_0.2') as $fnentry) {
				$entry_array['boundingbox'] = (string) $fnentry->boundingBox[0];
			}
			$entry_array['title'] = (string) $entry_object->entry->title;
			$entry_array['name'] = (string) $entry_object->entry->author->name;
			$entry_array['userid'] = (string) $entry_object->entry->author->userid;
			$entry_array['content'] = (string) $entry_object->entry->content;
		} else {
			// SET $entry_array manually
			displayDebug("simplexml functions missing. Building entry_array manually)", 4);
			
	/*
	[annotationID] => 2afff5c73dfdc3b86665dfc5f347c433
    [src_xml] => <entry><id>http://gelaptop.local/fnclient-0.4.0/test_images/2afff5c73dfdc3b86665dfc5f347c433@hana1.jpg</id><link rel="alternate" type="text/html" href="&amp;entry_ID=2afff5c73dfdc3b86665dfc5f347c433" /> <issued>2005-11-29T16:24:29Z</issued><created>2005-11-29T16:24:29Z</created><modified>2005-11-29T16:24:29Z</modified><fn:selection bordercolor="#FE0000"><fn:boundingBox>200,21,265,86</fn:boundingBox></fn:selection><title>Books1</title><author><name></name><userid></userid></author><content>These are books.</content><entry_id></entry_id></entry>
    [feed_uuid] => a0c04ec889598e244ac66afcd283105d
    [feed_file_path] => fndate/feed/2005/11/29/a0c04ec889598e244ac66afcd283105d.xml
	*/
/*
    [src_xml] => <entry>
    <id>http://gelaptop.local/fnclient-0.4.0/test_images/2afff5c73dfdc3b86665dfc5f347c433@hana1.jpg</id>
    <link rel="alternate" type="text/html" href="&amp;entry_ID=2afff5c73dfdc3b86665dfc5f347c433" /> 
    <issued>2005-11-29T16:24:29Z</issued>
    <created>2005-11-29T16:24:29Z</created>
    <modified>2005-11-29T16:24:29Z</modified>
    <fn:selection bordercolor="#FE0000">
    <fn:boundingBox>200,21,265,86</fn:boundingBox>
    </fn:selection><title>Books1</title>
    <author>
    	<name></name>
    	<userid></userid>
    </author>
    <content>These are books.</content>
    <entry_id></entry_id>
    </entry>


*/
/* What I know about the image (GLOBAL $fni)
           [image] => http://gelaptop.local/fnclient-0.4.0/test_images/hana1.jpg
            [action] => add
            [xml] => <entry><id>http://gelaptop.local/fnclient-0.4.0/test_images/8be160e1f0079506543616f5231505e0@hana1.jpg</id><link rel="alternate" type="text/html" href="&amp;entry_ID=8be160e1f0079506543616f5231505e0" /> <issued>2005-11-29T18:39:24Z</issued><created>2005-11-29T18:39:24Z</created><modified>2005-11-29T18:39:24Z</modified><fn:selection bordercolor="#FE0000"><fn:boundingBox>200,21,265,86</fn:boundingBox></fn:selection><title>Books1</title><author><name></name><userid></userid></author><content>These are books.</content><entry_id></entry_id></entry>
            [timestamp] => 2005-11-29T18:39:24Z
            [url_parts] => Array
                (
                    [scheme] => http
                    [host] => gelaptop.local
                    [path] => /fnclient-0.4.0/test_images/hana1.jpg
                )

            [image_path] => /Library/WebServer/Documents/fnclient-0.4.0/test_images/hana1.jpg
            [username] => 
            [userpropername] => 
            [annotationID] => 8be160e1f0079506543616f5231505e0

*/

			$entry_keys = array('id','fn:id','parent_id','link_href','issued','created','modified','title','name','userid','content');
			
			foreach($entry_keys as $key) {
				preg_match("#<".$key.">(.*)</".$key.">#",$this->param["src_xml"],$matches);
				$entry_array[$key] = $matches[1];
			}
		}

		displayDebug("entry_array:",4);
		displayDebugParam($entry_array,4);
		return $entry_array;
	}	
	
	
	
	function parseEntryXMLV03($entry_xml) {
    		$entry_array = array();
    
    		//add xml details for parsing by simplexml
    		$xml_prefix = <<<EOD
 <feed version="0.3" 
     xmlns="http://purl.org/atom/ns#" 
     xmlns:dc="http://purl.org/dc/elements/1.1/" xml:lang="en" 
     xmlns:fn="http://fotonotes.net/protocol/fotonotes_0.2"
 >\n
EOD;
		$xml_suffix = "\n</feed>";
		$entry_xml = $xml_prefix . $entry_xml . $xml_suffix;
		displayDebugParam($entry_xml, 3);
 
		$entry_object = simplexml_load_string($entry_xml);
		/*
		$entry_object->entry->id
		$entry_object->entry->link
		$entry_object->entry->issued
		$entry_object->entry->created
		$entry_object->entry->modified
		$entry_object->entry->selection->boundingbox // must be extracted from name space
		$entry_object->entry->title
		$entry_object->entry->author->name
		$entry_object->entry->author->userid
		$entry_object->entry->content
		*/
		
		$entry_array['file'] = (string) $entry_object->entry->id;
		$entry_array['id'] = $this->extractUuidFromFileUrl((string) $entry_object->entry->id);
		$entry_array['fn:id'] = $entry_object->entry->id;
		//$entry_array['parent_id'] = $this->extractParentUuidFromFileUrl((string) $entry_object->entry->id);
		$entry_array['parent_id'] = (string) $entry_object->entry->entry_id;
		$entry_array['link_href'] = (string) $entry_object->entry->link['href'];
		$entry_array['issued'] = (string) $entry_object->entry->issued;
		$entry_array['created'] = (string) $entry_object->entry->created;
		$entry_array['modified'] = (string) $entry_object->entry->modified;
		foreach ($entry_object->entry->children('http://fotonotes.net/protocol/fotonotes_0.2') as $fnentry) {
			$entry_array['boundingbox'] = (string) $fnentry->boundingBox[0];
		}
		$entry_array['title'] = (string) $entry_object->entry->title;
		$entry_array['name'] = (string) $entry_object->entry->author->name;
		$entry_array['userid'] = (string) $entry_object->entry->author->userid;
		$entry_array['content'] = (string) $entry_object->entry->content;
		displayDebug("entry_array:",4);
		displayDebugParam($entry_array,4);
		return $entry_array;
	}	

	function extractUuidFromFileUrl($serverUrl) {
		//displayDebug("serverUrl: $serverUrl", 3);
		$path_parts = pathinfo($serverUrl);
		$pattern = "/@([a-zA-Z0-9]+-)*+[a-zA-Z0-9]*+\.$path_parts[extension]$/";
		$Uuid = preg_replace($pattern,'',$path_parts['basename']);
		//displayDebug("Uuid: $Uuid", 3);
		return $Uuid;
	}

	function extractParentUuidFromFileUrl($serverUrl) {
		//displayDebug("serverUrl: $serverUrl\n");
		$path_parts = pathinfo($serverUrl);
		$pattern = "/@(.*)\.$path_parts[extension]$/";
		preg_match($pattern,$path_parts['basename'],$matches);
		$serverUuid = $matches[1];  
		//displayDebug("\nParent serverUuid: $serverUuid \n", 3);
		return $serverUuid;
	}	

	function generateFnImageAnnotationXml() {
		GLOBAL $FNDATAVERSION;

		if ($FNDATAVERSION  = "0.2") {
			return $this->generateFnImageAnnotationXmlV02();
		} else if ($FNDATAVERSION == "0.3") {
			return $this->generateFnImageAnnotationXmlV03();
		}		
	}

	function generateFnImageAnnotationXmlV02() {
		// make sure <entry>...<entry> is clearly generated
		GLOBAL $FN_FEED_PATH, $FN_FEED_STYLESHEET_PATH, $FN_FOTONOTES_DATA_PATH_PREFIX, $FILETYPES;
		GLOBAL $ANNOTATIONS_ORIGINALS_FOLDER, $ANNOTATIONS_THUMBNAILS_FOLDER;
		
		$xml = null;
		
		// get unique uuid for feed
		$feed_uuid = getNewUUID();
		$this->param["feed_uuid"] = $feed_uuid;
		
		// get feed file destination based on today's date
		$createdtime = time();
		$year = gmdate("Y", $createdtime);
		$month = gmdate("m", $createdtime);
		$day = gmdate("d", $createdtime);
		$created = gmdate("Y-m-d\TH:i:s\Z",$createdtime);
		
		$storagepath = $FN_FEED_PATH.$year."/".$month."/".$day;
		$feed_file_path = $storagepath."/".$feed_uuid.".xml";
		$this->param["feed_file_path"] = $feed_file_path;
		displayDebug( "feed_file_path: $feed_file_path", 3);
		displayDebug( "param[feed_file_path]: ".$this->param["feed_file_path"], 3);
		
		//make sure path exists
		createPath($storagepath);
		
		// parse incoming xml
		displayDebug("this->param[src_xml]", 2);
		displayDebugParam($this->param["src_xml"], 2);

		$entry_array = $this->parseEntryXML($this->param["src_xml"]);

		// ASSIGN *entry_array* values to *annotations* params
		foreach ($entry_array as $key => $value) {
			$this->setFnImageAnnotationParam($key,$value);
		}
		
		// fix uuid
		// first transform existing <id> in xml to <fn:id>
		//$xml = preg_replace("#<id>(.*)</id>#","<fn2:id>$1</fn2:id>",$xml);
		//$this->param["src_xml"] = preg_replace("#<id>(.*)</id>#","<fn:id>$1</fn:id>",$this->param["src_xml"]);
		//$entry_uuid = "<id>$entry_array[id]</id>\n";
		
		//$parent_entry_id = $entry_array['parent_id']; 
		//$parent_entry_uuid = "<parent_uuid>".$entry_array['parent_id']."</parent_uuid>";
		
		//$parent_entry_array = null; //getEntriesXML2Array($parent_entry_id);
		//echo "\ngeom: ".$parent_entry_array['geometry']. "\n";
		//$entry_geometry = "<geometry>".$parent_entry_array['geometry']."</geometry>";
		// SET parent_image_link in fn_anntoation
		//$this->param["parent_link_jpg"] = $parent_entry_array["link_jpg"];
		
		// PREPARE *make image* parameters
		//displayDebug("<p>parent_entry_array: $parent_entry_array", 2);
		//displayDebugParam($parent_entry_array, 2);
		//$annotation_storagepath = $ANNOTATIONS_ORIGINALS_FOLDER."/".$year."/".$month."/".$day;
		//displayDebug("annotation_storagepath: $annotation_storagepath", 1);
		
		// CHECK AND CREATE  *file path*
		//createPath($annotation_storagepath);
 		
 		// PREPARE xml links for section of photo
 		//$entry_link_jpg = "http://".$_SERVER['HTTP_HOST'].preg_replace("#/fns/fotonotes.php#","",$_SERVER['REQUEST_URI']).$annotation_storagepath.'/'.$entry_array[id].'.jpg';
		//$entry_link_jpg = "http://".$_SERVER['HTTP_HOST'].preg_replace("#/fns/fotonotes2.php#","",$_SERVER['REQUEST_URI']).$annotation_storagepath.'/'.$entry_array[id].'.jpg';
		
		//displayDebug("\$entry_link_jpg: $entry_link_jpg", 3);
		// this won't always be jpg
		//$entry_mimetype = "image/jpg";
		//$entry_annotation_link = "<link href=\"$entry_link_jpg\" rel=\"annotated_region\" type=\"$entry_mimetype\" />";		
		//$entry_jpg_link = "<link href=\"$entry_link_jpg\" rel=\"jpg\" type=\"$entry_mimetype\" />";

 		// WRITE annotated section of photo to file system
		/*
		displayDebug("\n fn_makeImage ($parent_entry_array[link_jpg], ".
			str_replace(","," ",$entry_array[boundingbox]).", 1,
			null, null, $annotation_storagepath.'/'.$entry_array[id].'.jpg')");
		fn_makeImage($parent_entry_array['link_jpg'], 
			str_replace(","," ",$entry_array[boundingbox]), 1,
			null, null, $annotation_storagepath.'/'.$entry_array[id].'.jpg', 1);				
		 		
 		// PREPARE *make image* parameters for *thumbnail* of annotated region
		$annotation_thumbnail_storagepath = $ANNOTATIONS_THUMBNAILS_FOLDER."/".$year."/".$month."/".$day;
		displayDebug("annotation_storagepath: $annotation_thumbnail_storagepath", 3);
		// calculate thumbscale factor	- This needs to be better, to actually calculate scale!
		$thumbscale = 1;
		
		// CHECK AND CREATE  *file path* for *thumbnail* of annotated region
		createPath($annotation_thumbnail_storagepath);
 		
 		// PREPARE xml links for *thumbnail* of annotated region
		$entry_thumbnail_link_jpg = "http://".$_SERVER['HTTP_HOST'].preg_replace("#/fns/fotonotes.php#","",$_SERVER['REQUEST_URI']).$annotation_thumbnail_storagepath.'/'.$entry_array[id].'.jpg';
		$entry_thumbnail_link_jpg = "http://".$_SERVER['HTTP_HOST'].preg_replace("#/fns/fotonotes2.php#","",$_SERVER['REQUEST_URI']).$annotation_thumbnail_storagepath.'/'.$entry_array[id].'.jpg';

		// this won't always be jpg
		$entry_mimetype = "image/jpg";
		$entry_mimetype_thn = "image/thn-jpg";
		$entry_thumbnail_link = "<link href=\"$entry_link_jpg\" rel=\"annotated region image\" type=\"$entry_mimetype\" />";		
		$entry_annotation_thumbnail_link = "<link href=\"$entry_thumbnail_link_jpg\" rel=\"thn\" type=\"$entry_mimetype_thn\" />";		
		// this won't always be jpg
		$parent_entry_mimetype = "image/jpg";
		$parent_entry_url = "";
		$parent_entry_link = "<link href=\"\" rel=\"annotated image\" type=\"$parent_entry_mimetype\" />";
 		
 		// WRITE *thumbnail of annotated section of photo to file system
		fn_makeImage($parent_entry_array['link_jpg'], 
			str_replace(","," ",$entry_array[boundingbox]), $thumbscale,
			null, null, $annotation_thumbnail_storagepath.'/'.$entry_array[id].'.jpg');
		
 		// Almost done - just need to create annotation entry xml, store it properly, and put feed on file system.
		*/
		
		// BUILD *annotation entry xml*
		// this needs fixing - what should $xml be that is getting replaced?
		/* $xml_entryinfo = preg_replace("#<entry>#",
			"<entry title=\"FotoNotes Annotation\" type=\"fn\" ".
			" xmlns:fn=\"http://fotonotes.net/protocol/fotonotes_0.2\">"	
			.$entry_uuid.$entry_geometry.$entry_jpg_link.$entry_annotation_link
			.$entry_annotation_thumbnail_link.$parent_entry_uuid,
			$this->param["src_xml"]);
		*/
 		// ADD carriage returns for readability
 		/*
 		$xml_entryinfo = preg_replace("#<([a-zA-Z])#","\n<$1",$xml_entryinfo);
		$xml_entryinfo = preg_replace("#</fn:selection>#","\n</fn:selection>",$xml_entryinfo);
		$xml_entryinfo = preg_replace("#</author>#","\n</author>",$xml_entryinfo);
		$xml_entryinfo = preg_replace("#</entry>#","\n</entry>",$xml_entryinfo);
		*/
		$this->param["xml_entryinfo"] = $xml_entryinfo; 

		displayDebug("generateFnImageAnnotationXmlV02 xml_entryinfo:", 2);
		displayDebugParam($this->param["xml_entryinfo"]);	
		return $this->param["xml_entryinfo"];
	}


	function generateFnImageAnnotationXmlV03() {
		// make sure <entry>...<entry> is clearly generated
		GLOBAL $FN_FEED_PATH, $FN_FEED_STYLESHEET_PATH, $FN_FOTONOTES_DATA_PATH_PREFIX, $FILETYPES;
		GLOBAL $ANNOTATIONS_ORIGINALS_FOLDER, $ANNOTATIONS_THUMBNAILS_FOLDER;
		
		$xml = null;
		
		// get unique uuid for feed
		$feed_uuid = getNewUUID();
		$this->param["feed_uuid"] = $feed_uuid;
		
		// get feed file destination based on today's date
		$createdtime = time();
		$year = gmdate("Y", $createdtime);
		$month = gmdate("m", $createdtime);
		$day = gmdate("d", $createdtime);
		$created = gmdate("Y-m-d\TH:i:s\Z",$createdtime);
		
		$storagepath = $FN_FEED_PATH.$year."/".$month."/".$day;
		$feed_file_path = $storagepath."/".$feed_uuid.".xml";
		$this->param["feed_file_path"] = $feed_file_path;
		displayDebug( "feed_file_path: $feed_file_path", 3);
		displayDebug( "param[feed_file_path]: ".$this->param["feed_file_path"], 3);
		
		//make sure path exists
		createPath($storagepath);
		
		// parse incoming xml
		displayDebug("this->param[src_xml]", 2);
		displayDebugParam($this->param["src_xml"], 2);
		
		$entry_array = $this->parseEntryXML($this->param["src_xml"]);
				
		
		// ASSIGN *entry_array* values to *annotations* params
		foreach ($entry_array as $key => $value) {
			$this->setFnImageAnnotationParam($key,$value);
		}

		// fix uuid
		// first transform existing <id> in xml to <fn:id>
		//$xml = preg_replace("#<id>(.*)</id>#","<fn2:id>$1</fn2:id>",$xml);
		$this->param["src_xml"] = preg_replace("#<id>(.*)</id>#","<fn:id>$1</fn:id>",$this->param["src_xml"]);
		$entry_uuid = "<id>$entry_array[id]</id>\n";
		
		$parent_entry_id = $entry_array['parent_id']; 
		$parent_entry_uuid = "<parent_uuid>".$entry_array['parent_id']."</parent_uuid>";
		
		$parent_entry_array = null; //getEntriesXML2Array($parent_entry_id);
		//echo "\ngeom: ".$parent_entry_array['geometry']. "\n";
		$entry_geometry = "<geometry>".$parent_entry_array['geometry']."</geometry>";
		// SET parent_image_link in fn_anntoation
		$this->param["parent_link_jpg"] = $parent_entry_array["link_jpg"];
		
		// PREPARE *make image* parameters
		displayDebug("<p>parent_entry_array: $parent_entry_array", 2);
		displayDebugParam($parent_entry_array, 2);
		$annotation_storagepath = $ANNOTATIONS_ORIGINALS_FOLDER."/".$year."/".$month."/".$day;
		displayDebug("annotation_storagepath: $annotation_storagepath", 1);
		
		// CHECK AND CREATE  *file path*
		createPath($annotation_storagepath);
 		
 		// PREPARE xml links for section of photo
 		$entry_link_jpg = "http://".$_SERVER['HTTP_HOST'].preg_replace("#/fns/fotonotes.php#","",$_SERVER['REQUEST_URI']).$annotation_storagepath.'/'.$entry_array[id].'.jpg';
		$entry_link_jpg = "http://".$_SERVER['HTTP_HOST'].preg_replace("#/fns/fotonotes2.php#","",$_SERVER['REQUEST_URI']).$annotation_storagepath.'/'.$entry_array[id].'.jpg';
		
		displayDebug("\$entry_link_jpg: $entry_link_jpg", 3);
		// this won't always be jpg
		$entry_mimetype = "image/jpg";
		$entry_annotation_link = "<link href=\"$entry_link_jpg\" rel=\"annotated_region\" type=\"$entry_mimetype\" />";		
		$entry_jpg_link = "<link href=\"$entry_link_jpg\" rel=\"jpg\" type=\"$entry_mimetype\" />";

 		// WRITE annotated section of photo to file system
		displayDebug("\n fn_makeImage ($parent_entry_array[link_jpg], ".
			str_replace(","," ",$entry_array[boundingbox]).", 1,
			null, null, $annotation_storagepath.'/'.$entry_array[id].'.jpg')");
		fn_makeImage($parent_entry_array['link_jpg'], 
			str_replace(","," ",$entry_array[boundingbox]), 1,
			null, null, $annotation_storagepath.'/'.$entry_array[id].'.jpg', 1);				
		 		
 		// PREPARE *make image* parameters for *thumbnail* of annotated region
		$annotation_thumbnail_storagepath = $ANNOTATIONS_THUMBNAILS_FOLDER."/".$year."/".$month."/".$day;
		displayDebug("annotation_storagepath: $annotation_thumbnail_storagepath", 3);
		// calculate thumbscale factor	- This needs to be better, to actually calculate scale!
		$thumbscale = 1;
		
		// CHECK AND CREATE  *file path* for *thumbnail* of annotated region
		createPath($annotation_thumbnail_storagepath);
 		
 		// PREPARE xml links for *thumbnail* of annotated region
		$entry_thumbnail_link_jpg = "http://".$_SERVER['HTTP_HOST'].preg_replace("#/fns/fotonotes.php#","",$_SERVER['REQUEST_URI']).$annotation_thumbnail_storagepath.'/'.$entry_array[id].'.jpg';
		$entry_thumbnail_link_jpg = "http://".$_SERVER['HTTP_HOST'].preg_replace("#/fns/fotonotes2.php#","",$_SERVER['REQUEST_URI']).$annotation_thumbnail_storagepath.'/'.$entry_array[id].'.jpg';

		// this won't always be jpg
		$entry_mimetype = "image/jpg";
		$entry_mimetype_thn = "image/thn-jpg";
		$entry_thumbnail_link = "<link href=\"$entry_link_jpg\" rel=\"annotated region image\" type=\"$entry_mimetype\" />";		
		$entry_annotation_thumbnail_link = "<link href=\"$entry_thumbnail_link_jpg\" rel=\"thn\" type=\"$entry_mimetype_thn\" />";		
		// this won't always be jpg
		$parent_entry_mimetype = "image/jpg";
		$parent_entry_url = "";
		$parent_entry_link = "<link href=\"\" rel=\"annotated image\" type=\"$parent_entry_mimetype\" />";
 		
 		// WRITE *thumbnail of annotated section of photo to file system
		fn_makeImage($parent_entry_array['link_jpg'], 
			str_replace(","," ",$entry_array[boundingbox]), $thumbscale,
			null, null, $annotation_thumbnail_storagepath.'/'.$entry_array[id].'.jpg');
		
 		// Almost done - just need to create annotation entry xml, store it properly, and put feed on file system.
		
		// BUILD *annotation entry xml*
		// this needs fixing - what should $xml be that is getting replaced?
		$xml_entryinfo = preg_replace("#<entry>#",
			"<entry title=\"FotoNotes Annotation\" type=\"fn\" ".
			" xmlns:fn=\"http://fotonotes.net/protocol/fotonotes_0.2\">"	
			.$entry_uuid.$entry_geometry.$entry_jpg_link.$entry_annotation_link
			.$entry_annotation_thumbnail_link.$parent_entry_uuid,
			$this->param["src_xml"]);
 		$xml_entryinfo = preg_replace("#<([a-zA-Z])#","\n<$1",$xml_entryinfo);
		$xml_entryinfo = preg_replace("#</fn:selection>#","\n</fn:selection>",$xml_entryinfo);
		$xml_entryinfo = preg_replace("#</author>#","\n</author>",$xml_entryinfo);
		$xml_entryinfo = preg_replace("#</entry>#","\n</entry>",$xml_entryinfo);
		displayDebug("xml_entryinfo: ", 2);
		displayDebugParam($xml_entryinfo, 2);
		$this->param["xml_entryinfo"] = $xml_entryinfo; 

		displayDebug("generateFnImageAnnotationXml xml_entryinfo:", 2);
		displayDebugParam($this->param["xml_entryinfo"]);
	
		return $this->param["xml_entryinfo"];
	}
}

?>