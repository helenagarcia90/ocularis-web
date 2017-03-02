<?php

class SelectorColumn extends DataColumn
{
	
	public $evalLabel;
	public $name = 'id';

	public function init()
	{
		parent::init();
		
		Yii::app()->clientScript->registerScript(__CLASS__,"$('.tooltipped').tooltip();");
	}
	
	public function renderFilterCellContent()
	{
		echo $this->grid->blankDisplay;
	}
	
	public function renderDataCellContent($row, $data)
	{
		
		echo CHtml::htmlButton('<span class="glyphicon glyphicon-check"></span>',
				array('class' => 'btn btn-default btn-xs selector tooltipped pull-right',
						'title' => Yii::t('selector', 'Select'),
						'data-placement' => 'top',
						'data-id' => $data->primaryKey,
						'data-desc' => $this->evaluateExpression($this->evalLabel,array('data' => $data)),
		));
				
		
		
		
		
	}
	
}
