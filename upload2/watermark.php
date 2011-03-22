<?php
function watermark($des,$scr,$namefile){
		header("Content-type: image/jpeg");
		$imagesource = $des; 
	
		$filetype = strtolower(substr($imagesource,strlen($imagesource)-4,4));
		if($filetype == ".gif") $image = @imagecreatefromgif($imagesource);
		if($filetype == ".jpg" || $filetype == "jpeg") $image = @imagecreatefromjpeg($imagesource);
		if($filetype == ".png") $image = @imagecreatefrompng($imagesource);
		
		$watermark = @imagecreatefrompng($scr); // file watermark
		list($watermark_width, $watermark_height) = getimagesize($scr);
		
		$watermark_p = imagecreatetruecolor(150, 25);
		imagecopyresampled($watermark_p, $watermark, 0, 0, 0, 0, 150, 25, $watermark_width, $watermark_height);
		$watermarkp_width = imagesx($watermark_p);  
		$watermarkp_height = imagesy($watermark_p);
		
		$size = getimagesize($imagesource);
		$dest_x = $size[0] - $watermarkp_width - 5;  
		$dest_y = $size[1] - $watermarkp_height - 5;   
		
		if(imagecopymerge($image, $watermark_p, $dest_x, $dest_y, 0, 0, $watermarkp_width, $watermarkp_height, 90)){
			imagejpeg($image,$namefile);
			imagedestroy($image);
			imagedestroy($watermark);
			imagedestroy($watermark_p);
		}
}		
?>
