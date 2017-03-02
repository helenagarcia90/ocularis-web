<?php


class ShopMigration extends ModuleMigration
{
	
	public function getComponentName(){
		return 'shop';
	}
	
	public function create(){
		
		$this->getDbConnection()->createCommand()->createTable("{{shop_product}}", array(
							'id_product' => 'INT UNSIGNED NOT NULL AUTO_INCREMENT',
							'price'		=> 	'NUMERIC(10,2)'		
		));
		
		
		$this->getDbConnection()->createCommand()->createTable("{{shop_product_translation}}", array(
				'id_product' => 'INT UNSIGNED NOT NULL',
				'lang'		=> 	'VARCHAR(7) NOT NULL',
				'name'		=> 'VARCAR(256) NOT NULL',
				'alias'		=> 'VARCAR(256) NOT NULL',
				'short_description'		=> 'VARCAR(512)',
				'long_description'		=> 'TEXT NOT NULL',
				'CONSTRAINT `fk_shop_product_translation_id_product` FOREIGN KEY (`id_product`) REFERENCES {{shop_product}} (`id_product`) ON DELETE CASCADE ON UPDATE CASCADE',
				'CONSTRAINT `fk_shop_product_translation_lang` FOREIGN KEY (`lang`) REFERENCES {{lang}} (`code`) ON DELETE CASCADE ON UPDATE CASCADE',
				'INDEX `fk_shop_product_translation_id_product_idx` (`id_product` ASC)',
				'INDEX `fk_shop_product_translation_lang` (`lang` ASC)',
		));
		
		
		//insert migration into DB
		$this->getDbConnection()->createCommand()->insert("{{migration}}",array(
				'component'=> $this->getComponentName(),
				'version'=> 1,
				'time'=> new CDbExpression("CURRENT_TIMESTAMP()"),
		));
		
		return true;
	}
	
	
}