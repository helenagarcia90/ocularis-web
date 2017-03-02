<?php

class PromoBlocks extends CWidget{
	
	public $itemNumber = 4;
	public $anchor;
	
	public function init()
	{
		Yii::beginProfile('PromoBlocks','widgets');
	}
	
	public function run()
	{
				
		Yii::import('modules.promoBlocks.models.*');
		
		
		$widget = PromoBlockWidget::model()->findByAttributes(array('anchor' => $this->anchor, 'lang' => Yii::app()->language));
		
		if($widget === null)
			return;
		
		$items = $widget->promoBlocks(array('limit' => $this->itemNumber));
		
		if( count($items) === 0)
			return;
		
		
		$this->render("promoBlocks",array(
							'items' => $items,
		));
		
		Yii::endProfile('PromoBlocks','widgets');
	}
	

	
}