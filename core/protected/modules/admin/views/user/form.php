<?php
/* @var $form CActiveForm */
/* @var $model Menu */
?>


<div class="form">
<?php $form=$this->beginWidget('ActiveForm', array(
		'id'=>'mainForm',
)); 
?>

<?php if($model->hasErrors() ):?>
	<?php Yii::app()->user->setFlash('error',$form->errorSummary($model)); ?>
<?php endif;?>

<?php 

$this->widget('TabView',array(
		'id' => 'menuTabs',
		'cssFile' => false,
		'tabs'=>array(
				'menu' =>  array(
									'title' => Yii::t('form', 'General'),
									'view' => '_general'
								),
		),

		'viewData' => CMap::mergeArray(array('form' => $form), $_data_),
		'htmlOptions' => array('class' => 'nav-tabs'),
));
?>

	<?php $this->endWidget(); ?>
</div>