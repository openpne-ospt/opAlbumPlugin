<?php
/* FILE INFO */
/*
 * File: library.fnimage.inc.php
 * Author: Greg Elin
 * Version: 0.1
 * Updated: 04.14.2005
 * Description: FNImage Class consisting managing images and controls over actions.
 *
 */ 

/* CLASSES */

class FNImage {

	function FNImage() {
		GLOBAL $PERMISSIONS;
		displayDebug("Creating FNImage...", 3);
		$this->param = array();
		foreach ($PERMISSIONS as $key => $value) {
			$this->setFnImageParam($key, $value);
		}	
		
	}
	
	function setFnImageParam($param, $value) {
		$this->param[$param] = $value;
	}
	
	function fnImageParamExists($param) {
		$exists = false;
		if ($this->param[$param]) {
			$exists = true;
		}
		return $exists;
	}

	function doFnAction() {
			GLOBAL $FNSAVESTRATEGY, $FNRETRIEVESTRATEGY, $FNANNOTATIONFEEDSAVESTRATEGY, $FN_FOTONOTES_DATA_PATH_PREFIX;
			displayDebugParam($this->param, 3);
	
			switch ($this->param["action"]) {
			case "display":
				// GET  annotations for this image 
				// UPDATE DOM to include the modified image.
				displayDebug("action: display \n display image: ".$this->param["image"], 3);
				displayDebug("action: display \n display image_path: ".$this->param["image_path"], 3);
				
				// Get scaling information
				$this->getAnnotationScaling();
				$annotations = $this->getAnnotations();
				displayDebugParam($annotations,5);	
		
				$annotationsHTML = $this->getAnnotationsHTMLFromAnnotations($annotations);
				// PRINT annotationsHTML out to send back to client. This pushed back correct information.
				echo "displayHTML##$annotationsHTML##";
				
				break;
			
			case "add":
				// ADD annotation
										
				// prompt for password?
				if ($this->param['ADD'] == 'prompt' && $this->param['password'] <> $this->param['PASSWORD']) {
					die('success=denied');
				}
								
				// CREATE new FNAnnotation (FNImage)
				$fna = new FNImageAnnotation($this);

				// create a uuid for entry
				$id_number = getNewUUID();
				$this->setFnImageParam("annotationID",$id_number);
				$fna->setFnImageAnnotationParam("annotationID",$id_number);
				$id = preg_replace("#([^/]*\.jpg)#Umsi", "$id_number@$1", $this->param["image"]);
				displayDebug("id: $id", 3);
				displayDebug("annotationID: ".$this->param['annotationID'], 1);
				displayDebugParam($this->param['xml'], 1);				
				
				$this->param["xml"] = preg_replace("#<issued>.*</issued>#Umsi", "", $this->param["xml"]);
				$this->param["xml"] = preg_replace("#<created>.*</created>#Umsi", "", $this->param["xml"]);
				$this->param["xml"] = preg_replace("#<modified>.*</modified>#Umsi", "", $this->param["xml"]);
				//$link_alt = "<link rel=\"alternate\" type=\"text/html\"><![CDATA[$FN_FOTONOTES_DISPLAY_SCRIPT"."&entry_ID=$id_number"."]]></link> ";
				//$link_alt = "<link rel=\"alternate\" type=\"text/html\" href=\"$FN_FOTONOTES_DISPLAY_SCRIPT&amp;entry_ID=$id_number\" /> ";
				$timestamp = $this->param["timestamp"];
               	$this->param["xml"] = preg_replace("#<entry>#Umsi", "<entry><id>$id</id>$link_alt<issued>$timestamp</issued><created>$timestamp</created><modified>$timestamp</modified>", $this->param["xml"]);
				displayDebug("xml:", 3);
				displayDebugParam($this->param['xml'], 3);			
				
				// SET annotation's xml parameter from image
				$fna->setFnImageAnnotationParam("src_xml",$this->param["xml"]);			
				$fna_xml = $fna->generateFnImageAnnotationXml();  // also creates thumbnails currently
				
				// PERFORM save
				$results = $this->saveNewAnnotation($fna);
	
				displayDebug("and results here are: $results",4);
				
				if (!$results) {
					echo "success=error";
					break;
				}

				// SAVE *anotation feed file* in each place indicated by configuration settings
				if ($FNANNOTATIONFEEDSAVESTRATEGY["FNAnnotationFeedSaveXmlFile"]) {
					$fnafsave = new FNAnnotationFeedSaveXmlFile;
					$fnafsave->updateFNAnnotationFeed($this, $fna); 
				}

				echo 'success=ok';		
				break;
				
			case "modify":
				// UPDATE annotation
				// prompt for password?
				if ($this->param['MODIFY'] == 'prompt' && $this->param['password'] <> $this->param['PASSWORD']) {
					die('success=denied');
				}
				// CREATE new FNAnnotation (FNImage)
				displayDebug("action: modify", 2);
				$fna = new FNImageAnnotation($this);
				
				displayDebug("xml is: ". $this->param["xml"], 2);
				preg_match("#<id>(.*)</id>#", $this->param["xml"],$matches);
				displayDebug("annotation id to delete/update: ".$matches[1], 2);
				$fna->setFnImageAnnotationParam("id",$matches[1]);
				$this->setFnImageParam("annotationID",$matches[1]);
				$fna->setFnImageAnnotationParam("annotationID",$matches[1]);
				
				
				$id = preg_replace("#([^/]*\.jpg)#Umsi", $matches[1]."@$1", $this->param["image"]);
				//displayDebug("id: $id", 3);
				displayDebug("annotationID: ".$this->param['annotationID'], 1);
				displayDebugParam($this->param['xml'], 1);		
				
				// GET old date information
				$annotations = $this->getAnnotations();
				
				// LOOP through existing annotations to get old information
				for ($i=0; $i< count($annotations); $i++) {
					displayDebug("looking at id: ".$annotations[$i]['id'],4);
					if($annotations[$i]['id'] == $this->param['annotationID']) {
						// Found the correct annotation data
						$originalcreated = $annotations[$i]['created'];
						$originalissued = $annotations[$i]['issued'];
					}
				}
							
				displayDebug("originalcreated: $originalcreated; originalissued: $originalissued",4);
				
				$this->param["xml"] = preg_replace("#<issued>.*</issued>#Umsi", "", $this->param["xml"]);
				$this->param["xml"] = preg_replace("#<created>.*</created>#Umsi", "", $this->param["xml"]);
				$this->param["xml"] = preg_replace("#<modified>.*</modified>#Umsi", "", $this->param["xml"]);
				//$link_alt = "<link rel=\"alternate\" type=\"text/html\"><![CDATA[$FN_FOTONOTES_DISPLAY_SCRIPT"."&entry_ID=$id_number"."]]></link> ";
				//$link_alt = "<link rel=\"alternate\" type=\"text/html\" href=\"$FN_FOTONOTES_DISPLAY_SCRIPT&amp;entry_ID=$matches[1]\" /> ";
				$timestamp = $this->param["timestamp"];
               	$this->param["xml"] = preg_replace("#<entry>#Umsi", "<entry><id>$id</id>$link_alt<issued>$originalissued</issued><created>$originalcreated</created><modified>$timestamp</modified>", $this->param["xml"]);
				displayDebugParam($this->param['xml'], 3);			
				
				$fna->setFnImageAnnotationParam("src_xml",$this->param["xml"]);		
				// UPDATE annotationXMLBlock
				$this->param['annotationXMLBlock'] = preg_replace("#(<entry><id>".$this->param['annotationID']."</id>.*</entry>)#Umsi", $fna->param['src_xml'], $this->param['annotationXMLBlock']);
				$fna->param['annotationXMLBlock'] = preg_replace("#(<entry><id>".$this->param['annotationID']."</id>.*</entry>)#Umsi", $fna->param['src_xml'], $this->param['annotationXMLBlock']);
		
				// UPDATE *existing annotation*
				$results = $this->updateExistingAnnotationByID($fna);
				
				if (!$results) {
					echo "success=error";
					break;
				}
				
				/*
				// SAVE *anotation feed file* in each place indicated by configuration settings
				if ($FNANNOTATIONFEEDSAVESTRATEGY["FNAnnotationFeedSaveXmlFile"]) {
					$fnafsave = new FNAnnotationFeedSaveXmlFile;
					$fnafsave->saveFNAnnotationFeed($this, $fna); 
				}
				*/
				echo 'success=ok';
				break;
				
			case "delete":
				displayDebug("action: delete", 2);
				// prompt for password?
				if ($this->param['DELETE'] == 'prompt' && $this->param['password'] <> $this->param['PASSWORD']) {
					die('success=denied');
				}


				$fna = new FNImageAnnotation($this);
				displayDebug("xml is: ". $this->param["xml"], 2);
				preg_match("#<id>(.*)</id>#", $this->param["xml"],$matches);
				displayDebug("annotation id to delete: ".$matches[1], 2);
				$fna->setFnImageAnnotationParam("id",$matches[1]);
				
				// DELETE *existing annotation*
				$results = $this->deleteAnnotationByID($fna);

				echo 'success=ok';
				break;
				
			case "annotationfeed":
				displayDebug("action: annotationfeed", 2);
				break;
		}
	
	}
	

	function saveNewAnnotation($fna) {
		GLOBAL $FNSAVESTRATEGY, $FNRETRIEVESTRATEGY, $FNANNOTATIONFEEDSAVESTRATEGY, $FN_FOTONOTES_DATA_PATH_PREFIX;
			
		// SAVE *annotation* in each place indicated by configuration settings
		if ($FNSAVESTRATEGY["FNSaveDatabaseRows"]) {
			$fnasave = new FNSaveDatabaseRows;
			$results['FNSaveDatabaseRows'] = $fnasave->saveNewAnnotation($this, $fna);  // $fna_xml == $fna->param["xml_entryinfo"]
		}
		if ($FNSAVESTRATEGY["FNSaveDatabaseXMLBlock"]) {
			$fnasave = new FNSaveDatabaseXMLBlock;
			$results['FNSaveDatabaseXMLBlock'] = $fnasave->saveNewAnnotation($this, $fna);  // $fna_xml == $fna->param["xml_entryinfo"]
		}
		if ($FNSAVESTRATEGY["FNSaveJPEGHeader"]) {
			$fnasave = new FNSaveJPEGHeader;
			$results['FNSaveJPEGHeader'] = $fnasave->saveNewAnnotation($this, $fna);  // $fna_xml == $fna->param["xml_entryinfo"]
		}
		if ($FNSAVESTRATEGY["FNSaveTextFile"]) {
			$fnasave = new FNSaveTextFile;
			$results['FNSaveTextFile'] = $fnasave->saveNewAnnotation($this, $fna);  // $fna_xml == $fna->param["xml_entryinfo"]
		}	
		
		return $results;
	}

	function updateExistingAnnotationByID($fna) {
		GLOBAL $FNSAVESTRATEGY, $FNRETRIEVESTRATEGY, $FNANNOTATIONFEEDSAVESTRATEGY, $FN_FOTONOTES_DATA_PATH_PREFIX;

		// UPDATE *existing annotation* in each place indicated by configuration settings
		if ($FNSAVESTRATEGY["FNSaveDatabaseRows"]) {
			$fnasave = new FNSaveDatabaseRows;
			$results['FNSaveDatabaseRows'] = $fnasave->updateExistingAnnotationByID($this, $fna);  
		}
			
		if ($FNSAVESTRATEGY["FNSaveDatabaseXMLBlock"]) {
			$fnasave = new FNSaveDatabaseXMLBlock;
			$results['FNSaveDatabaseXMLBlock'] = $fnasave->updateExistingAnnotationByID($this, $fna);  // $fna_xml == $fna->param["xml_entryinfo"]
		}
		if ($FNSAVESTRATEGY["FNSaveJPEGHeader"]) {
			$fnasave = new FNSaveJPEGHeader;
			$results['FNSaveDatabaseRows'] = $fnasave->updateExistingAnnotationByID($this, $fna);  // $fna_xml == $fna->param["xml_entryinfo"]
		}
		if ($FNSAVESTRATEGY["FNSaveTextFile"]) {
			$fnasave = new FNSaveTextFile;
			$results['FNSaveTextFile'] = $fnasave->updateExistingAnnotationByID($this, $fna);  // $fna_xml == $fna->param["xml_entryinfo"]
		}	
		
		return $results;
	}

	function deleteAnnotationByID($fna) {
		GLOBAL $FNSAVESTRATEGY, $FNRETRIEVESTRATEGY, $FNANNOTATIONFEEDSAVESTRATEGY, $FN_FOTONOTES_DATA_PATH_PREFIX;

		// DELETE *existing annotation* as indicated by configuration settings
		if ($FNSAVESTRATEGY["FNSaveDatabaseRows"]) {
			$fnasave = new FNSaveDatabaseRows;
			$results['FNSaveDatabaseRows'] = $fnasave->deleteAnnotationByID($this, $fna);  
		}
			
		if ($FNSAVESTRATEGY["FNSaveDatabaseXMLBlock"]) {
			$fnasave = new FNSaveDatabaseXMLBlock;
			$results['FNSaveDatabaseXMLBlock'] = $fnasave->deleteAnnotationByID($this, $fna);  // $fna_xml == $fna->param["xml_entryinfo"]
		}
		if ($FNSAVESTRATEGY["FNSaveJPEGHeader"]) {
			$fnasave = new FNSaveJPEGHeader;
			$results['FNSaveJPEGHeader'] = $fnasave->deleteAnnotationByID($this, $fna);  // $fna_xml == $fna->param["xml_entryinfo"]
		}
		if ($FNSAVESTRATEGY["FNSaveTextFile"]) {
			$fnasave = new FNSaveJPEGHeader;
			$results['FNSaveTextFile'] = $fnasave->deleteAnnotationByID($this, $fna);  // $fna_xml == $fna->param["xml_entryinfo"]
		}	
		
		return $results;
	}
	
	function getAnnotationScaling() {	
		// The $fn_image must be passed by reference because this functions sets parameters. 
		$DHTML_MAXWIDTH = $this->param['width'];
		$DHTML_MAXHEIGHT = $this->param['height'];

		$size = getimagesize($this->param['image_path']);
		displayDebug('getAnnotationScaling size', 4);
		displayDebugParam($size, 4);
		$ratioWidth = $DHTML_MAXWIDTH /  $size[0];
		$ratioHeight = $DHTML_MAXHEIGHT / $size[1];

		if($ratioHeight>$ratioWidth){$ratio=$ratioWidth;}else{$ratio=$ratioHeight;}
		if($ratio>1){$ratio=1;}
		$this->setFnImageParam('scalefactor', $ratio);
	}
		
	function getAnnotations() {
		GLOBAL $FNSAVESTRATEGY, $FNRETRIEVESTRATEGY, $FNANNOTATIONFEEDSAVESTRATEGY, $FN_FOTONOTES_DATA_PATH_PREFIX;
			
		// RETRIEVE *annotations* 
		if ($FNRETRIEVESTRATEGY["FNRetrieveDatabaseRows"]) {
			$fnaretrieve = new FNRetrieveDatabaseRows;
			$annotations = $fnaretrieve->getAnnotations($this);  // $fna_xml == $fna->param["xml_entryinfo"]
		}
		if ($FNRETRIEVESTRATEGY["FNRetrieveDatabaseXMLBlock"]) {
			$fnaretrieve = new FNRetrieveDatabaseXMLBlock;
			$annotations = $fnaretrieve->getAnnotations($this);  // $fna_xml == $fna->param["xml_entryinfo"]
		}
		if ($FNRETRIEVESTRATEGY["FNRetrieveJPEGHeader"]) {
			$fnaretrieve = new FNRetrieveJPEGHeader;
			$annotations = $fnaretrieve->getAnnotations($this);  // $fna_xml == $fna->param["xml_entryinfo"]
			
		}
		if ($FNRETRIEVESTRATEGY["FNRetrieveTextFile"]) {
			$fnaretrieve = new FNRetrieveTextFile;
			$annotations = $fnaretrieve->getAnnotations($this);  // $fna_xml == $fna->param["xml_entryinfo"]
		}				
		return $annotations;
	}
	
	function getAnnotationsHTMLFromAnnotations ($annotationsArray) {
		GLOBAL $DHTML_MAXWIDTH, $DHTML_MAXHEIGHT; 
		// Should not caluculate max width and height - 
		
		displayDebugParam($annotations, 3);
		$content = null;
		$content .= "\n<!--module_fotonotesmod-->\n";
		// canvas offsets
		
		$this->setFnImageParam('canvasOffSetTop', 20);
		$this->setFnImageParam('canvasOffSetBottom', 0);
		$this->setFnImageParam('canvasOffSetLeft', 0);
		$this->setFnImageParam('canvasOffSetRight', 0);
		$this->setFnImageParam('canvasHeight', $this->param['height']+$this->param['canvasOffSetTop']+$this->param['canvasOffSetBottom']);
		$this->setFnImageParam('canvasWidth', $this->param['width']+$this->param['canvasOffSetLeft']+$this->param['canvasOffSetRight']);
		
		
		$content .= "<div id=\"fn-canvas-id-".$this->param['image']."\" class=\"fn-canvas fn-container-active\" style=\"width: ".$this->param['canvasWidth']."px; height: ".$this->param['canvasHeight']."px;\">\n";
		
		//$content .= "<div class=\"errorMessage\">{%ERRORS%}</div>";	

		$content .= "<div id=\"unique-id-".$this->param['image']."\" class=\"fn-container fn-container-active\" style=\"width: ".$this->param['width']."px; height: ".$this->param['height']."px; top:".$this->param['canvasOffSetTop']."px; left:".$this->param['canvasOffSetLeft']."px;\">\n";
		$content .= "<img src=\"".$this->param['image']."\" width=\"".$this->param['width']."\" height=\"".$this->param['height']."\" alt=\"".$this->param['alt']."\" style=\"".$this->param['style']."\" />\n";
	 	$content .= "<span class=\"fn-scalefactor\" title=\"".$this->param['scalefactor']."\"></span>";
	 	
		for ($i=0; $i<count($annotationsArray); $i++) {
			$annotations = null;
			$annotations = $annotationsArray[$i];
		
			$content .= "\n\n<!-- ******* ANNOTATION $i : $annotations[content] ********* -->\n";
			$content .= <<<EOF
        <div class="fn-area" style="left: $annotations[upperleftx]px; top: $annotations[upperlefty]px; width: $annotations[width]px; height: $annotations[height]px; border-color: $annotations[bordercolor];">  
			<div class="fn-note">
				<span class="fn-note-created">$annotations[createddtg]</span>
				<span class="fn-note-title">$annotations[title]</span>
				<span class="fn-note-content">$annotations[content]</span>
				<span class="fn-note-author">$annotations[author]</span>
				<span class="fn-note-userid" style="display:none;">$annotations[userid]</span>
				<span class="fn-note-id" title="$annotations[id]"></span>
			</div>
			<div class="fn-area-innerborder-left"></div>
			<div class="fn-area-innerborder-right"></div>
			<div class="fn-area-innerborder-top"></div>
			<div class="fn-area-innerborder-bottom"></div>
			
		</div>
		<!-- end fn-area -->
		
EOF;

		}
		/* Bordercolor UI elements have been removed fn div elements. See fnclient-0.4.0.bordercolor for elements.*/
		$content .= <<<EOF
	
	<div class="fn-controlbar fn-controlbar-active">
			<!--span class="fn-controlbar-logo"></span-->
			<span class="fn-controlbar-credits"></span>
			<span class="fn-controlbar-del-inactive"></span>
			<span class="fn-controlbar-edit-inactive"></span>
			<span class="fn-controlbar-add-inactive"></span>
			<span class="fn-controlbar-toggle-inactive"></span>
		</div>

	 	<form class="fn-editbar fn-editbar-inactive" name="fn_editbar" id="fn_editbar">
	 				
        		<div class="fn-editbar-fields">
       			<p>TITLE:</p>
       			<input type="input" class="fn-editbar-title" name="title" value="default" />
	   			<input type="hidden" class="fn-editbar-author" name="author" value="$annotations[author]" />
			    <input type="hidden" class="fn-editbar-userid" name="userid" value="$annotations[userid]" />
			    <input type="hidden" class="fn-editbar-entry_id" name="entry_ID" value="$annotations[id]" />
			    <input type="hidden" class="fn-editbar-border-color" name="border_color" value="#FE0000" />
			    
	  		</div>
	  
	  		<div class="fn-editbar-fields">
	  			<p>CONTENT:</p>
				<textarea class="fn-editbar-content" name="content"></textarea>
	  		</div>
	  		<div class="fn-editbar-fields">
	  			<span class="fn-editbar-ok"></span>
				<span class="fn-editbar-cancel"></span>
			</div>
	 	</form>
	 	
	</div>

EOF;
		
		$content .= "\n</div><!--close fn-canvas-->\n";
		$content .= "\n<!--module_fotonotesmod-->\n";
		return $content;	
	}
	


// from previous code 
	function writeXMLV03($newxml, $newtitle) {
		// do I have the correct global values? I need to get from elsewhere
		// What we need to know:
		//		timestamp, full image path, filename, localpath, add time, modify, and if we are deleting.
		// We can look at all information 
		global $timestamp, $imagepath, $filename, $LOCALPATH, $ADD, $MODIFY, $DELETE;
		
		
		if (! preg_match("#<\?xml#Umsi", $newxml)) {
			// if xml doesn't have xml wrapper text, add it now. Otherwise it should have the necessary header infromation.
			if ($newtitle == '') {
				$newtitle = basename($filename);
			}		
			$header = '<?xml version="1.0" encoding="UTF-8"?>';
			$header .= '<feed version="0.3" xmlns="http://purl.org/atom/ns#" xmlns:dc="http://purl.org/dc/elements/1.1/" xml:lang="en" xmlns:fn="http://fotonotes.net/protocol/fotonotes_0.2">';
			$header .= '<copyright>Copyright (c) 2004, Greg Elin</copyright>';
			$header .= "<id>$imagepath</id>";
			$header .= "<modified>$timestamp</modified>";
			$header .= "<title>$newtitle</title>";
			$header .= '<link rel="alternate" type="text/html" href="' . $LOCALPATH . '?p=' . $filename . '" />';
			$xml = $header . $newxml . "</feed>";
		}
		else {	
			// newxml already has necessary prefix info.
			$entries = explode("<entry>", $newxml);		
			// update modification date of overall xml block (that which comes before the first <entry>
			$entries[0] = preg_replace("#<modified>.*</modified>#Umsi", "<modified>$timestamp</modified>", $entries[0]);
			if ($newtitle <> '') {
				// If a new title has been added to the overall image, update new title for image.
				$entries[0] = preg_replace("#<title>.*</title>#Umsi", "<title>$newtitle</title>", $entries[0]);		
			}
			// resemble entire xml block
			$newxml = implode("<entry>", $entries);
			$xml = $newxml;		
		}
		// Get rid of the <fn:permissions> that were passed to the client before saving the information.
		// The <fn:permissions> is set each time the information is set to the client because we can't know who
		// will be eding the file.
		$xml = preg_replace("#<fn:permissions>.*</fn:permissions>#", "", $xml);
		
		// Send of the entire XML block to get written into the header and replace what is there.
		$this->writeJPEGHeaders($xml);	
	}

}

?>