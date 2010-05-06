<?php
/* FILE INFO */
/*
 * File: library.fnretrieve.inc.php
 * Author: Greg Elin
 * Version: 0.1
 * Updated: 04.14.2005
 * Description: FNRetrieve Class consisting of an abstract class and concrete classes for retrieving data from storage
 *
 */ 
 
 /* CLASSES */
 
/* FNRetrieve - abstract class to retrieve entries
	FNRetrieveDatabaseRows - concrete class to fetch from database, annotations stored in separate rows
	FNRetrieveDatabaseXMLBlock - concrete class to fetch from databse, all annotations in single row as xml block
	FNRetrieveJPEGHeader - concrete class to fetch from JPEG headers.
	FNRetrieveTextFile - concrete class to fetch from sidecar text file.
	FNRetrieveUrl - concrete class to fetch annotations from a URL.
*/

//  FNRetrieve - abstract class to retrieve entries
class FNRetrieve {

	function FNRetrieve() { }

	function getAnnotations() { }
	
	function getAnnotationsByID() { }
}


//  FNSelect - alias class to retrieve entries
class FNSelect extends FNRetrieve {

}

// FNRetrieveDatabaseRows - concrete class to fetch from database, annotations stored in separate rows
class FNRetrieveDatabaseRows extends FNRetrieve {

	function FNRetrieveDatabaseRows($fn_image) {
		GLOBAL $FN_DB, $FN_USER;  // database and user
		$this->FNDB = $FN_DB;
	}
		
	function getAnnotationsV03($uuid, $sortfield, $sortorder) {
		
		$query = "SELECT * from fn_annotation_rows WHERE uuid = \"$uuid\" ORDER BY $sortfield $sortorder";
		displayDebug("\$query: $query", 1);
		
		$r = $this->FNDB->query($query);
    	$xml_prefix = <<<EOD
 <?xml version="1.0" encoding="UTF-8"?>
 <feed version="0.3" 
     xmlns="http://purl.org/atom/ns#" 
     xmlns:dc="http://purl.org/dc/elements/1.1/" xml:lang="en" 
     xmlns:fn="http://fotonotes.net/protocol/fotonotes_0.2"
 >\n
EOD;
		$xml_entryinfo = "";
		
		$xml_suffix = "\n</feed>";
		
		while ($row = $r->fetch()) {
			//print_r($row);
			$entry = "\n<entry>\n";
			$entry .= "<id>$row[uuid]</id>\n";
			$entry .= "<fn:id>$row[file]</fn:id>\n";
			$entry .= "<user_ID>$row[user_ID]</user_ID>\n";
			$entry .= "<username>$row[username]</username>\n";
			$entry .= "<added>$row[added]</added>\n";
			$entry .= "<modified>$row[modified]</modified>\n";
			$entry .= "<annotation>$row[annotation]</annotation>\n";
			$entry .= "<annotation_id>$row[annotation_id]</annotation_id>\n";
			$entry .= "<annotation_title>$row[annotation_title]</annotation_title>\n";
			$entry .= "<annotation_author>$row[annotation_author]</annotation_author>\n";
			$entry .= "<annotation_boundingbox>$row[annotation_boundingbox]</annotation_boundingbox>\n";
			$entry .= "<annotation_content>$row[annotation_content]</annotation_content>\n";
			
			$entry .= "</entry>";
			$xml .= $entry; 
		}
		
		$xml = $xml_prefix . $xml_entryinfo . $xml . $xml_suffix;
	
		return $xml;
	}
	
	function getAnnotationsByID() {	
	}		
}


// FNRetrieveDatabaseXMLBlock - concrete class to fetch from databse, all annotations in single row as xml block
class FNRetrieveDatabaseXMLBlock extends FNRetrieve {


}

// FNRetrieveJPEGHeader - concrete class to fetch from JPEG headers.
class FNRetrieveJPEGHeader extends FNRetrieve {
	
	function FNRetrieveJPEGHeader() {
		$this->ISTHeaderNumber = 8;
	}
	
	function getAnnotations(&$fn_image) {	
		// The $fn_image must be passed by reference because this functions sets parameters. 
		$DHTML_MAXWIDTH = $fn_image->param['width'];
		$DHTML_MAXHEIGHT = $fn_image->param['height'];

		//$this->image = $fn_image->param['image'];
		$this->image = $fn_image->param['image_path'];
		displayDebug('FNRetrieveJPEGHeader called & image is '.$this->image,4);
		//$imageinfo = $this->getImageInfo($imagefile);
		//$size = getimagesize($imageinfo['image_src']);
		$size = getimagesize($this->image);
		displayDebugParam($size, 4);
		$ratioWidth = $DHTML_MAXWIDTH /  $size[0];
		$ratioHeight = $DHTML_MAXHEIGHT / $size[1];

		if($ratioHeight>$ratioWidth){$ratio=$ratioWidth;}else{$ratio=$ratioHeight;}
		if($ratio>1){$ratio=1;}
		
		$fn_image->setFnImageParam('scalefactor', $ratio);
		
		$this->annotationXMLBlock = $this->getAnnotationsXMLBlockFromJPEGHeader();
		$fn_image->setFnImageParam("annotationXMLBlock", $this->annotationXMLBlock);
		
		//return $this->annotationXMLBlock;
		//$xml = $this->readFotonotesXML($imagefile); // from older code
		$xml = $this->annotationXMLBlock;
		
		preg_match_all("#<entry>(.*)</entry>#Umsi", $xml, $entries);
		foreach($entries[1] as $entry) {
			preg_match("#<fn:boundingBox>(.*)</fn:boundingBox>#Umsi", $entry, $coordstring);
			$coords = explode(",", $coordstring[1]);
			list(	$annotation['upperleftx'], 
					$annotation['upperlefty'], 
					$annotation['lowerrightx'], 
					$annotation['lowerrighty'])  = $coords;
			//$annotation['width'] = ($coords[2] - $coords[0])*$ratio;
			$annotation['width'] = ($coords[2] - $coords[0])*$ratio;
			$annotation['height'] = ($coords[3] - $coords[1])*$ratio;
			$annotation['upperlefty'] *= $ratio;
			$annotation['upperleftx'] *= $ratio;
			$annotation['lowerrightx'] *= $ratio;
			$annotation['lowerrighty'] *= $ratio;
			
			preg_match("#<title>(.*)</title>#Umsi", $entry, $title);
			$annotation['title'] = $title[1];
			preg_match("#<content.*>(.*)</content>#Umsi", $entry, $content);
			$annotation['content'] = $content[1];
			preg_match("#<name>(.*)</name>#Umsi", $entry, $author);
			$annotation['author'] = $author[1];
			preg_match("#<created>(.*)</created>#Umsi", $entry, $created);
			$annotation['created'] = $created[1];
			preg_match("#<issued>(.*)</issued>#Umsi", $entry, $issued);
			$annotation['issued'] = $issued[1];
			preg_match("#<modified>(.*)</modified>#Umsi", $entry, $modified);
			$annotation['modified'] = $modified[1];
			preg_match("#<id>(.*)</id>#Umsi", $entry, $id);
			//$annotation['id'] = basename($id[1]);  Do not get basename, use full url
			$annotation['id'] = $id[1];
			$annotations[] = $annotation;
		}
		return $annotations;
	}

	function getAnnotationByID($imagefile, $id) {
		global $ORIGINALS_FOLDER;
		$id = "$id@$imagefile";			
		$annotations = $this->getAnnotations($imagefile);
		$imageinfo = $this->getImageInfo($imagefile);
		$storagepath = $this->getStoragePath($imagefile);
		foreach($annotations as $annotation) {
			if ($annotation['id'] == $id) {
				if (! file_exists($id)) {
					$original = imagecreatefromjpeg($imageinfo['image_src']);
					$width = $annotation['lowerrightx'] - $annotation['upperleftx'];
					$height = $annotation['lowerrighty'] - $annotation['upperlefty'];
					$area = imagecreatetruecolor($width, $height);
					imagecopyresampled($area, $original, 0, 0, $annotation['upperleftx'], $annotation['upperlefty'], $width, $height, $width, $height);
					imagejpeg($area, "$ORIGINALS_FOLDER/$storagepath/$id");
					chmod("$ORIGINALS_FOLDER/$storagepath/$id", 0777);
					imagedestroy($original);
					imagedestroy($area);
				}
				return $annotation;
			}
		}
	}
	
	function getAnnotationsXMLBlockFromJPEGHeader() {
		$imageIn = fopen($this->image, "rb");
		$this->headers[0] = 1;
		while ( ($char = fgetc( $imageIn)) > -1) {
			$charValue = hexdec(bin2hex($char));
			if ( $charValue == 0xff) {
				$char2 = fgetc( $imageIn );
				$charValue2 = hexdec(bin2hex($char2));
				if ( $charValue2 > 0xe0 ) {
					$length = hexdec(bin2hex( fgetc($imageIn) . fgetc($imageIn) ));
					if (($charValue2 - 0xe0) == $this->ISTHeaderNumber) {
						$this->headers[ $charValue2 - 0xe0 ] = fread($imageIn,$length-2);
						fclose($imageIn);
						return $this->headers[ $charValue2 - 0xe0 ];
					}
				}
			}
		}
	}
	

}

// FNRetrieveTextFile - concrete class to fetch from sidecar text file.
// FNRetrieveUrl - concrete class to fetch annotations from a URL.

?>