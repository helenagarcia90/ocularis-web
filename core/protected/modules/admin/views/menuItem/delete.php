
<p>
        <?php
								if (isset ( $models ) && count ( $models ) === 1)
									echo Yii::t ( 'menu', "Are you sure you want to delete the menu item <strong>{item}</strong>?", array (
											'{item}' => $models [0]->label 
									) );
								else
									echo Yii::t ( 'menu', 'Are you sure you want to delete the {n} menu items?', count ( $models ) );
								
								?>
        </p>
<?= CHtml::hiddenField('ids',$ids);?>
      