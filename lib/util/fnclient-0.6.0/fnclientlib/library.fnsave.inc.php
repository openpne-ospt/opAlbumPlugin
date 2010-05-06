<?php
/* FILE INFO */
/*
 * File: library.fnsave.inc.php
 * Author: Greg Elin
 * Version: 0.1
 * Updated: 04.14.2005
 * Description: FNSave Class consisting of an abstract concrete classes for saving data
 *
 */ 
 
 /* CLASSES */
 
 /* FNSave - class to save entries
	FNSaveDatabaseRows - concrete class to save in database, annotations stored in separate rows
	FNSaveJPEGHeader - concrete class to save ion JPEG headers.
	FNSaveDatabaseXMLBlock - concrete class to save in databse, all annotations in single row as xml block
	FNSaveTextFile - concrete class to save in sidecar text file.
*/

//  FNSave - abstract class to storing annotations
class FNSave {

	function FNSave() { }

	function saveNewAnnotation() { }
	
	function updateExistingAnnotationByID() { }
	
	function deleteAnnotationByID() { }
}


//  FNSaveDatabaseRows - concrete class to save in database, annotations stored in separate rows
class FNSaveDatabaseRows extends FNSave {

	function FNSaveDatabaseRows() {
	
	}

	function saveNewAnnotation($fn_image, $fn_annotation) {
		displayDebug("Saving annotation in database row", 2);
		GLOBAL $FN_DB, $FN_USER;
		
		$timestamp = time();
		displayDebug("fn_image object: ", 3);
		displayDebugParam($fn_image, 3);
		displayDebug("fn_annotation object: ", 3);
		displayDebugParam($fn_annotation, 3);

		$query = "INSERT INTO fn_annotation_rows
			(file, image_id, user_id, username, added, modified, annotation, 
			annotation_id, annotation_title, annotation_author, annotation_boundingbox, 
			annotation_content)
			VALUES
		  	('".$fn_annotation->param["parent_link_jpg"]."',
			'".$fn_annotation->param["parent_id"]."',
			'".$fn_annotation->param["userid"]."',
			'".$fn_annotation->param["name"]."',
			'$timestamp',
			'$timestamp',
			'".$fn_annotation->param["xml_entryinfo"]."',
			'".$fn_annotation->param["id"]."',
			'".$fn_annotation->param["title"]."',
			'".$fn_annotation->param["name"]."',
			'".$fn_annotation->param["boundingbox"]."',
			'".$fn_annotation->param["content"]."' 
			)";

   		displayDebug("query addannotationdb: $query<br>", 2);
		
		$result = $FN_DB->query($query);		
		
		// better to return row id if it exists
		return true;
	}
	
	function updateExistingAnnotationByID($fn_image, $fn_annotation) {
		displayDebug("Updating annotation in database row", 2);
		displayDebug("fn_annotation object right inside updateExistingAnnotationByID: ", 3);
		GLOBAL $FN_DB, $FN_USER;
		
		$timestamp = time();
		displayDebug("fn_image object: ", 3);
		displayDebugParam($fn_image, 3);
		displayDebug("fn_annotation object: ", 3);
		displayDebugParam($fn_annotation, 3);

		$query = "UPDATE fn_annotation_rows 
		SET 
			file = '".$fn_annotation->param["parent_link_jpg"]."',
			image_id = '".$fn_annotation->param["parent_id"]."',
 
			user_id = '".$fn_annotation->param["userid"]."', 
			username = '".$fn_annotation->param["name"]."',
 
			modified = '$timestamp', 
			annotation = '".$fn_annotation->param["xml_entryinfo"]."',
			annotation_id = '".$fn_annotation->param["id"]."',
			annotation_title = '".$fn_annotation->param["title"]."',
			annotation_author = '".$fn_annotation->param["name"]."',
 
			annotation_boundingbox = '".$fn_annotation->param["boundingbox"]."',
			annotation_content = '".$fn_annotation->param["content"]."' 
		WHERE annotation_id = '".$fn_annotation->param["id"]."'";

   		displayDebug("query addannotationdb: $query<br>", 2);
		$FN_DB->query($query);
		return true;
	}
	
	function deleteAnnotationByID($fn_image, $fn_annotation) {
		displayDebug("deleting annotation in database row", 2);
		GLOBAL $FN_DB, $FN_USER;
		
		$timestamp = time();
		displayDebug("fn_image object: ", 3);
		displayDebugParam($fn_image, 3);
		displayDebug("fn_annotation object: ", 3);
		displayDebugParam($fn_annotation, 3);

		$query = "DELETE FROM fn_annotation_rows WHERE annotation_id = '".$fn_annotation->param["id"]."'";

   		displayDebug("query addannotationdb: $query<br>", 2);
		$FN_DB->query($query);		
		// better to return row id if it exists
		return true;	
	}

}


class FNSaveJPEGHeader extends FNSave {

	function FNSaveJPEGHeader() {
	
	}

	function saveNewAnnotation(&$fn_image, &$fn_annotation) {
		displayDebug("Saving annotation in JPEGHeader row", 2);
		GLOBAL $FN_DB, $FN_USER, $PERMISSIONS;
		
		$timestamp = time();
		// RETRIEVE existing annotations from image; getAnnotations also sets fni's param['annotationXMLBlock']
		$this->oldxml = $fn_image->getAnnotations();
		
		//$entry_array =  FNImageAnnotation::parseEntryXML($entry_xml);	
		// need to add/strip slashes?
		
		displayDebug("***************** fn_annotation object ***************************",4);
		displayDebugParam($fn_annotation, 4);
		displayDebug("***************** fn_image object ***************************",4);
		displayDebugParam($fn_image,4);
		
		$results = $this->writeJPEGHeaders($fn_image, $fn_annotation);
		displayDebug("results are: ".$results,4);
		
		return $results;
	}
	
	function updateExistingAnnotationByID(&$fn_image, &$fn_annotation) {
		displayDebug("Updating annotation in JPGEHeader", 2);
		GLOBAL $FN_DB, $FN_USER;
		
		$timestamp = time();
		
		displayDebug("***************** fn_annotation object ***************************",4);
		displayDebugParam($fn_annotation, 4);
		displayDebug("***************** fn_image object ***************************",4);
		displayDebugParam($fn_image,4);
		
		$results = $this->writeJPEGHeaders($fn_image, $fn_annotation);
		displayDebug("results are: ".$results,4);

		//return results;
		return true;
	}
	
	function deleteAnnotationByID(&$fn_image, &$fn_annotation) {
		displayDebug("deleting annotation in JPEGHeader row", 2);
		GLOBAL $FN_DB, $FN_USER, $PERMISSIONS;
		
		$timestamp = time();
		// RETRIEVE existing annotations from image; getAnnotations also sets fni's param['annotationXMLBlock']
		$this->oldxml = $fn_image->getAnnotations();
				
		displayDebug("***************** fn_annotation object ***************************",4);
		displayDebugParam($fn_annotation, 4);
		displayDebug("***************** fn_image object ***************************",4);
		displayDebugParam($fn_image,4);

		// REMOVE entry with indicated ID from existing annotation xml block
		displayDebug("replace: "."#<entry><id>".$fn_annotation->param['id']."</id></entry>#Umsi",4);
		displayDebug("searching annotationxmlblock: ".$fn_image->param['annotationXMLBlock'],4);		
		$results = preg_match("#<entry><id>".$fn_annotation->param['id']."</id>.*</entry>#Umsi", $fn_image->param['annotationXMLBlock']);
		displayDebug("match: $results",4);

		$fn_image->param['annotationXMLBlock'] = preg_replace("#<entry><id>".$fn_annotation->param['id']."</id>.*</entry>#Umsi","", $fn_image->param['annotationXMLBlock']);
		
//		<entry><id>http://gelaptop.local/fnsclient/3@flamingo.jpg</id>
//		$xml = preg_replace("#<entry><id>$id</id>.*</entry>#Umsi", "", $oldxml);
		
		
		$results = $this->writeJPEGHeaders($fn_image, $fn_annotation);
		
   		return $results;	
	}
	
	function writeJPEGHeaders($fn_image, $fn_annotation) {	
		GLOBAL $LOCALPATH, $ADD, $MODIFY, $DELETE;
					
		// ADD or REPLACE existing annotation block in JPEG with new annotation block 
		// The annototation block that is written must contain all annotations, new and pre-existing
		// GET *image file path* on the server
		$this->image = $fn_image->param['image_path'];
		displayDebug("image_path is: ".$this->image,4);
		
		// Is image writable?
		if (!is_writable($this->image)) {
			echo "success=501";
			die();
		}
		
		// SET the FotoNotes image header number for a jpeg
		$this->ISTHeaderNumber = 8;
		displayDebug("WriteJPEGHeaders for image: ".$this->image, 4);
		displayDebug("New annotation received from fnclient (fn_annotation->param['src_xml']): ". $fn_annotation->param['src_xml'],4);
		
		if ($fn_image->param['annotationXMLBlock'] == null) {
			// xml block does not exists, so create it an add new annotation as first entry 
			
			if ($newtitle == '') {
				$newtitle = basename($filename);
			}		
			$header = '<?xml version="1.0" encoding="UTF-8"?>';
			$header .= '<feed version="0.2" xmlns="http://purl.org/atom/ns#" xmlns:dc="http://purl.org/dc/elements/1.1/" xml:lang="en" xmlns:fn="http://fotonotes.net/protocol/fotonotes_0.2">';
			//$header .= '<copyright>Copyright (c) 2004, Greg Elin</copyright>';
			$header .= "<id>".$fn_image->param['image']."</id>";
			$header .= "<created>".$fn_image->param['timestamp']."</created>";
			$header .= "<modified>".$fn_image->param['timestamp']."</modified>";
			$header .= "<title>".$fn_image->param['title']."</title>";
			//$header .= '<link rel="alternate" type="text/html" href="' . $LOCALPATH . '?p=' . $filename . '" />';
			// SET annotationXMLBlock and include new annotation
			$fn_image->param['annotationXMLBlock'] = $header . $fn_annotation->param['src_xml'] . "</feed>";
		} else {
			// xml block already exists, I need to append new entry
			$fn_image->param['annotationXMLBlock'] = preg_replace("#</feed>#Umsi",$fn_annotation->param['src_xml']."</feed>", $fn_image->param['annotationXMLBlock']);
		}
		
		displayDebug("New Annotation Block to be put into jpeg header is: ".$fn_image->param['annotationXMLBlock'], 4);
				
		$text = $fn_image->param['annotationXMLBlock'];
		$imageIn = fopen($this->image, "rb");
		
				
		while ( ($char = fgetc( $imageIn)) > -1) {
			$charValue = hexdec(bin2hex($char));
			if ( $charValue == 0xff ) {
				$char2 = fgetc( $imageIn );
				if ($char2 < 0) {
					break;
				}
				$charValue2 = hexdec(bin2hex($char2));
				if ($charValue2 == (0xe0 + $this->ISTHeaderNumber)) {
					$length = hexdec(bin2hex( fgetc($imageIn) . fgetc($imageIn) ));
					if ($length < 2) {
					  $length = 2;
					}
					fread($imageIn,$length-2);
				} elseif ($charValue2 > 0xe0 && $charValue2 < (0xe0 + $this->ISTHeaderNumber)) {
					$lenA = fgetc($imageIn);
					$lenB = fgetc($imageIn);
					$newImage .= $char . $char2 . $lenA . $lenB;
					$length = hexdec(bin2hex( $lenA . $lenB ));
					if ($length < 2) {
					  $length = 2;
					}
					$newImage .= fread($imageIn,$length-2);
				} else {
					if ( ($charValue2 < 0xe0 && $charValue2 != 0xd8) || $charValue2 > (0xe0 + $this->ISTHeaderNumber)) {
					  if (strlen($text) > 0) {
					    $length = str_pad(dechex(strlen($text) + 2),4,"0",STR_PAD_LEFT);
					    $newImage .= pack("H*","FFe" . $this->ISTHeaderNumber . $length) . $text;
					  }
					  $newImage .= $char;
					  $newImage .= $char2;
					  break;
					}
					$newImage .= $char;
					$newImage .= $char2;
				}
			} else {
				$newImage .= $char;
			}
		}
		$newImage .= @fread($imageIn, @filesize($this->image));
		fclose($imageIn);
		if (! $this->imageOut = @fopen($this->image,'wb')) {
			die("success=Error!\n\nCouldn't add annotation because \"" . $this->image . "\" isn't writable!");
		}
		fwrite($this->imageOut, $newImage);
		fclose($this->imageOut);
		
		return true;
	}

	
	
// end FNSaveJPEGHeader
}

// FNSaveDatabaseXMLBlock - concrete class to save to database, all annotations in single row as xml block
class FNSaveDatabaseXMLBlock extends FNSave {


	function FNSave() {

	}

	function saveNewAnnotation($fn_image, $fn_annotation) { 		
		GLOBAL $FN_DB, $FN_USER;
			
		$query = "delete from fn_annotations where file='$imagefile'";
		$FN_DB->query($query);
		$timestamp = time();
			
		$query = "INSERT INTO fn_annotations
		         (file, username, added, modified, annotation) VALUES 
		 		('$imagefile', '', '$timestamp', '$timestamp', '" .addslashes($xml). "')"; 
   		   	
		$FN_DB->query($query);
	}
	
	function updateExistingAnnotationByID() { }
	
	function deleteAnnotationByID() { }
	
}


?>