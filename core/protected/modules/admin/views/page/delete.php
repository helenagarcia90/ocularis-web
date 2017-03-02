<p>
<?php
if(isset($models) && count($models) === 1)
	echo Yii::t('page', "Are you sure you want to permanetly delete the page <strong>{page}</strong>?", array('{page}' => $models[0]->title));
else
    echo Yii::t('page', 'Are you sure you want to permanetly delete the {n} pages?', count($models));
?>
</p>
<?=CHtml::hiddenField('ids',$ids);?>

 