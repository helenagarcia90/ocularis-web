<?php

/**
 * This is the model class for table "blog".
 *
 * The followings are the available columns in table 'menu':
 * @property string $id_blog
 * @property string $lang
 * @property string $name
 * @property string $alias
 * @property string $meta_title
 * @property string $meta_keywords
 * @property string $meta_description
 * @property string $meta_robots
 *
 * The followings are the available model relations:
 * @property Lang $lang
 * @property BlogPage[] $blogPages
 */
class Blog extends CActiveRecord
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
		return '{{blog}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{

		return array(
			array('lang, name, alias', 'required'),
			array('meta_title, meta_keywords, meta_description, meta_robots', 'safe'),
			array('name', 'length', 'max'=>512),
			array('alias', 'length', 'max'=>256),
			array('alias','checkUniqueBlog'),
			array('meta_title, meta_keywords, meta_description, meta_robots', 'default' , 'value' => null),
			array('id_blog, lang, name', 'safe', 'on'=>'search'),
		);
	}
	
	public function checkUniqueBlog($attribute)
	{
		if($attribute === 'alias')
		{
	
			$params = array(
					':lang' => $this->lang,
					':alias' => $this->alias,
			);
				
			if(!$this->isNewRecord)
				$params[':id_blog'] = $this->id_blog;
				
			$exists = Blog::model()->exists('lang = :lang AND alias = :alias'. (!$this->isNewRecord ? ' AND id_blog <> :id_blog' : ' '),
					$params);
				
			if($exists)
			{
				$this->addError('alias', Yii::t('blog', 'The blog alias must be unique for each language'));
			}
				
		}
	}
	

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{

		return array(
			'language' => array(self::BELONGS_TO, 'Lang', 'lang'),
			'blogPages' => array(self::HAS_MANY, 'BlogPage', 'id_blog'),
			'blogPagesCount' => array(self::STAT, 'BlogPage', 'id_blog'),
				
			'pages' => array(self::HAS_MANY, 'Page', array('id_page' => 'id_page'), 'through' => 'blogPages' ),

		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_blog' => 'Id Blog',
			'lang' => Yii::t('app','Language'),
			'name' => Yii::t('page','Name'),
			'alias' => Yii::t('page','Alias'),
		);
	}

	public static function getListData()
	{
		return CHtml::listData(self::model()->findAll(),'id_blog','name','language.name');
	}
	
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{

		$criteria=new CDbCriteria;
		$criteria->compare('id_blog',$this->id_blog,true);
		$criteria->compare('lang',$this->lang,true);
		$criteria->compare('name',$this->name,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}