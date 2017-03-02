<?php

/**
 * This is the model class for table "lang".
 *
 * The followings are the available columns in table 'lang':
 * @property string $name
 * @property string $code
 * @property string $default
 * @property string $active
 *
 */
class Lang extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Lang the static model class
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
		return '{{lang}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('name, code', 'required'),
			array('name', 'length', 'max'=>50),
			array('code', 'length', 'max'=>2),
			array('active', 'default', 'value'=>1),
			
			array('default', 'default', 'value'=>0),
			array('name, code, active', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
		);
	}

	public function scopes()
	{
		return array(
			
				'active' => array('condition' => 'active = 1'),
				
		);
	}
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'name' => Yii::t('lang', 'Name'),
			'code' => Yii::t('lang', 'Code'),
			'default' => Yii::t('lang', 'Default'),
			'active' => Yii::t('lang', 'Active'),
		);
	}
	
	public static function getList()
	{
		return self::model()->active()->findAll(array('index' => 'code'));
	}
	
	public static function getListData()
	{
		return CHtml::listData(self::model()->active()->findAll(), 'code', function($data){ return AdminHelper::flag($data) . ' ' . CHtml::encode($data->name); });
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

		$criteria->compare('name',$this->name,true);
		$criteria->compare('code',$this->code,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}