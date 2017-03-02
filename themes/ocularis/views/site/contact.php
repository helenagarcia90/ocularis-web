<?php
/* @var $this SiteController */
/* @var $model ContactForm */
/* @var $form CActiveForm */

$this->pageTitle=Yii::t('contact', 'Contact Us');

$this->breadcrumbs=array(
	Yii::t('contact', 'Contact Us')
);
?>

<h1><?=Yii::t('contact', 'Contact Us')?></h1>

<?php if(Yii::app()->user->hasFlash('contact')): ?>

<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('contact'); ?>
</div>

<?php else: ?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'contact-form',
)); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<div class="field-group<?=$model->hasErrors('name') ? ' error' : '';?>">
			<?php echo $form->textField($model,'name'); ?>
		</div>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<div class="field-group<?=$model->hasErrors('email') ? ' error' : '';?>">
			<?php echo $form->textField($model,'email'); ?>
		</div>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'subject'); ?>
		<div class="field-group<?=$model->hasErrors('subject') ? ' error' : '';?>">
			<?php echo $form->textField($model,'subject',array('size'=>60,'maxlength'=>128)); ?>
		</div>
		<?php echo $form->error($model,'subject'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'body'); ?>
		<div class="field-group<?=$model->hasErrors('body') ? ' error' : '';?>">
			<?php echo $form->textArea($model,'body',array('rows'=>6, 'cols'=>50)); ?>
		</div>
		<?php echo $form->error($model,'body'); ?>
	</div>

	<?php if(CCaptcha::checkRequirements()): ?>
	<div class="row">
		<?php echo $form->labelEx($model,'verifyCode'); ?>
		<div>
		
		<?php $this->widget('CCaptcha'); ?>
		<div class="field-group<?=$model->hasErrors('verifyCode') ? ' error' : '';?>">
		<?php echo $form->textField($model,'verifyCode'); ?>
		</div>
		</div>
		<?php echo $form->error($model,'verifyCode'); ?>
	</div>
	<?php endif; ?>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Submit',array('class' => 'button')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<?php endif; ?>