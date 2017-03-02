<?php
/* @var $form CActiveForm */
/* @var $model Menu */
?>

<div class="form">

<?php $form=$this->beginWidget('ActiveForm', array(
		'id'=>'mainForm',
)); 

if($model->hasErrors())
	Yii::app()->user->setFlash('errorSummary',$form->errorSummary($model,null,null,array('class' => 'message error errorSummary'))); 
 
$this->widget('TabView',array(
		'id' => 'menuTabs',
		'cssFile' => false,
		'tabs'=>array(
				'menu' =>  array(
									'title' => Yii::t('menu', 'Menu'),
									'view' => '_menu'
								),
		),

		'viewData' => CMap::mergeArray(array('form' => $form), $_data_),
		'htmlOptions' => array('class' => 'nav-tabs'),
));

$this->endWidget(); ?>

</div>