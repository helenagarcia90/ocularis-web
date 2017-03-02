<?php

class AdminModule extends CWebModule
{
	
	public $assets = null;
	public $layout = 'main';
	
	public function init()
	{

		Yii::beginProfile('application.admin.AdminModule.init','application.admin.AdminModule');
		
		Yii::app()->setComponents(array(
		
				//separate admin's user from front end's user
				'user' => array(
					'class' => 'AdminWebUser',
					'allowAutoLogin' => true,
					'loginUrl' => array('/admin/site/login'),
				),
								
				'errorHandler'=>array(
						//use admin's error page
						'errorAction'=>'admin/site/error',
				),
				
				'widgetFactory' => array(

					'widgets' => array(
					
						'CLinkPager' => array('cssFile' => false, 'htmlOptions' => array('class' => 'pager'), 'header' => '')
					
					)
				
				)
		
		));
		
		Yii::app()->language = 'en';
		
		$this->assets = Yii::app()->assetManager->publish(
				Yii::getPathOfAlias('application.modules.admin.assets')
		);
		
		$this->setImport(array(
			'admin.components.*',
			'admin.components.widgets.*',
			'admin.components.widgets.dashboard.*',
			'admin.components.form.*',
			'admin.components.form.widgets.*',
			'admin.components.widgets.grid.*',
			'admin.models.*',
		));
		
		
		$modules = Yii::app()->modules;
		
		unset($modules['admin']);
	
		$this->setModules($modules);
		
		Yii::app()->params->pageSize = 20;
		
		if(!Yii::app()->user->isGuest && Yii::app()->user->hasState('lang') && isset(Yii::app()->params->adminLangs[Yii::app()->user->lang]))
			Yii::app()->language = Yii::app()->user->lang;
		else
		{
				if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
				{
					$langs = explode(",",$_SERVER['HTTP_ACCEPT_LANGUAGE']);
					foreach($langs as $langTest)
					{
						$langTest = substr($langTest, 0 ,2);
						if( Yii::app()->languageManager->has($langTest) )
						{
							Yii::app()->language = $langTest;
							break;
						}
							
					}
				}
			
		}
		
		Yii::endProfile('application.admin.AdminModule.init','application.admin.AdminModule');
	}

	
}
