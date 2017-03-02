<?php


class HomeBlock extends MultiLangActiveRecord
{
	
	/**
	 * @property integer id_home_block
	 * @property string title
	 * @property string lang
	 * @property string content
	 * @property string link
	 * @property string target
	 * @property string image
	 * @property string active
	 * 
	 */
	
	public $active = 1;
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{home_block}}';
	}
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
	
		return array(
				array('active', 'required'),
				array('position', 'numerical'),
				array('target, image, position',  'safe'),
				array('active', 'default', 'value' => 1),
		);
	}
	
	public function scopes()
	{
		return array(
			
				'active' => array( 'condition' => '`active`= 1' ),
				
		);
	}

	public function translatedAttributes()
	{
		return array('title', 'content', 'link');
	}
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
				'id_home_block' => 'ID',
				'active' => Yii::t('HomeBlocksModule.main','Status'),
		);
	}

}