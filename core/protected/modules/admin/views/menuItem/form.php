<?php
/* @var $form CActiveForm */
/* @var $model MenuItem */
?>

<div class="form">
<?php $form=$this->beginWidget('ActiveForm', array(
		'id'=>'mainForm',
)); 
?>

<?php if($model->hasErrors() ):?>
	<?php Yii::app()->user->setFlash('errorSummary',$form->errorSummary($model,null,null,array('class' => 'message error errorSummary'))); ?>
<?php endif;?>

<?php 

$this->widget('TabView',array(
		'id' => 'menuItemsTabs',
		'cssFile' => false,
		'tabs'=>array(
				'menuItem' =>  array(
									'title' => Yii::t('menu', 'Menu Item'),
									'view' => '_menuItem'
								),
		),

		'viewData' => CMap::mergeArray(array('form' => $form), $_data_),
		'htmlOptions' => array('class' => 'nav-tabs'),
));
?>

	<?php $this->endWidget(); ?>
</div>