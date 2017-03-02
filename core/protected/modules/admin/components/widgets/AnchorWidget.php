<?php

class AnchorWidget extends CInputWidget{
	

	
	public function run()
	{
		
		list($name,$id)=$this->resolveNameID();
		if(isset($this->htmlOptions['id']))
			$id=$this->htmlOptions['id'];
		else
			$this->htmlOptions['id']=$id;
		if(isset($this->htmlOptions['name']))
			$name=$this->htmlOptions['name'];
		
		$this->registerClientScript();

		if(!isset($this->htmlOptions['class']))
			$this->htmlOptions['class'] = 'text xl';
		else
		{
			$this->htmlOptions['class'] .= ' text xl';
		}
		
		if($this->hasModel())
		{
			$this->htmlOptions['placeHolder'] = $this->model->getAttributeLabel('alias');
			$this->value = CHtml::value($this->model,$this->attribute);
			echo CHtml::activeTextField($this->model,$this->attribute,$this->htmlOptions);
		}
		else
		{
			echo CHtml::textField($name,$this->value,$this->htmlOptions);
		}
		
		
	}
	
	private function registerClientScript()
	{
		
		$id=$this->htmlOptions['id'];
		
		Yii::app()->clientScript->registerScript('pageForm','
		
		
			$("#'.$id.'").on("keypress",function(event) {

				
				if( !( (event.which >= 48 &&  event.which<= 57) || (event.which >= 97 &&  event.which<= 122) || event.which == 45 ) ) 
					event.preventDefault();
										
			});
						

						
			$("#'.$id.'").on("paste", function (e) {
			  	e.preventDefault();
				return false;
			});
		
					
		');
		
		
	}
	
	

	
}