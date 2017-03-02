<?php

class SliderWidget extends CWidget{
	
	public $anchor;
	public $assetsUrl;
	public $jsFile = null;
	public $cssFile = null;
	public $view = null;
	
	public function init(){
	
		Yii::beginProfile('bii.modules.Slider.widget','widgets');
		
		if($this->assetsUrl === null)
		{
			$this->assetsUrl = Yii::app()->assetManager->publish(
					Yii::getPathOfAlias('modules.slider.assets')
			);
		}
	
	}
	
	public function run()
	{
		
		
		Yii::import('modules.slider.models.Slider');
		Yii::import('modules.slider.models.Slide');
		
		$slider = Slider::model()->findByAttributes(array('anchor' => $this->anchor, 'lang' => Yii::app()->language));
		
		
		if($slider === null)
			return;
		
		$this->registerClientScripts();
		
		if($this->cssFile !== false)
		{
			if($this->cssFile === null)
				$this->cssFile = $this->assetsUrl . '/css/slider.css';
			Yii::app()->clientScript->registerCssFile($this->cssFile);
		}
		
		if($this->view === null)
			$this->view = 'slider';
		
		$this->render($this->view,array(
							'slider' => $slider,
		));
		
		Yii::endProfile('bii.modules.Slider.widget','widgets');
	}
	
	private function registerClientScripts()
	{
		
		if($this->jsFile === false)
			return;
		
		if($this->jsFile === null)
			$jsFile = $this->assetsUrl . '/js/bjqs-1.3.min.js';
		else
			$jsFile = $this->jsFile;
		
		$cs = Yii::app()->clientScript;
		$cs->registerCoreScript('jquery');
		$cs->registerScriptFile($jsFile);

	} 

	
}