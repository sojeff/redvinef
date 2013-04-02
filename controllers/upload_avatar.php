<?php

function function_upload_avatar($userguid, $file_url, $crop = 'no') {
    $allowedExtensions = array("image/jpg", "image/jpeg", "image/gif", "image/png", "image/pjpeg");
    $path70 = SITE_ROOT . "/controllers/img/avatar_70x70/";
    $path32 = SITE_ROOT . "/controllers/img/avatar_32x32/";
    $tempimagefolderpath = SITE_ROOT . "/userdata/files/tempupload/";
    $file60 = $path70 . $userguid . '.jpg';
    $file36 = $path32 . $userguid . '.jpg';
    $tempimage_pathfilename = $tempimagefolderpath . $userguid . '.jpg';
    
//make a directory if there isn't one
    if (!file_exists($tempimagefolderpath)) {
        $result = mkdir($tempimagefolderpath);
        //if there is a file with tempupload with no extension, delete it and make the directory
    } elseif (!is_dir($tempimagefolderpath)) {
        $resultUNLINK = unlink($tempimagefolderpath);
        $result = mkdir($tempimagefolderpath);
    } else {
        //$return = "ELSE (must already have a tempupload folder.)";
    }
    //check uploaded file if this is not a cropping
    if ($crop === 'no') {
        if (!in_array(strtolower($_FILES[$file_url]["type"]), $allowedExtensions) ) {
            return "Incorrect file type uploaded. " . '"' . $_FILES[$file_url]["type"] . '"' . " files not accepted. Please upload an image file.";
        }
        if ($_FILES[$file_url]["error"] > 0 ) {
            return "Return Code: " . $_FILES[$file_url]["error"] . "<br />";
        } elseif ($_FILES[$file_url]["size"] > 5000000 ) {
            return "File size must be less than 5 Megabytes.";
        }
    }
    //file name comes from uploaded file or from the temp folder
    $crop === 'no' ? $filename = $_FILES[$file_url]['name'] : $filename = $userguid . ".jpg";
    include_once("../libraries/SimpleImage.php");



    //       $sitespecific = "/var/www/redvinef/";
    //  $tempimage = $sitespecific . "userdata/files/tempupload/" . $user->userguid . ".jpg";
if ($crop==='yes'){
    $im_w = $_POST['w'];
    $im_h = $_POST['h'];
    $origin_x = $_POST['x'];
    $origin_y = $_POST['y'];

    // destination image
    $new_image = imagecreatetruecolor($im_w, $im_h);
    //$temp= "/var/www/redvinef/userdata/files/tempupload/" . $user->userguid . ".jpg";
    $imagecreatefromjpeg = imagecreatefromjpeg($tempimage_pathfilename);
    imagecopyresampled($new_image, $imagecreatefromjpeg, 0, 0, $origin_x, $origin_y, $im_w, $im_h, $im_w, $im_h);
    imagejpeg($new_image, $tempimage_pathfilename, 100); 
}


    //imagedestroy($new_image);
    //$imgData = file_get_contents($_FILES[$file_url]['tmp_name']);
    // $crop=='no' ? $imgData = file_get_contents($_FILES[$file_url]['tmp_name']) : $imgData=$new_image;
    if ($crop === 'no') {
        $image = new SimpleImage();
        $image->load($_FILES[$file_url]['tmp_name']);
        $image->resizeToWidth(700);
        $image->save($tempimage_pathfilename, IMAGETYPE_JPEG, 100);
        
        
        $image->load($_FILES[$file_url]['tmp_name']);
        $image->resizeToWidth(60);
        $image->save($tempimage_pathfilename . ".tmp.jpg", IMAGETYPE_JPEG, 100);
        
        
       // $image->load($_FILES[$file_url]['tmp_name']);
       // $image->resizeToWidth(36);
       // $image->save($file36, IMAGETYPE_JPEG, 100);
    }
    
      if ($crop === 'yes') {
        $image = new SimpleImage();
        $image->load($tempimage_pathfilename);
        $image->resizeToWidth(60);
        $image->save($tempimage_pathfilename . ".tmp.jpg", IMAGETYPE_JPEG, 100);
        
        
       // $image->load($tempimage_pathfilename);
       // $image->resizeToWidth(36);
       // $image->save($file36, IMAGETYPE_JPEG, 100);
    }


    if (!$imgData = file_get_contents($tempimage_pathfilename . ".tmp.jpg")) {
        return "There was an error placing your image in the database.  Please upload your image again.";
    }
    //$imgData = file_get_contents($tempimage_pathfilename);
    $imgData = mysql_real_escape_string($imgData);

    $sql = "update users set avatar = '{$imgData}', avatar_image_type = 'image/jpeg' where userguid = '$userguid' limit 1 ";

    mysql_query($sql);

    //$return= $return . " <BR>You have uploaded a file.";

    return ;//"Your file, $filename, was uploaded successfully."; //. "  " . $filered; // . " was it cleared?";
}
?> 