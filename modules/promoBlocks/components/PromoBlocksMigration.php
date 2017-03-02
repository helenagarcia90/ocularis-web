<?php


class PromoBlocksMigration extends ModuleMigration
{
	
	public function getComponentName(){
		return 'promoBlocks';
	}
	
	public function hasDb()
	{
		return true;
	}
	
	public function create(){
		
		$this->getDbConnection()->createCommand()->createTable("{{home_block}}", array(
		
				'id_promo_block' => 'INT UNSIGNED NOT NULL AUTO_INCREMENT',
				'lang' => 'CHAR(2) NOT NULL',
				'title' => 'VARCHAR(256) NOT NULL',
				'content' => 'TEXT NOT NULL',
				'image' => 'VARCHAR(256) NOT NULL',
				'image_alternate' => 'VARCHAR(256) NOT NULL',
				'link' => 'VARCHAR(256)',
				'position' => 'INT UNSIGNED NOT NULL',
				'active' => 'TINYINT(1) UNSIGNED NOT NULL',
				'CONSTRAINT `fk_home_blocks_lang` FOREIGN KEY (`lang`) REFERENCES `{{lang}}` (`code`) ON DELETE CASCADE ON UPDATE CASCADE',
				'INDEX `fk_home_blocks_lang_idx` (`lang` ASC)',
				'PRIMARY KEY (`id_promo_block`)',
		
		
		));
		
		//insert migration into DB
		$this->getDbConnection()->createCommand()->insert("{{migration}}",array(
				'component'=> "homeBlocks",
				'version'=> 1,
				'time'=> new CDbExpression("CURRENT_TIMESTAMP()"),
		));
		
		return true;
	}
	
	
}