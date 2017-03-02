<?php

class DateColumn extends DataColumn
{

	public $dateWidth = 'medium';
	public $timeWidth = 'medium'; 
	
	public function renderDataCellContent($row, $data)
	{
		
		if($this->value!==null)
			$value=$this->evaluateExpression($this->value,array('data'=>$data,'row'=>$row));
		elseif($this->name!==null)
			$value=CHtml::value($data,$this->name);
		
		echo $value===null ? $this->grid->nullDisplay : Yii::app()->locale->dateFormatter->formatDateTime($value,$this->dateWidth, $this->timeWidth);
		
	}
	
	public function getFilterValue()
	{
	
		$value = parent::getFilterValue();
		$op = '';
		
		if($value == '')
			return '';
		
		if(preg_match('/^(?:\s*(<>|<=|>=|<|>|=))?(.*)$/',$value,$matches))
		{
			$value=$matches[2];
			$op=$matches[1];
		}


		//format to mysql
		$locale = Yii::app()->locale;
		
		return ($op.date('Y-m-d', CDateTimeParser::parse($value, $locale->getDateFormat($this->dateWidth))));
	}
	
}