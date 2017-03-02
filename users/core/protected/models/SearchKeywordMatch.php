<?php

/**
 * This is the model class for table "search_keyword".
 *
 * The followings are the available columns in table 'menu':
 * @property string $id_keyword
 * @property string $type
 * @property string $id
 *  @property string $count
 *  */
 
class SearchKeywordMatch extends CActiveRecord
{
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{search_keyword_match}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{

		return array(
			array('id_keyword, type, id, count', 'required'),
		);
	}
	
		

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{

		return array(
			'language' => array(self::BELONGS_TO, 'SearchKeyword', 'id_keyword'),
		);
	}

}