<div id="news">

<h2><?=$widget->title;?></h2>

<?php 
$this->widget("zii.widgets.CListView",
	array(
		'dataProvider' => $blogPages,
		'itemView' => '_blogItem',
		'viewData' => array('truncate' => $truncate),
		'enablePagination' => false,
		'template' => '{items}',
	)
);
?>

<a href="<?=Yii::app()->createUrl('page/blog', array('id' => $widget->id_blog));?>" class="link">Més noticies</a>

</div>
