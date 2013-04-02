<?php
function deleteDirectory($dir) {
    if (!file_exists($dir)) return true;
    if (!is_dir($dir) || is_link($dir)) return unlink($dir);
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') continue;
            if (!deleteDirectory($dir . "/" . $item)) {
                chmod($dir . "/" . $item, 0777);
                if (!deleteDirectory($dir . "/" . $item)) return false;
            };
        }
        return rmdir($dir);
    } 


function upload_documents($userguid, $upload_input_field_name) {
//check file type ?
  //$return= deleteDirectory(SITE_ROOT . "/userdata/files/" .$userguid);
   // deleteUserfolderInUserdataFiles($userguid,'ewewers');
  //return $return . " this is done? ";
    //  $return2 = deleteDirectory(SITE_ROOT . "/userdata/files/700");
     //chmod(SITE_ROOT . "/userdata/files/$userguid/",0777);
    
   if (($_FILES[$upload_input_field_name]["size"] > 10000000) || ($_FILES[$upload_input_field_name]["error"] === 1)) {
        return "File size must be less than 10 Megabytes.";
    } 
    
    if ($_FILES[$upload_input_field_name]["error"] > 0) {
        if ($_FILES[$upload_input_field_name]["error"] == 4) {return "You must select a document to upload with the browse button.";}
        else {return "Return Code: " . $_FILES[$upload_input_field_name]["error"] . "<br />";}
    } 

    $allowedExtensions = array("application/pdf", "application/x-pdf", "image/jpg", "image/jpeg", "image/gif", "image/png", "image/pjpeg", "image/bmp", "image/tiff" );
    $file_type=$_FILES[$upload_input_field_name]["type"];
    if (!in_array($file_type, array_map('strtolower',$allowedExtensions)) ) {
            return implode (",", $allowedExtensions) . "Incorrect file type uploaded. " . '"' . $file_type . '"' . " files not accepted. Please contact support if you feel you received this message in error.";
        }
    $documentpath = SITE_ROOT . "/userdata/files/" . $userguid . "/";

//make a directory if there isn't one
    if (!file_exists($documentpath)) {
        $result = mkdir($documentpath,0777);
        //$return = "the result of mkdir was " . $result; // $documentpath . ' does not exists <br>'; // do upload logic here
//if there is a file with the guid with no extension, delete it and make the directory
    } elseif (!is_dir($documentpath)) {
        $resultUNLINK = unlink($documentpath);
        $result = mkdir($documentpath,0777);
        //$return = "The result of unlink was " . $resultUNLINK . " and the result of mkdir was " . $result; // $documentpath . ' does not exists <br>'; // do upload logic here
    } else {
        //$return = "ELSE (must already have a folder.)";
    }
	$file_name_clean = str_replace(" ","",$_FILES[$upload_input_field_name]['name']);
	$file_name_clean = str_replace("-","",$file_name_clean);
	$file_name_clean = str_replace(",","",$file_name_clean);
	$file_name_clean = str_replace("/","",$file_name_clean);
	$file_name_clean = str_replace("\\","",$file_name_clean);
	$file_name_clean = str_replace("&","",$file_name_clean);
	$file_name_clean = str_replace("?","",$file_name_clean);
    $file_contents = file_get_contents($_FILES[$upload_input_field_name]['tmp_name']);
    if (!file_put_contents($documentpath . $file_name_clean, $file_contents)) {
        $return = "File: " . $_FILES[$upload_input_field_name]['name'] . " was not uploaded to directory<BR>";
        return $return;
    } else {
        //chmod($documentpath . $_FILES[$upload_input_field_name]['name'],0777);
        return "File: " . $_FILES[$upload_input_field_name]['name'] . " was uploaded successfully<BR>";
    }


    return $return = '';//. "<br>" . $documentpath;
}

?> 