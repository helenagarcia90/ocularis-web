<?php


class HomeBlocksMigration extends ModuleMigration
{
	
	public function getComponentName(){
		return 'homeBlocks';
	}
	
	public function hasDb()
	{
		return true;
	}
	
	public function installDb(){
		
		$this->getDbConnection()->createCommand()->createTable("{{home_block}}", array(
		
				'id_home_block' => 'INT UNSIGNED NOT NULL AUTO_INCREMENT',
				'image' => 'VARCHAR(256) NOT NULL',
				'position' => 'INT UNSIGNED NOT NULL',
				'active' => 'TINYINT(1) UNSIGNED NOT NULL',
				'PRIMARY KEY (`id_home_block`)',
		
		
		));
		
		$this->getDbConnection()->createCommand()->createTable("{{home_block_lang}}", array(
		
				'id_home_block' => 'INT UNSIGNED NOT NULL',
				'lang' => 'VARCHAR(10) NOT NULL',
				'title' => 'VARCHAR(256) NOT NULL',
				'content' => 'TEXT NOT NULL',
				'link' => 'VARCHAR(256)',
		
				'PRIMARY KEY (`id_home_block`, `lang`)',
				
				'CONSTRAINT `fk_home_block_home_block_lang` FOREIGN KEY (`id_home_block`) REFERENCES `{{home_block}}` (`id_home_block`) ON DELETE CASCADE ON UPDATE CASCADE',
				'INDEX `fk_home_block_home_block_lang_idx` (`id_home_block` ASC)',
		
				'CONSTRAINT `fk_home_block_lang` FOREIGN KEY (`lang`) REFERENCES `{{lang}}` (`code`) ON DELETE CASCADE ON UPDATE CASCADE',
				'INDEX `fk_home_block_lang_idx` (`lang` ASC)',
		));
		
		
		$authManager = Yii::app()->authManager;
			
		$authManager->createOperation('viewHomeBlock');
		$authManager->createOperation('createHomeBlock');
		$authManager->createOperation('updateHomeBlock');
		$authManager->createOperation('deleteHomeBlock');
		
		return true;
	}
	
	
	public function updateDb()
	{
	
		switch($this->getCurrentVersion())
		{
			case '0.2':
				$this->getDbConnection()->createCommand()->addColumn("{{home_block}}", 'target', 'VARCHAR(16) DEFAULT NULL AFTER image');
					
		}
	
	
		return true;
	
	}
	
	
}