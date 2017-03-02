<?php

/**
 * This class extends CPhpMessageSource and loads additional translations from the theme's folder.
 * The them can, that way, use additional translationas well as override a default translation
 * @author Benoît
 *
 */

class PhpMessageSource extends CPhpMessageSource
{
	
	private $_themeFiles = array();
	
	/**
	 * Determines the message file name based on the given category and language for the current theme
	 * If the category name contains a dot, it will be split into the module class name and the category name.
	 * In this case, the message file will be assumed to be located within the 'messages' subdirectory of
	 * the directory containing the module class file.
	 * Otherwise, the message file is assumed to be under the {@link basePath}.
	 * @param string $category category name
	 * @param string $language language ID
	 * @return string the message file path
	 */
	protected function getMessageThemeFile($category,$language)
	{
		if(Yii::app()->theme === null)
			return null;
		
		if(!isset($this->_themeFiles[$category][$language]))
		{
			if(($pos=strpos($category,'.'))!==false)
			{
				$extensionClass=substr($category,0,$pos);
				$extensionCategory=substr($category,$pos+1);
				
				$this->_themeFiles[$category][$language]=Yii::app()->theme->basePath.DIRECTORY_SEPARATOR."messages".DIRECTORY_SEPARATOR.$extensionClass.DIRECTORY_SEPARATOR.$language.DIRECTORY_SEPARATOR.$extensionCategory.'.php';
			}
			else
				$this->_themeFiles[$category][$language]=Yii::app()->theme->basePath.DIRECTORY_SEPARATOR."messages".DIRECTORY_SEPARATOR.$language.DIRECTORY_SEPARATOR.$category.'.php';
		}
		return $this->_themeFiles[$category][$language];
	}
	
	/**
	 * Loads the message translation for the specified language and category for the current theme
	 * @param string $category the message category
	 * @param string $language the target language
	 * @return array the loaded messages
	 */
	protected function loadThemeMessages($category,$language)
	{
		
		if(Yii::app()->theme === null)
			return array();
		
		$messageFile=$this->getMessageThemeFile($category,$language);
	
		if($this->cachingDuration>0 && $this->cacheID!==false && ($cache=Yii::app()->getComponent($this->cacheID))!==null)
		{
			$key=self::CACHE_KEY_PREFIX . $messageFile;
			if(($data=$cache->get($key))!==false)
				return unserialize($data);
		}
	
		if(is_file($messageFile))
		{
			$messages=include($messageFile);
			if(!is_array($messages))
				$messages=array();
			if(isset($cache))
			{
				$dependency=new CFileCacheDependency($messageFile);
				$cache->set($key,serialize($messages),$this->cachingDuration,$dependency);
			}
			return $messages;
		}
		else
			return array();
	}
	
	protected function loadMessages($category,$language)
	{
		return CMap::mergeArray(parent::loadMessages($category, $language), 
								$this->loadThemeMessages($category, $language));
	}
	
}