<p>
<?php
switch ($status) {
	
	case Page::STATUS_TRASH :
		if (isset ( $models ) && count ( $models ) === 1)
			echo Yii::t ( 'page', "Are you sure you want to send the page <strong>{page}</strong> to trash?", array (
					'{page}' => $models [0]->title 
			) );
		else
			echo Yii::t ( 'page', 'Are you sure you want to send the {n} pages to trash?', count ( $models ) );
		break;
	
	case Page::STATUS_PUBLISHED :
		if (isset ( $models ) && count ( $models ) === 1)
			echo Yii::t ( 'page', "Publish <strong>{page}</strong> ?", array (
					'{page}' => $models [0]->title 
			) );
		else
			echo Yii::t ( 'page', 'Publish the {n} pages?', count ( $models ) );
		break;
	
	case Page::STATUS_UNPUBLISHED :
		if (isset ( $models ) && count ( $models ) === 1)
			echo Yii::t ( 'page', "Unpublish <strong>{page}</strong> ?", array (
					'{page}' => $models [0]->title 
			) );
		else
			echo Yii::t ( 'page', 'Unpublish the {n} pages?', count ( $models ) );
		break;
	
	case Page::STATUS_ARCHIVED :
		if (isset ( $models ) && count ( $models ) === 1)
			echo Yii::t ( 'page', "Archive <strong>{page}</strong> ?", array (
					'{page}' => $models [0]->title 
			) );
		else
			echo Yii::t ( 'page', 'Archive the {n} pages?', count ( $models ) );
		break;
}
?>
</p>
<?= CHtml::hiddenField('ids',$ids);?>
      