<?php

class CloneInputField extends CInputWidget{
	
	
	public $origin;	
	public $cloneIfDifferent = false;
	
	public function init(){
		
		if(!isset($this->origin))
			throw new CException("You must specify the origin field for the clone");
		
	}
	
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

				
		if($this->hasModel())
		{
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
		$origin = $this->origin;
		
		Yii::app()->clientScript->registerScript('CloneInputField'.$id,'
		
			
				mustClone_'.$id.' = true;
				
				'.  
				
				(!$this->cloneIfDifferent ? '
						
					$("#'.$origin.'").on("keydown",function(){

							mustClone_'.$id.' = ($("#'.$id.'").val() === "" || $("#'.$id.'").val() === $(this).val());

					});
						
				' : '') 
				
				. '
						
				$("#'.$origin.'").on("keyup",function(event){
											
					if(mustClone_'.$id.')
						$("#'.$id.'").val($(this).val());
								
				});
					
		');
		
		
	}
	
	

	
}