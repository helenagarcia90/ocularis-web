<?php

class SocialPopUp extends CWidget
{
	
	public $networks = array('facebook', 'twitter');
	public $assets;
	
	public function run()
	{

		$closed = Yii::app()->request->cookies->contains('socialPopUp') ? Yii::app()->request->cookies['socialPopUp']->value === 'closed' : false;
		
		$networks = array();
		$allNetworks = true;
		
		foreach($this->networks as $network)
		{
			$networks[$network] = Yii::app()->request->cookies->contains($network) ? true : false;
			$allNetworks = $allNetworks && $networks[$network];
		}
		
		
		
		if($closed || $allNetworks)
			return;
		
		$this->assets = Yii::app()->assetManager->publish( Yii::getPathOfAlias('modules.socialPopUp.widgets.popup.assets'));
		
		Yii::app()->clientScript->registerCssFile($this->assets."/style.css");
		
		$this->render('view', array('networks' => $networks));
		
	}
	
	
}