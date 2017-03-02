<?php

class PageExtraOption extends CActiveRecord
{
	
	/**
	 * @property string id_page
	 * @property string name
	 * @property string value
	 */
	
	
	/**
	 * 
	 * @param system $className
	 * @return PageExtraOption
	 */
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{page_extra_option}}';
	}
	
	public function rules(){
		
		return array(
				array('id_page, name, value', 'safe')
		);
		
	}
	
	public function relations()
	{
		
		return array(
			
				'page' => array(self::BELONGS_TO, 'Page', 'id_page'),
				
		);
		
	}
	
	
}

