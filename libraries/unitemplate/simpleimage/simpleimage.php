<?php
error_reporting(E_ALL);
class SimpleImage {
   var $image;
   var $image_type;
   var $watermark;
 	
	function SimpleImage( $watermark='' ){
		$this->watermark=$watermark;
	}

	function load($filename) {
	  $image_info = getimagesize($filename);
	  $this->image_type = $image_info[2];
	  if( $this->image_type == IMAGETYPE_JPEG ) {
		 $this->image = imagecreatefromjpeg($filename);
	  } elseif( $this->image_type == IMAGETYPE_GIF ) {
		 $this->image = imagecreatefromgif($filename);
	  } elseif( $this->image_type == IMAGETYPE_PNG ) {
		 $this->image = imagecreatefrompng($filename);
	  }
	}
	function save($filename, $sharpen=true, $image_type=IMAGETYPE_JPEG, $compression=95, $permissions=null) {
		if($this->watermark) $this->watermark_();
		if($sharpen) $this->image = $this->UnsharpMask($this->image, 100, 0.3, 5);
	  if( $image_type == IMAGETYPE_JPEG ) {
		 imagejpeg($this->image,$filename,$compression);
	  } elseif( $image_type == IMAGETYPE_GIF ) {
		 imagegif($this->image,$filename);         
	  } elseif( $image_type == IMAGETYPE_PNG ) {
		 imagepng($this->image,$filename);
	  }   
	  if( $permissions != null) {
		 chmod($filename,$permissions);
	  }
	}
	function output($image_type=IMAGETYPE_JPEG) {
	  if( $image_type == IMAGETYPE_JPEG ) {
		 imagejpeg($this->image);
	  } elseif( $image_type == IMAGETYPE_GIF ) {
		 imagegif($this->image);         
	  } elseif( $image_type == IMAGETYPE_PNG ) {
		 imagepng($this->image);
	  }   
	}
	function getWidth() {
	  return imagesx($this->image);
	}
	function getHeight() {
	  return imagesy($this->image);
	}
	function resizeToHeight($height) {
	  $ratio = $height / $this->getHeight();
	  $width = $this->getWidth() * $ratio;
	  $this->resize($width,$height);
	}
	function resizeToWidth($width) {
	  $ratio = $width / $this->getWidth();
	  $height = $this->getheight() * $ratio;
	  $this->resize($width,$height);
	}
	function scale($scale) {
	  $width = $this->getWidth() * $scale/100;
	  $height = $this->getheight() * $scale/100; 
	  $this->resize($width,$height);
	}
	function resize($width,$height) {
	  $new_image = imagecreatetruecolor($width, $height);
	  imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
	  $this->image = $new_image;   
	}      
	function crop($from_x,$from_y,$width,$height){
	  $new_image = imagecreatetruecolor($width,$height);
	  imagecopyresampled($new_image, $this->image, 0, 0, $from_x, $from_y, $width, $height, $width, $height);
	  $this->image = $new_image;
	}

	function resize_w($watermark, $width, $height){
		$src_w = $this->getWidth($watermark);
		$src_h = $this->getHeight($watermark);			
		$new_image = imagecreatetruecolor(100, 20);
	  imagecopyresized  (
			$watermark, $watermark, 
			0, 0, 0, 0, 
			100,
			20,
			$src_w, 
			$src_h
		  );
		  return $watermark;   
	}	

	function watermark_(){
		$watermark = @imagecreatefrompng($this->watermark);
		$imagewidth = $this->getWidth($this->image);
		$imageheight = $this->getHeight($this->image);  

		if( $this->getWidth($this->image)*0.9 > imagesx($watermark) ){
			$watermarkwidth =  imagesx($watermark);
			$watermarkheight =  imagesy($watermark);
			$startwidth = (($imagewidth - $watermarkwidth)/2);
			$startheight = (($imageheight - $watermarkheight)/2);
			imagecopy($this->image, $watermark,  $startwidth, $startheight, 0, 0, $watermarkwidth, $watermarkheight);
			imagedestroy($watermark);
		}
	}
	
	function applyReflection($reflectionHeight=50, $startingTrans=30, $R=255, $G=255, $B=255){
		$w = $this->getWidth($this->image);
		$h = $this->getHeight($this->image);
		$rH = $reflectionHeight;
		$tr = $startingTrans;
		$div = 1;
		$im = $this->image;

		$li = imagecreatetruecolor($w, 1);
		$bgc = imagecolorallocate($li, $R, $G, $B); // Background color
		imagefilledrectangle($li, 0, 0, $w, 1, $bgc);

		$new = imagecreatetruecolor($w, $h+$rH+$div);
		imagefilledrectangle($new, 0, 0, $w, $h+$div+$rH, $bgc);
		imagecopyresampled($new,$this->image,0,0,0,0,$w,$h,$w,$h);
		
		$bg = imagecreatetruecolor($w, $rH);

		$im2 = imagecreatetruecolor($w,$h);
		for($i = 0;$i < $w; $i++)
		{
			for($j = 0;$j < $h; $j++)
			{
				$ref = imagecolorat($im,$i,$j);
				imagesetpixel($im2,$w - $i,$h - $j,$ref);
			}
		}
		$im = $im2;
		
		imagecopyresampled($bg, $im, 0, 0, 0, 0, $w, $h, $w, $h);
		
		$im = $bg;
		$bg = imagecreatetruecolor($w, $rH);
		
		for ($x = 0; $x < $w; $x++) {
			imagecopy($bg, $im, $x, 0, $w-$x, 0, 1, $rH);
		} 
		
		$im = $bg;
		$in = 100/$rH;
		
		for($i=0; $i<=$rH; $i++){
			if($tr>100) $tr = 100;
			imagecopymerge($im, $li, 0, $i, 0, 0, $w, 1, $tr);
			$tr+=$in;
		}
		
		imagecopymerge($im, $li, 0, 0, 0, 0, $w, $div, 100); // Divider
		
		imagecopyresampled($new,$im,0,$h,0,0,$w,$rH,$w,$rH);
		
		$this->image = $new;
		imagedestroy($li);
		imagedestroy($im2);
	}
	
	function flip()
	{
		$wid = $this->getWidth($this->image);
		$hei = $this->getHeight($this->image);
		$im2 = imagecreatetruecolor($wid,$hei);
		
		for($i = 0;$i < $wid; $i++)
		{
			for($j = 0;$j < $hei; $j++)
			{
				$ref = imagecolorat($im,$i,$j);
				imagesetpixel($im2,$i,$hei - $j,$ref);
			}
		}
		
		$this->image = $im2;
	}

	function UnsharpMask($img, $amount, $radius, $threshold)    { 
	
	////////////////////////////////////////////////////////////////////////////////////////////////  
	////  
	////                  Unsharp Mask for PHP - version 2.1.1  
	////  
	////    Unsharp mask algorithm by Torstein Hønsi 2003-07.  
	////             thoensi_at_netcom_dot_no.  
	////               Please leave this notice.  
	////  
	///////////////////////////////////////////////////////////////////////////////////////////////  
	
	
	
		// $img is an image that is already created within php using 
		// imgcreatetruecolor. No url! $img must be a truecolor image. 
	
		// Attempt to calibrate the parameters to Photoshop: 
		if ($amount > 500)    $amount = 500; 
		$amount = $amount * 0.016; 
		if ($radius > 50)    $radius = 50; 
		$radius = $radius * 2; 
		if ($threshold > 255)    $threshold = 255; 
		 
		$radius = abs(round($radius));     // Only integers make sense. 
		if ($radius == 0) { 
			return $img; imagedestroy($img); break;        } 
		$w = imagesx($img); $h = imagesy($img); 
		$imgCanvas = imagecreatetruecolor($w, $h); 
		$imgBlur = imagecreatetruecolor($w, $h); 
		 
	
		// Gaussian blur matrix: 
		//                         
		//    1    2    1         
		//    2    4    2         
		//    1    2    1         
		//                         
		////////////////////////////////////////////////// 
			 
	
		if (function_exists('imageconvolution')) { // PHP >= 5.1  
				$matrix = array(  
				array( 1, 2, 1 ),  
				array( 2, 4, 2 ),  
				array( 1, 2, 1 )  
			);  
			imagecopy ($imgBlur, $img, 0, 0, 0, 0, $w, $h); 
			imageconvolution($imgBlur, $matrix, 16, 0);  
		}  
		else {  
	
		// Move copies of the image around one pixel at the time and merge them with weight 
		// according to the matrix. The same matrix is simply repeated for higher radii. 
			for ($i = 0; $i < $radius; $i++)    { 
				imagecopy ($imgBlur, $img, 0, 0, 1, 0, $w - 1, $h); // left 
				imagecopymerge ($imgBlur, $img, 1, 0, 0, 0, $w, $h, 50); // right 
				imagecopymerge ($imgBlur, $img, 0, 0, 0, 0, $w, $h, 50); // center 
				imagecopy ($imgCanvas, $imgBlur, 0, 0, 0, 0, $w, $h); 
	
				imagecopymerge ($imgBlur, $imgCanvas, 0, 0, 0, 1, $w, $h - 1, 33.33333 ); // up 
				imagecopymerge ($imgBlur, $imgCanvas, 0, 1, 0, 0, $w, $h, 25); // down 
			} 
		} 
	
		if($threshold>0){ 
			// Calculate the difference between the blurred pixels and the original 
			// and set the pixels 
			for ($x = 0; $x < $w-1; $x++)    { // each row
				for ($y = 0; $y < $h; $y++)    { // each pixel 
						 
					$rgbOrig = ImageColorAt($img, $x, $y); 
					$rOrig = (($rgbOrig >> 16) & 0xFF); 
					$gOrig = (($rgbOrig >> 8) & 0xFF); 
					$bOrig = ($rgbOrig & 0xFF); 
					 
					$rgbBlur = ImageColorAt($imgBlur, $x, $y); 
					 
					$rBlur = (($rgbBlur >> 16) & 0xFF); 
					$gBlur = (($rgbBlur >> 8) & 0xFF); 
					$bBlur = ($rgbBlur & 0xFF); 
					 
					// When the masked pixels differ less from the original 
					// than the threshold specifies, they are set to their original value. 
					$rNew = (abs($rOrig - $rBlur) >= $threshold)  
						? max(0, min(255, ($amount * ($rOrig - $rBlur)) + $rOrig))  
						: $rOrig; 
					$gNew = (abs($gOrig - $gBlur) >= $threshold)  
						? max(0, min(255, ($amount * ($gOrig - $gBlur)) + $gOrig))  
						: $gOrig; 
					$bNew = (abs($bOrig - $bBlur) >= $threshold)  
						? max(0, min(255, ($amount * ($bOrig - $bBlur)) + $bOrig))  
						: $bOrig; 
					 
					 
								 
					if (($rOrig != $rNew) || ($gOrig != $gNew) || ($bOrig != $bNew)) { 
							$pixCol = ImageColorAllocate($img, $rNew, $gNew, $bNew); 
							ImageSetPixel($img, $x, $y, $pixCol); 
						} 
				} 
			} 
		} 
		else{ 
			for ($x = 0; $x < $w; $x++)    { // each row 
				for ($y = 0; $y < $h; $y++)    { // each pixel 
					$rgbOrig = ImageColorAt($img, $x, $y); 
					$rOrig = (($rgbOrig >> 16) & 0xFF); 
					$gOrig = (($rgbOrig >> 8) & 0xFF); 
					$bOrig = ($rgbOrig & 0xFF); 
					 
					$rgbBlur = ImageColorAt($imgBlur, $x, $y); 
					 
					$rBlur = (($rgbBlur >> 16) & 0xFF); 
					$gBlur = (($rgbBlur >> 8) & 0xFF); 
					$bBlur = ($rgbBlur & 0xFF); 
					 
					$rNew = ($amount * ($rOrig - $rBlur)) + $rOrig; 
						if($rNew>255){$rNew=255;} 
						elseif($rNew<0){$rNew=0;} 
					$gNew = ($amount * ($gOrig - $gBlur)) + $gOrig; 
						if($gNew>255){$gNew=255;} 
						elseif($gNew<0){$gNew=0;} 
					$bNew = ($amount * ($bOrig - $bBlur)) + $bOrig; 
						if($bNew>255){$bNew=255;} 
						elseif($bNew<0){$bNew=0;} 
					$rgbNew = ($rNew << 16) + ($gNew <<8) + $bNew; 
						ImageSetPixel($img, $x, $y, $rgbNew); 
				} 
			} 
		} 
		imagedestroy($imgCanvas); 
		imagedestroy($imgBlur); 
		 
		return $img; 
	
	}
}
?>