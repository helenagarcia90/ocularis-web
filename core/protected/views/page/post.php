<?php
/* @var $this PageController */
?>
<?php $this->layout = '//layouts/blog';?>
<article>
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
		<div class="share">
			<a href="https://twitter.com/share" class="twitter-share-button" data-size="large">Tweet</a>
			<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
		</div>
		<?=$page->content;?>
</article>