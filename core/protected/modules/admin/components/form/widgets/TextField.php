<?php

class TextField extends InputField
{
	
	
	
	public function renderInput()
	{
		
		if($this->hasAddons())
			echo CHtml::openTag('div', array('class' => 'input-group'));
		
		if(isset($this->options['prepend']))
			echo CHtml::tag('span',array('class' => 'input-group-addon'),$this->options['prepend']);
		elseif(isset($this->options['prependBtn']) && is_array($this->options['prependBtn']))
		{
			echo CHtml::openTag('span', arraY('class' => 'input-group-btn'));
			
			$btnOptions = array();
			if(isset($this->options['prependBtn']['htmlOptions']) && is_array($this->options['prependBtn']['htmlOptions']))
				$btnOptions = $this->options['prependBtn']['htmlOptions'];
						
			$class = 'btn btn-default';
			
			if(isset($btnOptions['class']))
				$btnOptions['class'] .= ' '.$class;
			else
				$btnOptions['class'] = $class;
			
			if(!isset($btnOptions['id']))
				$btnOptions['id'] = $this->id . '_prependBtn';
				
			echo CHtml::htmlButton($this->options['prependBtn']['value'],$btnOptions);
			echo CHtml::closeTag('span');
		}
		
		$this->renderTextInput();
		
		
		if(isset($this->options['append']))
			echo CHtml::tag('span',array('class' => 'input-group-addon'),$this->options['append']);
		elseif(isset($this->options['appendBtn']) && is_array($this->options['appendBtn']))
		{
			echo CHtml::openTag('span', arraY('class' => 'input-group-btn'));
			
			$btnOptions = array();
			if(isset($this->options['appendBtn']['htmlOptions']) && is_array($this->options['appendBtn']['htmlOptions']))
				$btnOptions = $this->options['appendBtn']['htmlOptions'];
						
			$class = 'btn btn-default';
			
			if(isset($btnOptions['class']))
				$btnOptions['class'] .= ' '.$class;
			else
				$btnOptions['class'] = $class;
			
			if(!isset($btnOptions['id']))
				$btnOptions['id'] = $this->id . '_appendBtn';
			
			if(!isset($this->options['appendBtn']['href']))
				echo CHtml::htmlButton($this->options['appendBtn']['value'],$btnOptions);
			else
				echo CHtml::link($this->options['appendBtn']['value'],$this->options['appendBtn']['href'],$btnOptions);
				
			echo CHtml::closeTag('span');
		}
		
		if($this->hasAddons())
			echo CHtml::closeTag('div');
		
	}
	
	protected function renderTextInput()
	{
		$attribute = $this->attribute;
		CHtml::resolveName($this->model, $attribute);
		
		
		$htmlOptions = isset($this->options['htmlOptions']) ? $this->options['htmlOptions'] : array(); 
		
		if(isset($htmlOptions['class']))
			$htmlOptions['class'] .= 'form-control';
		else
			$htmlOptions['class'] = 'form-control';
		
		if($this->hasModel())
		{
			$htmlOptions['placeholder'] = $this->model->getAttributeLabel($attribute);
			echo CHtml::activeTextField($this->model, $this->attribute, $htmlOptions);
		}
		else
		{
			if(isset($this->options['label']) && $this->options['label'] !== false)
				$htmlOptions['placeholder'] = $this->options['label'];
			
			echo CHtml::textField($this->name, $this->value, $htmlOptions);
		}
	}
	
	public function hasAddons()
	{
		return isset($this->options['prepend']) || isset($this->options['prependBtn']) || isset($this->options['appendBtn']) || isset($this->options['append']);
	}
	
}