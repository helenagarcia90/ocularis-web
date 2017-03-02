<?php /* @var $this Controller */ 
$this->pageTitle = Yii::t('admin', 'Password recovery');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	<title><?php echo Yii::app()->name . ": " .  CHtml::encode($this->pageTitle); ?></title>
	<?php 
		Yii::app()->clientScript->registerCoreScript('bootstrap');
		Yii::app()->clientSCript->registerCssFile('//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css');
	?>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->getModule('admin')->assets; ?>/css/login.css" />
</head>

<body>

<div class="container" id="page">

	

	

	<div class="form">
		<?php $form=$this->beginWidget('ActiveForm', array(
			'id'=>'login-form',
			'enableClientValidation'=>true,
			'clientOptions'=>array(
				'validateOnSubmit'=>true,
			),
		)); ?>

		<?php if(Yii::app()->user->hasFlash("success")): ?>
					<div class="alert alert-success">
							<?= Yii::app()->user->getFlash("success"); ?>
					</div>		
					
					<?php echo CHtml::link('<span class="glyphicon glyphicon-chevron-left"></span> ' . Yii::t('admin', 'Back'),array("login"),array('class' => 'btn btn-block btn-primary')); ?>
					
		<?php else:?>
				
				<p><?=Yii::t('admin', 'Please, enter your email address.');?></p>
				
				<?php if(Yii::app()->user->hasFlash("error")): ?>
					<div class="alert alert-danger">
							<?= Yii::app()->user->getFlash("error"); ?>
					</div>
				<?php endif; ?>
				
				<?php
					$form->fieldGroup('textField', $model, 'email', array('label' => false, 'prepend' => '<span class="fa fa-envelope-o"></span>'));
				?>
			
				<div class="form-group row" style="overflow: hidden;">
				
					<div class="col-sm-6">
						<?php echo CHtml::link('<span class="glyphicon glyphicon-chevron-left"></span> ' . Yii::t('admin', 'Back'),array('admin'),array('class' => 'btn btn-block btn-primary')); ?>
					</div>
					<div class="col-sm-6">
						<?php echo CHtml::htmlButton('<span class="glyphicon glyphicon-hand-right"></span> ' . Yii::t('admin', 'Send'),array('id' => 'submitButton', 'class' => 'btn btn-block btn-success')); ?>
					</div>
					
					<?php
						$text = Yii::t('admin', 'Please, wait ...');
						Yii::app()->clientScript->registerScript('submit',"
				
							$('#submitButton').on('click', function(){
								$(this).prop('disabled', true).addClass('disabled').html('<span class=\"glyphicon glyphicon-refresh fa-spin\"></span> {$text}');
								$('#login-form').submit();
							});
							
						"); 
					?>	
				</div>
				
		<?php endif;?>
	
	<?php $this->endWidget(); ?>
	</div><!-- form -->

	</div>

</body>
</html>



