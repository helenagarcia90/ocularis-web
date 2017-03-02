<?php

/**
 * This is the model class for table "blog_page".
 *
 * The followings are the available columns in table 'blog_page':
 * @property string $id_blog
 * @property string $id_page
 *
 * The followings are the available model relations:
 * @property Blog $blog
 * @property Page $page
  */
class BlogPage extends CActiveRecord
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
		return '{{blog_page}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
				array('id_blog', 'required'),
				array('id_blog', 'required','except' => 'createupdate'),
		);
	}

	public function defaultScope()
	{
		return array(
			'with' => array('page' => array('joinType' => 'INNER JOIN')),
		);
	}
	
	public function scopes()
	{
		return array(
				'published' => array('with' => array('page' => array('scopes' => 'published'))),
		);
	}
	
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'page' => array(self::BELONGS_TO, 'Page', 'id_page'),
			'blog' => array(self::BELONGS_TO, 'Blog', 'id_blog'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id_blog' => Yii::t('page','Blog'),
		);
	}
}