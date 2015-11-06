<?php
class SimpleImage
{
	var $image;
	var $image_type;
 
	function load($filename)
	{
		$image_info = getimagesize($filename);
		$this->image_type = $image_info[2];
		switch ($this->image_type)
		{
			case IMAGETYPE_JPEG:
				$this->image = imagecreatefromjpeg($filename);
				break;
			case IMAGETYPE_GIF:
				$this->image = imagecreatefromgif($filename);
				break;
			case IMAGETYPE_PNG:
				$this->image = imagecreatefrompng($filename);
				break;
		}
	}
	
	function save($filename, $image_type=IMAGETYPE_JPEG, $compression=100, $permissions=null)
	{
		switch ($image_type)
		{
			case IMAGETYPE_JPEG:
				imagejpeg($this->image, $filename, $compression);
				break;
			case IMAGETYPE_GIF:
				imagegif($this->image, $filename);
				break;
			case IMAGETYPE_PNG:
				imagealphablending($this->image, false);
				imagesavealpha($this->image, true);
				imagepng($this->image, $filename);
				break;
		}
		if ($permissions != null)
		{
			chmod($filename, $permissions);
		}
	}
	
	function output($image_type=IMAGETYPE_JPEG)
	{
		switch ($image_type)
		{
			case IMAGETYPE_JPEG:
				imagejpeg($this->image);
				break;
			case IMAGETYPE_GIF:
				imagegif($this->image);
				break;
			case IMAGETYPE_PNG:
				imagealphablending($this->image, false);
				imagesavealpha($this->image, true);
				imagepng($this->image);
				break;
		}
	}
	
	function getWidth()
	{
		return imagesx($this->image);
	}
	
	function getHeight()
	{
		return imagesy($this->image);
	}
	
	function resizeToHeight($height)
	{
		$ratio = $height / $this->getHeight();
		$width = $this->getWidth() * $ratio;
		$this->resize($width, $height);
	}
	
	function resizeToWidth($width)
	{
		$ratio = $width / $this->getWidth();
		$height = $this->getHeight() * $ratio;
		$this->resize($width, $height);
	}
	
	function scale($scale)
	{
		$width = $this->getWidth() * $scale/100;
		$height = $this->getHeight() * $scale/100;
		$this->resize($width, $height);
	}
	
	function resize($width, $height)
	{
		$new_image = imagecreatetruecolor($width, $height);
		switch ($this->image_type)
		{
			case IMAGETYPE_GIF:
			case IMAGETYPE_PNG:
				/*$trnprt_indx = imagecolortransparent($this->image);
				if ($trnprt_indx >= 0)
				{
					$trnprt_color = imagecolorsforindex($this->image, $trnprt_indx);
					$trnprt_indx = imagecolorallocate($new_image, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue']);
					imagefill($new_image, 0, 0, $trnprt_indx);
					imagecolortransparent($new_image, $trnprt_indx);
				}*/
				imagealphablending($new_image, false);
				imagesavealpha($new_image, true);
				$transparent = imagecolorallocatealpha($new_image, 255, 255, 255, 127);
				imagefilledrectangle($new_image, 0, 0, $width, $height, $transparent);
				break;
		}
		imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
		$this->image = $new_image;
	}
}
?>