<?php

class PanelMenu extends CMenu{
	
	public $activateParents = true;
	
	public function init()
	{
		
		$this->items = array(
				array(
						'label'=> Yii::t('app','Dashboard'),
						'iconClass' => 'glyphicon glyphicon-dashboard',
						'url'=>array('/admin/site/index')),
		
				'users' => array(
						'label'=> Yii::t('user','Users') . '<span class="badge pull-right">'.User::model()->count().'</span>',
						'iconClass' => 'glyphicon glyphicon-user',
						'url'=>array('/admin/user/index'),
						'visible' => Yii::app()->params->enableUsers && Yii::app()->user->checkAccess('viewUser'),
				),
		
				array(	'label'=> Yii::t('app','Content'),
						'url' => '',
						'iconClass' => 'glyphicon glyphicon-pencil',
						'visible' => Yii::app()->user->checkAccess('viewPage') || Yii::app()->user->checkAccess('viewCategory') || Yii::app()->user->checkAccess('viewBlog'),
						'items' =>array(
								array(		'label' => Yii::t('page','Pages') . '<span class="badge pull-right">'.Page::model()->count('status <> :status AND type = :type', array('type' => Page::TYPE_CMS, 'status' => Page::STATUS_TRASH)).'</span>',
										 	'url'=>array('/admin/page/index'),
											'visible' => Yii::app()->user->checkAccess('viewPage'),
								),
								array(		'label' => Yii::t('page','Categories') . '<span class="badge pull-right">'.Category::model()->count().'</span>',
											'url'=>array('/admin/category/index'),
											'visible' => Yii::app()->user->checkAccess('viewCategory'),
								),
								array(
											'label' => Yii::t('blog','Blogs') . '<span class="badge pull-right">'.Blog::model()->count().'</span>',
											'url'=>array('/admin/blog/index'),
											'visible' => Yii::app()->user->checkAccess('viewBlog'),
								),
						)
				),
		
		);
		
		foreach(Yii::app()->moduleManager->getAdminMenuItems('root') as $item)
		{
			$this->items[] = $item;
		}
		
		
		$modules = array(	'label'=> Yii::t('app','Modules'),
				'iconClass' => 'glyphicon glyphicon-tower',
				'url' => '',
				'items' => Yii::app()->moduleManager->getAdminMenuItems('modules') );
		
			
		
		
		if(count($modules['items']) > 0)
		{
			$this->items[] = $modules;
		}
		
		
		$this->items['appearance'] = array(
				'label'=> Yii::t('app','Appearance'),
				'url' => '',
				'iconClass' => 'fa fa-tint',
				'visible' => Yii::app()->user->checkAccess('viewMenu'),
				'items' =>array(
						array(	'label' => Yii::t('menu','Menus') . '<span class="badge pull-right">'.Menu::model()->count().'</span>',
								'url'=>array('/admin/menu/index'),
								'visible' => Yii::app()->user->checkAccess('viewMenu'),
						),
				)
		);
		
		$this->items['tools'] = array(
				'label' => Yii::t('app','Tools'),
				'iconClass' => 'glyphicon glyphicon-wrench',
				'url' => '',
				'items' => array(
						array('label' => Yii::t('mediaManager', 'Media Manager'),
								'itemOptions' => array('id' => 'menuOpenMediaManager'),
								'url' => array('/admin/mediaManager/browse')),
						array('label' => Yii::t('backup', 'Backups'),
								'url' => array('/admin/backup/index'),
								'visible' => Yii::app()->user->checkAccess('viewBackup'),),
				),
		);
		
		$this->items['config'] = array(
		
				'label'	=> Yii::t('app','Configuration'),
				'iconClass' => 'glyphicon glyphicon-cog',
				'visible' => Yii::app()->user->checkAccess('viewLanguage') || Yii::app()->user->checkAccess('viewMigration') || Yii::app()->user->checkAccess('viewAdministrator') || Yii::app()->user->checkAccess('viewAuth'),
				'url' => '',
				'items' =>array(
		
						array(	'label' => Yii::t('lang','Languages'),
								'url'=>array('/admin/language/index'),
								'visible' => Yii::app()->user->checkAccess('viewLanguage'),
							),
		
						array(	'label' => Yii::t('migration','Updates'),
								'url'=>array('/admin/migration/index'),
								'visible' => Yii::app()->user->checkAccess('viewMigration'),
							),
		
						array(	'label' => Yii::t('admin','Administrators') . '<span class="badge pull-right">'.Admin::model()->count().'</span>',
								'url'=>array('/admin/administrator/index'),
								'visible' => Yii::app()->user->checkAccess('viewAdministrator'),
						),
						
						array(	'label' => Yii::t('auth','Permissions'),
								'url'=>array('/admin/auth/index'),
								'visible' => Yii::app()->user->checkAccess('viewAuth'),
						),
		
				)
		);
		
		foreach(Yii::app()->moduleManager->getAdminMenuItems('config') as $item)
		{
			$this->items['config']['items'][] = $item;
		}
		

		Yii::app()->clientScript->registerScript('mainMenu', "
		
				$('#menuOpenMediaManager a').on('click',function(e){
					e.preventDefault();
		
					openMediaManager('');
				});
		
				");
		
		
		parent::init();
		
	}
	
	protected function renderMenuItem($item)
	{
		
		if(isset($item['items']) && count($item['items']) > 0)
		{
			
			if($item['active'])
				$class = 'glyphicon-chevron-down';
			else
				$class = 'glyphicon-chevron-right';
			
			$item['label'] .= CHtml::tag('span', array('class' => 'glyphicon '.$class.' pull-right'),'');
			 
		}
		
		if(isset($item['iconClass']))
		{
			$item['label'] = CHtml::tag('span', array('class' => $item['iconClass']),'') . CHtml::tag('span', array('class' => 'description'), '&nbsp;' . $item['label']);
		}
					
		return parent::renderMenuItem($item);
		
	}
		
}