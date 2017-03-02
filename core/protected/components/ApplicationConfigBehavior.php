<?php
class ApplicationConfigBehavior extends CBehavior
{
	
	public $currentRoute;
	
	
	/**
	 * Declares events and the event handler methods
	 * See yii documentation on behavior
	 */
	public function events()
	{
		return array_merge(parent::events(), array(
				'onBeginRequest'=>'beginRequest',
				'onEndRequest' => 'endRequest',
		));
	}


	public function getVersion()
	{
		return "0.4.2";
	} 
	
	/**
	 * Load configuration that cannot be put in config/main
	 */
	public function beginRequest()
	{
		Yii::beginProfile('application.components.ApplicationConfigBehavior.processRequest','application.components.ApplicationConfigBehavior');
		//if in maintenance mode
		if(Yii::app()->catchAllRequest !== null)
		{
			//if admin is already connected, ignore
			if(!Yii::app()->userAdmin->isGuest)
				Yii::app()->catchAllRequest = null;
			else
			{
				//try to detect if the user is not trying to access the admin
				
				$base = Yii::app()->request->requestUri; //request URI
	
				//remove basePath from request URI
				if(Yii::app()->baseUrl !== '')
					$base = substr($base, Yii::app()->baseUrl);
			
				$base = trim($base,'/');
				
				if(strpos($base, '/') !== false)
					$base = substr($base, 0, strpos($base, '/')); //just need the first segment of URI
								
				//if we are trying to access admin
				if($base ===  Yii::app()->params->adminPath)
						Yii::app()->catchAllRequest = null;
			
			}
			
		}
		
		//generate the url rules
		if(Yii::app()->languageManager->addLangInUrl)
		{
			
			$prefix = Yii::app()->urlManager->langPrefix;
			$regex = Yii::app()->languageManager->regex;
			$prefix = str_replace("<lang>", "<lang:{$regex}>",$prefix);
			
			Yii::app()->urlManager->addRules(array(
		
					'dafault' => array('site/index',
										'pattern' => Yii::app()->urlManager->defaultPattern,
										'defaultParams' => array('lang' => Yii::app()->languageManager->default)
									),
								
					
					'lang' => array('site/index',
									'pattern' => $prefix,)
													
			 			),false);
				
				
					Yii::app()->urlManager->addRules(
									array(
										$prefix . "/<action:\w+>" => 'site/<action>',
										$prefix . "/<controller:\w+>/<action:\w+>" => '<controller>/<action>',
										$prefix . "/<module:(?!admin)[\w\/]+>/<controller:\w+>/<action:\w+>" => '<module>/<controller>/<action>',
									)
				
			 			);
				
		
		
		}
		else
		{
		
			Yii::app()->urlManager->addRules(array(

												'dafault' => array('site/index',
														'pattern' => Yii::app()->urlManager->defaultPattern,
												),
			
										),false);
					
					
						Yii::app()->urlManager->addRules(
						array(
								"<action:\w+>" => 'site/<action>',
								"<controller:\w+>/<action:\w+>" => '<controller>/<action>',
								"<module:(?!admin)\w+>/<controller:\w+>/<action:\w+>" => '<module>/<controller>/<action>',
						)
			
					);
		}
		
		
				
		

	}
	
	public function endRequest()
	{
		Yii::endProfile('application.components.ApplicationConfigBehavior.processRequest','application.components.ApplicationConfigBehavior');
	}
	
	
}