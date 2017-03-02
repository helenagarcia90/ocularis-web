
<p>
        <?php
								if (isset ( $models ) && count ( $models ) === 1)
									echo Yii::t ( 'menu', "Are you sure you want to delete the menu <strong>{menu}</strong>?", array (
											'{menu}' => $models [0]->name 
									) );
								else
									echo Yii::t ( 'menu', 'Are you sure you want to delete the {n} menus?', count ( $models ) );
								
								?>
        </p>
<?= CHtml::hiddenField('ids',$ids);?>