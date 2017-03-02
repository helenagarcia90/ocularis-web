

<div id="news">

<h2>News</h2>


<?php 
foreach($items as $item):

$exerpt = '';

?>

	<div class="news_block">
	
	<span class="date"><?= Yii::app()->locale->dateFormatter->format("d",$item->date); ?><span class="month"><?= strtoupper(Yii::app()->locale->dateFormatter->format("LLL",$item->date)); ?></span><?= Yii::app()->locale->dateFormatter->format("yy",$item->date); ?></span>
	<h3><?=CHtml::link($item->title,$item->link,array('target' => 'blank'))?></h3>
	
	<p class="content">
	<?=$item->exerpt?> <a href="<?=$item->link?>" target="_blank" class="btn btn-xs btn-primary">Leer más</a>
	</p>
	
	
	<div class="clearfix"></div>
	</div>

	<?php 
endforeach;
?>


</div>

