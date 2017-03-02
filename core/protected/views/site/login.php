
	

		<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>


		<div class="row">
			<?php echo $form->textField($model,'email',array('class' => 'text w', 'placeHolder' => $model->getAttributeLabel('email'))); ?>
			<?php echo $form->error($model,'email'); ?>
		</div>
	
		<div class="row">
			<?php echo $form->passwordField($model,'password',array('class' => 'text w', 'placeHolder' => $model->getAttributeLabel('password'))); ?>
			<?php echo $form->error($model,'password'); ?>
		</div>
	
		<div class="row rememberMe">
			<?php echo $form->checkBox($model,'rememberMe',array('class' => 'checkbox')); ?>
			<?php echo $form->label($model,'rememberMe'); ?>
		</div>
	
		<div class="row buttons" style="text-align: center;">
			<?php echo CHtml::submitButton('Login',array('class' => 'button blue')); ?>
		</div>
	
	<?php $this->endWidget(); ?>
	</div><!-- form -->



