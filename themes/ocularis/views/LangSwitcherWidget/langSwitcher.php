<?php 
$l = array(
	'fr' => 'FR',
	'en' => 'EN',
	'ca' => 'CAT',
	'es' => 'ES'
);
?>

<ul id="lang_switcher">
		<?php 
		foreach($links as $lang => $link):
		
			if($lang === Yii::app()->language)
				echo CHtml::tag('li',array('class' => 'active'),$l[$lang]);
			else
				echo CHtml::tag('li',array(),CHtml::link($l[$lang],$link));
		
		endforeach;
		?>
</ul>
