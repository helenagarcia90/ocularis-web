<?php

class HomeBlocks extends CWidget{
	
	public $itemNumber = 4;
	
	public function run()
	{
				
		Yii::import('modules.homeBlock.models.*');
		
		$items = HomeBlock::model()->findAll(array('limit' => $this->itemNumber));
		
		if( count($items) === 0)
			return;
		
		
		$this->render("homeBlocks",array(
							'items' => $items,
		));

	}
	

	
}