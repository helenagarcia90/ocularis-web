<div id="lang_switch">
<?php 
foreach($links as $lang => $link):

	if($lang === Yii::app()->language)
		echo Yii::app()->languageManager->langs[$lang] . " &nbsp; ";
	else
		echo CHtml::link(Yii::app()->languageManager->langs[$lang],$link) . " &nbsp; ";

endforeach;
?>
</div>