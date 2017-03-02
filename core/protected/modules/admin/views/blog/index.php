<?php
/* @var $model Blog */

$this->widget('ModelGridView', array(
	'id'=>'blog-grid',
	'dataProvider' => $dataProvider,
	'actions' => array(
			'update',
			Yii::t('page', 'Details') => array(
				'url' => 'Yii::app()->controller->createUrl("post/index",array("id_blog" => $data->id_blog))',
				'icon' => '<span class="glyphicon glyphicon-eye-open"></span>',
			),
			'delete'
	),
		
	'columns'=>array(

		'name',
		array(
			'header' => Yii::t('blog','# Posts'),
			'tooltip' => Yii::t('blog','Number of posts'),
			'value' => 'CHtml::link("<span class=\"badge\">".$data->blogPagesCount."</span>",Yii::app()->controller->createUrl("post/index",array("id_blog" => $data->id_blog)))',
			'htmlOptions' => array('class' => 'col-sm text-center'),
			'headerHtmlOptions' => array('class' => 'col-sm text-center'),
			'type' => 'raw',
		),
		array(
			'class' => 'LanguageColumn'
		)


	),
)); ?>