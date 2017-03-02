<?php 
$page = $data->page;
	
$date = strtotime($page->published_date);

?>

	<div class="news_block">
	<span class="date"><?= Yii::app()->locale->dateFormatter->format("d",$date); ?><span class="month"><?= strtoupper(Yii::app()->locale->dateFormatter->format("LLL",$date)); ?></span><?= Yii::app()->locale->dateFormatter->format("yy",$date); ?></span>
	
	<p class="content">
	<?=$page->excerpt;?>
	</p>
	
	<a href="<?=Yii::app()->createUrl('page/index',array('id' => $page->id_page))?>" class="link right small">Llegir més</a>
	
	<div class="clear"></div>
	</div>