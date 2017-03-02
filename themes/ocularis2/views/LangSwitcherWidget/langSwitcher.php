<?php 
$l = array(
	'fr' => 'FR',
	'en' => 'EN',
	'ca' => 'CAT',
	'es' => 'ES'
);
?>

<ul class="nav navbar-nav navbar-right">
	<li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?= $l[Yii::app()->language];?> <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
          	<?php foreach($links as $lang => $link): ?>
            	<?=CHtml::tag('li',array(),CHtml::link($l[$lang],$link));?>
            <?php endforeach;?>
          </ul>
        </li>
</ul>