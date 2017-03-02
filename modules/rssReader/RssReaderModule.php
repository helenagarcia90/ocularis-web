<?php

class SocioModule extends CWebModule
{
	
	public function init()
	{

		$this->setImport(array(
			'rssReader.models.*',
		));
	
		Yii::app()->onModuleCreate(new CEvent($this));
	}


}
