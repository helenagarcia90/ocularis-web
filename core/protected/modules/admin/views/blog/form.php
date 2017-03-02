<?php
/* @var $form CActiveForm */
/* @var $model Category */
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
		'id' => 'pageTabs',
		'cssFile' => false,
		'tabs'=>array(
				'general' =>  array(
									'title' => Yii::t('form', 'General'),
									'view' => '_general'
								),
				'metas' =>  array(
						'title' => Yii::t('blog', 'Metas'),
						'view' => '_meta'
				),
				
		),

		'viewData' => CMap::mergeArray(array('form' => $form), $_data_),
		'htmlOptions' => array('class' => 'nav-tabs'),
));
?>



	<?php $this->endWidget(); ?>
</div>