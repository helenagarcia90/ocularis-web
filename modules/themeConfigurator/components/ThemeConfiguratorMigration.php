<?php

class ThemeConfiguratorMigration extends ModuleMigration
{
	
	public function getComponentName(){
		return 'themeConfigurator';
	}
	
	public function hasDb()
	{
		return true;
	}
	
	public function installDb(){
		
		$this->getDbConnection()->createCommand()->createTable("{{theme_config}}", array(
		
				'id_theme_config' => 'INT UNSIGNED NOT NULL AUTO_INCREMENT',
				'name' => 'VARCHAR(256) NOT NULL',
				'value' => 'TEXT',
				'PRIMARY KEY (`id_theme_config`)',
		));
		
		
		$authManager = Yii::app()->authManager;
			
		$authManager->createOperation('viewThemeConfiguration');
		$authManager->createOperation('updateThemeConfiguration');
		
		return true;
	}
	
	
	
}