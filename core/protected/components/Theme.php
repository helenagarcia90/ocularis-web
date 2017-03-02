<?php


class Theme extends CTheme
{
	
	private $_config;
	
	public function getConfig()
	{

		if(is_array($this->_config))
			return $this->_config;
		
		if(is_file($this->basePath . DIRECTORY_SEPARATOR . 'config.php'))
		{
			return $this->_config = require($this->basePath . DIRECTORY_SEPARATOR . 'config.php');
		}

		return $this->_config = array();
	}
	
	
}