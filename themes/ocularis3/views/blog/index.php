<?php $this->layout = '//layouts/blog';?>
<?php foreach($posts as $post): ?>
		<div class="content">
			<h2><?=CHtml::link($post->title,Yii::app()->createUrl('/page/index',array('id' => $post->id_page)));?></h2>
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
			<?php $this->renderPartial('//includes/share', array('url' => $this->createAbsoluteUrl('/page/index',array('id' => $post->id_page)), 'text' => $post->title));?>
			<?php
				echo $post->excerpt;
			?>
			<div class="bottom">
				<?=CHtml::link('Read more', array('/page/index', 'id' => $post->id_page), array('class' => 'btn btn-primary	'))?>
			</div>
		</div>
	<?php endforeach;
	
	$this->widget('CLinkPager', array(
		'cssFile' => false,
	    'page' => $pages,
		'htmlOptions' => array('class' => 'pager'),
	)) ?>

