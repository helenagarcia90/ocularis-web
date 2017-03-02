<?php

class TextArea extends InputField
{
	
	public function renderInput()
	{
		
		$attribute = $this->attribute;
		CHtml::resolveNameID($this->model, $attribute, $htmlOptions);

		$htmlOptions['class'] = 'form-control';
		
		if(isset($this->options['rows']))
			$htmlOptions['rows'] = $this->options['rows'];
		
		if($this->hasModel())
		{
			$htmlOptions['placeholder'] = $this->model->getAttributeLabel($attribute);
			echo CHtml::activeTextArea($this->model, $attribute, $htmlOptions);
		}
		else
			echo CHtml::textArea($this->name, $this->value, $htmlOptions);
		
	}
	
}