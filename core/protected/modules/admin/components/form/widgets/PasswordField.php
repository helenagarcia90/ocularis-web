<?php

class PasswordField extends TextField
{
	
	
	
	protected function renderTextInput()
	{
		
		$attribute = $this->attribute;
		CHtml::resolveName($this->model, $attribute);
		
		if($this->hasModel())
			echo CHtml::activePasswordField($this->model, $this->attribute, array('class' => 'form-control', 'placeholder' => $this->model->getAttributeLabel($attribute)));
		else
			echo CHtml::passwordField($this->name, $this->value, array('class' => 'form-control'));
	}
	
}