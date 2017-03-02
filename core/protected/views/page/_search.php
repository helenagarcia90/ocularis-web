<div class="post">
		<h2><?=CHtml::link($data->title,Yii::app()->createUrl('/page/index',array('id' => $data->id_page)));?></h2>
		
		<?php
			echo $data->excerpt;
		?>
		<div class="bottom">
			<?=CHtml::link('Read more', array('/page/index', 'id' => $data->id_page), array('class' => 'btn btn-primary	'))?>
		</div>
	</div>