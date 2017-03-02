<?php 
$form->fieldGroup('textField', $model, 'name');
$form->fieldGroup('aliasMaker', $model, 'alias', array('referenceAttribute' => 'name' ));

if(count(Yii::app()->languageManager->langs)>1)
{
	$form->fieldGroup('dropDownList', $model, 'lang', array('data' => Lang::getListData() ));
}

?>

