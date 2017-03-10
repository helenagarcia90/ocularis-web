<?php
/* @var $this SiteController */
/* @var $model ContactForm */
/* @var $form CActiveForm */

Yii::app()->clientScript->registerCss('captcha','

.captcha .form-control-feedback
{
	top: 75px;
}

');

$this->pageTitle=Yii::t('contact', 'Contact Us');

/*$this->breadcrumbs=array(
	Yii::t('contact', 'Contact Us')
);*/
?>

<div class="jumbotron bottom0">

    <div class="container">
        <a href="<?= Yii::app()->baseUrl; ?>/es/hazte-socio" class="mascota"><img src="<?=Yii::app()->theme->baseUrl. '/images/mascota.png';?>"></a>
        <!--p>LOREM IPSUM DOLOR SIT AMET, CONSECTETUER ADIPISCING ELIT, <br />
            SED DIAM NONUMMY NIBH EUISMOD TINCIDUNT UT LAOREET</p-->
    </div>

</div>


<div class="bgGreen no-lift">

    <div class="container">
        <div class="row">

            <div class="col-md-8">

                <h1><?=Yii::t('contact', 'Contact Us')?></h1>
                <?php if($success): ?>

                <div class="alert alert-success">
                    <p><?=Yii::t('contact', 'Thank you for contacting us. We will respond to you as soon as possible.')?></p>
                </div>
                <p><?=CHtml::link(Yii::t('contact', 'Go back home'), array('/site/index'), array('class' => 'btn btn-primary'))?></p>

                <?php else: ?>

                <div class="form">

                <?php

                $form=$this->beginWidget('CActiveForm', array(
                    'id'=>'contact-form',
                    'enableClientValidation'=>true,
                    'clientOptions'=>array(
                        'validateOnSubmit'=>true,
                        'errorCssClass' => 'has-error has-feedback',
                        'successCssClass' => 'has-success has-feedback',
                        'errorMessageCssClass' => 'alert alert-danger',
                        'afterValidateAttribute' => "js: function(form, attribute, data, hasError) {

                                $('#'+attribute.id).parent().find('.form-control-feedback').remove();

                                if(!hasError)
                                    $('#'+attribute.id).after('<span class=\"glyphicon glyphicon-ok form-control-feedback\"></span>');
                                else
                                    $('#'+attribute.id).after('<span class=\"glyphicon glyphicon-remove form-control-feedback\"></span>');

                            }",

                            'afterValidate' => "js: function(form, data,  hasError) {

                                $('.form-group',form).each(function(){

                                    $('.form-control-feedback',this).remove();

                                    if($(this).hasClass('has-success'))
                                        $(this).append('<span class=\"glyphicon glyphicon-ok form-control-feedback\"></span>');
                                    else
                                        $(this).append('<span class=\"glyphicon glyphicon-remove form-control-feedback\"></span>');

                                });
                                return !hasError;
                            }"
                    ),
                )); ?>

                    <p class="note"><?= Yii::t('form', 'Fields with <span class="required">*</span> are required.');?></p>

                    <div class="form-group">
                        <?php echo $form->labelEx($model,'name', array('class' => 'control-label')); ?>
                        <?php echo $form->textField($model,'name', array('class' => 'form-control')); ?>
                        <?php echo $form->error($model,'name', array('style' => 'margin-top: 10px;', 'class' => 'alert alert-danger')); ?>
                    </div>

                    <div class="form-group">
                        <?php echo $form->labelEx($model,'email', array('class' => 'control-label')); ?>
                        <?php echo $form->textField($model,'email', array('class' => 'form-control')); ?>
                        <?php echo $form->error($model,'email', array('style' => 'margin-top: 10px;', 'class' => 'alert alert-danger')); ?>
                    </div>

                    <div class="form-group">
                        <?php echo $form->labelEx($model,'subject', array('class' => 'control-label')); ?>
                        <?php echo $form->textField($model,'subject',array('class' => 'form-control', 'size'=>60,'maxlength'=>128)); ?>
                        <?php echo $form->error($model,'subject', array('style' => 'margin-top: 10px;', 'class' => 'alert alert-danger')); ?>
                    </div>

                    <div class="form-group">
                        <?php echo $form->labelEx($model,'body', array('class' => 'control-label')); ?>
                        <?php echo $form->textArea($model,'body',array('class' => 'form-control', 'rows'=>6, 'cols'=>50)); ?>
                        <?php echo $form->error($model,'body', array('style' => 'margin-top: 10px;', 'class' => 'alert alert-danger')); ?>
                    </div>

                    <?php if(CCaptcha::checkRequirements()): ?>
                    <div class="form-group captcha">
                        <?php echo $form->labelEx($model,'verifyCode'); ?>
                        <div>
                                <?php $this->widget('CCaptcha',array(
                                'buttonOptions' => array('class' => 'btn btn-default'),
                                'buttonLabel' => '<span class="glyphicon glyphicon-refresh"></span>',
                        )); ?>
                            </div>
                        <?php echo $form->textField($model,'verifyCode', array('class' => 'form-control')); ?>
                        <?php echo $form->error($model,'verifyCode', array('style' => 'margin-top: 10px;', 'class' => 'alert alert-danger')); ?>
                    </div>
                    <?php endif; ?>

                    <div class="buttons">
                        <?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary')); ?>
                    </div>

                <?php $this->endWidget(); ?>

                </div><!-- form -->

                <?php endif; ?>

            </div>

        </div>
    </div>
</div>
