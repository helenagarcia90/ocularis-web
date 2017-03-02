<?php

class ThemeConfiguration extends CApplicationComponent
{
	
	public $config;
	
	public function init()
	{
		parent::init();
		
		$this->config = ThemeConfig::model()->findAll(); 
		
	}
	
	public function get($name)
	{
		if(isset($this->config[$name]))
			return 	$this->config[$name]->value;
		else
			return null;
		
	}
	
}