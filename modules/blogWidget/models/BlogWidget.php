<?php


class BlogWidget extends CActiveRecord
{
	
	/**
	 * @property integer id_widget
	 * @property string title
	 * @property string anchor
	 * @property string lang
	 * @property integer id_blog
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
		return '{{blog_widget}}';
	}
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
	
		return array(
				array('id_blog, anchor, lang', 'required'),
				array('anchor', 'length', 'max'=>32),
				array('title', 'length', 'max'=>256),
				array('anchor','checkUniqueWidget'),
		);
	}
	
	public function checkUniqueWidget($attribute)
	{
		if($attribute === 'anchor')
		{
	
			$params = array(
					':lang' => $this->lang,
					':anchor' => $this->anchor,
			);
	
			if(!$this->isNewRecord)
				$params[':id_widget'] = $this->id_widget;
	
			$exists = BlogWidget::model()->exists('lang = :lang AND anchor = :anchor'. (!$this->isNewRecord ? ' AND id_widget <> :id_widget' : ' '),
					$params);
	
			if($exists)
			{
				$this->addError('alias', Yii::t('BlogWidgetModule.main', 'The anchor must be unique for each language'));
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
				'blog' => array(self::BELONGS_TO, 'Blog', 'id_blog'),
	
		);
	}
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
				'id_widget' => 'ID Widget',
				'id_blog' => 'Blog',
				'lang' => Yii::t('app','Language'),
				'title' => Yii::t('BlogWidgetModule.main','Title'),
				'anchor' => Yii::t('BlogWidgetModule.main','Anchor'),
		);
	}
	
	public function search()
	{
	
		$criteria=new CDbCriteria;
		$criteria->compare('id_widget',$this->id_widget,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('lang',$this->lang,true);
		$criteria->compare('anchor',$this->lang,true);
	
		return new CActiveDataProvider($this, array(
				'criteria'=>$criteria,
		));
	}
	
	
}