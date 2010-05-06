<?php
/* FILE INFO */
/*
 * File: library.fn_makeimage.inc.php
 * Author: Greg Elin
 * Version: 0.1
 * Updated: 04.14.2005
 * Description: Contains a function to make an image from a larger image.
 * Notes: Should be required by fotonotes.php, but should be loaded after any function overrides are.
 *
 */ 
 
 /* FUNCTIONS */
 
if (!function_exists('fn_makeImage')) {
	function fn_makeImage ($photographName, $selectionToPhotographRectangle = null, $zoom=1,
		$width=null, $height=null, $filepath=null) {
		$imageSpecs = getimagesize($photographName);
		$extension = substr($photographName, -4);
		switch(strtolower($extension)) {
			case '.jpg':
				$image = imageCreateFromJpeg($photographName );
				break;
			case '.png':
				$image = imageCreateFrompng($photographName );
				break;
			case '.gif':
				$image = imageCreateFromGif( $photographName );
				break;
			default:
				$image = imageCreateFromJpeg($photographName );
				break;
		}
		
		// try to split bounding box on comma ','
		$coordiantesArray = split( ",", $selectionToPhotographRectangle);
		if (!$coordiantesArray[1]) {
			// try to split on space ' ' 
			$coordiantesArray = split( " ", $selectionToPhotographRectangle);
		}
		if (!$coordiantesArray[1]) {
			// still no split? use whole image
			$coordiantesArray = array(0,0,$imageSpecs[0], $imageSpecs[1]);
		}
		// translate boundingbox's x1,y1,x2,y2 to x1,y1,w,h
		$fn_x1 = $coordiantesArray[0];
		$fn_y1 = $coordiantesArray[1];
		$fn_w = $coordiantesArray[2]- $coordiantesArray[0];
		$fn_h = $coordiantesArray[3]- $coordiantesArray[1];
		
		if (!$zoom) {
			// no zoom? give full size
			$zoom = 1;
		}
		displayDebug("selection: $selectionToPhotographRectangle", 3);
		displayDebug("\n$fn_x1 | $fn_y1 | $fn_w | $fn_h ", 3);
		// adjust for width and height params if passed
		if ($width && !$height) {
			$height = ($width * $imageSpecs[1]) / $imageSpecs[0];
		} else {
			if (!$width) {
				$width = $fn_w * $zoom;
			}
			if (!$height) {
				$height = $fn_h * $zoom;
			}
		}
		
		// let's grabbed the region
		#$croppedImage=ImageCreate($width, $height);
		$croppedImage=imagecreatetruecolor($width, $height);
		
		imagecopyresized( $croppedImage, $image, 0, 0, $coordiantesArray[0], $coordiantesArray[1], 
		$width, $height, $fn_w, $fn_h );
		$image = $croppedImage;
	
		if ($filepath) {
			// output to file
			switch(strtolower($extension)) {
			// send to browser with proper header
			case '.jpg':
				imagejpeg($image, $filepath);
				break;
			case '.png':
				imagepng($image, $filepath);
				break;
			case '.gif':
				imageGif($image, $filepath);
				break;
			default:
				imagejpeg($image, $filepath);
				break;
			}
		} else {
			// output to browser
			switch(strtolower($extension)) {
			// send to browser with proper header
			case '.jpg':
				header ('Content-Type: image/jpeg');
				imagejpeg($image);
				break;
			case '.png':
				header ('Content-Type: image/png');
				imagepng($image);
				break;
			case '.gif':
				header ('Content-Type: image/gif');
				imageGif($image);
				break;
			default:
				header ('Content-Type: image/jpeg');
				imagejpeg($image);
				break;
			}
		}
		// delete image from memory
		imagedestroy($image);
	
		return true;
	}
}	
?>