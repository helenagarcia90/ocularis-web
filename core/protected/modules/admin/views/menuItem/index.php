<?php
/* @var $model MenuItem */


$this->widget('ModelGridView', array(
	'id'=>'menuitem-grid',
	'dataProvider' => $dataProvider,
		
	'actions' => array(
		'update',
		Yii::t('menu', 'Details') => array(
				'url' => 'Yii::app()->controller->createUrl("index",array("id_menu_item" => $data->id_menu_item))',
				'icon' => '<span class="glyphicon glyphicon-eye-open"></span>',
		),
		'delete',
	
	),
		

	'columns'=>array(
					
		array(
				'name' => 'label',
				'value' => function($data){
					$output = $data->label .
					CHtml::link("<span class=\"badge pull-right\">$data->itemsCount</span>",Yii::app()->controller->createUrl("index",array("id_menu_item" => $data->id_menu_item)));
						
					return $output;
				},
				'type' => 'raw',
		),
	
		array( 
				'name' => 'position',
				'header' => Yii::t('menu', 'Position'),
				'htmlOptions' => array('class' => 'text-right col-sm'),
			)
		
	),
)); ?>