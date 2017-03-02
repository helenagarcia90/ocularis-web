

<div id="news">

<h2>Blog</h2>


<?php 
foreach($items as $item):

$exerpt = '';

?>
	<div class="news_block">
	<span class="date"><?= Yii::app()->locale->dateFormatter->format("d",$item->date); ?><span class="month"><?= strtoupper(Yii::app()->locale->dateFormatter->format("LLL",$item->date)); ?></span><?= Yii::app()->locale->dateFormatter->format("yy",$item->date); ?></span>
	
	<p class="content">
	<?=$item->exerpt?>
	</p>
	
	<a href="<?=$item->link?>" target="_blank" class="link right small">Llegir més</a>
	
	<div class="clear"></div>
	</div>

	<?php 
endforeach;
?>


</div>

