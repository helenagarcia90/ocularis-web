<?php


class ImageCacheController extends CController
{
	
	
	public function actionCache($preset, $pathInfo)
	{
		
		$destination = $pathInfo;
		
		$src = ltrim(Yii::app()->imageCache->decodeUrl('/'.$pathInfo),'/');
		
		if( ($preset = Yii::app()->imageCache->getPreset($preset)) === false)
			throw new CHttpException(404);
				
		
		
		if(Yii::app()->baseUrl != '' && strpos($src,Yii::app()->baseUrl) === 0)
		{
			$src = substr($src, strlen(Yii::app()->baseUrl));
		}
		
		$filename = Yii::getPathOfAlias('webroot') . DIRECTORY_SEPARATOR . $src;
		
		$filename = urldecode($filename);
		
		if(!file_exists($filename))
			throw new CHttpException(404);

		
		$info = @getimagesize($filename);
		
		if($info === false)
			throw new CHttpException(404);
		
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
				throw new CHttpException(404);
		
		}
		
		//we do not want to scale up and the image is smaller
		//copy the source image and display it 
		if(isset($preset['scaleUp']) && $preset['scaleUp'] === false)
		{
			if($width < $preset['width'] && $height<$preset['height'])
			{
				@mkdir(dirname($destination),0777,true);
				copy($src, $destination);
				header('Cache-Control: private, must-revalidate, post-check=0, pre-check=0, max-age=1');
				header('Content-Type: '.$info['mime']);
				readfile($destination);
				Yii::app()->end();
			}
		}
			
		switch($preset['type'])
		{
			
			
			case 'resize':
				$out_width = $dest_width = $preset['width'];
				$out_height = $dest_height = $preset['height'];
				$dest_x = 0;
				$dest_y = 0;
				
				$source_width = $width;
				$source_height = $height;
				$source_x = 0;
				$source_y = 0;
			break;
			
			case 'crop':
					
				$out_width = $dest_width = $preset['width'];
				$out_height = $dest_height = $preset['height'];
				$dest_x = 0;
				$dest_y = 0;
				
				$scales  =  array( $dest_width/$width,
						$dest_height/$height);
				
				$scale = max($scales);
					
				$source_width = $dest_width / $scale;
				$source_height = $dest_height / $scale;
				$source_x = ($width - $source_width)/2;
				$source_y = ($height - $source_height)/2;
				
				/*
				if($scale_h < $scale_w)
				{
					$source_width = $width;
					$source_height = $dest_height / $scale_w;
					$source_x = 0;
					$source_y = ($height - $source_height)/2;
				}
				elseif($scale_w < $scale_h)
				{
					$source_height = $height;
					$source_width = $dest_width / $scale_h;
					$source_x = ($width-$source_width)/2;
					$source_y = 0;
				}
				else
				{
					$source_width = $width;
					$source_height = $height;
					$source_x = 0;
					$source_y = 0;
				}
				*/
				
			break;
			
						
			case 'inset':
			case 'outset':
			case 'fill':
			
				$source_width = $width;
				$source_height = $height;
				$source_x = 0;
				$source_y = 0;
				
				$scales = array( $preset['width']/$width,
								$preset['height']/$height );
				
				if($preset['type'] === 'inset' || $preset['type'] === 'fill')
				{
					$scale = min($scales);
				}
				elseif($preset['type'] === 'outset')
				{
					$scale = max($scales);
				}	

				$dest_width = $width * $scale;
				$dest_height = $height * $scale;
				
				if($preset['type'] === 'fill')
				{
					$out_width = $preset['width'];
					$out_height = $preset['height'];
					
					$dest_x = ($out_width - $dest_width)/2 ;
					$dest_y = ($out_height - $dest_height)/2 ;

				}
				else
				{
					$dest_x = 0;
					$dest_y = 0;
					$out_width = $dest_width;
					$out_height = $dest_height;
				}


				
			break;
		}
		
		

		$dest = imagecreatetruecolor($out_width, $out_height);
		if(isset($preset['background']) && preg_match('/^#([0-9a-f]{2})([0-9a-f]{2})([0-9a-f]{2})([0-9a-f]{2})?$/i', $preset['background'], $match))
		{
			
			if(isset($match[4]))
			{
				imagealphablending($dest, false);
				$backgroundColor = imagecolorallocatealpha($dest, hexdec($match[1]), hexdec($match[2]), hexdec($match[3]),hexdec($match[4]));
				imagefill($dest, 0, 0, $backgroundColor);
				imagesavealpha($dest, true);
			}
				
			else
			{
				$backgroundColor = imagecolorallocate($dest, hexdec($match[1]), hexdec($match[2]), hexdec($match[3]));
				imagefill($dest, 0, 0, $backgroundColor);
			}
			
		}

		
		
		if(!imagecopyresampled($dest, $source, $dest_x, $dest_y, $source_x, $source_y, $dest_width, $dest_height, $source_width, $source_height))
			throw new CHttpException(404);
		
		
		Helper::mkdirs(dirname($destination));
		
		//make image progressive
		imageinterlace($dest,1);
		
		header('Cache-Control: private, must-revalidate, post-check=0, pre-check=0, max-age=1');
		header('Content-Type: '.$info['mime']);
		
		switch($info[2])
		{
			case IMAGETYPE_JPEG:
				imagejpeg($dest,$destination,60);
				imagejpeg($dest,null,75);
			break;
					
			case IMAGETYPE_PNG:
				imagepng($dest,$destination, 7);
				imagepng($dest,null, 7);
			break;

			case IMAGETYPE_GIF:
				imagegif($dest,$destination);
				imagegif($dest,null);
			break;
			
			default:
				throw new CHttpException(404);
		
		}
		
		imagedestroy($source);
		imagedestroy($dest);
		Yii::app()->end();
	}
	
}