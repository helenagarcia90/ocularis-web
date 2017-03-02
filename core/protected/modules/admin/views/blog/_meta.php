<?php 
	$form->fieldGroup('textField', $model, 'meta_title');
	$form->fieldGroup('textField', $model, 'meta_keywords');
	$form->fieldGroup('textArea', $model, 'meta_description');
	$form->fieldGroup('dropDownList', $model, 'meta_robots', array('data' => Helper::getMetaRobotsListData()));
?>