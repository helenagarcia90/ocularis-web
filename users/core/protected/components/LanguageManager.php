<?php
 class LanguageManager extends CApplicationComponent
 {
 	
 	public $forceLangInUrl = true;
 	
 	private $_langs = array();
 	private $_default;
 	private $_regex;
 	
 
 	 	
 	public function init()
 	{
	 		Yii::beginProfile('application.components.LanguageManager.init','application.components.LanguageManager');
	 		
	 		$langs = Lang::model()->active()->findAll();
	 		
	 		//load the languages
	 		foreach($langs as $lang)
	 		{
	 			$this->_langs[$lang->code] = $lang->name;
	 		
	 			if($lang->default == 1)
	 			{
	 					
	 				Yii::app()->language = $this->_default = $lang->code;
	 		
	 			}
	 		}
	 		
	 		$this->_regex = implode("|",array_keys($this->_langs));
	 		
	 		//for backward compatibility
	 		Yii::app()->params->langs = $this->_langs;
	 		
			Yii::endProfile('application.components.LanguageManager.init','application.components.LanguageManager');
 	}
 	
 	public function has($lang)
 	{
 		return key_exists($lang, $this->_langs);
 	}
 	
 	public function getLangs()
 	{
 		return $this->_langs;
 	}
 	
 	public function getDefault()
 	{
 		return $this->_default;	
 	}
 	 	
 	public function getAddLangInUrl()
 	{
 		return $this->forceLangInUrl || count($this->_langs) > 1;
 		
 	}
 	
 	public function getRegex()
 	{
 		return $this->_regex;
 	}
 	
 	public function getUrlRutePrefix()
 	{
 		return "(?P<lang>{$this->regex})\/";
 	}
 	
 }