<?php

class Modal extends CWidget
{
	
	public $size = 'modal-md';
	public $htmlOptions = array();
	
	public function run()
	{
		
		$htmlOptions = CMap::mergeArray( $this->htmlOptions, array('class' => 'modal-dialog '.$this->size) );
		
		echo CHtml::tag('div', array('class' => 'modal fade', 'id' => $this->id, 'tabindex' => '-1', 'role' => 'dialog', 'aria-hidden' => 'true'),
				
				CHtml::tag('div', $htmlOptions,
					CHtml::tag('div', array('class' => 'modal-content'),''
					)		
				)
				
		);
	}
	
}