<?php

$form->fieldGroup('textField', $model, 'name');

$form->fieldGroup('aliasMaker', $model, 'anchor', array ( 'referenceAttribute' => 'name') ); 

if(count(Yii::app()->languageManager->langs)>1)
	$form->fieldGroup('dropDownList', $model, 'lang', array('data' => Lang::getListData()));



$form->fieldGroup('switcher', $model, 'active', array(
										'items' => array(
										array('value' => 1,
											'label' => Yii::t('app', 'Enabled'),
											'color' => 'btn-success'),

										array('value' => 0,
												'label' => Yii::t('app', 'Disabled'),
												'color' => 'btn-danger'),
										
									)));


?>

