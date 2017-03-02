<?php

class UrlManager extends CUrlManager
{
	/**
	 * The Language prefix to use for Urls.
	 * If null, thelanguage will be happend to the baseUrl.
	 * Example of usage: http://<lang>.domain.com/
	 * @var $langPrefix
	 */
	public $langPrefix = null;
	
	/**
	 * The default pattern for urls (the one of the default language)
	 * If '', http://www.domain.com/ will be the default language
	 * It is used when using $langPrefix.  
	 * Example of usage:
	 * http://<lang>.domain.com/ is used as $langPrefix and http://www.domain.com/ as defaultPattern (the default language)
	 * @var $langPrefix
	 */
	public $defaultPattern = '';
	
	public function init()
	{
		if($this->langPrefix === null)
			$this->langPrefix = '<lang>';
		
		if(strpos($this->langPrefix, "<lang>")===false)
			throw new CException("The languagePrefix must contain the token '<lang>'");
		
		parent::init();
	}
	
	public function createUrl($route,$params=array(),$ampersand='&')
	{
		//prepend lang
		Yii::beginProfile('application.components.UrlManager.createUrl','application.components.UrlManager');
		
		 if(substr(trim($route,"/"),0,5) !== 'admin' && Yii::app()->languageManager->addLangInUrl && !isset($params['lang'])) {
            $params['lang']=Yii::app()->language;
	      }
		
		try 
		{
			
			$url = parent::createUrl($route,$params,$ampersand);
			
			Yii::endProfile('application.components.UrlManager.createUrl','application.components.UrlManager');
			
			return $url;
		}
		catch(NoTranslationException $e) //in case of an invalid url set url to home
		{
			if(isset($params['lang']))
				$params = array('lang' => $params['lang']);
			else
				$params = array();
			
			//return the home url in case the url is invalid
			$url = parent::createUrl("/site/index",$params,$ampersand);
			
			Yii::endProfile('application.components.UrlManager.createUrl','application.components.UrlManager');
				
			return $url;
		}
		
	}
	
	public function parseUrl($request)
	{
		
				Yii::beginProfile('application.components.UrlManager.parseUrl','application.components.UrlManager');
		
				$route = parent::parseUrl($request);
				Yii::app()->currentRoute = $route;
				
				//if we use languages
				if(substr(trim($route,"/"),0,5) !== 'admin' && Yii::app()->languageManager->addLangInUrl)
				{
						//if index with default language and referrer is from outside, let's check if we can redirect the user to his language
						if($route === 'site/index' && isset($_GET['lang']) && $_GET['lang'] === Yii::app()->languageManager->default && (Yii::app()->request->urlReferrer === null || strpos(Yii::app()->request->urlReferrer,Yii::app()->getBaseUrl(true)) === false ))
						{
							//index and no lang set, check if the user has another prefered language 
							if (!isset(Yii::app()->request->cookies['lang']) || empty(Yii::app()->request->cookies['lang']))
							{
								$prefferredLang = null;
								if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) //but try to detect the preferred language
								{
									$langs = explode(",",$_SERVER['HTTP_ACCEPT_LANGUAGE']);
									foreach($langs as $lang)
									{
										$lang = substr($lang, 0 ,2);
										//if prefered language of user is not the current one but is in the installed languages, redirect to the language
										if( Yii::app()->languageManager->has($lang) )
										{
											$prefferredLang = $lang;
											break;
										}
										
									}
								}
							}
							else
							{
								$prefferredLang = Yii::app()->request->cookies['lang'];
							}
							
							//the user does not use the default language, redirect him
							if(isset($prefferredLang) && $prefferredLang != Yii::app()->languageManager->default)
							{
								$params = CMap::mergeArray($_GET, array('lang' => $prefferredLang));
								Yii::app()->getRequest()->redirect(Yii::app()->createUrl('site/index',$params));
							}
							
						}
					
					
						//language is set in the url
						if(isset($_GET['lang']) && Yii::app()->languageManager->has($_GET['lang'])) {
														
							Yii::app()->language = $_GET['lang'];
							
							$cookie = new CHttpCookie('lang', $_GET['lang']);
						
							$cookie->expire = time() + (60*60*24*365); // (1 year)
							Yii::app()->request->cookies['lang'] = $cookie;
						}
						
						
				}
				
				Yii::endProfile('application.components.UrlManager.parseUrl','application.components.UrlManager');
				return $route;
	
		
	}
	
	protected function createUrlRule($route, $pattern)
	{
		
		if(is_array($route) && isset($route['class']))
		{
			$r = Yii::createComponent($route);
			
			if($r instanceof PatternUrlRule)
				$r->init($this);
			
			return $r;
				
		}
		else 
			return parent::createUrlRule($route, $pattern);
		
	}
	
}