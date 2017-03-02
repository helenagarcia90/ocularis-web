<?php

class DataColumn extends CDataColumn
{

	public $filterPartial = true;
	public $attribute;

	public $badge;
	public $badgeUrl;
	
	public $tooltip;
	
	public function init(){
		
		if(isset($this->name))
		{

			if( ($pos = strpos($this->name, '.')) === false)
			{
				$this->grid->dataProvider->sort->attributes[] = $this->name;
				$this->attribute = $this->name;
			}
			else
			{
				$relation = substr($this->name,0,$pos);
				$relations = $this->grid->dataProvider->model->relations();
				if(isset($relations[$relation]))
				{
						
					if($this->grid->dataProvider->criteria->with === null)
						$this->grid->dataProvider->criteria->with = array();
					elseif(is_string($this->grid->dataProvider->criteria->with))
					$this->dataProvider->criteria->with = array($this->grid->dataProvider->criteria->with);
						
					$this->grid->dataProvider->criteria->with[] = $relation;
		
					if($this->header === null)
					{
						$model = $relations[$relation][1]::model();
						$name = $model->tableschema->primaryKey;
						$this->header = $this->grid->dataProvider->model->getAttributeLabel($name);
					}
		
					$this->attribute = $this->name;
					$this->name = str_replace('.', '_', $this->name);
					$this->grid->dataProvider->sort->attributes[$this->name] = array('asc' => $this->attribute . ' asc', 'desc' => $this->attribute . ' desc');
					$this->value = 'CHtml::value($data,"'.$this->attribute.'")';
				}
			}
		
		}
		
		$this->applyFilter();
		
		parent::init();
	}
	
	protected function renderHeaderCellContent()
	{
		parent::renderHeaderCellContent();
		if($this->tooltip !== null)
		{
			echo '&nbsp;' . CHTML::tag('span',array('class' => 'glyphicon glyphicon-question-sign tooltipped', 'title' => $this->tooltip),'');		
		}
		
	}
	
	protected function renderDataCellContent($row, $data)
	{
		parent::renderDataCellContent($row, $data);
		
		if($this->badge !== null)
		{
			$badge = '<span class="badge">'.$this->evaluateExpression($this->badge, array('data' => $data)).'</span>';
			
			if($this->badgeUrl!==null)
				echo '&nbsp;'.CHtml::link($badge, $this->evaluateExpression($this->badgeUrl, array('data' => $data)));
			else
				echo '&nbsp;'.$badge;
		}
			
	} 
	
	protected function renderFilterCellContent()
	{
		if($this->filter!==false && $this->name!==null && strpos($this->name,'.')===false)
		{
			$name = 'filter_'.$this->name;
			$value = $this->grid->getParam('filter_'.$this->name);
			
			if(is_array($this->filter))
				echo CHtml::dropDownList($name, $value, $this->filter, array('id'=>false,'prompt'=>'', 'class' => 'form-control'));
			elseif($this->filter===null)
				echo CHtml::textField($name, $value, array('id'=>false, 'class' => 'form-control'));
			
		}
		else
			parent::renderFilterCellContent();
	}

	
	public function applyFilter()
	{
		$this->grid->dataProvider->criteria->compare($this->attribute, $this->filterValue,$this->filterPartial);
	}
	
	public function getFilterValue()
	{
		return $this->grid->getParam('filter_'.$this->name);
	}
	
}