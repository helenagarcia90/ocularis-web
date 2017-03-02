<?php

class ActionColumn extends DataColumn
{

	function init()
	{
		parent::init();
		
		if(!isset($this->headerHtmlOptions['class']))
			$this->headerHtmlOptions['class'] = 'text-center col-md';
		else
			$this->headerHtmlOptions['class'] .= ' text-center col-md';
			
	}
	
	public function renderFilterCellContent()
	{
		echo $this->grid->blankDisplay;
	}
	
	public function renderDataCellContent($row, $data)
	{
		
		echo CHtml::openTag('div', array('class' => 'text-center'));
				
		foreach($this->grid->actions as $label => $action)
		{
			
			switch($action)
			{
			
				case 'update':
					
					if(Yii::app()->user->checkAccess('update'.ucfirst($this->grid->controller)))
					{
						echo CHtml::link('<span class="glyphicon glyphicon-edit"></span>',
								Yii::app()->controller->createUrl('update', array('id' => $data->{$this->name})),
								array('class' => 'btn btn-default btn-xs tooltipped', 'title' => Yii::t('modelGridView', 'Edit'), 'data-placement' => 'top'));
						echo "\n";
					}
				break;
				
				case 'delete':
					
					if(Yii::app()->user->checkAccess('delete'.ucfirst($this->grid->controller)))
					{
						echo CHtml::link('<span class="glyphicon glyphicon-trash"></span>',
								Yii::app()->controller->createUrl('delete', array_merge(array('id' => $data->{$this->name}), $_GET)),
								array('class' => 'btn btn-default btn-xs tooltipped modal_action', 'title' => Yii::t('modelGridView', 'Delete'), 'data-placement' => 'top'));
					}
				break;
				
				default:
				
					if(!isset($action['visible']) || $action['visible'])
					{
					
						$htmlOptions = array();
						
						if(isset($action['htmlOptions']))
							$htmlOptions = $action['htmlOptions'];
						
						$htmlOptions['title'] = $label;
						$htmlOptions['data-placement'] = 'top';
						
						$classes = 'btn btn-default btn-xs tooltipped' . (isset($action['modal']) ? ' modal_action' : '');
						
						if(isset($htmlOptions['class']))
							$htmlOptions['class'] .= ' '.$classes;
						else
							$htmlOptions['class'] = $classes;
						
						echo CHtml::link($action['icon'], $this->evaluateExpression($action['url'], array('data' => $data)),
								$htmlOptions);
						echo "\n";
					}
				break;
			}
		}
		
		
		echo CHtml::closeTag('div');
		
	}
	

}
