<?php
/* @var $form CActiveForm */
/* @var $model Page */
?>


<php /* @var $form ActiveForm */?>
<div class="form">
<?php $form=$this->beginWidget('ActiveForm', array(
		'id'=>'mainForm',
)); 
?>

<?php if($model->hasErrors()):?>
	<?php Yii::app()->user->setFlash('errorSummary',$form->errorSummary(array($model),null,null,array('class' => 'message error errorSummary'))); ?>
<?php endif;?>


<?php 
$form->fieldGroup('textField', $model, 'title', array('showLabel' => false , 'label-col' => 'col-lg-12', 'input-col' => 'col-lg-12' ));
?>

<?php 

$this->widget('TabView',array(
		'id' => 'pageTabs',
		'tabs'=>array(
				'cmspage' =>  array(
									'title' => Yii::t('blog', 'Post'),
									'view' => '_general'
								),

				'options' => array(
						'title' => Yii::t('page', 'Options'),
						'view' => '/page/_options',
				),

				'metas' => array(
									'title' => Yii::t('page', 'Metas'),
									'view' => '/page/_meta'
								),

				'revisions' => array(
						'title' => Yii::t('page', 'Revisions'),
						'view' => '/page/_revisions',
						'visible' => $revisions !== array(),
				),


		),

		'viewData' => CMap::mergeArray(array('form' => $form), $_data_),
		'htmlOptions' => array('class' => 'nav-tabs'),
));
?>

	<?php $this->endWidget(); ?>
</div>