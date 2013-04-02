<?php

function image_resize($imgURL, $src, $width, $height, $crop=0){

  if(!list($w, $h) = getimagesize($imgURL)) return $src;

  $type = strtolower(substr(strrchr($imgURL,"."),1));
  if($type == 'jpeg') $type = 'jpg';
  switch($type){
    case 'bmp': $img = imagecreatefromwbmp($imgURL); break;
    case 'gif': $img = imagecreatefromgif($imgURL); break;
    case 'jpg': $img = imagecreatefromjpeg($imgURL); break;
    case 'png': $img = imagecreatefrompng($imgURL); break;
    default : return $src;
  }
  $recrop = false;
  if($w > $width and $h > $height and $w > $h)
  	{
  	$crop = false;
  	$width2 = $width;
  	$height2 = $height;
  	if($w/$h < 2)
  		$recrop = true;
  	}
  else
  	$crop = true;
  
  // resize
  if($crop){
    if($w < $width and $h < $height) return $src;
    $ratio = $width/$w;  //450/192=
    $h = $height / $ratio;
    $x = ($w - $width / $ratio) / 2;
    $y = ($h - $height / $ratio) / 2;
    $w = $width / $ratio;
  }
  else{
    if($w < $width and $h < $height) return $src;
    $ratio = $width/$w;
    $width = $w * $ratio;
    $height = $h * $ratio;
    $x = 0;
    $y = 0;
  }

  $new = imagecreatetruecolor($width, $height);

  // preserve transparency
  if($type == "gif" or $type == "png"){
    imagecolortransparent($new, imagecolorallocatealpha($new, 0, 0, 0, 127));
    imagealphablending($new, false);
    imagesavealpha($new, true);
  }

  $imgcreated = imagecopyresampled($new, $img, 0, 0, $x, $y, $width, $height, $w, $h);
  
/* this part of code is not working
  if($type == 'bmp')
  	{
	$imgnew = 'tempimg.bmp';
  	$imgnewtf = image2wbmp($new, $imgnew,100);
  	}
  else if($type == 'gif')
  	{
	$imgnew = 'tempimg.gif';
    $imgnewtf = imagegif($new, $imgnew,100);
    }
  else if($type == 'png')
  	{
	$imgnew = 'tempimg.png';
  	$imgnewtf = imagepng($new, $imgnew,100);
  	}
  else
  	{
	$imgnew = 'tempimg.jpg';
    $imgnewtf = imagejpeg($new, $imgnew,100);
    }
*/
  
  //if($imgnewtf and 1==2)
 // 	return mysql_real_escape_string(file_get_contents($imgnew));
 // else
  	return $src;
}
?>