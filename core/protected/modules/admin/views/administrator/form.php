<?php
/* @var $form CActiveForm */
/* @var $model Admin */
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
		'id' => 'menuTabs',
		'cssFile' => false,
		'tabs'=>array(
				'menu' =>  array(
									'title' => Yii::t('admin', 'Administrator'),
									'view' => '_admin'
								),
				'roles' =>  array(
						'title' => Yii::t('admin', 'Roles'),
						'view' => '_roles'
				),
		),

		'viewData' => CMap::mergeArray(array('form' => $form), $_data_),
		'htmlOptions' => array('class' => 'nav-tabs'),
));
?>

	<?php $this->endWidget(); ?>
</div>