<?php

/**
 * This is the model class for table "page_assoc".
 *
 * The followings are the available columns in table 'page_assoc':
 * @property string $id_page_from
 * @property string $id_page_to
 * @property string $lang_from
 * @property string $lang_to
 *
 * The followings are the available model relations:
 * @property Page $idPageFrom
 * @property Page $idPageTo
 * @property Lang $language
 */
class PageAssoc extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return PageAssoc the static model class
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
		return '{{page_assoc}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_page_to, id_page_from, lang_from, lang_to', 'required'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'pageFrom' => array(self::BELONGS_TO, 'Page', 'id_page_from'),
			'pageTo' => array(self::BELONGS_TO, 'Page', 'id_page_to'),
			'langFrom' => array(self::BELONGS_TO, 'Lang', 'lang_from'),
			'langTo' => array(self::BELONGS_TO, 'Lang', 'lang_to'),
		);
	}

}