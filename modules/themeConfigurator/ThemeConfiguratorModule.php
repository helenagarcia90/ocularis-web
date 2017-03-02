<?php

class ThemeConfiguratorModule extends WebModule
{
	
	public function init()
	{

		$this->setImport(array(
			'themeConfigurator.models.*',
		));
	}
	
}
