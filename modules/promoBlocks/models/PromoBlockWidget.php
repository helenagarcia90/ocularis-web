<?php


class PromoBlockWidget extends CActiveRecord
{
	
	/**
	 * @property integer id_promo_block_widget
	 * @property string bane
	 * @property string lang
	 * @property string anchor
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
		return '{{promo_block_widget}}';
	}
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
	
		return array(
				array('name, anchor, lang', 'required'),
				array('name', 'length', 'max'=>256),
				array('anchor', 'length', 'max'=>32),
		);
	}
	
	

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
	
		return array(
				'language' => array(self::BELONGS_TO, 'Lang', 'lang'),
				'promoBlocks' => array(self::HAS_MANY, 'PromoBlockWidgetPromoBlock', 'id_promo_block_widget'),
		);
	}
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
				'id_promo_block_widget' => 'ID',
				'lang' => Yii::t('app','Language'),
				'name' => Yii::t('PromoBlocksModule.main','Name'),
				'anchor' => Yii::t('PromoBlocksModule.main','Anchor'),
		);
	}
	
	
	public function getListData()
	{
		return CHtml::listData(self::model()->findAll(), "id_promo_block_widget", "name", "language.name");
	}
	
	
	public function search()
	{
	
		$criteria=new CDbCriteria;
		$criteria->compare('id_promo_block_widget',$this->id_promo_block_widget,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('lang',$this->lang,true);
	
		return new CActiveDataProvider($this, array(
				'criteria'=>$criteria,
		));
	}
	
	
}