<?php
/* @var $form CActiveForm */
/* @var $model Admin */
?>

<div class="form">
<?php $form=$this->beginWidget('ActiveForm', array(
		'id'=>'mainForm',
)); 
 

$this->widget('TabView',array(
		'id' => 'authTabs',
		'cssFile' => false,
		'tabs'=>array(
				'default' =>  array(
									'title' => Yii::t('auth', 'Role'),
									'view' => '_role'
								),

			
		),

		'viewData' => CMap::mergeArray(array('form' => $form), $_data_),
		'htmlOptions' => array('class' => 'nav-tabs'),
));
?>

	<?php $this->endWidget(); ?>
</div>