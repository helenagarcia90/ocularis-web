<?php


class BlogWidgetMigration extends ModuleMigration
{
	
	public $upgraderUrl = "http://www.bbwebconsult.com/biicms/modules/blogWidget/releases.xml";
	
	public function getComponentName(){
		return 'blogWidget';
	}
	
	public function installDb(){
		
		$this->getDbConnection()->createCommand()->createTable("{{blog_widget}}", array(
		
				'id_widget' => 'INT UNSIGNED NOT NULL AUTO_INCREMENT',
				'title' => 'VARCHAR(256) NOT NULL',
				'anchor' => 'VARCHAR(32) NOT NULL',
				'lang' => 'CHAR(2) NOT NULL',
				'id_blog' => 'INT UNSIGNED NOT NULL',
				'CONSTRAINT `fk_blog_widget_blog` FOREIGN KEY (`id_blog`) REFERENCES `{{blog}}` (`id_blog`) ON DELETE CASCADE ON UPDATE CASCADE',
				'CONSTRAINT `fk_blog_widget_lang` FOREIGN KEY (`lang`) REFERENCES `{{lang}}` (`code`) ON DELETE CASCADE ON UPDATE CASCADE',
				'CONSTRAINT `lang_anchor_UNIQUE` UNIQUE (`lang`, `anchor`)',
				'INDEX `fk_blog_widget_blog_idx` (`id_blog` ASC)',
				'INDEX `fk_blog_widget_lang_idx` (`lang` ASC)',
				'INDEX `anchor_idx` (`anchor` ASC)',
				'PRIMARY KEY (`id_widget`)',
		
		
		));
		return true;
	}
	
	public function updateDb()
	{
		
		switch ($this->getCurrentVersion())
		{
		
		
		}
			
		return true;
		
	}
	
	public  function hasDb()
	{
		return true;
	}
	
}