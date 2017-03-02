<?php

$buttons = CHtml::submitButton(Yii::t('app', 'Cancel'),array('class' => 'btn btn-danger', 'data-dismiss' => 'modal',  'name' => 'cancel')). ' ' . CHtml::submitButton(Yii::t('app', 'Confirm'),array('class' => 'btn btn-success', 'name' => 'confirm'));

$form=$this->beginWidget('CActiveForm', array(
		'id' => 'modal-form',
		'action' => isset($formAction) ? $formAction : Yii::app()->request->requestUri,
));
	
	$this->beginContent('application.modules.admin.views.layouts.modal', array('title' => Yii::t('app', 'Confirm'), 'buttons' => $buttons));
	
		if(Yii::app()->user->hasFlash('warning')):
			?>
			<div class="alert alert-warning">
				<?php if(is_array($warnings = Yii::app()->user->getFlash('warning'))): ?>
					<?php foreach($warnings as $warning):?>
					<p><?=$warning;?></p>
					<?php endforeach;?>
				<?php else:?>
					<?=$warnings;?>
				<?php endif;?>
			</div>
			<?php 
		endif;
		
		echo $content;
	
	$this->endContent();

$this->endWidget();