<?php

$form->fieldGroup('dropDownList', $model, 'template', array('prompt' => Yii::t('app', 'Default'), 'data' => $alternateTemplates));

foreach($extraOptions as $name => $extra)
{
	echo FormHelper::renderFormElement($extra, "PageExtraOption[$name]", CHtml::value($extras[$name], 'value'));
}

?>