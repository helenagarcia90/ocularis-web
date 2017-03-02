<?php

class Switcher extends InputField{
	

	public $items = array();
	public $value = null; 
	
	public function init()
	{
		$this->labelHtmlOptions['for'] = false;
	
		parent::init();
	}
	
	public function renderInput()
	{

		
		list($name,$id)=$this->resolveNameID();
		if(isset($this->htmlOptions['id']))
			$id=$this->htmlOptions['id'];
		else
			$this->htmlOptions['id']=$id;
		
		
		$this->registerClientScript();
		
		if($this->hasModel())
		{
			$selected = CHtml::resolveValue($this->model, $this->attribute);
		}
		else
		{
			$selected = $this->value;
		}

		
		echo CHtml::openTag('div',array('class' => 'btn-group switcher', 'data-toggle' => 'buttons', 'id' => 'switcher_'.$id));
		
		foreach($this->items as $item)
		{
			
			$color = isset($item['color']) ? $item['color'] : 'btn-primary';
			
			//Begin backward compatibility
			switch($color)
			{
				case 'green':
					$color = 'btn-success';
				break;
				case 'red':
					$color = 'btn-danger';
				break;
				case 'orange':
					$color = 'btn-warning';
				break;
				case 'blue':
					$color = 'btn-primary';
				break;
				
			}
			//End backward compatibility
			
			
			if($item['value'] == $selected)
				$checked = ' active '.$color;
			else
				$checked = '';
			
			echo CHtml::openTag('label', array('class' => 'btn btn-sm btn-default'.$checked, 'data-color' => $color));

			$htmlOptions = array('value' => $item['value'], 'id' => CHtml::getIdByName($name).'_'.$item['value']);
			
			echo CHtml::radioButton($name, $item['value'] == $selected, $htmlOptions);
			
			echo $item['label'];
			
			echo CHtml::closeTag('label');
		}

		
		
		echo CHtml::closeTag('div');
		
	}
	
	private function registerClientScript()
	{
		
		$id=$this->htmlOptions['id'];
		
		Yii::app()->clientScript->registerScript('switcher_'.$id,'
				
			$("#switcher_'.$id.'").on("click",".btn",function()
			{
				$(this).parent().find(".btn").each(
					function(){ $(this).removeClass($(this).attr("data-color")); }
				);
				
				$(this).addClass($(this).attr("data-color"));
					
				$("#'.$id.'").trigger("change"); //trigger the change function in case some code is waiting for a change
			});
					
		');
		
		
	}
	
	

	
}