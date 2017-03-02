<?php

/**
 * This is the model class for table "admin".
 *
 * The followings are the available columns in table 'admin':
 * @property string $component
 * @property string $version
 * @property string $time
  */
class MigrationModel extends CActiveRecord
{

	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{migration}}';
	}
	
	public function getComponent_name()
	{
		
		if($this->component === 'core')
			return Yii::t('migration', 'Core');
		elseif( ($component = Yii::app()->moduleManager->getModuleConfig($this->component)) !== null)
			return $component->name;
		
		return $this->component;
	}
	

	public function getAttributeLabels()
	{
		return array(
			'display_version' => Yii::t('migration', 'Version'),
			'component_name' => Yii::t('migration', 'Name'),
		);
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
