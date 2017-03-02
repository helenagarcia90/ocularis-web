<p>
        <?php
		if (isset ( $models ) && count ( $models ) === 1)
			echo Yii::t ( 'page', "Are you sure you want to delete the category <strong>{category}</strong>?", array (
					'{category}' => $models [0]->name 
			) );
		else
			echo Yii::t ( 'page', 'Are you sure you want to delete the {n} categories?', count ( $models ) );
		?>
</p>
<?= CHtml::hiddenField('ids',$ids);?>