<?php

class ModuleConfig extends CComponent{
	
	
	private $file = null;
	
	public $alias;
	private $_d = array();
	private $_migration;
	
	public function __construct($alias)
	{
		$this->alias = $alias;
		$this->file = Yii::getPathOfAlias("modules.".$this->alias).DIRECTORY_SEPARATOR."config.php";
		$this->init();
	}
	
	public function init()
	{
		
		if(!is_file($this->file))
			throw new CException("The module {$this->alias} does not have it's configuration file");

		//This will be needed to load the messages translations
		Yii::import("modules.{$this->alias}.".ucfirst($this->alias)."Module");
		Yii::import("modules.{$this->alias}.components.*");
		Yii::import("modules.{$this->alias}.models.*");
		
		//load the config file
		$this->_d = require($this->file);
	}
	
	/**
	 * Override magic method __get to load config directly from array 
	 */
	public function __get($name)
	{
		if(isset($this->_d[$name]))
			return $this->_d[$name];
		else
			return parent::__get($name);
	}
	
	
	public function __isset($name)
	{
		if(isset($this->_d[$name]))
			return true;
		else
			return parent::__isset($name);
	}
	
	public function getMigration()
	{
		
		if($this->_migration === null)
		{
		
			$class = ucfirst($this->alias)."Migration";

			if(is_file(Yii::getPathOfAlias("modules.{$this->alias}.components.{$class}").".php"))
			{	
				Yii::import("modules.{$this->alias}.components.{$class}");
				$this->_migration = new $class;
			}
			else
				$this->_migration = false;
		}
		
		return $this->_migration;
		
	}
	
	
	public function getDisplayVersion($version = null)
	{
		if($version === null)
			$version = $this->version;
		
		return $this->versionDisplays[$version];
		
	}
	
	public function getSitemap()
	{
		$class = ucfirst($this->alias)."Module";
		
		if(class_exists($class))
		{
			$reflection = new ReflectionClass($class);
			if($reflection->hasMethod('getSitemap'))
			{
				return $class::getSitemap();
			}
		}
		
		return array();
	}
	
	
}