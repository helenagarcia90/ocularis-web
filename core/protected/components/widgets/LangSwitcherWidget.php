<?php


class LangSwitcherWidget extends CWidget{
	
	private $links = array();
	
	public function run(){
		
		if(count(Yii::app()->languageManager->langs)<=1)
			return;
		
		foreach(Yii::app()->languageManager->langs as $lang => $name)
		{
		
			
			$route = Yii::app()->currentRoute;
			$params = $_GET;
			
			if(Yii::app()->errorHandler->error !== null)
			{
				$params = array();
				$route = '/site/index';
			}
			elseif($route === 'site/index')
			{
				//if home, only use query params
				parse_str(Yii::app()->request->queryString,$params);
			}
			
						
			$params['lang'] = $lang;
			
			$this->links[$lang] = Yii::app()->createUrl($route,$params);
		}
		
			$this->render("langSwitcher",
				array(
						'links' => $this->links,
					));
		
		
	}
	
	
	
}