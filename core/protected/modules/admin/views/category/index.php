<?php $this->widget('ModelGridView', array(
	'id'=>'page-grid',
	'dataProvider' => $dataProvider,

	'actions' => array(
			'update',
			Yii::t('page', 'Details') => array(
					'url' => 'Yii::app()->controller->createUrl("index",array("id_category" => $data->id_category))',
					'icon' => '<span class="glyphicon glyphicon-eye-open"></span>',
			),
			'delete',
	
	),
		
	'columns'=>array(

		array(
				'name' =>'name',
				'badge' => '$data->categoriesCount',
				'badgeUrl' => 'Yii::app()->controller->createUrl("index",array("id_category" => $data->id_category))',
		),
		'description',

	),
)); ?>