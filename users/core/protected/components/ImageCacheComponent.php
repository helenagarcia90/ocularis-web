<?php

class ImageCacheComponent extends CComponent
{
	
	public $presets = array();
	public $cacheDir = 'cache';
	
	public function init()
	{
		
	}
	
	public function createUrl($preset, $src)
	{
		return str_replace('/medias/images/', '/medias/'.$this->cacheDir.'/'.$preset . '/', $src);
	}

	public function jsCreateUrl($preset, $src)
	{
		return $src.".replace('/medias/images/',".CJavascript::encode("/medias/".Yii::app()->imageCache->cacheDir."/".$preset."/").")";
	}

	public function decodeUrl($src)
	{
		return preg_replace('/\\/medias\\/'.str_replace('/','\/',Yii::app()->imageCache->cacheDir).'\\/[^\\/]+\\//', '/medias/images/', $src);
	}
	
	public function getPreset($preset)
	{
		
		//first, see if there is a preset defined by config
		if(isset($this->presets[$preset]))
		{
			return $this->presets[$preset];
		}
		
		//check in modules
		foreach(Yii::app()->moduleManager->modulesConfig as $module)
		{
			if(isset($module->imageCachePresets) && isset($module->imageCachePresets[$preset]))
				return $module->imageCachePresets[$preset];
				
		}
		
		if(preg_match('/^(\d+)x(\d+)$/i', $preset, $match))
		{
			return array(
				'type' => 'resize',
				'width' => $match[1],
				'height' => $match[2],
			);
		}
		
		return false;
	}
	
	
}