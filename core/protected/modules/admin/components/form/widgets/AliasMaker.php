<?php

class AliasMaker extends TextField {
	
	
	public $referenceId = null;
	
	
	public function init()
	{
		
		list(,$id) = $this->resolveNameID();

		$this->options['appendBtn'] = array(
			'value' => CHtml::tag('span', array('class' => 'glyphicon glyphicon-refresh'),''),
			'htmlOptions' => array('encode' => false, 'id' => $id . '_regen'),
		);
		
		$this->registerClientScript();

		
	}
	
	private function registerClientScript()
	{

		list(,$id) = $this->resolveNameID();
		
		Yii::app()->clientScript->registerScriptFile(Yii::app()->assetManager->publish(Yii::getPathOfAlias('application.modules.admin.components.widgets.assets.aliasmaker')).'/jquery.aliasMaker.js');
		
		Yii::app()->clientScript->registerScript($id.'_aliasMaker','
				
				$("#'.$id.'").aliasMaker("#'.$this->referenceId.'",'.	($this->hasModel() && $this->model->isNewRecord ? 'true' : 'false') . ');
					
		');
		
		
	}

	
}