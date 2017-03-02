<?php

class ModuleManager extends CApplicationComponent
{

	
	private $_modulesConfig = array();
	
	public function init()
	{
		
		//Load modules info
		Yii::beginProfile("application.components.ApplicationConfigBehavior.loadModules",'application.components.ApplicationConfigBehavior');
		
		foreach (Yii::app()->modules as $name => $config)
		{
			//ignore core modules
			if(isset($config['class']) && substr($config['class'],0,11) === 'application')
				continue;
		
			$module = new ModuleConfig($name);
			$this->_modulesConfig[$name] = $module;
		}
		
		Yii::endProfile("application.components.ApplicationConfigBehavior.loadModules",'application.components.ApplicationConfigBehavior');
		
	}
	

	public function getModulesConfig($installed = true)
	{
		
		$modules = array();
		
		foreach( $this->_modulesConfig as $name => $module )
		{
			if( ! $installed || $module->migration === false || ($installed && $module->migration->isInstalled()) )
				$modules[$name] = $module;
		}
		
		return $modules;
	}
	
	public function getModuleConfig($name)
	{
		if(isset($this->_modulesConfig[$name]))
			return $this->_modulesConfig[$name];
		else
			return null;
	}
	
	public function getPageExtraOptions()
	{
		
		$extras = array();
		
		foreach($this->modulesConfig as $module)
		{
			if(isset($module->pageExtraOptions))
				$extras += $module->pageExtraOptions;
		}
		
		return $extras;
	}
	
	public function getAdminMenuItems($parent = 'modules')
	{
		
		$items = array();
		
		foreach($this->modulesConfig as $module)
		{
			if(isset($module->adminMenuItems))
			{
				foreach($module->adminMenuItems as $item)
				{
					
					if(!isset($item['parent']))
						$p = 'modules';
					else
						$p = $item['parent'];
					
					unset($item['parent']);
					
					if($parent === $p)
					{
						$items[] = $item;
					}
					
				}
				
			}
		}
		
		return $items;
		
	}

}