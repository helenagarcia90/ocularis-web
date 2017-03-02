<?php

/**
 * This is the model class for table "menu".
 *
 * The followings are the available columns in table 'menu':
 * @property string $id_menu
 * @property string $lang
 * @property string $name
 * @property string $anchor
 * @property string $active
 *
 * The followings are the available model relations:
 * @property Lang $lang
 * @property MenuItem[] $menuItems
 * @property integer $menuItemsCount
 */
class Menu extends CActiveRecord
{
	
	public $active = 1;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Menu the static model class
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
		return '{{menu}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('lang, name, anchor, active', 'required'),
			array('name', 'length', 'max'=>50),
			array('anchor', 'length', 'max'=>256),
			
			array('anchor','checkUniqueMenu'),
				
			array('id_menu, lang, name', 'safe', 'on'=>'search'),
		);
	}
	
	public function checkUniqueMenu($attribute)
	{
		if($attribute === 'anchor')
		{
	
			$params = array(
					':lang' => $this->lang,
					':anchor' => $this->anchor,
			);
				
			if(!$this->isNewRecord)
				$params[':id_menu'] = $this->id_menu;
				
			$exists = Menu::model()->exists('lang = :lang AND anchor = :anchor'. (!$this->isNewRecord ? ' AND id_menu <> :id_menu' : ' '),
					$params);
				
			if($exists)
			{
				$this->addError('anchor', Yii::t('menu', 'The menu anchor must be unique for each language'));
			}
				
		}
	}
	

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'language' => array(self::BELONGS_TO, 'Lang', 'lang'),
			'menuItems' => array(self::HAS_MANY, 'MenuItem', 'id_menu'),
			'menuItemsCount' => array(self::STAT, 'MenuItem', 'id_menu'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_menu' => 'Id Menu',
			'lang' => Yii::t('app','Language'),
			'name' => Yii::t('menu','Name'),
			'anchor' => Yii::t('menu','Anchor'),
		);
	}

	public static function getListData()
	{
		return CHtml::listData(self::model()->findAll(),'id_menu','name');
	}
	
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id_menu',$this->id_menu,true);
		$criteria->compare('lang',$this->lang,true);
		$criteria->compare('name',$this->name,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}