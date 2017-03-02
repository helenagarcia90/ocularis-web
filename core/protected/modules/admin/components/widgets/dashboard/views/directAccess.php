<div class="panel panel-primary">

<div class="panel-heading">
	<?=Yii::t('dashboard', 'Direct Access')?>
</div>

<div class="panel-body">

	<?=CHtml::link(Yii::t('dashboard', 'New page'), array('page/create'), array('class' => 'btn btn-primary btn-block'))?>

	<?php foreach(Blog::model()->findAll() as $blog):?>
			<?=CHtml::link(Yii::t('dashboard', 'New post in {blog}', array('{blog}' => $blog->name)), array('post/create', 'id_blog' => $blog->id_blog), array('class' => 'btn btn-primary btn-block'))?>
	<?php endforeach;?>
	
</div>

</div>