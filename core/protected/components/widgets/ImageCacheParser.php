<?php

class ImageCacheParser extends COutputProcessor{
	
	
	/**
	 * 
	 * This method will parse the content and transform the images urls into their corresponding cached ones.
	 * e.g.: <img src="/medias/images/my_image.jpg" width="200" height="200"/> will be transformed into <img src="/medias/cache/200x200/my_image.jpg" width="200" height="200"/>
	 * Also, already cached images will have their name changed in order to reflect the new dimension:
	 * e.g.: <img src="/medias/cache/200x200/my_image.jpg" width="500" height="500"/> will be transformed into <img src="/medias/cache/500x500/my_image.jpg" width="500" height="500"/>
	 * Note: This doesn't actually caches the images, this will be the role of the ImageCacheController when the UrlManager handles them
	 * @param string $content The html content that needs to be parsed
	 */
	
	public function parse($content)
	{

		Yii::beginProfile('application.components.ImageCacheParser.parse','application.components.ImageCacheParser');
		$tr = array();
		$matches = array();
		//find all locale images that are from the medias
		preg_match_all('/<img[^>]*?src[ ]*=[ ]*"((?:'.str_replace('/',"\/",preg_quote(Yii::app()->getBaseUrl(true))).')?(\\/medias\\/[^"]+\.(?:jpg|png|jpeg|gif)))"[^>]*>/i', $content, $matches);

		foreach($matches[0] as $index => $tag)
		{
			
			$src = $matches[1][$index];
			$url = $matches[2][$index];

		
			
					//get style, width and height attributes
					preg_match_all('/[ ]+(style|width|height)[ ]*=[ ]*"([^"]+)"/i', $tag, $attributes);
					
					$result = array();
					
					foreach($attributes[1] as $index => $key)
					{
						$result[$key] = $attributes[2][$index];
					}
					
					if(isset($result['style']))
					{
						preg_match_all('/(width|height)[ ]*:[ ]*(\d+)px[ ]*;?/i', $result['style'], $properties);
						
						foreach($properties[1] as $index => $property)
						{
							$result[$property] = $properties[2][$index];
						}
					}
					
					//both width and height aren't explicitely specified (will use the fill size image)
					if(!isset($result['width']) || !isset($result['height']))
						continue;
					
					
					//filter cached images
					if(preg_match('/^\\/medias\\/'. str_replace('/','\/',Yii::app()->imageCache->cacheDir) .'\\/(\d+)x(\d+)\\/([^"]+)$/i', $url, $match))
					{

						$width = isset($result['width']) ? $result['width'] : '';
						$height = isset($result['height']) ? $result['height'] : '';
						$tr[$tag] = preg_replace('/\\/medias\\/'. str_replace('/','\/',Yii::app()->imageCache->cacheDir) .'\\/(\d+)x(\d+)\\/([^"]+)/i','/medias/' . Yii::app()->imageCache->cacheDir . '/'.$width.'x'.$height.'/$3',$tag);
						continue;
					}
					
						
					$info = @getimagesize( Yii::getPathOfAlias("webroot") . urldecode($url) );
					
					//could not extract info, leave unchanged
					if($info === false)
						continue;
						
					//the width and height are unchanged from original 
					if( $result['width'] == $info[0]  && $result['height'] == $info[1] )
							continue; 
					
					
					$preset = $result['width'].'x'.$result['height'];
					
						
					$tr[$tag] = str_replace($src, Yii::app()->imageCache->createUrl($preset, $src), $tag);

			
			
		}
		
		Yii::endProfile('application.components.ImageCacheParser.parse','application.components.ImageCacheParser');
		
		return strtr($content, $tr);
		
	}
	
	public function processOutput($output)
	{
		return $this->parse($content);
	}
	
}