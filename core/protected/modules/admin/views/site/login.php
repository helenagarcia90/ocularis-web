<?php /* @var $this Controller */ 
$this->pageTitle = Yii::t('admin', 'Login to your account');
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
			'checkFormBeforeLeave' => false,
			'enableClientValidation'=>true,
			'clientOptions'=>array(
				'validateOnSubmit'=>true,
			),
		)); ?>

		<h3><?=Yii::t('admin', 'Login to your account')?></h3>
		
		<?=CHtml::errorSummary($model, '', null, array('class' => 'alert alert-danger'));?>
		
		<?php  
			$form->fieldGroup('textField', $model, 'email', array('label' => false, 'prepend' => '<span class="fa fa-envelope-o"></span>'));
			$form->fieldGroup('passwordField', $model, 'password', array('label' => false, 'prepend' => '<span class="fa fa-key"></span>'));
		?>
	
		<div class="form-group checkbox">
			<?php echo $form->label($model,'rememberMe', array('label' => $form->checkBox($model,'rememberMe') . ' ' . Yii::t('admin', 'Remember me')) ); ?>
		</div>
		
		<div class="form-group forgot">
			<?php echo CHtml::link(Yii::t('admin', 'Password forgotten?'), array('site/recoverPassword')) ?>
		</div>
	
		<div class="form-group buttons" style="text-align: center;">
			<?php echo CHtml::htmlButton('<span class="glyphicon glyphicon-log-in"></span> ' . Yii::t('admin', 'Login'),array('id' => 'submitButton', 'class' => 'btn btn-block btn-primary')); ?>
			<?php
				$text = Yii::t('admin', 'Connecting ...');
				Yii::app()->clientScript->registerScript('submit',"
		
					$('#submitButton').on('click', function(){
						$(this).prop('disabled', true).addClass('disabled').html('<span class=\"glyphicon glyphicon-refresh fa-spin\"></span> {$text}');
						$('#login-form').submit();
					});
					
				"); 
			?>	
		</div>
		
		<?php $this->endWidget(); ?>

		<div class="footer"><a href="http://www.bbwebconsult.com">By BbWebConsult</a></div>
		
		</div><!-- form -->

		
		
	</div>

</div><!-- page -->

</body>
</html>



