<?php

class ActiveForm extends CActiveForm
{
	
	public $checkFormBeforeLeave = true;
	
	public function init()
	{
		parent::init();
		
		if($this->checkFormBeforeLeave)
		{
		
			Yii::app()->clientScript->registerScript('checkFormHasChanged'.$this->id,"
				
				
					$('#{$this->id}').on('submit',function(){
						$(this).data('submitted',true);
						return true;
					});
					
					$('#".$this->id." :input').each(function() {
					    $(this).data('initialValue', $(this).val());
					});
				
					window.onbeforeunload = function(){
					    var msg = ".CJavaScript::encode(Yii::t('activeForm', 'You have unsaved changes. If you leave this page, all changes will be lost.')).";
					    var isDirty = false;
						
						if($('#".$this->id."').data('submitted') != true)
						{
						    $('#".$this->id." :input').each(function () {
							        if($(this).data('initialValue') != undefined && $(this).data('initialValue').replace(/(\\r\\n|\\n|\\r)/gm,'') != $(this).val().replace(/(\\r\\n|\\n|\\r)/gm,'')){
							            isDirty = true;
							        }
						    });
					
						    if(isDirty == true){
						        return msg;
						    }
					    }
					};
				
			");
		}
		
	}
	
	public function fieldGroup($type, $model, $attribute, $options = array())
	{

		if($model->hasErrors($attribute))
			$validation = ' has-error';
		else
			$validation = '';
		
		echo CHtml::openTag('div', array('class' => 'form-group'.$validation));

		if(method_exists($this, $type.'Group'))
			$this->{$type.'Group'}($model, $attribute, $options);
		
		
		echo CHtml::closeTag('div');
		
	}
	
	/**
	 * Creates an Alias maker from another input field
	 * @param CActiveRecord $model The model
	 * @param string $attribute The attribute of the alias
	 * @param string $referenceAttribute The attribute used as the reference
	 */
	
	public function aliasMakerGroup($model, $attribute, $options = array())
	{
		
		if(!isset($options['referenceAttribute']))
			throw new CException('You must specify a referenceHtmlOptions for the AliasMaker');
		
		$referenceAttribute = $options['referenceAttribute'];
		
		CHtml::resolveNameID($model, $referenceAttribute, $referenceHtmlOptions);
		 
		$this->widget('AliasMaker',
				array(	'referenceId' 	=> $referenceHtmlOptions['id'],
						'model'		 	=> $model,
						'attribute' 	=> $attribute,
						'options' => $options,
						));
		
	}
	
	/**
	 * Creates a text input field group, with its label
	 * @param CActiveRecord $model The model
	 * @param string $attribute The attribute
	 * @param array $options Options for the input
	 * 
	 */
	
	public function textFieldGroup($model, $attribute, $options = array())
	{
		$this->widget('TextField', array(
			'model' => $model,
			'attribute' => $attribute,
			'options' => $options,		
		));
	}
	
	/**
	 * Creates a password input field group, with its label
	 * @param CActiveRecord $model The model
	 * @param string $attribute The attribute
	 * @param array $options Options for the input
	 *
	 */
	
	public function passwordFieldGroup($model, $attribute, $options = array())
	{
		$this->widget('PasswordField', array(
				'model' => $model,
				'attribute' => $attribute,
				'options' => $options,
		));
	}
	
	/**
	 * Creates a textarea field group, with its label
	 * @param CActiveRecord $model The model
	 * @param string $attribute The attribute
	 * @param array $options Options for the textarea
	 *
	 */
	
	public function textAreaGroup($model, $attribute, $options = array())
	{
		$this->widget('TextArea', array(
				'model' => $model,
				'attribute' => $attribute,
				'options' => $options,
		));
	}

	/**
	 * Creates a dropdownlost
	 * @param unknown $model
	 * @param unknown $attribute
	 * @param unknown $data
	 * @param unknown $options
	 */
	public function dropDownListGroup($model, $attribute, $options = array())
	{
	
				
		$data = isset($options['data']) ? $options['data'] : array();
		unset($options['data']);
		
		$this->widget('DropDown',array(
				'model' => $model,
				'attribute' => $attribute,
				'data' => $data,
				'options' => $options,
		));
	
	}
	
	/**
	 * Creates a switcher
	 * @param unknown $model
	 * @param unknown $attribute
	 * @param unknown $values
	 */
	public function switcherGroup($model, $attribute, $options = array())
	{
		
		$this->widget('Switcher',array(
				'model' => $model,
				'attribute' => $attribute,
				'items' => $options['items'],
				'options' => $options,
		));
		
	}
	
	public function pageSelectorGroup($model, $attribute, $options = array())
	{
		$scope = isset($options['scope']) ? $options['scope'] : null;
		
		$this->widget('PageSelector', array('model' => $model,
											'attribute' => $attribute,
											'scope' => $scope,
											'options' => $options,
		));
	}
	
	
	public function selectorGroup($model, $attribute, $options = array())
	{
		$this->widget('Selector',
				array(
						'model'		 	=> $model,
						'attribute' 	=> 'id_page_parent',
						'displayValue'	=> $options['displayValue'],
						'url' 	=> $options['url'],
						'options' => $options,
				));
	}
	
	
	
	
}