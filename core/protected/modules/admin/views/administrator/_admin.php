<?php 
	$form->fieldGroup('textField', $model, 'name');
	$form->fieldGroup('textField', $model, 'email');
	$form->fieldGroup('passwordField', $model, 'enter_password');
	$form->fieldGroup('passwordField', $model, 'repeat_password');
	$form->fieldGroup('dropDownList', $model, 'lang', array('data' => Yii::app()->params->adminLangs));
	$form->fieldGroup('dropDownList', $model, 'editor', array('data' => Editor::getList(), 'prompt' => false));
	?>
