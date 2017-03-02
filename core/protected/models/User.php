<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property string $id_user
 * @property string $username
 * @property string $email
 * @property string $firstname
 * @property string $lastname
 * @property string $password
 */
class User extends CActiveRecord
{
	
	public $enter_password;
	public $repeat_password;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User the static model class
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
		return '{{user}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{

		return array(
			array('username, email, firstname, lastname', 'required'),
			array('email', 'length', 'max'=>256),
			array('firstname, lastname, username', 'length', 'max'=>50),
			array('enter_password', 'required', 'on' => 'insert'),
			array('repeat_password', 'safe'),
			array('enter_password', 'compare', 'compareAttribute'=>'repeat_password'),

			array('email, username', 'unique'),
				
			array('id_user, email, username, firstname, lastname', 'safe', 'on'=>'search'),
		);
	}

	
	public function beforeSave() {
	
		if(!empty($this->enter_password) && !empty($this->repeat_password)){
			$this->password = md5($this->enter_password);
			
			$this->enter_password =  $this->repeat_password = null;
		}
	
		return parent::beforeSave();
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_user' => 'Id',
			'email' => Yii::t('user' , "Email"),
			'username' => Yii::t('user' , "Username"),
			'firstname' => Yii::t('user' , "First Name"),
			'lastname' => Yii::t('user' , "Last Name"),
			'password' => Yii::t('user' , "Password"),
			'enter_password' => Yii::t('user' , "Password"),
			'repeat_password' => Yii::t('user' , "Repeat Password"),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{

		$criteria=new CDbCriteria;

		$criteria->compare('id_user',$this->id_user,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('firstname',$this->firstname,true);
		$criteria->compare('lastname',$this->lastname,true);
		$criteria->compare('password',$this->password,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}