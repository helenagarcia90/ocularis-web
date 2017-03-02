<?php


class DropDown extends InputField
{

	
	public $data = array();
	
	public function init()
	{
		$this->labelHtmlOptions['for'] = false;
		
		parent::init();
	}
	
	public function renderInput()
	{
		
		$cs = Yii::app()->clientScript;
		
		$cs->registerCoreScript('bootstrap');
		
		list($name, $id)  = $this->resolveNameID();
				
		$cs->registerScript($this->id . '_dropdown', "
				
				$('#{$id}_dropdown > ul').on('click', 'li.option', function(){
				
					$('#{$id}_dropdown  > .dropdown-toggle > span.value').html($(this).html());
					$('#{$id}').val($(this).attr('data-value'));
					$('#{$id}').trigger('change');
				});
		");
	
		if($this->hasModel())
			$value = Chtml::resolveValue($this->model, $this->attribute);
		else
			$value = $this->value;
		
		if(isset($this->options['prompt']))
			$prompt = $this->options['prompt'];
		elseif($this->hasModel())
		{
			$attribute = $this->attribute;
			CHtml::resolveNameID($this->model, $attribute, $htmlOptions);
			$prompt = $this->model->getAttributeLabel($attribute);
		}

		if($prompt !== false && ($value === '' || $value === null || $value === 0))
			$label = $prompt;
		else
			$label = $this->resolveLabel($this->data, $value); 
		
		echo CHtml::openTag('div', array('id' => $id.'_dropdown', 'class' => 'btn-group dropdown-list'));
		
		echo CHtml::htmlButton('<span class="value">'.$label.'</span> <span class="caret"></span>', array('encode' => false, 'class' => 'btn btn-default form-control dropdown-toggle' . (isset($this->options['size']) ? ' btn-'.$this->options['size'] : ''), 'data-toggle' => 'dropdown'));
		
		echo CHtml::openTag('ul', array('class' => 'dropdown-menu' . (isset($this->options['align']) && $this->options['align'] === 'right' ? ' pull-right' : '') , 'role' => 'menu'));
		
		if($prompt !== false)
		{
			echo CHtml::tag('li',array('class' => 'option dropdown-header', 'data-value' => ''),  $prompt );
			echo CHtml::tag('li', array('class' => 'divider'),'');
		}
		
		$this->htmlOptions($this->data);
		
		echo CHtml::closeTag('ul');
		
		if($this->hasModel())
			echo CHtml::activeHiddenField($this->model, $this->attribute);
		else
			echo CHtml::hiddenField($id,$value);
		
		echo CHtml::closeTag('div');
		
	}
	
	protected function htmlOptions($data, $group = false)
	{
		
		foreach($data as $key => $value)
		{
				
			if(is_array($value))
			{
				echo CHtml::tag('li',array('class' => 'group'), CHtml::tag('span', array(), $key) );
				$this->htmlOptions($value, true);
			}
			else
			{
				echo CHtml::tag('li',array('class' => 'option' . ($group?' group-option' : ''), 'data-value' => $key), CHtml::tag('span', array(), $value) );
			}
				
				
		}
		
	}
	
	protected function resolveLabel($data, $value)
	{
		
		foreach ($data as $key => $label)
		{
			
			if(is_array($label))
			{
				$v = $this->resolveLabel($label, $value);
				if($v !== null)
					return $v;
			}
			elseif($key == $value)
				return $label;
		}
		
		return null;
	}
	
}