<?php
/* @var $form CActiveForm */
/* @var $model Page */
?>

<?php 
/*
 * 
 * Yii::t('toolbar', "Save and Close")
 * 
 * 
 */

?>


<?php 
Yii::app()->clientScript->registerScript('cmspage',<<<EOD
		
	$("#Page_lang").on("change",function()
		{
			$("#langAssoc > div").show();
			id = '#langAssocRow_' + $(this).val();
			$(id).hide();
		});		
		
		
		
EOD
);
?>

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
									'title' => Yii::t('page', 'Page'),
									'view' => '_page'
								),

				'options' => array(
						'title' => Yii::t('page', 'Options'),
						'view' => '_options',
				),

				'metas' => array(
									'title' => Yii::t('page', 'Metas'),
									'view' => '_meta'
								),

				'assoc' => array(
						'title' => Yii::t('page', 'Associations'),
						'view' => '_assoc',
						'visible' => count(Yii::app()->languageManager->langs)>1,
				),

				'revisions' => array(
						'title' => Yii::t('page', 'Revisions'),
						'view' => '_revisions',
						'visible' => $revisions !== array(),
				),

		),

		'viewData' => CMap::mergeArray(array('form' => $form), $_data_),
		'htmlOptions' => array('class' => 'nav-tabs'),
));
?>

	<?php $this->endWidget(); ?>
</div>