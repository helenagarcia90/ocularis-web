<?php


class HomeBlockI18n extends CActiveRecord
{
	
	/**
	 * @property integer id_home_block
	 * @property string title
	 * @property string lang
	 * @property string content
	 * @property string link
	 * @property string image
	 * @property string active
	 * 
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
		return '{{home_block_lang}}';
	}
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
	
		return array(
				array('title, content, lang', 'required'),
				array('link', 'PageSelectorValidator'),
				array('link', 'default', 'value' => null),
				array('title', 'length', 'max'=>256),
		);
	}
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
				'title' => Yii::t('HomeBlocksModule.main','Title'),
				'content' => Yii::t('HomeBlocksModule.main','Content'),
				'link' => Yii::t('HomeBlocksModule.main','Link'),
				'image' => Yii::t('HomeBlocksModule.main','Image'),
		);
	}
	
	
}