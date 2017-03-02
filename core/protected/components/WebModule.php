<?php

class WebModule extends CWebModule
{
	
	private $_controllerPath;
	private $_viewPath;
	private $_assets = null;
	
	/**
	 * @return string the directory that contains the controller classes. Defaults to 'moduleDir/controllers' where
     * moduleDir is the directory containing the module class.
	 */
	public function getControllerPath()
	{
		if($this->_controllerPath!==null)
			return $this->_controllerPath;
		else
		{
			if($this->parentModule !== null && $this->parentModule->id === 'admin')
				$this->_controllerPath=$this->getBasePath().DIRECTORY_SEPARATOR.'controllers'.DIRECTORY_SEPARATOR."admin";
			else
				$this->_controllerPath=$this->getBasePath().DIRECTORY_SEPARATOR.'controllers'.DIRECTORY_SEPARATOR."front";
			
			return $this->_controllerPath;
		}
	}



	/**
	 * @return string the root directory of view files. Defaults to 'moduleDir/views' where
	 * moduleDir is the directory containing the module class.
	 */
	public function getViewPath()
	{
		if($this->_viewPath!==null)
			return $this->_viewPath;
		else
		{
			if($this->parentModule !== null && $this->parentModule->id === 'admin')
				$this->_viewPath=$this->getBasePath().DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR."admin";
			else
				$this->_viewPath=$this->getBasePath().DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR."front";
			
			return $this->_viewPath;
		}
	}
	

}