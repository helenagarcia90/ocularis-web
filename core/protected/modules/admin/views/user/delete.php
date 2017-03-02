<p>
<?php
	if(isset($models) && count($models) === 1)
		echo Yii::t('user', "Are you sure you want to delete user <strong>{user}</strong>?", array('{user}' => $models[0]->username));
	else
		echo Yii::t('user', 'Are you sure you want to delete the {n} users?', count($models));?>
</p>
<?=CHtml::hiddenField('ids',$ids);?>