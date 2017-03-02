<?php

class HomeBlocskImageFilter
{
	
	public static function filter($path)
	{
		
		if( ($pos = strrpos(urldecode($path), '.')) !== false )
			$alternative = substr(urldecode($path), 0, $pos) . '-alternate' . substr(urldecode($path), $pos);
				
		$dest = Yii::getPathOfAlias('webroot') . $alternative;
		
		if(is_file($dest))
			return $alternative;
		
		$filename = Yii::getPathOfAlias('webroot') . urldecode($path);
		
		$info = @getimagesize($filename);
		
		if($info === false)
			return $path;
		
		$width = $info[0];
		$height = $info[1];
		
		switch($info[2])
		{
			case IMAGETYPE_JPEG:
				$source = imagecreatefromjpeg($filename);
				break;
					
			case IMAGETYPE_PNG:
				$source = imagecreatefrompng($filename);
				break;
					
			case IMAGETYPE_GIF:
				$source = imagecreatefromgif($filename);
				break;
					
			default:
				return $path;
		
		}
		
		imagefilter($source, IMG_FILTER_GRAYSCALE);
		
		
		
		switch($info[2])
		{
			case IMAGETYPE_JPEG:
				imagejpeg($source,$dest,60);
				break;
					
			case IMAGETYPE_PNG:
				imagepng($source,$dest, 7);
				break;
		
			case IMAGETYPE_GIF:
				imagegif($source,$dest);
				break;
			default:
				return $path;
		}
		
		return $alternative;
		
		
	}
	
}