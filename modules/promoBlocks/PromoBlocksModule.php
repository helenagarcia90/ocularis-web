<?php

class PromoBlocksModule extends CWebModule
{
	
	public function init()
	{

		$this->setImport(array(
			'promoBlocks.models.*',
		));
	
		Yii::app()->onModuleCreate(new CEvent($this));
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			return true;
		}
		else
			return false;
	}
	
}
