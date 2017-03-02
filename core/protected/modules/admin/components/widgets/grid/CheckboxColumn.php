<?php

class CheckboxColumn extends DataColumn
{
	
	public function init()
	{
		parent::init();
		
		Yii::app()->clientScript->registerScript($this->grid->id.'_'.$this->name, "

				$('#{$this->grid->id}').on('click', '#{$this->grid->id}_check_all', function(){
					name = $(this).attr('id').replace('_all','') + '[]';
					$('#{$this->grid->id} input[name=\"'+name+'\"]').prop('checked',$(this).prop('checked'));
				});
				
			");
	}
	
	public function renderHeaderCellContent()
	{
		echo CHtml::checkBox($this->grid->id . '_check_all',false);
	}
	
	public function renderFilterCellContent()
	{
		echo $this->grid->blankDisplay;
	}
	
	public function renderDataCellContent($row, $data)
	{
		echo CHtml::checkBox($this->grid->id . '_check[]',false, array('id' => $this->grid->id . '_check_'.$data->primaryKey, 'value' => $data->primaryKey));
	}
	
}
