<?php

/**
 * This is the model class for table "admin".
 *
 * The followings are the available columns in table 'admin':
 * @property string $id_admin
 * @property string $name
 * @property string $email
 * @property string $lang
 * @property string $password
 * @property string $enter_password
 * @property string $repeat_password
 */
class Admin extends CActiveRecord
{
	
	public $enter_password;
	public $repeat_password;
	
	public $editor = 'ckeditor';
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{admin}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('name, email, lang, editor', 'required'),
			
			array('enter_password', 'required', 'on' => 'create'),
			array('repeat_password', 'safe'),
			array('enter_password', 'compare', 'compareAttribute'=>'repeat_password'),
				
			array('name', 'length', 'max'=>50),
			array('email', 'length', 'max'=>256),
				
			array('email', 'unique'),
			array('email', 'email'),

			array('id_admin, name, email', 'safe', 'on'=>'search'),
		);
	}
	
	public function beforeSave() {
	
		if(!empty($this->enter_password) && !empty($this->repeat_password)){
			$this->password = md5($this->enter_password);
		}
	
		return parent::beforeSave();
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_admin' => 'Id',
			'name' => Yii::t('admin', 'Name'),
			'email' => Yii::t('admin', 'Email'),
			'password' => Yii::t('admin', 'Password'),
			'lang' => Yii::t('app', 'Language'),
			'enter_password' => Yii::t('admin', 'Password'),
			'repeat_password' => Yii::t('admin', 'Repeat Password'),
			'editor' => Yii::t('admin','Preferred editor'),
		);
	}


	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id_admin',$this->id_admin,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('email',$this->email,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}


	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
