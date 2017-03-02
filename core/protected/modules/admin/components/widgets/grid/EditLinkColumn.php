<?php

class EditLinkColumn extends CDataColumn
{
	
	
	public function init()
	{
		
		if($this->name !== null)
		{
			$this->value = 'CHtml::link( $data->{$this->name},Yii::app()->controller->createUrl("update",array("id" => $data->primaryKey)));';
			$this->type = 'raw';
		}
	}
	
	
}
