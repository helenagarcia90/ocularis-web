<?php

class CoreMigration extends Migration{
	
	public $upgraderUrl = 'http://www.bbwebconsult.com/biicms/releases.xml';
	

	public function getComponentLocation()
	{
		return Yii::getPathOfAlias("webroot");
	}
	
	public function hasDb(){
		return true;
	}
	
	public function installDb(){
		
		

		$this->getDbConnection()->createCommand()->createTable("{{admin}}", array(
									'id_admin' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
									'name' => 'varchar(50)  NOT NULL',
									'email' => 'varchar(255)  NOT NULL',
									'password' => 'varchar(32)  NOT NULL',
									'lang' => 'varchar(10)  NOT NULL',
									'editor' => 'varchar(50)  NOT NULL',
									'PRIMARY KEY (`id_admin`)',
									'UNIQUE KEY `email_UNIQUE` (`email`)'
		));
		
		$this->getDbConnection()->createCommand()->createTable("{{blog}}", array(
									'id_blog' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
									'lang' => 'varchar(10)  NOT NULL',
									'name' => 'varchar(512)  NOT NULL',
									'alias' => 'varchar(255)  NOT NULL',
									'meta_title' => 'varchar(256)   DEFAULT NULL',
									'meta_keywords' => 'varchar(512)   DEFAULT NULL',
									'meta_description' => 'varchar(512)   DEFAULT NULL',
									'meta_robots' => 'varchar(256) DEFAULT NULL',
									'PRIMARY KEY (`id_blog`)',
									'UNIQUE KEY `lang_alias_UNIQUE` (`lang`,`alias`)',
									'INDEX `fk_blog_lang_idx` (`lang`)',
									'INDEX `alias_idx` (`alias`)'
		));
		
		$this->getDbConnection()->createCommand()->createTable("{{blog_page}}", array(
									'id_blog' => 'int(10) unsigned NOT NULL',
									'id_page' => 'int(10) unsigned NOT NULL',
									'PRIMARY KEY (`id_blog`,`id_page`)',
									'INDEX `fk_blog_page_blog_idx` (`id_blog`)',
									'INDEX `fk_blog_page_page_idx` (`id_page`)'
		));
		
		$this->getDbConnection()->createCommand()->createTable("{{category}}", array(
									'id_category' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
									'id_category_parent' => 'int(10) unsigned DEFAULT NULL',
									'name' => 'varchar(512)  NOT NULL',
									'description' => 'mediumtext  NOT NULL',
									'level' => 'INT NOT NULL',
									'left' => 'INT NOT NULL',
									'right' => 'INT NOT NULL',
									'PRIMARY KEY (`id_category`)',
									'INDEX `fk_category_category1_idx` (`id_category_parent`)',
									'INDEX `left_idx` (`left`)',
									'INDEX `right_idx` (`right`)',
				
		));
		
		$this->getDbConnection()->createCommand()->createTable("{{lang}}", array(
									'code' => 'varchar(10)  NOT NULL',
									'name' => 'varchar(50)  NOT NULL',
									'default' => 'tinyint(1) NOT NULL',
									'active' => 'tinyint(1) NOT NULL',
									'PRIMARY KEY (`code`)'
		));
		
		$this->getDbConnection()->createCommand()->createTable("{{menu}}", array(
									'id_menu' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
									'lang' => 'varchar(10)  NOT NULL',
									'name' => 'varchar(50)  NOT NULL',
									'anchor' => 'varchar(32)  NOT NULL',
									'active' => 'tinyint(1) NOT NULL',
									'PRIMARY KEY (`id_menu`)',
									'UNIQUE KEY `alias_UNIQUE` (`anchor`,`lang`)',
									'INDEX `fk_menu_lang1_idx` (`lang`)'
		));
		
		$this->getDbConnection()->createCommand()->createTable("{{menu_item}}", array(
		
									'id_menu_item' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
									'id_menu' => 'int(10) unsigned NOT NULL',
									'id_menu_item_parent' => 'int(10) unsigned DEFAULT NULL',
									'link' => 'varchar(1024)  NOT NULL',
									'label' => 'varchar(50)  NOT NULL',
									'target' => 'varchar(16)   DEFAULT NULL',
									'position' => 'int(11) NOT NULL',
									'active' => 'tinyint(4) NOT NULL',
									'PRIMARY KEY (`id_menu_item`)',
									'INDEX `fk_menu_item_menu1_idx` (`id_menu`)',
									'INDEX `fk_menu_item_menu_item1_idx` (`id_menu_item_parent`)'
		));
		
		$this->getDbConnection()->createCommand()->createTable("{{migration}}", array(
									'time' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
									'component' => 'varchar(128)  NOT NULL',
									'version' => 'varchar(10)  NOT NULL',
									'PRIMARY KEY (`time`)',
									'INDEX `component_idx` (`component`)',
									'INDEX `version_idx` (`version`)'
		));
		

		$this->getDbConnection()->createCommand()->createTable("{{page}}", array(
									'id_page' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
									'id_author' => 'int(10) unsigned',
									'lang' => 'varchar(10) NULL',
									'id_page_parent' => 'int(10) unsigned DEFAULT NULL',
									'id_category' => 'int(10) unsigned DEFAULT NULL',
									'title' => 'varchar(512)  NOT NULL',
									'alias' => 'varchar(255)  NOT NULL',
									'content' => 'mediumtext  NOT NULL',
									'meta_title' => 'varchar(256)   DEFAULT NULL',
									'meta_keywords' => 'varchar(512)   DEFAULT NULL',
									'meta_description' => 'varchar(512)   DEFAULT NULL',
									'meta_robots' => 'varchar(256) DEFAULT NULL',
									'status' => "VARCHAR(32) NOT NULL",
							  		'update_date' => 'timestamp NULL DEFAULT NULL',
									'published_date' => 'timestamp NULL DEFAULT NULL',
									'type' => 'varchar(20)   DEFAULT NULL',
									'template' => 'varchar(256)   DEFAULT NULL',
									'id_page_revision' => 'int(10) unsigned NULL',
									'PRIMARY KEY (`id_page`)',
									'INDEX `fk_page_category1_idx` (`id_category`)',
									'INDEX `fk_page_author_idx` (`id_author`)',
									'INDEX `fk_page_page1_idx` (`id_page_parent`)',
									'INDEX `fk_page_lang1_idx` (`lang`)',
									'INDEX `fk_id_page_revision_idx` (`id_page_revision`)',
									'INDEX `alias_idx` (`alias`)',
		  ));
		
		  $this->getDbConnection()->createCommand()->createTable("{{page_assoc}}", array(
									  'id_page_from' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
									  'lang_from' => 'varchar(10)  NOT NULL',
									  'id_page_to' => 'int(10) unsigned NOT NULL',
									  'lang_to' => 'varchar(10)  NOT NULL',
									  'PRIMARY KEY (`id_page_from`,`id_page_to`,`lang_to`)',
									  'INDEX `fk_page_has_page_page2_idx` (`id_page_to`)',
									  'INDEX `fk_page_has_page_page1_idx` (`id_page_from`)',
									  'INDEX `fk_page_assoc_lang1_idx` (`lang_to`)',
									  'INDEX `fk_page_assoc_lang2_idx` (`lang_from`)'
		  ));
		
		  $this->getDbConnection()->createCommand()->createTable("{{page_extra_option}}", array(
									  'id_page' => 'int(10) unsigned NOT NULL',
									  'name' => 'varchar(32)  NOT NULL',
									  'value' => 'text  NOT NULL',
									  'PRIMARY KEY (`id_page`,`name`)',
									  'INDEX `fk_d_page` (`id_page`)'
		  ));
		
		  $this->getDbConnection()->createCommand()->createTable("{{search_keyword}}", array(
									  'id_keyword' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
									  'keyword' => 'VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL',
									  'lang' => 'varchar(10)  NOT NULL',
									  'PRIMARY KEY (`id_keyword`)',
									  'INDEX `fk_search_keyword_lang_idx` (`lang`)',
									  'UNIQUE `keyword_unique_idx` (`lang`,`keyword`)'
		  ));
		
		  $this->getDbConnection()->createCommand()->createTable("{{search_keyword_match}}", array(
									  'id_keyword' => 'int(10) unsigned NOT NULL',
									  'type' => 'varchar(32)  NOT NULL',
									  'id' => 'int(10) unsigned NOT NULL',
									  'count' => 'int(10) unsigned NOT NULL',
									  'PRIMARY KEY (`id_keyword`,`type`,`id`)'
		  ));
		
		  $this->getDbConnection()->createCommand()->createTable("{{user}}", array(
									  'id_user' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
									  'username' => 'varchar(50)  NOT NULL',
									  'email' => 'varchar(256)  NOT NULL',
									  'firstname' => 'varchar(50)  NOT NULL',
									  'lastname' => 'varchar(50)  NOT NULL',
									  'password' => 'varchar(32)  NOT NULL',
									  'PRIMARY KEY (`id_user`)',
									  'UNIQUE KEY `username_unique` (`username`)'
		  ));
		
		  $this->getDbConnection()->createCommand()->createTable("{{auth_item}}", array(
		  		'name' => 'varchar(64) not null',
		  		'type' => 'integer not null',
		  		'description' => 'text',
		  		'bizrule' => 'text',
		  		'data' => 'text',
		  		'PRIMARY KEY (`name`)'
		  ));
		  	
		  $this->getDbConnection()->createCommand()->createTable("{{auth_item_child}}", array(
		  		'parent' => 'varchar(64) not null',
		  		'child' => 'varchar(64) not null',
		  		'PRIMARY KEY (`parent`,`child`)',
		  		'INDEX `fk_auth_item_child_auth_item_parent_idx` (`parent`)',
		  		'INDEX `fk_auth_item_child_auth_item_child_idx` (`child`)',
		  ));
		  	
		  $this->getDbConnection()->createCommand()->createTable("{{auth_assignment}}", array(
		  		'itemname' => 'varchar(64) not null',
		  		'userid' => 'varchar(255) not null',
		  		'bizrule' => 'text',
		  		'data' => 'text',
		  		'primary key (`itemname`,`userid`)',
		  		'INDEX `fk_auth_assignment_auth_item_idx` (`itemname`)',
		  ));
		  	
		  $this->getDbConnection()->createCommand()->addForeignKey("fk_auth_item_child_auth_item_parent", "{{auth_item_child}}", "parent", "{{auth_item}}", "name","CASCADE","CASCADE");
		  $this->getDbConnection()->createCommand()->addForeignKey("fk_auth_item_child_auth_item_child", "{{auth_item_child}}", "child", "{{auth_item}}", "name","CASCADE","CASCADE");
		  $this->getDbConnection()->createCommand()->addForeignKey("fk_auth_assignment_auth_item", "{{auth_assignment}}", "itemname", "{{auth_item}}", "name","CASCADE","CASCADE");
		  $this->getDbConnection()->createCommand()->addForeignKey("fk_auth_assignment_admin", "{{auth_assignment}}", "userid", "{{admin}}", "email","CASCADE","CASCADE");
		  
		
		  $this->getDbConnection()->createCommand()->addForeignKey("fk_blog_lang", "{{blog}}", "lang", "{{lang}}", "code","CASCADE","CASCADE");
		
		  $this->getDbConnection()->createCommand()->addForeignKey("fk_blog_page_blog", "{{blog_page}}", "id_blog", "{{blog}}", "id_blog","CASCADE","CASCADE");
		  $this->getDbConnection()->createCommand()->addForeignKey("fk_blog_page_page", "{{blog_page}}", "id_page", "{{page}}", "id_page","CASCADE","CASCADE");
		 
		
		  $this->getDbConnection()->createCommand()->addForeignKey("fk_category_category1", "{{category}}", "id_category_parent", "{{category}}", "id_category","CASCADE","CASCADE");
		
		  $this->getDbConnection()->createCommand()->addForeignKey("fk_menu_lang1", "{{menu}}", "lang", "{{lang}}", "code","CASCADE","CASCADE");
		
		  $this->getDbConnection()->createCommand()->addForeignKey("fk_menu_item_menu1", "{{menu_item}}", "id_menu", "{{menu}}", "id_menu","CASCADE","CASCADE");
		  $this->getDbConnection()->createCommand()->addForeignKey("fk_menu_item_menu_item1", "{{menu_item}}", "id_menu_item_parent", "{{menu_item}}", "id_menu_item","CASCADE","CASCADE");
		
		  $this->getDbConnection()->createCommand()->addForeignKey("fk_blog_author", "{{page}}", "id_author", "{{admin}}", "id_admin","SET NULL","SET NULL");
		  $this->getDbConnection()->createCommand()->addForeignKey("fk_page_category1", "{{page}}", "id_category", "{{category}}", "id_category","SET NULL","SET NULL");
		  $this->getDbConnection()->createCommand()->addForeignKey("fk_page_lang1", "{{page}}", "lang", "{{lang}}", "code","CASCADE","CASCADE");
		  $this->getDbConnection()->createCommand()->addForeignKey("fk_page_page1", "{{page}}", "id_page_parent", "{{page}}", "id_page","CASCADE","CASCADE");
		  $this->getDbConnection()->createCommand()->addForeignKey("fk_id_page_revision", "{{page}}", "id_page_revision", "{{page}}", "id_page","CASCADE","CASCADE");
		
		  $this->getDbConnection()->createCommand()->addForeignKey("fk_page_assoc_lang1", "{{page_assoc}}", "lang_to", "{{lang}}", "code","CASCADE","CASCADE");
		  $this->getDbConnection()->createCommand()->addForeignKey("fk_page_assoc_lang2", "{{page_assoc}}", "lang_from", "{{lang}}", "code","CASCADE","CASCADE");
		  $this->getDbConnection()->createCommand()->addForeignKey("fk_page_has_page_page1", "{{page_assoc}}", "id_page_from", "{{page}}", "id_page","CASCADE","CASCADE");
		  $this->getDbConnection()->createCommand()->addForeignKey("fk_page_has_page_page2", "{{page_assoc}}", "id_page_to", "{{page}}", "id_page","CASCADE","CASCADE");
		
		  $this->getDbConnection()->createCommand()->addForeignKey("bii_page_extra_option_ibfk_1", "{{page_extra_option}}", "id_page", "{{page}}", "id_page","CASCADE","CASCADE");
		
		  $this->getDbConnection()->createCommand()->addForeignKey("fk_search_keyword_lang", "{{search_keyword}}", "lang", "{{lang}}", "code","CASCADE","CASCADE");
		
		  $this->getDbConnection()->createCommand()->addForeignKey("fk_search_keyword_match_id_keyword_idx", "{{search_keyword_match}}", "id_keyword", "{{search_keyword}}", "id_keyword","CASCADE","CASCADE");

		  
		  /* @var $authManager CAuthManager */
		  $authManager = Yii::app()->authManager;
		  	
		  $authManager->createRole('Superadmin');
		  	
		  $authManager->createOperation('viewAuth');
		  $authManager->createOperation('createAuth');
		  $authManager->createOperation('updateAuth');
		  $authManager->createOperation('deleteAuth');
		  	
		  $authManager->createOperation('viewPage');
		  $authManager->createOperation('createPage');
		  $authManager->createOperation('updatePage');
		  $authManager->createOperation('deletePage');
		  	
		  $authManager->createOperation('viewPost');
		  $authManager->createOperation('createPost');
		  $authManager->createOperation('updatePost');
		  $authManager->createOperation('deletePost');
		  	
		  $authManager->createOperation('viewAdministrator');
		  $authManager->createOperation('createAdministrator');
		  $authManager->createOperation('updateAdministrator');
		  $authManager->createOperation('deleteAdministrator');
		  	
		  $authManager->createOperation('viewBackup');
		  $authManager->createOperation('createBackup');
		  $authManager->createOperation('restoreBackup');
		  $authManager->createOperation('deleteBackup');
		  	
		  $authManager->createOperation('viewBlog');
		  $authManager->createOperation('createBlog');
		  $authManager->createOperation('updateBlog');
		  $authManager->createOperation('deleteBlog');
		  	
		  $authManager->createOperation('viewCategory');
		  $authManager->createOperation('createCategory');
		  $authManager->createOperation('updateCategory');
		  $authManager->createOperation('deleteCategory');
		  	
		  $authManager->createOperation('viewLanguage');
		  $authManager->createOperation('updateLanguage');
		  	
		  $authManager->createOperation('viewMenu');
		  $authManager->createOperation('createMenu');
		  $authManager->createOperation('updateMenu');
		  $authManager->createOperation('deleteMenu');
		  	
		  $authManager->createOperation('viewMenuItem');
		  $authManager->createOperation('createMenuItem');
		  $authManager->createOperation('updateMenuItem');
		  $authManager->createOperation('deleteMenuItem');
		  	
		  $authManager->createOperation('viewMigration');
		  $authManager->createOperation('migrateMigration');
		  	
		  $authManager->createOperation('viewUser');
		  $authManager->createOperation('createUser');
		  $authManager->createOperation('updateUser');
		  $authManager->createOperation('deleteUser');
		  
		  return true;
		  
	}
		
	public function updateDb()
	{

			switch ($this->getCurrentVersion())
			{

				case "0.1":
					/*Page assoc*/
					
					//drop foreign key on old lang columns
					$this->getDbConnection()->createCommand()->dropForeignKey("fk_page_assoc_lang1", "{{page_assoc}}");
					//rename lang to lang_to
					$this->getDbConnection()->createCommand()->renameColumn("{{page_assoc}}", "lang", "lang_to");
					//add lang_from column
					$this->getDbConnection()->createCommand()->addColumn("{{page_assoc}}", "lang_from", "VARCHAR(2) NOT NULL AFTER id_page_from");
					//add index on new column
					$this->getDbConnection()->createCommand()->createIndex("fk_page_assoc_lang2_idx", "{{page_assoc}}", "lang_from");
					//fill the new column with correct data
					$this->getDbConnection()->createCommand()->update("{{page_assoc}}", array("lang_from" => new CDbExpression("(SELECT lang from {{page}} WHERE id_page = id_page_from)")));
					//recreate foreign keys
					$this->getDbConnection()->createCommand()->addForeignKey("fk_page_assoc_lang1", "{{page_assoc}}", "lang_to", "{{lang}}", "code","CASCADE","CASCADE");
					$this->getDbConnection()->createCommand()->addForeignKey("fk_page_assoc_lang2", "{{page_assoc}}", "lang_from", "{{lang}}", "code","CASCADE","CASCADE");
					
					$this->getDbConnection()->createCommand()->createIndex("alias_idx", "{{page}}", "alias");
					$this->getDbConnection()->createCommand()->addColumn("{{page}}", "template", "VARCHAR(256) NULL");
					
					// Blog
					
					$this->getDbConnection()->createCommand()->createTable("{{blog}}", array(

								'id_blog' => 'INT UNSIGNED NOT NULL AUTO_INCREMENT',
								'lang' => 'CHAR(2) NOT NULL',
								'name' => 'VARCHAR(512) NOT NULL',
								'alias' => 'VARCHAR(256) NOT NULL',
								'PRIMARY KEY (`id_blog`)',
								'CONSTRAINT `fk_blog_lang` FOREIGN KEY (`lang`) REFERENCES `{{lang}}` (`code`) ON DELETE CASCADE ON UPDATE CASCADE',
								'CONSTRAINT `lang_alias_UNIQUE` UNIQUE (`lang`, `alias`)',
								'INDEX `fk_blog_lang_idx` (`lang` ASC)',
								'INDEX `alias_idx` (`alias` ASC)'
						
					));
					
					$this->getDbConnection()->createCommand()->createTable("{{blog_page}}", array(
					
							'id_blog' => 'INT UNSIGNED NOT NULL',
							'id_page' => 'INT UNSIGNED NOT NULL',
							'CONSTRAINT `fk_blog_page_blog` FOREIGN KEY (`id_blog`) REFERENCES `{{blog}}` (`id_blog`) ON DELETE CASCADE ON UPDATE CASCADE',
							'CONSTRAINT `fk_blog_page_page` FOREIGN KEY (`id_page`) REFERENCES `{{page}}` (`id_page`) ON DELETE CASCADE ON UPDATE CASCADE',
							'INDEX `fk_blog_page_blog_idx` (`id_blog` ASC)',
							'INDEX `fk_blog_page_page_idx` (`id_page` ASC)',
							'PRIMARY KEY (`id_blog`, `id_page`)',
							
					
					));

				case "0.2":

					
					
					$this->getDbConnection()->createCommand("SET foreign_key_checks = 0;")->execute();
										
					$this->getDbConnection()->createCommand()->alterColumn("{{admin}}", 'email', 'VARCHAR(255) NOT NULL');
					$this->getDbConnection()->createCommand()->alterColumn("{{blog}}", 'alias', 'VARCHAR(255) NOT NULL');
					$this->getDbConnection()->createCommand()->alterColumn("{{page}}", 'alias', 'VARCHAR(255) NOT NULL');
					$this->getDbConnection()->createCommand()->alterColumn("{{menu}}", 'alias', 'VARCHAR(255) NOT NULL');
					$this->getDbConnection()->createCommand()->renameColumn("{{menu}}", 'alias', 'anchor');
					
					$this->getDbConnection()->createCommand()->alterColumn("{{lang}}", 'code', 'VARCHAR(10) NOT NULL');
					$this->getDbConnection()->createCommand()->alterColumn("{{admin}}", 'lang', 'VARCHAR(10) NOT NULL');
					$this->getDbConnection()->createCommand()->alterColumn("{{blog}}", 'lang', 'VARCHAR(10) NOT NULL');
					$this->getDbConnection()->createCommand()->alterColumn("{{menu}}", 'lang', 'VARCHAR(10) NOT NULL');
					$this->getDbConnection()->createCommand()->alterColumn("{{page}}", 'lang', 'VARCHAR(10) NOT NULL');
					
					$this->getDbConnection()->createCommand("SET foreign_key_checks = 1;")->execute();
					
					
					//Update meta attributes
					//ehancement #18
					$this->getDbConnection()->createCommand()->alterColumn("{{page}}", 'meta_description', 'VARCHAR(500) NULL');
					$this->getDbConnection()->createCommand()->alterColumn("{{page}}", 'meta_keywords', 'VARCHAR(500) NULL');
					$this->getDbConnection()->createCommand()->alterColumn("{{page}}", 'meta_title', 'VARCHAR(255) NULL');

					
					$this->getDbConnection()->createCommand()->createTable("{{search_keyword}}", array(
							'id_keyword' => 'INT UNSIGNED NOT NULL AUTO_INCREMENT',
							'keyword' => 'VARCHAR(32) NOT NULL',
							'lang' => 'VARCHAR(10) NOT NULL',
							'CONSTRAINT `fk_search_keyword_lang` FOREIGN KEY (`lang`) REFERENCES `{{lang}}` (`code`) ON DELETE CASCADE ON UPDATE CASCADE',
							'INDEX `fk_search_keyword_lang_idx` (`lang` ASC)',
							'INDEX `keyword_idx` (`lang`, `keyword` ASC)',
							'PRIMARY KEY (`id_keyword`)'
					));
					
					$this->getDbConnection()->createCommand()->createTable("{{search_keyword_match}}", array(
							'id_keyword' => 'INT UNSIGNED NOT NULL',
							'type' => 'VARCHAR(32) NOT NULL',
							'id' => 'INT UNSIGNED NOT NULL',
							'count' => 'INT UNSIGNED NOT NULL',
							'CONSTRAINT `fk_search_keyword_match_id_keyword` FOREIGN KEY (`id_keyword`) REFERENCES `{{search_keyword}}` (`id_keyword`) ON DELETE CASCADE ON UPDATE CASCADE',
							'INDEX `fk_search_keyword_match_id_keyword_idx` (`id_keyword` ASC)',
							'PRIMARY KEY (`id_keyword`, `type`, `id`)',
					));
					
					
					$this->getDbConnection()->createCommand()->alterColumn("{{page}}", 'title', 'VARCHAR(255) NOT NULL');
					$this->getDbConnection()->createCommand()->alterColumn("{{page}}", 'alias', 'VARCHAR(255) NOT NULL');
					
					$this->getDbConnection()->createCommand()->addColumn("{{user}}", "username", "VARCHAR(50) NOT NULL AFTER id_user");
					$this->getDbConnection()->createCommand("UPDATE {{user}} SET username = LOWER(CONCAT(firstname, lastname));")->execute();
					$this->getDbConnection()->createCommand()->createIndex("username_UNIQUE","{{user}}","username",true);
					
					//new way to create urls: PageSelector
					$this->getDbConnection()->createCommand()->addColumn('{{menu_item}}', 'link', 'VARCHAR(1024) NOT NULL');
					$this->getDbConnection()->createCommand("UPDATE {{menu_item}} SET link = CONCAT(route, '?', params) WHERE params IS NOT NULL AND params <> '' ;")->execute();
					$this->getDbConnection()->createCommand("UPDATE {{menu_item}} SET link = CONCAT('url:',params)  WHERE route = 'external';")->execute();
					$this->getDbConnection()->createCommand("UPDATE {{menu_item}} SET link = route WHERE route <> 'external' AND params IS NULL OR params = '' ;")->execute();
					$this->getDbConnection()->createCommand()->dropColumn('{{menu_item}}', 'route');
					$this->getDbConnection()->createCommand()->dropColumn('{{menu_item}}', 'params');
					
					
				case "0.3":
					
					$this->getDbConnection()->createCommand()->createTable("{{page_extra_option}}", array(
							'id_page' => 'INT UNSIGNED NOT NULL',
							'name' => 'VARCHAR(32) NOT NULL',
							'value' => 'TEXT',
							'CONSTRAINT `fk_id_page` FOREIGN KEY (`id_page`) REFERENCES `{{page}}` (`id_page`) ON DELETE CASCADE ON UPDATE CASCADE',
							'INDEX `fk_id_page_idx` (`id_page` ASC)',
							'PRIMARY KEY (`id_page`, `name`)',
					));
					
					
					$this->getDbConnection()->createCommand()->dropIndex('keyword_idx', '{{search_keyword}}');
					$this->getDbConnection()->createCommand()->createIndex('keyword_unique_idx', '{{search_keyword}}','lang, keyword',true);
					$this->getDbConnection()->createCommand()->alterColumn("{{search_keyword}}", 'keyword', 'VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL');
					
					$this->getDbConnection()->createCommand()->addColumn("{{admin}}", "editor", "VARCHAR(50) NULL DEFAULT 'ckeditor'");
					
					$this->getDbConnection()->createCommand()->alterColumn("{{page}}", 'meta_robots', 'VARCHAR(256) DEFAULT NULL');
					
					$this->getDbConnection()->createCommand()->addColumn("{{blog}}",'meta_title','varchar(256) DEFAULT NULL');
					$this->getDbConnection()->createCommand()->addColumn("{{blog}}",'meta_keywords','varchar(512) DEFAULT NULL');
					$this->getDbConnection()->createCommand()->addColumn("{{blog}}",'meta_description','varchar(512) DEFAULT NULL');
					$this->getDbConnection()->createCommand()->addColumn("{{blog}}",'meta_robots','varchar(256) DEFAULT NULL');
					
					$this->getDbConnection()->createCommand()->addColumn("{{category}}", 'level', 'INT NOT NULL AFTER `description`');
					$this->getDbConnection()->createCommand()->addColumn("{{category}}", 'left', 'INT NOT NULL AFTER `level`');
					$this->getDbConnection()->createCommand()->addColumn("{{category}}", 'right', 'INT NOT NULL AFTER `left`');
					$this->getDbConnection()->createCommand()->createIndex('left_idx', '{{category}}','left');
					$this->getDbConnection()->createCommand()->createIndex('right_idx', '{{category}}','right');

					$this->getDbConnection()->createCommand()->dropForeignKey('fk_page_category1', "{{page}}");
					$this->getDbConnection()->createCommand()->addForeignKey("fk_page_category1", "{{page}}", "id_category", "{{category}}", "id_category","SET NULL","SET NULL");
					
					$this->getDbConnection()->createCommand()->dropIndex('alias_UNIQUE', "{{page}}");
					$this->getDbConnection()->createCommand()->addColumn("{{page}}", 'id_page_revision', 'INT(10) UNSIGNED NULL');
					$this->getDbConnection()->createCommand()->createIndex('fk_id_page_revision_idx', '{{page}}','id_page_revision');
					$this->getDbConnection()->createCommand()->addForeignKey("fk_id_page_revision", "{{page}}", "id_page_revision", "{{page}}", "id_page","CASCADE","CASCADE");
					$this->getDbConnection()->createCommand()->alterColumn("{{page}}", 'status', 'VARCHAR(32) NOT NULL');
					$this->getDbConnection()->createCommand()->alterColumn("{{page}}", 'lang', 'VARCHAR(10) NULL');
					$this->getDbConnection()->createCommand()->dropColumn("{{page}}", 'creation_date');
					
					$this->getDbConnection()->createCommand()->alterColumn("{{page_assoc}}", 'lang_from', 'VARCHAR(10) NOT NULL');
					$this->getDbConnection()->createCommand()->alterColumn("{{page_assoc}}", 'lang_to', 'VARCHAR(10) NOT NULL');
					
					$this->getDbConnection()->createCommand()->addColumn("{{page}}",'id_author','INT(10) UNSIGNED AFTER `id_page`');
					$this->getDbConnection()->createCommand()->createIndex('fk_page_author_idx', '{{page}}','id_author');
					$this->getDbConnection()->createCommand()->addForeignKey("fk_page_author", "{{page}}", "id_author", "{{admin}}", "id_admin","SET NULL","SET NULL");
					
					
					$this->getDbConnection()->createCommand()->createTable("{{auth_item}}", array(
							'name' => 'varchar(64) not null',
							'type' => 'integer not null',
							'description' => 'text',
							'bizrule' => 'text',
							'data' => 'text',
							'PRIMARY KEY (`name`)'
					));
					
					$this->getDbConnection()->createCommand()->createTable("{{auth_item_child}}", array(
							'parent' => 'varchar(64) not null',
							'child' => 'varchar(64) not null',
							'PRIMARY KEY (`parent`,`child`)',
							'INDEX `fk_auth_item_child_auth_item_parent_idx` (`parent`)',
							'INDEX `fk_auth_item_child_auth_item_child_idx` (`child`)',
					));
					
					$this->getDbConnection()->createCommand()->createTable("{{auth_assignment}}", array(
							'itemname' => 'varchar(64) not null',
							'userid' => 'varchar(255) not null',
							'bizrule' => 'text',
							'data' => 'text',
							'primary key (`itemname`,`userid`)',
							'INDEX `fk_auth_assignment_auth_item_idx` (`itemname`)',
					));
					
					$this->getDbConnection()->createCommand()->addForeignKey("fk_auth_item_child_auth_item_parent", "{{auth_item_child}}", "parent", "{{auth_item}}", "name","CASCADE","CASCADE");
					$this->getDbConnection()->createCommand()->addForeignKey("fk_auth_item_child_auth_item_child", "{{auth_item_child}}", "child", "{{auth_item}}", "name","CASCADE","CASCADE");
					$this->getDbConnection()->createCommand()->addForeignKey("fk_auth_assignment_auth_item", "{{auth_assignment}}", "itemname", "{{auth_item}}", "name","CASCADE","CASCADE");
					$this->getDbConnection()->createCommand()->addForeignKey("fk_auth_assignment_admin", "{{auth_assignment}}", "userid", "{{admin}}", "email","CASCADE","CASCADE");
						
					/* @var $authManager CAuthManager */
					$authManager = Yii::app()->authManager;
					
					$authManager->createRole('Superadmin');
					
					$authManager->createOperation('viewAuth');
					$authManager->createOperation('createAuth');
					$authManager->createOperation('updateAuth');
					$authManager->createOperation('deleteAuth');
					
					$authManager->createOperation('viewPage');
					$authManager->createOperation('createPage');
					$authManager->createOperation('updatePage');
					$authManager->createOperation('deletePage');
					
					$authManager->createOperation('viewPost');
					$authManager->createOperation('createPost');
					$authManager->createOperation('updatePost');
					$authManager->createOperation('deletePost');
					
					$authManager->createOperation('viewAdministrator');
					$authManager->createOperation('createAdministrator');
					$authManager->createOperation('updateAdministrator');
					$authManager->createOperation('deleteAdministrator');
					
					$authManager->createOperation('viewBackup');
					$authManager->createOperation('createBackup');
					$authManager->createOperation('restoreBackup');
					$authManager->createOperation('deleteBackup');
					
					$authManager->createOperation('viewBlog');
					$authManager->createOperation('createBlog');
					$authManager->createOperation('updateBlog');
					$authManager->createOperation('deleteBlog');
					
					$authManager->createOperation('viewCategory');
					$authManager->createOperation('createCategory');
					$authManager->createOperation('updateCategory');
					$authManager->createOperation('deleteCategory');
					
					$authManager->createOperation('viewLanguage');
					$authManager->createOperation('updateLanguage');
					
					$authManager->createOperation('viewMenu');
					$authManager->createOperation('createMenu');
					$authManager->createOperation('updateMenu');
					$authManager->createOperation('deleteMenu');
					
					$authManager->createOperation('viewMenuItem');
					$authManager->createOperation('createMenuItem');
					$authManager->createOperation('updateMenuItem');
					$authManager->createOperation('deleteMenuItem');
					
					$authManager->createOperation('viewMigration');
					$authManager->createOperation('migrateMigration');
					
					$authManager->createOperation('viewUser');
					$authManager->createOperation('createUser');
					$authManager->createOperation('updateUser');
					$authManager->createOperation('deleteUser');
						
					foreach(Admin::model()->findAll() as $admin)
						$authManager->assign('Superadmin', $admin->email);
					
				
			}
			
			return true;
			
		
		
	}
	
	public function afterUpdate()
	{
		$controllers = Yii::getPathOfAlias('application.modules.admin.controllers');
		
		if(@rename($controllers . '/MigrationController.php', $controllers . '/MigrationController.old.php'))
			if(rename($controllers . '/MigrationController.new.php', $controllers . '/MigrationController.php'))
				@unlink($controllers . '/MigrationController.old.php');
		
		return parent::afterUpdate();
	}
	
}
