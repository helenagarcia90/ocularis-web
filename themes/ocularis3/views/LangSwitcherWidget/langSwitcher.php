<?php
$l = array(
	'fr' => 'FR',
	'en' => 'EN',
	'ca' => 'CAT',
	'es' => 'ES'
);
?>

<ul id="langswitch" class="list-unstyled">
  <?php $i = 0; ?>
	<?php foreach($l as $lang => $link):
      if ($lang !== Yii::app()->language) {
        echo CHtml::tag('li', array(), CHtml::link($link, $links[$lang]));
      }
    ?>
  <?php endforeach;?>
</ul>