<?php

class Updates extends CWidget
{
	
	public function run(){
		
		if(!Yii::app()->user->checkAccess('migrateMigration'))
			return;
			
		$modules = array();
		
		foreach(Yii::app()->moduleManager->getModulesConfig(false) as $name => $module)
		{
			//only modules that have migration logic
			if($module->getMigration() === false)
				continue;
		
			if($module->migration->checkNewerVersion())
				$modules[$name] = $module;
		}
		
		$core = new CoreMigration();
		
		if($modules === array() && !$core->checkNewerVersion())
			return;
		
		$this->render('updates', array('coreMigration' => $core, 'modules' => $modules));
		
	}
	
}