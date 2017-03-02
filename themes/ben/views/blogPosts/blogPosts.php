<?php foreach($posts as $post): ?>
	<div class="post">
		<h2><?=CHtml::link($post->title,Yii::app()->createUrl('/page/index',array('id' => $post->id_page)));?><?= (strtotime($post->published_date) >= strtotime('now - 7 days') ? '&nbsp;&nbsp;<small><span class="label label-default">New</span>' : '');?></small></h2>
		<div class="date">
			<?php
			$time = strtotime($post->published_date);
			$day = date("j", $time);
			$month = Yii::app()->locale->getMonthName(date("n", $time),'abbreviated');
			$year = date("Y", $time);
			echo $day . '&nbsp;' .$month . '&nbsp;' . $year;
			//Yii::app()->locale->dateFormatter->formatDateTime($post->published_date,"medium", "short")
			?>
		</div>
		<div class="share">
			<a href="https://twitter.com/share" class="twitter-share-button" data-text="<?=$post->title?>" data-url="<?=Yii::app()->createAbsoluteUrl('/page/index',array('id' => $post->id_page));?>?>" data-size="large">Tweet</a>
			<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
			

		</div>
		<?php
		$this->beginWidget('modules.codeParser.CodeParser');
		
		echo $post->excerpt;
		
		$this->endWidget();
		?>
		<div class="bottom">
			<?=CHtml::link('Read more', array('/page/index', 'id' => $post->id_page), array('class' => 'btn btn-primary	'))?>
		</div>
	</div>
<?php endforeach;

$this->widget('modules.blogWidget.components.widgets.BlogPager', array(
    'pages' => $pages,
	'htmlOptions' => array('class' => 'pager'), 
)) ?>