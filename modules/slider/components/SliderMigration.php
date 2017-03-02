<?php


class SliderMigration extends ModuleMigration
{
	
	public function getComponentName(){
		return 'slider';
	}
	
	public function hasDb()
	{
		return true;
	}
	
	public function installDb(){
		
		$this->getDbConnection()->createCommand()->createTable("{{slider}}", array(
		
				'id_slider' => 'INT UNSIGNED NOT NULL AUTO_INCREMENT',
				'lang' => 'CHAR(2) NOT NULL',
				'name' => 'VARCHAR(256) NOT NULL',
				'anchor' => 'VARCHAR(32) NOT NULL',
				'animduration' => 'INT UNSIGNED NOT NULL',
				'animpause' => 'INT UNSIGNED NOT NULL',
				'CONSTRAINT `fk_slider_lang` FOREIGN KEY (`lang`) REFERENCES `{{lang}}` (`code`) ON DELETE CASCADE ON UPDATE CASCADE',
				'CONSTRAINT `lang_anchor_UNIQUE` UNIQUE (`lang`, `anchor`)',
				'INDEX `fk_slider_lang_idx` (`lang` ASC)',
				'INDEX `anchor_idx` (`anchor` ASC)',
				'PRIMARY KEY (`id_slider`)',
		
		
		));
		
		
		$this->getDbConnection()->createCommand()->createTable("{{slider_slide}}", array(
		
				'id_slider' => 'INT UNSIGNED NOT NULL',
				'image'				=> 'VARCHAR(256) NOT NULL',
				'legend'			=> 'TEXT NOT NULL',
				'link'				=> 'VARCHAR(512) NOT NULL',
				'position' 			=> 'INT UNSIGNED NOT NULL',
				'active' 			=> 'TINYINT(1) UNSIGNED NOT NULL',
				'CONSTRAINT `fk_slider_slide_slide` FOREIGN KEY (`id_slider`) REFERENCES `{{slider}}` (`id_slider`) ON DELETE CASCADE ON UPDATE CASCADE',
				'INDEX `fk_slider_slide_slider_idx` (`id_slider` ASC)',
				'INDEX `position_idx` (`id_slider`, `position` ASC)',
				'PRIMARY KEY (`id_slider`, `position`)',
		
		
		));
		
		$authManager = Yii::app()->authManager;
			
		$authManager->createOperation('viewSlider');
		$authManager->createOperation('createSlider');
		$authManager->createOperation('updateSlider');
		$authManager->createOperation('deleteSlider');
		
		
		
		return true;
	}
	
	public function updateDb()
	{
		
		switch($this->currentVersion)
		{
			
			case '1.0':
				
				$this->getDbConnection()->createCommand()->dropColumn('{{slider_slide}}', 'id_slider_slide');
				$this->getDbConnection()->createCommand()->addPrimaryKey('PRIMARY', '{{slider_slide}}', 'id_slider, position');
				
				$authManager = Yii::app()->authManager;
					
				$authManager->createOperation('viewSlider');
				$authManager->createOperation('createSlider');
				$authManager->createOperation('updateSlider');
				$authManager->createOperation('deleteSlider');
				
			break;
			
			
		}
		
		return true;
		
	}
	
}