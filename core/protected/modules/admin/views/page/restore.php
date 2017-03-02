<p>
<?php
	if(isset($models) && count($models) === 1)
    	echo Yii::t('page', "Are you sure you want to restore the page <strong>{page}</strong>?", array('{page}' => $models[0]->title));
	else
    	echo Yii::t('page', 'Are you sure you want to restore the {n} pages?', count($models));
?>
</p>
<?php 
echo CHtml::hiddenField('ids',$ids);