        <p>
        <?php
	        if(isset($models) && count($models) === 1)
	        	echo Yii::t('admin', "Are you sure you want to delete the administrator <strong>{admin}</strong>?", array('{admin}' => $models[0]->name));
	        else
	        	echo Yii::t('admin', 'Are you sure you want to delete the {n} admins?', count($models));
        
        ?>
        </p>
        <?= CHtml::hiddenField('ids',$ids);?>
