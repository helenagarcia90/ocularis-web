<?php
/* @var $this PageController */
?>
<?php $this->layout = '//layouts/blog';?>
<article class="content">
		<h1><?=$page->title;?></h1>
		<div class="date">
			<?php
			$time = strtotime($page->published_date);
			$day = date("j", $time);
			$month = Yii::app()->locale->getMonthName(date("n", $time),'abbreviated');
			$year = date("Y", $time);
			echo $day . '&nbsp;' .$month . '&nbsp;' . $year;
			?>
		</div>
		<?php $this->renderPartial('//includes/share', array('url' => $this->createAbsoluteUrl('/page/index',array('id' => $page->id_page)), 'text' => $page->title));?>
		<?=$page->content;?>
</article>