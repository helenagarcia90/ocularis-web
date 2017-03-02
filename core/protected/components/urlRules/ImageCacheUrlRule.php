<?php

class ImageCacheUrlRule extends CBaseUrlRule
{
	
	public function parseUrl($manager, $request, $pathInfo, $rawPathInfo)
	{
		
		if(preg_match('/^medias\/'.str_replace('/',"\/",preg_quote(Yii::app()->imageCache->cacheDir)).'\/([\w\d_\-]+)\/.*\.(jpg|jpeg|png|gif)$/i', $pathInfo, $matches))
		{
			
			$_GET['preset'] = $matches[1];
			$_GET['pathInfo'] = $pathInfo; 

			return 'imageCache/cache';
		}
		return false;
	}
	
	public function createUrl($manager, $route, $params, $ampersand)
	{
		return false;
	}
	
	
}