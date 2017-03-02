<?php

class FormHelper
{

	public static function renderActiveFormElement($paramss, $model, $attribute)
	{
		
		$name = CHtml::resolveName($model, $attribute);
		$value = CHtml::resolveValue($model, $attribute);
		
		return self::renderFormElement($paramss, $name, $value);
		
	}
	
	
	public static function renderFormElement($params, $name, $value)
	{
		
		
		echo CHtml::openTag('div', array('class' => 'form-group'));
		
		
		if($params['type'] === 'dropdown')
		{
		
			Yii::app()->controller->widget("DropDown", array('name' => $name,
					'value' => $value,
					'data' => is_array($params['items']) ? $params['items'] : Yii::app()->evaluateExpression($params['items']),
					'options' => array('label' => $params['label'],'prompt' => isset($params['prompt']) ? $params['prompt'] : $params['label']),
			) );
		}
		elseif($params['type'] === 'text')
		{

			Yii::app()->controller->widget('TextField', array(
				'name' => $name,
				'value' => $value,
				'options' => array('label' => $params['label']),
				));
		
		}
		elseif($params['type'] === 'selector')
		{
				
			$valueModel = $params['valueModel']::model()->findByPk($value);
		
			Yii::app()->controller->widget('application.modules.admin.components.form.widgets.Selector',
					array(
							'name'		 	=> $name,
							'value' 	=> $value,
							'displayValue'	=> CHtml::value($valueModel, $params['valueAttribute'],''),
							'url' 	=> $params['url'],
							'options' => array('label' => $params['label']),
					));
		}
		elseif($params['type'] === 'switcher')
		{
				
			Yii::app()->controller->widget('Switcher',array(
					'name' => $name,
					'value' => $value,
					'items' => is_array($params['items']) ? $params['items'] : Yii::app()->evaluateExpression($params['items']),
					'options' => array('label' => $params['label']),
			));
		}
		elseif($params['type'] === 'image')
		{
					
			Yii::app()->controller->widget('MediaSelector',array(
				'name' => $name,
				'value' => $value,
				'imagePreset' => isset($params['imagePreset']) ? $params['imagePreset'] : null,
				'options' => array('label' => $params['label']),
			));
		}
		elseif($params['type'] === 'view')
		{
			Yii::app()->controller->renderPartial($params['view'], array('params' => $params, 'name' => $name, 'value' => $value));
		}
			
		echo CHtml::closeTag('div');
			
	}
	
}