<?php

class Selector extends TextField {
	

	public $displayValue = '';
	public $text = '';
	public $url = '';
	public $placeHolder = '';
	public $buttonHtmlOptions = array();
	public $textFieldHtmlOptions = array();
	public $assetsUrl;
	
	public function init()
	{
		$this->labelHtmlOptions['for'] = false;
		parent::init();
		
		$this->registerScript();
	}
	
	
	public function renderInput()
	{
		
		//$this->model->getAttributeLabel($attribute)
		$id=$this->htmlOptions['id'];	
		echo CHtml::openTag('div',array('class' => 'input-group selector'));
		echo CHtml::tag('p', array('id' => $id.'Display', 'class' => 'form-control form-control-static'),$this->displayValue);
		echo CHtml::tag('span', array('id' => $id.'Remove', 'class' => 'glyphicon glyphicon-remove selector-remove'),'');
		echo CHtml::tag('span', arraY('class' => 'input-group-btn'),
			CHtml::link(CHtml::tag('span', array('class' => 'glyphicon glyphicon-search'),''),$this->url, array('class' => 'btn btn-default', 'data-toggle' => 'modal', 'data-target' => '#'.$id.'Modal'))
		);
		echo CHtml::closeTag('div');
		if($this->hasModel())
			echo CHtml::activeHiddenField($this->model, $this->attribute, array('class' => 'form-control'));
		else
			echo CHtml::hiddenField($this->name, $this->value);
		
	}
	

	private function registerScript()
	{
		
		$id=$this->htmlOptions['id'];
		
		$modalId = $id . 'Modal';
	
		echo CHtml::tag('div', array('class' => 'modal', 'id' => $modalId, 'role' => 'dialog', 'aria-hidden' => 'true'),
				
				CHtml::tag('div', array('class' => 'modal-dialog modal-lg'),
					CHtml::tag('div', array('class' => 'modal-content'),''
					)		
				)
				
		);

		
		Yii::app()->clientScript->registerScript($id,'
				
				$("#'.$id.'Remove").on("click",function(){
						$("#'.$id.'").val("");
						$("#'.$id.'Display").html("");
						$("#'.$id.'").trigger("change"); //trigger the change function in case some code is waiting for a change
				});
				
				$("#'.$modalId.'").on("click",".reset",function(){$("#'.$id.'").val("");$("#'. $id . '_display").val("");});
						
				$("#'.$modalId.'").on("click","table tbody tr .selector",function(event){
							event.preventDefault();
							$("#'.$id.'").val($(this).attr("data-id"));
							$("#'.$id.'").trigger("change"); //trigger the change function in case some code is waiting for a change
							$("#'. $id . 'Display").html($(this).attr("data-desc"));
							$("#'.$modalId.'").modal("hide");
						});
											
									
				');
		
				
	}

	
}