<?php

abstract class MultiLangActiveRecord extends CActiveRecord{
	
	
	
	private $_transaction;
	private $_translations = array();
	
	private $_langAttributes = array();
	private static $_langColumns;
	
	public abstract function translatedAttributes();
	
	
	public function __construct($scenario = 'insert')
	{
		
		parent::__construct($scenario);
		
		if(!$this->metaData->hasRelation('i18n'))
		{
			$this->metaData->addRelation('i18n', array(self::HAS_MANY,
														get_class($this) . 'I18n',
														$this->tableSchema->primaryKey,
														'index' => 'lang'
													)
										);
			$this->with(array( 'i18n' => array()));
			
			//set the available columns
			foreach($this->translatedAttributes() as $attr)
			{
				foreach(Yii::app()->params->langs as $code => $lang)
				{
					self::$_langColumns[$attr.'_'.$code] = true;
				}		
			}
			
			
		}
		
		
	}
	
	/**
	 * Will only fetch the localized translation
	 * @return MultiLangActiveRecord
	 */
	
	public function localized()
	{
		
		return $this->with(array( 'i18n' => array( 'on' => 'i18n.lang = :lang', 'params' => array('lang' => Yii::app()->language) )));
		
	}
	
	public function __set($name, $value)
	{

		if(isset(self::$_langColumns[$name]))
		{
			$this->_langAttributes[$name] = $value;
		}
		else		
			parent::__set($name, $value);
					
	} 
	
	public function __get($name)
	{
		
		if(in_array($name, $this->translatedAttributes()))
		{

			if(isset($this->i18n[Yii::app()->language])) //value from relation
				return $this->i18n[Yii::app()->language]->$name;
			else
				return null;
			
		}
		elseif(isset(self::$_langColumns[$name])) //this is a translated attribute
		{
			
			if(isset($this->_langAttributes[$name])) //value was set directly (e.g: in form)
				return $this->_langAttributes[$name];
			else
			{
				
				//extract attribute name and language
				$pos = strrpos($name, '_');
				$attr = substr($name, 0, $pos);
				$lang =substr($name, $pos+1);
				
				if(isset($this->i18n[$lang])) //value from relation
					return $this->i18n[$lang]->$attr;
				else
					return null;
				
			}
				
				
		}
		else
			return parent::__get($name);
		
	}
	
	public function __isset($name)
	{
			
		if(isset(self::$_langColumns[$name]) || (in_array($name, $this->translatedAttributes()) && isset($this->i18n[Yii::app()->language]->$name)  ) )
			return true;
		else
			return parent::__isset($name);
	
	}
	
	public function getSafeAttributeNames()
	{
		$attributes = parent::getSafeAttributeNames();
		
		$class = get_class($this) . 'I18n';
		
		$model = new $class();
		
		foreach($model->getSafeAttributeNames() as $attr)
		{
			foreach( Yii::app()->languageManager->langs as $lang => $language)
				$attributes[] = $attr . '_' . $lang;
		}
		
		return $attributes;
	}
	
	public function getAttributeLabel($attribute)
	{
		
		if(isset(self::$_langColumns[$attribute]))
		{
			//extract attribute name and language
			$pos = strrpos($attribute, '_');
			$attr = substr($attribute, 0, $pos);
			$lang =substr($attribute, $pos+1);
			
			$class = get_class($this) . 'I18n';
			$model = new $class();
			return $model->getAttributeLabel($attr);
			
		}
		else
			return parent::getAttributeLabel($attribute);
		
	}
	
	public function afterValidate()
	{
		
		
		parent::afterValidate();
		
		//validate the translations
		$class = get_class($this) . 'I18n';
		$pk = $this->tableSchema->primaryKey;
		
		foreach( Yii::app()->languageManager->langs as $lang => $language)
		{
				
			$model = new $class();
			$model->$pk = $this->$pk;
			$model->lang = $lang;
				
			foreach($this->translatedAttributes() as $attr)
			{
				if(isset($this->_langAttributes["{$attr}_{$lang}"]))
					$model->$attr = $this->_langAttributes["{$attr}_{$lang}"];
				else
					$model->$attr = null;
			}
				

			
			
			if(!$model->validate())
			{
				$this->addErrors($model->errors);
			}
			
			$this->_translations[$lang] = $model;
			
				
		}
		
		
	}
	
	public function beforeSave()
	{
		
		//check if transaction already exist.
		//if not, begin transaction and save it
		if(Yii::app()->db->currentTransaction === null)
			$this->_transaction = Yii::app()->db->beginTransaction();
		
		return parent::beforeSave();
	}
	
	
	public function afterSave()
	{
		
		$class = get_class($this) . 'I18n';
		$model = new $class();
		$model->deleteAllByAttributes(array($this->tableSchema->primaryKey => $this->primaryKey));
		
		
		foreach($this->_translations as $i18n)
		{
			$i18n->{$this->tableSchema->primaryKey} = $this->primaryKey;
			$i18n->save();
		}
		
		//if the transaction exists AND it has been created in beforeSave() (it is saved in $this->_trnasaction), commit it.
		if($this->_transaction !== null)		
			$this->_transaction->commit();
		
		return parent::afterSave();
		
	}
	
}