<?php

class ThemeConfig extends CActiveRecord
{
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	

	public function tableName()
	{
		return '{{theme_config}}';
	}
	

	public function rules()
	{
	
		return array(
				array('name', 'required'),
				array('value', 'safe'),
		);
	}
	
	public function defaultScope()
	{
		return array(
					
				'index' => 'name'
	
		);
	}
	
}