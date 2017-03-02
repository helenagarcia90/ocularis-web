<p>
<?php
	if(isset($models) && count($models) === 1)
    	echo Yii::t('blog', "Are you sure you want to permanetly delete the blog <strong>{blog}</strong>?", array('{blog}' => $models[0]->name));
	else
		echo Yii::t('blog', 'Are you sure you want to permanetly delete the {n} blogs?', count($models));?>
</p>
<?=CHtml::hiddenField('ids',$ids);?>