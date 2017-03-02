<?php
/* @var $model  CActiveRecord */

abstract class InputField extends CInputWidget
{
	
	/**
	 * The input options
	 * @var array An array with the options
	 */
	
	public $options = array();
	
	/**
	 * Renders the input widget
	 */
	
	abstract function renderInput();		
	
	/**
	 * The label of the input
	 * @var string
	 */

	public $labelHtmlOptions = array();
	
	
	public function init()
	{

		list($name,$id)=$this->resolveNameID();
		
		if(isset($this->htmlOptions['id']))
			$id=$this->htmlOptions['id'];
		else
			$this->htmlOptions['id']=$id;
		
		if(!isset($this->labelHtmlOptions['for']))
			$this->labelHtmlOptions['for'] = $this->htmlOptions['id'];
		
		 
	}
	
	public function run(){
		
		if(isset($this->labelHtmlOptions['class']))
			$this->labelHtmlOptions['class'] .= ' control-label';
		else
			$this->labelHtmlOptions['class'] = 'control-label';
		
		if(isset($this->options['showLabel']) && !$this->options['showLabel'])
			$this->labelHtmlOptions['class'] .= ' sr-only';

			if($this->hasModel() && !isset($this->options['label']))
				echo CHtml::activeLabel($this->model, $this->attribute, $this->labelHtmlOptions);
			elseif(isset($this->options['label']) && $this->options['label']!== false)
				echo CHtml::label($this->options['label'],$this->labelHtmlOptions['for'], $this->labelHtmlOptions);
			
			echo CHtml::openTag('div');
				$this->renderInput();
			echo CHtml::closeTag('div');
		
	}

	
	
	public function hasAddons()
	{
		return isset($this->options['prepend']) || isset($this->options['append']);
	}
	
}