<?php


class PromoBlock extends CActiveRecord
{
	
	/**
	 * @property integer id_promo_block
	 * @property string title
	 * @property string lang
	 * @property string content
	 * @property string link
	 * @property string image
	 * @property string image_alternate
	 * @property string active
	 * @property CUploadedFile image_file
	 * @property CUploadedFile image_alternate_file
	 * 
	 */
	
	public $active = 1;
	public $image_file;
	public $image_alternate_file;
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{promo_block}}';
	}
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
	
		return array(
				array('title, content, image, image_alternate, lang, active', 'required'),
				array('active', 'default', 'value' => 0),
				array('link, image_file', 'safe'),
				array('title, link, image, image_alternate', 'length', 'max'=>256),
				array('image_file, image_alternate_file', 'file', 'allowEmpty' => true, 'types'=>'gif, jpg, jpeg, png'),
		);
	}
	
	public function beforeValidate()
	{
		
		if($this->image_file !== null )
		{
			if(!is_dir(dirname(Yii::app()->basePath).DIRECTORY_SEPARATOR."images".DIRECTORY_SEPARATOR."homeBlocks"))
				mkdir(dirname(Yii::app()->basePath).DIRECTORY_SEPARATOR."images".DIRECTORY_SEPARATOR."homeBlocks");
			
			$this->image_file->saveAs(dirname(Yii::app()->basePath).DIRECTORY_SEPARATOR."images".DIRECTORY_SEPARATOR."homeBlocks".DIRECTORY_SEPARATOR.$this->image_file->name);
			$this->image = $this->image_file->name;
		}
		
		if($this->image_alternate_file !== null)
		{
			if(!is_dir(dirname(Yii::app()->basePath).DIRECTORY_SEPARATOR."images".DIRECTORY_SEPARATOR."homeBlocks"))
				mkdir(dirname(Yii::app()->basePath).DIRECTORY_SEPARATOR."images".DIRECTORY_SEPARATOR."homeBlocks");
				
			$this->image_alternate_file->saveAs(dirname(Yii::app()->basePath).DIRECTORY_SEPARATOR."images".DIRECTORY_SEPARATOR."homeBlocks".DIRECTORY_SEPARATOR.$this->image_alternate_file->name);
			$this->image_alternate = $this->image_alternate_file->name;
		}
		
		return parent::beforeValidate();
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
	
		return array(
				'language' => array(self::BELONGS_TO, 'Lang', 'lang'),
				'promoBLocksWidgets' => array(self::HAS_MANY, 'PromoBlockWidgetPromoBlock', 'id_promo_block'),
		);
	}
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
				'id_promo_block' => 'ID block',
				'lang' => Yii::t('app','Language'),
				'title' => Yii::t('PromoBlocksModule.main','Title'),
				'content' => Yii::t('PromoBlocksModule.main','Content'),
				'link' => Yii::t('PromoBlocksModule.main','Link'),
				'image' => Yii::t('PromoBlocksModule.main','Image'),
				'image_alternate' => Yii::t('PromoBlocksModule.main','Alternative Image'),
				'active' => Yii::t('PromoBlocksModule.main','Status'),
		);
	}
	
	public function getListData()
	{
		return CHtml::listData(self::model()->findAll(), "id_promo_block", "title", "language.name");	
	}
	
	
	public function search()
	{
	
		$criteria=new CDbCriteria;
		$criteria->compare('id_promo_block',$this->id_promo_block,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('lang',$this->lang,true);
	
		return new CActiveDataProvider($this, array(
				'criteria'=>$criteria,
		));
	}
	
	
}