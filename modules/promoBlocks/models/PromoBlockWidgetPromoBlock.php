<?php


class PromoBlockWidgetPromoBlock extends CActiveRecord
{
	
	/**
	 * @property string id_promo_block
	 * @property string id_promo_block_widget;
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
		return '{{promo_block_widget_promo_block}}';
	}
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
	
		return array(
				array('id_promo_block, id_promo_block_widget', 'required'),
		);
	}
	
	
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
	
		return array(
				'promoBlock' => array(self::BELONGS_TO, 'PromoBlock', 'id_promo_block'),
				'promoBlockWidget' => array(self::BELONGS_TO, 'PromoBlockWidget', 'id_promo_block_widget'),
		);
	}
	
	
	
}