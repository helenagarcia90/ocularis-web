<?php
/* @var $model Menu */

$this->widget('ModelGridView', array(
	'id'=>'menu-grid',
	
	'dataProvider' => $dataProvider,
	
	'actions' => array(
			'update',
			Yii::t('menu', 'Details') => array(
					'url' => 'Yii::app()->controller->createUrl("menuItem/index",array("id_menu" => $data->id_menu))',
					'icon' => '<span class="glyphicon glyphicon-eye-open"></span>',
			),
			'delete',
	),

	'columns'=>array(
			
		array(
				'name' => 'name',
				'value' => function($data){
						$output = $data->name .
						CHtml::link("<span class=\"badge pull-right\">$data->menuItemsCount</span>",Yii::app()->controller->createUrl("menuItem/index",array("id_menu" => $data->id_menu)));
							
						return $output;
				},
				'type' => 'raw',
		),
			
		'anchor',
		
		array(
			'class' => 'LanguageColumn',
		),
		
		
	),
)); ?>